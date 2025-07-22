<?php
namespace think\db\builder;

use think\App;
use think\db\Builder;
use think\facade\Config;
use think\db\Query;
use think\db\Raw;
use think\exception\Exception;
use Closure;

/**
 * 修复ThinkPHP Builder类中的parseWhereLogic方法
 * 确保在调用strpos函数前检查参数类型
 */
class Patch
{
    /**
     * 应用补丁
     * @param App $app 应用实例
     */
    public function register(App $app)
    {
        // 替换parseWhereLogic方法
        $this->patchMethod();
    }

    /**
     * 使用Runkit扩展修补Builder类的parseWhereLogic方法
     * 由于PHP不支持直接替换类方法，我们只能通过添加一个修复后的Builder子类
     * 然后在容器中绑定这个子类来实现
     */
    protected function patchMethod()
    {
        // 打印日志，说明补丁已被应用
        trace('应用Builder类parseWhereLogic方法补丁', 'info');
        
        // 此函数不直接修改原始类，而是依赖容器中的类绑定替换
        // 实际替换逻辑在 extend/think/db/FixedBuilder.php 中实现
        // 容器绑定在 config/bind.php 中设置
    }

    /**
     * 修复parseWhereLogic方法中的类型问题
     *
     * @param Builder $builder Builder实例
     * @param Query $query 查询对象
     * @param string $logic 逻辑运算符
     * @param array $val 查询条件
     * @param array $binds 参数绑定
     * @return array
     */
    public static function fixParseWhereLogic(Builder $builder, Query $query, string $logic, array $val, array $binds = []): array
    {
        $where = [];
        
        // 使用反射获取私有方法
        $parseClosureWhereMethod = new \ReflectionMethod($builder, 'parseClosureWhere');
        $parseClosureWhereMethod->setAccessible(true);
        
        $parseMultiWhereFieldMethod = new \ReflectionMethod($builder, 'parseMultiWhereField');
        $parseMultiWhereFieldMethod->setAccessible(true);
        
        $parseWhereItemMethod = new \ReflectionMethod($builder, 'parseWhereItem');
        $parseWhereItemMethod->setAccessible(true);
        
        $parseRawMethod = new \ReflectionMethod($builder, 'parseRaw');
        $parseRawMethod->setAccessible(true);
        
        $parseFieldsOrMethod = new \ReflectionMethod($builder, 'parseFieldsOr');
        $parseFieldsOrMethod->setAccessible(true);
        
        $parseFieldsAndMethod = new \ReflectionMethod($builder, 'parseFieldsAnd');
        $parseFieldsAndMethod->setAccessible(true);
        
        foreach ($val as $value) {
            if ($value instanceof Raw) {
                $where[] = ' ' . $logic . ' ( ' . $parseRawMethod->invoke($builder, $query, $value) . ' )';
                continue;
            }

            if (is_array($value)) {
                if (key($value) !== 0) {
                    throw new Exception('where express error:' . var_export($value, true));
                }
                $field = array_shift($value);
            } elseif (true === $value) {
                $where[] = ' ' . $logic . ' 1 ';
                continue;
            } elseif (!($value instanceof Closure)) {
                throw new Exception('where express error:' . var_export($value, true));
            }

            if ($value instanceof Closure) {
                // 使用闭包查询
                $whereClosureStr = $parseClosureWhereMethod->invoke($builder, $query, $value, $logic);
                if ($whereClosureStr) {
                    $where[] = $whereClosureStr;
                }
            } elseif (is_array($field)) {
                $where[] = $parseMultiWhereFieldMethod->invoke($builder, $query, $value, $field, $logic, $binds);
            } elseif ($field instanceof Raw) {
                $where[] = ' ' . $logic . ' ' . $parseWhereItemMethod->invoke($builder, $query, $field, $value, $binds);
            } elseif (is_string($field) && strpos($field, '|') !== false) {
                // 修复：确保 $field 是字符串并且包含 '|' 字符
                $where[] = $parseFieldsOrMethod->invoke($builder, $query, $value, $field, $logic, $binds);
            } elseif (is_string($field) && strpos($field, '&') !== false) {
                // 修复：确保 $field 是字符串并且包含 '&' 字符
                $where[] = $parseFieldsAndMethod->invoke($builder, $query, $value, $field, $logic, $binds);
            } else {
                // 对字段使用表达式查询
                $field = is_string($field) ? $field : '';
                $where[] = ' ' . $logic . ' ' . $parseWhereItemMethod->invoke($builder, $query, $field, $value, $binds);
            }
        }
        
        return $where;
    }
} 