<?php
// +----------------------------------------------------------------------
// | Video-Reward Content模块路由配置
// +----------------------------------------------------------------------
// | 定义Content模块的所有路由规则，确保URL访问路径保持不变
// +----------------------------------------------------------------------
// | 重构说明：保持与原项目完全一致的URL路径，确保向后兼容
// +----------------------------------------------------------------------

use think\facade\Route;

// Content模块路由组
Route::group('admin', function () {
    
    // 分类管理路由 - 保持与原项目一致的URL路径
    Route::group('category', function () {
        // 分类列表页面
        Route::get('index', 'app\Modules\Content\Controllers\CategoryController@index');
        
        // 分类列表AJAX数据
        Route::post('index', 'app\Modules\Content\Controllers\CategoryController@index');
        
        // 添加分类页面
        Route::get('add', 'app\Modules\Content\Controllers\CategoryController@add');
        
        // 添加分类处理
        Route::post('add', 'app\Modules\Content\Controllers\CategoryController@add');
        
        // 编辑分类页面
        Route::get('edit', 'app\Modules\Content\Controllers\CategoryController@edit');
        
        // 编辑分类处理
        Route::post('edit', 'app\Modules\Content\Controllers\CategoryController@edit');
        
        // 删除分类
        Route::post('delete', 'app\Modules\Content\Controllers\CategoryController@delete');
        
        // 修改分类属性
        Route::post('modify', 'app\Modules\Content\Controllers\CategoryController@modify');
        
        // 导出分类数据
        Route::get('export', 'app\Modules\Content\Controllers\CategoryController@export');
        
        // 获取选择列表
        Route::get('selectList', 'app\Modules\Content\Controllers\CategoryController@selectList');
        Route::post('selectList', 'app\Modules\Content\Controllers\CategoryController@selectList');
    });
    
})->middleware(['admin.auth', 'admin.permission']);

// 兼容性路由 - 确保原有URL仍然可以访问
Route::group('admin', function () {
    
    // 原有的Category控制器路由映射到新的CategoryController
    Route::any('Category/[:action]', function ($action = 'index') {
        $controller = 'app\Modules\Content\Controllers\CategoryController';
        return app()->invokeMethod([$controller, $action]);
    })->pattern(['action' => '\w+']);
    
})->middleware(['admin.auth', 'admin.permission']);

// API路由 - 为前端提供RESTful API接口
Route::group('api/admin/content', function () {
    
    // 分类资源路由
    Route::resource('categories', 'app\Modules\Content\Controllers\CategoryController')->only([
        'index', 'show', 'store', 'update', 'delete'
    ]);
    
    // 分类树形结构
    Route::get('categories/tree', 'app\Modules\Content\Controllers\CategoryController@tree');
    
    // 分类选择列表
    Route::get('categories/select', 'app\Modules\Content\Controllers\CategoryController@selectList');
    
})->middleware(['admin.auth', 'admin.permission']);

// 前台路由 - 为前台页面提供分类数据
Route::group('content', function () {
    
    // 获取分类列表（前台使用）
    Route::get('categories', 'app\Modules\Content\Controllers\CategoryController@publicList');
    
    // 获取分类树形结构（前台使用）
    Route::get('categories/tree', 'app\Modules\Content\Controllers\CategoryController@publicTree');
    
});

// 路由别名定义 - 为常用路由定义别名
Route::alias([
    'admin/category' => 'app\Modules\Content\Controllers\CategoryController@index',
    'admin/category/list' => 'app\Modules\Content\Controllers\CategoryController@index',
    'admin/category/add' => 'app\Modules\Content\Controllers\CategoryController@add',
    'admin/category/edit' => 'app\Modules\Content\Controllers\CategoryController@edit',
    'admin/category/delete' => 'app\Modules\Content\Controllers\CategoryController@delete',
]);

// 路由模型绑定 - 自动注入模型实例
Route::bind('category', function ($value) {
    $categoryModel = new \app\Modules\Content\Models\Category();
    return $categoryModel->find($value) ?: abort(404, '分类不存在');
});

// 路由缓存配置
Route::cache(3600); // 缓存1小时
