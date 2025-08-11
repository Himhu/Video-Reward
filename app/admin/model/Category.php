<?php
// +----------------------------------------------------------------------
// | 模型名称：分类模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统资源分类数据
// | 数据表：category
// | 主要字段：ctitle(分类名称)、pid(父级ID)、sort(排序)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Category extends TimeModel
{
    // 注意：由于.env中设置了PREFIX=ds_，所以这里只需要写表名后缀
    protected $name = "category";

    protected $deleteTime = "delete_time";

    /**
     * 获取分类状态列表
     */
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'正常'];
    }

    /**
     * 获取启用的分类列表
     * @param int $pid 父级ID
     * @return array
     */
    public function getEnabledCategories($pid = 0)
    {
        return $this->where(['status' => 1, 'pid' => $pid])
            ->order('sort asc, id asc')
            ->select()
            ->toArray();
    }

    /**
     * 根据名称查找分类
     * @param string $title 分类名称
     * @param int $pid 父级ID
     * @return array|null
     */
    public function findByTitle($title, $pid = 0)
    {
        $result = $this->where(['ctitle' => $title, 'pid' => $pid])->find();
        return $result ? $result->toArray() : null;
    }

    /**
     * 获取子分类数量
     * @param int $id 分类ID
     * @return int
     */
    public function getChildrenCount($id)
    {
        return $this->where('pid', $id)->count();
    }

    /**
     * 检查分类是否可以删除
     * @param int $id 分类ID
     * @return bool
     */
    public function canDelete($id)
    {
        // 检查是否有子分类
        if ($this->getChildrenCount($id) > 0) {
            return false;
        }

        // 这里可以添加其他业务规则检查
        // 例如：检查是否有关联的内容

        return true;
    }

    /**
     * 获取分类路径（面包屑）
     * @param int $id 分类ID
     * @return array
     */
    public function getCategoryPath($id)
    {
        $path = [];
        $category = $this->find($id);

        while ($category) {
            array_unshift($path, [
                'id' => $category['id'],
                'title' => $category['ctitle']
            ]);

            if ($category['pid'] > 0) {
                $category = $this->find($category['pid']);
            } else {
                break;
            }
        }

        return $path;
    }

}