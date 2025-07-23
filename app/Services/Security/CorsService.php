<?php
// +----------------------------------------------------------------------
// | CORS安全服务类
// +----------------------------------------------------------------------
// | 功能：处理跨域资源共享(CORS)配置
// | 职责：单一职责原则 - 专门处理CORS相关逻辑
// | 设计：可配置、可扩展的CORS处理方案
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\SecurityException;

/**
 * CORS服务类
 * 
 * 负责处理跨域资源共享的配置和验证：
 * - 动态CORS头设置
 * - 安全的Origin验证
 * - 可配置的CORS策略
 * - 预检请求处理
 * 
 * @package App\Services\Security
 * @author Video-Reward Team
 * @version 1.0.0
 */
class CorsService
{
    /**
     * 默认CORS配置
     */
    private const DEFAULT_CONFIG = [
        'allowed_origins' => ['*'],
        'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        'allowed_headers' => [
            'Origin',
            'X-Requested-With',
            'Content-Type',
            'Accept',
            'Authorization',
            'x-file-name'
        ],
        'allow_credentials' => true,
        'max_age' => 86400, // 24小时
        'expose_headers' => []
    ];

    /**
     * CORS配置
     */
    private array $config;

    /**
     * 构造函数
     * 
     * @param array $config CORS配置
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(self::DEFAULT_CONFIG, $config);
        $this->loadConfigFromFile();
    }

    /**
     * 配置CORS头
     * 
     * @throws SecurityException
     */
    public function configure(): void
    {
        try {
            $origin = $this->getRequestOrigin();
            
            // 验证Origin
            if ($this->isOriginAllowed($origin)) {
                $this->setAllowOriginHeader($origin);
            }

            // 设置其他CORS头
            $this->setMethodsHeader();
            $this->setHeadersHeader();
            $this->setCredentialsHeader();
            $this->setMaxAgeHeader();
            $this->setExposeHeadersHeader();

            // 处理预检请求
            $this->handlePreflightRequest();

        } catch (\Throwable $e) {
            throw new SecurityException(
                'CORS配置失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 获取请求Origin
     * 
     * @return string
     */
    private function getRequestOrigin(): string
    {
        return $_SERVER['HTTP_ORIGIN'] ?? '';
    }

    /**
     * 检查Origin是否被允许
     * 
     * @param string $origin 请求Origin
     * @return bool
     */
    private function isOriginAllowed(string $origin): bool
    {
        // 如果配置为允许所有Origin
        if (in_array('*', $this->config['allowed_origins'])) {
            return true;
        }

        // 检查是否在允许列表中
        return in_array($origin, $this->config['allowed_origins']);
    }

    /**
     * 设置Allow-Origin头
     * 
     * @param string $origin 允许的Origin
     */
    private function setAllowOriginHeader(string $origin): void
    {
        if (in_array('*', $this->config['allowed_origins']) && !$this->config['allow_credentials']) {
            header('Access-Control-Allow-Origin: *');
        } elseif ($origin) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }
    }

    /**
     * 设置Allow-Methods头
     */
    private function setMethodsHeader(): void
    {
        $methods = implode(', ', $this->config['allowed_methods']);
        header('Access-Control-Allow-Methods: ' . $methods);
    }

    /**
     * 设置Allow-Headers头
     */
    private function setHeadersHeader(): void
    {
        $headers = implode(', ', $this->config['allowed_headers']);
        header('Access-Control-Allow-Headers: ' . $headers);
    }

    /**
     * 设置Allow-Credentials头
     */
    private function setCredentialsHeader(): void
    {
        if ($this->config['allow_credentials']) {
            header('Access-Control-Allow-Credentials: true');
        }
    }

    /**
     * 设置Max-Age头
     */
    private function setMaxAgeHeader(): void
    {
        if ($this->config['max_age'] > 0) {
            header('Access-Control-Max-Age: ' . $this->config['max_age']);
        }
    }

    /**
     * 设置Expose-Headers头
     */
    private function setExposeHeadersHeader(): void
    {
        if (!empty($this->config['expose_headers'])) {
            $headers = implode(', ', $this->config['expose_headers']);
            header('Access-Control-Expose-Headers: ' . $headers);
        }
    }

    /**
     * 处理预检请求
     */
    private function handlePreflightRequest(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    /**
     * 从配置文件加载CORS配置
     */
    private function loadConfigFromFile(): void
    {
        $configFile = ROOT_PATH . 'config' . DIRECTORY_SEPARATOR . 'cors.php';
        
        if (file_exists($configFile)) {
            $fileConfig = include $configFile;
            if (is_array($fileConfig)) {
                $this->config = array_merge($this->config, $fileConfig);
            }
        }
    }

    /**
     * 验证CORS配置
     * 
     * @return bool
     */
    public function validateConfig(): bool
    {
        // 验证必要的配置项
        $requiredKeys = ['allowed_origins', 'allowed_methods', 'allowed_headers'];
        
        foreach ($requiredKeys as $key) {
            if (!isset($this->config[$key]) || !is_array($this->config[$key])) {
                return false;
            }
        }

        return true;
    }

    /**
     * 获取当前配置
     * 
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 更新配置
     * 
     * @param array $config 新配置
     */
    public function updateConfig(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * 添加允许的Origin
     * 
     * @param string $origin Origin地址
     */
    public function addAllowedOrigin(string $origin): void
    {
        if (!in_array($origin, $this->config['allowed_origins'])) {
            $this->config['allowed_origins'][] = $origin;
        }
    }

    /**
     * 移除允许的Origin
     * 
     * @param string $origin Origin地址
     */
    public function removeAllowedOrigin(string $origin): void
    {
        $key = array_search($origin, $this->config['allowed_origins']);
        if ($key !== false) {
            unset($this->config['allowed_origins'][$key]);
            $this->config['allowed_origins'] = array_values($this->config['allowed_origins']);
        }
    }

    /**
     * 检查是否为安全的Origin
     * 
     * @param string $origin Origin地址
     * @return bool
     */
    public function isSecureOrigin(string $origin): bool
    {
        // 检查是否为HTTPS
        if (strpos($origin, 'https://') === 0) {
            return true;
        }

        // 检查是否为本地开发环境
        $localPatterns = [
            'http://localhost',
            'http://127.0.0.1',
            'http://192.168.',
            'http://10.',
            'http://172.'
        ];

        foreach ($localPatterns as $pattern) {
            if (strpos($origin, $pattern) === 0) {
                return true;
            }
        }

        return false;
    }
}
