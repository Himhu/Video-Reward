<?php

// +----------------------------------------------------------------------
// | 配置名称：跳转响应Trait
// +----------------------------------------------------------------------
// | 配置功能：提供控制器跳转和响应方法
// | 主要配置：成功跳转、错误跳转、结果返回、重定向等方法
// | 当前配置：支持JSON响应、URL重定向、错误日志记录等功能
// +----------------------------------------------------------------------

namespace app\common\traits;

use think\exception\HttpResponseException;
use think\Response;

/**
 * Trait JumpTrait
 * @package app\common\traits
 */
trait JumpTrait
{

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的 URL 地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function success($msg = '', $data = '', $url = null, $wait = 3, array $header = [])
    {
        // 记录启动日志
        $log_file = runtime_path() . 'log/jump_debug_' . date('Ymd') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - success方法被调用: ' . $msg . PHP_EOL, FILE_APPEND);
        
        try {
            // 简化处理，直接返回JSON响应
            $result = [
                'code' => 1,
                'msg'  => $msg ?: '操作成功',
                'data' => $data,
                'url'  => $url ?: '',
                'wait' => $wait,
            ];
            
            // 记录返回数据
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - 返回数据: ' . json_encode($result, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            
            $response = \think\Response::create($result, 'json')->header($header);
            
            // 正确抛出异常
            throw new HttpResponseException($response);
        } catch (HttpResponseException $e) {
            // 直接再次抛出，让框架处理
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - HttpResponseException被正确抛出' . PHP_EOL, FILE_APPEND);
            throw $e;
        } catch (\Exception $e) {
            // 记录其他异常
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - success方法异常: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
            // 转换为HttpResponseException
            $response = \think\Response::create(['code' => 0, 'msg' => '操作失败: ' . $e->getMessage()], 'json')->header($header);
            throw new HttpResponseException($response);
        }
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param string $url 跳转的 URL 地址
     * @param int $wait 跳转等待时间
     * @param array $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function error($msg = '', $data = '', $url = null, $wait = 3, array $header = [])
    {
        // 记录启动日志
        $log_file = runtime_path() . 'log/jump_debug_' . date('Ymd') . '.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - error方法被调用: ' . $msg . PHP_EOL, FILE_APPEND);
        
        try {
            // 简化处理，直接返回JSON响应
            $result = [
                'code' => 0,
                'msg'  => $msg ?: '操作失败',
                'data' => $data,
                'url'  => $url ?: '',
                'wait' => $wait,
            ];
            
            // 记录返回数据
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - 返回数据: ' . json_encode($result, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            
            $response = \think\Response::create($result, 'json')->header($header);
            
            // 正确抛出异常
            throw new HttpResponseException($response);
        } catch (HttpResponseException $e) {
            // 直接再次抛出，让框架处理
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - HttpResponseException被正确抛出' . PHP_EOL, FILE_APPEND);
            throw $e;
        } catch (\Exception $e) {
            // 记录其他异常
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - error方法异常: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
            // 转换为HttpResponseException
            $response = \think\Response::create(['code' => 0, 'msg' => '操作失败: ' . $e->getMessage()], 'json')->header($header);
            throw new HttpResponseException($response);
        }
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
     * URL 重定向
     * @access protected
     * @param string $url 跳转的 URL 表达式
     * @param array|int $params 其它 URL 参数
     * @param int $code http code
     * @return void
     */
    protected function redirect($url, $params = [], $code = 302)
    {
        $response = \think\Response::create($url, 'redirect', $code);
        throw new \think\exception\HttpResponseException($response);
    }

    /**
     * 获取当前的 response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return (request()->isJson() || request()->isAjax() || request()->isPost()) ? 'json' : 'html';
    }
}
