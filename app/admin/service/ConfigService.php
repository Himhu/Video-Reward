<?php
// +----------------------------------------------------------------------
// | 服务名称：配置服务
// +----------------------------------------------------------------------
// | 服务功能：提供系统配置信息获取服务
// | 主要方法：getVersion - 获取系统版本号
// | 调用方式：ConfigService::getVersion()
// +----------------------------------------------------------------------

namespace app\admin\service;

use think\facade\Cache;

class ConfigService
{

    public static function getVersion()
    {
        $version = Cache('version');
        if (empty($version)) {
            $version = sysconfig('site', 'site_version');
            cache('site_version', $version);
            Cache::set('version', $version, 3600);
        }
        return $version;
    }

}