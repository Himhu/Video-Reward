<?php
// +----------------------------------------------------------------------
// | 模型名称：扣量设置模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统扣量规则数据
// | 数据表：quantity
// | 主要字段：uid(用户ID)、bottom_all(底量)、quantity(扣量比例)、creator_id(创建者)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Quantity extends TimeModel
{

    protected $name = "quantity";

    protected $deleteTime = false;

    
    
    public function getBottomAllList()
    {
        return ['0'=>'禁用','1'=>'正常',];
    }

    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }


}