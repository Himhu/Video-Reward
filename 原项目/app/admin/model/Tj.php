<?php
// +----------------------------------------------------------------------
// | 模型名称：统计数据模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统数据统计记录
// | 数据表：tj
// | 主要字段：date(日期)、new_users(新增用户)、orders(订单数)、amount(金额)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Tj extends TimeModel
{

    protected $name = "tj";

    protected $deleteTime = false;

    
    

}