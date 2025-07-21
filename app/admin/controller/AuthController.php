<?php

// +----------------------------------------------------------------------
// | 认证控制器 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建认证控制器 - 认证系统升级
// | 功能说明：基于新RBAC权限体系的用户认证控制器
// | 新架构：集成AuthenticationService的完整认证功能
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\common\service\AuthenticationService;
use think\facade\Env;
use think\facade\View;
use think\facade\Config;

/**
 * 认证控制器
 * 
 * 基于新RBAC权限体系的用户认证控制器
 * 提供登录、登出、权限验证等功能
 * 
 * @package app\admin\controller
 * @version 3.0.0
 * @since 2025-01-21
 */
class AuthController extends AdminController
{
    /**
     * 认证服务
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * 控制器初始化
     */
    protected function initialize()
    {
        parent::initialize();
        $this->authService = new AuthenticationService();
    }

    /**
     * 登录页面
     * 
     * @return string|\think\Response
     */
    public function login()
    {
        // 如果已经登录，跳转到首页
        if ($this->authService->isLoggedIn()) {
            $this->redirect($this->getIndexUrl());
        }

        // 处理登录请求
        if ($this->request->isPost()) {
            return $this->handleLogin();
        }

        // 显示登录页面
        return $this->showLoginPage();
    }

    /**
     * 处理登录请求
     * 
     * @return \think\Response
     */
    protected function handleLogin()
    {
        try {
            // 获取POST数据
            $post = $this->request->post();
            
            // 数据验证
            $this->validateLoginData($post);

            // 获取设备指纹
            $deviceFingerprint = $this->getDeviceFingerprint();

            // 执行登录
            $result = $this->authService->login(
                $post['username'],
                $post['password'],
                $deviceFingerprint,
                !empty($post['keep_login'])
            );

            if ($result['success']) {
                // 登录成功，记录日志
                $this->logLoginSuccess($post['username'], $deviceFingerprint);
                
                // 返回成功响应
                $this->success($result['message'], [], $this->getIndexUrl());
            } else {
                // 登录失败，记录日志
                $this->logLoginFailure($post['username'], $result['message']);
                
                // 返回失败响应
                $this->error($result['message']);
            }

        } catch (\Exception $e) {
            // 异常处理
            $this->logLoginError($e->getMessage());
            $this->error('登录异常：' . $e->getMessage());
        }
    }

    /**
     * 验证登录数据
     * 
     * @param array $data 登录数据
     * @throws \think\exception\ValidateException
     */
    protected function validateLoginData($data)
    {
        $rule = [
            'username|用户名' => 'require|length:3,50',
            'password|密码' => 'require|length:6,255',
            'keep_login|保持登录' => 'boolean',
        ];

        // 如果启用验证码，添加验证码验证
        if (Env::get('easyadmin.captcha', 1)) {
            $rule['captcha|验证码'] = 'require|captcha';
        }

        $this->validate($data, $rule);
    }

    /**
     * 获取设备指纹
     * 
     * @return string 设备指纹
     */
    protected function getDeviceFingerprint()
    {
        // 基于用户代理、IP地址等生成设备指纹
        $userAgent = $this->request->header('User-Agent', '');
        $ip = $this->request->ip();
        $acceptLanguage = $this->request->header('Accept-Language', '');
        
        return md5($userAgent . $ip . $acceptLanguage);
    }

    /**
     * 显示登录页面
     * 
     * @return string
     */
    protected function showLoginPage()
    {
        // 获取配置
        $captcha = Env::get('easyadmin.captcha', 1);
        $adminTitle = Config::get('app.admin_title', '管理后台');
        
        // 分配模板变量
        View::assign([
            'captcha' => $captcha,
            'admin_title' => $adminTitle,
        ]);

        return View::fetch('login');
    }

    /**
     * 用户登出
     * 
     * @return \think\Response
     */
    public function logout()
    {
        try {
            // 获取当前用户信息（用于日志）
            $agent = $this->authService->getCurrentAgent();
            $username = $agent ? $agent->username : '未知用户';

            // 执行登出
            $this->authService->logout();

            // 记录登出日志
            $this->logLogoutSuccess($username);

            // 返回成功响应
            if ($this->request->isAjax()) {
                $this->success('登出成功', [], $this->getLoginUrl());
            } else {
                $this->redirect($this->getLoginUrl());
            }

        } catch (\Exception $e) {
            $this->logLogoutError($e->getMessage());
            $this->error('登出异常：' . $e->getMessage());
        }
    }

    /**
     * 获取当前用户信息
     * 
     * @return \think\Response
     */
    public function userInfo()
    {
        try {
            $agent = $this->authService->getCurrentAgent();
            
            if (!$agent) {
                $this->error('用户未登录');
            }

            // 获取用户角色和权限
            $roles = $this->authService->getAgentRoles();
            $permissions = $this->authService->getAgentPermissions();

            $data = [
                'id' => $agent->id,
                'username' => $agent->username,
                'email' => $agent->email,
                'phone' => $agent->phone,
                'role_type' => $agent->role_type,
                'role_type_text' => $agent->role_type_text,
                'status' => $agent->status,
                'status_text' => $agent->status_text,
                'is_super_admin' => $agent->isSuperAdmin(),
                'last_login_at' => $agent->last_login_at,
                'roles' => $roles,
                'permissions' => $permissions,
            ];

            $this->success('获取成功', $data);

        } catch (\Exception $e) {
            $this->error('获取用户信息失败：' . $e->getMessage());
        }
    }

    /**
     * 检查权限
     * 
     * @return \think\Response
     */
    public function checkPermission()
    {
        try {
            $permission = $this->request->param('permission', '');
            $module = $this->request->param('module', '');

            if (empty($permission)) {
                $this->error('权限标识不能为空');
            }

            $hasPermission = $this->authService->checkPermission($permission, $module);

            $this->success('检查完成', [
                'permission' => $permission,
                'module' => $module,
                'has_permission' => $hasPermission,
            ]);

        } catch (\Exception $e) {
            $this->error('权限检查失败：' . $e->getMessage());
        }
    }

    /**
     * 获取首页URL
     * 
     * @return string
     */
    protected function getIndexUrl()
    {
        $adminAlias = Config::get('app.admin_alias_name', 'admin');
        return __url("@{$adminAlias}/index/index");
    }

    /**
     * 获取登录页URL
     * 
     * @return string
     */
    protected function getLoginUrl()
    {
        $adminAlias = Config::get('app.admin_alias_name', 'admin');
        return __url("@{$adminAlias}/auth/login");
    }

    /**
     * 记录登录成功日志
     * 
     * @param string $username 用户名
     * @param string $deviceFingerprint 设备指纹
     */
    protected function logLoginSuccess($username, $deviceFingerprint)
    {
        $this->writeLog('login_success', [
            'username' => $username,
            'ip' => $this->request->ip(),
            'user_agent' => $this->request->header('User-Agent'),
            'device_fingerprint' => $deviceFingerprint,
        ]);
    }

    /**
     * 记录登录失败日志
     * 
     * @param string $username 用户名
     * @param string $reason 失败原因
     */
    protected function logLoginFailure($username, $reason)
    {
        $this->writeLog('login_failure', [
            'username' => $username,
            'reason' => $reason,
            'ip' => $this->request->ip(),
            'user_agent' => $this->request->header('User-Agent'),
        ]);
    }

    /**
     * 记录登录异常日志
     * 
     * @param string $error 异常信息
     */
    protected function logLoginError($error)
    {
        $this->writeLog('login_error', [
            'error' => $error,
            'ip' => $this->request->ip(),
            'user_agent' => $this->request->header('User-Agent'),
        ]);
    }

    /**
     * 记录登出成功日志
     * 
     * @param string $username 用户名
     */
    protected function logLogoutSuccess($username)
    {
        $this->writeLog('logout_success', [
            'username' => $username,
            'ip' => $this->request->ip(),
        ]);
    }

    /**
     * 记录登出异常日志
     * 
     * @param string $error 异常信息
     */
    protected function logLogoutError($error)
    {
        $this->writeLog('logout_error', [
            'error' => $error,
            'ip' => $this->request->ip(),
        ]);
    }

    /**
     * 写入日志
     * 
     * @param string $type 日志类型
     * @param array $data 日志数据
     */
    protected function writeLog($type, $data)
    {
        try {
            $logDir = runtime_path() . 'log/auth/';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            $logFile = $logDir . 'auth_' . date('Ymd') . '.log';
            $logData = [
                'time' => date('Y-m-d H:i:s'),
                'type' => $type,
                'data' => $data,
            ];

            file_put_contents($logFile, json_encode($logData, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);

        } catch (\Exception $e) {
            // 日志写入失败时不抛出异常，避免影响主流程
        }
    }
}
