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

// 初始化系统常量
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Common' . DIRECTORY_SEPARATOR . 'Constants.php';
use app\Common\Constants;

// 初始化路径常量
Constants::initializePaths(__DIR__);

// 设置错误处理，防止HTML错误输出污染JSON响应
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// 设置自定义错误处理器
set_error_handler(function($severity, $message, $file, $line) {
    // 记录错误到日志而不是输出
    error_log("PHP Error: [$severity] $message in $file on line $line");

    // 对于AJAX请求，不输出任何内容
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        return true; // 阻止默认错误输出
    }

    return false; // 让PHP处理其他情况
});

// 环境检查（使用统一的环境检查服务）
require_once Constants::getComposerAutoloadPath();
use app\Services\System\EnvironmentChecker;
use app\Helpers\ResponseHelper;

// 执行环境检查
EnvironmentChecker::checkAll();

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
    if (ResponseHelper::isAjaxRequest()) {
        handleAjaxRequest();
    } else {
        displayInstallPage();
    }
    
} catch (\Throwable $e) {
    handleError($e);
}

// 重构说明：isAjaxRequest() 函数已移至 app\Helpers\ResponseHelper 类中

/**
 * 处理AJAX请求
 */
function handleAjaxRequest(): void
{
    // 使用统一的输出缓冲和错误处理
    ResponseHelper::startOutputBuffering();
    $originalSettings = ResponseHelper::setupSafeErrorHandling();

    try {
        $params = validateParams();
        $result = performInstall($params);

        // 清理意外输出
        ResponseHelper::cleanOutputBuffer('安装程序');

        // 发送响应
        if ($result['success']) {
            ResponseHelper::sendSuccessResponse($result['message'], ['redirect_url' => $params['admin_url']]);
        } else {
            ResponseHelper::sendErrorResponse($result['message']);
        }

    } catch (\Throwable $e) {
        // 清理输出缓冲区
        ResponseHelper::cleanOutputBuffer('安装程序异常');

        // 发送错误响应
        ResponseHelper::sendErrorResponse('安装失败: ' . $e->getMessage());

    } finally {
        // 恢复原始错误设置
        ResponseHelper::restoreErrorHandling($originalSettings);
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

    // 处理覆盖选项
    $params = $_POST;
    $params['cover'] = isset($_POST['cover']) && $_POST['cover'] == '1';

    // 处理清理模式选项
    $params['clean_mode'] = $_POST['clean_mode'] ?? 'smart'; // smart: 智能清理, full: 完全清理

    return $params;
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

        // 5. 生成配置文件 (使用统一的ConfigService，职责分离)
        ConfigService::generateAppConfigStatic($params['admin_url'], APP_PATH);           // 生成 config/app.php
        ConfigService::generateDatabaseConfigStatic($params, APP_PATH);                   // 生成 config/database.php
        ConfigService::generateEnvFileStatic($params['admin_url'], $params, APP_PATH);    // 生成 .env 文件

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

    // 准备模板变量
    $templateVars = [
        'hasError' => $hasError,
        'errorMsg' => $errorMsg,
        'host' => $host,
        'disabled' => $disabled
    ];

    // 渲染模板
    return renderTemplate('install', $templateVars);
}

/**
 * 简单的模板渲染器
 */
function renderTemplate(string $template, array $vars = []): string
{
    $templateFile = __DIR__ . '/install/templates/' . $template . '.php';

    if (!file_exists($templateFile)) {
        throw new \InvalidArgumentException("模板文件不存在: {$template}");
    }

    // 提取变量到当前作用域
    extract($vars, EXTR_SKIP);

    // 开启输出缓冲
    ob_start();

    try {
        include $templateFile;
        return ob_get_clean();
    } catch (\Throwable $e) {
        ob_end_clean();
        throw $e;
    }
}
