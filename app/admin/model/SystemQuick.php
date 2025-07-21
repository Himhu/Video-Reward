<?php
// +----------------------------------------------------------------------
// | 模型名称：系统快捷入口模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统首页快捷入口数据
// | 数据表：system_quick
// | 主要字段：title(标题)、icon(图标)、href(链接)、sort(排序)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemQuick extends TimeModel
{

    protected $deleteTime = 'delete_time';

}