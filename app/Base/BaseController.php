<?php
// +----------------------------------------------------------------------
// | Video-Reward 基础控制器
// +----------------------------------------------------------------------
// | 为所有控制器提供统一的基础功能和标准化响应方法
// +----------------------------------------------------------------------
// | 设计原则：最小化实现，渐进式扩展，零影响现有功能
// +----------------------------------------------------------------------

namespace app\Base;

use think\App;
use think\Request;
use think\Response;

/**
 * 基础控制器类
 * 
 * 为Video-Reward项目的所有控制器提供统一的基础功能
 * 包含标准化的响应方法和基础的请求处理能力
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var Request
     */
    protected $request;

    /**
     * 应用实例
     * @var App
     */
    protected $app;

    /**
     * 构造方法
     * 
     * @param App $app 应用实例
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    /**
     * 初始化方法
     * 
     * 子类可以重写此方法进行自定义初始化
     */
    protected function initialize()
    {
        // 子类可以重写此方法
    }

    /**
     * 成功响应
     * 
     * @param mixed $data 响应数据
     * @param string $message 响应消息
     * @param int $code 响应代码
     * @return Response
     */
    protected function success($data = null, $message = 'success', $code = 200)
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ];

        return json($response);
    }

    /**
     * 错误响应
     * 
     * @param string $message 错误消息
     * @param int $code 错误代码
     * @param mixed $data 额外数据
     * @return Response
     */
    protected function error($message = 'error', $code = 400, $data = null)
    {
        $response = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time()
        ];

        return json($response);
    }

    /**
     * 分页响应
     * 
     * @param mixed $data 分页数据
     * @param int $total 总数
     * @param int $page 当前页
     * @param int $limit 每页数量
     * @return Response
     */
    protected function paginate($data, $total, $page, $limit)
    {
        $response = [
            'code' => 200,
            'message' => 'success',
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'pages' => ceil($total / $limit)
            ],
            'timestamp' => time()
        ];

        return json($response);
    }

    /**
     * 安全获取请求参数
     * 
     * @param string $key 参数名
     * @param mixed $default 默认值
     * @param string $filter 过滤器
     * @return mixed
     */
    protected function getParam($key, $default = null, $filter = '')
    {
        return $this->request->param($key, $default, $filter);
    }

    /**
     * 获取POST参数
     * 
     * @param string $key 参数名
     * @param mixed $default 默认值
     * @param string $filter 过滤器
     * @return mixed
     */
    protected function getPost($key = '', $default = null, $filter = '')
    {
        return $this->request->post($key, $default, $filter);
    }

    /**
     * 获取GET参数
     * 
     * @param string $key 参数名
     * @param mixed $default 默认值
     * @param string $filter 过滤器
     * @return mixed
     */
    protected function getGet($key = '', $default = null, $filter = '')
    {
        return $this->request->get($key, $default, $filter);
    }

    /**
     * 检查是否为AJAX请求
     * 
     * @return bool
     */
    protected function isAjax()
    {
        return $this->request->isAjax();
    }

    /**
     * 检查是否为POST请求
     * 
     * @return bool
     */
    protected function isPost()
    {
        return $this->request->isPost();
    }

    /**
     * 检查是否为GET请求
     * 
     * @return bool
     */
    protected function isGet()
    {
        return $this->request->isGet();
    }

    /**
     * 获取客户端IP地址
     * 
     * @return string
     */
    protected function getClientIp()
    {
        return $this->request->ip();
    }

    /**
     * 重定向
     * 
     * @param string $url 重定向URL
     * @param int $code HTTP状态码
     * @return Response
     */
    protected function redirect($url, $code = 302)
    {
        return redirect($url, $code);
    }

    /**
     * 渲染视图
     * 
     * @param string $template 模板名称
     * @param array $vars 模板变量
     * @return Response
     */
    protected function view($template = '', $vars = [])
    {
        return view($template, $vars);
    }

    /**
     * 分配模板变量
     * 
     * @param string|array $name 变量名或变量数组
     * @param mixed $value 变量值
     * @return $this
     */
    protected function assign($name, $value = '')
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->app->view->assign($key, $val);
            }
        } else {
            $this->app->view->assign($name, $value);
        }
        
        return $this;
    }
}
