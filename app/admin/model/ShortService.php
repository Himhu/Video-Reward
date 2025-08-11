<?php
// +----------------------------------------------------------------------
// | 模型名称：短地址服务模型
// +----------------------------------------------------------------------
// | 模型功能：短地址服务数据管理
// | 主要字段：service_code(服务代码)、service_name(服务名称)、api_key(API密钥)等
// | 主要职责：提供短地址服务的数据操作接口
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class ShortService extends TimeModel
{
    // 设置当前模型对应的数据表名称（不包含前缀）
    protected $name = 'short_service';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取默认服务
     */
    public static function getDefaultService()
    {
        return self::where('is_default', 1)->find();
    }

    /**
     * 根据服务代码获取服务配置
     */
    public static function getServiceByCode($code)
    {
        return self::where('service_code', $code)->find();
    }

    /**
     * 获取启用的服务列表
     */
    public static function getEnabledServices()
    {
        return self::where('is_enabled', 1)->order('sort_order asc')->select();
    }

    /**
     * 获取服务选项数组（用于下拉选择）
     */
    public static function getServiceOptions()
    {
        $services = self::getEnabledServices();
        $options = [];
        
        foreach ($services as $service) {
            $options[$service['service_code']] = $service['service_name'];
        }
        
        return $options;
    }

    /**
     * 状态获取器
     */
    public function getIsEnabledTextAttr($value, $data)
    {
        return $data['is_enabled'] ? '启用' : '禁用';
    }

    /**
     * 默认状态获取器
     */
    public function getIsDefaultTextAttr($value, $data)
    {
        return $data['is_default'] ? '是' : '否';
    }
}
