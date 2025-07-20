<?php
/**
 * 控制器基础类
 * 
 * 当前版本变更说明：
 * - 修复验证器动态类名解析的安全风险
 * - 加强中间件配置的安全验证
 * - 优化数据验证机制的安全性
 * - 添加基础权限检查机制
 * - 增强错误处理和日志记录
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date 2025-07-20
 */

declare(strict_types=1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\Log;

/**
 * 控制器基础类
 * 
 * 提供所有控制器的基础功能，包括：
 * - 依赖注入管理
 * - 安全的数据验证机制
 * - 中间件支持和验证
 * - 基础权限检查
 * - 统一的错误处理
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 允许的验证器类白名单
     * @var array
     */
    protected $allowedValidators = [
        'User', 'Admin', 'Login', 'Register', 'Config', 'Upload',
        'Category', 'Article', 'Comment', 'Order', 'Payment'
    ];

    /**
     * 构造方法
     * 
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 验证中间件配置安全性
        $this->validateMiddlewareConfig();

        // 控制器初始化
        $this->initialize();
    }

    /**
     * 初始化方法
     * 
     * 子类可以重写此方法来实现自定义初始化逻辑
     * 
     * @access protected
     * @return void
     */
    protected function initialize(): void
    {
        // 子类可重写此方法
    }

    /**
     * 验证数据
     * 
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        try {
            if (is_array($validate)) {
                // 使用数组规则验证
                $v = new Validate();
                $v->rule($validate);
            } else {
                // 使用验证器类验证
                $scene = '';
                if (strpos($validate, '.')) {
                    // 支持场景
                    list($validate, $scene) = explode('.', $validate);
                }
                
                // 获取安全的验证器类
                $class = $this->getSecureValidatorClass($validate);
                $v = new $class();
                
                if (!empty($scene)) {
                    $v->scene($scene);
                }
            }

            $v->message($message);

            // 是否批量验证
            if ($batch || $this->batchValidate) {
                $v->batch(true);
            }

            return $v->failException(true)->check($data);
            
        } catch (ValidateException $e) {
            // 记录验证失败日志
            Log::warning('数据验证失败', [
                'controller' => get_class($this),
                'data' => $this->sanitizeLogData($data),
                'validator' => $validate,
                'error' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            // 记录其他异常
            Log::error('验证过程异常', [
                'controller' => get_class($this),
                'validator' => $validate,
                'error' => $e->getMessage()
            ]);
            throw new ValidateException('验证器配置错误');
        }
    }

    /**
     * 获取安全的验证器类
     * 
     * @access protected
     * @param string $validate 验证器名称
     * @return string 验证器类名
     * @throws ValidateException
     */
    protected function getSecureValidatorClass(string $validate): string
    {
        // 验证器名称安全检查
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*$/', $validate)) {
            throw new ValidateException('验证器名称格式不正确');
        }

        // 检查是否包含命名空间
        if (strpos($validate, '\\') !== false) {
            // 完整类名验证
            if (!$this->isAllowedValidatorClass($validate)) {
                throw new ValidateException('不允许的验证器类');
            }
            $class = $validate;
        } else {
            // 简单类名验证
            if (!in_array($validate, $this->allowedValidators)) {
                throw new ValidateException('验证器不在允许列表中');
            }
            $class = $this->app->parseClass('validate', $validate);
        }

        // 验证类是否存在
        if (!class_exists($class)) {
            throw new ValidateException('验证器类不存在: ' . $validate);
        }

        // 验证类是否继承自Validate
        if (!is_subclass_of($class, Validate::class)) {
            throw new ValidateException('验证器类必须继承自Validate');
        }

        return $class;
    }

    /**
     * 检查是否为允许的验证器类
     * 
     * @access protected
     * @param string $className 类名
     * @return bool
     */
    protected function isAllowedValidatorClass(string $className): bool
    {
        // 只允许app命名空间下的验证器
        $allowedNamespaces = [
            'app\\admin\\validate\\',
            'app\\index\\validate\\',
            'app\\common\\validate\\'
        ];

        foreach ($allowedNamespaces as $namespace) {
            if (strpos($className, $namespace) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 验证中间件配置安全性
     * 
     * @access protected
     * @return void
     * @throws ValidateException
     */
    protected function validateMiddlewareConfig(): void
    {
        if (!is_array($this->middleware)) {
            throw new ValidateException('中间件配置必须是数组');
        }

        foreach ($this->middleware as $middleware) {
            if (is_string($middleware)) {
                // 验证中间件类名安全性
                if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_\\\\]*$/', $middleware)) {
                    throw new ValidateException('中间件类名格式不正确');
                }
            } elseif (is_array($middleware)) {
                // 验证中间件配置数组
                if (!isset($middleware[0]) || !is_string($middleware[0])) {
                    throw new ValidateException('中间件配置格式不正确');
                }
            } else {
                throw new ValidateException('中间件配置类型不正确');
            }
        }
    }

    /**
     * 基础权限检查
     * 
     * @access protected
     * @param string|null $permission 权限标识
     * @return bool
     */
    protected function checkPermission(string $permission = null): bool
    {
        // 基础实现，子类可以重写
        if ($permission === null) {
            return true;
        }

        // 这里可以集成具体的权限检查逻辑
        // 例如检查用户是否有指定权限
        return true;
    }

    /**
     * 清理日志数据，移除敏感信息
     * 
     * @access protected
     * @param array $data 原始数据
     * @return array 清理后的数据
     */
    protected function sanitizeLogData(array $data): array
    {
        $sensitiveFields = ['password', 'token', 'secret', 'key', 'auth'];
        $sanitized = $data;

        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '***';
            }
        }

        return $sanitized;
    }

    /**
     * 获取当前控制器名称
     * 
     * @access protected
     * @return string
     */
    protected function getControllerName(): string
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    /**
     * 获取当前操作名称
     * 
     * @access protected
     * @return string
     */
    protected function getActionName(): string
    {
        return $this->request->action();
    }

    /**
     * 记录控制器访问日志
     * 
     * @access protected
     * @param array $extra 额外信息
     * @return void
     */
    protected function logAccess(array $extra = []): void
    {
        Log::info('控制器访问', array_merge([
            'controller' => $this->getControllerName(),
            'action' => $this->getActionName(),
            'method' => $this->request->method(),
            'ip' => $this->request->ip(),
            'user_agent' => $this->request->header('user-agent'),
            'timestamp' => time()
        ], $extra));
    }
}
