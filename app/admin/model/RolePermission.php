<?php

// +----------------------------------------------------------------------
// | 角色权限关联模型 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建角色权限关联模型 - 权限系统重构
// | 功能说明：管理角色与权限的多对多关联关系，支持权限分配和批量操作
// | 新架构：基于RBAC模型，支持灵活的角色权限分配机制
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Exception;
use think\facade\Db;

/**
 * 角色权限关联模型
 * 
 * 管理角色与权限的多对多关联关系
 * 提供权限分配、批量操作、权限查询等功能
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class RolePermission extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'role_permissions';

    /**
     * 主键字段
     * @var string
     */
    protected $pk = 'id';

    /**
     * 关闭时间戳自动写入
     * @var bool
     */
    protected $autoWriteTimestamp = 'timestamp';

    /**
     * 创建时间字段
     * @var string
     */
    protected $createTime = 'created_at';

    /**
     * 更新时间字段设为false（关联表通常不需要更新时间）
     * @var bool
     */
    protected $updateTime = false;

    /**
     * 字段类型定义
     * @var array
     */
    protected $type = [
        'id' => 'integer',
        'role_id' => 'integer',
        'permission_id' => 'integer',
        'created_at' => 'timestamp',
    ];

    /**
     * 只读字段
     * @var array
     */
    protected $readonly = [
        'id',
        'role_id',
        'permission_id',
        'created_at',
    ];

    /**
     * 关联角色模型
     * 
     * @return \think\model\relation\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    /**
     * 关联权限模型
     * 
     * @return \think\model\relation\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }

    /**
     * 为角色批量分配权限
     * 
     * @param int $roleId 角色ID
     * @param array $permissionIds 权限ID数组
     * @return bool 分配结果
     * @throws Exception
     */
    public static function assignRolePermissions($roleId, array $permissionIds)
    {
        if (empty($roleId)) {
            throw new Exception('角色ID不能为空');
        }

        Db::startTrans();
        try {
            // 验证角色是否存在
            $role = Role::find($roleId);
            if (!$role) {
                throw new Exception('角色不存在');
            }

            // 清除角色现有权限
            self::where('role_id', $roleId)->delete();

            // 如果权限ID数组为空，则只清除权限
            if (empty($permissionIds)) {
                Db::commit();
                return true;
            }

            // 验证权限ID的有效性
            $validPermissions = Permission::where('id', 'in', $permissionIds)
                                         ->where('status', Permission::STATUS_ACTIVE)
                                         ->column('id');
            
            if (count($validPermissions) !== count($permissionIds)) {
                throw new Exception('包含无效的权限ID');
            }

            // 批量插入新的角色权限关联
            $insertData = [];
            $now = date('Y-m-d H:i:s');
            
            foreach ($permissionIds as $permissionId) {
                $insertData[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                ];
            }

            $result = (new self())->insertAll($insertData);
            
            Db::commit();
            return $result !== false;

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 为权限批量分配角色
     * 
     * @param int $permissionId 权限ID
     * @param array $roleIds 角色ID数组
     * @return bool 分配结果
     * @throws Exception
     */
    public static function assignPermissionRoles($permissionId, array $roleIds)
    {
        if (empty($permissionId)) {
            throw new Exception('权限ID不能为空');
        }

        Db::startTrans();
        try {
            // 验证权限是否存在
            $permission = Permission::find($permissionId);
            if (!$permission) {
                throw new Exception('权限不存在');
            }

            // 清除权限现有角色
            self::where('permission_id', $permissionId)->delete();

            // 如果角色ID数组为空，则只清除关联
            if (empty($roleIds)) {
                Db::commit();
                return true;
            }

            // 验证角色ID的有效性
            $validRoles = Role::where('id', 'in', $roleIds)
                             ->where('status', Role::STATUS_ACTIVE)
                             ->column('id');
            
            if (count($validRoles) !== count($roleIds)) {
                throw new Exception('包含无效的角色ID');
            }

            // 批量插入新的角色权限关联
            $insertData = [];
            $now = date('Y-m-d H:i:s');
            
            foreach ($roleIds as $roleId) {
                $insertData[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'created_at' => $now,
                ];
            }

            $result = (new self())->insertAll($insertData);
            
            Db::commit();
            return $result !== false;

        } catch (Exception $e) {
            Db::rollback();
            throw $e;
        }
    }

    /**
     * 检查角色是否拥有指定权限
     * 
     * @param int $roleId 角色ID
     * @param int|string $permission 权限ID或权限标识
     * @return bool 是否拥有权限
     */
    public static function checkRolePermission($roleId, $permission)
    {
        $query = self::alias('rp')
                    ->join('permissions p', 'rp.permission_id = p.id')
                    ->where('rp.role_id', $roleId)
                    ->where('p.status', Permission::STATUS_ACTIVE);

        if (is_numeric($permission)) {
            $query->where('rp.permission_id', $permission);
        } else {
            $query->where('p.slug', $permission);
        }

        return $query->count() > 0;
    }

    /**
     * 获取角色的所有权限
     * 
     * @param int $roleId 角色ID
     * @param string $module 模块筛选（可选）
     * @return array 权限列表
     */
    public static function getRolePermissions($roleId, $module = '')
    {
        $query = self::alias('rp')
                    ->join('permissions p', 'rp.permission_id = p.id')
                    ->where('rp.role_id', $roleId)
                    ->where('p.status', Permission::STATUS_ACTIVE)
                    ->field('p.*');

        if (!empty($module)) {
            $query->where('p.module', $module);
        }

        return $query->select()->toArray();
    }

    /**
     * 获取权限的所有角色
     * 
     * @param int $permissionId 权限ID
     * @return array 角色列表
     */
    public static function getPermissionRoles($permissionId)
    {
        return self::alias('rp')
                  ->join('roles r', 'rp.role_id = r.id')
                  ->where('rp.permission_id', $permissionId)
                  ->where('r.status', Role::STATUS_ACTIVE)
                  ->field('r.*')
                  ->select()
                  ->toArray();
    }

    /**
     * 复制角色权限
     * 
     * @param int $fromRoleId 源角色ID
     * @param int $toRoleId 目标角色ID
     * @return bool 复制结果
     * @throws Exception
     */
    public static function copyRolePermissions($fromRoleId, $toRoleId)
    {
        if ($fromRoleId === $toRoleId) {
            throw new Exception('源角色和目标角色不能相同');
        }

        // 获取源角色的权限
        $permissionIds = self::where('role_id', $fromRoleId)->column('permission_id');
        
        if (empty($permissionIds)) {
            return true;
        }

        // 为目标角色分配权限
        return self::assignRolePermissions($toRoleId, $permissionIds);
    }

    /**
     * 获取角色权限统计
     * 
     * @param int $roleId 角色ID
     * @return array 统计信息
     */
    public static function getRolePermissionStats($roleId)
    {
        $total = self::where('role_id', $roleId)->count();
        
        // 按模块统计
        $moduleStats = self::alias('rp')
                          ->join('permissions p', 'rp.permission_id = p.id')
                          ->where('rp.role_id', $roleId)
                          ->where('p.status', Permission::STATUS_ACTIVE)
                          ->group('p.module')
                          ->field('p.module, count(*) as count')
                          ->select()
                          ->toArray();

        $moduleCount = [];
        foreach ($moduleStats as $stat) {
            $moduleCount[$stat['module']] = $stat['count'];
        }

        return [
            'total' => $total,
            'module_count' => $moduleCount,
        ];
    }

    /**
     * 清理无效的角色权限关联
     * 清理关联到已删除角色或权限的记录
     * 
     * @return int 清理的记录数
     */
    public static function cleanInvalidRelations()
    {
        $count = 0;

        // 清理关联到已删除角色的记录
        $invalidRoleIds = self::alias('rp')
                             ->leftJoin('roles r', 'rp.role_id = r.id')
                             ->whereNull('r.id')
                             ->column('rp.id');
        
        if (!empty($invalidRoleIds)) {
            $count += self::where('id', 'in', $invalidRoleIds)->delete();
        }

        // 清理关联到已删除权限的记录
        $invalidPermissionIds = self::alias('rp')
                                   ->leftJoin('permissions p', 'rp.permission_id = p.id')
                                   ->whereNull('p.id')
                                   ->column('rp.id');
        
        if (!empty($invalidPermissionIds)) {
            $count += self::where('id', 'in', $invalidPermissionIds)->delete();
        }

        return $count;
    }

    /**
     * 获取角色权限关联统计
     * 
     * @return array 统计信息
     */
    public static function getRelationStats()
    {
        $total = self::count();
        $roleCount = self::group('role_id')->count();
        $permissionCount = self::group('permission_id')->count();

        return [
            'total_relations' => $total,
            'roles_with_permissions' => $roleCount,
            'permissions_with_roles' => $permissionCount,
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
        // 角色ID验证
        if (isset($data['role_id'])) {
            if (empty($data['role_id'])) {
                throw new Exception('角色ID不能为空');
            }
            
            $role = Role::find($data['role_id']);
            if (!$role) {
                throw new Exception('角色不存在');
            }
        }

        // 权限ID验证
        if (isset($data['permission_id'])) {
            if (empty($data['permission_id'])) {
                throw new Exception('权限ID不能为空');
            }
            
            $permission = Permission::find($data['permission_id']);
            if (!$permission) {
                throw new Exception('权限不存在');
            }
        }

        // 检查重复关联
        if (isset($data['role_id']) && isset($data['permission_id'])) {
            $exists = self::where('role_id', $data['role_id'])
                         ->where('permission_id', $data['permission_id'])
                         ->where('id', '<>', $this->id ?? 0)
                         ->find();
            if ($exists) {
                throw new Exception('角色权限关联已存在');
            }
        }

        return true;
    }
}
