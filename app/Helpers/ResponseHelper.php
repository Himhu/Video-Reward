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
     * @param \Throwable $exception 异常对象 (包括Exception和Error)
     * @param bool $shouldDisplay 是否显示详细错误
     * @param string $userMessage 用户友好的错误消息
     * @return void
     */
    public static function handleException(
        \Throwable $exception,
        bool $shouldDisplay = false,
        string $userMessage = '系统内部错误，请稍后重试'
    ): void {
        // 记录异常日志 (包含异常类型信息)
        $exceptionType = get_class($exception);
        error_log(sprintf(
            "[%s] %s: %s in %s:%d",
            date('Y-m-d H:i:s'),
            $exceptionType,  // 记录具体异常类型 (Exception/Error)
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ));

        // 记录堆栈跟踪 (仅在开发环境或Error类型时)
        if ($exception instanceof \Error || (defined('APP_DEBUG') && APP_DEBUG)) {
            error_log("Stack trace:\n" . $exception->getTraceAsString());
        }
        
        $httpCode = self::getHttpStatusCode($exception->getCode());

        // 区分Error和Exception的处理
        // 对于Error类型，默认不显示详细信息给用户 (安全考虑)
        if ($exception instanceof \Error && !$shouldDisplay) {
            $message = $userMessage;
            $displayDetails = false;
        } else {
            $message = $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine(); // 临时强制显示详细错误
            $displayDetails = true;
        }
        
        if (self::isAjaxRequest()) {
            self::sendErrorResponse($message, $exception->getCode(), 'error', $httpCode);
        } else {
            // 对于非AJAX请求，设置HTTP状态码并显示错误页面
            http_response_code($httpCode);

            // 渲染用户友好的错误页面
            echo "<!DOCTYPE html>\n";
            echo "<html><head>\n";
            echo "<title>系统错误</title>\n";
            echo "<meta charset='UTF-8'>\n";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
            echo "<style>body{font-family:Arial,sans-serif;margin:40px;color:#333;} .error{background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:20px;} .error-title{color:#dc3545;margin-bottom:10px;}</style>\n";
            echo "</head><body>\n";
            echo "<div class='error'>\n";
            echo "<h1 class='error-title'>系统错误</h1>\n";
            echo "<p>" . htmlspecialchars($message) . "</p>\n";

            // 根据异常类型和显示设置决定是否显示详细信息
            if ($displayDetails) {
                $exceptionType = get_class($exception);
                echo "<hr>\n";
                echo "<p><strong>错误类型:</strong> " . htmlspecialchars($exceptionType) . "</p>\n";
                if ($exception->getCode()) {
                    echo "<p><strong>错误代码:</strong> " . $exception->getCode() . "</p>\n";
                }
                echo "<p><strong>文件位置:</strong> " . htmlspecialchars($exception->getFile()) . ":" . $exception->getLine() . "</p>\n";
            }

            echo "</div>\n";
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
