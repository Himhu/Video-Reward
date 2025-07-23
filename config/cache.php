<?php
// +----------------------------------------------------------------------
// | Video-Reward 缓存配置文件 (重构版本)
// +----------------------------------------------------------------------
// | 针对视频内容管理系统优化的缓存配置
// | 支持多种缓存驱动，适配开发和生产环境
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 默认缓存驱动 (开发环境建议file，生产环境建议redis)
    'default' => Env::get('cache.driver', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        // 文件缓存 (保持原有配置，确保兼容性)
        'file' => [
            'type'       => 'File',
            'path'       => '', // 保持原有空路径配置
            'prefix'     => '', // 保持原有空前缀配置
            'expire'     => 0,  // 保持原有永久缓存配置
            'tag_prefix' => 'tag:', // 保持原有标签前缀
            'serialize'  => [], // 保持原有空序列化配置
        ],

        // Redis缓存 (推荐生产环境使用)
        'redis' => [
            'type'       => 'Redis',
            'host'       => Env::get('redis.host', '127.0.0.1'),
            'port'       => Env::get('redis.port', 6379),
            'password'   => Env::get('redis.password', ''),
            'select'     => Env::get('redis.select', 0),
            'timeout'    => Env::get('redis.timeout', 0),
            'expire'     => Env::get('redis.expire', 3600),
            'persistent' => Env::get('redis.persistent', false),
            'prefix'     => 'video_reward:',
            'tag_prefix' => 'vr_tag:',
            'serialize'  => ['serialize', 'unserialize'],
        ],

        // 内存缓存 (适合临时数据)
        'memory' => [
            'type'   => 'Memory',
            'prefix' => 'vr_mem_',
            'expire' => 600, // 10分钟
        ],
    ],

    // 缓存预热配置 (针对视频内容优化)
    'preload' => [
        'category' => [
            'key'    => 'category_tree',
            'expire' => 7200, // 2小时
        ],
        'config' => [
            'key'    => 'system_config',
            'expire' => 3600, // 1小时
        ],
    ],
];
