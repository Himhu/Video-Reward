<?php

declare(strict_types=1);

namespace app\Services\System;

use Psr\Log\LoggerInterface;

/**
 * 环境服务
 * 
 * 负责验证和检查系统运行环境
 * 确保应用在合适的环境中运行
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class EnvironmentService
{
    private LoggerInterface $logger;

    // 最低PHP版本要求
    private const MIN_PHP_VERSION = '7.1.0';

    // 必需的PHP扩展
    private const REQUIRED_EXTENSIONS = [
        'pdo',
        'pdo_mysql',
        'mbstring',
        'openssl',
        'json',
        'curl'
    ];

    /**
     * 构造函数
     *
     * @param LoggerInterface $logger 日志实例
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * 验证运行环境
     *
     * @return bool 环境是否满足要求
     */
    public function validateEnvironment(): bool
    {
        $checks = [
            'php_version' => $this->checkPhpVersion(),
            'extensions' => $this->checkRequiredExtensions(),
            'permissions' => $this->checkDirectoryPermissions(),
            'memory_limit' => $this->checkMemoryLimit(),
            'execution_time' => $this->checkExecutionTime()
        ];

        $allPassed = true;
        foreach ($checks as $check => $result) {
            if (!$result['passed']) {
                $allPassed = false;
                $this->logger->error("环境检查失败: {$check}", $result);
            } else {
                $this->logger->info("环境检查通过: {$check}", $result);
            }
        }

        return $allPassed;
    }

    /**
     * 检查PHP版本
     *
     * @return array 检查结果
     */
    private function checkPhpVersion(): array
    {
        $currentVersion = PHP_VERSION;
        $passed = version_compare($currentVersion, self::MIN_PHP_VERSION, '>=');

        return [
            'passed' => $passed,
            'current' => $currentVersion,
            'required' => self::MIN_PHP_VERSION,
            'message' => $passed 
                ? "PHP版本满足要求: {$currentVersion}" 
                : "PHP版本过低: {$currentVersion}，要求: " . self::MIN_PHP_VERSION
        ];
    }

    /**
     * 检查必需的PHP扩展
     *
     * @return array 检查结果
     */
    private function checkRequiredExtensions(): array
    {
        $missing = [];
        $loaded = [];

        foreach (self::REQUIRED_EXTENSIONS as $extension) {
            if (extension_loaded($extension)) {
                $loaded[] = $extension;
            } else {
                $missing[] = $extension;
            }
        }

        $passed = empty($missing);

        return [
            'passed' => $passed,
            'loaded' => $loaded,
            'missing' => $missing,
            'message' => $passed 
                ? '所有必需扩展已加载' 
                : '缺少扩展: ' . implode(', ', $missing)
        ];
    }

    /**
     * 检查目录权限
     *
     * @return array 检查结果
     */
    private function checkDirectoryPermissions(): array
    {
        $directories = [
            'runtime' => dirname(__DIR__, 3) . '/runtime',
            'config' => dirname(__DIR__, 3) . '/config',
            'public' => dirname(__DIR__, 3) . '/public'
        ];

        $errors = [];
        $checked = [];

        foreach ($directories as $name => $path) {
            $writable = $this->isDirectoryWritable($path);
            $checked[$name] = [
                'path' => $path,
                'writable' => $writable,
                'exists' => is_dir($path)
            ];

            if (!$writable) {
                $errors[] = "{$name}: {$path}";
            }
        }

        $passed = empty($errors);

        return [
            'passed' => $passed,
            'checked' => $checked,
            'errors' => $errors,
            'message' => $passed 
                ? '目录权限检查通过' 
                : '目录权限不足: ' . implode(', ', $errors)
        ];
    }

    /**
     * 检查内存限制
     *
     * @return array 检查结果
     */
    private function checkMemoryLimit(): array
    {
        $memoryLimit = ini_get('memory_limit');
        $memoryBytes = $this->convertToBytes($memoryLimit);
        $recommendedBytes = 128 * 1024 * 1024; // 128MB

        $passed = $memoryBytes >= $recommendedBytes || $memoryLimit === '-1';

        return [
            'passed' => $passed,
            'current' => $memoryLimit,
            'recommended' => '128M',
            'message' => $passed 
                ? "内存限制满足要求: {$memoryLimit}" 
                : "内存限制过低: {$memoryLimit}，建议: 128M"
        ];
    }

    /**
     * 检查执行时间限制
     *
     * @return array 检查结果
     */
    private function checkExecutionTime(): array
    {
        $maxExecutionTime = ini_get('max_execution_time');
        $recommendedTime = 30;

        $passed = $maxExecutionTime >= $recommendedTime || $maxExecutionTime == 0;

        return [
            'passed' => $passed,
            'current' => $maxExecutionTime,
            'recommended' => $recommendedTime,
            'message' => $passed 
                ? "执行时间限制满足要求: {$maxExecutionTime}秒" 
                : "执行时间限制过低: {$maxExecutionTime}秒，建议: {$recommendedTime}秒"
        ];
    }

    /**
     * 检查目录是否可写
     *
     * @param string $directory 目录路径
     * @return bool 是否可写
     */
    private function isDirectoryWritable(string $directory): bool
    {
        if (!is_dir($directory)) {
            return false;
        }

        // Windows系统直接返回true
        if (DIRECTORY_SEPARATOR === '\\') {
            return true;
        }

        // Unix/Linux系统检查权限
        if (is_writable($directory)) {
            return true;
        }

        // 尝试创建测试文件
        $testFile = $directory . DIRECTORY_SEPARATOR . '.write_test_' . uniqid();
        $fp = @fopen($testFile, 'w');
        
        if ($fp === false) {
            return false;
        }

        fclose($fp);
        @unlink($testFile);
        
        return true;
    }

    /**
     * 将内存限制字符串转换为字节数
     *
     * @param string $value 内存限制值
     * @return int 字节数
     */
    private function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $number = (int) $value;

        switch ($last) {
            case 'g':
                $number *= 1024;
                // no break
            case 'm':
                $number *= 1024;
                // no break
            case 'k':
                $number *= 1024;
        }

        return $number;
    }

    /**
     * 获取环境信息摘要
     *
     * @return array 环境信息
     */
    public function getEnvironmentInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'php_sapi' => PHP_SAPI,
            'os' => PHP_OS,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'loaded_extensions' => get_loaded_extensions(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
        ];
    }
}
