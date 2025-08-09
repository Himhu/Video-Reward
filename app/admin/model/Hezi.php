<?php
// +----------------------------------------------------------------------
// | 模型名称：推广盒子模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统推广链接数据
// | 数据表：hezi
// | 主要字段：uid(用户ID)、title(标题)、url(链接)、short_url(短链接)、view_id(模板ID)、type(类型)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Hezi extends TimeModel
{

    protected $name = "hezi";

    protected $deleteTime = false;


    public function view()
    {
        return $this->hasOne(Muban::class, 'id' , 'view_id');
    }
    

}