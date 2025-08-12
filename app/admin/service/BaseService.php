<?php
// +----------------------------------------------------------------------
// | 服务名称：基础服务类
// +----------------------------------------------------------------------
// | 服务功能：提供Service层的基础功能和通用方法
// | 主要职责：业务逻辑封装、数据处理、业务规则管理
// | 设计模式：服务层模式，分离业务逻辑与控制器
// +----------------------------------------------------------------------

namespace app\admin\service;

use think\Model;
use think\facade\Db;

/**
 * 基础服务类
 * Class BaseService
 * @package app\admin\service
 */
abstract class BaseService
{
    /**
     * 关联的模型
     * @var Model
     */
    protected $model;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->initModel();
    }

    /**
     * 初始化模型（子类实现）
     */
    abstract protected function initModel();

    /**
     * 获取列表数据
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @return array
     */
    public function getList($where = [], $page = 1, $limit = 20, $order = 'id desc')
    {
        $count = $this->model->where($where)->count();
        $list = $this->model
            ->where($where)
            ->page($page, $limit)
            ->order($order)
            ->select()
            ->toArray();

        return [
            'count' => $count,
            'list' => $list
        ];
    }

    /**
     * 根据ID获取单条记录
     * @param int $id
     * @return array|null
     */
    public function getById($id)
    {
        $result = $this->model->find($id);
        return $result ? $result->toArray() : null;
    }

    /**
     * 创建记录
     * @param array $data
     * @return bool|int
     */
    public function create($data)
    {
        try {
            // 创建新的模型实例进行保存
            $modelClass = get_class($this->model);
            $model = new $modelClass;
            $result = $model->save($data);
            return $result ? $model->id : false;
        } catch (\Exception $e) {
            // 记录错误日志
            \think\facade\Log::error('BaseService create error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 更新记录
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        try {
            $model = $this->model->find($id);
            if (!$model) {
                return false;
            }
            return $model->save($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 删除记录
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            return $this->model->destroy($id);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 批量删除
     * @param array $ids
     * @return bool
     */
    public function batchDelete($ids)
    {
        try {
            return $this->model->destroy($ids);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 修改状态
     * @param int $id
     * @param string $field 字段名
     * @param mixed $value 新值
     * @return bool
     */
    public function changeStatus($id, $field, $value)
    {
        try {
            return $this->model->where('id', $id)->update([$field => $value]);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取统计数据
     * @param array $where 查询条件
     * @return array
     */
    public function getStatistics($where = [])
    {
        return [
            'total' => $this->model->where($where)->count(),
            'today' => $this->model->where($where)->whereTime('create_time', 'today')->count(),
            'yesterday' => $this->model->where($where)->whereTime('create_time', 'yesterday')->count(),
            'week' => $this->model->where($where)->whereTime('create_time', 'week')->count(),
            'month' => $this->model->where($where)->whereTime('create_time', 'month')->count(),
        ];
    }

    /**
     * 数据验证
     * @param array $data 待验证数据
     * @param array $rules 验证规则
     * @return bool|string true表示验证通过，字符串表示错误信息
     */
    protected function validate($data, $rules)
    {
        $validate = new \think\Validate();
        $validate->rule($rules);
        
        if (!$validate->check($data)) {
            return $validate->getError();
        }
        
        return true;
    }

    /**
     * 开始事务
     */
    protected function startTrans()
    {
        Db::startTrans();
    }

    /**
     * 提交事务
     */
    protected function commit()
    {
        Db::commit();
    }

    /**
     * 回滚事务
     */
    protected function rollback()
    {
        Db::rollback();
    }

    /**
     * 记录日志
     * @param string $message 日志信息
     * @param string $level 日志级别
     */
    protected function log($message, $level = 'info')
    {
        \think\facade\Log::record($message, $level);
    }
}
