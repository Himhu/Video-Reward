<?php

// +----------------------------------------------------------------------
// | 配置名称：时间模型基类
// +----------------------------------------------------------------------
// | 配置功能：提供时间相关的模型基础功能
// | 主要配置：自动时间戳、创建和更新时间字段、软删除等
// | 当前配置：支持自动记录时间和软删除功能
// +----------------------------------------------------------------------


namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

/**
 * 有关时间的模型
 * Class TimeModel
 * @package app\common\model
 */
class TimeModel extends Model
{

    /**
     * 自动时间戳类型
     * @var string
     */
    protected $autoWriteTimestamp = true;

    /**
     * 添加时间
     * @var string
     */
    protected $createTime = 'create_time';

    /**
     * 更新时间
     * @var string
     */
    protected $updateTime = 'update_time';

    /**
     * 软删除
     */
    use SoftDelete;
    protected $deleteTime = false;

}