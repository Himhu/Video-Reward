<?php
// +----------------------------------------------------------------------
// | 服务名称：代理片库管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理代理视频资源库相关的业务逻辑
// | 主要职责：代理视频管理、权限控制、资源分配等
// | 业务规则：代理专属视频资源的统一管理
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\Link;
use app\admin\model\Category;
use app\admin\model\SystemAdmin;
use think\facade\Db;

/**
 * 代理片库管理服务
 * Class LinkService
 * @package app\admin\service
 */
class LinkService extends BaseService
{
    /**
     * Category模型
     * @var Category
     */
    protected $categoryModel;

    /**
     * SystemAdmin模型
     * @var SystemAdmin
     */
    protected $adminModel;

    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new Link();
        $this->categoryModel = new Category();
        $this->adminModel = new SystemAdmin();
    }

    /**
     * 获取代理片库列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function getLinkList($where = [], $page = 1, $limit = 20, $order = 'create_time desc', $currentUid = 1)
    {
        // 权限控制：非超级管理员只能查看自己的片库
        if ($currentUid != 1) {
            $where['uid'] = $currentUid;
        }

        $count = $this->model->where($where)->count();
        $list = $this->model->where($where)
            ->with(['Category', 'Admin'])
            ->page($page, $limit)
            ->order($order)
            ->select()
            ->toArray();

        // 格式化数据
        foreach ($list as &$item) {
            $item['create_time_formatted'] = date('Y-m-d H:i:s', strtotime($item['create_time']));
            $item['category_name'] = $item['category']['name'] ?? '未分类';
            $item['admin_name'] = $item['admin']['username'] ?? '未知';
            $item['status_text'] = $this->getStatusText($item['status']);
            $item['mianfei_text'] = $this->getMianfeiText($item['mianfei']);
        }

        return [
            'count' => $count,
            'list' => $list
        ];
    }

    /**
     * 创建代理片库记录
     * @param array $data 片库数据
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function createLink($data, $currentUid)
    {
        // 验证必要字段
        if (empty($data['title']) || empty($data['url'])) {
            return ['success' => false, 'message' => '标题和链接不能为空'];
        }

        // 设置创建者
        $data['uid'] = $currentUid;
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['status'] = $data['status'] ?? 1;
        $data['mianfei'] = $data['mianfei'] ?? 0;

        if ($this->model->save($data)) {
            return [
                'success' => true,
                'message' => '创建成功',
                'data' => ['id' => $this->model->id]
            ];
        }

        return ['success' => false, 'message' => '创建失败'];
    }

    /**
     * 更新代理片库记录
     * @param int $id 记录ID
     * @param array $data 更新数据
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function updateLink($id, $data, $currentUid)
    {
        $link = $this->model->find($id);
        if (!$link) {
            return ['success' => false, 'message' => '记录不存在'];
        }

        // 权限检查：非超级管理员只能修改自己的记录
        if ($currentUid != 1 && $link['uid'] != $currentUid) {
            return ['success' => false, 'message' => '无权限修改此记录'];
        }

        $data['update_time'] = date('Y-m-d H:i:s');

        if ($link->save($data)) {
            return ['success' => true, 'message' => '更新成功'];
        }

        return ['success' => false, 'message' => '更新失败'];
    }

    /**
     * 删除代理片库记录
     * @param int $id 记录ID
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function deleteLink($id, $currentUid)
    {
        $link = $this->model->find($id);
        if (!$link) {
            return ['success' => false, 'message' => '记录不存在'];
        }

        // 权限检查：非超级管理员只能删除自己的记录
        if ($currentUid != 1 && $link['uid'] != $currentUid) {
            return ['success' => false, 'message' => '无权限删除此记录'];
        }

        if ($this->delete($id)) {
            return ['success' => true, 'message' => '删除成功'];
        }

        return ['success' => false, 'message' => '删除失败'];
    }

    /**
     * 批量删除代理片库记录
     * @param array $ids 记录ID数组
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function batchDeleteLinks($ids, $currentUid)
    {
        if (empty($ids)) {
            return ['success' => false, 'message' => '请选择要删除的记录'];
        }

        // 权限检查：非超级管理员只能删除自己的记录
        $where = ['id' => ['in', $ids]];
        if ($currentUid != 1) {
            $where['uid'] = $currentUid;
        }

        $count = $this->model->where($where)->delete();

        if ($count > 0) {
            return ['success' => true, 'message' => "成功删除{$count}条记录"];
        }

        return ['success' => false, 'message' => '删除失败或无权限'];
    }

    /**
     * 获取代理片库统计
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function getLinkStatistics($currentUid)
    {
        $where = [];
        if ($currentUid != 1) {
            $where['uid'] = $currentUid;
        }

        $total = $this->model->where($where)->count();
        $active = $this->model->where($where)->where('status', 1)->count();
        $inactive = $this->model->where($where)->where('status', 0)->count();
        $free = $this->model->where($where)->where('mianfei', 1)->count();
        $paid = $this->model->where($where)->where('mianfei', 0)->count();
        $todayCount = $this->model->where($where)->whereTime('create_time', 'today')->count();

        // 分类统计
        $categories = $this->categoryModel->column('name', 'id');
        $categoryStats = [];
        foreach ($categories as $id => $name) {
            $categoryWhere = array_merge($where, ['cid' => $id]);
            $categoryStats[] = [
                'category_name' => $name,
                'count' => $this->model->where($categoryWhere)->count()
            ];
        }

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'free' => $free,
            'paid' => $paid,
            'today_count' => $todayCount,
            'category_stats' => $categoryStats
        ];
    }

    /**
     * 批量更新状态
     * @param array $ids 记录ID数组
     * @param int $status 状态
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function batchUpdateStatus($ids, $status, $currentUid)
    {
        if (empty($ids)) {
            return ['success' => false, 'message' => '请选择要操作的记录'];
        }

        // 权限检查
        $where = ['id' => ['in', $ids]];
        if ($currentUid != 1) {
            $where['uid'] = $currentUid;
        }

        $count = $this->model->where($where)->update([
            'status' => $status,
            'update_time' => date('Y-m-d H:i:s')
        ]);

        if ($count > 0) {
            return ['success' => true, 'message' => "成功更新{$count}条记录"];
        }

        return ['success' => false, 'message' => '更新失败或无权限'];
    }

    /**
     * 获取状态文本
     * @param int $status 状态值
     * @return string
     */
    public function getStatusText($status)
    {
        $statusMap = [
            0 => '禁用',
            1 => '启用'
        ];

        return $statusMap[$status] ?? '未知状态';
    }

    /**
     * 获取免费状态文本
     * @param int $mianfei 免费状态值
     * @return string
     */
    public function getMianfeiText($mianfei)
    {
        $mianfeiMap = [
            0 => '付费',
            1 => '免费'
        ];

        return $mianfeiMap[$mianfei] ?? '未知';
    }

    /**
     * 获取用户的片库列表
     * @param int $uid 用户ID
     * @param int $limit 数量限制
     * @return array
     */
    public function getUserLinks($uid, $limit = 10)
    {
        return $this->model->where('uid', $uid)
            ->with(['Category'])
            ->order('create_time desc')
            ->limit($limit)
            ->select()
            ->toArray();
    }

    /**
     * 复制片库记录
     * @param int $id 源记录ID
     * @param int $targetUid 目标用户ID
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function copyLink($id, $targetUid, $currentUid)
    {
        $link = $this->model->find($id);
        if (!$link) {
            return ['success' => false, 'message' => '源记录不存在'];
        }

        // 权限检查：非超级管理员只能复制自己的记录
        if ($currentUid != 1 && $link['uid'] != $currentUid) {
            return ['success' => false, 'message' => '无权限复制此记录'];
        }

        $newData = $link->toArray();
        unset($newData['id']);
        $newData['uid'] = $targetUid;
        $newData['create_time'] = date('Y-m-d H:i:s');
        $newData['update_time'] = null;

        if ($this->model->save($newData)) {
            return ['success' => true, 'message' => '复制成功'];
        }

        return ['success' => false, 'message' => '复制失败'];
    }
}
