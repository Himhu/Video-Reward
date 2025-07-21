<?php
// +----------------------------------------------------------------------
// | 模型名称：积分日志模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统用户积分变动日志
// | 数据表：point_logs
// | 主要字段：uid(用户ID)、point(积分变动)、type(变动类型)、remark(备注)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class PointLog extends TimeModel
{

    protected $name = "point_logs";

    protected $deleteTime = false;



}