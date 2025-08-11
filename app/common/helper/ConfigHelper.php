<?php
/**
 * 配置助手类 - 避免硬编码问题
 */

namespace app\common\helper;

class ConfigHelper
{
    /**
     * 获取后台入口路径
     * @return string
     */
    public static function getAdminPath()
    {
        // 从应用映射中获取后台入口
        $appMap = config('app.app_map', []);
        
        // 查找admin应用对应的入口
        foreach ($appMap as $alias => $app) {
            if ($app === 'admin') {
                return $alias;
            }
        }
        
        // 如果没有找到映射，使用环境变量
        return env('ADMIN_ALIAS', 'admin');
    }
    
    /**
     * 获取数据库表前缀
     * @return string
     */
    public static function getTablePrefix()
    {
        return config('database.connections.mysql.prefix', '');
    }
    
    /**
     * 获取完整表名
     * @param string $tableName 表名（不含前缀）
     * @return string
     */
    public static function getFullTableName($tableName)
    {
        return self::getTablePrefix() . $tableName;
    }
    
    /**
     * 生成后台URL
     * @param string $route 路由
     * @param array $params 参数
     * @return string
     */
    public static function adminUrl($route, $params = [])
    {
        $adminPath = self::getAdminPath();
        $url = "/{$adminPath}/{$route}";
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * 检查是否存在硬编码问题
     * @return array
     */
    public static function checkHardcoding()
    {
        return [
            'admin_path' => self::getAdminPath(),
            'table_prefix' => self::getTablePrefix(),
            'env_admin_alias' => env('ADMIN_ALIAS'),
            'app_map' => config('app.app_map'),
            'database_prefix' => config('database.connections.mysql.prefix')
        ];
    }
}
