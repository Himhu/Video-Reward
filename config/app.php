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
 * @date 2025-07-21
 */

declare(strict_types=1);

use think\facade\Env;

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
            $thinkPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'topthink' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'think_exception.tpl';
            if (is_file($thinkPath)) {
                return $thinkPath;
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
