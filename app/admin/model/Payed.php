<?php
// +----------------------------------------------------------------------
// | 模型名称：已支付订单模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统已支付订单展示数据
// | 数据表：payed_show
// | 主要字段：uid(用户ID)、transact(订单号)、pay_channel(支付渠道)、amount(金额)、is_tj(是否统计)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Payed extends TimeModel
{

    protected $name = "payed_show";

    protected $deleteTime = false;



}