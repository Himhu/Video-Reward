<?php
// +----------------------------------------------------------------------
// | Video-Reward Swoole配置文件 (重构版本)
// +----------------------------------------------------------------------
// | 针对视频内容管理系统优化的Swoole高性能配置
// | 支持WebSocket、协程、连接池等高级特性
// +----------------------------------------------------------------------

use think\swoole\websocket\socketio\Handler;
use think\facade\Env;

return [
    // Swoole服务器配置
    'server' => [
        // 监听地址 (保持原有配置)
        'host'      => env('SWOOLE_HOST', '127.0.0.1'),
        // 监听端口 (🔧 修复：恢复原有80端口配置)
        'port'      => env('SWOOLE_PORT', 80),
        // 运行模式 (SWOOLE_PROCESS: 多进程模式)
        'mode'      => SWOOLE_PROCESS,
        // Socket类型 (TCP协议)
        'sock_type' => SWOOLE_SOCK_TCP,
        // 服务器选项配置
        'options'   => [
            // 进程ID文件路径
            'pid_file'              => runtime_path() . 'swoole.pid',
            // 日志文件路径
            'log_file'              => runtime_path() . 'swoole.log',
            // 是否守护进程模式 (生产环境建议true)
            'daemonize'             => Env::get('swoole.daemonize', false),
            // Reactor线程数 (建议设置为CPU核数的1-4倍)
            'reactor_num'           => Env::get('swoole.reactor_num', swoole_cpu_num()),
            // Worker进程数 (建议设置为CPU核数的1-4倍)
            'worker_num'            => Env::get('swoole.worker_num', swoole_cpu_num()),
            // Task进程数 (处理异步任务)
            'task_worker_num'       => Env::get('swoole.task_worker_num', swoole_cpu_num()),
            // 启用静态文件处理
            'enable_static_handler' => true,
            // 静态文件根目录
            'document_root'         => root_path('public'),
            // 数据包最大长度 (20MB，适合视频文件上传)
            'package_max_length'    => 20 * 1024 * 1024,
            // 输出缓冲区大小 (10MB)
            'buffer_output_size'    => 10 * 1024 * 1024,
            // Socket缓冲区大小 (128MB，适合大文件传输)
            'socket_buffer_size'    => 128 * 1024 * 1024,
            // 最大请求数 (防止内存泄漏)
            'max_request'           => Env::get('swoole.max_request', 10000),
            // 最大连接数
            'max_conn'              => Env::get('swoole.max_conn', 10000),
            // 心跳检测间隔 (秒)
            'heartbeat_check_interval' => 60,
            // 心跳超时时间 (秒)
            'heartbeat_idle_time'   => 600,
        ],
    ],
    // WebSocket配置 (适合实时视频互动)
    'websocket' => [
        // 是否启用WebSocket (🔧 修复：恢复原有默认关闭状态)
        'enable'        => false,
        // WebSocket处理器
        'handler'       => Handler::class,
        // 心跳检测间隔 (毫秒)
        'ping_interval' => 25000,
        // 心跳超时时间 (毫秒)
        'ping_timeout'  => 60000,
        // 房间管理配置
        'room'          => [
            'type'  => Env::get('websocket.room_type', 'table'),
            // 内存表配置 (适合小规模应用)
            'table' => [
                'room_rows'   => 4096,  // 房间数量
                'room_size'   => 2048,  // 房间信息大小
                'client_rows' => 8192,  // 客户端数量
                'client_size' => 2048,  // 客户端信息大小
            ],
            // Redis配置 (适合大规模分布式应用)
            'redis' => [
                'host'          => Env::get('redis.host', '127.0.0.1'),
                'port'          => Env::get('redis.port', 6379),
                'password'      => Env::get('redis.password', ''),
                'max_active'    => 3,
                'max_wait_time' => 5,
            ],
        ],
        // 监听事件配置
        'listen'        => [],
        // 订阅频道配置
        'subscribe'     => [],
    ],
    // RPC服务配置 (微服务架构支持)
    'rpc' => [
        'server' => [
            // 是否启用RPC服务
            'enable'   => Env::get('rpc.enable', false),
            // RPC服务端口
            'port'     => Env::get('rpc.port', 9502),
            // 注册的服务列表
            'services' => [],
        ],
        'client' => [],
    ],

    // 热更新配置 (开发环境)
    'hot_update' => [
        // 是否启用热更新 (仅开发环境)
        'enable'  => Env::get('app.debug', false),
        // 监听文件类型
        'name'    => ['*.php'],
        // 监听目录
        'include' => [app_path()],
        // 排除目录
        'exclude' => [],
    ],

    // 连接池配置 (提升数据库性能)
    'pool' => [
        // 数据库连接池
        'db' => [
            'enable'        => true,
            'max_active'    => Env::get('db.pool_max_active', 10),
            'max_wait_time' => Env::get('db.pool_max_wait_time', 5),
        ],
        // 缓存连接池
        'cache' => [
            'enable'        => true,
            'max_active'    => Env::get('cache.pool_max_active', 10),
            'max_wait_time' => Env::get('cache.pool_max_wait_time', 5),
        ],
        // Redis连接池
        'redis' => [
            'enable'        => true,
            'max_active'    => Env::get('redis.pool_max_active', 10),
            'max_wait_time' => Env::get('redis.pool_max_wait_time', 5),
        ],
    ],

    // 队列配置 (异步任务处理)
    'queue' => [
        // 是否启用队列 (🔧 修复：恢复原有默认关闭状态)
        'enable'  => false,
        // 队列工作进程配置
        'workers' => [
            // 视频处理队列
            'video_process' => [
                'handler'     => \app\queue\VideoProcessJob::class,
                'max_jobs'    => 0, // 0表示无限制
                'memory'      => 512, // 内存限制(MB)
                'sleep'       => 3, // 空闲时休眠时间(秒)
                'tries'       => 3, // 重试次数
            ],
            // 邮件发送队列
            'email_send' => [
                'handler'     => \app\queue\EmailSendJob::class,
                'max_jobs'    => 0,
                'memory'      => 128,
                'sleep'       => 1,
                'tries'       => 3,
            ],
        ],
    ],
    // 协程配置
    'coroutine' => [
        // 是否启用协程
        'enable' => true,
        // 协程Hook标志 (Hook所有阻塞函数)
        'flags'  => SWOOLE_HOOK_ALL,
    ],

    // 内存表配置 (高性能数据共享)
    'tables' => [
        // 在线用户表
        'online_users' => [
            'size'   => 10240, // 表大小
            'column' => [
                ['user_id', 'int', 4],
                ['login_time', 'int', 4],
                ['last_active', 'int', 4],
                ['ip', 'string', 15],
            ],
        ],
        // 系统统计表
        'statistics' => [
            'size'   => 1024,
            'column' => [
                ['key', 'string', 64],
                ['value', 'int', 8],
                ['update_time', 'int', 4],
            ],
        ],
    ],

    // 每个Worker进程需要预加载的实例
    'concretes' => [],

    // 重置器配置 (请求间状态重置)
    'resetters' => [],

    // 每次请求前需要清空的实例
    'instances' => [],

    // 每次请求前需要重新执行的服务
    'services' => [],
];
