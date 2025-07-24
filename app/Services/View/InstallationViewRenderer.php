<?php

declare(strict_types=1);

namespace app\Services\View;

/**
 * 安装程序视图渲染器
 * 
 * 负责生成安装相关的页面，包括重定向页面和错误页面
 * 符合重构文档的分层架构要求
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class InstallationViewRenderer
{
    /**
     * 渲染重定向页面
     * 
     * @param string $installUrl 安装程序URL
     * @return string HTML内容
     */
    public function renderRedirectPage(string $installUrl): string
    {
        $title = 'Video-Reward 系统安装';
        $message = '系统尚未安装，正在跳转到安装程序...';
        
        return $this->renderBasePage($title, $this->getRedirectPageContent($installUrl, $message));
    }
    
    /**
     * 渲染错误页面
     * 
     * @param string $title 错误标题
     * @param string $message 错误消息
     * @param int $code 错误代码
     * @param string $level 错误级别
     * @return string HTML内容
     */
    public function renderErrorPage(string $title, string $message, int $code = 500, string $level = 'error'): string
    {
        return $this->renderBasePage($title, $this->getErrorPageContent($title, $message, $code, $level));
    }
    
    /**
     * 渲染基础页面模板
     * 
     * @param string $title 页面标题
     * @param string $content 页面内容
     * @return string HTML内容
     */
    private function renderBasePage(string $title, string $content): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--gray-900);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }
        
        .message {
            color: var(--gray-600);
            margin-bottom: 2rem;
            line-height: 1.5;
        }
        
        .progress-bar {
            background: var(--gray-100);
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .progress-fill {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
            height: 100%;
            border-radius: 4px;
            animation: progress 3s ease-in-out;
        }
        
        @keyframes progress {
            from { width: 0%; }
            to { width: 100%; }
        }
        
        .countdown {
            color: var(--gray-600);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }
        
        .btn-secondary:hover {
            background: var(--gray-200);
        }
        
        .error-container {
            border-left: 4px solid var(--error-color);
            background: #fef2f2;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: left;
        }
        
        .warning-container {
            border-left: 4px solid var(--warning-color);
            background: #fffbeb;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            text-align: left;
        }
        
        .code {
            background: var(--gray-100);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.875rem;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 1.5rem;
            }
            
            .icon {
                font-size: 3rem;
            }
            
            .title {
                font-size: 1.25rem;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {$content}
    </div>
</body>
</html>
HTML;
    }
    
    /**
     * 获取重定向页面内容
     * 
     * @param string $installUrl 安装URL
     * @param string $message 提示消息
     * @return string HTML内容
     */
    private function getRedirectPageContent(string $installUrl, string $message): string
    {
        $escapedUrl = htmlspecialchars($installUrl, ENT_QUOTES, 'UTF-8');
        
        return <<<HTML
        <div class="icon">🎬</div>
        <h1 class="title">Video-Reward</h1>
        <p class="message">{$message}</p>
        
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        
        <div class="countdown">
            <span id="countdown">3</span> 秒后自动跳转...
        </div>
        
        <div class="actions">
            <a href="{$escapedUrl}" class="btn btn-primary">
                🚀 立即安装
            </a>
        </div>
        
        <script>
            let countdown = 3;
            const countdownElement = document.getElementById('countdown');
            
            const timer = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    window.location.href = '{$escapedUrl}';
                }
            }, 1000);
        </script>
HTML;
    }
    
    /**
     * 获取错误页面内容
     * 
     * @param string $title 错误标题
     * @param string $message 错误消息
     * @param int $code 错误代码
     * @param string $level 错误级别
     * @return string HTML内容
     */
    private function getErrorPageContent(string $title, string $message, int $code, string $level): string
    {
        $icon = $this->getErrorIcon($level);
        $containerClass = $this->getErrorContainerClass($level);
        $escapedTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $escapedMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        
        return <<<HTML
        <div class="icon">{$icon}</div>
        <h1 class="title">{$escapedTitle}</h1>
        
        <div class="{$containerClass}">
            <p><strong>错误信息：</strong>{$escapedMessage}</p>
            <p><strong>错误代码：</strong><span class="code">{$code}</span></p>
        </div>
        
        <div class="actions">
            <button class="btn btn-secondary" onclick="history.back()">
                ← 返回上页
            </button>
            <button class="btn btn-primary" onclick="location.reload()">
                🔄 刷新页面
            </button>
        </div>
HTML;
    }
    
    /**
     * 获取错误图标
     * 
     * @param string $level 错误级别
     * @return string 图标
     */
    private function getErrorIcon(string $level): string
    {
        switch ($level) {
            case 'critical':
                return '🚨';
            case 'error':
                return '❌';
            case 'warning':
                return '⚠️';
            case 'info':
                return 'ℹ️';
            default:
                return '❌';
        }
    }
    
    /**
     * 获取错误容器样式类
     * 
     * @param string $level 错误级别
     * @return string CSS类名
     */
    private function getErrorContainerClass(string $level): string
    {
        switch ($level) {
            case 'critical':
            case 'error':
                return 'error-container';
            case 'warning':
                return 'warning-container';
            default:
                return 'error-container';
        }
    }
}
