<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <19382406@qq.com>
// +----------------------------------------------------------------------

namespace think\swoole\facade;

use think\Facade;

class Server extends Facade
{
    protected static function getFacadeClass()
    {
        return 'swoole.server';
    }
}
