<?php
// +----------------------------------------------------------------------
// | 模型名称：落地域名模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统落地域名数据
// | 数据表：domain_rule
// | 主要字段：uid(用户ID)、domain(域名)、type(类型)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class DomainRule extends TimeModel
{

    protected $name = "domain_rule";

    protected $deleteTime = false;

    public function Admins()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , 'uid');
    }
    
    public function getStatusList()
    {
        return ['0'=>'禁用','1'=>'正常',];
    }

    public function getTypeList()
    {
        return ['1'=>'主域名','2'=>'炮灰域名',];
    }


}