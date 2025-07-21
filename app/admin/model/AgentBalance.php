<?php

// +----------------------------------------------------------------------
// | 代理余额模型 - 新架构版本
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建代理余额模型 - 系统重构
// | 功能说明：管理代理用户的财务信息，分离余额与用户基础信息
// | 新架构：独立的余额表，支持精确的财务管理和审计
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Exception;
use think\facade\Db;

/**
 * 代理余额模型
 * 
 * 管理代理用户的财务信息
 * 包括可用余额、冻结余额、总收入等
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class AgentBalance extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'agent_balances';

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
        'available_balance' => 'float',
        'frozen_balance' => 'float',
        'total_income' => 'float',
        'total_withdraw' => 'float',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

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
     * 获取总余额
     * 
     * @return float 总余额
     */
    public function getTotalBalance()
    {
        return $this->available_balance + $this->frozen_balance;
    }

    /**
     * 增加余额
     * 
     * @param float $amount 金额
     * @param string $memo 备注
     * @param string $type 类型
     * @return bool 操作结果
     * @throws Exception
     */
    public function addBalance($amount, $memo = '', $type = 'income')
    {
        if ($amount <= 0) {
            throw new Exception('金额必须大于0');
        }

        Db::startTrans();
        try {
            // 更新余额
            $this->available_balance += $amount;
            $this->total_income += $amount;
            
            if (!$this->save()) {
                throw new Exception('余额更新失败');
            }

            // 记录日志
            $this->addBalanceLog($amount, $memo, $type, $this->available_balance - $amount, $this->available_balance);

            Db::commit();
            return true;

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 扣减余额
     * 
     * @param float $amount 金额
     * @param string $memo 备注
     * @param string $type 类型
     * @return bool 操作结果
     * @throws Exception
     */
    public function deductBalance($amount, $memo = '', $type = 'withdraw')
    {
        if ($amount <= 0) {
            throw new Exception('金额必须大于0');
        }

        if ($this->available_balance < $amount) {
            throw new Exception('余额不足');
        }

        Db::startTrans();
        try {
            $beforeBalance = $this->available_balance;
            
            // 更新余额
            $this->available_balance -= $amount;
            if ($type === 'withdraw') {
                $this->total_withdraw += $amount;
            }
            
            if (!$this->save()) {
                throw new Exception('余额更新失败');
            }

            // 记录日志
            $this->addBalanceLog(-$amount, $memo, $type, $beforeBalance, $this->available_balance);

            Db::commit();
            return true;

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 冻结余额
     * 
     * @param float $amount 金额
     * @param string $memo 备注
     * @return bool 操作结果
     * @throws Exception
     */
    public function freezeBalance($amount, $memo = '')
    {
        if ($amount <= 0) {
            throw new Exception('金额必须大于0');
        }

        if ($this->available_balance < $amount) {
            throw new Exception('可用余额不足');
        }

        Db::startTrans();
        try {
            $beforeAvailable = $this->available_balance;
            $beforeFrozen = $this->frozen_balance;
            
            // 转移余额
            $this->available_balance -= $amount;
            $this->frozen_balance += $amount;
            
            if (!$this->save()) {
                throw new Exception('余额冻结失败');
            }

            // 记录日志
            $this->addBalanceLog(-$amount, $memo, 'freeze', $beforeAvailable, $this->available_balance);

            Db::commit();
            return true;

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 解冻余额
     * 
     * @param float $amount 金额
     * @param string $memo 备注
     * @return bool 操作结果
     * @throws Exception
     */
    public function unfreezeBalance($amount, $memo = '')
    {
        if ($amount <= 0) {
            throw new Exception('金额必须大于0');
        }

        if ($this->frozen_balance < $amount) {
            throw new Exception('冻结余额不足');
        }

        Db::startTrans();
        try {
            $beforeAvailable = $this->available_balance;
            $beforeFrozen = $this->frozen_balance;
            
            // 转移余额
            $this->frozen_balance -= $amount;
            $this->available_balance += $amount;
            
            if (!$this->save()) {
                throw new Exception('余额解冻失败');
            }

            // 记录日志
            $this->addBalanceLog($amount, $memo, 'unfreeze', $beforeAvailable, $this->available_balance);

            Db::commit();
            return true;

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 添加余额日志
     * 
     * @param float $amount 变动金额
     * @param string $memo 备注
     * @param string $type 类型
     * @param float $beforeBalance 变动前余额
     * @param float $afterBalance 变动后余额
     * @return bool 添加结果
     */
    protected function addBalanceLog($amount, $memo, $type, $beforeBalance, $afterBalance)
    {
        try {
            $balanceLog = new BalanceLog();
            return $balanceLog->save([
                'agent_id' => $this->agent_id,
                'amount' => $amount,
                'before_balance' => $beforeBalance,
                'after_balance' => $afterBalance,
                'type' => $type,
                'memo' => $memo,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            $this->logError('添加余额日志失败', $e->getMessage());
            return false;
        }
    }

    /**
     * 获取余额统计信息
     * 
     * @return array 统计信息
     */
    public function getBalanceStats()
    {
        return [
            'available_balance' => $this->available_balance,
            'frozen_balance' => $this->frozen_balance,
            'total_balance' => $this->getTotalBalance(),
            'total_income' => $this->total_income,
            'total_withdraw' => $this->total_withdraw,
            'net_income' => $this->total_income - $this->total_withdraw,
        ];
    }
}
