<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video-Reward 系统安装</title>
    <link rel="stylesheet" href="install/assets/css/install.css">
</head>
<body>
    <div class="container">
        <div class="install-card">
            <div class="install-header">
                <h1>🎬 Video-Reward</h1>
                <p class="subtitle">专业的视频内容付费平台安装向导</p>
            </div>
            
            <div class="install-content">
                <?php if ($hasError): ?>
                <div class="error-alert">
                    <div class="error-icon">⚠️</div>
                    <div class="error-content">
                        <strong>环境检查失败</strong>
                        <p><?= htmlspecialchars($errorMsg) ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <form id="installForm" onsubmit="return submitForm(event)">
                    <div class="section">
                        <h3>数据库配置</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>数据库地址</label>
                                <input type="text" name="hostname" value="localhost" required <?= $disabled ?> placeholder="localhost">
                            </div>
                            <div class="form-group">
                                <label>数据库端口</label>
                                <input type="text" name="hostport" value="3306" required <?= $disabled ?> placeholder="3306">
                            </div>
                            <div class="form-group full-width">
                                <label>数据库名称</label>
                                <input type="text" name="database" value="" required <?= $disabled ?> placeholder="video_reward">
                                <small>如果数据库不存在，系统将自动创建</small>
                            </div>
                            <div class="form-group">
                                <label>数据表前缀</label>
                                <input type="text" name="prefix" value="ds_" required <?= $disabled ?> placeholder="ds_">
                            </div>
                            <div class="form-group">
                                <label>数据库用户名</label>
                                <input type="text" name="db_username" value="" required <?= $disabled ?> placeholder="root">
                            </div>
                            <div class="form-group">
                                <label>数据库密码</label>
                                <input type="password" name="db_password" <?= $disabled ?> placeholder="请输入数据库密码">
                            </div>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" name="cover" value="1" id="cover" <?= $disabled ?>>
                            <label for="cover">覆盖已存在的数据库（谨慎操作）</label>
                        </div>
                    </div>
                    
                    <div class="section">
                        <h3>管理员配置</h3>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label>后台访问地址</label>
                                <input type="text" name="admin_url" value="admin" required <?= $disabled ?> placeholder="admin">
                                <small>完整地址: <?= htmlspecialchars($host) ?><span id="preview">admin</span></small>
                            </div>
                            <div class="form-group">
                                <label>管理员账号</label>
                                <input type="text" name="username" value="admin" required <?= $disabled ?> placeholder="admin">
                            </div>
                            <div class="form-group">
                                <label>管理员密码</label>
                                <input type="password" name="password" required <?= $disabled ?> placeholder="请设置管理员密码">
                                <small>密码长度不少于5位</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="submit-section">
                        <button type="submit" class="btn-primary" <?= $disabled ?>>
                            <span class="btn-text">开始安装</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- 加载遮罩 -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p>正在安装系统，请稍候...</p>
        </div>
    </div>
    
    <script src="install/assets/js/install.js"></script>
</body>
</html>
