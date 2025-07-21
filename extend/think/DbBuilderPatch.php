<?php
namespace think;

use think\db\builder\Patch;
use think\db\Builder;

/**
 * 数据库Builder类补丁应用器
 * 用于解决ThinkPHP Builder类中的strpos类型问题
 */
class DbBuilderPatch
{
    /**
     * 应用补丁方法
     *
     * 替换Builder类中的parseWhereLogic方法，修复strpos类型问题
     */
    public static function apply()
    {
        // 保存原始的parseWhereLogic方法
        $originalMethod = function (Query $query, string $logic, array $val, array $binds = []): array {
            return Patch::fixParseWhereLogic($this, $query, $logic, $val, $binds);
        };
        
        // 使用闭包绑定技术替换Builder类中的parseWhereLogic方法
        Builder::macro('parseWhereLogic', $originalMethod);
    }

    /**
     * 注册服务
     * @param App $app 应用实例
     */
    public function register(App $app)
    {
        // 实例化并注册补丁类
        $patch = new Patch();
        $patch->register($app);
        
        // 注册事件，确保每次请求都加载正确的Builder类
        Event::listen('app_init', function () use ($app) {
            // 确保使用我们的修复版Builder
            $app->bind('think\db\Builder', 'think\db\FixedBuilder');
            $app->bind('think\db\builder\Mysql', 'think\db\builder\FixedMysql');
        });
    }
} 