<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统错误 - Video-Reward</title>
    <style>
        :root {
            --primary-color: #6366f1;
            --error-color: #dc3545;
            --warning-color: #ffc107;
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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, var(--gray-50) 0%, var(--gray-100) 100%);
            color: var(--gray-900);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }
        
        .error-header {
            background: var(--error-color);
            color: white;
            padding: 24px;
            text-align: center;
        }
        
        .error-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
        
        .error-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .error-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .error-content {
            padding: 32px;
        }
        
        .error-message {
            background: var(--gray-50);
            border-left: 4px solid var(--error-color);
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 0 8px 8px 0;
        }
        
        .error-message h3 {
            color: var(--error-color);
            margin-bottom: 8px;
            font-size: 16px;
        }
        
        .error-message p {
            color: var(--gray-700);
            margin: 0;
        }
        
        .error-details {
            background: var(--gray-50);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: var(--gray-600);
            overflow-x: auto;
        }
        
        .error-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
        }
        
        .btn-secondary:hover {
            background: var(--gray-200);
        }
        
        .system-info {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--gray-100);
            font-size: 12px;
            color: var(--gray-600);
        }
        
        .system-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }
        
        .system-info-item {
            background: var(--gray-50);
            padding: 8px 12px;
            border-radius: 6px;
        }
        
        .system-info-label {
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .system-info-value {
            color: var(--gray-600);
        }
        
        @media (max-width: 640px) {
            .error-container {
                margin: 10px;
            }
            
            .error-header {
                padding: 20px;
            }
            
            .error-content {
                padding: 24px;
            }
            
            .error-actions {
                flex-direction: column;
            }
            
            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            <div class="error-icon">⚠️</div>
            <h1 class="error-title">系统错误</h1>
            <p class="error-subtitle">Video-Reward 系统遇到了一个错误</p>
        </div>
        
        <div class="error-content">
            <div class="error-message">
                <h3>错误信息</h3>
                <p><?php echo isset($message) ? htmlspecialchars($message) : '系统内部错误，请稍后重试'; ?></p>
            </div>
            
            <?php if (isset($file) && isset($line)): ?>
            <div class="error-details">
                <strong>错误位置：</strong><?php echo htmlspecialchars($file); ?> (第 <?php echo $line; ?> 行)
                <?php if (isset($code) && $code): ?>
                <br><strong>错误代码：</strong><?php echo htmlspecialchars($code); ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="error-actions">
                <a href="/" class="btn btn-primary">
                    🏠 返回首页
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    ← 返回上页
                </a>
                <a href="javascript:location.reload()" class="btn btn-secondary">
                    🔄 刷新页面
                </a>
            </div>
            
            <div class="system-info">
                <div class="system-info-label">系统信息</div>
                <div class="system-info-grid">
                    <div class="system-info-item">
                        <div class="system-info-label">时间</div>
                        <div class="system-info-value"><?php echo date('Y-m-d H:i:s'); ?></div>
                    </div>
                    <div class="system-info-item">
                        <div class="system-info-label">PHP版本</div>
                        <div class="system-info-value"><?php echo PHP_VERSION; ?></div>
                    </div>
                    <div class="system-info-item">
                        <div class="system-info-label">请求方法</div>
                        <div class="system-info-value"><?php echo $_SERVER['REQUEST_METHOD'] ?? 'Unknown'; ?></div>
                    </div>
                    <div class="system-info-item">
                        <div class="system-info-label">请求URI</div>
                        <div class="system-info-value"><?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
