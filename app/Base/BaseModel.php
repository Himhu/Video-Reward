<?php
// +----------------------------------------------------------------------
// | Video-Reward 基础模型类
// +----------------------------------------------------------------------
// | 提供所有模型的基础功能，包括自动时间戳和软删除
// +----------------------------------------------------------------------
// | 重构说明：替代原项目的TimeModel，提供独立的基础模型功能
// +----------------------------------------------------------------------

namespace app\Base;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 基础模型类
 * 
 * 为Video-Reward项目的所有模型提供统一的基础功能
 * 包括自动时间戳、软删除、数据验证等
 */
abstract class BaseModel extends Model
{
    /**
     * 自动时间戳类型
     * @var string|bool
     */
    protected $autoWriteTimestamp = true;

    /**
     * 创建时间字段
     * @var string
     */
    protected $createTime = 'create_time';

    /**
     * 更新时间字段
     * @var string
     */
    protected $updateTime = 'update_time';

    /**
     * 软删除功能
     */
    use SoftDelete;
    
    /**
     * 软删除字段
     * @var string|bool
     */
    protected $deleteTime = false;

    /**
     * 数据表字段信息
     * @var array
     */
    protected $schema = [];

    /**
     * 模型初始化
     */
    protected static function init()
    {
        // 模型事件
        static::beforeInsert(function ($model) {
            // 插入前的处理
            static::beforeSave($model);
        });

        static::beforeUpdate(function ($model) {
            // 更新前的处理
            static::beforeSave($model);
        });
    }

    /**
     * 保存前的数据处理
     * 
     * @param BaseModel $model
     */
    protected static function beforeSave($model)
    {
        // 子类可以重写此方法进行自定义处理
    }

    /**
     * 获取器 - 格式化创建时间
     * 
     * @param mixed $value
     * @return string
     */
    public function getCreateTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 获取器 - 格式化更新时间
     * 
     * @param mixed $value
     * @return string
     */
    public function getUpdateTimeAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', $value) : '';
    }

    /**
     * 修改器 - 处理创建时间
     * 
     * @param mixed $value
     * @return int
     */
    public function setCreateTimeAttr($value)
    {
        return is_numeric($value) ? $value : strtotime($value);
    }

    /**
     * 修改器 - 处理更新时间
     * 
     * @param mixed $value
     * @return int
     */
    public function setUpdateTimeAttr($value)
    {
        return is_numeric($value) ? $value : strtotime($value);
    }

    /**
     * 获取分页数据
     * 
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @param array $field 查询字段
     * @return array
     */
    public function getPageList($where = [], $page = 1, $limit = 20, $order = '', $field = ['*'])
    {
        $query = $this->where($where);
        
        // 总数
        $count = $query->count();
        
        // 列表数据
        $list = $query->field($field)
            ->page($page, $limit)
            ->order($order ?: $this->getPk() . ' desc')
            ->select();

        return [
            'count' => $count,
            'list' => $list,
            'page' => $page,
            'limit' => $limit,
            'pages' => ceil($count / $limit)
        ];
    }

    /**
     * 批量插入数据
     * 
     * @param array $data 数据数组
     * @param bool $replace 是否替换
     * @return int
     */
    public function insertAll($data, $replace = false)
    {
        if (empty($data)) {
            return 0;
        }

        // 添加时间戳
        $time = time();
        foreach ($data as &$item) {
            if ($this->autoWriteTimestamp) {
                $item[$this->createTime] = $item[$this->createTime] ?? $time;
                $item[$this->updateTime] = $item[$this->updateTime] ?? $time;
            }
        }

        return $this->insertAll($data, $replace);
    }

    /**
     * 根据条件更新数据
     * 
     * @param array $data 更新数据
     * @param array $where 更新条件
     * @return int
     */
    public function updateByWhere($data, $where)
    {
        if (empty($data) || empty($where)) {
            return 0;
        }

        // 添加更新时间
        if ($this->autoWriteTimestamp && !isset($data[$this->updateTime])) {
            $data[$this->updateTime] = time();
        }

        return $this->where($where)->update($data);
    }

    /**
     * 软删除数据
     * 
     * @param mixed $ids ID或ID数组
     * @return bool
     */
    public function softDelete($ids)
    {
        if (empty($ids)) {
            return false;
        }

        $ids = is_array($ids) ? $ids : [$ids];
        
        return $this->whereIn($this->getPk(), $ids)->delete();
    }

    /**
     * 恢复软删除的数据
     * 
     * @param mixed $ids ID或ID数组
     * @return bool
     */
    public function restore($ids)
    {
        if (empty($ids) || !$this->deleteTime) {
            return false;
        }

        $ids = is_array($ids) ? $ids : [$ids];
        
        return $this->onlyTrashed()
            ->whereIn($this->getPk(), $ids)
            ->update([$this->deleteTime => null]);
    }

    /**
     * 获取字段注释
     * 
     * @param string $field 字段名
     * @return string
     */
    public function getFieldComment($field)
    {
        if (empty($this->schema)) {
            return '';
        }

        return $this->schema[$field]['comment'] ?? '';
    }

    /**
     * 验证数据
     * 
     * @param array $data 数据
     * @param array $rules 验证规则
     * @param array $messages 错误消息
     * @return bool
     * @throws \think\exception\ValidateException
     */
    public function validateData($data, $rules = [], $messages = [])
    {
        if (empty($rules)) {
            return true;
        }

        $validate = new \think\Validate();
        $validate->rule($rules);
        
        if (!empty($messages)) {
            $validate->message($messages);
        }

        if (!$validate->check($data)) {
            throw new \think\exception\ValidateException($validate->getError());
        }

        return true;
    }
}
