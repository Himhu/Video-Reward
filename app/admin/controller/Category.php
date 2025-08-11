<?php
// +----------------------------------------------------------------------
// | 控制器名称：资源分类控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统资源的分类信息
// | 包含操作：分类列表、添加分类、编辑分类、删除分类等
// | 主要职责：维护系统资源的分类体系，使用Service层处理业务逻辑
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\CategoryService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="资源分类")
 */
class Category extends AdminController
{
    /**
     * 分类服务
     * @var CategoryService
     */
    protected $categoryService;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->categoryService = new CategoryService();
    }

    /**
     * @NodeAnotation(title="分类列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            // 获取分类树形数据
            $tree = $this->categoryService->getCategoryTree();

            return json([
                'code' => 0,
                'msg' => '',
                'count' => count($tree),
                'data' => $tree
            ]);
        }

        // 获取统计信息
        $statistics = $this->categoryService->getCategoryStatistics();
        $this->assign('statistics', $statistics);

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加分类")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $data = $this->request->post();

            $result = $this->categoryService->createCategory($data);
            if ($result === true) {
                $this->success('添加成功');
            } else {
                $this->error($result);
            }
        }

        // 获取父级分类选项
        $parentOptions = $this->categoryService->getCategoryOptions();
        $this->assign('parentOptions', $parentOptions);

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑分类")
     */
    public function edit($id)
    {
        $category = $this->categoryService->getById($id);
        if (!$category) {
            $this->error('分类不存在');
        }

        if ($this->request->isAjax()) {
            $data = $this->request->post();

            $result = $this->categoryService->updateCategory($id, $data);
            if ($result === true) {
                $this->success('更新成功');
            } else {
                $this->error($result);
            }
        }

        // 获取父级分类选项（排除自己）
        $parentOptions = $this->categoryService->getCategoryOptions($id);

        $this->assign('row', $category);
        $this->assign('parentOptions', $parentOptions);

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="删除分类")
     */
    public function delete($id)
    {
        $result = $this->categoryService->deleteCategory($id);
        if ($result === true) {
            $this->success('删除成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * @NodeAnotation(title="修改状态")
     */
    public function modify()
    {
        $id = $this->request->post('id');
        $field = $this->request->post('field');
        $value = $this->request->post('value');

        $result = $this->categoryService->changeStatus($id, $field, $value);
        if ($result) {
            $this->success('修改成功');
        } else {
            $this->error('修改失败');
        }
    }

    /**
     * @NodeAnotation(title="获取分类选项")
     */
    public function getOptions()
    {
        $options = $this->categoryService->getCategoryOptions();
        return json(['code' => 0, 'data' => $options]);
    }

    /**
     * @NodeAnotation(title="批量排序")
     */
    public function batchSort()
    {
        $sortData = $this->request->post('sort_data');
        if (empty($sortData)) {
            $this->error('排序数据不能为空');
        }

        $result = $this->categoryService->batchUpdateSort($sortData);
        if ($result) {
            $this->success('排序更新成功');
        } else {
            $this->error('排序更新失败');
        }
    }
}