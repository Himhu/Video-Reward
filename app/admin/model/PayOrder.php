<?php
// +----------------------------------------------------------------------
// | 模型名称：支付订单模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统支付订单数据
// | 数据表：pay_order
// | 主要字段：uid(用户ID)、vid(视频ID)、transact(订单号)、pay_channel(支付渠道)、amount(金额)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class PayOrder extends TimeModel
{

    protected $name = "pay_order";

    protected $deleteTime = false;


    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [
        'createtime'  =>  'timestamp',
        'paytime'     =>  'timestamp',
    ];

    public static function getOrderInfo($id)
    {
        return self::where(['transact' => $id])->find()->toArray();
    }

    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }


    public function Link()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(Link::class, 'id' , "vid");
    }
    

}