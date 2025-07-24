<?php

declare(strict_types=1);

namespace app\Services\System;

/**
 * 配置服务
 * 
 * 负责生成系统配置文件
 * 包括应用配置和数据库配置
 * 
 * @package app\Services\System
 */
class ConfigService
{
    /**
     * 生成应用配置文件
     * 
     * @param string $adminUrl 后台URL
     * @return bool
     */
    public function generateAppConfig(string $adminUrl): bool
    {
        $configPath = dirname(__DIR__, 3) . '/config/app.php';
        $config = $this->getAppConfigTemplate($adminUrl);
        
        return file_put_contents($configPath, $config) !== false;
    }

    /**
     * 生成数据库配置文件
     * 
     * @param array $dbConfig 数据库配置
     * @return bool
     */
    public function generateDatabaseConfig(array $dbConfig): bool
    {
        $configPath = dirname(__DIR__, 3) . '/config/database.php';
        $config = $this->getDatabaseConfigTemplate($dbConfig);
        
        return file_put_contents($configPath, $config) !== false;
    }

    /**
     * 获取应用配置模板
     *
     * @param string $adminUrl
     * @return string
     */
    private function getAppConfigTemplate(string $adminUrl): string
    {
        return <<<EOT
<?php
// +----------------------------------------------------------------------
// | Video-Reward 应用配置
// +----------------------------------------------------------------------

use think\\facade\Env;

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
        Env::get('video_reward.admin_url', '{$adminUrl}') => 'admin',
    ],

    // 后台别名
    'admin_alias_name' => Env::get('video_reward.admin_url', '{$adminUrl}'),

    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],

    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => ['common'],

    // 异常页面的模板文件
    'exception_tmpl'   => Env::get('app_debug') == 1
        ? app()->getThinkPath() . 'tpl/think_exception.tpl'
        : app()->getBasePath() . 'common/tpl/think_exception.tpl',

    // 跳转页面的成功模板文件
    'dispatch_success_tmpl' => app()->getBasePath() . 'common/tpl/dispatch_jump.tpl',

    // 跳转页面的失败模板文件
    'dispatch_error_tmpl' => app()->getBasePath() . 'common/tpl/dispatch_jump.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',

    // 显示错误信息
    'show_error_msg'   => false,
];

EOT;
    }

    /**
     * 获取数据库配置模板
     * 
     * @param array $dbConfig
     * @return string
     */
    private function getDatabaseConfigTemplate(array $dbConfig): string
    {

        return \app\Helpers\DatabaseConfigHelper::generateDatabaseConfigTemplate($dbConfig);
    }

    /**
     * 创建安装锁文件
     *
     * @return bool
     */
    public function createInstallLock(): bool
    {
        return self::createInstallLockStatic();
    }

    /**
     * 静态方法：生成应用配置文件
     *
     * @param string $adminUrl 后台URL
     * @param string|null $appPath 应用路径，默认自动检测
     * @return bool
     */
    public static function generateAppConfigStatic(string $adminUrl, ?string $appPath = null): bool
    {
        $appPath = $appPath ?: self::getAppPath();
        $configPath = $appPath . '/config/app.php';
        $instance = new self();
        $config = $instance->getAppConfigTemplate($adminUrl);

        return file_put_contents($configPath, $config) !== false;
    }

    /**
     * 生成.env文件
     *
     * @param string $adminUrl 后台URL
     * @param array|null $dbConfig 数据库配置
     * @param string|null $appPath 应用路径
     * @return bool
     */
    public static function generateEnvFileStatic(string $adminUrl, ?array $dbConfig = null, ?string $appPath = null): bool
    {
        $appPath = $appPath ?: self::getAppPath();
        $envPath = $appPath . '/.env';

        // 如果.env文件已存在，更新其中的后台地址配置
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            // 更新或添加后台地址配置
            if (strpos($envContent, 'VIDEO_REWARD.ADMIN_URL') !== false) {
                $envContent = preg_replace(
                    '/VIDEO_REWARD\.ADMIN_URL\s*=\s*.*/i',
                    "VIDEO_REWARD.ADMIN_URL = {$adminUrl}",
                    $envContent
                );
            } else {
                $envContent .= "\n[VIDEO_REWARD]\nADMIN_URL = {$adminUrl}\n";
            }

            return file_put_contents($envPath, $envContent) !== false;
        } else {
            // 创建新的.env文件，包含数据库配置
            $envContent = self::getEnvTemplate($adminUrl, $dbConfig);
            return file_put_contents($envPath, $envContent) !== false;
        }
    }

    /**
     * 获取.env文件模板
     *
     * @param string $adminUrl 后台URL
     * @param array|null $dbConfig 数据库配置
     * @return string
     */
    private static function getEnvTemplate(string $adminUrl, ?array $dbConfig = null): string
    {
        $envContent = <<<EOT
# Video-Reward 环境配置文件
# 由安装程序自动生成

[APP]
DEBUG = false

[VIDEO_REWARD]
ADMIN_URL = {$adminUrl}
VERSION = 2.0.0

EOT;

        // 如果提供了数据库配置，添加到.env文件中
        if ($dbConfig !== null) {
            $envContent .= <<<EOT

[DATABASE]
TYPE = {$dbConfig['type']}
HOSTNAME = {$dbConfig['hostname']}
DATABASE = {$dbConfig['database']}
USERNAME = {$dbConfig['db_username']}
PASSWORD = {$dbConfig['db_password']}
HOSTPORT = {$dbConfig['hostport']}
PREFIX = {$dbConfig['prefix']}
CHARSET = utf8mb4

EOT;
        }

        return $envContent;
    }

    /**
     * 静态方法：生成数据库配置文件
     *
     * @param array $dbConfig 数据库配置
     * @param string|null $appPath 应用路径，默认自动检测
     * @return bool
     */
    public static function generateDatabaseConfigStatic(array $dbConfig, ?string $appPath = null): bool
    {
        $appPath = $appPath ?: self::getAppPath();
        $configPath = $appPath . '/config/database.php';
        $instance = new self();
        $config = $instance->getDatabaseConfigTemplate($dbConfig);

        return file_put_contents($configPath, $config) !== false;
    }

    /**
     * 静态方法：创建安装锁文件
     *
     * @param string|null $appPath 应用路径，默认自动检测
     * @return bool
     */
    public static function createInstallLockStatic(?string $appPath = null): bool
    {
        $appPath = $appPath ?: self::getAppPath();
        $lockDir = $appPath . '/config/install/lock';
        $lockFile = $lockDir . '/install.lock';

        // 确保目录存在
        if (!is_dir($lockDir)) {
            mkdir($lockDir, 0755, true);
        }

        // 创建锁文件
        return file_put_contents($lockFile, date('Y-m-d H:i:s')) !== false;
    }

    /**
     * 获取应用路径
     *
     * @return string
     */
    private static function getAppPath(): string
    {
        // 优先使用常量
        if (defined('APP_PATH')) {
            return rtrim(APP_PATH, '/\\');
        }

        // 自动检测路径
        return dirname(__DIR__, 3);
    }
}
