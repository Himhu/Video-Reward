<?php

// +----------------------------------------------------------------------
// | 代理配置模型 - 新架构版本
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建代理配置模型 - 系统重构
// | 功能说明：管理代理用户的业务配置信息，分离配置与用户基础信息
// | 新架构：独立的配置表，支持灵活的业务配置管理
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;

/**
 * 代理配置模型
 * 
 * 管理代理用户的业务配置信息
 * 包括扣量设置、域名配置、模板设置等
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class AgentConfig extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'agent_configs';

    /**
     * 主键字段
     * @var string
     */
    protected $pk = 'id';

    /**
     * 字段类型定义
     * @var array
     */
    protected $type = [
        'id' => 'integer',
        'agent_id' => 'integer',
        'template_id' => 'integer',
        'domain_config' => 'json',
        'deduction_rate' => 'float',
        'min_amount' => 'float',
        'notification_settings' => 'json',
        'api_settings' => 'json',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * JSON字段
     * @var array
     */
    protected $json = [
        'domain_config',
        'notification_settings', 
        'api_settings',
    ];

    /**
     * 关联代理用户模型
     * 
     * @return \think\model\relation\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    /**
     * 获取域名配置
     * 
     * @return array 域名配置
     */
    public function getDomainConfig()
    {
        return $this->domain_config ?: [];
    }

    /**
     * 设置域名配置
     * 
     * @param array $config 域名配置
     * @return bool 设置结果
     */
    public function setDomainConfig(array $config)
    {
        return $this->save(['domain_config' => $config]);
    }

    /**
     * 获取通知设置
     * 
     * @return array 通知设置
     */
    public function getNotificationSettings()
    {
        return $this->notification_settings ?: [];
    }

    /**
     * 设置通知设置
     * 
     * @param array $settings 通知设置
     * @return bool 设置结果
     */
    public function setNotificationSettings(array $settings)
    {
        return $this->save(['notification_settings' => $settings]);
    }

    /**
     * 获取API设置
     * 
     * @return array API设置
     */
    public function getApiSettings()
    {
        return $this->api_settings ?: [];
    }

    /**
     * 设置API设置
     * 
     * @param array $settings API设置
     * @return bool 设置结果
     */
    public function setApiSettings(array $settings)
    {
        return $this->save(['api_settings' => $settings]);
    }
}
