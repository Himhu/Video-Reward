<?php
// +----------------------------------------------------------------------
// | 服务名称：邀请码管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理邀请码相关的业务逻辑
// | 主要职责：邀请码生成、激活、统计、下级管理等
// | 业务规则：邀请码唯一性、激活状态管理、代理商下级统计
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\Number;
use app\admin\model\PayOrder;
use think\facade\Db;

/**
 * 邀请码管理服务
 * Class NumberService
 * @package app\admin\service
 */
class NumberService extends BaseService
{
    /**
     * PayOrder模型（用于下级统计）
     * @var PayOrder
     */
    protected $payOrderModel;

    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new Number();
        $this->payOrderModel = new PayOrder();
    }

    /**
     * 获取邀请码列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @param string $type 类型 all|subordinate
     * @return array
     */
    public function getNumberList($where = [], $page = 1, $limit = 20, $order = 'id desc', $type = 'all')
    {
        // 如果是下级管理，添加用户过滤
        if ($type === 'subordinate' && isset($where['current_uid'])) {
            $currentUid = $where['current_uid'];
            unset($where['current_uid']);
            $where['uid'] = $currentUid;
        }

        // 使用模型关联查询，保持与原控制器一致
        $count = $this->model->where($where)->count();
        $list = $this->model
            ->where($where)
            ->page($page, $limit)
            ->with(['AdminUa', 'Admin'])  // 添加原有的模型关联
            ->order($order)
            ->select()
            ->toArray();

        $result = [
            'count' => $count,
            'list' => $list
        ];

        // 格式化数据
        foreach ($result['list'] as &$item) {
            $item['status_text'] = $this->getStatusText($item['status']);
            $item['status_class'] = $this->getStatusClass($item['status']);

            // 安全的时间格式化
            $item['create_time_formatted'] = $this->formatTime($item['create_time']);
            $item['activate_time_formatted'] = $this->formatTime($item['activate_time']);

            // 如果是下级管理，添加订单统计
            if ($type === 'subordinate' && !empty($item['ua'])) {
                $item['order_stats'] = $this->getUserOrderStats($item['ua']);
            }
        }

        return $result;
    }

    /**
     * 获取用户的订单统计
     * @param int $uid 用户ID
     * @return array
     */
    public function getUserOrderStats($uid)
    {
        $stats = [
            'total_orders' => 0,
            'total_amount' => '0.00',
            'today_orders' => 0,
            'today_amount' => '0.00'
        ];

        if (!$uid) {
            return $stats;
        }

        // 总订单统计
        $stats['total_orders'] = $this->payOrderModel->where('uid', $uid)->count();
        $stats['total_amount'] = number_format($this->payOrderModel->where('uid', $uid)->sum('money') ?: 0, 2);
        
        // 今日订单统计
        $stats['today_orders'] = $this->payOrderModel->where('uid', $uid)->whereTime('create_time', 'today')->count();
        $stats['today_amount'] = number_format($this->payOrderModel->where('uid', $uid)->whereTime('create_time', 'today')->sum('money') ?: 0, 2);

        return $stats;
    }

    /**
     * 生成邀请码
     * @param int $uid 生成用户ID
     * @param int $count 生成数量
     * @return bool|string
     */
    public function generateNumbers($uid, $count = 1)
    {
        if ($count <= 0 || $count > 100) {
            return '生成数量必须在1-100之间';
        }

        $this->startTrans();
        try {
            $numbers = [];
            for ($i = 0; $i < $count; $i++) {
                $number = $this->generateUniqueNumber();
                $data = [
                    'uid' => $uid,
                    'number' => $number,
                    'status' => 0,
                    'create_time' => time()
                ];
                
                $result = $this->create($data);
                if (!$result) {
                    $this->rollback();
                    return '生成邀请码失败';
                }
                $numbers[] = $number;
            }
            
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->log('生成邀请码失败：' . $e->getMessage(), 'error');
            return '生成失败：' . $e->getMessage();
        }
    }

    /**
     * 激活邀请码
     * @param string $number 邀请码
     * @param int $activateUid 激活用户ID
     * @return bool|string
     */
    public function activateNumber($number, $activateUid)
    {
        $numberRecord = $this->model->where('number', $number)->find();
        if (!$numberRecord) {
            return '邀请码不存在';
        }

        if ($numberRecord['status'] == 1) {
            return '邀请码已被激活';
        }

        $updateData = [
            'ua' => $activateUid,
            'status' => 1,
            'activate_time' => time()
        ];

        $result = $this->update($numberRecord['id'], $updateData);
        return $result ? true : '激活失败';
    }

    /**
     * 获取邀请码统计
     * @param int|null $uid 用户ID，null表示全部
     * @param string $type 类型 all|subordinate
     * @return array
     */
    public function getNumberStatistics($uid = null, $type = 'all')
    {
        $where = [];
        if ($uid && $type === 'subordinate') {
            $where['uid'] = $uid;
        }

        $total = $this->model->where($where)->count();
        $activated = $this->model->where($where)->where('status', 1)->count();
        $unactivated = $this->model->where($where)->where('status', 0)->count();
        $todayGenerated = $this->model->where($where)->whereTime('create_time', 'today')->count();
        $todayActivated = $this->model->where($where)->where('status', 1)->whereTime('activate_time', 'today')->count();

        $stats = [
            'total' => $total,
            'activated' => $activated,
            'unactivated' => $unactivated,
            'today_generated' => $todayGenerated,
            'today_activated' => $todayActivated,
            'activation_rate' => $total > 0 ? round(($activated / $total) * 100, 2) : 0
        ];

        // 如果是下级管理，添加订单统计
        if ($type === 'subordinate' && $uid) {
            $subordinateUids = $this->model->where('uid', $uid)->where('status', 1)->column('ua');
            if ($subordinateUids) {
                $orderStats = [
                    'total_orders' => $this->payOrderModel->whereIn('uid', $subordinateUids)->count(),
                    'total_amount' => number_format($this->payOrderModel->whereIn('uid', $subordinateUids)->sum('money') ?: 0, 2),
                    'today_orders' => $this->payOrderModel->whereIn('uid', $subordinateUids)->whereTime('create_time', 'today')->count(),
                    'today_amount' => number_format($this->payOrderModel->whereIn('uid', $subordinateUids)->whereTime('create_time', 'today')->sum('money') ?: 0, 2)
                ];
                $stats = array_merge($stats, $orderStats);
            }
        }

        return $stats;
    }

    /**
     * 获取状态文本
     * @param int $status 状态值
     * @return string
     */
    public function getStatusText($status)
    {
        $statusMap = [
            0 => '未激活',
            1 => '已激活'
        ];

        return $statusMap[$status] ?? '未知状态';
    }

    /**
     * 获取状态样式类
     * @param int $status 状态值
     * @return string
     */
    public function getStatusClass($status)
    {
        $classMap = [
            0 => 'layui-btn-warm',
            1 => 'layui-btn-normal'
        ];

        return $classMap[$status] ?? '';
    }

    /**
     * 生成唯一邀请码
     * @return string
     */
    private function generateUniqueNumber()
    {
        do {
            $number = $this->generateRandomNumber();
            $exists = $this->model->where('number', $number)->find();
        } while ($exists);

        return $number;
    }

    /**
     * 生成随机邀请码
     * @return string
     */
    private function generateRandomNumber()
    {
        // 生成8位数字+字母的邀请码
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $number = '';
        for ($i = 0; $i < 8; $i++) {
            $number .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $number;
    }

    /**
     * 安全的时间格式化
     * @param mixed $timestamp 时间戳
     * @return string
     */
    private function formatTime($timestamp)
    {
        // 如果是NULL或空值，返回"-"
        if (is_null($timestamp) || $timestamp === '' || $timestamp === 0) {
            return '-';
        }

        // 如果是字符串格式的时间，直接返回
        if (is_string($timestamp) && strtotime($timestamp) !== false) {
            return $timestamp;
        }

        // 检查时间戳是否为有效数值
        if (!is_numeric($timestamp) || $timestamp <= 0) {
            return '-';
        }

        // 确保时间戳是整数
        $timestamp = (int)$timestamp;

        // 检查时间戳是否在合理范围内（1970年到2100年）
        if ($timestamp < 946684800 || $timestamp > 4102444800) {
            return '-';
        }

        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * 批量删除邀请码
     * @param array $ids 邀请码ID数组
     * @param bool $onlyUnactivated 是否只删除未激活的
     * @return bool|string
     */
    public function batchDeleteNumbers($ids, $onlyUnactivated = true)
    {
        if (empty($ids)) {
            return '请选择要删除的记录';
        }

        if ($onlyUnactivated) {
            $activatedCount = $this->model->whereIn('id', $ids)->where('status', 1)->count();
            if ($activatedCount > 0) {
                return '不能删除已激活的邀请码';
            }
        }

        return $this->batchDelete($ids) ? true : '删除失败';
    }
}
