<?php
// +----------------------------------------------------------------------
// | 服务名称：触发器服务
// +----------------------------------------------------------------------
// | 服务功能：提供系统缓存更新触发服务
// | 主要方法：updateMenu - 更新菜单缓存，updateNode - 更新节点缓存，updateSysconfig - 更新系统设置缓存
// | 调用方式：TriggerService::updateXxx()
// +----------------------------------------------------------------------

namespace app\admin\service;


use think\facade\Cache;

class TriggerService
{

    /**
     * 更新菜单缓存
     * @param null $adminId
     * @return bool
     */
    public static function updateMenu($adminId = null)
    {
        if(empty($adminId)){
            Cache::tag('initAdmin')->clear();
        }else{
            Cache::delete('initAdmin_' . $adminId);
        }
        return true;
    }

    /**
     * 更新节点缓存
     * @param null $adminId
     * @return bool
     */
    public static function updateNode($adminId = null)
    {
        if(empty($adminId)){
            Cache::tag('authNode')->clear();
        }else{
            Cache::delete('allAuthNode_' . $adminId);
        }
        return true;
    }

    /**
     * 更新系统设置缓存
     * @return bool
     */
    public static function updateSysconfig()
    {
        Cache::tag('sysconfig')->clear();
        return true;
    }

}