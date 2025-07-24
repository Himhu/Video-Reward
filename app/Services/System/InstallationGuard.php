<?php

declare(strict_types=1);

namespace app\Services\System;

/**
 * 安装状态守护服务
 *
 * 提供轻量级的安装状态检查和重定向功能
 * 不依赖复杂的配置文件和服务，确保在未安装状态下也能正常工作
 * 专门支持重构版安装程序 (installer.php)
 *
 * @package app\Services\System
 */
class InstallationGuard
{
    /**
     * 安装锁文件路径
     */
    private const LOCK_FILE_PATH = 'config' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'lock' . DIRECTORY_SEPARATOR . 'install.lock';
    
    /**
     * 应用根路径
     */
    private string $appPath;
    
    /**
     * 构造函数
     * 
     * @param string $appPath 应用根路径
     */
    public function __construct(string $appPath)
    {
        $this->appPath = rtrim($appPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
    
    /**
     * 检查系统安装状态
     * 
     * @return bool 是否已安装
     */
    public function isInstalled(): bool
    {
        $lockFile = $this->appPath . self::LOCK_FILE_PATH;
        return file_exists($lockFile);
    }
    
    /**
     * 获取安装程序URL
     *
     * 使用重构版安装程序
     *
     * @return string 安装程序URL
     */
    public function getInstallerUrl(): string
    {
        $installer = 'installer.php';
        return $this->buildUrl($installer);
    }
    
    /**
     * 执行安装重定向
     * 
     * @return void
     */
    public function redirectToInstaller(): void
    {
        $installUrl = $this->getInstallerUrl();
        
        // 记录重定向信息
        $this->logRedirection($installUrl);
        
        // 发送重定向响应
        $this->sendRedirectResponse($installUrl);
        
        // 显示重定向页面并退出
        echo $this->renderRedirectPage($installUrl);
        exit;
    }
    
    /**
     * 构建完整的URL
     * 
     * @param string $path 相对路径
     * @return string 完整URL
     */
    private function buildUrl(string $path): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        return $protocol . $host . '/' . ltrim($path, '/');
    }
    
    /**
     * 记录重定向日志
     *
     * @param string $installUrl 安装URL
     * @return void
     */
    private function logRedirection(string $installUrl): void
    {
        $logMessage = sprintf(
            "[%s] Video-Reward: 系统未安装，重定向到安装程序: %s",
            date('Y-m-d H:i:s'),
            $installUrl
        );

        error_log($logMessage);
    }
    
    /**
     * 发送重定向响应头
     * 
     * @param string $installUrl 安装URL
     * @return void
     */
    private function sendRedirectResponse(string $installUrl): void
    {
        http_response_code(302);
        header("Location: {$installUrl}");
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
    }
    
    /**
     * 渲染重定向页面
     * 
     * @param string $installUrl 安装URL
     * @return string HTML内容
     */
    private function renderRedirectPage(string $installUrl): string
    {
        // 使用独立的视图渲染服务
        $viewRenderer = new \app\Services\View\InstallationViewRenderer();
        return $viewRenderer->renderRedirectPage($installUrl);
    }
    
    /**
     * 静态工厂方法
     * 
     * @param string $appPath 应用根路径
     * @return self
     */
    public static function create(string $appPath): self
    {
        return new self($appPath);
    }
    
    /**
     * 快速检查并重定向（静态方法）
     * 
     * @param string $appPath 应用根路径
     * @return void
     */
    public static function checkAndRedirect(string $appPath): void
    {
        $guard = self::create($appPath);
        
        if (!$guard->isInstalled()) {
            $guard->redirectToInstaller();
        }
    }
}
