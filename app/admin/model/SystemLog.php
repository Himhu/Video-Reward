<?php
// +----------------------------------------------------------------------
// | 模型名称：系统日志模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统操作日志数据
// | 数据表：system_log_YYYYMM（按月分表）
// | 主要字段：admin_id(管理员ID)、url(请求地址)、method(请求方法)、ip(IP地址)、content(请求内容)
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemLog extends TimeModel
{

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->name = 'system_log_' . date('Ym');
    }

    public function setMonth($month)
    {
        $this->name = 'system_log_' . $month;
        return $this;
    }

    public function admin()
    {
        return $this->belongsTo('app\admin\model\SystemAdmin', 'admin_id', 'id');
    }


}