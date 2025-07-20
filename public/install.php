<?php
/**
 * 系统安装程序
 * 
 * 当前版本变更说明：
 * - 修复SQL注入漏洞和不安全的数据库操作
 * - 加强输入验证和参数过滤
 * - 优化配置文件生成的安全性
 * - 移除调试代码和不安全的错误抑制符
 * 
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date 2025-07-20
 */

declare(strict_types=1);

// 安全的错误报告设置（仅在开发环境）
if (defined('INSTALL_DEBUG') && INSTALL_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

use think\facade\Db;

// 引入必要的依赖
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/topthink/framework/src/helper.php';

// 定义安全的常量
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . DS);
}
if (!defined('INSTALL_PATH')) {
    define('INSTALL_PATH', ROOT_PATH . 'config' . DS . 'install' . DS);
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', ROOT_PATH . 'config' . DS);
}

/**
 * 安全的文件读写权限检查
 * 
 * @param string $file 文件路径
 * @return bool
 */
function isReadWrite(string $file): bool
{
    // Windows系统直接返回true（简化处理）
    if (DIRECTORY_SEPARATOR === '\\') {
        return true;
    }
    
    // Unix/Linux系统检查权限
    if (DIRECTORY_SEPARATOR === '/' && ini_get("safe_mode") === false) {
        return is_writable($file);
    }
    
    // 安全的文件打开测试
    if (!is_file($file)) {
        return false;
    }
    
    $fp = fopen($file, "r+");
    if ($fp === false) {
        return false;
    }
    
    fclose($fp);
    return true;
}

/**
 * 检查PHP版本
 * 
 * @param string $version 最低版本要求
 * @return bool
 */
function checkPhpVersion(string $version): bool
{
    return version_compare(PHP_VERSION, $version, '>=');
}

/**
 * 检查是否为AJAX请求
 * 
 * @return bool
 */
function isAjax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * 安全的输入过滤函数
 * 
 * @param mixed $input 输入数据
 * @return mixed 过滤后的数据
 */
function filterInput($input)
{
    if (is_array($input)) {
        return array_map('filterInput', $input);
    }
    
    if (is_string($input)) {
        // 移除危险字符
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        return $input;
    }
    
    return $input;
}

/**
 * 验证数据库配置参数
 * 
 * @param array $config 数据库配置
 * @return array 验证结果
 */
function validateDatabaseConfig(array $config): array
{
    $errors = [];
    
    // 验证主机名
    if (empty($config['hostname'])) {
        $errors[] = '数据库主机名不能为空';
    } elseif (!filter_var($config['hostname'], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) && 
              !filter_var($config['hostname'], FILTER_VALIDATE_IP)) {
        $errors[] = '数据库主机名格式不正确';
    }
    
    // 验证端口
    if (!empty($config['hostport'])) {
        $port = (int)$config['hostport'];
        if ($port < 1 || $port > 65535) {
            $errors[] = '数据库端口范围应在1-65535之间';
        }
    }
    
    // 验证数据库名
    if (empty($config['database'])) {
        $errors[] = '数据库名不能为空';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $config['database'])) {
        $errors[] = '数据库名只能包含字母、数字和下划线';
    }
    
    // 验证用户名
    if (empty($config['username'])) {
        $errors[] = '数据库用户名不能为空';
    }
    
    // 验证表前缀
    if (!empty($config['prefix']) && !preg_match('/^[a-zA-Z0-9_]+$/', $config['prefix'])) {
        $errors[] = '表前缀只能包含字母、数字和下划线';
    }
    
    return $errors;
}

/**
 * 安全的数据库连接测试
 * 
 * @param array $config 数据库配置
 * @return array 测试结果
 */
function testDatabaseConnection(array $config): array
{
    try {
        $dsn = "mysql:host={$config['hostname']};port={$config['hostport']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        // 测试数据库是否存在
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
        $stmt->execute([$config['database']]);
        $exists = $stmt->fetch();
        
        if (!$exists) {
            // 尝试创建数据库
            $stmt = $pdo->prepare("CREATE DATABASE IF NOT EXISTS `{$config['database']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $stmt->execute();
        }
        
        // 选择数据库
        $pdo->exec("USE `{$config['database']}`");
        
        return ['success' => true, 'message' => '数据库连接成功'];
        
    } catch (PDOException $e) {
        return ['success' => false, 'message' => '数据库连接失败: ' . $e->getMessage()];
    }
}

// 获取当前主机信息（安全处理）
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$currentHost = $protocol . '://' . $host . '/';

// 系统环境检查
$errorInfo = null;

// 检查是否已安装
if (is_file(INSTALL_PATH . 'lock' . DS . 'install.lock')) {
    $errorInfo = '系统已安装，如需重新安装请删除文件：/config/install/lock/install.lock';
}
// 检查目录权限
elseif (!isReadWrite(ROOT_PATH . 'config' . DS)) {
    $errorInfo = ROOT_PATH . 'config' . DS . ' 目录读写权限不足';
}
elseif (!isReadWrite(ROOT_PATH . 'runtime' . DS)) {
    $errorInfo = ROOT_PATH . 'runtime' . DS . ' 目录读写权限不足';
}
elseif (!isReadWrite(ROOT_PATH . 'public' . DS)) {
    $errorInfo = ROOT_PATH . 'public' . DS . ' 目录读写权限不足';
}
// 检查PHP版本（更新为7.4.0）
elseif (!checkPhpVersion('7.4.0')) {
    $errorInfo = 'PHP版本不能小于7.4.0，当前版本：' . PHP_VERSION;
}
// 检查PDO扩展
elseif (!extension_loaded("PDO")) {
    $errorInfo = '当前未开启PDO扩展，无法进行安装';
}
// 检查PDO MySQL驱动
elseif (!extension_loaded("pdo_mysql")) {
    $errorInfo = '当前未开启PDO MySQL驱动，无法进行安装';
}

// 处理安装请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isAjax() && !$errorInfo) {

    // 过滤输入数据
    $config = filterInput($_POST);

    // 检查覆盖安装选项（在过滤前获取原始值）
    $coverInstall = isset($_POST['cover']) && $_POST['cover'] == '1';
    $config['cover'] = $coverInstall ? '1' : '0';

    // 验证数据库配置
    $validationErrors = validateDatabaseConfig($config);
    if (!empty($validationErrors)) {
        echo json_encode(['code' => 0, 'msg' => implode('<br>', $validationErrors)]);
        exit;
    }

    // 测试数据库连接
    $connectionTest = testDatabaseConnection($config);
    if (!$connectionTest['success']) {
        echo json_encode(['code' => 0, 'msg' => $connectionTest['message']]);
        exit;
    }

    try {
        // 生成环境配置文件
        $envConfig = generateEnvConfig($config);

        // 生成安全的配置文件
        $appConfig = getSecureAppConfig($config['admin_url'] ?? 'admin');
        $databaseConfig = getSecureDatabaseConfig($config);

        // 确保配置目录存在
        if (!is_dir(CONFIG_PATH)) {
            mkdir(CONFIG_PATH, 0755, true);
        }

        // 写入.env文件
        $envFile = ROOT_PATH . '.env';
        if (!file_put_contents($envFile, $envConfig)) {
            throw new Exception('无法创建环境配置文件');
        }

        // 设置.env文件安全权限
        chmod($envFile, 0600);

        // 写入配置文件
        if (!file_put_contents(CONFIG_PATH . 'app.php', $appConfig)) {
            throw new Exception('无法写入应用配置文件');
        }

        if (!file_put_contents(CONFIG_PATH . 'database.php', $databaseConfig)) {
            throw new Exception('无法写入数据库配置文件');
        }

        // 执行数据库安装
        $installResult = installDatabase($config);
        if (!$installResult['success']) {
            throw new Exception($installResult['message']);
        }

        // 创建安装锁定文件
        $lockDir = INSTALL_PATH . 'lock';
        if (!is_dir($lockDir)) {
            if (!mkdir($lockDir, 0755, true)) {
                throw new Exception('无法创建安装锁定目录: ' . $lockDir);
            }
        }

        $lockContent = json_encode([
            'install_time' => date('Y-m-d H:i:s'),
            'version' => '1.0.1',
            'admin_url' => $config['admin_url'] ?? 'admin'
        ]);

        $lockFile = $lockDir . DS . 'install.lock';
        $result = file_put_contents($lockFile, $lockContent);

        if ($result === false) {
            // 获取更详细的错误信息
            $error = error_get_last();
            $errorMsg = $error ? $error['message'] : '未知错误';
            throw new Exception('无法创建安装锁定文件: ' . $lockFile . ' - ' . $errorMsg);
        }

        // 验证文件是否真的创建成功
        if (!file_exists($lockFile)) {
            throw new Exception('安装锁定文件创建后验证失败: ' . $lockFile);
        }

        echo json_encode(['code' => 1, 'msg' => '安装成功']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['code' => 0, 'msg' => '安装失败: ' . $e->getMessage()]);
        exit;
    }
}

/**
 * 生成环境配置文件内容
 *
 * @param array $config 安装配置
 * @return string
 */
function generateEnvConfig(array $config): string
{
    $adminUrl = preg_replace('/[^a-zA-Z0-9_-]/', '', $config['admin_url'] ?? 'admin');
    $appKey = 'base64:' . base64_encode(random_bytes(32));

    return <<<EOT
# =====================================================
# 视频奖励平台环境配置文件
# =====================================================
#
# 重要提醒：
# 1. 此文件包含敏感信息，请勿提交到版本控制系统
# 2. 生产环境请修改所有默认值
# 3. 确保文件权限设置为 600 (仅所有者可读写)
#
# 自动生成时间: {DATE_PLACEHOLDER}
# =====================================================

# =====================================================
# 应用基础配置
# =====================================================

# 应用调试模式 (生产环境设置为false)
APP_DEBUG = true

# 应用环境 (local/testing/production)
APP_ENV = local

# 应用密钥 (用于加密)
APP_KEY = {$appKey}

# 应用域名
APP_HOST =

# 应用URL
APP_URL =

# 默认时区
APP_TIMEZONE = Asia/Shanghai

# =====================================================
# 数据库配置
# =====================================================

# 数据库类型
DB_CONNECTION = mysql

# 数据库服务器
DB_HOST = {$config['hostname']}

# 数据库端口
DB_PORT = {$config['hostport']}

# 数据库名称
DB_DATABASE = {$config['database']}

# 数据库用户名
DB_USERNAME = {$config['username']}

# 数据库密码
DB_PASSWORD = {$config['password']}

# 数据库表前缀
DB_PREFIX = {$config['prefix']}

# 数据库字符集
DB_CHARSET = utf8mb4

# =====================================================
# 后台管理配置
# =====================================================

# 后台访问URL
ADMIN_URL = {$adminUrl}

# 后台会话超时时间 (分钟)
ADMIN_SESSION_TIMEOUT = 120

# 后台登录失败锁定时间 (分钟)
ADMIN_LOGIN_LOCK_TIME = 30

# =====================================================
# 缓存配置
# =====================================================

# 缓存驱动 (file/redis/memcache)
CACHE_DRIVER = file

# =====================================================
# 会话配置
# =====================================================

# 会话驱动 (file/redis/database)
SESSION_DRIVER = file

# 会话生命周期 (分钟)
SESSION_LIFETIME = 120

# =====================================================
# 安全配置
# =====================================================

# CSRF保护开关
CSRF_PROTECTION = true

# XSS过滤开关
XSS_FILTER = true

# SQL注入防护开关
SQL_INJECTION_FILTER = true

# =====================================================
# 性能优化配置
# =====================================================

# 开启OPcache
OPCACHE_ENABLE = true

# 开启页面压缩
GZIP_ENABLE = true

# 图片压缩质量 (1-100)
IMAGE_QUALITY = 80

EOT;
}

/**
 * 生成安全的应用配置文件内容
 *
 * @param string $adminUrl 后台URL
 * @return string
 */
function getSecureAppConfig(string $adminUrl): string
{
    // 验证后台URL的安全性
    $adminUrl = preg_replace('/[^a-zA-Z0-9_-]/', '', $adminUrl);
    if (empty($adminUrl) || strlen($adminUrl) < 2) {
        $adminUrl = 'admin';
    }

    $config = <<<EOT
<?php
/**
 * 应用配置文件（安装程序生成）
 *
 * 当前版本变更说明：
 * - 系统安装时自动生成的配置文件
 * - 包含安全优化的配置选项
 * - 支持动态后台URL配置
 *
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date {DATE_PLACEHOLDER}
 */

declare(strict_types=1);

use think\\facade\\Env;

return [
    // 应用地址配置
    'app_host' => Env::get('APP_HOST', ''),

    // 应用调试模式
    'app_debug' => Env::get('APP_DEBUG', false),

    // 应用环境
    'app_env' => Env::get('APP_ENV', 'production'),

    // 应用密钥
    'app_key' => Env::get('APP_KEY', ''),

    // 应用URL
    'app_url' => Env::get('APP_URL', ''),

    // 应用的命名空间（留空使用默认）
    'app_namespace' => '',

    // 路由功能开关
    'with_route' => true,

    // 事件功能开关
    'with_event' => true,

    // 应用快速访问开关
    'app_express' => true,

    // 默认应用模块
    'default_app' => 'index',

    // 默认时区设置
    'default_timezone' => Env::get('APP_TIMEZONE', 'Asia/Shanghai'),

    // 应用映射配置（多应用模式）
    'app_map' => [
        Env::get('ADMIN_URL', 'admin') => 'admin',
    ],

    // 后台访问别名（动态配置）
    'admin_alias_name' => Env::get('ADMIN_URL', 'admin'),

    // 域名绑定配置（多应用模式）
    'domain_bind' => [],

    // 禁止URL访问的应用列表
    'deny_app_list' => ['common'],

    // 异常页面模板配置（安全优化）
    'exception_tmpl' => function() {
        if (Env::get('app_debug', false)) {
            \$thinkPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'topthink' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'think_exception.tpl';
            if (is_file(\$thinkPath)) {
                return \$thinkPath;
            }
        }
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'think_exception.tpl';
    },

    // 成功跳转页面模板
    'dispatch_success_tmpl' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',

    // 错误跳转页面模板
    'dispatch_error_tmpl' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',

    // 错误显示信息（生产环境安全）
    'error_message' => '系统繁忙，请稍后重试',

    // 错误信息显示开关（生产环境关闭）
    'show_error_msg' => Env::get('app_debug', false),

    // OSS静态资源前缀
    'oss_static_prefix' => Env::get('easyadmin.oss_static_prefix', 'static_easyadmin'),
];

EOT;

    // 替换占位符
    $config = str_replace('{DATE_PLACEHOLDER}', date('Y-m-d'), $config);

    return $config;
}

/**
 * 生成安全的数据库配置文件内容
 *
 * @param array $config 数据库配置
 * @return string
 */
function getSecureDatabaseConfig(array $config): string
{
    $hostname = $config['hostname'] ?? 'localhost';
    $database = $config['database'] ?? '';
    $username = $config['username'] ?? '';
    $password = $config['password'] ?? '';
    $hostport = $config['hostport'] ?? '3306';
    $prefix = $config['prefix'] ?? 'ea_';

    $configContent = <<<EOT
<?php
/**
 * 数据库配置文件（安装程序生成）
 *
 * 当前版本变更说明：
 * - 系统安装时自动生成的数据库配置
 * - 包含安全的连接参数设置
 * - 支持动态表前缀配置
 *
 * @author 迪迦奥特曼之父
 * @version 1.0.1
 * @date {DATE_PLACEHOLDER}
 */

declare(strict_types=1);

use think\\facade\\Env;

return [
    // 默认数据库连接
    'default' => Env::get('DB_CONNECTION', 'mysql'),

    // 数据库连接配置信息
    'connections' => [
        'mysql' => [
            // 数据库类型
            'type' => 'mysql',
            // 服务器地址
            'hostname' => Env::get('DB_HOST', 'localhost'),
            // 数据库名
            'database' => Env::get('DB_DATABASE', ''),
            // 用户名
            'username' => Env::get('DB_USERNAME', ''),
            // 密码
            'password' => Env::get('DB_PASSWORD', ''),
            // 端口
            'hostport' => Env::get('DB_PORT', '3306'),
            // 数据库连接参数
            'params' => [
                // 连接超时3秒
                \\PDO::ATTR_TIMEOUT => 3,
            ],
            // 数据库编码默认采用utf8mb4
            'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
            // 数据库表前缀
            'prefix' => Env::get('DB_PREFIX', ''),
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy' => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate' => false,
            // 读写分离后 主服务器数量
            'master_num' => 1,
            // 指定从服务器序号
            'slave_no' => '',
            // 自动读取主库数据
            'read_master' => false,
            // 是否严格检查字段是否存在
            'fields_strict' => true,
            // 是否需要断线重连
            'break_reconnect' => false,
            // 监听SQL
            'trigger_sql' => env('app_debug', false),
            // 开启字段缓存
            'fields_cache' => !env('app_debug', false),
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ],
    ],
];

EOT;

    // 替换占位符
    $configContent = str_replace('{DATE_PLACEHOLDER}', date('Y-m-d'), $configContent);

    return $configContent;
}

/**
 * 执行数据库安装
 *
 * @param array $config 配置信息
 * @return array 安装结果
 */
function installDatabase(array $config): array
{
    try {
        // 读取SQL安装文件
        $sqlFile = ROOT_PATH . 'config' . DS . 'install' . DS . 'sql' . DS . 'install.sql';
        if (!is_file($sqlFile)) {
            return ['success' => false, 'message' => 'SQL安装文件不存在'];
        }

        $sqlContent = file_get_contents($sqlFile);
        if (empty($sqlContent)) {
            return ['success' => false, 'message' => 'SQL安装文件内容为空'];
        }

        // 替换表前缀
        $prefix = $config['prefix'] ?? 'ds_';
        $sqlContent = str_replace('{prefix}', $prefix, $sqlContent);

        // 连接数据库
        $dsn = "mysql:host={$config['hostname']};port={$config['hostport']};dbname={$config['database']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        // 处理覆盖安装
        if (isset($config['cover']) && $config['cover'] == '1') {
            // 禁用外键检查
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

            // 获取所有表
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // 删除所有表（覆盖安装模式）
            foreach ($tables as $table) {
                $pdo->exec("DROP TABLE IF EXISTS `{$table}`");
            }

            // 重新启用外键检查
            $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
        }

        // 分割SQL语句并执行
        $sqlStatements = array_filter(array_map('trim', explode(';', $sqlContent)));

        foreach ($sqlStatements as $sql) {
            if (!empty($sql)) {
                $pdo->exec($sql);
            }
        }

        // 更新默认管理员密码（SQL文件中已包含默认数据）
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $updateAdminSql = "UPDATE `{$prefix}system_admin` SET `password` = ? WHERE `id` = 1";

        $stmt = $pdo->prepare($updateAdminSql);
        $stmt->execute([$adminPassword]);

        return ['success' => true, 'message' => '数据库安装成功'];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => '数据库安装失败: ' . $e->getMessage()];
    } catch (Exception $e) {
        return ['success' => false, 'message' => '安装过程出错: ' . $e->getMessage()];
    }
}

/**
 * 渲染安装页面模板
 *
 * @param string $errorInfo 错误信息
 * @param string $currentHost 当前主机地址
 */
function renderInstallTemplate($errorInfo, $currentHost) {
    $templateFile = __DIR__ . '/templates/install.html';

    if (is_file($templateFile)) {
        // 使用模板文件
        ob_start();
        include $templateFile;
        return ob_get_clean();
    } else {
        // 模板文件不存在时的降级处理
        return generateFallbackTemplate($errorInfo, $currentHost);
    }
}

/**
 * 生成降级模板（当模板文件不存在时）
 *
 * @param string $errorInfo 错误信息
 * @param string $currentHost 当前主机地址
 * @return string
 */
function generateFallbackTemplate($errorInfo, $currentHost) {
    $template = '<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>系统安装程序</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/static/css/install.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>系统安装程序</h1>
            <p>请填写以下信息完成系统安装</p>
        </div>
        <div class="content">';

    if ($errorInfo) {
        $template .= '<div class="error">' . htmlspecialchars($errorInfo) . '</div>';
    } else {
        $template .= '<p>模板文件缺失，请检查 /public/templates/install.html 文件是否存在。</p>';
    }

    $template .= '</div>
    </div>
    <script src="/static/js/install.js"></script>
</body>
</html>';

    return $template;
}

// 渲染并输出页面
echo renderInstallTemplate($errorInfo, $currentHost);
