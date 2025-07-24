<?php

declare(strict_types=1);

namespace app\Services\Security;

use think\Request;
use Psr\Log\LoggerInterface;
use app\Services\System\ConfigManager;

/**
 * CORS (跨域资源共享) 服务
 * 
 * 负责处理跨域请求的安全验证和响应头设置
 * 基于配置驱动，支持灵活的CORS策略配置
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class CorsService
{
    private ConfigManager $configManager;
    private LoggerInterface $logger;

    /**
     * 构造函数
     *
     * @param LoggerInterface $logger 日志实例
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->configManager = ConfigManager::getInstance();
        $this->logger = $logger;
    }

    /**
     * 处理CORS请求
     *
     * @param Request $request HTTP请求实例
     * @return void
     */
    public function handleCors(Request $request): void
    {
        $origin = $request->header('Origin', '');
        $method = $request->method();

        // 验证来源域名
        if ($this->isOriginAllowed($origin)) {
            $this->setAllowOriginHeader($origin);
        } else {
            $this->logger->warning('CORS: 不被允许的来源域名', [
                'origin' => $origin,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent', '')
            ]);
        }

        // 设置允许的HTTP方法
        $this->setAllowMethodsHeader();

        // 设置允许的请求头
        $this->setAllowHeadersHeader();

        // 设置是否允许携带凭证
        $this->setAllowCredentialsHeader();

        // 设置预检请求缓存时间
        $this->setMaxAgeHeader();

        // 处理预检请求
        if ($method === 'OPTIONS') {
            $this->handlePreflightRequest($request);
        }

        $this->logger->info('CORS处理完成', [
            'origin' => $origin,
            'method' => $method,
            'allowed' => $this->isOriginAllowed($origin)
        ]);
    }

    /**
     * 检查来源域名是否被允许
     *
     * @param string $origin 来源域名
     * @return bool 是否允许
     */
    private function isOriginAllowed(string $origin): bool
    {
        if (empty($origin)) {
            return false;
        }

        $corsConfig = $this->configManager->getCorsConfig();
        $allowedOrigins = $corsConfig['allowed_origins'];

        // 如果配置为 * 则允许所有域名
        if ($allowedOrigins === '*') {
            return true;
        }

        // 如果是数组，检查是否在允许列表中
        if (is_array($allowedOrigins)) {
            return in_array($origin, $allowedOrigins, true);
        }

        // 如果是字符串，按逗号分割后检查
        if (is_string($allowedOrigins)) {
            $origins = array_map('trim', explode(',', $allowedOrigins));
            return in_array($origin, $origins, true);
        }

        return false;
    }

    /**
     * 设置 Access-Control-Allow-Origin 头
     *
     * @param string $origin 允许的来源域名
     * @return void
     */
    private function setAllowOriginHeader(string $origin): void
    {
        $corsConfig = $this->configManager->getCorsConfig();
        $allowedOrigins = $corsConfig['allowed_origins'];

        if ($allowedOrigins === '*') {
            header('Access-Control-Allow-Origin: *');
        } else {
            header("Access-Control-Allow-Origin: {$origin}");
        }
    }

    /**
     * 设置 Access-Control-Allow-Methods 头
     *
     * @return void
     */
    private function setAllowMethodsHeader(): void
    {
        $corsConfig = $this->configManager->getCorsConfig();
        $allowedMethods = $corsConfig['allowed_methods'];
        header("Access-Control-Allow-Methods: {$allowedMethods}");
    }

    /**
     * 设置 Access-Control-Allow-Headers 头
     *
     * @return void
     */
    private function setAllowHeadersHeader(): void
    {
        $corsConfig = $this->configManager->getCorsConfig();
        $allowedHeaders = $corsConfig['allowed_headers'];
        header("Access-Control-Allow-Headers: {$allowedHeaders}");
    }

    /**
     * 设置 Access-Control-Allow-Credentials 头
     *
     * @return void
     */
    private function setAllowCredentialsHeader(): void
    {
        $corsConfig = $this->configManager->getCorsConfig();
        $allowCredentials = $corsConfig['allow_credentials'] ? 'true' : 'false';
        header("Access-Control-Allow-Credentials: {$allowCredentials}");
    }

    /**
     * 设置 Access-Control-Max-Age 头
     *
     * @return void
     */
    private function setMaxAgeHeader(): void
    {
        $corsConfig = $this->configManager->getCorsConfig();
        $maxAge = $corsConfig['max_age']; // 从配置管理器获取
        header("Access-Control-Max-Age: {$maxAge}");
    }

    /**
     * 处理预检请求 (OPTIONS)
     *
     * @param Request $request HTTP请求实例
     * @return void
     */
    private function handlePreflightRequest(Request $request): void
    {
        $this->logger->info('处理CORS预检请求', [
            'origin' => $request->header('Origin', ''),
            'method' => $request->header('Access-Control-Request-Method', ''),
            'headers' => $request->header('Access-Control-Request-Headers', '')
        ]);

        // 设置响应状态码
        http_response_code(200);
        
        // 结束请求处理
        exit;
    }

    /**
     * 获取CORS配置摘要
     *
     * @return array CORS配置信息
     */
    public function getCorsConfig(): array
    {
        return [
            'allowed_origins' => $this->config->get('cors.allowed_origins', '*'),
            'allowed_methods' => $this->config->get('cors.allowed_methods', 'GET,POST,PUT,DELETE,OPTIONS'),
            'allowed_headers' => $this->config->get('cors.allowed_headers', 'Content-Type,Authorization,X-Requested-With'),
            'allow_credentials' => $this->config->get('cors.allow_credentials', 'true'),
            'max_age' => $this->config->get('cors.max_age', '86400')
        ];
    }
}
