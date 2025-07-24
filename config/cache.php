<?php
// +----------------------------------------------------------------------
// | Video-Reward 缓存配置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 默认缓存驱动
    'default' => Env::get('cache.driver', 'file'),

    // 缓存连接配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        // redis缓存
        'redis'   =>  [
            // 驱动方式
            'type'   => 'redis',
            // 服务器地址
            'host'   => Env::get('redis.host', '127.0.0.1'),
            // 端口
            'port'   => Env::get('redis.port', 6379),
            // 密码
            'password' => Env::get('redis.password', ''),
            // 缓存有效期 0表示永久缓存
            'expire'   => 0,
            // 缓存前缀
            'prefix'   => Env::get('cache.prefix', ''),
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 数据库 0号数据库
            'select'     => 0,
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
            // 连接超时时间（秒）
            'timeout'    => 0,
        ],
    ],
];
