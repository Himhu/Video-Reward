<?php
// +----------------------------------------------------------------------
// | 模型名称：公共视频库模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统公共视频资源数据
// | 数据表：stock
// | 主要字段：cid(分类ID)、title(标题)、url(视频地址)、image(封面图片)、time(视频时长)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Stock extends TimeModel
{
    // 注意：由于.env中设置了PREFIX=ds_，所以这里只需要写表名后缀
    protected $name = "stock";

    protected $deleteTime = "delete_time";

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [
        'create_time'  =>  'timestamp',
    ];

    public function Category()
    {
        return $this->hasOne(Category::class, 'id' , 'cid');
    }

    public function Links()
    {
        return $this->hasOne(Link::class, 'stock_id' , 'id');
    }



}