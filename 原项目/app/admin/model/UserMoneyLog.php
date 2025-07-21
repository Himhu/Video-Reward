<?php
// +----------------------------------------------------------------------
// | 模型名称：用户资金流水模型
// +----------------------------------------------------------------------
// | 模型功能：管理用户资金变动记录
// | 数据表：user_money_log
// | 主要字段：uid(用户ID)、money(金额)、type(类型)、remark(备注)、before(变动前)、after(变动后)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class UserMoneyLog extends TimeModel
{

    protected $name = "user_money_log";

    protected $deleteTime = false;


    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }

}