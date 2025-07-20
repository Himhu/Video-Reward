<?php
/**
 * 应用程序入口文件
 * 
 * 当前版本变更说明：
 * - 修复CORS安全配置问题，实施白名单机制
 * - 移除错误抑制符，增强错误处理
 * - 优化安装状态检查逻辑
 * - 增强输入验证和安全防护
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date 2025-07-20
 */

declare(strict_types=1);

namespace think;

// 引入自动加载器
require __DIR__ . '/../vendor/autoload.php';

/**
 * 安全的CORS配置处理
 * 
 * @return void
 */
function handleCorsHeaders(): void
{
    // 定义允许的域名白名单
    $allowedOrigins = [
        'http://localhost',
        'http://127.0.0.1',
        'https://localhost',
        'https://127.0.0.1'
    ];
    
    // 获取请求来源，进行安全验证
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    
    // 验证来源是否在白名单中
    if (!empty($origin) && in_array($origin, $allowedOrigins, true)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        // 如果不在白名单中，设置为当前域名
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        header('Access-Control-Allow-Origin: ' . $protocol . '://' . $host);
    }
    
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization, x-file-name');
    header('Access-Control-Allow-Credentials: true');
    
    // 处理预检请求
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

/**
 * 检查系统安装状态
 * 
 * @return void
 */
function checkInstallationStatus(): void
{
    // 定义安装锁文件路径
    $lockFile = ROOT_PATH . 'config' . DS . 'install' . DS . 'lock' . DS . 'install.lock';
    
    // 检查安装锁文件是否存在
    if (!is_file($lockFile)) {
        // 构建安装程序URL
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $installUrl = $protocol . '://' . $host . '/install.php';
        
        // 重定向到安装程序
        header('Location: ' . $installUrl);
        exit('系统尚未安装，正在跳转到安装程序...');
    }
}

/**
 * 初始化应用程序常量
 * 
 * @return void
 */
function initializeConstants(): void
{
    // 定义目录分隔符常量
    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }
    
    // 定义根目录路径常量
    if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', dirname(__DIR__) . DS);
    }
}

/**
 * 启动应用程序
 * 
 * @return void
 */
function startApplication(): void
{
    try {
        // 创建应用实例
        $app = new App();
        
        // 获取HTTP应用管理器
        $http = $app->http;
        
        // 运行应用程序并获取响应
        $response = $http->run();
        
        // 发送响应到客户端
        $response->send();
        
        // 执行应用程序结束处理
        $http->end($response);
        
    } catch (\Throwable $e) {
        // 记录错误日志
        error_log('应用程序启动失败: ' . $e->getMessage());
        
        // 在调试模式下显示详细错误信息
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo '<h1>应用程序启动失败</h1>';
            echo '<p>错误信息: ' . htmlspecialchars($e->getMessage()) . '</p>';
            echo '<p>文件: ' . htmlspecialchars($e->getFile()) . '</p>';
            echo '<p>行号: ' . $e->getLine() . '</p>';
        } else {
            // 生产环境下显示友好错误信息
            http_response_code(500);
            echo '系统暂时无法访问，请稍后重试。';
        }
        exit;
    }
}

// ============================================================================
// 主程序执行流程
// ============================================================================

// 1. 初始化应用程序常量
initializeConstants();

// 2. 处理CORS跨域请求头
handleCorsHeaders();

// 3. 检查系统安装状态
checkInstallationStatus();

// 4. 启动应用程序
startApplication();
