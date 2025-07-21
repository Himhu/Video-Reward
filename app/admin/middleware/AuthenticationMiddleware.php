<?php

// +----------------------------------------------------------------------
// | 认证中间件 - 新权限管理系统
// +----------------------------------------------------------------------
// | 创建时间：2025-01-21 - 新建认证中间件 - 认证系统升级
// | 功能说明：基于新RBAC权限体系的用户认证和权限验证中间件
// | 新架构：集成AuthenticationService的完整认证和权限验证
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\middleware;

use app\common\service\AuthenticationService;
use app\common\traits\JumpTrait;
use think\Request;
use think\Response;
use think\facade\Config;
use think\facade\Env;

/**
 * 认证中间件
 * 
 * 基于新RBAC权限体系的用户认证和权限验证中间件
 * 支持登录验证、权限验证、模块权限检查等功能
 * 
 * @package app\admin\middleware
 * @version 3.0.0
 * @since 2025-01-21
 */
class AuthenticationMiddleware
{
    use JumpTrait;

    /**
     * 认证服务
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * 免登录控制器
     * @var array
     */
    protected $noLoginControllers = [
        'login',
        'captcha',
    ];

    /**
     * 免登录节点
     * @var array
     */
    protected $noLoginNodes = [
        'admin/login/index',
        'admin/login/out',
        'admin/captcha/index',
    ];

    /**
     * 免权限验证控制器
     * @var array
     */
    protected $noAuthControllers = [
        'login',
        'index',
        'captcha',
    ];

    /**
     * 免权限验证节点
     * @var array
     */
    protected $noAuthNodes = [
        'admin/login/index',
        'admin/login/out',
        'admin/index/index',
        'admin/index/welcome',
        'admin/captcha/index',
    ];

    /**
     * 中间件处理
     * 
     * @param Request $request 请求对象
     * @param \Closure $next 下一个中间件
     * @return Response 响应对象
     */
    public function handle(Request $request, \Closure $next)
    {
        // 初始化认证服务
        $this->initAuthService($request);

        // 获取当前控制器和节点
        $currentController = $this->getCurrentController($request);
        $currentNode = $this->authService->getCurrentNode();

        // 验证登录状态
        $this->checkLogin($currentController, $currentNode);

        // 验证权限
        $this->checkPermission($request, $currentController, $currentNode);

        return $next($request);
    }

    /**
     * 初始化认证服务
     * 
     * @param Request $request 请求对象
     * @return void
     */
    protected function initAuthService($request)
    {
        // 从配置或会话中获取当前代理ID
        $agentId = session('admin.id');
        $this->authService = new AuthenticationService($agentId);

        // 从配置中加载免验证列表
        $adminConfig = Config::get('admin', []);
        
        if (isset($adminConfig['no_login_controller'])) {
            $this->noLoginControllers = array_merge($this->noLoginControllers, $adminConfig['no_login_controller']);
        }
        
        if (isset($adminConfig['no_login_node'])) {
            $this->noLoginNodes = array_merge($this->noLoginNodes, $adminConfig['no_login_node']);
        }
        
        if (isset($adminConfig['no_auth_controller'])) {
            $this->noAuthControllers = array_merge($this->noAuthControllers, $adminConfig['no_auth_controller']);
        }
        
        if (isset($adminConfig['no_auth_node'])) {
            $this->noAuthNodes = array_merge($this->noAuthNodes, $adminConfig['no_auth_node']);
        }
    }

    /**
     * 获取当前控制器名称
     * 
     * @param Request $request 请求对象
     * @return string 控制器名称
     */
    protected function getCurrentController($request)
    {
        return parse_name($request->controller());
    }

    /**
     * 检查登录状态
     * 
     * @param string $currentController 当前控制器
     * @param string $currentNode 当前节点
     * @return void
     */
    protected function checkLogin($currentController, $currentNode)
    {
        // 检查是否需要登录验证
        if ($this->needLoginCheck($currentController, $currentNode)) {
            
            // 检查登录状态
            if (!$this->authService->isLoggedIn()) {
                $this->redirectToLogin('请先登录后台');
            }

            // 检查登录是否过期（在AuthenticationService中已处理）
            // 如果过期，isLoggedIn()会返回false并自动清除会话
        }
    }

    /**
     * 检查是否需要登录验证
     * 
     * @param string $currentController 当前控制器
     * @param string $currentNode 当前节点
     * @return bool 是否需要登录验证
     */
    protected function needLoginCheck($currentController, $currentNode)
    {
        // 检查控制器是否在免登录列表中
        if (in_array($currentController, $this->noLoginControllers)) {
            return false;
        }

        // 检查节点是否在免登录列表中
        if (in_array($currentNode, $this->noLoginNodes)) {
            return false;
        }

        return true;
    }

    /**
     * 检查权限
     * 
     * @param Request $request 请求对象
     * @param string $currentController 当前控制器
     * @param string $currentNode 当前节点
     * @return void
     */
    protected function checkPermission($request, $currentController, $currentNode)
    {
        // 检查是否需要权限验证
        if ($this->needPermissionCheck($currentController, $currentNode)) {
            
            // 执行权限验证
            if (!$this->authService->checkNode($currentNode)) {
                $this->error('无权限访问');
            }

            // 检查是否为演示环境
            $this->checkDemoEnvironment($request);
        }
    }

    /**
     * 检查是否需要权限验证
     * 
     * @param string $currentController 当前控制器
     * @param string $currentNode 当前节点
     * @return bool 是否需要权限验证
     */
    protected function needPermissionCheck($currentController, $currentNode)
    {
        // 检查控制器是否在免权限验证列表中
        if (in_array($currentController, $this->noAuthControllers)) {
            return false;
        }

        // 检查节点是否在免权限验证列表中
        if (in_array($currentNode, $this->noAuthNodes)) {
            return false;
        }

        return true;
    }

    /**
     * 检查演示环境
     * 
     * @param Request $request 请求对象
     * @return void
     */
    protected function checkDemoEnvironment($request)
    {
        // 判断是否为演示环境
        if (Env::get('easyadmin.is_demo', false) && $request->isPost()) {
            $this->error('演示环境下不允许修改');
        }
    }

    /**
     * 重定向到登录页面
     * 
     * @param string $message 提示信息
     * @return void
     */
    protected function redirectToLogin($message = '请先登录')
    {
        $loginUrl = $this->getLoginUrl();
        $this->error($message, [], $loginUrl);
    }

    /**
     * 获取登录URL
     * 
     * @return string 登录URL
     */
    protected function getLoginUrl()
    {
        // 获取管理后台别名
        $adminAlias = Config::get('app.admin_alias_name', 'admin');
        return __url("@{$adminAlias}/login/index");
    }

    /**
     * 权限验证失败处理
     * 
     * @param string $message 错误信息
     * @param array $data 附加数据
     * @param string $url 跳转URL
     * @return void
     */
    protected function error($message = '操作失败', $data = [], $url = '')
    {
        // 如果是AJAX请求，返回JSON
        if (request()->isAjax()) {
            $result = [
                'code' => 0,
                'msg' => $message,
                'data' => $data,
                'url' => $url,
            ];
            
            $response = Response::create($result, 'json');
            throw new \think\exception\HttpResponseException($response);
        }

        // 普通请求，跳转页面
        if (!empty($url)) {
            $response = Response::create('', 'redirect', 302)->header('Location', $url);
        } else {
            $response = Response::create($message, 'html');
        }
        
        throw new \think\exception\HttpResponseException($response);
    }

    /**
     * 获取认证服务实例
     * 
     * @return AuthenticationService 认证服务
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * 设置免登录控制器
     * 
     * @param array $controllers 控制器列表
     * @return $this
     */
    public function setNoLoginControllers(array $controllers)
    {
        $this->noLoginControllers = $controllers;
        return $this;
    }

    /**
     * 设置免登录节点
     * 
     * @param array $nodes 节点列表
     * @return $this
     */
    public function setNoLoginNodes(array $nodes)
    {
        $this->noLoginNodes = $nodes;
        return $this;
    }

    /**
     * 设置免权限验证控制器
     * 
     * @param array $controllers 控制器列表
     * @return $this
     */
    public function setNoAuthControllers(array $controllers)
    {
        $this->noAuthControllers = $controllers;
        return $this;
    }

    /**
     * 设置免权限验证节点
     * 
     * @param array $nodes 节点列表
     * @return $this
     */
    public function setNoAuthNodes(array $nodes)
    {
        $this->noAuthNodes = $nodes;
        return $this;
    }
}
