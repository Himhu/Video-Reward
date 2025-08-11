<?php
// +----------------------------------------------------------------------
// | 服务名称：财务管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理财务相关的业务逻辑
// | 主要职责：提现管理、财务统计、状态控制、审核流程等
// | 业务规则：提现审核、金额验证、状态流转、权限控制
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\Outlay;
use think\facade\Db;

/**
 * 财务管理服务
 * Class FinanceService
 * @package app\admin\service
 */
class FinanceService extends BaseService
{
    // 提现状态常量
    const STATUS_PENDING = 0;   // 待审核
    const STATUS_APPROVED = 1;  // 已批准
    const STATUS_REJECTED = 2;  // 已拒绝

    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new Outlay();
    }

    /**
     * 获取提现列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @return array
     */
    public function getOutlayList($where = [], $page = 1, $limit = 20, $order = 'create_time desc')
    {
        $result = $this->getList($where, $page, $limit, $order);
        
        // 格式化数据
        foreach ($result['list'] as &$item) {
            $item['amount_formatted'] = number_format($item['amount'], 2);
            $item['create_time_formatted'] = date('Y-m-d H:i:s', $item['create_time']);
            $item['status_text'] = $this->getStatusText($item['status']);
            $item['status_class'] = $this->getStatusClass($item['status']);
        }
        
        return $result;
    }

    /**
     * 获取待审核提现列表
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getPendingOutlays($page = 1, $limit = 20)
    {
        return $this->getOutlayList(['status' => self::STATUS_PENDING], $page, $limit);
    }

    /**
     * 获取已批准提现列表
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getApprovedOutlays($page = 1, $limit = 20)
    {
        return $this->getOutlayList(['status' => self::STATUS_APPROVED], $page, $limit, 'approve_time desc');
    }

    /**
     * 获取已拒绝提现列表
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getRejectedOutlays($page = 1, $limit = 20)
    {
        return $this->getOutlayList(['status' => self::STATUS_REJECTED], $page, $limit, 'reject_time desc');
    }

    /**
     * 批准提现
     * @param int $id 提现ID
     * @param array $options 选项 ['admin_id' => 管理员ID, 'remark' => 备注]
     * @return bool|string
     */
    public function approveOutlay($id, $options = [])
    {
        $outlay = $this->model->find($id);
        if (!$outlay) {
            return '提现记录不存在';
        }

        if ($outlay['status'] != self::STATUS_PENDING) {
            return '只能审核待审核状态的提现';
        }

        $this->startTrans();
        try {
            // 更新提现状态
            $updateData = [
                'status' => self::STATUS_APPROVED,
                'approve_time' => time(),
                'approve_admin_id' => $options['admin_id'] ?? 0,
                'approve_remark' => $options['remark'] ?? ''
            ];
            
            $this->model->where('id', $id)->update($updateData);
            
            // 这里可以添加其他业务逻辑
            // 例如：发送通知、记录日志、更新用户余额等
            
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->log('批准提现失败：' . $e->getMessage(), 'error');
            return '批准失败：' . $e->getMessage();
        }
    }

    /**
     * 拒绝提现
     * @param int $id 提现ID
     * @param array $options 选项 ['admin_id' => 管理员ID, 'reason' => 拒绝原因]
     * @return bool|string
     */
    public function rejectOutlay($id, $options = [])
    {
        if (empty($options['reason'])) {
            return '拒绝原因不能为空';
        }

        $outlay = $this->model->find($id);
        if (!$outlay) {
            return '提现记录不存在';
        }

        if ($outlay['status'] != self::STATUS_PENDING) {
            return '只能审核待审核状态的提现';
        }

        $this->startTrans();
        try {
            // 更新提现状态
            $updateData = [
                'status' => self::STATUS_REJECTED,
                'reject_time' => time(),
                'reject_admin_id' => $options['admin_id'] ?? 0,
                'reject_reason' => $options['reason']
            ];
            
            $this->model->where('id', $id)->update($updateData);
            
            // 这里可以添加其他业务逻辑
            // 例如：退还用户余额、发送通知、记录日志等
            
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->log('拒绝提现失败：' . $e->getMessage(), 'error');
            return '拒绝失败：' . $e->getMessage();
        }
    }

    /**
     * 获取财务统计数据
     * @param string $type 统计类型 outlay|all
     * @param int|null $status 状态筛选
     * @return array
     */
    public function getFinanceStatistics($type = 'outlay', $status = null)
    {
        $where = [];
        if ($status !== null) {
            $where['status'] = $status;
        }

        $baseStats = $this->getStatistics($where);
        
        // 金额统计
        $totalAmount = $this->model->where($where)->sum('amount') ?: 0;
        $todayAmount = $this->model->where($where)->whereTime('create_time', 'today')->sum('amount') ?: 0;
        $monthAmount = $this->model->where($where)->whereTime('create_time', 'month')->sum('amount') ?: 0;

        return array_merge($baseStats, [
            'total_amount' => number_format($totalAmount, 2),
            'today_amount' => number_format($todayAmount, 2),
            'month_amount' => number_format($monthAmount, 2),
            'avg_amount' => $baseStats['total'] > 0 ? number_format($totalAmount / $baseStats['total'], 2) : '0.00'
        ]);
    }

    /**
     * 获取状态文本
     * @param int $status 状态值
     * @return string
     */
    public function getStatusText($status)
    {
        $statusMap = [
            self::STATUS_PENDING => '待审核',
            self::STATUS_APPROVED => '已批准',
            self::STATUS_REJECTED => '已拒绝'
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
            self::STATUS_PENDING => 'layui-btn-warm',
            self::STATUS_APPROVED => 'layui-btn-normal',
            self::STATUS_REJECTED => 'layui-btn-danger'
        ];

        return $classMap[$status] ?? '';
    }

    /**
     * 获取状态列表
     * @return array
     */
    public function getStatusList()
    {
        return [
            self::STATUS_PENDING => '待审核',
            self::STATUS_APPROVED => '已批准',
            self::STATUS_REJECTED => '已拒绝'
        ];
    }

    /**
     * 批量审核提现
     * @param array $ids 提现ID数组
     * @param int $action 操作类型 1=批准 2=拒绝
     * @param array $options 选项
     * @return bool|string
     */
    public function batchAudit($ids, $action, $options = [])
    {
        if (empty($ids)) {
            return '请选择要操作的记录';
        }

        $this->startTrans();
        try {
            foreach ($ids as $id) {
                if ($action == 1) {
                    $result = $this->approveOutlay($id, $options);
                } else {
                    $result = $this->rejectOutlay($id, $options);
                }
                
                if ($result !== true) {
                    $this->rollback();
                    return $result;
                }
            }
            
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            return '批量操作失败：' . $e->getMessage();
        }
    }
}
