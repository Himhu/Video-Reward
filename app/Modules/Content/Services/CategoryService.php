<?php
// +----------------------------------------------------------------------
// | Video-Reward 分类服务层 (重构版本)
// +----------------------------------------------------------------------
// | 服务功能：处理分类相关的业务逻辑
// | 主要职责：数据验证、业务规则处理、数据操作封装
// +----------------------------------------------------------------------
// | 重构说明：新增的服务层，封装业务逻辑，提供标准化的服务接口
// +----------------------------------------------------------------------

namespace app\Modules\Content\Services;

use app\Modules\Content\Models\Category;
use think\exception\ValidateException;

/**
 * 分类服务层
 * 
 * 处理分类相关的业务逻辑，提供标准化的服务接口
 */
class CategoryService
{
    /**
     * 分类模型实例
     * @var Category
     */
    protected $categoryModel;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->categoryModel = new Category();
    }

    /**
     * 创建分类
     * 
     * @param array $data 分类数据
     * @return bool
     * @throws ValidateException
     */
    public function create($data)
    {
        // 数据验证
        $this->validateCategoryData($data);
        
        // 检查分类名称是否存在
        if ($this->categoryModel->checkTitleExists($data['ctitle'])) {
            throw new ValidateException('分类名称已存在');
        }
        
        // 设置默认值
        $data['create_time'] = time();
        $data['update_time'] = time();
        $data['status'] = $data['status'] ?? 1;
        $data['sort'] = $data['sort'] ?? 0;
        $data['pid'] = $data['pid'] ?? 0;
        
        try {
            return $this->categoryModel->save($data);
        } catch (\Exception $e) {
            throw new ValidateException('创建分类失败：' . $e->getMessage());
        }
    }

    /**
     * 更新分类
     * 
     * @param int $id 分类ID
     * @param array $data 分类数据
     * @return bool
     * @throws ValidateException
     */
    public function update($id, $data)
    {
        if (!$id || $id <= 0) {
            throw new ValidateException('分类ID无效');
        }
        
        // 检查分类是否存在
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new ValidateException('分类不存在');
        }
        
        // 数据验证
        $this->validateCategoryData($data, $id);
        
        // 检查分类名称是否存在（排除当前分类）
        if (isset($data['ctitle']) && $this->categoryModel->checkTitleExists($data['ctitle'], $id)) {
            throw new ValidateException('分类名称已存在');
        }
        
        // 检查父级分类循环引用
        if (isset($data['pid']) && $data['pid'] > 0) {
            if ($this->checkCircularReference($id, $data['pid'])) {
                throw new ValidateException('不能将分类设置为自己的子分类');
            }
        }
        
        $data['update_time'] = time();
        
        try {
            return $this->categoryModel->where('id', $id)->update($data);
        } catch (\Exception $e) {
            throw new ValidateException('更新分类失败：' . $e->getMessage());
        }
    }

    /**
     * 删除分类
     * 
     * @param int $id 分类ID
     * @return bool
     * @throws ValidateException
     */
    public function delete($id)
    {
        if (!$id || $id <= 0) {
            throw new ValidateException('分类ID无效');
        }
        
        // 检查分类是否存在
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new ValidateException('分类不存在');
        }
        
        // 检查是否有子分类
        if ($this->categoryModel->getChildrenCount($id) > 0) {
            throw new ValidateException('该分类下还有子分类，无法删除');
        }
        
        try {
            return $this->categoryModel->destroy($id);
        } catch (\Exception $e) {
            throw new ValidateException('删除分类失败：' . $e->getMessage());
        }
    }

    /**
     * 查找分类
     * 
     * @param int $id 分类ID
     * @return array|null
     */
    public function find($id)
    {
        if (!$id || $id <= 0) {
            return null;
        }
        
        return $this->categoryModel->find($id);
    }

    /**
     * 修改分类属性
     * 
     * @param array $data 修改数据
     * @return bool
     * @throws ValidateException
     */
    public function modify($data)
    {
        if (!isset($data['id']) || !isset($data['field']) || !isset($data['value'])) {
            throw new ValidateException('参数不完整');
        }
        
        $id = intval($data['id']);
        $field = trim($data['field']);
        $value = $data['value'];
        
        // 检查分类是否存在
        $category = $this->categoryModel->find($id);
        if (!$category) {
            throw new ValidateException('分类不存在');
        }
        
        // 验证字段
        $allowFields = ['status', 'sort', 'ctitle'];
        if (!in_array($field, $allowFields)) {
            throw new ValidateException('不允许修改该字段');
        }
        
        try {
            return $this->categoryModel->where('id', $id)->update([
                $field => $value,
                'update_time' => time()
            ]);
        } catch (\Exception $e) {
            throw new ValidateException('修改失败：' . $e->getMessage());
        }
    }

    /**
     * 导出分类数据
     * 
     * @return array
     */
    public function export()
    {
        $list = $this->categoryModel->order('sort desc,id desc')->select();
        
        $exportData = [];
        foreach ($list as $item) {
            $exportData[] = [
                'ID' => $item['id'],
                '分类名称' => $item['ctitle'],
                '父级ID' => $item['pid'],
                '排序' => $item['sort'],
                '状态' => $item['status'] == 1 ? '启用' : '禁用',
                '创建时间' => date('Y-m-d H:i:s', $item['create_time']),
                '更新时间' => date('Y-m-d H:i:s', $item['update_time'])
            ];
        }
        
        return $exportData;
    }

    /**
     * 获取选择列表
     * 
     * @return array
     */
    public function getSelectList()
    {
        return $this->categoryModel->getSelectList();
    }

    /**
     * 验证分类数据 - 公开方法供控制器调用
     *
     * @param array $data 分类数据
     * @param int $excludeId 排除的ID
     * @throws ValidateException
     */
    public function validateCategoryData($data, $excludeId = 0)
    {
        // 验证分类名称
        if (empty($data['ctitle'])) {
            throw new ValidateException('分类名称不能为空');
        }
        
        if (mb_strlen($data['ctitle']) > 50) {
            throw new ValidateException('分类名称不能超过50个字符');
        }
        
        // 验证排序
        if (isset($data['sort']) && (!is_numeric($data['sort']) || $data['sort'] < 0)) {
            throw new ValidateException('排序必须为非负整数');
        }
        
        // 验证状态
        if (isset($data['status']) && !in_array($data['status'], [0, 1])) {
            throw new ValidateException('状态值无效');
        }
        
        // 验证父级ID
        if (isset($data['pid']) && $data['pid'] > 0) {
            $parent = $this->categoryModel->find($data['pid']);
            if (!$parent) {
                throw new ValidateException('父级分类不存在');
            }
        }
    }

    /**
     * 检查循环引用
     * 
     * @param int $id 当前分类ID
     * @param int $pid 父级分类ID
     * @return bool
     */
    protected function checkCircularReference($id, $pid)
    {
        if ($id == $pid) {
            return true;
        }
        
        $parent = $this->categoryModel->find($pid);
        if ($parent && $parent['pid'] > 0) {
            return $this->checkCircularReference($id, $parent['pid']);
        }
        
        return false;
    }
}
