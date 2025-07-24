<?php
/**
 * Video-Reward 配置管理服务
 * 
 * 统一处理配置获取、缓存和默认值管理
 * 解决ApplicationBootstrap.php和CorsService.php中70%的配置获取重复代码
 * 
 * @author Video-Reward Team
 * @version 1.0
 * @since 2025-01-23
 */

declare(strict_types=1);

namespace app\Services\System;

use app\Common\Constants;

class ConfigManager
{
    /**
     * 单例实例
     */
    private static ?self $instance = null;
    
    /**
     * 配置缓存
     */
    private array $configCache = [];
    
    /**
     * 配置文件缓存
     */
    private array $fileCache = [];
    
    /**
     * 获取单例实例
     * 
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 私有构造函数，防止外部实例化
     */
    private function __construct()
    {
        // 初始化基础配置
        $this->loadBaseConfig();
    }
    
    /**
     * 获取配置值
     * 
     * @param string $key 配置键，支持点号分隔的嵌套键
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        // 检查缓存
        if (isset($this->configCache[$key])) {
            return $this->configCache[$key];
        }
        
        // 解析配置键
        $value = $this->getNestedValue($key, $default);
        
        // 缓存结果
        $this->configCache[$key] = $value;
        
        return $value;
    }
    
    /**
     * 设置配置值
     * 
     * @param string $key 配置键
     * @param mixed $value 配置值
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->setNestedValue($key, $value);
        
        // 更新缓存
        $this->configCache[$key] = $value;
    }
    
    /**
     * 获取CORS配置
     * 
     * @return array
     */
    public function getCorsConfig(): array
    {
        $defaults = Constants::getCorsDefaults();
        
        return [
            'allowed_origins' => $this->get('cors.allowed_origins', $defaults['allowed_origins']),
            'allowed_methods' => $this->get('cors.allowed_methods', $defaults['allowed_methods']),
            'allowed_headers' => $this->get('cors.allowed_headers', $defaults['allowed_headers']),
            'max_age' => $this->get('cors.max_age', $defaults['max_age']),
            'allow_credentials' => $this->get('cors.allow_credentials', false),
            'expose_headers' => $this->get('cors.expose_headers', '')
        ];
    }
    
    /**
     * 获取数据库配置
     * 
     * @return array
     */
    public function getDatabaseConfig(): array
    {
        return [
            'type' => $this->get('database.type', 'mysql'),
            'hostname' => $this->get('database.hostname', 'localhost'),
            'hostport' => $this->get('database.hostport', '3306'),
            'database' => $this->get('database.database', ''),
            'username' => $this->get('database.username', 'root'),
            'password' => $this->get('database.password', ''),
            'prefix' => $this->get('database.prefix', 'ds_'),
            'charset' => $this->get('database.charset', 'utf8mb4'),
            'debug' => $this->get('database.debug', false)
        ];
    }
    
    /**
     * 获取应用配置
     * 
     * @return array
     */
    public function getAppConfig(): array
    {
        return [
            'name' => $this->get('app.name', Constants::SYSTEM_NAME),
            'version' => $this->get('app.version', Constants::SYSTEM_VERSION),
            'debug' => $this->get('app.debug', false),
            'timezone' => $this->get('app.timezone', 'Asia/Shanghai'),
            'admin_url' => $this->get('app.admin_url', 'admin'),
            'language' => $this->get('app.language', 'zh-cn')
        ];
    }
    
    /**
     * 获取缓存配置
     * 
     * @return array
     */
    public function getCacheConfig(): array
    {
        return [
            'type' => $this->get('cache.type', 'file'),
            'prefix' => $this->get('cache.prefix', Constants::CACHE_PREFIX),
            'expire' => $this->get('cache.expire', Constants::CACHE_TTL_MEDIUM),
            'path' => $this->get('cache.path', RUNTIME_PATH . 'cache'),
            'serialize' => $this->get('cache.serialize', true)
        ];
    }
    
    /**
     * 检查配置是否存在
     * 
     * @param string $key 配置键
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->getNestedValue($key, '__NOT_FOUND__') !== '__NOT_FOUND__';
    }
    
    /**
     * 清除配置缓存
     * 
     * @param string|null $key 要清除的配置键，null表示清除所有
     * @return void
     */
    public function clearCache(?string $key = null): void
    {
        if ($key === null) {
            $this->configCache = [];
        } else {
            unset($this->configCache[$key]);
        }
    }
    
    /**
     * 加载配置文件
     * 
     * @param string $file 配置文件名（不含扩展名）
     * @return array
     */
    public function loadConfigFile(string $file): array
    {
        if (isset($this->fileCache[$file])) {
            return $this->fileCache[$file];
        }
        
        $configPath = CONFIG_PATH . $file . '.php';
        
        if (!file_exists($configPath)) {
            $this->fileCache[$file] = [];
            return [];
        }
        
        $config = include $configPath;
        $this->fileCache[$file] = is_array($config) ? $config : [];
        
        return $this->fileCache[$file];
    }
    
    /**
     * 加载基础配置
     * 
     * @return void
     */
    private function loadBaseConfig(): void
    {
        // 加载主要配置文件
        $configFiles = ['app', 'database', 'cache', 'cors'];
        
        foreach ($configFiles as $file) {
            $this->loadConfigFile($file);
        }
    }
    
    /**
     * 获取嵌套配置值
     * 
     * @param string $key 配置键
     * @param mixed $default 默认值
     * @return mixed
     */
    private function getNestedValue(string $key, $default)
    {
        $keys = explode('.', $key);
        $configFile = array_shift($keys);
        
        // 加载配置文件
        $config = $this->loadConfigFile($configFile);
        
        // 遍历嵌套键
        $value = $config;
        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * 设置嵌套配置值
     * 
     * @param string $key 配置键
     * @param mixed $value 配置值
     * @return void
     */
    private function setNestedValue(string $key, $value): void
    {
        $keys = explode('.', $key);
        $configFile = array_shift($keys);
        
        // 加载配置文件
        if (!isset($this->fileCache[$configFile])) {
            $this->loadConfigFile($configFile);
        }
        
        // 设置嵌套值
        $config = &$this->fileCache[$configFile];
        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        $config = $value;
    }
    
    /**
     * 获取环境变量值
     * 
     * @param string $key 环境变量键
     * @param mixed $default 默认值
     * @return mixed
     */
    public function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? getenv($key);
        
        if ($value === false) {
            return $default;
        }
        
        // 转换布尔值
        if (in_array(strtolower($value), ['true', 'false'])) {
            return strtolower($value) === 'true';
        }
        
        // 转换数字
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float)$value : (int)$value;
        }
        
        return $value;
    }
    
    /**
     * 获取所有配置
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->fileCache;
    }
}
?>
