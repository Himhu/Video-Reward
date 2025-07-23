<?php
// +----------------------------------------------------------------------
// | 模型名称：积分扣减模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统用户积分扣减记录
// | 数据表：point_decr
// | 主要字段：uid(用户ID)、point(扣减积分)、reason(扣减原因)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class PointDecr extends TimeModel
{

    protected $name = "point_decr";

    protected $deleteTime = false;



}