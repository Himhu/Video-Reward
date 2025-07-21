<?php
namespace think\db;

/**
 * 修复了类型检查问题的Builder类
 */
class FixedBuilder extends Builder
{
    /**
     * 重写不同字段使用相同查询条件（AND）方法，修复类型检查问题
     * @access protected
     * @param  Query  $query 查询对象
     * @param  string $logic Logic
     * @param  array  $val   查询条件
     * @param  array  $binds 参数绑定
     * @return array
     */
    protected function parseWhereLogic(Query $query, string $logic, array $val, array $binds = []): array
    {
        $where = [];
        foreach ($val as $value) {
            if ($value instanceof Raw) {
                $where[] = ' ' . $logic . ' ( ' . $this->parseRaw($query, $value) . ' )';
                continue;
            }

            if (is_array($value)) {
                if (key($value) !== 0) {
                    throw new \think\Exception('where express error:' . var_export($value, true));
                }
                $field = array_shift($value);
            } elseif (true === $value) {
                $where[] = ' ' . $logic . ' 1 ';
                continue;
            } elseif (!($value instanceof \Closure)) {
                throw new \think\Exception('where express error:' . var_export($value, true));
            }

            if ($value instanceof \Closure) {
                // 使用闭包查询
                $whereClosureStr = $this->parseClosureWhere($query, $value, $logic);
                if ($whereClosureStr) {
                    $where[] = $whereClosureStr;
                }
            } elseif (is_array($field)) {
                $where[] = $this->parseMultiWhereField($query, $value, $field, $logic, $binds);
            } elseif ($field instanceof Raw) {
                $where[] = ' ' . $logic . ' ' . $this->parseWhereItem($query, $field, $value, $binds);
            } elseif (is_string($field) && strpos($field, '|') !== false) {
                // 修复：确保 $field 是字符串并且包含 '|' 字符
                $where[] = $this->parseFieldsOr($query, $value, $field, $logic, $binds);
            } elseif (is_string($field) && strpos($field, '&') !== false) {
                // 修复：确保 $field 是字符串并且包含 '&' 字符
                $where[] = $this->parseFieldsAnd($query, $value, $field, $logic, $binds);
            } else {
                // 对字段使用表达式查询，确保字段是字符串
                $field = is_string($field) ? $field : '';
                $where[] = ' ' . $logic . ' ' . $this->parseWhereItem($query, $field, $value, $binds);
            }
        }
        
        return $where;
    }
} 