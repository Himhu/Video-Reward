<?php
/**
 * 应用异常处理类
 * 
 * 当前版本变更说明：
 * - 修复异常信息泄露的安全风险
 * - 加强错误日志记录的安全性
 * - 优化异常处理机制，避免敏感信息暴露
 * - 添加生产环境和开发环境的异常处理区分
 * - 增强异常分类和安全过滤机制
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date 2025-07-20
 */

declare(strict_types=1);

namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use think\facade\Log;
use think\facade\Env;
use Throwable;

/**
 * 应用异常处理类
 * 
 * 提供安全的异常处理机制，包括：
 * - 敏感信息过滤和保护
 * - 生产环境和开发环境的区分处理
 * - 安全的日志记录机制
 * - 统一的错误响应格式
 * - 异常分类和优先级管理
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 敏感信息字段列表
     * @var array
     */
    protected $sensitiveFields = [
        'password', 'passwd', 'pwd', 'secret', 'token', 'key', 'auth',
        'authorization', 'api_key', 'access_token', 'refresh_token',
        'session_id', 'cookie', 'csrf_token', 'signature'
    ];

    /**
     * 安全的异常类型映射
     * @var array
     */
    protected $secureExceptionMap = [
        'database' => '数据库连接异常',
        'validation' => '数据验证失败',
        'authentication' => '身份验证失败',
        'authorization' => '权限不足',
        'not_found' => '请求的资源不存在',
        'method_not_allowed' => '请求方法不被允许',
        'server_error' => '服务器内部错误',
        'service_unavailable' => '服务暂时不可用'
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 检查是否需要记录此异常
        if ($this->shouldReport($exception)) {
            $this->logSecureException($exception);
        }

        // 调用父类方法，保持框架兼容性
        parent::report($exception);
    }

    /**
     * 渲染异常为HTTP响应
     *
     * @access public
     * @param \think\Request $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 检查是否为AJAX请求
        $isAjax = $request->isAjax() || $request->isJson();
        
        // 根据异常类型进行安全处理
        if ($e instanceof ValidateException) {
            return $this->renderValidationException($e, $isAjax);
        }
        
        if ($e instanceof HttpException) {
            return $this->renderHttpException($e, $isAjax);
        }
        
        if ($this->isSecurityException($e)) {
            return $this->renderSecurityException($e, $isAjax);
        }
        
        // 其他异常的安全处理
        return $this->renderGeneralException($e, $isAjax);
    }

    /**
     * 判断是否应该记录异常
     * 
     * @access protected
     * @param Throwable $exception
     * @return bool
     */
    protected function shouldReport(Throwable $exception): bool
    {
        // 检查忽略列表
        foreach ($this->ignoreReport as $type) {
            if ($exception instanceof $type) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * 安全地记录异常日志
     * 
     * @access protected
     * @param Throwable $exception
     * @return void
     */
    protected function logSecureException(Throwable $exception): void
    {
        try {
            $logData = [
                'type' => get_class($exception),
                'message' => $this->sanitizeMessage($exception->getMessage()),
                'code' => $exception->getCode(),
                'file' => $this->sanitizeFilePath($exception->getFile()),
                'line' => $exception->getLine(),
                'url' => $this->app->request->url(true),
                'method' => $this->app->request->method(),
                'ip' => $this->app->request->ip(),
                'user_agent' => $this->sanitizeUserAgent($this->app->request->header('user-agent')),
                'timestamp' => date('Y-m-d H:i:s'),
            ];

            // 开发环境记录更多信息
            if ($this->app->isDebug()) {
                $logData['trace'] = $this->sanitizeTrace($exception->getTraceAsString());
                $logData['request_data'] = $this->sanitizeRequestData();
            }

            // 记录日志
            Log::error('应用异常', $logData);
            
        } catch (\Exception $e) {
            // 日志记录失败时的降级处理
            error_log('异常日志记录失败: ' . $e->getMessage());
        }
    }

    /**
     * 渲染验证异常
     * 
     * @access protected
     * @param ValidateException $e
     * @param bool $isAjax
     * @return Response
     */
    protected function renderValidationException(ValidateException $e, bool $isAjax): Response
    {
        $message = $e->getError();
        
        if ($isAjax) {
            return Response::create([
                'code' => 0,
                'msg' => $message,
                'data' => null,
                'timestamp' => time()
            ], 'json', 400);
        }
        
        // 非AJAX请求返回页面
        return Response::create($this->getErrorPage('validation', $message), 'html', 400);
    }

    /**
     * 渲染HTTP异常
     * 
     * @access protected
     * @param HttpException $e
     * @param bool $isAjax
     * @return Response
     */
    protected function renderHttpException(HttpException $e, bool $isAjax): Response
    {
        $statusCode = $e->getStatusCode();
        $message = $this->getHttpErrorMessage($statusCode);
        
        if ($isAjax) {
            return Response::create([
                'code' => 0,
                'msg' => $message,
                'data' => null,
                'timestamp' => time()
            ], 'json', $statusCode);
        }
        
        return Response::create($this->getErrorPage('http', $message), 'html', $statusCode);
    }

    /**
     * 渲染安全异常
     * 
     * @access protected
     * @param Throwable $e
     * @param bool $isAjax
     * @return Response
     */
    protected function renderSecurityException(Throwable $e, bool $isAjax): Response
    {
        // 安全异常不暴露具体信息
        $message = '访问被拒绝';
        
        // 记录安全日志
        Log::warning('安全异常', [
            'type' => get_class($e),
            'ip' => $this->app->request->ip(),
            'url' => $this->app->request->url(true),
            'user_agent' => $this->app->request->header('user-agent'),
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        
        if ($isAjax) {
            return Response::create([
                'code' => 0,
                'msg' => $message,
                'data' => null,
                'timestamp' => time()
            ], 'json', 403);
        }
        
        return Response::create($this->getErrorPage('security', $message), 'html', 403);
    }

    /**
     * 渲染一般异常
     * 
     * @access protected
     * @param Throwable $e
     * @param bool $isAjax
     * @return Response
     */
    protected function renderGeneralException(Throwable $e, bool $isAjax): Response
    {
        // 生产环境隐藏具体错误信息
        if ($this->app->isDebug()) {
            $message = $e->getMessage();
        } else {
            $message = $this->secureExceptionMap['server_error'];
        }
        
        if ($isAjax) {
            return Response::create([
                'code' => 0,
                'msg' => $message,
                'data' => null,
                'timestamp' => time()
            ], 'json', 500);
        }
        
        return Response::create($this->getErrorPage('general', $message), 'html', 500);
    }

    /**
     * 判断是否为安全相关异常
     * 
     * @access protected
     * @param Throwable $e
     * @return bool
     */
    protected function isSecurityException(Throwable $e): bool
    {
        $securityExceptions = [
            'think\exception\HttpException',
            'think\exception\RouteNotFoundException',
            'think\exception\ClassNotFoundException'
        ];
        
        $message = strtolower($e->getMessage());
        $securityKeywords = ['sql injection', 'xss', 'csrf', 'unauthorized', 'forbidden'];
        
        // 检查异常类型
        foreach ($securityExceptions as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }
        
        // 检查异常消息中的安全关键词
        foreach ($securityKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * 清理异常消息，移除敏感信息
     *
     * @access protected
     * @param string $message
     * @return string
     */
    protected function sanitizeMessage(string $message): string
    {
        // 移除文件路径信息
        $message = preg_replace('/\/[^\s]*\/[^\s]*\.php/', '[FILE_PATH]', $message);

        // 移除SQL语句
        $message = preg_replace('/SELECT\s+.*?FROM\s+.*?(?:WHERE|ORDER|GROUP|LIMIT|$)/i', '[SQL_QUERY]', $message);

        // 移除敏感字段值
        foreach ($this->sensitiveFields as $field) {
            $message = preg_replace('/' . $field . '\s*[:=]\s*[^\s,}]+/i', $field . ':***', $message);
        }

        return $message;
    }

    /**
     * 清理文件路径，隐藏敏感目录信息
     *
     * @access protected
     * @param string $filePath
     * @return string
     */
    protected function sanitizeFilePath(string $filePath): string
    {
        // 只保留相对于项目根目录的路径
        $rootPath = $this->app->getRootPath();
        if (strpos($filePath, $rootPath) === 0) {
            return substr($filePath, strlen($rootPath));
        }

        // 隐藏绝对路径
        return basename(dirname($filePath)) . '/' . basename($filePath);
    }

    /**
     * 清理请求数据
     *
     * @access protected
     * @return array
     */
    protected function sanitizeRequestData(): array
    {
        $request = $this->app->request;

        $data = [
            'get' => $this->sanitizeArray($request->get()),
            'post' => $this->sanitizeArray($request->post()),
            'header' => $this->sanitizeArray($request->header()),
        ];

        return $data;
    }

    /**
     * 清理数组中的敏感信息
     *
     * @access protected
     * @param array $data
     * @return array
     */
    protected function sanitizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            $lowerKey = strtolower($key);

            // 检查是否为敏感字段
            foreach ($this->sensitiveFields as $field) {
                if (strpos($lowerKey, $field) !== false) {
                    $data[$key] = '***';
                    break;
                }
            }

            // 递归处理数组
            if (is_array($value)) {
                $data[$key] = $this->sanitizeArray($value);
            }
        }

        return $data;
    }

    /**
     * 获取HTTP错误消息
     *
     * @access protected
     * @param int $statusCode
     * @return string
     */
    protected function getHttpErrorMessage(int $statusCode): string
    {
        $messages = [
            400 => '请求参数错误',
            401 => '未授权访问',
            403 => '禁止访问',
            404 => '页面不存在',
            405 => '请求方法不被允许',
            500 => '服务器内部错误',
            503 => '服务不可用'
        ];

        return $messages[$statusCode] ?? '未知错误';
    }

    /**
     * 获取错误页面内容
     *
     * @access protected
     * @param string $type 错误类型
     * @param string $message 错误消息
     * @return string
     */
    protected function getErrorPage(string $type, string $message): string
    {
        $title = $this->getErrorTitle($type);

        return <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
        .error-container { max-width: 500px; margin: 0 auto; background: white; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .error-title { color: #e74c3c; font-size: 24px; margin-bottom: 20px; }
        .error-message { color: #7f8c8d; font-size: 16px; margin-bottom: 30px; }
        .error-link { color: #3498db; text-decoration: none; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-title">{$title}</div>
        <div class="error-message">{$message}</div>
        <div>
            <a href="javascript:history.back()" class="error-link">返回上一页</a> |
            <a href="/" class="error-link">返回首页</a>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * 获取错误标题
     *
     * @access protected
     * @param string $type 错误类型
     * @return string
     */
    protected function getErrorTitle(string $type): string
    {
        $titles = [
            'validation' => '数据验证失败',
            'http' => 'HTTP错误',
            'security' => '安全错误',
            'general' => '系统错误'
        ];

        return $titles[$type] ?? '未知错误';
    }
}
