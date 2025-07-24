<?php
/**
 * Video-Reward 系统常量定义
 * 
 * 统一定义系统中使用的常量，避免重复定义
 * 解决APP_PATH、ROOT_PATH等常量在多个文件中重复定义的问题
 * 
 * @author Video-Reward Team
 * @version 1.0
 * @since 2025-01-23
 */

declare(strict_types=1);

namespace app\Common;

class Constants
{
    /**
     * 系统版本信息
     */
    public const SYSTEM_NAME = 'Video-Reward';
    public const SYSTEM_VERSION = '2.0.0';
    public const SYSTEM_AUTHOR = 'Video-Reward Team';
    
    /**
     * PHP环境要求
     */
    public const MIN_PHP_VERSION = '7.1.0';
    public const RECOMMENDED_PHP_VERSION = '8.0.0';
    
    /**
     * 错误代码定义
     */
    public const ERROR_ENVIRONMENT = 1001;    // 环境异常
    public const ERROR_SECURITY = 1002;       // 安全异常
    public const ERROR_INSTALLATION = 1003;   // 安装异常
    public const ERROR_CONFIGURATION = 1004;  // 配置异常
    
    /**
     * HTTP状态码映射
     */
    public const HTTP_STATUS_MAP = [
        self::ERROR_ENVIRONMENT => 500,
        self::ERROR_SECURITY => 403,
        self::ERROR_INSTALLATION => 503,
        self::ERROR_CONFIGURATION => 500
    ];
    
    /**
     * 安装相关常量
     */
    public const INSTALL_LOCK_FILE = 'config/install/lock/install.lock';
    public const INSTALL_SQL_FILE = 'config/install/sql/install.sql';
    
    /**
     * 缓存相关常量
     */
    public const CACHE_PREFIX = 'video_reward_';
    public const CACHE_TTL_SHORT = 300;      // 5分钟
    public const CACHE_TTL_MEDIUM = 1800;    // 30分钟
    public const CACHE_TTL_LONG = 3600;      // 1小时
    
    /**
     * 日志级别
     */
    public const LOG_LEVEL_DEBUG = 'debug';
    public const LOG_LEVEL_INFO = 'info';
    public const LOG_LEVEL_WARNING = 'warning';
    public const LOG_LEVEL_ERROR = 'error';
    public const LOG_LEVEL_CRITICAL = 'critical';
    
    /**
     * CORS默认配置
     */
    public const CORS_DEFAULT_ORIGINS = '*';
    public const CORS_DEFAULT_METHODS = 'GET,POST,PUT,DELETE,OPTIONS';
    public const CORS_DEFAULT_HEADERS = 'Content-Type,Authorization,X-Requested-With';
    public const CORS_DEFAULT_MAX_AGE = 86400;
    
    /**
     * 初始化系统路径常量
     * 
     * 这个方法应该在应用启动时调用一次
     * 
     * @param string|null $publicPath public目录的绝对路径
     * @return void
     */
    public static function initializePaths(?string $publicPath = null): void
    {
        if ($publicPath === null) {
            $publicPath = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__;
        }
        
        // 确保路径以目录分隔符结尾
        if (substr($publicPath, -1) !== DIRECTORY_SEPARATOR) {
            $publicPath .= DIRECTORY_SEPARATOR;
        }
        
        // 定义核心路径常量（只定义一次）
        if (!defined('APP_PATH')) {
            define('APP_PATH', dirname($publicPath) . DIRECTORY_SEPARATOR);
        }
        
        if (!defined('ROOT_PATH')) {
            define('ROOT_PATH', APP_PATH);
        }
        
        if (!defined('PUBLIC_PATH')) {
            define('PUBLIC_PATH', $publicPath);
        }
        
        if (!defined('CONFIG_PATH')) {
            define('CONFIG_PATH', APP_PATH . 'config' . DIRECTORY_SEPARATOR);
        }
        
        if (!defined('RUNTIME_PATH')) {
            define('RUNTIME_PATH', APP_PATH . 'runtime' . DIRECTORY_SEPARATOR);
        }
        
        if (!defined('VENDOR_PATH')) {
            define('VENDOR_PATH', APP_PATH . 'vendor' . DIRECTORY_SEPARATOR);
        }
    }
    
    /**
     * 获取安装锁文件的完整路径
     * 
     * @return string
     */
    public static function getInstallLockPath(): string
    {
        return (defined('APP_PATH') ? APP_PATH : '') . self::INSTALL_LOCK_FILE;
    }
    
    /**
     * 获取安装SQL文件的完整路径
     * 
     * @return string
     */
    public static function getInstallSqlPath(): string
    {
        return (defined('APP_PATH') ? APP_PATH : '') . self::INSTALL_SQL_FILE;
    }
    
    /**
     * 获取Composer自动加载文件路径
     * 
     * @return string
     */
    public static function getComposerAutoloadPath(): string
    {
        return (defined('VENDOR_PATH') ? VENDOR_PATH : (defined('APP_PATH') ? APP_PATH . 'vendor' . DIRECTORY_SEPARATOR : '')) . 'autoload.php';
    }
    
    /**
     * 获取ThinkPHP助手文件路径
     * 
     * @return string
     */
    public static function getThinkHelperPath(): string
    {
        $vendorPath = defined('VENDOR_PATH') ? VENDOR_PATH : (defined('APP_PATH') ? APP_PATH . 'vendor' . DIRECTORY_SEPARATOR : '');
        return $vendorPath . 'topthink' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'helper.php';
    }
    
    /**
     * 检查是否已初始化路径常量
     * 
     * @return bool
     */
    public static function isPathsInitialized(): bool
    {
        return defined('APP_PATH') && defined('ROOT_PATH') && defined('PUBLIC_PATH');
    }
    
    /**
     * 获取错误代码对应的HTTP状态码
     * 
     * @param int $errorCode 错误代码
     * @return int HTTP状态码
     */
    public static function getHttpStatusCode(int $errorCode): int
    {
        return self::HTTP_STATUS_MAP[$errorCode] ?? 500;
    }
    
    /**
     * 获取所有定义的错误代码
     * 
     * @return array
     */
    public static function getErrorCodes(): array
    {
        return [
            'ENVIRONMENT' => self::ERROR_ENVIRONMENT,
            'SECURITY' => self::ERROR_SECURITY,
            'INSTALLATION' => self::ERROR_INSTALLATION,
            'CONFIGURATION' => self::ERROR_CONFIGURATION
        ];
    }
    
    /**
     * 获取CORS默认配置
     * 
     * @return array
     */
    public static function getCorsDefaults(): array
    {
        return [
            'allowed_origins' => self::CORS_DEFAULT_ORIGINS,
            'allowed_methods' => self::CORS_DEFAULT_METHODS,
            'allowed_headers' => self::CORS_DEFAULT_HEADERS,
            'max_age' => self::CORS_DEFAULT_MAX_AGE
        ];
    }
}
?>
