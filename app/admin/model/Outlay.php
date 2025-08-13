<?php
// +----------------------------------------------------------------------
// | 模型名称：提现记录模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统提现申请数据
// | 数据表：outlay
// | 主要字段：uid(用户ID)、money(提现金额)、account(收款账号)、status(状态)、remark(备注)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Outlay extends TimeModel
{

    protected $name = "outlay";

    protected $deleteTime = false;

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [
        'money' => 'float',
        'status' => 'integer',
        'create_time' => 'timestamp',
        // 注释掉可能为NULL的时间戳字段，避免自动转换导致错误
        // 'end_time'  =>  'timestamp',
        // 'refuse_time'  =>  'timestamp',
    ];
    
    // 状态常量
    const STATUS_PENDING = 0;   // 待审核/未结算
    const STATUS_APPROVED = 1;  // 已结算
    const STATUS_REJECTED = 2;  // 已拒绝

    public function getStatusList()
    {
        return [
            self::STATUS_PENDING => '待审核',
            self::STATUS_APPROVED => '已结算',
            self::STATUS_REJECTED => '已拒绝'
        ];
    }

    /**
     * 获取状态文本
     * @param int $status 状态值
     * @return string
     */
    public function getStatusText($status)
    {
        $statusList = $this->getStatusList();
        return $statusList[$status] ?? '未知状态';
    }

    /**
     * 获取待审核列表
     * @param array $where 额外查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getPendingList($where = [], $page = 1, $limit = 20)
    {
        $where['status'] = self::STATUS_PENDING;
        return $this->where($where)
            ->page($page, $limit)
            ->order('create_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取已结算列表
     * @param array $where 额外查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getApprovedList($where = [], $page = 1, $limit = 20)
    {
        $where['status'] = self::STATUS_APPROVED;
        return $this->where($where)
            ->page($page, $limit)
            ->order('end_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取已拒绝列表
     * @param array $where 额外查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getRejectedList($where = [], $page = 1, $limit = 20)
    {
        $where['status'] = self::STATUS_REJECTED;
        return $this->where($where)
            ->page($page, $limit)
            ->order('refuse_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取用户的提现列表
     * @param int $uid 用户ID
     * @param array $where 额外查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array
     */
    public function getUserOutlayList($uid, $where = [], $page = 1, $limit = 20)
    {
        $where['uid'] = $uid;
        return $this->where($where)
            ->page($page, $limit)
            ->order('create_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取统计数据
     * @param int|null $status 状态筛选
     * @return array
     */
    public function getStatistics($status = null)
    {
        $where = [];
        if ($status !== null) {
            $where['status'] = $status;
        }

        $total = $this->where($where)->count();
        $totalAmount = $this->where($where)->sum('money') ?: 0;
        $todayCount = $this->where($where)->whereTime('create_time', 'today')->count();
        $todayAmount = $this->where($where)->whereTime('create_time', 'today')->sum('money') ?: 0;

        return [
            'total_count' => $total,
            'total_amount' => number_format($totalAmount, 2),
            'today_count' => $todayCount,
            'today_amount' => number_format($todayAmount, 2),
            'avg_amount' => $total > 0 ? number_format($totalAmount / $total, 2) : '0.00'
        ];
    }

    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }


}