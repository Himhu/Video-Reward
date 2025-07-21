<?php

// +----------------------------------------------------------------------
// | 权限模型 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建权限管理模型 - 权限系统重构
// | 功能说明：管理系统权限定义，支持模块分组和权限验证
// | 新架构：基于RBAC模型，支持细粒度权限控制和模块化管理
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Exception;

/**
 * 权限模型
 * 
 * 管理系统权限定义，包括权限标识、模块分组、权限验证等
 * 基于RBAC模型设计，支持细粒度的权限控制机制
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class Permission extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'permissions';

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
        'module' => 'string',
        'description' => 'string',
        'status' => 'integer',
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
     * 模块常量
     */
    const MODULE_SYSTEM = 'system';         // 系统管理
    const MODULE_AGENT = 'agent';           // 代理管理
    const MODULE_CONTENT = 'content';       // 内容管理
    const MODULE_PAYMENT = 'payment';       // 支付管理
    const MODULE_REPORT = 'report';         // 报表统计
    const MODULE_CONFIG = 'config';         // 配置管理

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
     * 获取模块列表
     * 
     * @return array 模块列表
     */
    public static function getModuleList()
    {
        return [
            self::MODULE_SYSTEM => '系统管理',
            self::MODULE_AGENT => '代理管理',
            self::MODULE_CONTENT => '内容管理',
            self::MODULE_PAYMENT => '支付管理',
            self::MODULE_REPORT => '报表统计',
            self::MODULE_CONFIG => '配置管理',
        ];
    }

    /**
     * 关联角色模型
     * 多对多关联
     * 
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, RolePermission::class, 'role_id', 'permission_id');
    }

    /**
     * 关联角色权限模型
     * 一对多关联
     * 
     * @return \think\model\relation\HasMany
     */
    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class, 'permission_id', 'id');
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
     * 模块获取器
     * 
     * @param string $value 原始值
     * @return string 模块文本
     */
    public function getModuleTextAttr($value)
    {
        $moduleList = self::getModuleList();
        return $moduleList[$this->module] ?? '未知模块';
    }

    /**
     * 检查权限是否可用
     * 
     * @return bool 权限是否可用
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * 获取权限的所有角色
     * 
     * @return array 角色数组
     */
    public function getPermissionRoles()
    {
        return $this->roles()->where('status', Role::STATUS_ACTIVE)->select()->toArray();
    }

    /**
     * 检查权限是否被角色使用
     * 
     * @return bool 是否被使用
     */
    public function isUsedByRoles()
    {
        return $this->rolePermissions()->count() > 0;
    }

    /**
     * 根据权限标识查找权限
     * 
     * @param string $slug 权限标识
     * @return Permission|null 权限对象
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)
                   ->where('status', self::STATUS_ACTIVE)
                   ->find();
    }

    /**
     * 根据模块获取权限列表
     * 
     * @param string $module 模块名称
     * @param bool $activeOnly 是否只获取启用的权限
     * @return array 权限列表
     */
    public static function getPermissionsByModule($module, $activeOnly = true)
    {
        $query = self::where('module', $module);
        
        if ($activeOnly) {
            $query->where('status', self::STATUS_ACTIVE);
        }
        
        return $query->order('id asc')->select()->toArray();
    }

    /**
     * 获取权限树结构
     * 参考旧系统的权限树构建逻辑
     * 
     * @param bool $activeOnly 是否只获取启用的权限
     * @return array 权限树
     */
    public static function getPermissionTree($activeOnly = true)
    {
        $query = self::field('id,name,slug,module,description,status');
        
        if ($activeOnly) {
            $query->where('status', self::STATUS_ACTIVE);
        }
        
        $permissions = $query->select()->toArray();
        
        return self::buildPermissionTree($permissions);
    }

    /**
     * 构建权限树结构
     * 参考SystemNode的buildNodeTree方法
     * 
     * @param array $permissions 权限列表
     * @return array 权限树
     */
    protected static function buildPermissionTree($permissions)
    {
        $tree = [];
        $modules = self::getModuleList();
        
        // 按模块分组
        foreach ($modules as $moduleKey => $moduleName) {
            $modulePermissions = array_filter($permissions, function($permission) use ($moduleKey) {
                return $permission['module'] === $moduleKey;
            });
            
            if (!empty($modulePermissions)) {
                $tree[] = [
                    'id' => 'module_' . $moduleKey,
                    'name' => $moduleName,
                    'slug' => $moduleKey,
                    'module' => $moduleKey,
                    'type' => 'module',
                    'children' => array_values($modulePermissions),
                ];
            }
        }
        
        return $tree;
    }

    /**
     * 获取权限选择器数据
     * 用于前端权限分配界面
     * 
     * @param array $selectedIds 已选中的权限ID
     * @return array 选择器数据
     */
    public static function getPermissionSelector($selectedIds = [])
    {
        $tree = self::getPermissionTree(true);
        
        // 标记选中状态
        foreach ($tree as &$module) {
            $module['checked'] = false;
            $module['spread'] = true;
            
            if (isset($module['children'])) {
                $checkedCount = 0;
                foreach ($module['children'] as &$permission) {
                    $permission['checked'] = in_array($permission['id'], $selectedIds);
                    if ($permission['checked']) {
                        $checkedCount++;
                    }
                }
                
                // 如果所有子权限都选中，则模块也选中
                if ($checkedCount > 0 && $checkedCount === count($module['children'])) {
                    $module['checked'] = true;
                }
            }
        }
        
        return $tree;
    }

    /**
     * 批量创建权限
     * 
     * @param array $permissions 权限数据数组
     * @return bool 创建结果
     * @throws Exception
     */
    public static function batchCreate(array $permissions)
    {
        if (empty($permissions)) {
            return true;
        }

        try {
            $insertData = [];
            $now = date('Y-m-d H:i:s');
            
            foreach ($permissions as $permission) {
                // 验证必要字段
                if (empty($permission['name']) || empty($permission['slug']) || empty($permission['module'])) {
                    throw new Exception('权限名称、标识和模块不能为空');
                }
                
                // 检查标识是否已存在
                $exists = self::where('slug', $permission['slug'])->find();
                if ($exists) {
                    continue; // 跳过已存在的权限
                }
                
                $insertData[] = [
                    'name' => $permission['name'],
                    'slug' => $permission['slug'],
                    'module' => $permission['module'],
                    'description' => $permission['description'] ?? '',
                    'status' => $permission['status'] ?? self::STATUS_ACTIVE,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            
            if (!empty($insertData)) {
                return (new self())->insertAll($insertData);
            }
            
            return true;

        } catch (Exception $e) {
            throw new Exception('批量创建权限失败：' . $e->getMessage());
        }
    }

    /**
     * 获取权限统计信息
     * 
     * @return array 统计信息
     */
    public static function getPermissionStats()
    {
        $total = self::count();
        $active = self::where('status', self::STATUS_ACTIVE)->count();
        $modules = self::group('module')->column('module');
        
        $moduleStats = [];
        foreach ($modules as $module) {
            $moduleStats[$module] = self::where('module', $module)->count();
        }
        
        return [
            'total' => $total,
            'active' => $active,
            'disabled' => $total - $active,
            'modules' => count($modules),
            'module_stats' => $moduleStats,
        ];
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
        // 权限名称验证
        if (isset($data['name'])) {
            if (empty($data['name'])) {
                throw new Exception('权限名称不能为空');
            }
            
            if (strlen($data['name']) > 100) {
                throw new Exception('权限名称长度不能超过100个字符');
            }
        }

        // 权限标识验证
        if (isset($data['slug'])) {
            if (empty($data['slug'])) {
                throw new Exception('权限标识不能为空');
            }
            
            if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $data['slug'])) {
                throw new Exception('权限标识只能包含字母、数字、下划线、点和横线');
            }
            
            // 检查标识是否已存在
            $exists = self::where('slug', $data['slug'])
                         ->where('id', '<>', $this->id ?? 0)
                         ->find();
            if ($exists) {
                throw new Exception('权限标识已存在');
            }
        }

        // 模块验证
        if (isset($data['module'])) {
            if (empty($data['module'])) {
                throw new Exception('权限模块不能为空');
            }
            
            $modules = array_keys(self::getModuleList());
            if (!in_array($data['module'], $modules)) {
                throw new Exception('无效的权限模块');
            }
        }

        return true;
    }
}
