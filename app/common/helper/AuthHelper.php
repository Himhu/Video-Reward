<?php

// +----------------------------------------------------------------------
// | 权限验证助手类 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建权限验证助手 - 认证系统升级
// | 功能说明：提供便捷的权限验证和认证相关的助手方法
// | 新架构：基于AuthenticationService的权限验证助手
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\common\helper;

use app\common\service\AuthenticationService;
use app\admin\model\Agent;
use app\admin\model\AgentRole;
use app\admin\model\Permission;

/**
 * 权限验证助手类
 * 
 * 提供便捷的权限验证和认证相关的助手方法
 * 支持静态调用，方便在模板和其他地方使用
 * 
 * @package app\common\helper
 * @version 3.0.0
 * @since 2025-01-21
 */
class AuthHelper
{
    /**
     * 认证服务实例
     * @var AuthenticationService|null
     */
    protected static $authService = null;

    /**
     * 获取认证服务实例
     * 
     * @return AuthenticationService
     */
    protected static function getAuthService()
    {
        if (self::$authService === null) {
            self::$authService = new AuthenticationService();
        }
        
        return self::$authService;
    }

    /**
     * 检查用户是否已登录
     * 
     * @return bool 是否已登录
     */
    public static function isLoggedIn()
    {
        return self::getAuthService()->isLoggedIn();
    }

    /**
     * 获取当前登录的代理ID
     * 
     * @return int|null 代理ID
     */
    public static function getCurrentAgentId()
    {
        return self::getAuthService()->getCurrentAgentId();
    }

    /**
     * 获取当前登录的代理信息
     * 
     * @return Agent|null 代理对象
     */
    public static function getCurrentAgent()
    {
        return self::getAuthService()->getCurrentAgent();
    }

    /**
     * 检查是否为超级管理员
     * 
     * @return bool 是否为超级管理员
     */
    public static function isSuperAdmin()
    {
        return self::getAuthService()->isSuperAdmin();
    }

    /**
     * 检查权限
     * 
     * @param string $permission 权限标识
     * @param string $module 模块名称（可选）
     * @return bool 是否有权限
     */
    public static function checkPermission($permission, $module = '')
    {
        return self::getAuthService()->checkPermission($permission, $module);
    }

    /**
     * 检查节点权限
     * 
     * @param string|null $node 节点路径
     * @return bool 是否有权限
     */
    public static function checkNode($node = null)
    {
        return self::getAuthService()->checkNode($node);
    }

    /**
     * 检查模块权限
     * 
     * @param string $module 模块名称
     * @return bool 是否有模块权限
     */
    public static function checkModule($module)
    {
        return self::checkPermission('', $module);
    }

    /**
     * 获取当前代理的所有权限
     * 
     * @return array 权限列表
     */
    public static function getAgentPermissions()
    {
        return self::getAuthService()->getAgentPermissions();
    }

    /**
     * 获取当前代理的所有角色
     * 
     * @return array 角色列表
     */
    public static function getAgentRoles()
    {
        return self::getAuthService()->getAgentRoles();
    }

    /**
     * 检查代理是否拥有指定角色
     * 
     * @param string|int $role 角色标识或ID
     * @return bool 是否拥有角色
     */
    public static function hasRole($role)
    {
        $agentId = self::getCurrentAgentId();
        if (!$agentId) {
            return false;
        }

        return AgentRole::checkAgentRole($agentId, $role);
    }

    /**
     * 检查代理是否拥有指定权限（通过角色继承）
     * 
     * @param string|int $permission 权限标识或ID
     * @return bool 是否拥有权限
     */
    public static function hasPermission($permission)
    {
        $agentId = self::getCurrentAgentId();
        if (!$agentId) {
            return false;
        }

        return AgentRole::checkAgentPermission($agentId, $permission);
    }

    /**
     * 获取当前代理的权限菜单
     * 
     * @return array 权限菜单树
     */
    public static function getPermissionMenu()
    {
        $agentId = self::getCurrentAgentId();
        if (!$agentId) {
            return [];
        }

        try {
            // 如果是超级管理员，返回所有权限
            if (self::isSuperAdmin()) {
                return Permission::getPermissionTree(true);
            }

            // 获取代理的权限
            $permissions = AgentRole::getAgentPermissions($agentId);
            
            // 构建权限菜单树
            return self::buildPermissionMenu($permissions);

        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 构建权限菜单树
     * 
     * @param array $permissions 权限列表
     * @return array 菜单树
     */
    protected static function buildPermissionMenu($permissions)
    {
        $tree = [];
        $modules = Permission::getModuleList();
        
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
     * 获取权限验证的JavaScript代码
     * 用于前端权限控制
     * 
     * @return string JavaScript代码
     */
    public static function getPermissionScript()
    {
        $permissions = self::getAgentPermissions();
        $roles = self::getAgentRoles();
        $isSuperAdmin = self::isSuperAdmin();

        $permissionSlugs = array_column($permissions, 'slug');
        $roleSlugs = array_column($roles, 'slug');

        $script = "
        <script>
        window.AuthHelper = {
            isSuperAdmin: " . ($isSuperAdmin ? 'true' : 'false') . ",
            permissions: " . json_encode($permissionSlugs) . ",
            roles: " . json_encode($roleSlugs) . ",
            
            hasPermission: function(permission) {
                return this.isSuperAdmin || this.permissions.indexOf(permission) !== -1;
            },
            
            hasRole: function(role) {
                return this.isSuperAdmin || this.roles.indexOf(role) !== -1;
            },
            
            checkNode: function(node) {
                // 简化的节点权限检查
                return this.isSuperAdmin || this.hasPermission(node);
            }
        };
        </script>";

        return $script;
    }

    /**
     * 权限验证装饰器
     * 用于方法级别的权限验证
     * 
     * @param string $permission 权限标识
     * @param callable $callback 回调函数
     * @param callable|null $failCallback 失败回调
     * @return mixed 回调函数的返回值
     */
    public static function withPermission($permission, $callback, $failCallback = null)
    {
        if (self::checkPermission($permission)) {
            return call_user_func($callback);
        } else {
            if ($failCallback) {
                return call_user_func($failCallback);
            }
            throw new \Exception('无权限访问');
        }
    }

    /**
     * 角色验证装饰器
     * 用于方法级别的角色验证
     * 
     * @param string $role 角色标识
     * @param callable $callback 回调函数
     * @param callable|null $failCallback 失败回调
     * @return mixed 回调函数的返回值
     */
    public static function withRole($role, $callback, $failCallback = null)
    {
        if (self::hasRole($role)) {
            return call_user_func($callback);
        } else {
            if ($failCallback) {
                return call_user_func($failCallback);
            }
            throw new \Exception('角色权限不足');
        }
    }

    /**
     * 获取权限统计信息
     * 
     * @return array 统计信息
     */
    public static function getPermissionStats()
    {
        $agentId = self::getCurrentAgentId();
        if (!$agentId) {
            return [];
        }

        try {
            return AgentRole::getAgentRoleStats($agentId);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 清理权限缓存
     * 
     * @return bool 清理结果
     */
    public static function clearPermissionCache()
    {
        // 重新初始化认证服务
        self::$authService = null;
        return true;
    }

    /**
     * 设置认证服务实例
     * 
     * @param AuthenticationService $authService 认证服务
     * @return void
     */
    public static function setAuthService(AuthenticationService $authService)
    {
        self::$authService = $authService;
    }
}
