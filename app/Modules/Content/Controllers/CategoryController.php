<?php
// +----------------------------------------------------------------------
// | Video-Reward 资源分类控制器 (重构版本)
// +----------------------------------------------------------------------
// | 控制器功能：管理系统资源的分类信息
// | 包含操作：分类列表、添加分类、编辑分类、删除分类等
// | 主要职责：维护系统资源的分类体系
// +----------------------------------------------------------------------
// | 重构说明：基于新的模块化架构，继承BaseController，保持原有功能完整性
// +----------------------------------------------------------------------

namespace app\Modules\Content\Controllers;

use app\Base\BaseController;
use app\Modules\Content\Models\Category;
use app\Modules\Content\Services\CategoryService;
use app\Shared\Traits\CrudTrait;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * 资源分类控制器 (重构版本)
 * 
 * @ControllerAnnotation(title="资源分类")
 */
class CategoryController extends BaseController
{
    /**
     * 使用新的CRUD trait提供标准化功能
     */
    use CrudTrait;

    /**
     * 分类模型实例
     * @var Category
     */
    protected $model;

    /**
     * 分类服务实例
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * 构造方法
     * 
     * @param App $app 应用实例
     */
    public function __construct(App $app)
    {
        parent::__construct($app);

        // 初始化模型 - 保持与原控制器完全一致
        $this->model = new Category();
        
        // 初始化服务层
        $this->categoryService = new CategoryService();
    }

    /**
     * 分类列表 - 使用CrudTrait的标准实现
     *
     * @NodeAnotation(title="列表")
     * @return \think\Response
     */
    // index方法由CrudTrait提供，无需重复实现

    /**
     * 添加分类 - 重写CrudTrait方法以使用服务层
     *
     * @NodeAnotation(title="添加")
     * @return \think\Response
     */
    public function add()
    {
        if ($this->isPost()) {
            $data = $this->getPost();

            try {
                // 使用服务层处理业务逻辑
                $result = $this->categoryService->create($data);

                if ($result) {
                    return $this->success('添加成功');
                } else {
                    return $this->error('添加失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }

        return $this->view();
    }

    /**
     * 编辑分类
     * 
     * @NodeAnotation(title="编辑")
     * @return \think\Response
     */
    public function edit()
    {
        $id = $this->getParam('id', 0, 'intval');
        
        if ($this->request->isPost()) {
            $post = $this->request->post();
            
            try {
                // 使用服务层处理业务逻辑
                $result = $this->categoryService->update($id, $post);
                
                if ($result) {
                    return $this->success('编辑成功');
                } else {
                    return $this->error('编辑失败');
                }
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
        
        // 获取分类信息
        $row = $this->categoryService->find($id);
        if (!$row) {
            return $this->error('分类不存在');
        }
        
        $this->assign('row', $row);
        return $this->view();
    }

    /**
     * 删除分类
     * 
     * @NodeAnotation(title="删除")
     * @return \think\Response
     */
    public function delete()
    {
        $id = $this->getParam('id', 0, 'intval');
        
        if (!$id) {
            return $this->error('参数错误');
        }
        
        try {
            $result = $this->categoryService->delete($id);
            
            if ($result) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 修改分类属性
     * 
     * @NodeAnotation(title="属性修改")
     * @return \think\Response
     */
    public function modify()
    {
        $post = $this->request->post();
        
        try {
            $result = $this->categoryService->modify($post);
            
            if ($result) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 导出分类数据
     * 
     * @NodeAnotation(title="导出")
     * @return \think\Response
     */
    public function export()
    {
        try {
            $result = $this->categoryService->export();
            return $this->success('导出成功', $result);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 构建查询条件 - 重写CrudTrait方法
     *
     * @return array
     */
    protected function buildWhere()
    {
        $where = [];

        // 分类名称搜索
        $ctitle = $this->getParam('ctitle', '');
        if ($ctitle) {
            $where[] = ['ctitle', 'like', '%' . $ctitle . '%'];
        }

        // 状态筛选
        $status = $this->getParam('status', '');
        if ($status !== '') {
            $where[] = ['status', '=', $status];
        }

        // 父级分类筛选
        $pid = $this->getParam('pid', '');
        if ($pid !== '') {
            $where[] = ['pid', '=', $pid];
        }

        return $where;
    }

    /**
     * 数据验证 - 重写CrudTrait方法
     *
     * @param array $data 数据
     * @param string $scene 场景
     * @return bool
     */
    protected function validateData($data, $scene = '')
    {
        try {
            if ($scene === 'add') {
                $this->categoryService->validateCategoryData($data);
            } elseif ($scene === 'edit') {
                $id = $this->getParam('id', 0, 'intval');
                $this->categoryService->validateCategoryData($data, $id);
            }
            return true;
        } catch (\Exception $e) {
            throw new \think\exception\ValidateException($e->getMessage());
        }
    }

    /**
     * 允许修改的字段 - 重写CrudTrait方法
     *
     * @param string $field 字段名
     * @return bool
     */
    protected function isAllowModifyField($field)
    {
        $allowFields = ['status', 'sort', 'ctitle'];
        return in_array($field, $allowFields);
    }

    /**
     * 格式化导出数据 - 重写CrudTrait方法
     *
     * @param mixed $list 数据列表
     * @return array
     */
    protected function formatExportData($list)
    {
        return $this->categoryService->export();
    }

    /**
     * 格式化选择数据 - 重写CrudTrait方法
     *
     * @param mixed $list 数据列表
     * @return array
     */
    protected function formatSelectData($list)
    {
        return $this->categoryService->getSelectList();
    }
}
