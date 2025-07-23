<?php
// +----------------------------------------------------------------------
// | Video-Reward 安装程序 (重构版本)
// +----------------------------------------------------------------------
// | 支持新的模块化架构，兼容原项目功能
// +----------------------------------------------------------------------
// | 重构说明：适配新的目录结构，保持功能完全一致
// +----------------------------------------------------------------------

ini_set('display_errors', 'On');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use think\facade\Db;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/topthink/framework/src/helper.php';

// 定义路径常量
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', __DIR__ . DS . '..' . DS);
define('INSTALL_PATH', ROOT_PATH . 'config' . DS . 'install' . DS);
define('CONFIG_PATH', ROOT_PATH . 'config' . DS);

// 新增：模块化架构路径常量
define('APP_PATH', ROOT_PATH . 'app' . DS);
define('MODULES_PATH', APP_PATH . 'Modules' . DS);
define('BASE_PATH', APP_PATH . 'Base' . DS);
define('SHARED_PATH', APP_PATH . 'Shared' . DS);

$currentHost = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';

// 生成安全验证码
$verify = md5(time() . mt_rand(1000, 9999));

/**
 * 检查目录读写权限
 */
function isReadWrite($file)
{
    if (DIRECTORY_SEPARATOR == '\\') {
        return true;
    }
    if (DIRECTORY_SEPARATOR == '/' && @ ini_get("safe_mode") === false) {
        return is_writable($file);
    }
    if (!is_file($file) || ($fp = @fopen($file, "r+")) === false) {
        return false;
    }
    fclose($fp);
    return true;
}

// 环境检查
$errorInfo = null;
if (is_file(INSTALL_PATH . 'lock' . DS . 'install.lock')) {
    $errorInfo = '已安装系统，如需重新安装请删除文件：/config/install/lock/install.lock';
} elseif (!isReadWrite(ROOT_PATH . 'config' . DS)) {
    $errorInfo = ROOT_PATH . 'config' . DS . '：读写权限不足';
} elseif (!isReadWrite(ROOT_PATH . 'storage' . DS)) {
    $errorInfo = ROOT_PATH . 'storage' . DS . '：读写权限不足';
} elseif (!isReadWrite(ROOT_PATH . 'public' . DS)) {
    $errorInfo = ROOT_PATH . 'public' . DS . '：读写权限不足';
} elseif (!checkPhpVersion('7.1.0')) {
    $errorInfo = 'PHP版本不能小于7.1.0';
} elseif (!extension_loaded("PDO")) {
    $errorInfo = '当前未开启PDO，无法进行安装';
}

// 新增：检查新架构目录是否存在
if (!$errorInfo) {
    $requiredDirs = [
        APP_PATH => 'app目录',
        BASE_PATH => 'app/Base目录',
        SHARED_PATH => 'app/Shared目录',
        MODULES_PATH => 'app/Modules目录'
    ];

    foreach ($requiredDirs as $dir => $name) {
        if (!is_dir($dir)) {
            $errorInfo = "缺少必要的{$name}，请确保新架构目录结构完整";
            break;
        }
    }
}

// 新增：检查SQL安装文件是否存在
if (!$errorInfo) {
    $sqlFile = INSTALL_PATH . 'sql' . DS . 'install.sql';
    if (!file_exists($sqlFile)) {
        $errorInfo = '缺少必要的数据库安装文件：config/install/sql/install.sql';
    }
}

// POST请求处理
if (isAjax() && isPost()) {
    $hostname = trim($_POST['hostname']);
    $hostport = trim($_POST['hostport']);
    $database = trim($_POST['database']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $prefix   = trim($_POST['prefix']);
    $cover    = intval($_POST['cover']);
    
    // 管理员信息
    $admin_username = trim($_POST['admin_username']);
    $admin_password = trim($_POST['admin_password']);
    $adminUrl = trim($_POST['admin_url']);

    // 验证表前缀格式
    $prefixValidation = validatePrefix($prefix);
    if ($prefixValidation !== true) {
        $data = [
            'code' => 0,
            'msg'  => $prefixValidation,
        ];
        die(json_encode($data));
    }

    // 数据库配置
    $config = [
        'type'     => 'mysql',
        'hostname' => $hostname,
        'hostport' => $hostport,
        'username' => $username,
        'password' => $password,
        'charset'  => 'utf8mb4',
        'prefix'   => $prefix,
        'cover'    => $cover, // 添加覆盖安装参数
        'debug'    => true,
    ];
    
    Db::setConfig([
        'default'     => 'mysql',
        'connections' => [
            'mysql'   => $config,
            'install' => array_merge($config, ['database' => $database]),
        ],
    ]);

    // 检测数据库连接
    if (!checkConnect()) {
        $data = [
            'code' => 0,
            'msg'  => '数据库连接失败',
        ];
        die(json_encode($data));
    }
    
    // 检测数据库是否存在
    if (!$cover && checkDatabase($database)) {
        $data = [
            'code' => 0,
            'msg'  => '数据库已存在，请选择覆盖安装或者修改数据库名',
        ];
        die(json_encode($data));
    }
    
    // 创建数据库
    createDatabase($database);
    
    // 执行安装
    $install = install($admin_username, $admin_password, array_merge($config, ['database' => $database]), $adminUrl);
    if ($install !== true) {
        $data = [
            'code' => 0,
            'msg'  => '系统安装失败：' . $install,
        ];
        die(json_encode($data));
    }
    
    $data = [
        'code' => 1,
        'msg'  => '系统安装成功，正在跳转登录页面',
        'url'  => $adminUrl,
    ];
    die(json_encode($data));
}

/**
 * 检查是否为AJAX请求
 */
function isAjax()
{
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    } else {
        return false;
    }
}

/**
 * 检查是否为POST请求
 */
function isPost()
{
    return ($_SERVER['REQUEST_METHOD'] == 'POST' && checkurlHash($GLOBALS['verify'])
        && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
}

/**
 * 检查PHP版本
 */
function checkPhpVersion($version)
{
    $php_version = explode('-', phpversion());
    $check = strnatcasecmp($php_version[0], $version) >= 0 ? true : false;
    return $check;
}

/**
 * 检查数据库连接
 */
function checkConnect()
{
    try {
        Db::query("select version()");
    } catch (\Exception $e) {
        return false;
    }
    return true;
}

/**
 * 检查数据库是否存在
 */
function checkDatabase($database)
{
    $check = Db::query("SELECT * FROM information_schema.schemata WHERE schema_name='{$database}'");
    if (empty($check)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 处理覆盖安装 - 删除现有表
 * @param string $database 数据库名
 * @param int $cover 是否覆盖安装
 * @param string $prefix 表前缀
 * @return bool|string 成功返回true，失败返回错误信息
 */
function handleCoverInstall($database, $cover, $prefix = 'ds_')
{
    if ($cover != 1) {
        return true; // 不覆盖安装，直接返回
    }

    try {
        // 切换到目标数据库
        Db::execute("USE `{$database}`");

        // 获取所有以指定前缀开头的表
        $tables = Db::query("SHOW TABLES LIKE '{$prefix}%'");

        if (!empty($tables)) {
            // 禁用外键检查
            Db::execute("SET FOREIGN_KEY_CHECKS = 0");

            // 删除现有表
            foreach ($tables as $table) {
                $tableName = array_values($table)[0];
                Db::execute("DROP TABLE IF EXISTS `{$tableName}`");
            }

            // 恢复外键检查
            Db::execute("SET FOREIGN_KEY_CHECKS = 1");
        }

        return true;
    } catch (\Exception $e) {
        return "覆盖安装失败：" . $e->getMessage();
    }
}

/**
 * 创建数据库
 */
function createDatabase($database)
{
    try {
        Db::execute("CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    } catch (\Exception $e) {
        return false;
    }
    return true;
}

/**
 * 验证表前缀格式
 * @param string $prefix 表前缀
 * @return bool|string 验证通过返回true，失败返回错误信息
 */
function validatePrefix($prefix)
{
    // 检查是否为空
    if (empty($prefix)) {
        return '表前缀不能为空';
    }

    // 检查前缀格式：必须以字母开头，可包含字母、数字、下划线，必须以下划线结尾
    if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]*_$/', $prefix)) {
        return '表前缀格式不正确，必须以字母开头，以下划线结尾，只能包含字母、数字和下划线';
    }

    // 检查长度
    if (strlen($prefix) > 20) {
        return '表前缀长度不能超过20个字符';
    }

    // 检查是否包含MySQL保留字
    $reservedWords = ['select', 'insert', 'update', 'delete', 'create', 'drop', 'table', 'database'];
    $prefixLower = strtolower(rtrim($prefix, '_'));
    if (in_array($prefixLower, $reservedWords)) {
        return '表前缀不能使用MySQL保留字';
    }

    return true;
}

/**
 * 解析SQL文件
 */
function parseSql($sql = '', $to, $from)
{
    list($pure_sql, $comment) = [[], false];
    $sql = explode("\n", trim(str_replace(["\r\n", "\r"], "\n", $sql)));
    foreach ($sql as $key => $line) {
        if ($line == '') {
            continue;
        }
        if (preg_match("/^(#|--)/", $line)) {
            continue;
        }
        if (preg_match("/^\/\*(.*?)\*\//", $line)) {
            continue;
        }
        if (substr($line, 0, 2) == '/*') {
            $comment = true;
            continue;
        }
        if (substr($line, -2) == '*/') {
            $comment = false;
            continue;
        }
        if ($comment) {
            continue;
        }
        if ($from != '') {
            $line = str_replace('`' . $from, '`' . $to, $line);
        }
        if ($line == 'BEGIN;' || $line == 'COMMIT;') {
            continue;
        }
        array_push($pure_sql, $line);
    }
    $pure_sql = implode($pure_sql, "\n");
    $pure_sql = explode(";\n", $pure_sql);
    return $pure_sql;
}

/**
 * 执行安装程序 (重构版本 - 支持覆盖安装和前缀验证)
 */
function install($username, $password, $config, $adminUrl)
{
    try {
        // 验证表前缀格式
        $prefixValidation = validatePrefix($config['prefix']);
        if ($prefixValidation !== true) {
            return $prefixValidation;
        }

        // 处理覆盖安装
        $coverResult = handleCoverInstall($config['database'], $config['cover'], $config['prefix']);
        if ($coverResult !== true) {
            return $coverResult;
        }

        // 读取SQL文件
        $sqlPath = file_get_contents(INSTALL_PATH . 'sql' . DS . 'install.sql');
        if (!$sqlPath) {
            return '无法读取install.sql文件';
        }

        // 解析SQL并替换表前缀
        $sqlArray = parseSql($sqlPath, $config['prefix'], 'ds_');

        // 开始事务
        Db::startTrans();

        // 执行SQL语句
        foreach ($sqlArray as $vo) {
            $vo = trim($vo);
            if (empty($vo)) {
                continue;
            }

            try {
                Db::connect('install')->execute($vo);
            } catch (\Exception $e) {
                Db::rollback();
                return "SQL执行失败：" . $e->getMessage() . " SQL: " . substr($vo, 0, 100) . "...";
            }
        }

        // 创建管理员账户
        Db::connect('install')
            ->name('admin')
            ->where('id', 1)
            ->delete();
        Db::connect('install')
            ->name('admin')
            ->insert([
                'id'          => 1,
                'username'    => $username,
                'password'    => password($password),
                'create_time' => time(),
                'update_time' => time(),
            ]);

        // 处理安装文件
        !is_dir(INSTALL_PATH) && @mkdir(INSTALL_PATH, 0755, true);
        !is_dir(INSTALL_PATH . 'lock' . DS) && @mkdir(INSTALL_PATH . 'lock' . DS, 0755, true);
        @file_put_contents(INSTALL_PATH . 'lock' . DS . 'install.lock', date('Y-m-d H:i:s'));

        // 生成配置文件（适配新架构）
        @file_put_contents(CONFIG_PATH . 'app.php', getAppConfig($adminUrl));
        @file_put_contents(CONFIG_PATH . 'database.php', getDatabaseConfig($config));

        // 新增：创建模块化架构需要的配置
        createModuleConfigs();

        Db::commit();
    } catch (\Exception $e) {
        Db::rollback();
        return $e->getMessage();
    }
    return true;
}

/**
 * 密码加密
 */
function password($value)
{
    $value = sha1('blog_') . md5($value) . md5('_encrypt') . sha1($value);
    return sha1($value);
}

/**
 * 生成应用配置文件（适配新架构）
 */
function getAppConfig($admin)
{
    $config = <<<EOT
<?php
// +----------------------------------------------------------------------
// | 应用设置 (重构版本)
// +----------------------------------------------------------------------
// | 支持新的模块化架构
// +----------------------------------------------------------------------

use think\\facade\\Env;

return [
    // 应用地址
    'app_host'         => Env::get('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 是否启用事件
    'with_event'       => true,
    // 开启应用快速访问
    'app_express'      => true,
    // 默认应用
    'default_app'      => 'index',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [
        Env::get('easyadmin.admin', 'admin') => 'admin',
    ],
    // 后台别名
    'admin_alias_name' => Env::get('easyadmin.admin', 'admin'),
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用
    'deny_app_list'    => ['common'],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => false,
];
EOT;
    return $config;
}

/**
 * 生成数据库配置文件
 */
function getDatabaseConfig($config)
{
    $database = <<<EOT
<?php
// +----------------------------------------------------------------------
// | 数据库设置
// +----------------------------------------------------------------------

return [
    // 默认数据库连接
    'default'         => 'mysql',

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'            => '{$config['type']}',
            // 服务器地址
            'hostname'        => '{$config['hostname']}',
            // 数据库名
            'database'        => '{$config['database']}',
            // 用户名
            'username'        => '{$config['username']}',
            // 密码
            'password'        => '{$config['password']}',
            // 端口
            'hostport'        => '{$config['hostport']}',
            // 数据库连接参数
            'params'          => [],
            // 数据库编码默认采用utf8
            'charset'         => '{$config['charset']}',
            // 数据库表前缀
            'prefix'          => '{$config['prefix']}',
            // 数据库调试模式
            'debug'           => {$config['debug']},
            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'          => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'     => false,
            // 读写分离后 主服务器数量
            'master_num'      => 1,
            // 指定从服务器序号
            'slave_no'        => '',
            // 自动读取主库数据
            'read_master'     => false,
            // 是否严格检查字段是否存在
            'fields_strict'   => true,
            // 开启字段缓存
            'fields_cache'    => false,
        ],
    ],
];
EOT;
    return $database;
}

/**
 * 创建模块化架构需要的配置
 */
function createModuleConfigs()
{
    // 创建模块配置目录
    $moduleConfigPath = CONFIG_PATH . 'modules' . DS;
    if (!is_dir($moduleConfigPath)) {
        @mkdir($moduleConfigPath, 0755, true);
    }

    // 创建Content模块配置
    $contentConfig = <<<EOT
<?php
// +----------------------------------------------------------------------
// | Content模块配置
// +----------------------------------------------------------------------

return [
    // 模块名称
    'name' => 'Content',

    // 模块描述
    'description' => '内容管理模块',

    // 模块版本
    'version' => '1.0.0',

    // 是否启用
    'enabled' => true,

    // 模块路由前缀
    'route_prefix' => 'content',

    // 模块中间件
    'middleware' => [],

    // 模块服务提供者
    'providers' => [],
];
EOT;
    @file_put_contents($moduleConfigPath . 'content.php', $contentConfig);
}

/**
 * 检查URL哈希
 */
function checkurlHash($verify)
{
    return isset($_POST['verify']) && $_POST['verify'] === $verify;
}


/**
 * 渲染安装页面
 * 使用独立的HTML模板文件，实现前后端分离
 */
function renderInstallPage() {
    global $errorInfo, $verify, $currentHost;

    // 模板文件路径
    $templatePath = __DIR__ . '/static/install/templates/install.html';

    // 读取模板内容
    $template = file_get_contents($templatePath);

    // 开始输出缓冲
    ob_start();

    // 执行模板（允许PHP代码执行）
    eval('?>' . $template);

    // 获取渲染结果
    $output = ob_get_clean();

    // 输出最终页面
    echo $output;
}

// 如果不是AJAX请求，渲染安装页面
if (!isAjax()) {
    renderInstallPage();
    exit;
}
