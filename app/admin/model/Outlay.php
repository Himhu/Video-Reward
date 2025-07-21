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

        'end_time'  =>  'timestamp',
        'refuse_time'  =>  'timestamp',
    ];
    
    public function getStatusList()
    {
        return ['1'=>'结算','2'=>'拒绝',];
    }

    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }


}