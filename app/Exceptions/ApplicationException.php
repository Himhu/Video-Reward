<?php

declare(strict_types=1);

namespace app\Exceptions;

use Exception;
use Throwable;

/**
 * 应用异常类
 * 
 * 处理应用启动和运行过程中的异常情况
 * 提供详细的错误信息和上下文
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */
class ApplicationException extends Exception
{
    private array $context;

    /**
     * 构造函数
     *
     * @param string $message 异常消息
     * @param int $code 异常代码
     * @param Throwable|null $previous 前一个异常
     * @param array $context 异常上下文
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        array $context = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * 获取异常上下文
     *
     * @return array 上下文信息
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * 设置异常上下文
     *
     * @param array $context 上下文信息
     * @return self
     */
    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * 添加上下文信息
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
     * 转换为数组格式
     *
     * @return array 异常信息数组
     */
    public function toArray(): array
    {
        return [
            'type' => static::class,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'context' => $this->context,
            'trace' => $this->getTraceAsString()
        ];
    }

    /**
     * 转换为JSON格式
     *
     * @return string JSON字符串
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * 创建环境异常
     *
     * @param string $message 异常消息
     * @param array $context 上下文信息
     * @return static
     */
    public static function environment(string $message, array $context = []): self
    {
        return new static($message, 1001, null, $context);
    }

    /**
     * 创建安全异常
     *
     * @param string $message 异常消息
     * @param array $context 上下文信息
     * @return static
     */
    public static function security(string $message, array $context = []): self
    {
        return new static($message, 1002, null, $context);
    }

    /**
     * 创建安装异常
     *
     * @param string $message 异常消息
     * @param array $context 上下文信息
     * @return static
     */
    public static function installation(string $message, array $context = []): self
    {
        return new static($message, 1003, null, $context);
    }

    /**
     * 创建配置异常
     *
     * @param string $message 异常消息
     * @param array $context 上下文信息
     * @return static
     */
    public static function configuration(string $message, array $context = []): self
    {
        return new static($message, 1004, null, $context);
    }

    /**
     * 获取用户友好的错误消息
     *
     * @return string 用户友好的错误消息
     */
    public function getUserMessage(): string
    {
        switch ($this->getCode()) {
            case 1001:
                return '系统运行环境不满足要求，请检查服务器配置';
            case 1002:
                return '安全验证失败，请检查请求来源';
            case 1003:
                return '系统尚未安装或安装不完整，请重新安装';
            case 1004:
                return '系统配置错误，请检查配置文件';
            default:
                return '系统启动失败，请联系管理员';
        }
    }

    /**
     * 获取错误级别
     *
     * @return string 错误级别
     */
    public function getLevel(): string
    {
        switch ($this->getCode()) {
            case 1001:
            case 1003:
                return 'critical';
            case 1002:
                return 'warning';
            case 1004:
                return 'error';
            default:
                return 'error';
        }
    }

    /**
     * 是否应该记录到日志
     *
     * @return bool 是否记录日志
     */
    public function shouldLog(): bool
    {
        return true;
    }

    /**
     * 是否应该显示给用户
     *
     * @return bool 是否显示给用户
     */
    public function shouldDisplay(): bool
    {
        // 安全相关的异常不应该显示详细信息给用户
        return $this->getCode() !== 1002;
    }
}
