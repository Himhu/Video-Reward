<?php
namespace think;

/**
 * Think类，用于提供框架助手函数
 */
class Think
{
    /**
     * 从数组中获取值
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        
        if (isset($array[$key])) {
            return $array[$key];
        }
        
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            
            $array = $array[$segment];
        }
        
        return $array;
    }
} 