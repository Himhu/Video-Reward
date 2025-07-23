<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

$AllowOrigin = @$_SERVER["HTTP_ORIGIN"];

header("Access-Control-Allow-Origin: ".$AllowOrigin );
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-file-name");
header('Access-Control-Allow-Credentials:true');

require __DIR__ . '/../vendor/autoload.php';

// 声明全局变量
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', __DIR__ . DS . '..' . DS);

// 判断是否安装程序
if (!is_file(ROOT_PATH . 'config' . DS . 'install' . DS . 'lock' . DS . 'install.lock')) {
    exit(header("location:/install.php"));
}

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
