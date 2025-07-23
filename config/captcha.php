<?php
// +----------------------------------------------------------------------
// | Video-Reward 验证码配置文件 (重构版本)
// +----------------------------------------------------------------------
// | 针对视频内容管理系统的验证码安全配置
// | 支持多场景验证码，增强系统安全性
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 默认验证码配置
    'default' => [
        // 验证码位数 (建议4-6位)
        'length'   => 4,
        // 验证码字符集合 (排除易混淆字符)
        'codeSet'  => '23456789abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码过期时间 (秒)
        'expire'   => Env::get('captcha.expire', 1800),
        // 是否使用中文验证码
        'useZh'    => false,
        // 是否使用算术验证码
        'math'     => false,
        // 是否使用背景图
        'useImgBg' => false,
        // 验证码字符大小
        'fontSize' => 20,
        // 是否使用混淆曲线
        'useCurve' => true,
        // 是否添加杂点
        'useNoise' => true,
        // 验证码字体 (不设置则随机)
        'fontttf'  => '',
        // 背景颜色 (RGB)
        'bg'       => [243, 251, 254],
        // 验证码图片高度
        'imageH'   => 40,
        // 验证码图片宽度
        'imageW'   => 120,
        // 验证码图片透明度
        'alpha'    => 0,
        // 是否采用API模式生成
        'api'      => false,
    ],

    // 登录验证码 (安全性要求较高)
    'login' => [
        'length'   => 5,
        'expire'   => 600, // 10分钟
        'fontSize' => 22,
        'useCurve' => true,
        'useNoise' => true,
        'imageH'   => 45,
        'imageW'   => 130,
    ],

    // 注册验证码 (中等安全性)
    'register' => [
        'length'   => 4,
        'expire'   => 1200, // 20分钟
        'fontSize' => 20,
        'useCurve' => true,
        'useNoise' => false,
        'imageH'   => 40,
        'imageW'   => 120,
    ],

    // 找回密码验证码 (高安全性)
    'reset' => [
        'length'   => 6,
        'expire'   => 300, // 5分钟
        'math'     => true, // 使用算术验证码
        'fontSize' => 18,
        'useCurve' => true,
        'useNoise' => true,
        'imageH'   => 50,
        'imageW'   => 150,
    ],

    // 评论验证码 (低安全性要求)
    'comment' => [
        'length'   => 3,
        'expire'   => 3600, // 1小时
        'fontSize' => 18,
        'useCurve' => false,
        'useNoise' => false,
        'imageH'   => 35,
        'imageW'   => 100,
    ],

    // 兼容性配置 (保持原有调用方式，确保功能零影响)
    'length'   => 5,   // 🔧 修复：恢复原有5位验证码
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    'expire'   => 1800,
    'useZh'    => false,
    'math'     => false,
    'useImgBg' => false,
    'fontSize' => 25,  // 🔧 修复：恢复原有25px字体大小
    'useCurve' => true,
    'useNoise' => true,
    'fontttf'  => '',
    'bg'       => [243, 251, 254],
    'imageH'   => 0,   // 🔧 修复：恢复原有自动高度
    'imageW'   => 0,   // 🔧 修复：恢复原有自动宽度
    'alpha'    => 0,
    'api'      => false,
];
