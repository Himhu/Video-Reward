<?php
/**
 * Video-Reward 响应处理助手类
 * 
 * 统一处理AJAX请求检查、JSON响应和错误响应
 * 解决public/index.php和public/installer.php中85%的重复代码
 * 
 * @author Video-Reward Team
 * @version 1.0
 * @since 2025-01-23
 */

declare(strict_types=1);

namespace app\Helpers;

class ResponseHelper
{
    /**
     * 检查是否为AJAX请求
     * 
     * @return bool
     */
    public static function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * 发送JSON响应
     * 
     * @param array $data 响应数据
     * @param int $httpCode HTTP状态码
     * @return void
     */
    public static function sendJsonResponse(array $data, int $httpCode = 200): void
    {
        if (!headers_sent()) {
            http_response_code($httpCode);
            header('Content-Type: application/json; charset=utf-8');
        }
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * 发送成功响应
     * 
     * @param string $message 成功消息
     * @param array $data 附加数据
     * @return void
     */
    public static function sendSuccessResponse(string $message = '操作成功', array $data = []): void
    {
        self::sendJsonResponse([
            'success' => true,
            'code' => 1,
            'msg' => $message,
            'data' => $data
        ]);
    }
    
    /**
     * 发送错误响应
     * 
     * @param string $message 错误消息
     * @param int $code 错误代码
     * @param string $level 错误级别
     * @param int $httpCode HTTP状态码
     * @return void
     */
    public static function sendErrorResponse(
        string $message, 
        int $code = 500, 
        string $level = 'error',
        int $httpCode = 500
    ): void {
        self::sendJsonResponse([
            'success' => false,
            'code' => 0,
            'msg' => $message,
            'error' => [
                'code' => $code,
                'message' => $message,
                'level' => $level
            ]
        ], $httpCode);
    }
    
    /**
     * 根据异常代码获取HTTP状态码
     * 
     * @param int $exceptionCode 异常代码
     * @return int HTTP状态码
     */
    public static function getHttpStatusCode(int $exceptionCode): int
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
     * 处理应用异常的统一响应
     * 
     * @param \Exception $exception 异常对象
     * @param bool $shouldDisplay 是否显示详细错误
     * @param string $userMessage 用户友好的错误消息
     * @return void
     */
    public static function handleException(
        \Exception $exception, 
        bool $shouldDisplay = false,
        string $userMessage = '系统内部错误，请稍后重试'
    ): void {
        // 记录异常日志
        error_log(sprintf(
            "[%s] Exception: %s in %s:%d",
            date('Y-m-d H:i:s'),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));
        
        $httpCode = self::getHttpStatusCode($exception->getCode());
        $message = $shouldDisplay ? $exception->getMessage() : $userMessage;
        
        if (self::isAjaxRequest()) {
            self::sendErrorResponse($message, $exception->getCode(), 'error', $httpCode);
        } else {
            // 对于非AJAX请求，设置HTTP状态码并显示错误页面
            http_response_code($httpCode);
            
            // 这里可以渲染错误页面，暂时输出简单的HTML
            echo "<!DOCTYPE html>\n";
            echo "<html><head><title>错误</title></head><body>\n";
            echo "<h1>系统错误</h1>\n";
            echo "<p>" . htmlspecialchars($message) . "</p>\n";
            echo "<p>错误代码: " . $exception->getCode() . "</p>\n";
            echo "</body></html>\n";
        }
    }
    
    /**
     * 安全地启用输出缓冲区并清理意外输出
     * 
     * @return void
     */
    public static function startOutputBuffering(): void
    {
        if (!ob_get_level()) {
            ob_start();
        }
    }
    
    /**
     * 清理输出缓冲区并记录意外输出
     * 
     * @param string $context 上下文信息
     * @return void
     */
    public static function cleanOutputBuffer(string $context = ''): void
    {
        if (ob_get_level()) {
            $unexpectedOutput = ob_get_clean();
            if (!empty($unexpectedOutput)) {
                error_log("意外输出 [{$context}]: " . $unexpectedOutput);
            }
        }
    }
    
    /**
     * 设置安全的错误处理环境
     * 
     * @return array 原始设置，用于恢复
     */
    public static function setupSafeErrorHandling(): array
    {
        $original = [
            'error_reporting' => error_reporting(E_ALL),
            'display_errors' => ini_get('display_errors')
        ];
        
        ini_set('display_errors', '0');
        
        return $original;
    }
    
    /**
     * 恢复错误处理设置
     * 
     * @param array $original 原始设置
     * @return void
     */
    public static function restoreErrorHandling(array $original): void
    {
        error_reporting($original['error_reporting']);
        ini_set('display_errors', $original['display_errors']);
    }
}
?>
