<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
     \think\middleware\SessionInit::class,
     
    // IP地址URL修复中间件
    function ($request, \Closure $next) {
        // 调用fixIpUrlRequest函数处理IP地址URL问题
        $response = fixIpUrlRequest($request);
        if ($response) {
            return $response;
        }
        return $next($request);
    }
];
