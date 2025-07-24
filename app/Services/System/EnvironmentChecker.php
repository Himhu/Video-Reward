<?php
/**
 * Video-Reward 环境检查服务
 * 
 * 统一处理PHP版本检查、依赖检查等环境验证
 * 解决public/index.php和public/installer.php中100%的环境检查重复代码
 * 
 * @author Video-Reward Team
 * @version 1.0
 * @since 2025-01-23
 */

declare(strict_types=1);

namespace app\Services\System;

class EnvironmentChecker
{
    /**
     * 最低PHP版本要求
     */
    private const MIN_PHP_VERSION = '7.1.0';
    
    /**
     * 必需的PHP扩展
     */
    private const REQUIRED_EXTENSIONS = [
        'json' => 'JSON处理',
        'mbstring' => '多字节字符串处理',
        'openssl' => 'SSL加密',
        'curl' => 'HTTP客户端',
        'pdo' => '数据库抽象层'
    ];
    
    /**
     * 推荐的PHP扩展
     */
    private const RECOMMENDED_EXTENSIONS = [
        'gd' => '图像处理',
        'zip' => '压缩文件处理',
        'xml' => 'XML处理',
        'fileinfo' => '文件信息检测'
    ];
    
    /**
     * 执行完整的环境检查
     * 
     * @param bool $strict 是否严格模式（推荐扩展也必须存在）
     * @return void
     * @throws \RuntimeException 环境检查失败时抛出异常
     */
    public static function checkAll(bool $strict = false): void
    {
        self::checkPhpVersion();
        self::checkComposerDependencies();
        self::checkRequiredExtensions();
        
        if ($strict) {
            self::checkRecommendedExtensions();
        }
        
        self::checkDirectoryPermissions();
    }
    
    /**
     * 检查PHP版本
     * 
     * @param string $minVersion 最低版本要求
     * @return void
     * @throws \RuntimeException PHP版本过低时抛出异常
     */
    public static function checkPhpVersion(string $minVersion = self::MIN_PHP_VERSION): void
    {
        if (version_compare(PHP_VERSION, $minVersion, '<')) {
            http_response_code(500);
            $message = "PHP版本过低，要求PHP {$minVersion}或更高版本，当前版本：" . PHP_VERSION;
            
            // 记录错误日志
            error_log("Environment Check Failed: " . $message);
            
            // 输出错误信息并退出
            if (self::isCommandLine()) {
                echo $message . "\n";
            } else {
                echo "<!DOCTYPE html>\n";
                echo "<html><head><title>环境错误</title></head><body>\n";
                echo "<h1>PHP版本不兼容</h1>\n";
                echo "<p>" . htmlspecialchars($message) . "</p>\n";
                echo "</body></html>\n";
            }
            
            exit(1);
        }
    }
    
    /**
     * 检查Composer依赖
     * 
     * @return void
     * @throws \RuntimeException 依赖未安装时抛出异常
     */
    public static function checkComposerDependencies(): void
    {
        $autoloadFile = self::getProjectRoot() . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
        
        if (!file_exists($autoloadFile)) {
            http_response_code(500);
            $message = 'Composer依赖未安装，请运行: composer install';
            
            error_log("Environment Check Failed: " . $message);
            
            if (self::isCommandLine()) {
                echo $message . "\n";
            } else {
                echo "<!DOCTYPE html>\n";
                echo "<html><head><title>依赖错误</title></head><body>\n";
                echo "<h1>依赖未安装</h1>\n";
                echo "<p>" . htmlspecialchars($message) . "</p>\n";
                echo "<p>请在项目根目录运行: <code>composer install</code></p>\n";
                echo "</body></html>\n";
            }
            
            exit(1);
        }
    }
    
    /**
     * 检查必需的PHP扩展
     * 
     * @return void
     * @throws \RuntimeException 缺少必需扩展时抛出异常
     */
    public static function checkRequiredExtensions(): void
    {
        $missingExtensions = [];
        
        foreach (self::REQUIRED_EXTENSIONS as $extension => $description) {
            if (!extension_loaded($extension)) {
                $missingExtensions[] = "{$extension} ({$description})";
            }
        }
        
        if (!empty($missingExtensions)) {
            http_response_code(500);
            $message = "缺少必需的PHP扩展: " . implode(', ', $missingExtensions);
            
            error_log("Environment Check Failed: " . $message);
            
            if (self::isCommandLine()) {
                echo $message . "\n";
            } else {
                echo "<!DOCTYPE html>\n";
                echo "<html><head><title>扩展错误</title></head><body>\n";
                echo "<h1>PHP扩展缺失</h1>\n";
                echo "<p>" . htmlspecialchars($message) . "</p>\n";
                echo "<p>请安装缺失的PHP扩展后重试。</p>\n";
                echo "</body></html>\n";
            }
            
            exit(1);
        }
    }
    
    /**
     * 检查推荐的PHP扩展
     * 
     * @return array 缺失的推荐扩展列表
     */
    public static function checkRecommendedExtensions(): array
    {
        $missingExtensions = [];
        
        foreach (self::RECOMMENDED_EXTENSIONS as $extension => $description) {
            if (!extension_loaded($extension)) {
                $missingExtensions[] = "{$extension} ({$description})";
            }
        }
        
        if (!empty($missingExtensions)) {
            $message = "缺少推荐的PHP扩展: " . implode(', ', $missingExtensions);
            error_log("Environment Warning: " . $message);
        }
        
        return $missingExtensions;
    }
    
    /**
     * 检查目录权限
     * 
     * @return void
     */
    public static function checkDirectoryPermissions(): void
    {
        $projectRoot = self::getProjectRoot();
        $checkDirs = [
            'runtime' => '运行时目录',
            'runtime/log' => '日志目录',
            'runtime/cache' => '缓存目录',
            'config' => '配置目录'
        ];
        
        $permissionIssues = [];
        
        foreach ($checkDirs as $dir => $description) {
            $fullPath = $projectRoot . $dir;
            
            // 如果目录不存在，尝试创建
            if (!is_dir($fullPath)) {
                if (!@mkdir($fullPath, 0755, true)) {
                    $permissionIssues[] = "无法创建{$description}: {$dir}";
                    continue;
                }
            }
            
            // 检查写权限
            if (!is_writable($fullPath)) {
                $permissionIssues[] = "{$description}不可写: {$dir}";
            }
        }
        
        if (!empty($permissionIssues)) {
            $message = "目录权限问题: " . implode(', ', $permissionIssues);
            error_log("Environment Warning: " . $message);
            
            // 权限问题通常不是致命的，只记录警告
            if (self::isCommandLine()) {
                echo "警告: " . $message . "\n";
            }
        }
    }
    
    /**
     * 获取环境检查报告
     * 
     * @return array 环境检查结果
     */
    public static function getEnvironmentReport(): array
    {
        $report = [
            'php_version' => PHP_VERSION,
            'php_version_ok' => version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '>='),
            'composer_installed' => file_exists(self::getProjectRoot() . 'vendor/autoload.php'),
            'required_extensions' => [],
            'recommended_extensions' => [],
            'directory_permissions' => []
        ];
        
        // 检查必需扩展
        foreach (self::REQUIRED_EXTENSIONS as $extension => $description) {
            $report['required_extensions'][$extension] = [
                'loaded' => extension_loaded($extension),
                'description' => $description
            ];
        }
        
        // 检查推荐扩展
        foreach (self::RECOMMENDED_EXTENSIONS as $extension => $description) {
            $report['recommended_extensions'][$extension] = [
                'loaded' => extension_loaded($extension),
                'description' => $description
            ];
        }
        
        return $report;
    }
    
    /**
     * 检查是否在命令行环境
     * 
     * @return bool
     */
    private static function isCommandLine(): bool
    {
        return php_sapi_name() === 'cli';
    }
    
    /**
     * 获取项目根目录
     * 
     * @return string
     */
    private static function getProjectRoot(): string
    {
        // 从当前文件位置推算项目根目录
        return dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
    }
}
?>
