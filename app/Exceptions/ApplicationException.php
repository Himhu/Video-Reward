<?php
// +----------------------------------------------------------------------
// | 应用异常类
// +----------------------------------------------------------------------
// | 功能：应用级别的异常处理
// | 职责：统一的异常处理和错误信息管理
// | 设计：可扩展的异常处理体系
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * 应用异常基类
 * 
 * 提供统一的异常处理机制：
 * - 结构化错误信息
 * - 错误代码管理
 * - 异常链支持
 * - 调试信息收集
 * 
 * @package App\Exceptions
 * @author Video-Reward Team
 * @version 1.0.0
 */
class ApplicationException extends Exception
{
    /**
     * 异常上下文数据
     */
    protected array $context = [];

    /**
     * 错误级别
     */
    protected string $level = 'error';

    /**
     * 构造函数
     * 
     * @param string $message 异常消息
     * @param int $code 异常代码
     * @param Throwable|null $previous 前一个异常
     * @param array $context 上下文数据
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * 获取上下文数据
     * 
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * 设置上下文数据
     * 
     * @param array $context 上下文数据
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * 添加上下文数据
     * 
     * @param string $key 键名
     * @param mixed $value 值
     * @return self
     */
    public function addContext(string $key, $value): self
    {
        $this->context[$key] = $value;
        return $this;
    }

    /**
     * 获取错误级别
     * 
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * 设置错误级别
     * 
     * @param string $level 错误级别
     * @return self
     */
    public function setLevel(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    /**
     * 转换为数组格式
     * 
     * @param bool $includeTrace 是否包含堆栈跟踪
     * @return array
     */
    public function toArray(bool $includeTrace = false): array
    {
        $data = [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'level' => $this->level,
            'context' => $this->context,
            'timestamp' => date('c')
        ];

        if ($includeTrace) {
            $data['trace'] = $this->getTraceAsString();
        }

        if ($this->getPrevious()) {
            $data['previous'] = [
                'message' => $this->getPrevious()->getMessage(),
                'code' => $this->getPrevious()->getCode(),
                'file' => $this->getPrevious()->getFile(),
                'line' => $this->getPrevious()->getLine()
            ];
        }

        return $data;
    }

    /**
     * 转换为JSON格式
     * 
     * @param bool $includeTrace 是否包含堆栈跟踪
     * @return string
     */
    public function toJson(bool $includeTrace = false): string
    {
        return json_encode(
            $this->toArray($includeTrace),
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }

    /**
     * 获取用户友好的错误消息
     * 
     * @return string
     */
    public function getUserMessage(): string
    {
        // 在生产环境中，可能需要返回更友好的错误消息
        return $this->getMessage();
    }

    /**
     * 检查是否为严重错误
     * 
     * @return bool
     */
    public function isCritical(): bool
    {
        return in_array($this->level, ['critical', 'emergency']);
    }

    /**
     * 检查是否需要记录日志
     * 
     * @return bool
     */
    public function shouldLog(): bool
    {
        return !in_array($this->level, ['debug']);
    }

    /**
     * 获取HTTP状态码
     * 
     * @return int
     */
    public function getHttpStatusCode(): int
    {
        // 根据异常代码映射HTTP状态码
        $statusCodeMap = [
            400 => 400, // Bad Request
            401 => 401, // Unauthorized
            403 => 403, // Forbidden
            404 => 404, // Not Found
            422 => 422, // Unprocessable Entity
            429 => 429, // Too Many Requests
            500 => 500, // Internal Server Error
            502 => 502, // Bad Gateway
            503 => 503, // Service Unavailable
        ];

        return $statusCodeMap[$this->getCode()] ?? 500;
    }
}

/**
 * 安全异常类
 */
class SecurityException extends ApplicationException
{
    protected string $level = 'warning';
}

/**
 * 安装异常类
 */
class InstallationException extends ApplicationException
{
    protected string $level = 'error';
}

/**
 * 环境异常类
 */
class EnvironmentException extends ApplicationException
{
    protected string $level = 'critical';
}

/**
 * 配置异常类
 */
class ConfigurationException extends ApplicationException
{
    protected string $level = 'error';
}

/**
 * 验证异常类
 */
class ValidationException extends ApplicationException
{
    protected string $level = 'warning';
    
    /**
     * 验证错误详情
     */
    protected array $errors = [];

    /**
     * 设置验证错误
     * 
     * @param array $errors 验证错误
     * @return self
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * 获取验证错误
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * 转换为数组格式
     * 
     * @param bool $includeTrace 是否包含堆栈跟踪
     * @return array
     */
    public function toArray(bool $includeTrace = false): array
    {
        $data = parent::toArray($includeTrace);
        $data['errors'] = $this->errors;
        return $data;
    }
}
