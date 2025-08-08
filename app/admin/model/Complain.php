<?php
// +----------------------------------------------------------------------
// | 模型名称：投诉管理模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统用户投诉数据
// | 数据表：complain
// | 主要字段：title(标题)、content(内容)、contact(联系方式)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Complain extends TimeModel
{

    protected $name = "complain";

    protected $deleteTime = false;

    
    
    /**
     * 获取投诉类型列表
     */
    public function getTypeList()
    {
        return [
            '1' => '新冠肺炎疫情相关',
            '2' => '欺诈',
            '3' => '色情',
            '4' => '诱导行为',
            '5' => '不实信息',
            '6' => '犯法犯罪',
            '7' => '骚扰',
            '8' => '抄袭/洗稿、滥用原创',
            '9' => '其它',
            '10' => '侵权(冒充他人、侵犯名誉等)',
            '0' => '未知'
        ];
    }

    /**
     * 获取处理状态列表
     */
    public function getStatusList()
    {
        return [
            '0' => '待处理',
            '1' => '已处理'
        ];
    }

    /**
     * 关联用户表
     */
    public function user()
    {
        return $this->hasOne(SystemAdmin::class, 'id', 'uid');
    }

    /**
     * 关联视频表
     */
    public function video()
    {
        return $this->hasOne(Link::class, 'id', 'vid');
    }
}