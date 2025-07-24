<?php
/**
 * Video-Reward 数据库配置助手类
 * 
 * 统一处理数据库相关的配置操作，避免重复代码
 * 解决DatabaseService和ConfigService中表前缀处理的重复逻辑
 * 
 * @author Video-Reward Team
 * @version 1.0
 * @since 2025-01-23
 */

declare(strict_types=1);

namespace app\Helpers;

class DatabaseConfigHelper
{
    /**
     * 默认表前缀
     */
    public const DEFAULT_PREFIX = 'ds_';
    
    /**
     * 支持的SQL语句类型
     */
    private const SUPPORTED_SQL_TYPES = [
        'CREATE TABLE',
        'INSERT INTO',
        'ALTER TABLE',
        'DROP TABLE',
        'REFERENCES',
        'CONSTRAINT',
        'INDEX',
        'KEY'
    ];
    
    /**
     * 替换SQL中的表前缀
     * 
     * 统一的表前缀替换逻辑，支持所有常见的SQL语句类型
     * 
     * @param string $sql SQL内容
     * @param string $newPrefix 新的表前缀
     * @param string $oldPrefix 原始表前缀，默认为 'ds_'
     * @return string 替换后的SQL内容
     */
    public static function replaceTablePrefix(string $sql, string $newPrefix, string $oldPrefix = self::DEFAULT_PREFIX): string
    {
        // 确保新前缀以下划线结尾（如果不为空）
        if (!empty($newPrefix) && substr($newPrefix, -1) !== '_') {
            $newPrefix .= '_';
        }
        
        // 确保旧前缀以下划线结尾
        if (!empty($oldPrefix) && substr($oldPrefix, -1) !== '_') {
            $oldPrefix .= '_';
        }
        
        // 1. 替换CREATE TABLE语句中的表前缀
        $sql = preg_replace('/CREATE TABLE `' . preg_quote($oldPrefix, '/') . '/', "CREATE TABLE `{$newPrefix}", $sql);
        
        // 2. 替换INSERT INTO语句中的表前缀
        $sql = preg_replace('/INSERT INTO `' . preg_quote($oldPrefix, '/') . '/', "INSERT INTO `{$newPrefix}", $sql);
        
        // 3. 替换外键约束中的表前缀
        $sql = preg_replace('/REFERENCES `' . preg_quote($oldPrefix, '/') . '/', "REFERENCES `{$newPrefix}", $sql);
        
        // 4. 替换ALTER TABLE语句中的表前缀
        $sql = preg_replace('/ALTER TABLE `' . preg_quote($oldPrefix, '/') . '/', "ALTER TABLE `{$newPrefix}", $sql);
        
        // 5. 替换约束名称中的表前缀（外键约束名）
        $sql = preg_replace('/CONSTRAINT `fk_(\w+)_/', "CONSTRAINT `fk_{$newPrefix}\\1_", $sql);
        
        // 6. 替换索引名称中的表前缀
        $sql = preg_replace('/KEY `idx_(\w+)/', "KEY `idx_{$newPrefix}\\1", $sql);
        
        // 7. 替换注释中的表名引用（用于文档说明）
        $sql = preg_replace('/-- 表的结构 `' . preg_quote($oldPrefix, '/') . '/', "-- 表的结构 `{$newPrefix}", $sql);
        $sql = preg_replace('/-- 插入.*数据 `' . preg_quote($oldPrefix, '/') . '/', "-- 插入数据到 `{$newPrefix}", $sql);
        
        // 8. 替换AUTO_INCREMENT设置中的表前缀
        $sql = preg_replace('/ALTER TABLE `' . preg_quote($oldPrefix, '/') . '(\w+)` AUTO_INCREMENT/', "ALTER TABLE `{$newPrefix}\\1` AUTO_INCREMENT", $sql);
        
        // 9. 替换COMMENT中的表名引用
        $sql = preg_replace_callback('/COMMENT=\'([^\']*' . preg_quote($oldPrefix, '/') . '[^\']*)\'/i', function($matches) use ($newPrefix, $oldPrefix) {
            return "COMMENT='" . str_replace($oldPrefix, $newPrefix, $matches[1]) . "'";
        }, $sql);
        
        // 10. 替换DROP TABLE语句中的表前缀
        $sql = preg_replace('/DROP TABLE IF EXISTS `' . preg_quote($oldPrefix, '/') . '/', "DROP TABLE IF EXISTS `{$newPrefix}", $sql);
        $sql = preg_replace('/DROP TABLE `' . preg_quote($oldPrefix, '/') . '/', "DROP TABLE `{$newPrefix}", $sql);
        
        // 验证替换完整性
        $validation = self::validatePrefixReplacement($sql, $newPrefix, $oldPrefix);
        if (!$validation['success']) {
            error_log("Video-Reward: 表前缀替换验证发现问题: " . json_encode($validation['issues']));
        }
        
        return $sql;
    }
    
    /**
     * 验证表前缀替换的完整性
     * 
     * @param string $sql 替换后的SQL
     * @param string $newPrefix 新前缀
     * @param string $oldPrefix 旧前缀
     * @return array 验证结果
     */
    public static function validatePrefixReplacement(string $sql, string $newPrefix, string $oldPrefix = self::DEFAULT_PREFIX): array
    {
        $issues = [];
        
        // 检查是否还有未替换的旧前缀
        if (preg_match_all('/`' . preg_quote($oldPrefix, '/') . '\w+`/', $sql, $matches)) {
            $issues[] = [
                'type' => 'unreplaced_prefix',
                'message' => '发现未替换的旧表前缀',
                'tables' => array_unique($matches[0])
            ];
        }
        
        // 检查新前缀的表是否正确格式
        if (preg_match_all('/`' . preg_quote($newPrefix, '/') . '(\w+)`/', $sql, $matches)) {
            $newTables = array_unique($matches[0]);
            foreach ($newTables as $table) {
                if (!preg_match('/^`' . preg_quote($newPrefix, '/') . '\w+`$/', $table)) {
                    $issues[] = [
                        'type' => 'invalid_table_format',
                        'message' => '表名格式不正确',
                        'table' => $table
                    ];
                }
            }
        }
        
        return [
            'success' => empty($issues),
            'issues' => $issues,
            'old_prefix' => $oldPrefix,
            'new_prefix' => $newPrefix
        ];
    }
    
    /**
     * 生成数据库配置模板
     * 
     * @param array $dbConfig 数据库配置参数
     * @return string 配置模板内容
     */
    public static function generateDatabaseConfigTemplate(array $dbConfig): string
    {
        // 确保必需的配置项存在
        $config = array_merge([
            'type' => 'mysql',
            'hostname' => 'localhost',
            'database' => '',
            'db_username' => 'root',
            'db_password' => '',
            'hostport' => '3306',
            'prefix' => self::DEFAULT_PREFIX,
            'charset' => 'utf8mb4'
        ], $dbConfig);
        
        return <<<EOT
<?php
// +----------------------------------------------------------------------
// | Video-Reward 数据库配置
// +----------------------------------------------------------------------

use think\\facade\Env;

return [
    // 默认使用的数据库连接配置
    'default'         => Env::get('database.driver', 'mysql'),

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => Env::get('database.type', '{$config['type']}'),
            // 服务器地址
            'hostname'          => Env::get('database.hostname', '{$config['hostname']}'),
            // 数据库名
            'database'          => Env::get('database.database', '{$config['database']}'),
            // 用户名
            'username'          => Env::get('database.username', '{$config['db_username']}'),
            // 密码
            'password'          => Env::get('database.password', '{$config['db_password']}'),
            // 端口
            'hostport'          => Env::get('database.hostport', '{$config['hostport']}'),
            // 数据库连接参数
            'params'            => [],
            // 数据库编码默认采用utf8mb4
            'charset'           => Env::get('database.charset', '{$config['charset']}'),
            // 数据库表前缀
            'prefix'            => Env::get('database.prefix', '{$config['prefix']}'),

            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => false,
            // 监听SQL
            'trigger_sql'       => true,
            // 开启字段缓存
            'fields_cache'      => false,
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ],

        // 更多的数据库配置信息
    ],
];

EOT;
    }
    
    /**
     * 解析SQL语句，分离不同类型的语句
     * 
     * @param string $sql SQL内容
     * @return array 分类后的SQL语句
     */
    public static function parseSqlStatements(string $sql): array
    {
        // 移除注释和空行
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
        $sql = preg_replace('/^\s*$/m', '', $sql);
        
        // 按分号分割SQL语句
        $statements = preg_split('/;\s*$/m', $sql, -1, PREG_SPLIT_NO_EMPTY);
        
        $categorized = [
            'create_table' => [],
            'insert_data' => [],
            'alter_table' => [],
            'create_index' => [],
            'other' => []
        ];
        
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (empty($statement)) continue;
            
            $upperStatement = strtoupper($statement);
            
            if (strpos($upperStatement, 'CREATE TABLE') === 0) {
                $categorized['create_table'][] = $statement;
            } elseif (strpos($upperStatement, 'INSERT INTO') === 0) {
                $categorized['insert_data'][] = $statement;
            } elseif (strpos($upperStatement, 'ALTER TABLE') === 0) {
                $categorized['alter_table'][] = $statement;
            } elseif (strpos($upperStatement, 'CREATE INDEX') === 0 || strpos($upperStatement, 'CREATE UNIQUE INDEX') === 0) {
                $categorized['create_index'][] = $statement;
            } else {
                $categorized['other'][] = $statement;
            }
        }
        
        return $categorized;
    }
    
    /**
     * 获取SQL中的表名列表
     * 
     * @param string $sql SQL内容
     * @param string $prefix 表前缀
     * @return array 表名列表
     */
    public static function extractTableNames(string $sql, string $prefix = self::DEFAULT_PREFIX): array
    {
        $tables = [];
        
        // 匹配CREATE TABLE语句中的表名
        if (preg_match_all('/CREATE TABLE `' . preg_quote($prefix, '/') . '(\w+)`/i', $sql, $matches)) {
            $tables = array_merge($tables, $matches[1]);
        }
        
        // 匹配INSERT INTO语句中的表名
        if (preg_match_all('/INSERT INTO `' . preg_quote($prefix, '/') . '(\w+)`/i', $sql, $matches)) {
            $tables = array_merge($tables, $matches[1]);
        }
        
        return array_unique($tables);
    }
    
    /**
     * 检查数据库连接配置
     * 
     * @param array $config 数据库配置
     * @return array 检查结果
     */
    public static function validateDatabaseConfig(array $config): array
    {
        $errors = [];
        
        // 检查必需字段
        $requiredFields = ['hostname', 'database', 'db_username'];
        foreach ($requiredFields as $field) {
            if (empty($config[$field])) {
                $errors[] = "缺少必需的配置项: {$field}";
            }
        }
        
        // 检查端口号
        if (isset($config['hostport']) && !is_numeric($config['hostport'])) {
            $errors[] = "端口号必须是数字";
        }
        
        // 检查表前缀格式
        if (isset($config['prefix']) && !empty($config['prefix'])) {
            if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*_?$/', $config['prefix'])) {
                $errors[] = "表前缀格式不正确，应该以字母开头，可包含字母、数字和下划线";
            }
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'config' => $config
        ];
    }
}
?>
