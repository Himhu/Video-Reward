<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video-Reward 系统安装</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="installer-container">
        <!-- 顶部品牌区域 -->
        <div class="brand-section">
            <div class="brand-logo">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" rx="12" fill="url(#gradient)"/>
                    <path d="M16 14L32 24L16 34V14Z" fill="white"/>
                    <defs>
                        <linearGradient id="gradient" x1="0" y1="0" x2="48" y2="48" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#667eea"/>
                            <stop offset="1" stop-color="#764ba2"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h1 class="brand-title">Video-Reward</h1>
            <p class="brand-subtitle">专业视频内容付费平台</p>
        </div>

        <!-- 主要安装区域 -->
        <div class="installer-main">
            <div class="installer-card">
                <div class="card-header">
                    <h2>系统安装</h2>
                    <p>请填写以下信息完成系统安装</p>
                </div>

                <?php if ($hasError): ?>
                <div class="alert alert-error">
                    <div class="alert-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="alert-content">
                        <strong>环境检查失败</strong>
                        <p><?= htmlspecialchars($errorMsg) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <form id="installForm" onsubmit="return submitForm(event)">
                    <!-- 数据库配置区域 -->
                    <div class="config-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"/>
                                    <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                                    <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                                </svg>
                            </div>
                            <div class="section-title">
                                <h3>数据库配置</h3>
                                <p>配置系统数据库连接信息</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="input-group">
                                <label class="input-label">数据库地址</label>
                                <input type="text" name="hostname" value="localhost" required <?= $disabled ?>
                                       class="form-input" placeholder="localhost">
                            </div>
                            <div class="input-group">
                                <label class="input-label">端口</label>
                                <input type="text" name="hostport" value="3306" required <?= $disabled ?>
                                       class="form-input" placeholder="3306">
                            </div>
                            <div class="input-group full-width">
                                <label class="input-label">数据库名称</label>
                                <input type="text" name="database" value="" required <?= $disabled ?>
                                       class="form-input" placeholder="video_reward">
                                <span class="input-hint">如果数据库不存在，系统将自动创建</span>
                            </div>
                            <div class="input-group">
                                <label class="input-label">表前缀</label>
                                <input type="text" name="prefix" value="ds_" required <?= $disabled ?>
                                       class="form-input" placeholder="ds_">
                            </div>
                            <div class="input-group">
                                <label class="input-label">用户名</label>
                                <input type="text" name="db_username" value="" required <?= $disabled ?>
                                       class="form-input" placeholder="root">
                            </div>
                            <div class="input-group">
                                <label class="input-label">密码</label>
                                <input type="password" name="db_password" <?= $disabled ?>
                                       class="form-input" placeholder="请输入数据库密码">
                            </div>
                        </div>

                        <div class="advanced-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="cover" value="1" id="cover" <?= $disabled ?> onchange="toggleCleanMode()">
                                <span class="checkbox-custom"></span>
                                <span class="checkbox-text">覆盖已存在的数据库</span>
                                <span class="warning-badge">危险操作</span>
                            </label>
                        </div>

                        <div id="cleanModeSection" class="clean-mode-panel" style="display: none;">
                            <div class="panel-header">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>数据库清理模式</span>
                            </div>

                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="clean_mode" value="smart" checked <?= $disabled ?>>
                                    <span class="radio-custom"></span>
                                    <div class="radio-content">
                                        <span class="radio-title">智能清理</span>
                                        <span class="radio-desc">仅删除Video-Reward相关表，保留其他数据</span>
                                        <span class="badge badge-success">推荐</span>
                                    </div>
                                </label>

                                <label class="radio-label">
                                    <input type="radio" name="clean_mode" value="full" <?= $disabled ?>>
                                    <span class="radio-custom"></span>
                                    <div class="radio-content">
                                        <span class="radio-title">完全清理</span>
                                        <span class="radio-desc">清空整个数据库，包括其他应用数据</span>
                                        <span class="badge badge-danger">危险</span>
                                    </div>
                                </label>
                            </div>

                            <div class="warning-notice">
                                <div class="notice-header">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                                    </svg>
                                    <span>重要提醒</span>
                                </div>
                                <ul class="notice-list">
                                    <li>覆盖安装将永久删除现有数据</li>
                                    <li>请确保已备份重要数据</li>
                                    <li>建议在测试环境中先验证</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- 管理员配置区域 -->
                    <div class="config-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </div>
                            <div class="section-title">
                                <h3>管理员配置</h3>
                                <p>设置系统管理员账户信息</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="input-group full-width">
                                <label class="input-label">后台访问地址</label>
                                <div class="input-with-prefix">
                                    <span class="input-prefix"><?= htmlspecialchars($host) ?></span>
                                    <input type="text" name="admin_url" value="admin" required <?= $disabled ?>
                                           class="form-input" placeholder="admin" id="adminUrlInput">
                                </div>
                                <span class="input-hint">完整地址: <?= htmlspecialchars($host) ?><span id="preview">admin</span></span>
                            </div>
                            <div class="input-group">
                                <label class="input-label">管理员账号</label>
                                <input type="text" name="username" value="admin" required <?= $disabled ?>
                                       class="form-input" placeholder="admin">
                            </div>
                            <div class="input-group">
                                <label class="input-label">管理员密码</label>
                                <input type="password" name="password" required <?= $disabled ?>
                                       class="form-input" placeholder="请设置管理员密码">
                                <span class="input-hint">密码长度不少于5位</span>
                            </div>
                        </div>
                    </div>

                    <!-- 提交按钮区域 -->
                    <div class="submit-section">
                        <button type="submit" class="install-button" <?= $disabled ?>>
                            <svg class="button-icon" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span class="button-text">开始安装</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 加载遮罩 -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner">
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
                <div class="spinner-ring"></div>
            </div>
            <h3 class="loading-title">正在安装系统</h3>
            <p class="loading-text">请稍候，系统正在为您配置环境...</p>
        </div>
    </div>

    <script src="install/assets/js/install.js"></script>
    <script>
        // 控制清理模式选择区域的显示/隐藏
        function toggleCleanMode() {
            const coverCheckbox = document.getElementById('cover');
            const cleanModeSection = document.getElementById('cleanModeSection');

            if (coverCheckbox.checked) {
                cleanModeSection.style.display = 'block';
                cleanModeSection.style.animation = 'slideDown 0.3s ease-out';
            } else {
                cleanModeSection.style.animation = 'slideUp 0.3s ease-out';
                setTimeout(() => {
                    cleanModeSection.style.display = 'none';
                }, 300);
            }
        }

        // 实时预览后台地址
        function updateAdminUrlPreview() {
            const adminUrlInput = document.getElementById('adminUrlInput');
            const previewElement = document.getElementById('preview');

            if (adminUrlInput && previewElement) {
                adminUrlInput.addEventListener('input', function() {
                    previewElement.textContent = this.value || 'admin';
                });
            }
        }

        // 页面加载时初始化
        document.addEventListener('DOMContentLoaded', function() {
            toggleCleanMode();
            updateAdminUrlPreview();
        });
    </script>
</body>
</html>
