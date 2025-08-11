<?php
// +----------------------------------------------------------------------
// | 服务名称：分类管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理分类相关的业务逻辑
// | 主要职责：分类的增删改查、分类树构建、状态管理等
// | 业务规则：分类层级管理、状态控制、数据验证
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\Category;

/**
 * 分类管理服务
 * Class CategoryService
 * @package app\admin\service
 */
class CategoryService extends BaseService
{
    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new Category();
    }

    /**
     * 获取分类树形结构
     * @param int $pid 父级ID
     * @param bool $includeDisabled 是否包含禁用的分类
     * @return array
     */
    public function getCategoryTree($pid = 0, $includeDisabled = true)
    {
        $where = ['pid' => $pid];
        if (!$includeDisabled) {
            $where['status'] = 1;
        }

        $categories = $this->model->where($where)->order('sort asc, id asc')->select()->toArray();
        
        foreach ($categories as &$category) {
            $category['children'] = $this->getCategoryTree($category['id'], $includeDisabled);
            $category['has_children'] = !empty($category['children']);
        }

        return $categories;
    }

    /**
     * 获取分类选项列表（用于下拉框）
     * @param int $excludeId 排除的分类ID（编辑时排除自己）
     * @return array
     */
    public function getCategoryOptions($excludeId = 0)
    {
        $tree = $this->getCategoryTree(0, false);
        $options = [['id' => 0, 'name' => '顶级分类']];
        
        $this->buildOptions($tree, $options, 0, $excludeId);
        
        return $options;
    }

    /**
     * 递归构建选项列表
     * @param array $tree 分类树
     * @param array &$options 选项数组
     * @param int $level 层级
     * @param int $excludeId 排除的ID
     */
    private function buildOptions($tree, &$options, $level = 0, $excludeId = 0)
    {
        foreach ($tree as $item) {
            if ($item['id'] == $excludeId) {
                continue;
            }
            
            $prefix = str_repeat('　', $level) . ($level > 0 ? '└ ' : '');
            $options[] = [
                'id' => $item['id'],
                'name' => $prefix . $item['ctitle']
            ];
            
            if (!empty($item['children'])) {
                $this->buildOptions($item['children'], $options, $level + 1, $excludeId);
            }
        }
    }

    /**
     * 创建分类
     * @param array $data 分类数据
     * @return bool|string
     */
    public function createCategory($data)
    {
        // 数据验证
        $rules = [
            'ctitle' => 'require|max:50',
            'pid' => 'integer|egt:0',
            'sort' => 'integer|egt:0',
            'status' => 'in:0,1'
        ];
        
        $validateResult = $this->validate($data, $rules);
        if ($validateResult !== true) {
            return $validateResult;
        }

        // 检查父级分类是否存在
        if ($data['pid'] > 0) {
            $parent = $this->model->find($data['pid']);
            if (!$parent) {
                return '父级分类不存在';
            }
        }

        // 检查同级分类名称是否重复
        $exists = $this->model->where([
            'pid' => $data['pid'],
            'ctitle' => $data['ctitle']
        ])->find();
        
        if ($exists) {
            return '同级分类中已存在相同名称';
        }

        // 设置默认值
        $data['sort'] = $data['sort'] ?? 0;
        $data['status'] = $data['status'] ?? 1;

        return $this->create($data) ? true : '创建失败';
    }

    /**
     * 更新分类
     * @param int $id 分类ID
     * @param array $data 更新数据
     * @return bool|string
     */
    public function updateCategory($id, $data)
    {
        // 数据验证
        $rules = [
            'ctitle' => 'require|max:50',
            'pid' => 'integer|egt:0',
            'sort' => 'integer|egt:0',
            'status' => 'in:0,1'
        ];
        
        $validateResult = $this->validate($data, $rules);
        if ($validateResult !== true) {
            return $validateResult;
        }

        // 检查分类是否存在
        $category = $this->model->find($id);
        if (!$category) {
            return '分类不存在';
        }

        // 不能将分类设置为自己的子分类
        if (isset($data['pid']) && $this->isChildCategory($id, $data['pid'])) {
            return '不能将分类移动到自己的子分类下';
        }

        // 检查同级分类名称是否重复
        if (isset($data['ctitle'])) {
            $exists = $this->model->where([
                'pid' => $data['pid'] ?? $category['pid'],
                'ctitle' => $data['ctitle']
            ])->where('id', '<>', $id)->find();
            
            if ($exists) {
                return '同级分类中已存在相同名称';
            }
        }

        return $this->update($id, $data) ? true : '更新失败';
    }

    /**
     * 删除分类
     * @param int $id 分类ID
     * @return bool|string
     */
    public function deleteCategory($id)
    {
        // 检查是否有子分类
        $hasChildren = $this->model->where('pid', $id)->count() > 0;
        if ($hasChildren) {
            return '该分类下还有子分类，无法删除';
        }

        // 这里可以添加检查是否有关联数据的逻辑
        // 例如：检查是否有文章使用了这个分类

        return $this->delete($id) ? true : '删除失败';
    }

    /**
     * 检查是否为子分类
     * @param int $parentId 父级ID
     * @param int $childId 子级ID
     * @return bool
     */
    private function isChildCategory($parentId, $childId)
    {
        if ($parentId == $childId) {
            return true;
        }

        $children = $this->model->where('pid', $parentId)->column('id');
        foreach ($children as $id) {
            if ($this->isChildCategory($id, $childId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取分类统计信息
     * @return array
     */
    public function getCategoryStatistics()
    {
        $total = $this->model->count();
        $enabled = $this->model->where('status', 1)->count();
        $disabled = $this->model->where('status', 0)->count();
        $topLevel = $this->model->where('pid', 0)->count();

        return [
            'total' => $total,
            'enabled' => $enabled,
            'disabled' => $disabled,
            'top_level' => $topLevel
        ];
    }

    /**
     * 批量更新排序
     * @param array $sortData 排序数据 [['id' => 1, 'sort' => 10], ...]
     * @return bool
     */
    public function batchUpdateSort($sortData)
    {
        $this->startTrans();
        try {
            foreach ($sortData as $item) {
                $this->model->where('id', $item['id'])->update(['sort' => $item['sort']]);
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return false;
        }
    }
}
