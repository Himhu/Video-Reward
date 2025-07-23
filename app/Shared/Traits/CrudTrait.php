<?php
// +----------------------------------------------------------------------
// | Video-Reward CRUD操作特性
// +----------------------------------------------------------------------
// | 提供控制器通用的数据增删改查方法
// +----------------------------------------------------------------------
// | 重构说明：替代原项目的Curd trait，提供独立的CRUD功能
// +----------------------------------------------------------------------

namespace app\Shared\Traits;

use think\Response;
use think\exception\ValidateException;

/**
 * CRUD操作特性
 * 
 * 为控制器提供标准化的增删改查操作
 */
trait CrudTrait
{
    /**
     * 模型实例
     * @var \app\Base\BaseModel
     */
    protected $model;

    /**
     * 排序字段
     * @var string
     */
    protected $sort = 'id desc';

    /**
     * 每页显示数量
     * @var int
     */
    protected $limit = 20;

    /**
     * 列表页面
     * 
     * @return Response
     */
    public function index()
    {
        if ($this->isAjax()) {
            // 处理选择列表请求
            if ($this->getParam('selectFields')) {
                return $this->selectList();
            }

            // 获取分页参数
            list($page, $limit, $where) = $this->buildTableParams();
            
            // 获取数据
            $result = $this->model->getPageList($where, $page, $limit, $this->sort);
            
            // 返回标准格式数据
            return $this->success('获取成功', [
                'code' => 0,
                'msg' => '',
                'count' => $result['count'],
                'data' => $result['list']
            ]);
        }

        return $this->view();
    }

    /**
     * 添加页面
     * 
     * @return Response
     */
    public function add()
    {
        if ($this->isPost()) {
            $data = $this->getPost();
            
            try {
                // 数据验证
                $this->validateData($data, 'add');
                
                // 保存数据
                $result = $this->model->save($data);
                
                if ($result) {
                    return $this->success('添加成功');
                } else {
                    return $this->error('添加失败');
                }
            } catch (ValidateException $e) {
                return $this->error($e->getError());
            } catch (\Exception $e) {
                return $this->error('添加失败：' . $e->getMessage());
            }
        }

        return $this->view();
    }

    /**
     * 编辑页面
     * 
     * @return Response
     */
    public function edit()
    {
        $id = $this->getParam('id', 0, 'intval');
        
        if (!$id) {
            return $this->error('参数错误');
        }

        $row = $this->model->find($id);
        if (!$row) {
            return $this->error('数据不存在');
        }

        if ($this->isPost()) {
            $data = $this->getPost();
            
            try {
                // 数据验证
                $this->validateData($data, 'edit');
                
                // 更新数据
                $result = $row->save($data);
                
                if ($result) {
                    return $this->success('编辑成功');
                } else {
                    return $this->error('编辑失败');
                }
            } catch (ValidateException $e) {
                return $this->error($e->getError());
            } catch (\Exception $e) {
                return $this->error('编辑失败：' . $e->getMessage());
            }
        }

        $this->assign('row', $row);
        return $this->view();
    }

    /**
     * 删除操作
     * 
     * @return Response
     */
    public function delete()
    {
        $id = $this->getParam('id', 0, 'intval');
        
        if (!$id) {
            return $this->error('参数错误');
        }

        try {
            $row = $this->model->find($id);
            if (!$row) {
                return $this->error('数据不存在');
            }

            $result = $row->delete();
            
            if ($result) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        } catch (\Exception $e) {
            return $this->error('删除失败：' . $e->getMessage());
        }
    }

    /**
     * 修改字段值
     * 
     * @return Response
     */
    public function modify()
    {
        $id = $this->getParam('id', 0, 'intval');
        $field = $this->getParam('field', '');
        $value = $this->getParam('value', '');

        if (!$id || !$field) {
            return $this->error('参数错误');
        }

        try {
            $row = $this->model->find($id);
            if (!$row) {
                return $this->error('数据不存在');
            }

            // 验证字段是否允许修改
            if (!$this->isAllowModifyField($field)) {
                return $this->error('不允许修改该字段');
            }

            $result = $row->save([$field => $value]);
            
            if ($result) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        } catch (\Exception $e) {
            return $this->error('修改失败：' . $e->getMessage());
        }
    }

    /**
     * 导出数据
     * 
     * @return Response
     */
    public function export()
    {
        try {
            $list = $this->model->order($this->sort)->select();
            
            $exportData = $this->formatExportData($list);
            
            return $this->success('导出成功', $exportData);
        } catch (\Exception $e) {
            return $this->error('导出失败：' . $e->getMessage());
        }
    }

    /**
     * 获取选择列表
     * 
     * @return Response
     */
    protected function selectList()
    {
        try {
            $list = $this->model->where('status', 1)
                ->order($this->sort)
                ->select();
                
            $selectData = $this->formatSelectData($list);
            
            return $this->success('获取成功', $selectData);
        } catch (\Exception $e) {
            return $this->error('获取失败：' . $e->getMessage());
        }
    }

    /**
     * 构建表格参数
     * 
     * @return array
     */
    protected function buildTableParams()
    {
        $page = $this->getParam('page', 1, 'intval');
        $limit = $this->getParam('limit', $this->limit, 'intval');
        
        // 构建查询条件
        $where = $this->buildWhere();
        
        return [$page, $limit, $where];
    }

    /**
     * 构建查询条件
     * 
     * @return array
     */
    protected function buildWhere()
    {
        $where = [];
        
        // 子类可以重写此方法构建具体的查询条件
        
        return $where;
    }

    /**
     * 数据验证
     * 
     * @param array $data 数据
     * @param string $scene 场景
     * @return bool
     */
    protected function validateData($data, $scene = '')
    {
        // 子类可以重写此方法进行数据验证
        return true;
    }

    /**
     * 检查字段是否允许修改
     * 
     * @param string $field 字段名
     * @return bool
     */
    protected function isAllowModifyField($field)
    {
        // 默认允许修改的字段
        $allowFields = ['status', 'sort'];
        
        return in_array($field, $allowFields);
    }

    /**
     * 格式化导出数据
     * 
     * @param mixed $list 数据列表
     * @return array
     */
    protected function formatExportData($list)
    {
        // 子类可以重写此方法格式化导出数据
        return $list->toArray();
    }

    /**
     * 格式化选择数据
     * 
     * @param mixed $list 数据列表
     * @return array
     */
    protected function formatSelectData($list)
    {
        // 子类可以重写此方法格式化选择数据
        return $list->toArray();
    }
}
