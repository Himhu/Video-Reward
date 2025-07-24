<?php

declare(strict_types=1);

namespace app\admin\controller;

use think\Response;

/**
 * 后台管理系统入口控制器
 * 
 * 临时控制器，用于处理安装完成后的后台访问
 * 后续将被完整的后台管理系统替换
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class Index
{
    /**
     * 后台首页
     * 
     * @return Response
     */
    public function index(): Response
    {
        // 检查是否已安装
        $lockFile = app()->getRootPath() . 'config/install/lock/install.lock';
        
        if (!file_exists($lockFile)) {
            // 如果未安装，重定向到安装程序
            return redirect('/installer.php');
        }
        
        // 渲染后台欢迎页面
        $html = $this->renderWelcomePage();
        
        return Response::create($html, 'html');
    }
    
    /**
     * 渲染欢迎页面
     * 
     * @return string HTML内容
     */
    private function renderWelcomePage(): string
    {
        $systemName = config('app.video_reward.system_name', 'Video-Reward');
        $version = config('app.video_reward.version', '2.0.0');
        
        return <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$systemName} 后台管理系统</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
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
            padding: 3rem;
            max-width: 600px;
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
        
        .logo {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }
        
        .subtitle {
            color: var(--gray-600);
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .success-badge {
            background: var(--success-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 2rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .info-item {
            background: var(--gray-50);
            padding: 1rem;
            border-radius: 8px;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            color: var(--gray-900);
            font-size: 1rem;
        }
        
        .notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .notice-title {
            font-weight: 600;
            color: #856404;
            margin-bottom: 0.5rem;
        }
        
        .notice-content {
            color: #856404;
            font-size: 0.875rem;
            line-height: 1.5;
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
        
        @media (max-width: 480px) {
            .container {
                padding: 2rem;
            }
            
            .logo {
                font-size: 3rem;
            }
            
            .title {
                font-size: 1.5rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
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
        <div class="logo">🎬</div>
        <h1 class="title">{$systemName}</h1>
        <p class="subtitle">专业的视频内容付费平台</p>
        
        <div class="success-badge">
            ✅ 系统安装成功
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">系统版本</div>
                <div class="info-value">{$version}</div>
            </div>
            <div class="info-item">
                <div class="info-label">安装时间</div>
                <div class="info-value">" . date('Y-m-d H:i:s') . "</div>
            </div>
            <div class="info-item">
                <div class="info-label">PHP版本</div>
                <div class="info-value">" . PHP_VERSION . "</div>
            </div>
            <div class="info-item">
                <div class="info-label">运行环境</div>
                <div class="info-value">" . php_sapi_name() . "</div>
            </div>
        </div>
        
        <div class="notice">
            <div class="notice-title">🚧 开发提示</div>
            <div class="notice-content">
                这是一个临时的欢迎页面。完整的后台管理系统正在开发中，将包括：<br>
                • 用户管理和权限控制<br>
                • 视频内容管理<br>
                • 订单和支付管理<br>
                • 代理分销系统<br>
                • 数据统计和报表
            </div>
        </div>
        
        <div class="actions">
            <a href="/" class="btn btn-secondary">
                🏠 返回首页
            </a>
            <button class="btn btn-primary" onclick="alert('后台管理功能开发中，敬请期待！')">
                ⚙️ 进入管理后台
            </button>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
