<?php

// +----------------------------------------------------------------------
// | 余额日志模型 - 新架构版本
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建余额日志模型 - 系统重构
// | 功能说明：记录代理用户的余额变动日志，提供完整的财务审计
// | 新架构：独立的日志表，支持详细的余额变动追踪
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;

/**
 * 余额日志模型
 * 
 * 记录代理用户的所有余额变动
 * 提供完整的财务审计和追踪功能
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class BalanceLog extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'balance_logs';

    /**
     * 主键字段
     * @var string
     */
    protected $pk = 'id';

    /**
     * 字段类型定义
     * @var array
     */
    protected $type = [
        'id' => 'integer',
        'agent_id' => 'integer',
        'amount' => 'float',
        'before_balance' => 'float',
        'after_balance' => 'float',
        'type' => 'string',
        'memo' => 'string',
        'created_at' => 'timestamp',
    ];

    /**
     * 只读字段
     * @var array
     */
    protected $readonly = [
        'id',
        'agent_id',
        'amount',
        'before_balance',
        'after_balance',
        'type',
        'memo',
        'created_at',
    ];

    /**
     * 日志类型常量
     */
    const TYPE_INCOME = 'income';       // 收入
    const TYPE_WITHDRAW = 'withdraw';   // 提现
    const TYPE_FREEZE = 'freeze';       // 冻结
    const TYPE_UNFREEZE = 'unfreeze';   // 解冻
    const TYPE_REFUND = 'refund';       // 退款
    const TYPE_DEDUCT = 'deduct';       // 扣减
    const TYPE_BONUS = 'bonus';         // 奖励
    const TYPE_PENALTY = 'penalty';     // 罚金

    /**
     * 获取日志类型列表
     * 
     * @return array 日志类型列表
     */
    public static function getTypeList()
    {
        return [
            self::TYPE_INCOME => '收入',
            self::TYPE_WITHDRAW => '提现',
            self::TYPE_FREEZE => '冻结',
            self::TYPE_UNFREEZE => '解冻',
            self::TYPE_REFUND => '退款',
            self::TYPE_DEDUCT => '扣减',
            self::TYPE_BONUS => '奖励',
            self::TYPE_PENALTY => '罚金',
        ];
    }

    /**
     * 关联代理用户模型
     * 
     * @return \think\model\relation\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    /**
     * 日志类型获取器
     * 
     * @param string $value 原始值
     * @return string 类型文本
     */
    public function getTypeTextAttr($value)
    {
        $typeList = self::getTypeList();
        return $typeList[$this->type] ?? '未知';
    }

    /**
     * 金额获取器
     * 格式化显示金额
     * 
     * @param float $value 原始值
     * @return string 格式化金额
     */
    public function getAmountTextAttr($value)
    {
        $prefix = $this->amount >= 0 ? '+' : '';
        return $prefix . number_format($this->amount, 2);
    }

    /**
     * 获取指定代理的余额日志
     * 
     * @param int $agentId 代理ID
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @return array 分页数据
     */
    public static function getAgentLogs($agentId, $where = [], $page = 1, $limit = 15)
    {
        $query = self::where('agent_id', $agentId);
        
        // 添加查询条件
        if (!empty($where['type'])) {
            $query->where('type', $where['type']);
        }
        
        if (!empty($where['start_date'])) {
            $query->where('created_at', '>=', $where['start_date']);
        }
        
        if (!empty($where['end_date'])) {
            $query->where('created_at', '<=', $where['end_date']);
        }
        
        if (!empty($where['memo'])) {
            $query->where('memo', 'like', '%' . $where['memo'] . '%');
        }
        
        // 执行分页查询
        $result = $query->order('id desc')
                       ->paginate([
                           'list_rows' => $limit,
                           'page' => $page,
                       ]);
        
        return [
            'data' => $result->items(),
            'total' => $result->total(),
            'page' => $page,
            'limit' => $limit,
            'pages' => $result->lastPage(),
        ];
    }

    /**
     * 获取指定代理的余额统计
     * 
     * @param int $agentId 代理ID
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @return array 统计数据
     */
    public static function getAgentStats($agentId, $startDate = '', $endDate = '')
    {
        $query = self::where('agent_id', $agentId);
        
        if (!empty($startDate)) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if (!empty($endDate)) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $logs = $query->select();
        
        $stats = [
            'total_income' => 0,
            'total_withdraw' => 0,
            'total_freeze' => 0,
            'total_unfreeze' => 0,
            'total_refund' => 0,
            'total_deduct' => 0,
            'total_bonus' => 0,
            'total_penalty' => 0,
            'net_change' => 0,
        ];
        
        foreach ($logs as $log) {
            switch ($log->type) {
                case self::TYPE_INCOME:
                    $stats['total_income'] += $log->amount;
                    break;
                case self::TYPE_WITHDRAW:
                    $stats['total_withdraw'] += abs($log->amount);
                    break;
                case self::TYPE_FREEZE:
                    $stats['total_freeze'] += abs($log->amount);
                    break;
                case self::TYPE_UNFREEZE:
                    $stats['total_unfreeze'] += $log->amount;
                    break;
                case self::TYPE_REFUND:
                    $stats['total_refund'] += $log->amount;
                    break;
                case self::TYPE_DEDUCT:
                    $stats['total_deduct'] += abs($log->amount);
                    break;
                case self::TYPE_BONUS:
                    $stats['total_bonus'] += $log->amount;
                    break;
                case self::TYPE_PENALTY:
                    $stats['total_penalty'] += abs($log->amount);
                    break;
            }
            
            $stats['net_change'] += $log->amount;
        }
        
        return $stats;
    }
}
