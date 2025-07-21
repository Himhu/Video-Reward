<?php
// +----------------------------------------------------------------------
// | 容器绑定定义
// +----------------------------------------------------------------------

return [
    // 将ThinkPHP原生的Builder类绑定为我们修复后的类
    'think\db\Builder'       => 'think\db\FixedBuilder',
    'think\db\builder\Mysql' => 'think\db\builder\FixedMysql',
]; 