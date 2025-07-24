<?php

declare(strict_types=1);

namespace app\Services\System;

use app\Services\System\ConfigManager;
use Psr\Log\LoggerInterface;

/**
 * 安装服务
 * 
 * 负责检查系统安装状态和相关操作
 * 确保系统在正确安装后才能正常运行
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class InstallationService
{
    private ?ConfigManager $configManager = null;
    private ?LoggerInterface $logger = null;

    /**
     * 构造函数
     *
     * @param ConfigManager|null $configManager 配置管理器实例
     * @param LoggerInterface|null $logger 日志实例
     */
    public function __construct(?ConfigManager $configManager = null, ?LoggerInterface $logger = null)
    {
        $this->configManager = $configManager;
        $this->logger = $logger;
    }

    /**
     * 检查系统是否已安装
     *
     * @return bool 是否已安装
     */
    public function isInstalled(): bool
    {
        $lockFile = $this->getLockFilePath();
        $exists = file_exists($lockFile);

        if ($this->logger) {
            $this->logger->info('检查安装状态', [
                'lock_file' => $lockFile,
                'exists' => $exists,
                'installed' => $exists
            ]);
        }

        return $exists;
    }

    /**
     * 创建安装锁文件
     *
     * @return bool 是否创建成功
     */
    public function createInstallLock(): bool
    {
        $lockFile = $this->getLockFilePath();
        $lockDir = dirname($lockFile);

        // 确保目录存在
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }

        // 创建锁文件
        $result = file_put_contents($lockFile, date('Y-m-d H:i:s')) !== false;

        if ($this->logger) {
            $this->logger->info('创建安装锁文件', [
                'lock_file' => $lockFile,
                'success' => $result
            ]);
        }

        return $result;
    }

    /**
     * 获取安装锁文件路径
     *
     * @return string 锁文件完整路径
     */
    public function getLockFilePath(): string
    {
        $relativePath = $this->configManager ?
            $this->configManager->get('path.install_lock', 'config/install/lock/install.lock') :
            'config/install/lock/install.lock';
        $rootPath = dirname(__DIR__, 3);
        
        return $rootPath . DIRECTORY_SEPARATOR . $relativePath;
    }

    /**
     * 创建安装锁文件
     *
     * @param array $installInfo 安装信息
     * @return bool 是否创建成功
     */
    public function createLockFile(array $installInfo = []): bool
    {
        $lockFile = $this->getLockFilePath();
        $lockDir = dirname($lockFile);

        // 确保目录存在
        if (!is_dir($lockDir)) {
            if (!mkdir($lockDir, 0755, true)) {
                $this->logger->error('创建锁文件目录失败', [
                    'directory' => $lockDir
                ]);
                return false;
            }
        }

        // 准备锁文件内容
        $lockContent = $this->prepareLockContent($installInfo);

        // 写入锁文件
        $result = file_put_contents($lockFile, $lockContent);

        if ($result !== false) {
            $this->logger->info('安装锁文件创建成功', [
                'lock_file' => $lockFile,
                'size' => $result
            ]);
            return true;
        } else {
            $this->logger->error('安装锁文件创建失败', [
                'lock_file' => $lockFile
            ]);
            return false;
        }
    }

    /**
     * 删除安装锁文件
     *
     * @return bool 是否删除成功
     */
    public function removeLockFile(): bool
    {
        $lockFile = $this->getLockFilePath();

        if (!file_exists($lockFile)) {
            $this->logger->info('安装锁文件不存在，无需删除', [
                'lock_file' => $lockFile
            ]);
            return true;
        }

        $result = unlink($lockFile);

        if ($result) {
            $this->logger->info('安装锁文件删除成功', [
                'lock_file' => $lockFile
            ]);
        } else {
            $this->logger->error('安装锁文件删除失败', [
                'lock_file' => $lockFile
            ]);
        }

        return $result;
    }

    /**
     * 获取安装信息
     *
     * @return array|null 安装信息，如果未安装返回null
     */
    public function getInstallationInfo(): ?array
    {
        if (!$this->isInstalled()) {
            return null;
        }

        $lockFile = $this->getLockFilePath();
        $content = file_get_contents($lockFile);

        if ($content === false) {
            $this->logger->error('读取安装锁文件失败', [
                'lock_file' => $lockFile
            ]);
            return null;
        }

        return $this->parseLockContent($content);
    }

    /**
     * 验证安装完整性
     *
     * @return bool 安装是否完整
     */
    public function validateInstallation(): bool
    {
        if (!$this->isInstalled()) {
            return false;
        }

        $checks = [
            'config_files' => $this->checkConfigFiles(),
            'database_config' => $this->checkDatabaseConfig(),
            'app_config' => $this->checkAppConfig()
        ];

        $allPassed = true;
        foreach ($checks as $check => $result) {
            if (!$result) {
                $allPassed = false;
                $this->logger->warning("安装完整性检查失败: {$check}");
            }
        }

        return $allPassed;
    }

    /**
     * 准备锁文件内容
     *
     * @param array $installInfo 安装信息
     * @return string 锁文件内容
     */
    private function prepareLockContent(array $installInfo): string
    {
        $data = array_merge([
            'installed_at' => date('Y-m-d H:i:s'),
            'version' => '2.0',
            'php_version' => PHP_VERSION,
            'installer_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ], $installInfo);

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * 解析锁文件内容
     *
     * @param string $content 锁文件内容
     * @return array 解析后的安装信息
     */
    private function parseLockContent(string $content): array
    {
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->warning('锁文件内容解析失败，使用默认格式', [
                'json_error' => json_last_error_msg()
            ]);
            
            // 兼容旧格式的锁文件
            return [
                'installed_at' => 'unknown',
                'version' => 'unknown',
                'content' => $content
            ];
        }

        return $data;
    }

    /**
     * 检查配置文件是否存在
     *
     * @return bool 配置文件是否完整
     */
    private function checkConfigFiles(): bool
    {
        $rootPath = dirname(__DIR__, 3);
        $requiredFiles = [
            'config/app.php',
            'config/database.php'
        ];

        foreach ($requiredFiles as $file) {
            $filePath = $rootPath . DIRECTORY_SEPARATOR . $file;
            if (!file_exists($filePath)) {
                $this->logger->warning('配置文件缺失', [
                    'file' => $filePath
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * 检查数据库配置
     *
     * @return bool 数据库配置是否有效
     */
    private function checkDatabaseConfig(): bool
    {
        $rootPath = dirname(__DIR__, 3);
        $configFile = $rootPath . DIRECTORY_SEPARATOR . 'config/database.php';

        if (!file_exists($configFile)) {
            return false;
        }

        // 简单检查配置文件是否包含必要的数据库配置
        $content = file_get_contents($configFile);
        return strpos($content, 'hostname') !== false && 
               strpos($content, 'database') !== false;
    }

    /**
     * 检查应用配置
     *
     * @return bool 应用配置是否有效
     */
    private function checkAppConfig(): bool
    {
        $rootPath = dirname(__DIR__, 3);
        $configFile = $rootPath . DIRECTORY_SEPARATOR . 'config/app.php';

        if (!file_exists($configFile)) {
            return false;
        }

        // 简单检查配置文件是否包含必要的应用配置
        $content = file_get_contents($configFile);
        return strpos($content, 'app_namespace') !== false;
    }

    /**
     * 获取安装状态摘要
     *
     * @return array 安装状态信息
     */
    public function getInstallationStatus(): array
    {
        $isInstalled = $this->isInstalled();
        $info = $isInstalled ? $this->getInstallationInfo() : null;
        $isValid = $isInstalled ? $this->validateInstallation() : false;

        return [
            'installed' => $isInstalled,
            'valid' => $isValid,
            'lock_file' => $this->getLockFilePath(),
            'install_info' => $info
        ];
    }
}
