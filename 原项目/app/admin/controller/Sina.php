<?php
// +----------------------------------------------------------------------
// | 控制器名称：新浪接口控制器
// +----------------------------------------------------------------------
// | 控制器功能：处理与新浪相关的接口调用和数据交互
// | 包含操作：新浪接口调用、数据同步、回调处理等
// | 主要职责：提供与新浪平台的集成功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\service\Arr;
use think\facade\Cache;

/**
 * 新浪短链接接口类
 */
class Sina
{
    /**
     * 生成新浪短链接
     * @param string $url 要转换的URL
     * @return array 返回结果数组，包含code和data
     */
    public function index($url)
    {
        // 获取新浪cookie配置
        $cookie = sysconfig('short', 'sina');
        if (empty($cookie)) {
            return ['code' => 0, 'msg' => '未配置新浪cookie'];
        }
        
        try {
            // 缓存键，避免频繁请求
            $cacheKey = 'sina_short_url_' . md5($url);
            $cacheResult = Cache::get($cacheKey);
            
            if (!empty($cacheResult)) {
                return ['code' => 1, 'data' => $cacheResult];
            }
            
            // 构建请求参数
            $postData = [
                'url_long' => $url
            ];
            
            // 发起请求
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.weibo.com/2/short_url/shorten.json');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode != 200) {
                return ['code' => 0, 'msg' => '请求失败，HTTP状态码：' . $httpCode];
            }
            
            $result = json_decode($response, true);
            
            if (empty($result) || !isset($result['urls'][0]['url_short'])) {
                return ['code' => 0, 'msg' => '解析响应失败：' . $response];
            }
            
            $shortUrl = $result['urls'][0]['url_short'];
            
            // 缓存结果，有效期1天
            Cache::set($cacheKey, ['short_url' => $shortUrl], 86400);
            
            return ['code' => 1, 'data' => ['short_url' => $shortUrl]];
            
        } catch (\Exception $e) {
            return ['code' => 0, 'msg' => '生成短链接异常：' . $e->getMessage()];
        }
    }
}
