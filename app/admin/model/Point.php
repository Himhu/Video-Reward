<?php
// +----------------------------------------------------------------------
// | 模型名称：用户积分模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统用户积分数据
// | 数据表：user_point
// | 主要字段：uid(用户ID)、point(积分)、total_point(总积分)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Point extends TimeModel
{

    protected $name = "user_point";

    protected $deleteTime = false;



}