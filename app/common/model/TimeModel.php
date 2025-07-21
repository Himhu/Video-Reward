<?php

// +----------------------------------------------------------------------
// | 时间模型基类 - 重构版本
// +----------------------------------------------------------------------
// | 最后修改：2025-01-21 - 重构适配新数据库架构 - 系统重构
// | 重构内容：适配timestamp时间字段、统一时间字段命名、优化软删除机制
// | 新架构：支持created_at/updated_at字段、timestamp类型、现代化时间处理
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

/**
 * 时间模型基类
 *
 * 提供统一的时间字段管理和软删除功能
 * 适配新数据库架构的timestamp字段类型
 *
 * @package app\common\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class TimeModel extends Model
{
    /**
     * 自动写入时间戳
     * 设置为timestamp类型以适配新数据库架构
     * @var string
     */
    protected $autoWriteTimestamp = 'timestamp';

    /**
     * 创建时间字段
     * 新架构统一使用created_at字段名
     * @var string
     */
    protected $createTime = 'created_at';

    /**
     * 更新时间字段
     * 新架构统一使用updated_at字段名
     * @var string
     */
    protected $updateTime = 'updated_at';

    /**
     * 时间字段格式
     * 使用标准的MySQL timestamp格式
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 软删除功能
     * 使用deleted_at字段实现软删除
     */
    use SoftDelete;

    /**
     * 软删除时间字段
     * 新架构统一使用deleted_at字段名
     * @var string
     */
    protected $deleteTime = 'deleted_at';

    /**
     * 数据表字段类型定义
     * 明确定义时间字段的数据类型
     * @var array
     */
    protected $type = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    /**
     * 获取格式化的创建时间
     *
     * @param mixed $value 原始时间值
     * @param string $format 时间格式
     * @return string 格式化后的时间字符串
     */
    public function getCreatedAtAttr($value, $format = 'Y-m-d H:i:s')
    {
        return $value ? date($format, strtotime($value)) : '';
    }

    /**
     * 获取格式化的更新时间
     *
     * @param mixed $value 原始时间值
     * @param string $format 时间格式
     * @return string 格式化后的时间字符串
     */
    public function getUpdatedAtAttr($value, $format = 'Y-m-d H:i:s')
    {
        return $value ? date($format, strtotime($value)) : '';
    }

    /**
     * 获取格式化的删除时间
     *
     * @param mixed $value 原始时间值
     * @param string $format 时间格式
     * @return string 格式化后的时间字符串
     */
    public function getDeletedAtAttr($value, $format = 'Y-m-d H:i:s')
    {
        return $value ? date($format, strtotime($value)) : '';
    }

    /**
     * 检查记录是否为今天创建
     *
     * @return bool 是否为今天创建
     */
    public function isCreatedToday()
    {
        return $this->created_at && date('Y-m-d', strtotime($this->created_at)) === date('Y-m-d');
    }

    /**
     * 检查记录是否在指定天数内创建
     *
     * @param int $days 天数
     * @return bool 是否在指定天数内创建
     */
    public function isCreatedWithinDays($days = 7)
    {
        if (!$this->created_at) {
            return false;
        }

        $createdTime = strtotime($this->created_at);
        $limitTime = time() - ($days * 24 * 60 * 60);

        return $createdTime >= $limitTime;
    }
}