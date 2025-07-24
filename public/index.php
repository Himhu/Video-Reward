<?php

declare(strict_types=1);

/**
 * Video-Reward 应用入口文件
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

// 环境检查（使用统一的环境检查服务）
require_once Constants::getComposerAutoloadPath();
use app\Services\System\EnvironmentChecker;

// 执行环境检查
EnvironmentChecker::checkAll();

// 引入ThinkPHP助手函数
$helperFile = Constants::getThinkHelperPath();
if (file_exists($helperFile)) {
    require_once $helperFile;
}

// 使用命名空间
use app\Bootstrap\ApplicationBootstrap;
use app\Exceptions\ApplicationException;
use app\Services\System\InstallationGuard;
use app\Services\View\InstallationViewRenderer;
use app\Helpers\ResponseHelper;

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
    // 使用统一的异常处理
    ResponseHelper::handleException($e, $e->shouldDisplay(), $e->getUserMessage());

} catch (Throwable $e) {
    // 使用统一的异常处理
    ResponseHelper::handleException($e, false, '系统内部错误，请稍后重试');
}

// ============================================================================
// 重构说明：原有的异常处理函数已移至 app\Helpers\ResponseHelper 类中
// 这样可以避免代码重复，并提供更好的可维护性
// ============================================================================
