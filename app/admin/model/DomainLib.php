<?php
// +----------------------------------------------------------------------
// | 模型名称：中转域名模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统中转域名数据
// | 数据表：domain_lib
// | 主要字段：uid(用户ID)、domain(域名)、status(状态)、q_status(是否启用)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class DomainLib extends TimeModel
{

    protected $name = "domain_lib";

    protected $deleteTime = false;



    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }

    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'正常',];
    }


}