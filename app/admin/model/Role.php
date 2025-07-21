<?php

// +----------------------------------------------------------------------
// | 角色模型 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建角色管理模型 - 权限系统重构
// | 功能说明：管理系统角色信息，支持角色权限分配和验证
// | 新架构：基于RBAC模型，支持角色权限分离和灵活的权限管理
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Exception;

/**
 * 角色模型
 * 
 * 管理系统角色信息，包括角色定义、权限分配、状态管理等
 * 基于RBAC模型设计，支持灵活的权限管理机制
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class Role extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'roles';

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
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'is_system' => 'integer',
        'status' => 'integer',
        'sort' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    /**
     * 只读字段
     * @var array
     */
    protected $readonly = [
        'id',
        'slug',
        'created_at',
    ];

    /**
     * 状态常量
     */
    const STATUS_DISABLED = 0;  // 禁用
    const STATUS_ACTIVE = 1;    // 启用

    /**
     * 系统角色常量
     */
    const SYSTEM_ROLE = 1;      // 系统角色
    const CUSTOM_ROLE = 0;      // 自定义角色

    /**
     * 获取状态列表
     * 
     * @return array 状态列表
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_DISABLED => '禁用',
            self::STATUS_ACTIVE => '启用',
        ];
    }

    /**
     * 关联权限模型
     * 多对多关联
     * 
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, RolePermission::class, 'permission_id', 'role_id');
    }

    /**
     * 关联代理用户模型
     * 多对多关联
     * 
     * @return \think\model\relation\BelongsToMany
     */
    public function agents()
    {
        return $this->belongsToMany(Agent::class, AgentRole::class, 'agent_id', 'role_id');
    }

    /**
     * 关联角色权限模型
     * 一对多关联
     * 
     * @return \think\model\relation\HasMany
     */
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id', 'id');
    }

    /**
     * 关联代理角色模型
     * 一对多关联
     * 
     * @return \think\model\relation\HasMany
     */
    public function agentRoles()
    {
        return $this->hasMany(AgentRole::class, 'role_id', 'id');
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
     * 系统角色获取器
     * 
     * @param int $value 原始值
     * @return string 角色类型文本
     */
    public function getIsSystemTextAttr($value)
    {
        return $this->is_system ? '系统角色' : '自定义角色';
    }

    /**
     * 检查角色是否可用
     * 
     * @return bool 角色是否可用
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * 检查是否为系统角色
     * 
     * @return bool 是否为系统角色
     */
    public function isSystemRole()
    {
        return $this->is_system === self::SYSTEM_ROLE;
    }

    /**
     * 获取角色的所有权限ID
     * 
     * @return array 权限ID数组
     */
    public function getPermissionIds()
    {
        return $this->rolePermissions()->column('permission_id');
    }

    /**
     * 获取角色的所有权限
     * 
     * @return array 权限数组
     */
    public function getRolePermissions()
    {
        return $this->permissions()->where('status', Permission::STATUS_ACTIVE)->select()->toArray();
    }

    /**
     * 检查角色是否拥有指定权限
     * 
     * @param string|int $permission 权限标识或ID
     * @return bool 是否拥有权限
     */
    public function hasPermission($permission)
    {
        $query = $this->permissions()->where('status', Permission::STATUS_ACTIVE);
        
        if (is_numeric($permission)) {
            $query->where('id', $permission);
        } else {
            $query->where('slug', $permission);
        }
        
        return $query->count() > 0;
    }

    /**
     * 检查角色是否拥有指定模块的权限
     * 
     * @param string $module 模块名称
     * @return bool 是否拥有模块权限
     */
    public function hasModulePermission($module)
    {
        return $this->permissions()
                   ->where('module', $module)
                   ->where('status', Permission::STATUS_ACTIVE)
                   ->count() > 0;
    }

    /**
     * 为角色分配权限
     * 
     * @param array $permissionIds 权限ID数组
     * @return bool 分配结果
     * @throws Exception
     */
    public function assignPermissions(array $permissionIds)
    {
        if (empty($permissionIds)) {
            return $this->clearPermissions();
        }

        try {
            // 验证权限ID的有效性
            $validPermissions = Permission::where('id', 'in', $permissionIds)
                                         ->where('status', Permission::STATUS_ACTIVE)
                                         ->column('id');
            
            if (count($validPermissions) !== count($permissionIds)) {
                throw new Exception('包含无效的权限ID');
            }

            // 清除现有权限
            $this->clearPermissions();

            // 分配新权限
            $rolePermissions = [];
            foreach ($permissionIds as $permissionId) {
                $rolePermissions[] = [
                    'role_id' => $this->id,
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }

            return (new RolePermission())->insertAll($rolePermissions);

        } catch (Exception $e) {
            $this->logError('角色权限分配失败', $e->getMessage(), $permissionIds);
            throw $e;
        }
    }

    /**
     * 清除角色的所有权限
     * 
     * @return bool 清除结果
     */
    public function clearPermissions()
    {
        try {
            return RolePermission::where('role_id', $this->id)->delete();
        } catch (Exception $e) {
            $this->logError('清除角色权限失败', $e->getMessage());
            return false;
        }
    }

    /**
     * 获取角色统计信息
     * 
     * @return array 统计信息
     */
    public function getRoleStats()
    {
        return [
            'permission_count' => $this->permissions()->count(),
            'agent_count' => $this->agents()->count(),
            'active_permission_count' => $this->permissions()->where('status', Permission::STATUS_ACTIVE)->count(),
            'active_agent_count' => $this->agents()->where('status', Agent::STATUS_ACTIVE)->count(),
        ];
    }

    /**
     * 根据角色标识查找角色
     * 
     * @param string $slug 角色标识
     * @return Role|null 角色对象
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)
                   ->where('status', self::STATUS_ACTIVE)
                   ->find();
    }

    /**
     * 获取可用的角色列表
     * 
     * @param bool $includeSystem 是否包含系统角色
     * @return array 角色列表
     */
    public static function getActiveRoles($includeSystem = true)
    {
        $query = self::where('status', self::STATUS_ACTIVE);
        
        if (!$includeSystem) {
            $query->where('is_system', self::CUSTOM_ROLE);
        }
        
        return $query->order('sort asc, id asc')->select()->toArray();
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
        // 角色名称验证
        if (isset($data['name'])) {
            if (empty($data['name'])) {
                throw new Exception('角色名称不能为空');
            }
            
            if (strlen($data['name']) > 50) {
                throw new Exception('角色名称长度不能超过50个字符');
            }
        }

        // 角色标识验证
        if (isset($data['slug'])) {
            if (empty($data['slug'])) {
                throw new Exception('角色标识不能为空');
            }
            
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $data['slug'])) {
                throw new Exception('角色标识只能包含字母、数字、下划线和横线');
            }
            
            // 检查标识是否已存在
            $exists = self::where('slug', $data['slug'])
                         ->where('id', '<>', $this->id ?? 0)
                         ->find();
            if ($exists) {
                throw new Exception('角色标识已存在');
            }
        }

        return true;
    }
}
