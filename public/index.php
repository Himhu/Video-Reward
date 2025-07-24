<?php

declare(strict_types=1);

/**
 * Video-Reward 应用入口文件
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
use app\Services\System\InstallationGuard;
use app\Services\View\InstallationViewRenderer;

// ============================================================================
// 安装状态检查
// ============================================================================

// 检查安装状态，如果未安装则自动重定向
InstallationGuard::checkAndRedirect(APP_PATH);

// ============================================================================
// 应用启动
// ============================================================================

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

// ============================================================================
// 异常处理函数
// ============================================================================

/**
 * 处理应用异常
 */
function handleApplicationException(ApplicationException $e): void
{
    // 记录异常日志
    if ($e->shouldLog()) {
        error_log(sprintf(
            "[%s] ApplicationException: %s in %s:%d",
            date('Y-m-d H:i:s'),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));
    }

    // 设置HTTP状态码
    http_response_code(getHttpStatusCode($e->getCode()));

    // 判断是否为AJAX请求
    if (isAjaxRequest()) {
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
        $viewRenderer = new InstallationViewRenderer();
        echo $viewRenderer->renderErrorPage(
            '应用异常',
            $e->shouldDisplay() ? $e->getMessage() : $e->getUserMessage(),
            $e->getCode(),
            $e->getLevel()
        );
    }
}

/**
 * 处理系统异常
 */
function handleSystemException(Throwable $e): void
{
    // 记录系统异常
    error_log(sprintf(
        "[%s] SystemException: %s in %s:%d",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    ));

    http_response_code(500);

    if (isAjaxRequest()) {
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
        $viewRenderer = new InstallationViewRenderer();
        echo $viewRenderer->renderErrorPage(
            '系统错误',
            '系统内部错误，请稍后重试',
            500,
            'critical'
        );
    }
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
 * 根据异常代码获取HTTP状态码
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
