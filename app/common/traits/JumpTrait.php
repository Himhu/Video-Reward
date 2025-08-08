<?php

// +----------------------------------------------------------------------
// | 跳转响应Trait
// +----------------------------------------------------------------------
// | 提供控制器跳转和响应方法，支持智能判断请求类型
// | 浏览器访问：重定向跳转 | AJAX请求：JSON响应
// +----------------------------------------------------------------------

namespace app\common\traits;

use think\exception\HttpResponseException;
use think\Response;

/**
 * 跳转响应Trait
 * 提供成功/错误跳转、JSON响应、重定向等功能
 */
trait JumpTrait
{
    /**
     * 判断是否为浏览器直接访问
     * @return bool
     */
    private function isBrowserDirectAccess()
    {
        $request = request();
        $isAjax = $request->isAjax() || $request->isJson() || $request->isPost();
        $acceptHeader = $request->header('accept', '');
        $isBrowserRequest = strpos($acceptHeader, 'text/html') !== false;

        return !$isAjax && $isBrowserRequest;
    }

    /**
     * 操作成功跳转
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的 URL 地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的 Header 信息
     * @throws HttpResponseException
     */
    protected function success($msg = '', $data = '', $url = null, $wait = 3, array $header = [])
    {
        // 浏览器直接访问且有跳转URL，则重定向
        if ($this->isBrowserDirectAccess() && !empty($url)) {
            $this->redirect($url);
            return;
        }

        // 返回JSON响应
        $result = [
            'code' => 1,
            'msg'  => $msg ?: '操作成功',
            'data' => $data,
            'url'  => $url ?: '',
            'wait' => $wait,
        ];

        $response = Response::create($result, 'json')->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 操作错误跳转
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的 URL 地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的 Header 信息
     * @throws HttpResponseException
     */
    protected function error($msg = '', $data = '', $url = null, $wait = 3, array $header = [])
    {
        // 浏览器直接访问且有跳转URL，则重定向
        if ($this->isBrowserDirectAccess() && !empty($url)) {
            $this->redirect($url);
            return;
        }

        // 返回JSON响应
        $result = [
            'code' => 0,
            'msg'  => $msg ?: '操作失败',
            'data' => $data,
            'url'  => $url ?: '',
            'wait' => $wait,
        ];

        $response = Response::create($result, 'json')->header($header);
        throw new HttpResponseException($response);
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param int $code 返回的 code
     * @param mixed $msg 提示信息
     * @param string $type 返回数据格式
     * @param array $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function result($data, $code = 0, $msg = '', $type = '', array $header = [])
    {
        $result   = [
            'code' => $code,
            'msg'  => $msg,
            'time' => time(),
            'data' => $data,
        ];
        $type     = $type ?: $this->getResponseType();
        $response = Response::create($result, $type)->header($header);

        throw new HttpResponseException($response);
    }

    /**
     * URL重定向
     * @param string $url 跳转的URL地址
     * @param array|int $params 其它URL参数
     * @param int $code HTTP状态码
     */
    protected function redirect($url, $params = [], $code = 302)
    {
        $response = Response::create($url, 'redirect', $code);
        throw new HttpResponseException($response);
    }

    /**
     * 获取响应输出类型
     * @return string
     */
    protected function getResponseType()
    {
        return $this->isBrowserDirectAccess() ? 'html' : 'json';
    }
}
