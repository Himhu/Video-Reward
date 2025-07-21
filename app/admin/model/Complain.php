<?php
// +----------------------------------------------------------------------
// | 模型名称：投诉管理模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统用户投诉数据
// | 数据表：complain
// | 主要字段：title(标题)、content(内容)、contact(联系方式)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Complain extends TimeModel
{

    protected $name = "complain";

    protected $deleteTime = false;

    
    
    public function getStatusList()
    {
        return ['0'=>'禁止访问','1'=>'正常',];
    }


}