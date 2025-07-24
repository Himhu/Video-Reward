<?php
/**
 * Video-Reward 应用配置文件
 * 
 * 基于重构文档要求的现代化配置
 * 支持多应用模式和后台管理系统
 * 
 * @author Video-Reward Team
 * @version 2.0
 * @since 2025-01-23
 */

use think\facade\Env;

return [
    // 应用地址
    'app_host' => Env::get('app.host', ''),
    
    // 应用的命名空间
    'app_namespace' => '',
    
    // 是否启用路由
    'with_route' => true,
    
    // 是否启用事件
    'with_event' => true,
    
    // 开启应用快速访问
    'app_express' => true,
    
    // 默认应用
    'default_app' => 'index',
    
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',
    
    // 应用映射（自动多应用模式有效）
    // 支持用户自定义后台地址
    'app_map' => [
        // 动态获取安装时设置的后台地址
        // 如果未设置则默认为 'admin'
        Env::get('video_reward.admin_url', 'admin') => 'admin',
    ],
    
    // 后台别名（用于兼容性）
    'admin_alias_name' => Env::get('video_reward.admin_url', 'admin'),
    
    // 域名绑定（自动多应用模式有效）
    'domain_bind' => [],
    
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list' => ['common'],
    
    // 异常页面的模板文件
    'exception_tmpl' => Env::get('app.debug', false) 
        ? app()->getThinkPath() . 'tpl/think_exception.tpl' 
        : app()->getBasePath() . 'common' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'think_exception.tpl',
    
    // 跳转页面的成功模板文件
    'dispatch_success_tmpl' => app()->getBasePath() . 'common' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',
    
    // 跳转页面的失败模板文件
    'dispatch_error_tmpl' => app()->getBasePath() . 'common' . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'dispatch_jump.tpl',
    
    // 错误显示信息,非调试模式有效
    'error_message' => '页面错误！请稍后再试～',
    
    // 显示错误信息
    'show_error_msg' => Env::get('app.debug', false),
    
    // 静态资源上传到OSS前缀
    'oss_static_prefix' => Env::get('video_reward.oss_static_prefix', 'static_video_reward'),
    
    // Video-Reward 特定配置
    'video_reward' => [
        // 系统名称
        'system_name' => 'Video-Reward',
        
        // 系统版本
        'version' => '2.0.0',
        
        // 是否启用调试模式
        'debug' => Env::get('app.debug', false),
        
        // 安装状态检查
        'check_install' => true,
        
        // 默认后台地址
        'default_admin_url' => 'admin',
        
        // 支持的语言
        'supported_languages' => ['zh-cn', 'en'],
        
        // 默认语言
        'default_language' => 'zh-cn',
    ],
];
