<?php

// +----------------------------------------------------------------------
// | 认证服务 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建认证服务 - 认证系统升级
// | 功能说明：基于新RBAC权限体系的用户认证和权限验证服务
// | 新架构：集成Agent、Role、Permission、AgentRole模型的完整认证体系
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\common\service;

use app\admin\model\Agent;
use app\admin\model\AgentRole;
use app\admin\model\Permission;
use app\common\constants\AdminConstant;
use think\Exception;
use think\facade\Session;
use think\facade\Request;

/**
 * 认证服务
 * 
 * 基于新RBAC权限体系的用户认证和权限验证服务
 * 集成设备指纹识别、角色权限继承、模块权限验证等功能
 * 
 * @package app\common\service
 * @version 3.0.0
 * @since 2025-01-21
 */
class AuthenticationService
{
    /**
     * 当前代理ID
     * @var int|null
     */
    protected $agentId = null;

    /**
     * 当前代理信息
     * @var Agent|null
     */
    protected $agent = null;

    /**
     * 权限验证开关
     * @var bool
     */
    protected $authEnabled = true;

    /**
     * 超级管理员ID
     * @var int
     */
    protected $superAdminId = 1;

    /**
     * 构造方法
     * 
     * @param int|null $agentId 代理ID
     */
    public function __construct($agentId = null)
    {
        $this->agentId = $agentId;
        $this->loadAgent();
    }

    /**
     * 加载代理信息
     * 
     * @return void
     */
    protected function loadAgent()
    {
        if ($this->agentId) {
            try {
                $this->agent = Agent::find($this->agentId);
            } catch (Exception $e) {
                $this->agent = null;
            }
        }
    }

    /**
     * 用户登录认证
     * 
     * @param string $username 用户名
     * @param string $password 密码
     * @param string $deviceFingerprint 设备指纹
     * @param bool $keepLogin 是否保持登录
     * @return array 登录结果
     * @throws Exception
     */
    public function login($username, $password, $deviceFingerprint = '', $keepLogin = false)
    {
        try {
            // 检查是否需要数据迁移
            $this->checkDataMigration();

            // 查找代理用户 - 严格使用新Agent模型
            $agent = Agent::findByUsername($username);
            if (!$agent) {
                throw new Exception('用户名不存在，请确保已完成数据迁移到新权限系统');
            }

            // 检查账户状态
            if (!$agent->isActive()) {
                throw new Exception('账户已被禁用或锁定');
            }

            // 验证密码
            if (!$agent->verifyPassword($password)) {
                throw new Exception('密码错误');
            }

            // 设备指纹验证（如果启用）
            if (!empty($deviceFingerprint)) {
                $this->validateDeviceFingerprint($agent, $deviceFingerprint);
            }

            // 更新最后登录时间
            $agent->updateLastLogin();

            // 设置会话信息
            $sessionData = [
                'id' => $agent->id,
                'username' => $agent->username,
                'role_type' => $agent->role_type,
                'device_fingerprint' => $deviceFingerprint,
                'login_time' => time(),
                'expire_time' => $keepLogin ? true : (time() + 7200), // 2小时过期
            ];

            Session::set('admin', $sessionData);

            return [
                'success' => true,
                'message' => '登录成功',
                'data' => [
                    'agent_id' => $agent->id,
                    'username' => $agent->username,
                    'role_type' => $agent->role_type,
                    'is_super_admin' => $agent->isSuperAdmin(),
                ],
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * 检查数据迁移状态
     * 确保新权限系统有基础数据
     *
     * @throws Exception
     */
    protected function checkDataMigration()
    {
        try {
            // 检查agents表是否有数据
            $agentCount = Agent::count();
            if ($agentCount === 0) {
                throw new Exception('检测到agents表为空，请先执行数据迁移命令将旧系统数据迁移到新权限系统。迁移完成后方可使用新认证功能。');
            }

            // 检查是否有超级管理员
            $superAdmin = Agent::where('role_type', Agent::ROLE_SUPER_ADMIN)->find();
            if (!$superAdmin) {
                throw new Exception('检测到系统中没有超级管理员，请先创建超级管理员账户或执行数据迁移。');
            }

        } catch (Exception $e) {
            // 如果是我们抛出的异常，直接重新抛出
            if (strpos($e->getMessage(), '检测到') === 0) {
                throw $e;
            }

            // 其他异常可能是表不存在等问题
            throw new Exception('新权限系统初始化检查失败，请确保数据库已正确安装并执行了数据迁移。错误信息：' . $e->getMessage());
        }
    }

    /**
     * 验证设备指纹
     *
     * @param Agent $agent 代理对象
     * @param string $deviceFingerprint 设备指纹
     * @return bool 验证结果
     * @throws Exception
     */
    protected function validateDeviceFingerprint($agent, $deviceFingerprint)
    {
        // 如果是首次登录，记录设备指纹
        if (empty($agent->device_fingerprint)) {
            $agent->save(['device_fingerprint' => $deviceFingerprint]);
            return true;
        }

        // 验证设备指纹是否匹配
        if ($agent->device_fingerprint !== $deviceFingerprint) {
            throw new Exception('设备指纹不匹配，请联系管理员');
        }

        return true;
    }

    /**
     * 检查用户登录状态
     * 
     * @return bool 是否已登录
     */
    public function isLoggedIn()
    {
        $adminSession = Session::get('admin');
        
        if (empty($adminSession) || empty($adminSession['id'])) {
            return false;
        }

        // 检查登录是否过期
        if (isset($adminSession['expire_time']) && 
            $adminSession['expire_time'] !== true && 
            time() > $adminSession['expire_time']) {
            $this->logout();
            return false;
        }

        return true;
    }

    /**
     * 获取当前登录的代理ID
     * 
     * @return int|null 代理ID
     */
    public function getCurrentAgentId()
    {
        $adminSession = Session::get('admin');
        return $adminSession['id'] ?? null;
    }

    /**
     * 获取当前登录的代理信息
     * 
     * @return Agent|null 代理对象
     */
    public function getCurrentAgent()
    {
        if (!$this->agent && $this->isLoggedIn()) {
            $this->agentId = $this->getCurrentAgentId();
            $this->loadAgent();
        }
        
        return $this->agent;
    }

    /**
     * 检查是否为超级管理员
     *
     * @return bool 是否为超级管理员
     */
    public function isSuperAdmin()
    {
        $agent = $this->getCurrentAgent();
        return $agent && $agent->isSuperAdmin();
    }

    /**
     * 权限验证
     * 
     * @param string $permission 权限标识或节点路径
     * @param string $module 模块名称（可选）
     * @return bool 是否有权限
     */
    public function checkPermission($permission, $module = '')
    {
        // 检查权限验证开关
        if (!$this->authEnabled) {
            return true;
        }

        // 超级管理员拥有所有权限
        if ($this->isSuperAdmin()) {
            return true;
        }

        $agentId = $this->getCurrentAgentId();
        if (!$agentId) {
            return false;
        }

        try {
            // 如果指定了模块，检查模块权限
            if (!empty($module)) {
                return $this->checkModulePermission($agentId, $module);
            }

            // 检查具体权限
            return AgentRole::checkAgentPermission($agentId, $permission);

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 检查模块权限
     * 
     * @param int $agentId 代理ID
     * @param string $module 模块名称
     * @return bool 是否有模块权限
     */
    protected function checkModulePermission($agentId, $module)
    {
        try {
            $permissions = AgentRole::getAgentPermissions($agentId, $module);
            return !empty($permissions);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 检查节点权限
     * 基于控制器/方法路径的权限验证
     * 
     * @param string $node 节点路径 (如: system.auth/index)
     * @return bool 是否有权限
     */
    public function checkNode($node = null)
    {
        // 获取当前节点
        if (empty($node)) {
            $node = $this->getCurrentNode();
        } else {
            $node = $this->parseNodeStr($node);
        }

        // 超级管理员拥有所有权限
        if ($this->isSuperAdmin()) {
            return true;
        }

        // 解析节点到模块和权限
        $modulePermission = $this->parseNodeToPermission($node);
        
        return $this->checkPermission($modulePermission['permission'], $modulePermission['module']);
    }

    /**
     * 获取当前节点
     * 
     * @return string 当前节点路径
     */
    public function getCurrentNode()
    {
        $controller = Request::controller();
        $action = Request::action();
        return $this->parseNodeStr($controller . '/' . $action);
    }

    /**
     * 解析节点字符串
     * 将驼峰命名转换为下划线命名
     * 
     * @param string $node 节点字符串
     * @return string 解析后的节点
     */
    protected function parseNodeStr($node)
    {
        $array = explode('/', $node);
        foreach ($array as $key => $val) {
            if ($key == 0) {
                $val = explode('.', $val);
                foreach ($val as &$vo) {
                    $vo = $this->humpToLine(lcfirst($vo));
                }
                $val = implode('.', $val);
                $array[$key] = $val;
            }
        }
        return implode('/', $array);
    }

    /**
     * 驼峰转下划线
     * 
     * @param string $str 驼峰字符串
     * @return string 下划线字符串
     */
    protected function humpToLine($str)
    {
        return strtolower(preg_replace('/([A-Z])/', '_$1', $str));
    }

    /**
     * 解析节点到权限
     * 将节点路径解析为模块和权限标识
     * 
     * @param string $node 节点路径
     * @return array 包含module和permission的数组
     */
    protected function parseNodeToPermission($node)
    {
        // 默认映射规则
        $moduleMap = [
            'system' => Permission::MODULE_SYSTEM,
            'agent' => Permission::MODULE_AGENT,
            'content' => Permission::MODULE_CONTENT,
            'payment' => Permission::MODULE_PAYMENT,
            'report' => Permission::MODULE_REPORT,
            'config' => Permission::MODULE_CONFIG,
        ];

        // 解析节点路径
        $parts = explode('/', $node);
        $controllerPath = $parts[0] ?? '';
        $action = $parts[1] ?? 'index';

        // 解析控制器路径
        $controllerParts = explode('.', $controllerPath);
        $module = $controllerParts[0] ?? 'system';
        $controller = $controllerParts[1] ?? 'index';

        // 映射到权限模块
        $permissionModule = $moduleMap[$module] ?? Permission::MODULE_SYSTEM;
        
        // 生成权限标识
        $permission = $module . '.' . $controller . '.' . $action;

        return [
            'module' => $permissionModule,
            'permission' => $permission,
        ];
    }

    /**
     * 获取代理的所有权限
     * 
     * @param int|null $agentId 代理ID，为空则使用当前代理
     * @return array 权限列表
     */
    public function getAgentPermissions($agentId = null)
    {
        $agentId = $agentId ?: $this->getCurrentAgentId();
        
        if (!$agentId) {
            return [];
        }

        try {
            return AgentRole::getAgentPermissions($agentId);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * 获取代理的所有角色
     * 
     * @param int|null $agentId 代理ID，为空则使用当前代理
     * @return array 角色列表
     */
    public function getAgentRoles($agentId = null)
    {
        $agentId = $agentId ?: $this->getCurrentAgentId();
        
        if (!$agentId) {
            return [];
        }

        try {
            return AgentRole::getAgentRoles($agentId);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * 用户登出
     * 
     * @return bool 登出结果
     */
    public function logout()
    {
        Session::delete('admin');
        $this->agentId = null;
        $this->agent = null;
        return true;
    }

    /**
     * 设置权限验证开关
     * 
     * @param bool $enabled 是否启用权限验证
     * @return $this
     */
    public function setAuthEnabled($enabled)
    {
        $this->authEnabled = $enabled;
        return $this;
    }

    /**
     * 获取权限验证开关状态
     * 
     * @return bool 权限验证是否启用
     */
    public function isAuthEnabled()
    {
        return $this->authEnabled;
    }
}
