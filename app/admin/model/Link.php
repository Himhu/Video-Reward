<?php
// +----------------------------------------------------------------------
// | 模型名称：代理视频链接模型
// +----------------------------------------------------------------------
// | 模型功能：管理代理的视频资源数据
// | 数据表：link
// | 主要字段：uid(用户ID)、cid(分类ID)、title(标题)、url(视频地址)、image(封面图片)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Link extends TimeModel
{

    protected $name = "link";

    protected $deleteTime = false;


    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [
        'create_time'  =>  'timestamp',
    ];
    
    
    public function getMianfeiList()
    {
        return ['0'=>'不免费','1'=>'免费',];
    }

    public function getStatusList()
    {
        return ['1'=>'启用','2'=>'禁用',];
    }


    public function Category()
    {
        return $this->hasOne(Category::class, 'id' , 'cid');
    }

    public function Admins()
    {
        return $this->hasOne(SystemAdmin::class, 'id' , 'uid');
    }

}