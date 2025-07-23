<?php
// +----------------------------------------------------------------------
// | Video-Reward 分类模型 (重构版本)
// +----------------------------------------------------------------------
// | 模型功能：管理系统资源分类数据
// | 数据表：category
// | 主要字段：ctitle(分类名称)、pid(父级ID)、sort(排序)、status(状态)
// +----------------------------------------------------------------------
// | 重构说明：迁移到新的模块化架构，保持原有数据结构和功能完整性
// +----------------------------------------------------------------------

namespace app\Modules\Content\Models;

use app\Base\BaseModel;

/**
 * 分类模型 (重构版本)
 *
 * 管理系统资源分类数据，完全独立的模块化实现
 */
class Category extends BaseModel
{
    /**
     * 数据表名称
     * @var string
     */
    protected $name = "category";

    /**
     * 软删除字段
     * @var string
     */
    protected $deleteTime = "delete_time";

    /**
     * 获取分类列表
     * 
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @return array
     */
    public function getList($where = [], $page = 1, $limit = 20, $order = 'sort desc,id desc')
    {
        $count = $this->where($where)->count();
        $list = $this->where($where)
            ->page($page, $limit)
            ->order($order)
            ->select();
            
        return [
            'count' => $count,
            'list' => $list
        ];
    }

    /**
     * 获取分类树形结构
     * 
     * @param int $pid 父级ID
     * @return array
     */
    public function getTree($pid = 0)
    {
        $list = $this->where('pid', $pid)
            ->where('status', 1)
            ->order('sort desc,id desc')
            ->select();
            
        $tree = [];
        foreach ($list as $item) {
            $children = $this->getTree($item['id']);
            if (!empty($children)) {
                $item['children'] = $children;
            }
            $tree[] = $item;
        }
        
        return $tree;
    }

    /**
     * 获取选择列表
     * 
     * @return array
     */
    public function getSelectList()
    {
        return $this->where('status', 1)
            ->order('sort desc,id desc')
            ->column('ctitle', 'id');
    }

    /**
     * 检查分类名称是否存在
     * 
     * @param string $ctitle 分类名称
     * @param int $excludeId 排除的ID
     * @return bool
     */
    public function checkTitleExists($ctitle, $excludeId = 0)
    {
        $where = [['ctitle', '=', $ctitle]];
        if ($excludeId > 0) {
            $where[] = ['id', '<>', $excludeId];
        }
        
        return $this->where($where)->count() > 0;
    }

    /**
     * 获取子分类数量
     * 
     * @param int $pid 父级ID
     * @return int
     */
    public function getChildrenCount($pid)
    {
        return $this->where('pid', $pid)->count();
    }

    /**
     * 批量更新排序
     * 
     * @param array $sorts 排序数据
     * @return bool
     */
    public function updateSorts($sorts)
    {
        if (empty($sorts) || !is_array($sorts)) {
            return false;
        }
        
        try {
            foreach ($sorts as $id => $sort) {
                $this->where('id', $id)->update(['sort' => $sort]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取分类路径
     * 
     * @param int $id 分类ID
     * @return array
     */
    public function getCategoryPath($id)
    {
        $path = [];
        $category = $this->find($id);
        
        while ($category) {
            array_unshift($path, $category);
            if ($category['pid'] > 0) {
                $category = $this->find($category['pid']);
            } else {
                break;
            }
        }
        
        return $path;
    }
}
