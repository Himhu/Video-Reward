<?php
// +----------------------------------------------------------------------
// | Video-Reward 主路由配置文件
// +----------------------------------------------------------------------
// | 包含所有模块的路由配置，保持与原项目的兼容性
// +----------------------------------------------------------------------

use think\facade\Route;

// 加载Content模块路由
if (file_exists(__DIR__ . '/../app/Modules/Content/routes.php')) {
    include __DIR__ . '/../app/Modules/Content/routes.php';
}

// 原项目兼容性路由 - 确保原有URL继续工作
Route::group('admin', function () {
    
    // Category控制器兼容性路由
    Route::any('Category/[:action]', function ($action = 'index') {
        // 重定向到新的CategoryController
        $controller = new \app\Modules\Content\Controllers\CategoryController(app());
        return call_user_func_array([$controller, $action], func_get_args());
    })->pattern(['action' => '\w+']);
    
});

// 默认路由保持不变
Route::get('/', function () {
    return redirect('/admin');
});
