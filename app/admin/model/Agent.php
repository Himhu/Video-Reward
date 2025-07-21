<?php

// +----------------------------------------------------------------------
// | 代理用户模型 - 新架构版本
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 重构SystemAdmin为Agent模型 - 系统重构
// | 功能说明：管理代理用户核心信息，适配新的分离式表结构设计
// | 新架构：分离用户信息、配置、余额到不同表，支持设备指纹识别
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Exception;

/**
 * 代理用户模型
 * 
 * 管理代理用户的核心信息，包括认证、角色、状态等
 * 采用分离式设计，用户信息、配置、余额分表管理
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class Agent extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'agents';

    /**
     * 主键字段
     * @var string
     */
    protected $pk = 'id';

    /**
     * 软删除字段
     * @var string
     */
    protected $deleteTime = 'deleted_at';

    /**
     * 字段类型定义
     * @var array
     */
    protected $type = [
        'id' => 'integer',
        'username' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'password_hash' => 'string',
        'device_fingerprint' => 'string',
        'role_type' => 'string',
        'status' => 'integer',
        'last_login_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'password_hash',
        'device_fingerprint',
    ];

    /**
     * 只读字段
     * @var array
     */
    protected $readonly = [
        'id',
        'created_at',
    ];

    /**
     * 角色类型常量
     */
    const ROLE_SUPER_ADMIN = 'super_admin';  // 超级管理员
    const ROLE_AGENT = 'agent';              // 代理用户

    /**
     * 状态常量
     */
    const STATUS_DISABLED = 0;  // 禁用
    const STATUS_ACTIVE = 1;    // 正常
    const STATUS_LOCKED = 2;    // 锁定

    /**
     * 获取角色类型列表
     * 
     * @return array 角色类型列表
     */
    public static function getRoleTypeList()
    {
        return [
            self::ROLE_SUPER_ADMIN => '超级管理员',
            self::ROLE_AGENT => '代理用户',
        ];
    }

    /**
     * 获取状态列表
     * 
     * @return array 状态列表
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_DISABLED => '禁用',
            self::STATUS_ACTIVE => '正常',
            self::STATUS_LOCKED => '锁定',
        ];
    }

    /**
     * 关联代理配置模型
     * 一对一关联
     * 
     * @return \think\model\relation\HasOne
     */
    public function config()
    {
        return $this->hasOne(AgentConfig::class, 'agent_id', 'id');
    }

    /**
     * 关联代理余额模型
     * 一对一关联
     * 
     * @return \think\model\relation\HasOne
     */
    public function balance()
    {
        return $this->hasOne(AgentBalance::class, 'agent_id', 'id');
    }

    /**
     * 关联余额日志模型
     * 一对多关联
     * 
     * @return \think\model\relation\HasMany
     */
    public function balanceLogs()
    {
        return $this->hasMany(BalanceLog::class, 'agent_id', 'id');
    }

    /**
     * 关联代理角色模型
     * 多对多关联
     * 
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, AgentRole::class, 'role_id', 'agent_id');
    }

    /**
     * 关联支付订单模型
     * 一对多关联
     * 
     * @return \think\model\relation\HasMany
     */
    public function paymentOrders()
    {
        return $this->hasMany(PaymentOrder::class, 'agent_id', 'id');
    }

    /**
     * 密码加密器
     * 
     * @param string $value 原始密码
     * @return string 加密后的密码
     */
    public function setPasswordHashAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }

    /**
     * 角色类型获取器
     * 
     * @param string $value 原始值
     * @return string 角色类型文本
     */
    public function getRoleTypeTextAttr($value)
    {
        $roleTypes = self::getRoleTypeList();
        return $roleTypes[$this->role_type] ?? '未知';
    }

    /**
     * 状态获取器
     * 
     * @param int $value 原始值
     * @return string 状态文本
     */
    public function getStatusTextAttr($value)
    {
        $statusList = self::getStatusList();
        return $statusList[$this->status] ?? '未知';
    }

    /**
     * 最后登录时间获取器
     * 
     * @param string $value 原始值
     * @return string 格式化的时间
     */
    public function getLastLoginAtAttr($value)
    {
        return $value ? date('Y-m-d H:i:s', strtotime($value)) : '从未登录';
    }

    /**
     * 验证密码
     * 
     * @param string $password 待验证的密码
     * @return bool 验证结果
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password_hash);
    }

    /**
     * 检查是否为超级管理员
     * 
     * @return bool 是否为超级管理员
     */
    public function isSuperAdmin()
    {
        return $this->role_type === self::ROLE_SUPER_ADMIN;
    }

    /**
     * 检查是否为代理用户
     * 
     * @return bool 是否为代理用户
     */
    public function isAgent()
    {
        return $this->role_type === self::ROLE_AGENT;
    }

    /**
     * 检查账户是否可用
     * 
     * @return bool 账户是否可用
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * 更新最后登录时间
     * 
     * @return bool 更新结果
     */
    public function updateLastLogin()
    {
        try {
            return $this->save([
                'last_login_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            $this->logError('更新最后登录时间失败', $e->getMessage());
            return false;
        }
    }

    /**
     * 根据用户名查找代理
     * 
     * @param string $username 用户名
     * @return Agent|null 代理对象
     */
    public static function findByUsername($username)
    {
        return self::where('username', $username)
                   ->where('status', self::STATUS_ACTIVE)
                   ->find();
    }

    /**
     * 根据设备指纹查找代理
     * 
     * @param string $fingerprint 设备指纹
     * @return Agent|null 代理对象
     */
    public static function findByFingerprint($fingerprint)
    {
        return self::where('device_fingerprint', $fingerprint)
                   ->where('status', self::STATUS_ACTIVE)
                   ->find();
    }

    /**
     * 获取代理的完整信息
     * 包含配置和余额信息
     * 
     * @return array 完整信息
     */
    public function getFullInfo()
    {
        $data = $this->toArray();
        
        // 加载关联数据
        $data['config'] = $this->config ? $this->config->toArray() : [];
        $data['balance'] = $this->balance ? $this->balance->toArray() : [];
        $data['roles'] = $this->roles ? $this->roles->toArray() : [];
        
        return $data;
    }

    /**
     * 数据验证
     * 
     * @param array $data 要验证的数据
     * @return bool 验证结果
     * @throws Exception
     */
    protected function validateData(array $data)
    {
        // 用户名验证
        if (isset($data['username'])) {
            if (empty($data['username'])) {
                throw new Exception('用户名不能为空');
            }
            
            if (strlen($data['username']) < 3 || strlen($data['username']) > 50) {
                throw new Exception('用户名长度必须在3-50个字符之间');
            }
            
            // 检查用户名是否已存在
            $exists = self::where('username', $data['username'])
                         ->where('id', '<>', $this->id ?? 0)
                         ->find();
            if ($exists) {
                throw new Exception('用户名已存在');
            }
        }
        
        // 邮箱验证
        if (isset($data['email']) && !empty($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('邮箱格式不正确');
            }
        }
        
        // 手机号验证
        if (isset($data['phone']) && !empty($data['phone'])) {
            if (!preg_match('/^1[3-9]\d{9}$/', $data['phone'])) {
                throw new Exception('手机号格式不正确');
            }
        }
        
        return true;
    }
}
