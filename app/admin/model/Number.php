<?php
// +----------------------------------------------------------------------
// | 模型名称：邀请码模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统邀请码数据
// | 数据表：number
// | 主要字段：uid(生成用户ID)、number(邀请码)、ua(使用用户ID)、status(状态)、activate_time(激活时间)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Number extends TimeModel
{

    protected $name = "number";

    protected $deleteTime = false;

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [

        'activate_time'  =>  'timestamp',
    ];
    
    
    public function getStatusList()
    {
        return ['0'=>'未激活','1'=>'已激活',];
    }

    public function AdminUa()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "ua");
    }

    public function Admin()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }




}