<?php
// 事件定义文件
return [
    'bind'      => [
    ],

    'listen'    => [
        'AppInit'  => [
            function () {
                // 加载helper.php
                if (is_file(root_path() . 'extend/think/helper.php')) {
                    include_once root_path() . 'extend/think/helper.php';
                }
            }
        ],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
    ],

    'subscribe' => [
    ],
];
