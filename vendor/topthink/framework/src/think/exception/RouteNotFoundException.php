<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2021 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <19382406@qq.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think\exception;

/**
 * 路由未定义异常
 */
class RouteNotFoundException extends HttpException
{

    public function __construct()
    {
        parent::__construct(404, 'Route Not Found');
    }

}
