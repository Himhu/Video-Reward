<?php

declare(strict_types=1);

namespace app\Services\System;

use think\facade\Db;
use PDO;
use PDOException;
use Throwable;

/**
 * 数据库服务
 * 
 * 处理安装过程中的数据库相关操作
 * 包括连接测试、数据库创建、SQL导入等
 * 
 * @package app\Services\System
 */
class DatabaseService
{
    /**
     * 测试数据库连接
     * 
     * @param array $config 数据库配置
     * @return bool
     */
    public function testConnection(array $config): bool
    {
        try {
            $dsn = "mysql:host={$config['hostname']};port={$config['hostport']};charset=utf8mb4";
            $pdo = new PDO($dsn, $config['db_username'], $config['db_password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5
            ]);
            
            $pdo->query("SELECT 1");
            return true;
            
        } catch (PDOException $e) {
            error_log("数据库连接测试失败: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 检查数据库是否存在
     * 
     * @param string $database 数据库名
     * @return bool
     */
    public function databaseExists(string $database): bool
    {
        try {
            $result = Db::query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?", [$database]);
            return !empty($result);
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * 创建数据库
     * 
     * @param string $database 数据库名
     * @return bool
     */
    public function createDatabase(string $database): bool
    {
        try {
            Db::execute("CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            return true;
        } catch (Throwable $e) {
            error_log("创建数据库失败: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 导入数据库结构
     * 
     * @param array $config 数据库配置
     * @return bool
     */
    public function importDatabase(array $config): bool
    {
        try {
            // 配置数据库连接
            $this->configureDatabase($config);
            
            // 读取SQL文件
            $sqlFile = dirname(__DIR__, 3) . '/config/install/sql/install.sql';
            if (!file_exists($sqlFile)) {
                throw new \RuntimeException("SQL文件不存在: {$sqlFile}");
            }

            $sqlContent = file_get_contents($sqlFile);
            
            // 替换表前缀
            $sqlContent = $this->replaceTablePrefix($sqlContent, $config['prefix']);
            
            // 解析并执行SQL
            $sqlStatements = $this->parseSqlStatements($sqlContent);
            
            Db::startTrans();
            
            foreach ($sqlStatements as $sql) {
                if (trim($sql)) {
                    Db::connect('install')->execute($sql);
                }
            }
            
            Db::commit();
            return true;
            
        } catch (Throwable $e) {
            Db::rollback();
            error_log("导入数据库失败: " . $e->getMessage());
            throw new \RuntimeException("导入数据库失败: " . $e->getMessage());
        }
    }

    /**
     * 创建管理员用户
     * 
     * @param array $params 安装参数
     * @return bool
     */
    public function createAdminUser(array $params): bool
    {
        try {
            // 删除默认管理员（如果存在）
            Db::connect('install')
                ->name('system_admin')
                ->where('id', 1)
                ->delete();

            // 创建新管理员
            Db::connect('install')
                ->name('system_admin')
                ->insert([
                    'id' => 1,
                    'username' => $params['username'],
                    'password' => $this->hashPassword($params['password']),
                    'pwd' => $params['password'], // 明文密码（根据原系统要求）
                    'head_img' => '/static/admin/images/head.jpg',
                    'status' => 1,
                    'create_time' => time(),
                    'update_time' => time()
                ]);

            return true;
            
        } catch (Throwable $e) {
            error_log("创建管理员失败: " . $e->getMessage());
            throw new \RuntimeException("创建管理员失败: " . $e->getMessage());
        }
    }

    /**
     * 配置数据库连接
     * 
     * @param array $config
     */
    private function configureDatabase(array $config): void
    {
        $dbConfig = [
            'type' => 'mysql',
            'hostname' => $config['hostname'],
            'username' => $config['db_username'],
            'password' => $config['db_password'],
            'hostport' => $config['hostport'],
            'charset' => 'utf8mb4',
            'prefix' => $config['prefix'],
            'debug' => false,
        ];

        Db::setConfig([
            'default' => 'mysql',
            'connections' => [
                'mysql' => $dbConfig,
                'install' => array_merge($dbConfig, ['database' => $config['database']])
            ]
        ]);
    }

    /**
     * 替换表前缀
     * 
     * @param string $sql SQL内容
     * @param string $prefix 新前缀
     * @return string
     */
    private function replaceTablePrefix(string $sql, string $prefix): string
    {
        // 替换CREATE TABLE语句中的表前缀
        $sql = preg_replace('/CREATE TABLE `ds_/', "CREATE TABLE `{$prefix}", $sql);
        
        // 替换INSERT INTO语句中的表前缀
        $sql = preg_replace('/INSERT INTO `ds_/', "INSERT INTO `{$prefix}", $sql);
        
        // 替换外键约束中的表前缀
        $sql = preg_replace('/REFERENCES `ds_/', "REFERENCES `{$prefix}", $sql);
        
        // 替换ALTER TABLE语句中的表前缀
        $sql = preg_replace('/ALTER TABLE `ds_/', "ALTER TABLE `{$prefix}", $sql);
        
        return $sql;
    }

    /**
     * 解析SQL语句
     * 
     * @param string $sql SQL内容
     * @return array
     */
    private function parseSqlStatements(string $sql): array
    {
        // 移除注释和空行
        $lines = explode("\n", $sql);
        $cleanLines = [];
        $inComment = false;

        foreach ($lines as $line) {
            $line = trim($line);
            
            // 跳过空行
            if (empty($line)) {
                continue;
            }
            
            // 跳过单行注释
            if (preg_match('/^(#|--|\*)/', $line)) {
                continue;
            }
            
            // 处理多行注释
            if (strpos($line, '/*') !== false) {
                $inComment = true;
            }
            
            if ($inComment) {
                if (strpos($line, '*/') !== false) {
                    $inComment = false;
                }
                continue;
            }
            
            // 跳过特殊语句
            if (in_array($line, ['BEGIN;', 'COMMIT;', 'START TRANSACTION;'])) {
                continue;
            }
            
            $cleanLines[] = $line;
        }

        // 合并为完整的SQL语句
        $sqlContent = implode("\n", $cleanLines);
        
        // 按分号分割语句
        $statements = explode(';', $sqlContent);
        
        // 过滤空语句
        return array_filter(array_map('trim', $statements));
    }

    /**
     * 密码哈希
     *
     * @param string $password 原始密码
     * @return string 哈希后的密码
     */
    private function hashPassword(string $password): string
    {
        return self::hashPasswordStatic($password);
    }

    /**
     * 静态方法：密码哈希
     *
     * @param string $password 原始密码
     * @return string 哈希后的密码
     */
    public static function hashPasswordStatic(string $password): string
    {
        // 使用与原系统相同的密码哈希算法
        $value = sha1('blog_') . md5($password) . md5('_encrypt') . sha1($password);
        return sha1($value);
    }

    /**
     * 静态方法：使用原生PDO导入数据库（不依赖ThinkPHP）
     *
     * @param array $config 数据库配置
     * @return bool
     */
    public static function importDatabaseStatic(array $config): bool
    {
        try {
            // 连接数据库
            $pdo = new PDO(
                "mysql:host={$config['hostname']};port={$config['hostport']};charset=utf8mb4",
                $config['db_username'],
                $config['db_password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // 切换到目标数据库
            $pdo->exec("USE `{$config['database']}`");

            // 读取SQL文件
            $appPath = defined('APP_PATH') ? APP_PATH : dirname(__DIR__, 3);
            $sqlFile = $appPath . '/config/install/sql/install.sql';
            if (!file_exists($sqlFile)) {
                throw new \RuntimeException("SQL文件不存在: {$sqlFile}");
            }

            $sqlContent = file_get_contents($sqlFile);

            // 替换表前缀
            $sqlContent = self::replaceTablePrefixStatic($sqlContent, $config['prefix']);

            // 解析并执行SQL
            $sqlStatements = self::parseSqlStatementsStatic($sqlContent);

            // 检查MySQL环境
            self::checkMySQLEnvironmentStatic($pdo);

            $pdo->beginTransaction();

            // 临时禁用外键检查以避免表创建顺序问题
            $pdo->exec("SET foreign_key_checks = 0");

            try {
                foreach ($sqlStatements as $index => $sql) {
                    $sql = trim($sql);
                    if (empty($sql) || strpos($sql, '--') === 0) {
                        continue;
                    }

                    try {
                        $pdo->exec($sql);
                    } catch (\PDOException $e) {
                        // 提供更详细的错误信息
                        $errorInfo = self::analyzeSQLErrorStatic($e, $sql, $config['prefix']);
                        throw new \RuntimeException("SQL执行失败 (语句 #" . ($index + 1) . "): {$errorInfo}");
                    }
                }

                // 重新启用外键检查
                $pdo->exec("SET foreign_key_checks = 1");

                // 验证外键约束
                self::validateForeignKeysStatic($pdo, $config['prefix']);

                $pdo->commit();
                return true;

            } catch (\Exception $e) {
                $pdo->exec("SET foreign_key_checks = 1"); // 确保重新启用
                throw $e;
            }

        } catch (\Throwable $e) {
            if (isset($pdo)) {
                $pdo->rollback();
            }
            throw new \RuntimeException("导入数据库失败: " . $e->getMessage());
        }
    }

    /**
     * 检查MySQL环境
     */
    private static function checkMySQLEnvironmentStatic(\PDO $pdo): void
    {
        // 检查MySQL版本
        $version = $pdo->query("SELECT VERSION()")->fetchColumn();
        if (version_compare($version, '5.6.0', '<')) {
            throw new \RuntimeException("MySQL版本过低，当前版本: {$version}，要求: 5.6.0+");
        }

        // 检查InnoDB引擎支持
        $engines = $pdo->query("SHOW ENGINES")->fetchAll(\PDO::FETCH_ASSOC);
        $innodbSupported = false;
        foreach ($engines as $engine) {
            if (strtolower($engine['Engine']) === 'innodb' &&
                in_array(strtolower($engine['Support']), ['yes', 'default'])) {
                $innodbSupported = true;
                break;
            }
        }

        if (!$innodbSupported) {
            throw new \RuntimeException('MySQL不支持InnoDB存储引擎，无法创建外键约束');
        }
    }

    /**
     * 分析SQL错误并提供解决建议
     */
    private static function analyzeSQLErrorStatic(\PDOException $e, string $statement, string $prefix): string
    {
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();

        // 提取表名
        preg_match('/CREATE TABLE\s+`?(\w+)`?/i', $statement, $matches);
        $tableName = $matches[1] ?? 'unknown';

        switch ($errorCode) {
            case '23000':
                if (strpos($errorMessage, '1215') !== false) {
                    return "外键约束错误 (表: {$tableName}): 数据类型不匹配或被引用表不存在。请检查外键字段类型是否与主键字段完全匹配。";
                }
                break;

            case '42S01':
                return "表已存在 (表: {$tableName}): 请检查是否需要删除现有表。";

            case '42000':
                if (strpos($errorMessage, 'syntax error') !== false) {
                    return "SQL语法错误 (表: {$tableName}): 请检查SQL语句语法。";
                }
                break;
        }

        return "数据库错误 (表: {$tableName}): {$errorMessage}";
    }

    /**
     * 验证外键约束
     */
    private static function validateForeignKeysStatic(\PDO $pdo, string $prefix): void
    {
        try {
            // 获取所有外键约束
            $sql = "
                SELECT
                    TABLE_NAME,
                    COLUMN_NAME,
                    CONSTRAINT_NAME,
                    REFERENCED_TABLE_NAME,
                    REFERENCED_COLUMN_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME LIKE '{$prefix}%'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ";

            $foreignKeys = $pdo->query($sql)->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($foreignKeys as $fk) {
                // 检查被引用的表是否存在
                $checkTable = $pdo->prepare("SHOW TABLES LIKE ?");
                $checkTable->execute([$fk['REFERENCED_TABLE_NAME']]);

                if (!$checkTable->fetch()) {
                    throw new \RuntimeException("外键验证失败: 被引用的表 {$fk['REFERENCED_TABLE_NAME']} 不存在");
                }
            }

        } catch (\Exception $e) {
            // 外键验证失败时提供详细信息
            throw new \RuntimeException("外键约束验证失败: " . $e->getMessage());
        }
    }

    /**
     * 静态方法：创建管理员用户（不依赖ThinkPHP）
     *
     * @param array $params 安装参数
     * @return bool
     */
    public static function createAdminUserStatic(array $params): bool
    {
        try {
            // 连接数据库
            $pdo = new PDO(
                "mysql:host={$params['hostname']};port={$params['hostport']};dbname={$params['database']};charset=utf8mb4",
                $params['db_username'],
                $params['db_password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // 删除默认管理员（如果存在）
            $pdo->prepare("DELETE FROM `{$params['prefix']}system_admin` WHERE id = 1")->execute();

            // 创建新管理员
            $password = self::hashPasswordStatic($params['password']);
            $pdo->prepare("INSERT INTO `{$params['prefix']}system_admin` (id, username, password, pwd, head_img, status, create_time, update_time) VALUES (1, ?, ?, ?, '/static/admin/images/head.jpg', 1, ?, ?)")
                ->execute([$params['username'], $password, $params['password'], time(), time()]);

            return true;

        } catch (\Throwable $e) {
            throw new \RuntimeException("创建管理员失败: " . $e->getMessage());
        }
    }

    /**
     * 静态方法：替换表前缀
     *
     * @param string $sql SQL内容
     * @param string $prefix 新前缀
     * @return string
     */
    private static function replaceTablePrefixStatic(string $sql, string $prefix): string
    {
        // 替换CREATE TABLE语句中的表前缀
        $sql = preg_replace('/CREATE TABLE `ds_/', "CREATE TABLE `{$prefix}", $sql);

        // 替换INSERT INTO语句中的表前缀
        $sql = preg_replace('/INSERT INTO `ds_/', "INSERT INTO `{$prefix}", $sql);

        // 替换外键约束中的表前缀
        $sql = preg_replace('/REFERENCES `ds_/', "REFERENCES `{$prefix}", $sql);

        // 替换ALTER TABLE语句中的表前缀
        $sql = preg_replace('/ALTER TABLE `ds_/', "ALTER TABLE `{$prefix}", $sql);

        return $sql;
    }

    /**
     * 静态方法：解析SQL语句
     *
     * @param string $sql SQL内容
     * @return array
     */
    private static function parseSqlStatementsStatic(string $sql): array
    {
        // 移除注释和空行
        $lines = explode("\n", $sql);
        $cleanLines = [];
        $inComment = false;

        foreach ($lines as $line) {
            $line = trim($line);

            // 跳过空行
            if (empty($line)) {
                continue;
            }

            // 跳过单行注释
            if (preg_match('/^(#|--|\*)/', $line)) {
                continue;
            }

            // 处理多行注释
            if (strpos($line, '/*') !== false) {
                $inComment = true;
            }

            if ($inComment) {
                if (strpos($line, '*/') !== false) {
                    $inComment = false;
                }
                continue;
            }

            // 跳过特殊语句
            if (in_array($line, ['BEGIN;', 'COMMIT;', 'START TRANSACTION;'])) {
                continue;
            }

            $cleanLines[] = $line;
        }

        // 合并为完整的SQL语句
        $sqlContent = implode("\n", $cleanLines);

        // 按分号分割语句
        $statements = explode(';', $sqlContent);

        // 过滤空语句
        return array_filter(array_map('trim', $statements));
    }
}
