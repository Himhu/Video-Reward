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

            // 检查是否需要覆盖安装
            if (isset($config['cover']) && $config['cover']) {
                $cleanMode = $config['clean_mode'] ?? 'smart'; // smart: 智能清理, full: 完全清理
                self::cleanDatabaseStatic($pdo, $config['prefix'], $cleanMode);
            }

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

            // 检查重复键冲突
            self::checkDuplicateKeysStatic($pdo, $sqlStatements, $config['prefix']);

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

                // 安全地提交事务
                if (!self::safeCommitTransaction($pdo)) {
                    throw new \RuntimeException("提交事务失败");
                }

                return true;

            } catch (\Exception $e) {
                try {
                    $pdo->exec("SET foreign_key_checks = 1"); // 确保重新启用

                    // 如果事务仍然活动，回滚它
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                } catch (\PDOException $pdoEx) {
                    // 忽略回滚过程中的错误，但记录日志
                    error_log("回滚事务时发生错误: " . $pdoEx->getMessage());
                }

                throw $e;
            }

        } catch (\Throwable $e) {
            if (isset($pdo)) {
                // 安全地回滚事务
                self::safeRollbackTransaction($pdo);
            }
            throw new \RuntimeException("导入数据库失败: " . $e->getMessage());
        }
    }

    /**
     * 清理数据库中的现有表（覆盖安装）
     *
     * @param \PDO $pdo 数据库连接
     * @param string $prefix 表前缀
     * @param string $mode 清理模式：smart(智能清理) 或 full(完全清理)
     * @return void
     */
    private static function cleanDatabaseStatic(\PDO $pdo, string $prefix, string $mode = 'smart'): void
    {
        try {
            // 禁用外键检查
            $pdo->exec("SET foreign_key_checks = 0");

            $tables = [];
            $cleanedCount = 0;

            if ($mode === 'full') {
                // 完全清理模式：删除数据库中的所有表
                $sql = "SELECT TABLE_NAME FROM information_schema.TABLES
                        WHERE TABLE_SCHEMA = DATABASE()";

                $stmt = $pdo->query($sql);
                $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

                // 记录操作日志
                error_log("Video-Reward安装：完全清理模式，将删除 " . count($tables) . " 个表");

            } else {
                // 智能清理模式：只删除匹配前缀的表
                $sql = "SELECT TABLE_NAME FROM information_schema.TABLES
                        WHERE TABLE_SCHEMA = DATABASE()
                        AND TABLE_NAME LIKE '{$prefix}%'";

                $stmt = $pdo->query($sql);
                $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);

                // 记录操作日志
                error_log("Video-Reward安装：智能清理模式，将删除 " . count($tables) . " 个匹配前缀的表");
            }

            // 删除表
            foreach ($tables as $table) {
                try {
                    $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
                    $cleanedCount++;
                    error_log("Video-Reward安装：已删除表 {$table}");
                } catch (\Exception $e) {
                    error_log("Video-Reward安装：删除表 {$table} 失败: " . $e->getMessage());
                    // 继续删除其他表，不中断整个过程
                }
            }

            // 重新启用外键检查
            $pdo->exec("SET foreign_key_checks = 1");

            // 记录清理结果
            error_log("Video-Reward安装：数据库清理完成，成功删除 {$cleanedCount} 个表");

        } catch (\Exception $e) {
            // 确保重新启用外键检查
            $pdo->exec("SET foreign_key_checks = 1");
            throw new \RuntimeException("清理数据库失败: " . $e->getMessage());
        }
    }

    /**
     * 检查重复键冲突
     *
     * @param \PDO $pdo 数据库连接
     * @param array $sqlStatements SQL语句数组
     * @param string $prefix 表前缀
     * @return void
     */
    private static function checkDuplicateKeysStatic(\PDO $pdo, array $sqlStatements, string $prefix): void
    {
        $conflicts = [];

        foreach ($sqlStatements as $index => $sql) {
            $sql = trim($sql);
            if (empty($sql)) continue;

            // 检查CREATE TABLE语句
            if (preg_match('/CREATE TABLE\s+`?(\w+)`?/i', $sql, $matches)) {
                $tableName = $matches[1];

                // 检查表是否已存在
                $checkSql = "SELECT COUNT(*) FROM information_schema.TABLES
                            WHERE TABLE_SCHEMA = DATABASE()
                            AND TABLE_NAME = ?";
                $stmt = $pdo->prepare($checkSql);
                $stmt->execute([$tableName]);

                if ($stmt->fetchColumn() > 0) {
                    $conflicts[] = [
                        'type' => 'table',
                        'name' => $tableName,
                        'statement' => $index + 1,
                        'message' => "表 {$tableName} 已存在"
                    ];
                }
            }
        }

        // 如果发现冲突，抛出详细错误
        if (!empty($conflicts)) {
            $errorMsg = "检测到数据库冲突:\n";
            foreach ($conflicts as $conflict) {
                $errorMsg .= "- {$conflict['message']} (语句 #{$conflict['statement']})\n";
            }
            $errorMsg .= "\n建议解决方案:\n";
            $errorMsg .= "1. 勾选'覆盖已存在的数据库'选项\n";
            $errorMsg .= "2. 手动删除冲突的表\n";
            $errorMsg .= "3. 使用不同的表前缀";

            throw new \RuntimeException($errorMsg);
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

                if (strpos($errorMessage, '1022') !== false) {
                    return "重复键错误 (表: {$tableName}): 表或索引已存在。解决方案:\n" .
                           "1. 勾选'覆盖已存在的数据库'选项重新安装\n" .
                           "2. 手动删除表: DROP TABLE IF EXISTS `{$tableName}`\n" .
                           "3. 使用不同的表前缀避免冲突\n" .
                           "4. 检查数据库中是否有残留的表结构";
                }

                if (strpos($errorMessage, '1062') !== false) {
                    return "唯一键冲突 (表: {$tableName}): 尝试插入重复的唯一值。请检查数据是否已存在。";
                }
                break;

            case '42S01':
                return "表已存在 (表: {$tableName}): 请勾选'覆盖已存在的数据库'选项或手动删除现有表。";

            case '42000':
                if (strpos($errorMessage, 'syntax error') !== false) {
                    return "SQL语法错误 (表: {$tableName}): 请检查SQL语句语法。";
                }
                break;

            case 'HY000':
                if (strpos($errorMessage, '1050') !== false) {
                    return "表已存在 (表: {$tableName}): 数据库中已存在同名表。请勾选覆盖选项或使用不同的表前缀。";
                }
                break;
        }

        // 通用错误处理
        $suggestions = [
            "错误详情: {$errorMessage}",
            "涉及表: {$tableName}",
            "建议解决方案:",
            "1. 检查数据库连接和权限",
            "2. 确认MySQL版本兼容性 (要求5.6+)",
            "3. 勾选'覆盖已存在的数据库'选项",
            "4. 尝试使用不同的表前缀",
            "5. 手动清理数据库后重新安装"
        ];

        return implode("\n", $suggestions);
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
        // 1. 替换CREATE TABLE语句中的表前缀
        $sql = preg_replace('/CREATE TABLE `ds_/', "CREATE TABLE `{$prefix}", $sql);

        // 2. 替换INSERT INTO语句中的表前缀
        $sql = preg_replace('/INSERT INTO `ds_/', "INSERT INTO `{$prefix}", $sql);

        // 3. 替换外键约束中的表前缀
        $sql = preg_replace('/REFERENCES `ds_/', "REFERENCES `{$prefix}", $sql);

        // 4. 替换ALTER TABLE语句中的表前缀
        $sql = preg_replace('/ALTER TABLE `ds_/', "ALTER TABLE `{$prefix}", $sql);

        // 5. 替换约束名称中的表前缀（外键约束名）
        $sql = preg_replace('/CONSTRAINT `fk_(\w+)_/', "CONSTRAINT `fk_{$prefix}\\1_", $sql);

        // 6. 替换索引名称中的表前缀
        $sql = preg_replace('/KEY `idx_(\w+)/', "KEY `idx_{$prefix}\\1", $sql);

        // 7. 替换注释中的表名引用（用于文档说明）
        $sql = preg_replace('/-- 表的结构 `ds_/', "-- 表的结构 `{$prefix}", $sql);
        $sql = preg_replace('/-- 插入.*数据 `ds_/', "-- 插入数据到 `{$prefix}", $sql);

        // 8. 替换AUTO_INCREMENT设置中的表前缀
        $sql = preg_replace('/ALTER TABLE `ds_(\w+)` AUTO_INCREMENT/', "ALTER TABLE `{$prefix}\\1` AUTO_INCREMENT", $sql);

        // 9. 替换COMMENT中的表名引用
        $sql = preg_replace_callback('/COMMENT=\'([^\']*ds_[^\']*)\'/i', function($matches) use ($prefix) {
            return "COMMENT='" . str_replace('ds_', $prefix, $matches[1]) . "'";
        }, $sql);

        // 验证替换完整性
        $validation = self::validatePrefixReplacementStatic($sql, $prefix);
        if (!$validation['success']) {
            error_log("Video-Reward安装：表前缀替换验证发现问题: " . json_encode($validation['issues']));
        }

        return $sql;
    }

    /**
     * 验证表前缀替换的完整性
     *
     * @param string $sql 替换后的SQL内容
     * @param string $prefix 新前缀
     * @return array 验证结果
     */
    private static function validatePrefixReplacementStatic(string $sql, string $prefix): array
    {
        $issues = [];

        // 检查是否还有未替换的ds_前缀
        if (preg_match_all('/`ds_\w+`/', $sql, $matches)) {
            $issues[] = [
                'type' => 'unreplaced_table_names',
                'message' => '发现未替换的表名',
                'details' => array_unique($matches[0])
            ];
        }

        // 检查是否还有未替换的约束名
        if (preg_match_all('/CONSTRAINT `[^`]*ds_[^`]*`/', $sql, $matches)) {
            $issues[] = [
                'type' => 'unreplaced_constraints',
                'message' => '发现未替换的约束名',
                'details' => array_unique($matches[0])
            ];
        }

        // 检查是否还有未替换的注释
        if (preg_match_all('/-- [^\\n]*ds_[^\\n]*/', $sql, $matches)) {
            $issues[] = [
                'type' => 'unreplaced_comments',
                'message' => '发现未替换的注释',
                'details' => array_unique($matches[0])
            ];
        }

        return [
            'success' => empty($issues),
            'issues' => $issues,
            'total_issues' => count($issues)
        ];
    }

    /**
     * 安全地回滚PDO事务
     *
     * @param \PDO $pdo 数据库连接
     * @return bool 是否成功回滚
     */
    private static function safeRollbackTransaction(\PDO $pdo): bool
    {
        try {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
                return true;
            }
            return false; // 没有活动事务
        } catch (\PDOException $e) {
            error_log("回滚事务失败: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 安全地提交PDO事务
     *
     * @param \PDO $pdo 数据库连接
     * @return bool 是否成功提交
     */
    private static function safeCommitTransaction(\PDO $pdo): bool
    {
        try {
            if ($pdo->inTransaction()) {
                $pdo->commit();
                return true;
            }
            return false; // 没有活动事务
        } catch (\PDOException $e) {
            error_log("提交事务失败: " . $e->getMessage());
            return false;
        }
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
