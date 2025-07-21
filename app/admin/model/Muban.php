<?php
// +----------------------------------------------------------------------
// | 模型名称：模板模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统模板数据
// | 数据表：muban
// | 主要字段：title(标题)、image(图片)、content(内容)、uid(用户ID)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Muban extends TimeModel
{

    protected $name = "muban";

    protected $deleteTime = "delete_time";

    
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'正常',];
    }

    public function Admins()
    {
        // 用户资料表的uid 对应代理表的主键id - 适配新权限系统
        return $this->hasOne(Agent::class, 'id' , "uid");
    }

}