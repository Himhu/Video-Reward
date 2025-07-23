<?php
// +----------------------------------------------------------------------
// | 环境服务类
// +----------------------------------------------------------------------
// | 功能：处理环境初始化和配置管理
// | 职责：单一职责原则 - 专门处理环境相关逻辑
// | 设计：可配置的环境管理方案
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace App\Services\System;

use App\Exceptions\EnvironmentException;

/**
 * 环境服务类
 * 
 * 负责应用环境的初始化和管理：
 * - 全局常量定义
 * - 环境变量加载
 * - 路径配置管理
 * - 运行环境检测
 * 
 * @package App\Services\System
 * @author Video-Reward Team
 * @version 1.0.0
 */
class EnvironmentService
{
    /**
     * 环境类型常量
     */
    public const ENV_PRODUCTION = 'production';
    public const ENV_DEVELOPMENT = 'development';
    public const ENV_TESTING = 'testing';

    /**
     * 当前环境类型
     */
    private string $environment;

    /**
     * 是否已初始化
     */
    private bool $initialized = false;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->environment = $this->detectEnvironment();
    }

    /**
     * 初始化环境
     * 
     * @throws EnvironmentException
     */
    public function initialize(): void
    {
        if ($this->initialized) {
            return;
        }

        try {
            // 定义全局常量
            $this->defineConstants();
            
            // 加载环境变量
            $this->loadEnvironmentVariables();
            
            // 设置错误报告级别
            $this->configureErrorReporting();
            
            // 设置时区
            $this->configureTimezone();
            
            // 设置内存限制
            $this->configureMemoryLimit();

            $this->initialized = true;

        } catch (\Throwable $e) {
            throw new EnvironmentException(
                '环境初始化失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 定义全局常量
     */
    private function defineConstants(): void
    {
        // 目录分隔符
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }

        // 根路径
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', dirname(__DIR__, 3) . DS);
        }

        // 应用路径
        if (!defined('APP_PATH')) {
            define('APP_PATH', ROOT_PATH . 'app' . DS);
        }

        // 配置路径
        if (!defined('CONFIG_PATH')) {
            define('CONFIG_PATH', ROOT_PATH . 'config' . DS);
        }

        // 运行时路径
        if (!defined('RUNTIME_PATH')) {
            define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);
        }

        // 公共路径
        if (!defined('PUBLIC_PATH')) {
            define('PUBLIC_PATH', ROOT_PATH . 'public' . DS);
        }

        // 上传路径
        if (!defined('UPLOAD_PATH')) {
            define('UPLOAD_PATH', PUBLIC_PATH . 'upload' . DS);
        }

        // 应用版本
        if (!defined('APP_VERSION')) {
            define('APP_VERSION', $this->getAppVersion());
        }

        // 环境类型
        if (!defined('APP_ENV')) {
            define('APP_ENV', $this->environment);
        }
    }

    /**
     * 加载环境变量
     */
    private function loadEnvironmentVariables(): void
    {
        $envFile = ROOT_PATH . '.env';
        
        if (file_exists($envFile)) {
            $this->parseEnvFile($envFile);
        }
    }

    /**
     * 解析环境变量文件
     * 
     * @param string $filePath 文件路径
     */
    private function parseEnvFile(string $filePath): void
    {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            // 跳过注释行
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // 解析键值对
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // 移除引号
                $value = trim($value, '"\'');

                // 设置环境变量
                if (!empty($key)) {
                    $_ENV[$key] = $value;
                    putenv("{$key}={$value}");
                }
            }
        }
    }

    /**
     * 配置错误报告
     */
    private function configureErrorReporting(): void
    {
        if ($this->isProduction()) {
            // 生产环境：关闭错误显示
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
        } else {
            // 开发环境：显示所有错误
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            ini_set('log_errors', '1');
        }
    }

    /**
     * 配置时区
     */
    private function configureTimezone(): void
    {
        $timezone = $_ENV['APP_TIMEZONE'] ?? 'Asia/Shanghai';
        date_default_timezone_set($timezone);
    }

    /**
     * 配置内存限制
     */
    private function configureMemoryLimit(): void
    {
        $memoryLimit = $_ENV['MEMORY_LIMIT'] ?? '256M';
        ini_set('memory_limit', $memoryLimit);
    }

    /**
     * 检测运行环境
     * 
     * @return string
     */
    private function detectEnvironment(): string
    {
        // 从环境变量获取
        $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? null;
        
        if ($env) {
            return $env;
        }

        // 根据域名判断
        $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
        
        if (strpos($host, 'localhost') !== false || 
            strpos($host, '127.0.0.1') !== false ||
            strpos($host, '192.168.') !== false) {
            return self::ENV_DEVELOPMENT;
        }

        // 默认为生产环境
        return self::ENV_PRODUCTION;
    }

    /**
     * 获取应用版本
     * 
     * @return string
     */
    private function getAppVersion(): string
    {
        $versionFile = ROOT_PATH . 'version';
        
        if (file_exists($versionFile)) {
            $version = trim(file_get_contents($versionFile));
            if (!empty($version)) {
                return $version;
            }
        }

        return '1.0.0';
    }

    /**
     * 检查是否为生产环境
     * 
     * @return bool
     */
    public function isProduction(): bool
    {
        return $this->environment === self::ENV_PRODUCTION;
    }

    /**
     * 检查是否为开发环境
     * 
     * @return bool
     */
    public function isDevelopment(): bool
    {
        return $this->environment === self::ENV_DEVELOPMENT;
    }

    /**
     * 检查是否为测试环境
     * 
     * @return bool
     */
    public function isTesting(): bool
    {
        return $this->environment === self::ENV_TESTING;
    }

    /**
     * 获取当前环境
     * 
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * 设置环境
     * 
     * @param string $environment 环境类型
     * @throws EnvironmentException
     */
    public function setEnvironment(string $environment): void
    {
        $validEnvironments = [
            self::ENV_PRODUCTION,
            self::ENV_DEVELOPMENT,
            self::ENV_TESTING
        ];

        if (!in_array($environment, $validEnvironments)) {
            throw new EnvironmentException("无效的环境类型: {$environment}");
        }

        $this->environment = $environment;
    }

    /**
     * 获取环境变量
     * 
     * @param string $key 变量名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function getEnv(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * 设置环境变量
     * 
     * @param string $key 变量名
     * @param mixed $value 变量值
     */
    public function setEnv(string $key, $value): void
    {
        $_ENV[$key] = $value;
        putenv("{$key}={$value}");
    }

    /**
     * 获取系统信息
     * 
     * @return array
     */
    public function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'os' => PHP_OS,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'timezone' => date_default_timezone_get(),
            'environment' => $this->environment,
            'app_version' => APP_VERSION ?? '1.0.0'
        ];
    }

    /**
     * 检查是否已初始化
     * 
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->initialized;
    }
}
