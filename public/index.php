<?php
// +----------------------------------------------------------------------
// | Video-Reward 应用入口文件
// +----------------------------------------------------------------------
// | 重构版本：基于SOLID原则和现代化PHP开发模式
// | 功能：应用启动和请求处理的统一入口
// | 设计：职责分离、依赖注入、异常处理、安全加固
// +----------------------------------------------------------------------
// | 版权所有: Video-Reward Team
// | 开源协议: MIT License
// | 遵循规范: PSR-12 编码规范
// +----------------------------------------------------------------------

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| 应用入口点
|--------------------------------------------------------------------------
|
| 这是应用的主入口文件，负责：
| 1. 加载自动加载器
| 2. 初始化应用引导程序
| 3. 处理HTTP请求和响应
| 4. 统一的异常处理
|
| 重构原则：
| - 单一职责：入口文件只负责应用启动
| - 开闭原则：通过依赖注入支持扩展
| - 依赖倒置：依赖抽象而非具体实现
|
*/

// 加载Composer自动加载器
require_once __DIR__ . '/../vendor/autoload.php';

// 引入应用引导类
use App\Bootstrap\ApplicationBootstrap;
use App\Services\Security\CorsService;
use App\Services\System\InstallationService;
use App\Services\System\EnvironmentService;

try {
    // 创建服务实例（依赖注入）
    $environmentService = new EnvironmentService();
    $corsService = new CorsService();
    $installationService = new InstallationService();

    // 创建应用引导程序
    $bootstrap = new ApplicationBootstrap(
        $corsService,
        $installationService,
        $environmentService
    );

    // 启动应用并处理请求
    $response = $bootstrap->bootstrap();

    // 正常情况下，响应已在bootstrap方法中发送
    // 这里主要用于确保脚本正常结束

} catch (Throwable $e) {
    // 最后的异常处理机制
    // 如果应用引导程序也失败了，使用基础的错误处理

    // 记录严重错误
    error_log(sprintf(
        "[%s] CRITICAL: Application bootstrap failed: %s in %s:%d",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    ));

    // 设置HTTP状态码
    http_response_code(500);

    // 根据环境返回不同的错误信息
    $isProduction = ($_ENV['APP_ENV'] ?? 'production') === 'production';

    if ($isProduction) {
        // 生产环境：返回通用错误信息
        $errorResponse = [
            'success' => false,
            'code' => 500,
            'message' => '服务器内部错误，请稍后重试',
            'timestamp' => date('c')
        ];
    } else {
        // 开发环境：返回详细错误信息
        $errorResponse = [
            'success' => false,
            'code' => 500,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'timestamp' => date('c')
        ];
    }

    // 输出JSON格式的错误响应
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($errorResponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// 脚本结束
// 注意：在正常情况下，响应已经在ApplicationBootstrap中发送
// 这里的代码主要用于异常情况的处理
