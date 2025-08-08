<?php
// +----------------------------------------------------------------------
// | 模型名称：通知公告模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统通知和公告数据
// | 数据表：notify
// | 主要字段：title(标题)、content(内容)、type(类型)、is_show(是否显示)、creator_id(创建者)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Notify extends TimeModel
{

    protected $name = "notify";

    protected $deleteTime = false;

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [

        'create_time'  =>  'timestamp',
    ];
    
    
    public function getTypeList()
    {
        return ['1'=>'通知','2'=>'公告',];
    }

    public function getIsShowList()
    {
        return ['0'=>'禁用','1'=>'启用',];
    }

    /**
     * 关联创建者
     */
    public function creator()
    {
        return $this->hasOne(SystemAdmin::class, 'id', 'creator_id');
    }
}