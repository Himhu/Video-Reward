<?php

declare(strict_types=1);

/**
 * Video-Reward 应用入口文件
 * 
 * 基于SOLID原则重构的现代化应用入口
 * 采用分层架构和依赖注入设计模式
 * 提供完整的环境检查、安全验证和错误处理机制
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */

// 设置错误报告级别
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 定义应用常量
define('APP_PATH', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
define('ROOT_PATH', APP_PATH);

// 检查PHP版本
if (version_compare(PHP_VERSION, '7.1.0', '<')) {
    http_response_code(500);
    exit('PHP版本过低，要求PHP 7.1.0或更高版本，当前版本：' . PHP_VERSION);
}

// 自动加载器
$autoloadFile = APP_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (!file_exists($autoloadFile)) {
    http_response_code(500);
    exit('Composer依赖未安装，请运行: composer install');
}

require_once $autoloadFile;

// 引入ThinkPHP助手函数
$helperFile = APP_PATH . 'vendor' . DIRECTORY_SEPARATOR . 'topthink' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'helper.php';
if (file_exists($helperFile)) {
    require_once $helperFile;
}

// 使用命名空间
use app\Bootstrap\ApplicationBootstrap;
use app\Exceptions\ApplicationException;

try {
    // 创建应用引导实例
    $bootstrap = new ApplicationBootstrap();
    
    // 启动应用并获取响应
    $response = $bootstrap->boot();
    
    // 发送响应
    $response->send();
    
} catch (ApplicationException $e) {
    // 处理应用异常
    handleApplicationException($e);
    
} catch (Throwable $e) {
    // 处理系统异常
    handleSystemException($e);
}

/**
 * 处理应用异常
 *
 * @param ApplicationException $e 应用异常
 * @return void
 */
function handleApplicationException(ApplicationException $e): void
{
    // 记录异常日志
    if ($e->shouldLog()) {
        error_log(sprintf(
            "[%s] ApplicationException: %s in %s:%d\nContext: %s\nTrace: %s",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            json_encode($e->getContext(), JSON_UNESCAPED_UNICODE),
            $e->getTraceAsString()
        ));
    }

    // 设置HTTP状态码
    $statusCode = getHttpStatusCode($e->getCode());
    http_response_code($statusCode);

    // 判断是否为AJAX请求
    if (isAjaxRequest()) {
        // 返回JSON响应
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => [
                'code' => $e->getCode(),
                'message' => $e->shouldDisplay() ? $e->getMessage() : $e->getUserMessage(),
                'level' => $e->getLevel()
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        // 返回HTML错误页面
        displayErrorPage($e);
    }
}

/**
 * 处理系统异常
 *
 * @param Throwable $e 系统异常
 * @return void
 */
function handleSystemException(Throwable $e): void
{
    // 记录系统异常
    error_log(sprintf(
        "[%s] SystemException: %s in %s:%d\nTrace: %s",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        $e->getTraceAsString()
    ));

    // 设置HTTP状态码
    http_response_code(500);

    // 判断是否为AJAX请求
    if (isAjaxRequest()) {
        // 返回JSON响应
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => [
                'code' => 500,
                'message' => '系统内部错误，请稍后重试',
                'level' => 'critical'
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        // 返回HTML错误页面
        displaySystemErrorPage($e);
    }
}

/**
 * 检查是否为AJAX请求
 *
 * @return bool 是否为AJAX请求
 */
function isAjaxRequest(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * 根据异常代码获取HTTP状态码
 *
 * @param int $exceptionCode 异常代码
 * @return int HTTP状态码
 */
function getHttpStatusCode(int $exceptionCode): int
{
    switch ($exceptionCode) {
        case 1001: // 环境异常
        case 1004: // 配置异常
            return 500;
        case 1002: // 安全异常
            return 403;
        case 1003: // 安装异常
            return 503;
        default:
            return 500;
    }
}

/**
 * 显示应用异常错误页面
 *
 * @param ApplicationException $e 应用异常
 * @return void
 */
function displayErrorPage(ApplicationException $e): void
{
    $title = '应用错误';
    $message = $e->shouldDisplay() ? $e->getMessage() : $e->getUserMessage();
    $code = $e->getCode();
    $level = $e->getLevel();

    echo generateErrorHtml($title, $message, $code, $level);
}

/**
 * 显示系统异常错误页面
 *
 * @param Throwable $e 系统异常
 * @return void
 */
function displaySystemErrorPage(Throwable $e): void
{
    $title = '系统错误';
    $message = '系统内部错误，请稍后重试';
    $code = 500;
    $level = 'critical';

    echo generateErrorHtml($title, $message, $code, $level);
}

/**
 * 生成错误页面HTML
 *
 * @param string $title 错误标题
 * @param string $message 错误消息
 * @param int $code 错误代码
 * @param string $level 错误级别
 * @return string HTML内容
 */
function generateErrorHtml(string $title, string $message, int $code, string $level): string
{
    $levelColor = match($level) {
        'critical' => '#dc3545',
        'error' => '#fd7e14',
        'warning' => '#ffc107',
        default => '#6c757d'
    };

    return <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - Video-Reward</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 0; padding: 0; background: #f8f9fa; }
        .container { max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error-icon { font-size: 48px; color: {$levelColor}; text-align: center; margin-bottom: 20px; }
        .error-title { font-size: 24px; color: #333; text-align: center; margin-bottom: 15px; }
        .error-message { font-size: 16px; color: #666; text-align: center; margin-bottom: 20px; line-height: 1.5; }
        .error-code { font-size: 14px; color: #999; text-align: center; margin-bottom: 30px; }
        .actions { text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 0 5px; }
        .btn:hover { background: #0056b3; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; color: #999; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title">{$title}</h1>
        <p class="error-message">{$message}</p>
        <p class="error-code">错误代码: {$code}</p>
        <div class="actions">
            <a href="javascript:history.back()" class="btn">返回上页</a>
            <a href="/" class="btn">返回首页</a>
        </div>
        <div class="footer">
            Video-Reward System v2.0<br>
            如果问题持续存在，请联系系统管理员
        </div>
    </div>
</body>
</html>
HTML;
}
