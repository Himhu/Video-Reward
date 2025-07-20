<?php
/**
 * 应用请求处理类
 * 
 * 当前版本变更说明：
 * - 修复请求参数过滤的安全风险
 * - 加强输入验证和数据清理机制
 * - 优化请求处理的安全性，防止注入攻击
 * - 添加请求频率限制和安全检查
 * - 增强恶意请求检测和防护机制
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date 2025-07-20
 */

declare(strict_types=1);

namespace app;

use think\Request as ThinkRequest;
use think\facade\Log;
use think\facade\Cache;
use think\exception\ValidateException;

/**
 * 应用请求处理类
 * 
 * 提供安全的请求处理机制，包括：
 * - 多层次输入过滤和验证
 * - 恶意请求检测和防护
 * - 请求频率限制和安全检查
 * - SQL注入和XSS攻击防护
 * - 文件上传安全验证
 */
class Request extends ThinkRequest
{
    /**
     * 全局过滤器配置
     * @var array
     */
    protected $filter = ['trim', 'htmlspecialchars'];

    /**
     * 危险字符模式列表
     * @var array
     */
    protected $dangerousPatterns = [
        // SQL注入模式
        '/(\bunion\b.*\bselect\b)|(\bselect\b.*\bunion\b)/i',
        '/(\binsert\b.*\binto\b)|(\bdelete\b.*\bfrom\b)|(\bupdate\b.*\bset\b)/i',
        '/(\bdrop\b.*\btable\b)|(\balter\b.*\btable\b)|(\bcreate\b.*\btable\b)/i',
        '/(\bexec\b)|(\bexecute\b)|(\bsp_\w+)/i',
        
        // XSS模式
        '/<script[^>]*>.*?<\/script>/is',
        '/<iframe[^>]*>.*?<\/iframe>/is',
        '/javascript\s*:/i',
        '/on\w+\s*=/i',
        
        // 路径遍历模式
        '/\.\.[\/\\\\]/i',
        '/[\/\\\\]\.\.[\/\\\\]/i',
        
        // 命令注入模式
        '/[;&|`$(){}]/i',
        '/\b(eval|exec|system|shell_exec|passthru|file_get_contents)\b/i'
    ];

    /**
     * 敏感参数名称列表
     * @var array
     */
    protected $sensitiveParams = [
        'password', 'passwd', 'pwd', 'secret', 'token', 'key', 'auth',
        'authorization', 'api_key', 'access_token', 'refresh_token',
        'session_id', 'csrf_token', 'signature', 'private_key'
    ];

    /**
     * 允许的文件上传类型
     * @var array
     */
    protected $allowedFileTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'],
        'document' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
        'archive' => ['zip', 'rar', '7z', 'tar', 'gz']
    ];

    /**
     * 请求频率限制配置
     * @var array
     */
    protected $rateLimitConfig = [
        'max_requests' => 100,      // 最大请求数
        'time_window' => 3600,      // 时间窗口（秒）
        'block_duration' => 1800    // 封禁时长（秒）
    ];

    /**
     * 获取过滤后的输入数据
     * 
     * @access public
     * @param array $data 数据源
     * @param string|false $name 字段名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤函数
     * @return mixed
     */
    public function input(array $data = [], $name = '', $default = null, $filter = '')
    {
        // 执行安全检查
        $this->performSecurityChecks($data, $name);
        
        // 调用父类方法获取数据
        $result = parent::input($data, $name, $default, $filter);
        
        // 对结果进行额外的安全过滤
        if ($result !== $default && $result !== null) {
            $result = $this->applySafetyFilter($result, $name);
        }
        
        return $result;
    }

    /**
     * 执行安全检查
     * 
     * @access protected
     * @param array $data 数据源
     * @param string $name 字段名
     * @return void
     * @throws ValidateException
     */
    protected function performSecurityChecks(array $data, string $name): void
    {
        // 检查请求频率限制
        $this->checkRateLimit();
        
        // 检查恶意模式
        $this->checkMaliciousPatterns($data, $name);
        
        // 检查输入长度限制
        $this->checkInputLength($data, $name);
        
        // 记录安全日志
        $this->logSecurityEvent($data, $name);
    }

    /**
     * 检查请求频率限制
     * 
     * @access protected
     * @return void
     * @throws ValidateException
     */
    protected function checkRateLimit(): void
    {
        $clientIp = $this->ip();
        $cacheKey = 'rate_limit:' . $clientIp;
        
        // 检查是否被封禁
        $blockKey = 'blocked:' . $clientIp;
        if (Cache::get($blockKey)) {
            Log::warning('被封禁IP尝试访问', [
                'ip' => $clientIp,
                'url' => $this->url(true),
                'user_agent' => $this->header('user-agent')
            ]);
            throw new ValidateException('请求过于频繁，请稍后再试');
        }
        
        // 获取当前请求计数
        $requests = Cache::get($cacheKey, 0);
        
        if ($requests >= $this->rateLimitConfig['max_requests']) {
            // 超过限制，封禁IP
            Cache::set($blockKey, true, $this->rateLimitConfig['block_duration']);
            
            Log::warning('IP请求频率超限被封禁', [
                'ip' => $clientIp,
                'requests' => $requests,
                'limit' => $this->rateLimitConfig['max_requests'],
                'url' => $this->url(true)
            ]);
            
            throw new ValidateException('请求过于频繁，已被暂时封禁');
        }
        
        // 增加请求计数
        Cache::set($cacheKey, $requests + 1, $this->rateLimitConfig['time_window']);
    }

    /**
     * 检查恶意模式
     * 
     * @access protected
     * @param array $data 数据源
     * @param string $name 字段名
     * @return void
     * @throws ValidateException
     */
    protected function checkMaliciousPatterns(array $data, string $name): void
    {
        $checkData = $name ? ($data[$name] ?? '') : $data;
        
        if (is_array($checkData)) {
            foreach ($checkData as $key => $value) {
                $this->validateSingleValue($value, $key);
            }
        } else {
            $this->validateSingleValue($checkData, $name);
        }
    }

    /**
     * 验证单个值是否包含恶意模式
     * 
     * @access protected
     * @param mixed $value 要检查的值
     * @param string $fieldName 字段名
     * @return void
     * @throws ValidateException
     */
    protected function validateSingleValue($value, string $fieldName): void
    {
        if (!is_string($value)) {
            return;
        }
        
        foreach ($this->dangerousPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                // 记录安全威胁
                Log::error('检测到恶意请求模式', [
                    'field' => $fieldName,
                    'pattern' => $pattern,
                    'value' => $this->sanitizeLogValue($value),
                    'ip' => $this->ip(),
                    'url' => $this->url(true),
                    'user_agent' => $this->header('user-agent')
                ]);
                
                throw new ValidateException('请求包含非法字符，已被拒绝');
            }
        }
    }

    /**
     * 检查输入长度限制
     * 
     * @access protected
     * @param array $data 数据源
     * @param string $name 字段名
     * @return void
     * @throws ValidateException
     */
    protected function checkInputLength(array $data, string $name): void
    {
        $maxLength = 10000; // 默认最大长度
        $maxArraySize = 100; // 数组最大元素数量
        
        $checkData = $name ? ($data[$name] ?? '') : $data;
        
        if (is_array($checkData)) {
            if (count($checkData) > $maxArraySize) {
                throw new ValidateException('数组元素数量超过限制');
            }
            
            foreach ($checkData as $value) {
                if (is_string($value) && strlen($value) > $maxLength) {
                    throw new ValidateException('输入数据长度超过限制');
                }
            }
        } elseif (is_string($checkData) && strlen($checkData) > $maxLength) {
            throw new ValidateException('输入数据长度超过限制');
        }
    }

    /**
     * 应用安全过滤器
     * 
     * @access protected
     * @param mixed $value 要过滤的值
     * @param string $name 字段名
     * @return mixed
     */
    protected function applySafetyFilter($value, string $name)
    {
        if (is_array($value)) {
            return array_map(function($item) use ($name) {
                return $this->applySafetyFilter($item, $name);
            }, $value);
        }
        
        if (!is_string($value)) {
            return $value;
        }
        
        // 移除null字节
        $value = str_replace("\0", '', $value);
        
        // 移除控制字符（除了换行和制表符）
        $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        
        // 对敏感参数进行额外处理
        if (in_array(strtolower($name), $this->sensitiveParams)) {
            // 敏感参数不记录到日志
            return $value;
        }
        
        return $value;
    }

    /**
     * 记录安全事件
     * 
     * @access protected
     * @param array $data 数据源
     * @param string $name 字段名
     * @return void
     */
    protected function logSecurityEvent(array $data, string $name): void
    {
        // 只在调试模式下记录详细信息
        if (!$this->app->isDebug()) {
            return;
        }
        
        $logData = [
            'method' => $this->method(),
            'url' => $this->url(true),
            'ip' => $this->ip(),
            'user_agent' => $this->header('user-agent'),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // 添加非敏感参数信息
        if ($name && !in_array(strtolower($name), $this->sensitiveParams)) {
            $logData['field'] = $name;
            $logData['data_type'] = gettype($data[$name] ?? null);
        }
        
        Log::info('请求安全检查', $logData);
    }

    /**
     * 安全的文件上传验证
     *
     * @access public
     * @param string $name 文件字段名
     * @param string $type 允许的文件类型分类
     * @return array|null
     * @throws ValidateException
     */
    public function secureFile(string $name, string $type = 'image'): ?array
    {
        $file = $this->file($name);

        if (!$file) {
            return null;
        }

        // 检查文件类型
        $this->validateFileType($file, $type);

        // 检查文件大小
        $this->validateFileSize($file);

        return [
            'name' => $file->getOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getOriginalMime(),
            'extension' => $file->getOriginalExtension(),
            'tmp_name' => $file->getPathname()
        ];
    }

    /**
     * 验证文件类型
     *
     * @access protected
     * @param \think\File $file 上传文件对象
     * @param string $type 允许的类型分类
     * @return void
     * @throws ValidateException
     */
    protected function validateFileType($file, string $type): void
    {
        $extension = strtolower($file->getOriginalExtension());
        $allowedTypes = $this->allowedFileTypes[$type] ?? [];

        if (!in_array($extension, $allowedTypes)) {
            throw new ValidateException("不允许的文件类型: {$extension}");
        }
    }

    /**
     * 验证文件大小
     *
     * @access protected
     * @param \think\File $file 上传文件对象
     * @return void
     * @throws ValidateException
     */
    protected function validateFileSize($file): void
    {
        $maxSize = 10 * 1024 * 1024; // 10MB

        if ($file->getSize() > $maxSize) {
            throw new ValidateException("文件大小超过限制");
        }
    }

    /**
     * 清理日志值，移除敏感信息
     *
     * @access protected
     * @param string $value 原始值
     * @return string
     */
    protected function sanitizeLogValue(string $value): string
    {
        if (strlen($value) > 100) {
            $value = substr($value, 0, 100) . '...';
        }

        foreach ($this->sensitiveParams as $sensitive) {
            $value = preg_replace('/' . preg_quote($sensitive, '/') . '\s*[:=]\s*[^\s,}]+/i', $sensitive . ':***', $value);
        }

        return $value;
    }

    /**
     * 安全的参数获取方法
     *
     * @access public
     * @param string $name 参数名
     * @param mixed $default 默认值
     * @param string|array $filter 过滤方法
     * @return mixed
     */
    public function secureParam(string $name, $default = null, $filter = '')
    {
        try {
            return $this->param($name, $default, $filter);
        } catch (ValidateException $e) {
            Log::warning('参数安全验证失败', [
                'param' => $name,
                'error' => $e->getMessage(),
                'ip' => $this->ip(),
                'url' => $this->url(true)
            ]);
            return $default;
        }
    }

    /**
     * 获取请求指纹
     *
     * @access public
     * @return string
     */
    public function getFingerprint(): string
    {
        $components = [
            $this->ip(),
            $this->header('user-agent', ''),
            $this->header('accept-language', '')
        ];

        return md5(implode('|', $components));
    }
}
