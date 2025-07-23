<?php
// +----------------------------------------------------------------------
// | 模型名称：分类模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统资源分类数据
// | 数据表：category
// | 主要字段：ctitle(分类名称)、pid(父级ID)、sort(排序)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Category extends TimeModel
{

    protected $name = "category";

    protected $deleteTime = "delete_time";

    
    

}