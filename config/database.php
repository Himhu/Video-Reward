<?php
// +----------------------------------------------------------------------
// | Video-Reward 数据库配置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 默认使用的数据库连接配置
    'default'         => Env::get('database.driver', 'mysql'),

    // 数据库连接配置信息
    'connections'     => [
        'mysql' => [
            // 数据库类型
            'type'              => Env::get('database.type', 'mysql'),
            // 服务器地址
            'hostname'          => Env::get('database.hostname', 'localhost'),
            // 数据库名
            'database'          => Env::get('database.database', '43_162_120_29'),
            // 用户名
            'username'          => Env::get('database.username', '43_162_120_29'),
            // 密码
            'password'          => Env::get('database.password', 'PRmd4K66ZDN5fkk5'),
            // 端口
            'hostport'          => Env::get('database.hostport', '3306'),
            // 数据库连接参数
            'params'            => [],
            // 数据库编码默认采用utf8mb4
            'charset'           => Env::get('database.charset', 'utf8mb4'),
            // 数据库表前缀
            'prefix'            => Env::get('database.prefix', 'd221s_'),

            // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
            'deploy'            => 0,
            // 数据库读写是否分离 主从式有效
            'rw_separate'       => false,
            // 读写分离后 主服务器数量
            'master_num'        => 1,
            // 指定从服务器序号
            'slave_no'          => '',
            // 是否严格检查字段是否存在
            'fields_strict'     => true,
            // 是否需要断线重连
            'break_reconnect'   => false,
            // 监听SQL
            'trigger_sql'       => true,
            // 开启字段缓存
            'fields_cache'      => false,
            // 字段缓存路径
            'schema_cache_path' => app()->getRuntimePath() . 'schema' . DIRECTORY_SEPARATOR,
        ],

        // 更多的数据库配置信息
    ],
];
