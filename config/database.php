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
 * @date 2025-07-21
 */

declare(strict_types=1);

use think\facade\Env;

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
                \PDO::ATTR_TIMEOUT => 3,
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
