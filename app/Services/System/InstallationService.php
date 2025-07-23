<?php
// +----------------------------------------------------------------------
// | 安装检查服务类
// +----------------------------------------------------------------------
// | 功能：检查系统安装状态和相关依赖
// | 职责：单一职责原则 - 专门处理安装状态检查
// | 设计：可扩展的安装检查机制
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace App\Services\System;

use App\Exceptions\InstallationException;

/**
 * 安装检查服务类
 * 
 * 负责系统安装状态的检查和验证：
 * - 安装锁文件检查
 * - 系统依赖验证
 * - 配置文件完整性检查
 * - 数据库连接验证
 * 
 * @package App\Services\System
 * @author Video-Reward Team
 * @version 1.0.0
 */
class InstallationService
{
    /**
     * 安装锁文件路径
     */
    private const LOCK_FILE_PATH = 'config/install/lock/install.lock';

    /**
     * 必需的配置文件列表
     */
    private const REQUIRED_CONFIG_FILES = [
        'config/app.php',
        'config/database.php',
        'config/cache.php'
    ];

    /**
     * 必需的目录列表
     */
    private const REQUIRED_DIRECTORIES = [
        'runtime',
        'runtime/cache',
        'runtime/log',
        'runtime/session',
        'public/upload'
    ];

    /**
     * 检查系统是否已安装
     * 
     * @return bool
     */
    public function isInstalled(): bool
    {
        return $this->hasLockFile();
    }

    /**
     * 执行完整的安装检查
     * 
     * @return array 检查结果
     * @throws InstallationException
     */
    public function performFullCheck(): array
    {
        $results = [
            'installed' => false,
            'checks' => [],
            'errors' => [],
            'warnings' => []
        ];

        try {
            // 检查安装锁文件
            $results['checks']['lock_file'] = $this->checkLockFile();
            
            // 检查配置文件
            $results['checks']['config_files'] = $this->checkConfigFiles();
            
            // 检查目录权限
            $results['checks']['directories'] = $this->checkDirectories();
            
            // 检查PHP扩展
            $results['checks']['php_extensions'] = $this->checkPhpExtensions();
            
            // 检查文件权限
            $results['checks']['file_permissions'] = $this->checkFilePermissions();

            // 汇总结果
            $results['installed'] = $this->isInstalled();
            
            // 收集错误和警告
            $this->collectIssues($results);

        } catch (\Throwable $e) {
            throw new InstallationException(
                '安装检查失败: ' . $e->getMessage(),
                500,
                $e
            );
        }

        return $results;
    }

    /**
     * 检查安装锁文件是否存在
     * 
     * @return bool
     */
    private function hasLockFile(): bool
    {
        $lockFilePath = ROOT_PATH . self::LOCK_FILE_PATH;
        return file_exists($lockFilePath);
    }

    /**
     * 检查安装锁文件
     * 
     * @return array
     */
    private function checkLockFile(): array
    {
        $lockFilePath = ROOT_PATH . self::LOCK_FILE_PATH;
        
        return [
            'exists' => file_exists($lockFilePath),
            'path' => $lockFilePath,
            'readable' => file_exists($lockFilePath) && is_readable($lockFilePath),
            'modified_time' => file_exists($lockFilePath) ? filemtime($lockFilePath) : null
        ];
    }

    /**
     * 检查必需的配置文件
     * 
     * @return array
     */
    private function checkConfigFiles(): array
    {
        $results = [];
        
        foreach (self::REQUIRED_CONFIG_FILES as $configFile) {
            $fullPath = ROOT_PATH . $configFile;
            
            $results[$configFile] = [
                'exists' => file_exists($fullPath),
                'readable' => file_exists($fullPath) && is_readable($fullPath),
                'size' => file_exists($fullPath) ? filesize($fullPath) : 0,
                'path' => $fullPath
            ];
        }
        
        return $results;
    }

    /**
     * 检查必需的目录
     * 
     * @return array
     */
    private function checkDirectories(): array
    {
        $results = [];
        
        foreach (self::REQUIRED_DIRECTORIES as $directory) {
            $fullPath = ROOT_PATH . $directory;
            
            $results[$directory] = [
                'exists' => is_dir($fullPath),
                'writable' => is_dir($fullPath) && is_writable($fullPath),
                'readable' => is_dir($fullPath) && is_readable($fullPath),
                'path' => $fullPath,
                'permissions' => is_dir($fullPath) ? substr(sprintf('%o', fileperms($fullPath)), -4) : null
            ];
        }
        
        return $results;
    }

    /**
     * 检查PHP扩展
     * 
     * @return array
     */
    private function checkPhpExtensions(): array
    {
        $requiredExtensions = [
            'pdo',
            'pdo_mysql',
            'mbstring',
            'openssl',
            'json',
            'curl',
            'gd',
            'fileinfo'
        ];

        $results = [];
        
        foreach ($requiredExtensions as $extension) {
            $results[$extension] = [
                'loaded' => extension_loaded($extension),
                'version' => extension_loaded($extension) ? phpversion($extension) : null
            ];
        }
        
        return $results;
    }

    /**
     * 检查文件权限
     * 
     * @return array
     */
    private function checkFilePermissions(): array
    {
        $criticalFiles = [
            'public/index.php',
            'public/install.php',
            '.env'
        ];

        $results = [];
        
        foreach ($criticalFiles as $file) {
            $fullPath = ROOT_PATH . $file;
            
            if (file_exists($fullPath)) {
                $results[$file] = [
                    'exists' => true,
                    'readable' => is_readable($fullPath),
                    'writable' => is_writable($fullPath),
                    'permissions' => substr(sprintf('%o', fileperms($fullPath)), -4)
                ];
            } else {
                $results[$file] = [
                    'exists' => false,
                    'readable' => false,
                    'writable' => false,
                    'permissions' => null
                ];
            }
        }
        
        return $results;
    }

    /**
     * 收集问题和警告
     * 
     * @param array $results 检查结果
     */
    private function collectIssues(array &$results): void
    {
        // 检查配置文件问题
        foreach ($results['checks']['config_files'] as $file => $info) {
            if (!$info['exists']) {
                $results['errors'][] = "配置文件不存在: {$file}";
            } elseif (!$info['readable']) {
                $results['errors'][] = "配置文件不可读: {$file}";
            }
        }

        // 检查目录问题
        foreach ($results['checks']['directories'] as $dir => $info) {
            if (!$info['exists']) {
                $results['errors'][] = "目录不存在: {$dir}";
            } elseif (!$info['writable']) {
                $results['warnings'][] = "目录不可写: {$dir}";
            }
        }

        // 检查PHP扩展问题
        foreach ($results['checks']['php_extensions'] as $ext => $info) {
            if (!$info['loaded']) {
                $results['errors'][] = "PHP扩展未加载: {$ext}";
            }
        }
    }

    /**
     * 创建安装锁文件
     * 
     * @return bool
     * @throws InstallationException
     */
    public function createLockFile(): bool
    {
        try {
            $lockFilePath = ROOT_PATH . self::LOCK_FILE_PATH;
            $lockDir = dirname($lockFilePath);

            // 创建目录（如果不存在）
            if (!is_dir($lockDir)) {
                if (!mkdir($lockDir, 0755, true)) {
                    throw new InstallationException("无法创建锁文件目录: {$lockDir}");
                }
            }

            // 创建锁文件
            $lockContent = [
                'installed_at' => date('Y-m-d H:i:s'),
                'version' => '1.0.0',
                'php_version' => PHP_VERSION,
                'installer_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];

            $result = file_put_contents($lockFilePath, json_encode($lockContent, JSON_PRETTY_PRINT));
            
            if ($result === false) {
                throw new InstallationException("无法写入锁文件: {$lockFilePath}");
            }

            return true;

        } catch (\Throwable $e) {
            throw new InstallationException(
                '创建安装锁文件失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 删除安装锁文件（用于重新安装）
     * 
     * @return bool
     * @throws InstallationException
     */
    public function removeLockFile(): bool
    {
        try {
            $lockFilePath = ROOT_PATH . self::LOCK_FILE_PATH;
            
            if (file_exists($lockFilePath)) {
                return unlink($lockFilePath);
            }
            
            return true;

        } catch (\Throwable $e) {
            throw new InstallationException(
                '删除安装锁文件失败: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * 获取安装信息
     * 
     * @return array|null
     */
    public function getInstallationInfo(): ?array
    {
        if (!$this->isInstalled()) {
            return null;
        }

        $lockFilePath = ROOT_PATH . self::LOCK_FILE_PATH;
        $content = file_get_contents($lockFilePath);
        
        if ($content === false) {
            return null;
        }

        $info = json_decode($content, true);
        return is_array($info) ? $info : null;
    }
}
