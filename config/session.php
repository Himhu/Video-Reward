<?php
// +----------------------------------------------------------------------
// | Video-Reward 会话配置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // session name
    'name'           => 'PHPSESSID',
    // SESSION_ID的提交变量,解决flash上传跨域
    'var_session_id' => '',
    // 驱动方式 支持file cache
    'type'           => Env::get('session.type', 'file'),
    // 存储连接标识 当type使用cache的时候有效
    'store'          => null,
    // 过期时间
    'expire'         => 1440,
    // 前缀
    'prefix'         => '',
    // 是否自动开启 SESSION
    'auto_start'     => true,
    // httponly设置
    'httponly'       => true,
    // 是否使用 cookie
    'use_cookies'    => true,
    // cookie 生命周期 0 表示随浏览器进程
    'cookie_lifetime' => 0,
    // cookie 保存路径
    'cookie_path'    => '/',
    // cookie 有效域名
    'cookie_domain'  => '',
    //  cookie 启用安全传输
    'cookie_secure'  => false,
    // httponly设置
    'cookie_httponly' => true,
    // 是否使用 trans_sid
    'use_trans_sid' => false,
    // SESSION 前缀
    'session_name'  => '',
];
