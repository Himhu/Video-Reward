<?php
/**
 * 公共辅助函数文件
 * 
 * 当前版本变更说明：
 * - 初始版本，提供系统公共辅助函数
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.0
 * @date 2025-07-20
 */

declare(strict_types=1);

// 定义根目录常量（如果尚未定义）
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
}

/**
 * 安全的数组获取函数
 * 
 * @param array $array 目标数组
 * @param string $key 键名
 * @param mixed $default 默认值
 * @return mixed
 */
function array_get(array $array, string $key, $default = null)
{
    return $array[$key] ?? $default;
}

/**
 * 安全的字符串清理函数
 * 
 * @param string $string 输入字符串
 * @return string
 */
function clean_string(string $string): string
{
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * 生成安全的随机字符串
 * 
 * @param int $length 长度
 * @return string
 */
function generate_random_string(int $length = 32): string
{
    try {
        return bin2hex(random_bytes($length / 2));
    } catch (Exception $e) {
        // 降级到不太安全但可用的方法
        return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
    }
}

/**
 * 格式化文件大小
 * 
 * @param int $bytes 字节数
 * @param int $precision 精度
 * @return string
 */
function format_bytes(int $bytes, int $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * 安全的JSON编码
 * 
 * @param mixed $data 要编码的数据
 * @return string
 */
function safe_json_encode($data): string
{
    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return json_encode(['error' => 'JSON encoding failed']);
    }
    
    return $json;
}

/**
 * 安全的JSON解码
 * 
 * @param string $json JSON字符串
 * @param bool $assoc 是否返回关联数组
 * @return mixed
 */
function safe_json_decode(string $json, bool $assoc = true)
{
    $data = json_decode($json, $assoc);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return $assoc ? [] : null;
    }
    
    return $data;
}

/**
 * 检查是否为AJAX请求
 * 
 * @return bool
 */
function is_ajax_request(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * 获取客户端IP地址
 * 
 * @return string
 */
function get_client_ip(): string
{
    $ip_keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    
    foreach ($ip_keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = $_SERVER[$key];
            // 处理多个IP的情况（X-Forwarded-For可能包含多个IP）
            if (strpos($ip, ',') !== false) {
                $ip = trim(explode(',', $ip)[0]);
            }
            // 验证IP格式
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * 生成CSRF令牌
 * 
 * @return string
 */
function generate_csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $token = generate_random_string(32);
    $_SESSION['csrf_token'] = $token;
    
    return $token;
}

/**
 * 验证CSRF令牌
 * 
 * @param string $token 要验证的令牌
 * @return bool
 */
function verify_csrf_token(string $token): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * 记录系统日志
 * 
 * @param string $message 日志消息
 * @param string $level 日志级别
 * @return void
 */
function write_log(string $message, string $level = 'info'): void
{
    $log_file = ROOT_PATH . 'runtime/log/' . date('Y-m-d') . '.log';
    $log_dir = dirname($log_file);
    
    // 确保日志目录存在
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}
