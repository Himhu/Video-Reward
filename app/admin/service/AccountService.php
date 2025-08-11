<?php
// +----------------------------------------------------------------------
// | 服务名称：账户流水管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理用户账户流水相关的业务逻辑
// | 主要职责：账户流水查询、统计、权限控制等
// | 业务规则：管理员可查看所有流水，普通用户只能查看自己的流水
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\UserMoneyLog;
use app\admin\model\SystemAdmin;
use think\facade\Db;

/**
 * 账户流水管理服务
 * Class AccountService
 * @package app\admin\service
 */
class AccountService extends BaseService
{
    /**
     * SystemAdmin模型（用于用户信息）
     * @var SystemAdmin
     */
    protected $adminModel;

    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new UserMoneyLog();
        $this->adminModel = new SystemAdmin();
    }

    /**
     * 获取账户流水列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @param string $type 类型 all|personal
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function getAccountList($where = [], $page = 1, $limit = 20, $order = 'create_time desc', $type = 'all', $currentUid = 1)
    {
        // 权限控制：根据类型和用户权限决定查询范围
        $query = $this->model->where($where);
        
        if ($type === 'personal' || ($type === 'all' && $currentUid != 1)) {
            // 个人模式或非超级管理员只能查看自己的流水
            $query = $query->where('uid', $currentUid);
        }
        
        // 获取总数
        $count = $query->count();
        
        // 获取列表数据
        $list = $query->with(['Admins'])
            ->page($page, $limit)
            ->order($order)
            ->select()
            ->toArray();
        
        // 格式化数据
        foreach ($list as &$item) {
            $item['type_text'] = $this->getTypeText($item['type']);
            $item['type_class'] = $this->getTypeClass($item['type']);
            $item['money_formatted'] = $this->formatMoney($item['money']);
            $item['before_formatted'] = $this->formatMoney($item['before']);
            $item['after_formatted'] = $this->formatMoney($item['after']);
            $item['create_time_formatted'] = date('Y-m-d H:i:s', strtotime($item['create_time']));
            
            // 用户信息
            if (isset($item['admins'])) {
                $item['username'] = $item['admins']['username'] ?? '-';
            } else {
                $item['username'] = '-';
            }
        }
        
        return [
            'count' => $count,
            'list' => $list
        ];
    }

    /**
     * 获取账户流水统计
     * @param int|null $uid 用户ID，null表示全部
     * @param string $type 类型 all|personal
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function getAccountStatistics($uid = null, $type = 'all', $currentUid = 1)
    {
        $where = [];
        
        // 权限控制
        if ($type === 'personal' || ($type === 'all' && $currentUid != 1)) {
            $where['uid'] = $currentUid;
        } elseif ($uid) {
            $where['uid'] = $uid;
        }

        // 基础统计
        $totalRecords = $this->model->where($where)->count();
        $todayRecords = $this->model->where($where)->whereTime('create_time', 'today')->count();
        
        // 收入统计
        $totalIncome = $this->model->where($where)->where('type', 1)->sum('money') ?: 0;
        $todayIncome = $this->model->where($where)->where('type', 1)->whereTime('create_time', 'today')->sum('money') ?: 0;
        
        // 支出统计
        $totalExpense = $this->model->where($where)->where('type', 2)->sum('money') ?: 0;
        $todayExpense = $this->model->where($where)->where('type', 2)->whereTime('create_time', 'today')->sum('money') ?: 0;

        return [
            'total_records' => $totalRecords,
            'today_records' => $todayRecords,
            'total_income' => number_format($totalIncome, 2),
            'today_income' => number_format($todayIncome, 2),
            'total_expense' => number_format($totalExpense, 2),
            'today_expense' => number_format($todayExpense, 2),
            'net_amount' => number_format($totalIncome - $totalExpense, 2),
            'today_net' => number_format($todayIncome - $todayExpense, 2)
        ];
    }

    /**
     * 获取用户的账户流水
     * @param int $uid 用户ID
     * @param int $limit 数量限制
     * @return array
     */
    public function getUserAccountRecords($uid, $limit = 10)
    {
        return $this->model->where('uid', $uid)
            ->with(['Admins'])
            ->order('create_time desc')
            ->limit($limit)
            ->select()
            ->toArray();
    }

    /**
     * 检查用户是否有权限查看指定用户的流水
     * @param int $currentUid 当前用户ID
     * @param int $targetUid 目标用户ID
     * @return bool
     */
    public function canViewUserAccount($currentUid, $targetUid)
    {
        // 超级管理员可以查看所有用户
        if ($currentUid == 1) {
            return true;
        }
        
        // 普通用户只能查看自己的
        return $currentUid == $targetUid;
    }

    /**
     * 获取类型文本
     * @param int $type 类型值
     * @return string
     */
    public function getTypeText($type)
    {
        $typeMap = [
            1 => '收入',
            2 => '支出'
        ];

        return $typeMap[$type] ?? '未知类型';
    }

    /**
     * 获取类型样式类
     * @param int $type 类型值
     * @return string
     */
    public function getTypeClass($type)
    {
        $classMap = [
            1 => 'layui-btn-normal',  // 收入 - 绿色
            2 => 'layui-btn-danger'   // 支出 - 红色
        ];

        return $classMap[$type] ?? '';
    }

    /**
     * 格式化金额
     * @param float $money 金额
     * @return string
     */
    private function formatMoney($money)
    {
        return number_format($money, 2);
    }

    /**
     * 获取最近的流水记录
     * @param int $currentUid 当前用户ID
     * @param string $type 类型
     * @param int $limit 数量限制
     * @return array
     */
    public function getRecentRecords($currentUid, $type = 'all', $limit = 10)
    {
        $where = [];
        
        // 权限控制
        if ($type === 'personal' || $currentUid != 1) {
            $where['uid'] = $currentUid;
        }

        return $this->model->where($where)
            ->with(['Admins'])
            ->order('create_time desc')
            ->limit($limit)
            ->select()
            ->toArray();
    }

    /**
     * 导出账户流水数据
     * @param array $where 查询条件
     * @param string $type 类型
     * @param int $currentUid 当前用户ID
     * @return array
     */
    public function exportAccountData($where = [], $type = 'all', $currentUid = 1)
    {
        $query = $this->model->where($where);
        
        // 权限控制
        if ($type === 'personal' || ($type === 'all' && $currentUid != 1)) {
            $query = $query->where('uid', $currentUid);
        }
        
        return $query->with(['Admins'])
            ->order('create_time desc')
            ->select()
            ->toArray();
    }
}
