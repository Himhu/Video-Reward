<?php

// +----------------------------------------------------------------------
// | 代理角色关联模型 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建代理角色关联模型 - 权限系统重构
// | 功能说明：管理代理与角色的多对多关联关系，支持角色分配和权限继承
// | 新架构：基于RBAC模型，支持灵活的代理角色分配机制
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\BaseModel;
use think\Exception;
use think\facade\Db;

/**
 * 代理角色关联模型
 * 
 * 管理代理与角色的多对多关联关系
 * 提供角色分配、权限继承、批量操作等功能
 * 
 * @package app\admin\model
 * @version 3.0.0
 * @since 2025-01-21
 */
class AgentRole extends BaseModel
{
    /**
     * 数据表名称（不含前缀）
     * @var string
     */
    protected $name = 'agent_roles';

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
        'agent_id' => 'integer',
        'role_id' => 'integer',
        'created_at' => 'timestamp',
    ];

    /**
     * 只读字段
     * @var array
     */
    protected $readonly = [
        'id',
        'agent_id',
        'role_id',
        'created_at',
    ];

    /**
     * 关联代理模型
     * 
     * @return \think\model\relation\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

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
     * 为代理批量分配角色
     * 
     * @param int $agentId 代理ID
     * @param array $roleIds 角色ID数组
     * @return bool 分配结果
     * @throws Exception
     */
    public static function assignAgentRoles($agentId, array $roleIds)
    {
        if (empty($agentId)) {
            throw new Exception('代理ID不能为空');
        }

        Db::startTrans();
        try {
            // 验证代理是否存在
            $agent = Agent::find($agentId);
            if (!$agent) {
                throw new Exception('代理不存在');
            }

            // 清除代理现有角色
            self::where('agent_id', $agentId)->delete();

            // 如果角色ID数组为空，则只清除角色
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

            // 批量插入新的代理角色关联
            $insertData = [];
            $now = date('Y-m-d H:i:s');
            
            foreach ($roleIds as $roleId) {
                $insertData[] = [
                    'agent_id' => $agentId,
                    'role_id' => $roleId,
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
     * 为角色批量分配代理
     * 
     * @param int $roleId 角色ID
     * @param array $agentIds 代理ID数组
     * @return bool 分配结果
     * @throws Exception
     */
    public static function assignRoleAgents($roleId, array $agentIds)
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

            // 清除角色现有代理
            self::where('role_id', $roleId)->delete();

            // 如果代理ID数组为空，则只清除关联
            if (empty($agentIds)) {
                Db::commit();
                return true;
            }

            // 验证代理ID的有效性
            $validAgents = Agent::where('id', 'in', $agentIds)
                                ->where('status', Agent::STATUS_ACTIVE)
                                ->column('id');
            
            if (count($validAgents) !== count($agentIds)) {
                throw new Exception('包含无效的代理ID');
            }

            // 批量插入新的代理角色关联
            $insertData = [];
            $now = date('Y-m-d H:i:s');
            
            foreach ($agentIds as $agentId) {
                $insertData[] = [
                    'agent_id' => $agentId,
                    'role_id' => $roleId,
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
     * 检查代理是否拥有指定角色
     * 
     * @param int $agentId 代理ID
     * @param int|string $role 角色ID或角色标识
     * @return bool 是否拥有角色
     */
    public static function checkAgentRole($agentId, $role)
    {
        $query = self::alias('ar')
                    ->join('roles r', 'ar.role_id = r.id')
                    ->where('ar.agent_id', $agentId)
                    ->where('r.status', Role::STATUS_ACTIVE);

        if (is_numeric($role)) {
            $query->where('ar.role_id', $role);
        } else {
            $query->where('r.slug', $role);
        }

        return $query->count() > 0;
    }

    /**
     * 检查代理是否拥有指定权限
     * 通过角色继承权限
     * 
     * @param int $agentId 代理ID
     * @param int|string $permission 权限ID或权限标识
     * @return bool 是否拥有权限
     */
    public static function checkAgentPermission($agentId, $permission)
    {
        $query = self::alias('ar')
                    ->join('roles r', 'ar.role_id = r.id')
                    ->join('role_permissions rp', 'r.id = rp.role_id')
                    ->join('permissions p', 'rp.permission_id = p.id')
                    ->where('ar.agent_id', $agentId)
                    ->where('r.status', Role::STATUS_ACTIVE)
                    ->where('p.status', Permission::STATUS_ACTIVE);

        if (is_numeric($permission)) {
            $query->where('p.id', $permission);
        } else {
            $query->where('p.slug', $permission);
        }

        return $query->count() > 0;
    }

    /**
     * 获取代理的所有角色
     * 
     * @param int $agentId 代理ID
     * @return array 角色列表
     */
    public static function getAgentRoles($agentId)
    {
        return self::alias('ar')
                  ->join('roles r', 'ar.role_id = r.id')
                  ->where('ar.agent_id', $agentId)
                  ->where('r.status', Role::STATUS_ACTIVE)
                  ->field('r.*')
                  ->select()
                  ->toArray();
    }

    /**
     * 获取代理的所有权限
     * 通过角色继承获取
     * 
     * @param int $agentId 代理ID
     * @param string $module 模块筛选（可选）
     * @return array 权限列表
     */
    public static function getAgentPermissions($agentId, $module = '')
    {
        $query = self::alias('ar')
                    ->join('roles r', 'ar.role_id = r.id')
                    ->join('role_permissions rp', 'r.id = rp.role_id')
                    ->join('permissions p', 'rp.permission_id = p.id')
                    ->where('ar.agent_id', $agentId)
                    ->where('r.status', Role::STATUS_ACTIVE)
                    ->where('p.status', Permission::STATUS_ACTIVE)
                    ->field('p.*')
                    ->group('p.id');

        if (!empty($module)) {
            $query->where('p.module', $module);
        }

        return $query->select()->toArray();
    }

    /**
     * 获取角色的所有代理
     * 
     * @param int $roleId 角色ID
     * @return array 代理列表
     */
    public static function getRoleAgents($roleId)
    {
        return self::alias('ar')
                  ->join('agents a', 'ar.agent_id = a.id')
                  ->where('ar.role_id', $roleId)
                  ->where('a.status', Agent::STATUS_ACTIVE)
                  ->field('a.*')
                  ->select()
                  ->toArray();
    }

    /**
     * 复制代理角色
     * 
     * @param int $fromAgentId 源代理ID
     * @param int $toAgentId 目标代理ID
     * @return bool 复制结果
     * @throws Exception
     */
    public static function copyAgentRoles($fromAgentId, $toAgentId)
    {
        if ($fromAgentId === $toAgentId) {
            throw new Exception('源代理和目标代理不能相同');
        }

        // 获取源代理的角色
        $roleIds = self::where('agent_id', $fromAgentId)->column('role_id');
        
        if (empty($roleIds)) {
            return true;
        }

        // 为目标代理分配角色
        return self::assignAgentRoles($toAgentId, $roleIds);
    }

    /**
     * 获取代理角色统计
     * 
     * @param int $agentId 代理ID
     * @return array 统计信息
     */
    public static function getAgentRoleStats($agentId)
    {
        $roleCount = self::where('agent_id', $agentId)->count();
        
        // 通过角色获取权限统计
        $permissionCount = self::alias('ar')
                              ->join('role_permissions rp', 'ar.role_id = rp.role_id')
                              ->join('permissions p', 'rp.permission_id = p.id')
                              ->where('ar.agent_id', $agentId)
                              ->where('p.status', Permission::STATUS_ACTIVE)
                              ->group('p.id')
                              ->count();

        // 按模块统计权限
        $moduleStats = self::alias('ar')
                          ->join('role_permissions rp', 'ar.role_id = rp.role_id')
                          ->join('permissions p', 'rp.permission_id = p.id')
                          ->where('ar.agent_id', $agentId)
                          ->where('p.status', Permission::STATUS_ACTIVE)
                          ->group('p.module')
                          ->field('p.module, count(distinct p.id) as count')
                          ->select()
                          ->toArray();

        $moduleCount = [];
        foreach ($moduleStats as $stat) {
            $moduleCount[$stat['module']] = $stat['count'];
        }

        return [
            'role_count' => $roleCount,
            'permission_count' => $permissionCount,
            'module_count' => $moduleCount,
        ];
    }

    /**
     * 清理无效的代理角色关联
     * 清理关联到已删除代理或角色的记录
     * 
     * @return int 清理的记录数
     */
    public static function cleanInvalidRelations()
    {
        $count = 0;

        // 清理关联到已删除代理的记录
        $invalidAgentIds = self::alias('ar')
                              ->leftJoin('agents a', 'ar.agent_id = a.id')
                              ->whereNull('a.id')
                              ->column('ar.id');
        
        if (!empty($invalidAgentIds)) {
            $count += self::where('id', 'in', $invalidAgentIds)->delete();
        }

        // 清理关联到已删除角色的记录
        $invalidRoleIds = self::alias('ar')
                             ->leftJoin('roles r', 'ar.role_id = r.id')
                             ->whereNull('r.id')
                             ->column('ar.id');
        
        if (!empty($invalidRoleIds)) {
            $count += self::where('id', 'in', $invalidRoleIds)->delete();
        }

        return $count;
    }

    /**
     * 获取代理角色关联统计
     * 
     * @return array 统计信息
     */
    public static function getRelationStats()
    {
        $total = self::count();
        $agentCount = self::group('agent_id')->count();
        $roleCount = self::group('role_id')->count();

        return [
            'total_relations' => $total,
            'agents_with_roles' => $agentCount,
            'roles_with_agents' => $roleCount,
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
        // 代理ID验证
        if (isset($data['agent_id'])) {
            if (empty($data['agent_id'])) {
                throw new Exception('代理ID不能为空');
            }
            
            $agent = Agent::find($data['agent_id']);
            if (!$agent) {
                throw new Exception('代理不存在');
            }
        }

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

        // 检查重复关联
        if (isset($data['agent_id']) && isset($data['role_id'])) {
            $exists = self::where('agent_id', $data['agent_id'])
                         ->where('role_id', $data['role_id'])
                         ->where('id', '<>', $this->id ?? 0)
                         ->find();
            if ($exists) {
                throw new Exception('代理角色关联已存在');
            }
        }

        return true;
    }
}
