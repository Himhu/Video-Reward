<?php

declare(strict_types=1);

/**
 * Video-Reward 系统安装程序 (简化版)
 * 
 * 不依赖完整ThinkPHP框架的轻量级安装程序
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */

// 定义应用常量
define('APP_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('ROOT_PATH', APP_PATH);

// 检查PHP版本
if (version_compare(PHP_VERSION, '7.1.0', '<')) {
    http_response_code(500);
    exit('PHP版本过低，要求PHP 7.1.0或更高版本，当前版本：' . PHP_VERSION);
}

// 检查是否已安装
$lockFile = APP_PATH . 'config' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'lock' . DIRECTORY_SEPARATOR . 'install.lock';
if (file_exists($lockFile)) {
    http_response_code(403);
    exit('系统已安装，如需重新安装请删除锁文件：' . $lockFile);
}

// 自动加载器
$autoloadFile = APP_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (!file_exists($autoloadFile)) {
    http_response_code(500);
    exit('Composer依赖未安装，请运行: composer install');
}

require_once $autoloadFile;

// 引入统一的服务类
use app\Services\System\ConfigService;
use app\Services\System\DatabaseService;

try {
    // 确保运行时目录存在
    $runtimePath = APP_PATH . 'runtime';
    if (!is_dir($runtimePath)) {
        mkdir($runtimePath, 0755, true);
    }
    
    // 处理请求
    if (isAjaxRequest()) {
        handleAjaxRequest();
    } else {
        displayInstallPage();
    }
    
} catch (\Throwable $e) {
    handleError($e);
}

/**
 * 检查是否为AJAX请求
 */
function isAjaxRequest(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * 处理AJAX请求
 */
function handleAjaxRequest(): void
{
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        $params = validateParams();
        $result = performInstall($params);
        
        echo json_encode([
            'code' => $result['success'] ? 1 : 0,
            'msg' => $result['message'],
            'data' => $result['success'] ? ['redirect_url' => $params['admin_url']] : []
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (\Throwable $e) {
        echo json_encode([
            'code' => 0,
            'msg' => '安装失败: ' . $e->getMessage()
        ], JSON_UNESCAPED_UNICODE);
    }
}

/**
 * 显示安装页面
 */
function displayInstallPage(): void
{
    $checks = checkEnvironment();
    $hasError = false;
    $errorMsg = '';
    
    foreach ($checks as $check) {
        if (!$check['status']) {
            $hasError = true;
            $errorMsg = "环境检查失败: {$check['name']}";
            break;
        }
    }
    
    $host = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
    
    echo renderPage($hasError, $errorMsg, $host);
}

/**
 * 环境检查
 */
function checkEnvironment(): array
{
    return [
        'php' => [
            'name' => 'PHP版本',
            'status' => version_compare(PHP_VERSION, '7.1.0', '>=')
        ],
        'pdo' => [
            'name' => 'PDO扩展',
            'status' => extension_loaded('PDO')
        ],
        'config' => [
            'name' => 'config目录权限',
            'status' => is_writable(APP_PATH . 'config')
        ],
        'runtime' => [
            'name' => 'runtime目录权限',
            'status' => is_writable(APP_PATH . 'runtime')
        ]
    ];
}

/**
 * 验证参数
 */
function validateParams(): array
{
    $required = ['hostname', 'hostport', 'database', 'db_username', 'db_password', 
                'prefix', 'admin_url', 'username', 'password'];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new \InvalidArgumentException("字段 {$field} 不能为空");
        }
    }
    
    if (!preg_match('/^[0-9a-zA-Z]+$/', $_POST['admin_url'])) {
        throw new \InvalidArgumentException('后台地址只能包含字母和数字');
    }
    
    if (strlen($_POST['admin_url']) < 2) {
        throw new \InvalidArgumentException('后台地址不能少于2位');
    }
    
    if (strlen($_POST['password']) < 5) {
        throw new \InvalidArgumentException('管理员密码不能少于5位');
    }
    
    return $_POST;
}

/**
 * 执行安装
 */
function performInstall(array $params): array
{
    try {
        // 1. 测试数据库连接
        $pdo = new PDO(
            "mysql:host={$params['hostname']};port={$params['hostport']};charset=utf8mb4",
            $params['db_username'],
            $params['db_password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        // 2. 创建数据库
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$params['database']}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // 3. 导入数据库结构 (使用统一的DatabaseService)
        DatabaseService::importDatabaseStatic($params);

        // 4. 创建管理员账户 (使用统一的DatabaseService)
        DatabaseService::createAdminUserStatic($params);

        // 5. 生成配置文件 (使用统一的ConfigService)
        ConfigService::generateAppConfigStatic($params['admin_url'], APP_PATH);
        ConfigService::generateDatabaseConfigStatic($params, APP_PATH);

        // 6. 创建安装锁文件
        ConfigService::createInstallLockStatic(APP_PATH);

        return ['success' => true, 'message' => '安装成功'];

    } catch (\Throwable $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}







/**
 * 错误处理
 */
function handleError(\Throwable $e): void
{
    http_response_code(500);
    
    if (isAjaxRequest()) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['code' => 0, 'msg' => '安装程序错误: ' . $e->getMessage()]);
    } else {
        echo "<h1>安装程序错误</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

/**
 * 渲染页面
 */
function renderPage(bool $hasError, string $errorMsg, string $host): string
{
    $disabled = $hasError ? 'disabled' : '';
    $errorSection = $hasError ? "<div style='color:red;margin:20px;padding:15px;border:1px solid red;'>{$errorMsg}</div>" : '';
    
    return <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video-Reward 系统安装</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007cba; color: white; padding: 12px 24px; border: none; border-radius: 4px; cursor: pointer; }
        button:disabled { background: #ccc; cursor: not-allowed; }
        .section { border: 1px solid #ddd; padding: 20px; margin: 20px 0; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🎬 Video-Reward 系统安装</h1>
    {$errorSection}
    
    <form id="installForm" onsubmit="return submitForm(event)">
        <div class="section">
            <h3>数据库配置</h3>
            <div class="form-group">
                <label>数据库地址:</label>
                <input type="text" name="hostname" value="localhost" required {$disabled}>
            </div>
            <div class="form-group">
                <label>数据库端口:</label>
                <input type="text" name="hostport" value="3306" required {$disabled}>
            </div>
            <div class="form-group">
                <label>数据库名称:</label>
                <input type="text" name="database" value="video_reward" required {$disabled}>
            </div>
            <div class="form-group">
                <label>数据表前缀:</label>
                <input type="text" name="prefix" value="vr_" required {$disabled}>
            </div>
            <div class="form-group">
                <label>数据库用户名:</label>
                <input type="text" name="db_username" value="root" required {$disabled}>
            </div>
            <div class="form-group">
                <label>数据库密码:</label>
                <input type="password" name="db_password" required {$disabled}>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="cover" value="1"> 覆盖已存在的数据库</label>
            </div>
        </div>
        
        <div class="section">
            <h3>管理员配置</h3>
            <div class="form-group">
                <label>后台访问地址:</label>
                <input type="text" name="admin_url" value="admin" required {$disabled}>
                <small>完整地址: {$host}<span id="preview">admin</span></small>
            </div>
            <div class="form-group">
                <label>管理员账号:</label>
                <input type="text" name="username" value="admin" required {$disabled}>
            </div>
            <div class="form-group">
                <label>管理员密码:</label>
                <input type="password" name="password" required {$disabled}>
            </div>
        </div>
        
        <button type="submit" {$disabled}>开始安装</button>
    </form>
    
    <script>
        document.querySelector('input[name="admin_url"]').addEventListener('input', function() {
            document.getElementById('preview').textContent = this.value || 'admin';
        });
        
        function submitForm(e) {
            e.preventDefault();
            const btn = e.target.querySelector('button');
            btn.disabled = true;
            btn.textContent = '安装中...';
            
            const formData = new FormData(e.target);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.code === 1) {
                    alert('安装成功！即将跳转...');
                    window.location.href = '/' + formData.get('admin_url');
                } else {
                    alert('安装失败：' + data.msg);
                    btn.disabled = false;
                    btn.textContent = '开始安装';
                }
            })
            .catch(err => {
                alert('安装出错：' + err.message);
                btn.disabled = false;
                btn.textContent = '开始安装';
            });
            
            return false;
        }
    </script>
</body>
</html>
HTML;
}
