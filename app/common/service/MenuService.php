<?php

// +----------------------------------------------------------------------
// | 配置名称：菜单服务
// +----------------------------------------------------------------------
// | 配置功能：提供系统菜单管理和生成功能
// | 主要配置：首页信息、菜单树构建、角色菜单权限等
// | 当前配置：支持根据用户权限生成菜单树、角色菜单过滤等功能
// +----------------------------------------------------------------------

namespace app\common\service;

use app\common\constants\MenuConstant;
use think\facade\Db;

class MenuService
{

    /**
     * 管理员ID
     * @var integer
     */
    protected $adminId;
    protected  $authServer = Null;

    public function __construct($adminId)
    {
        $this->adminId = $adminId;

        $this->authServer = (new AuthService($this->adminId));

        return $this;
    }

    /**
     * 获取首页信息
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getHomeInfo()
    {
        // 优先查找仪表盘菜单
        $data = Db::name('system_menu')
            ->field('title,icon,href')
            ->where("delete_time is null")
            ->where('href', 'index/welcome')
            ->where('status', 1)
            ->find();

        // 如果没找到仪表盘，则查找原有的HOME_PID菜单
        if (empty($data)) {
            $data = Db::name('system_menu')
                ->field('title,icon,href')
                ->where("delete_time is null")
                ->where('pid', MenuConstant::HOME_PID)
                ->find();
        }

        !empty($data) && $data['href'] = __url($data['href']);
        return $data;
    }

    /**
     * 获取后台菜单树信息
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMenuTree()
    {
        $keys = '';
        if(session('admin.auth_ids'))
        {
            $role = Db::name('system_auth')
                ->distinct(true)
                ->whereIn('id', session('admin.auth_ids'))
                ->find();
            $keys = $role['keys'];
        }

        $menuTreeList = $this->buildMenuChild(0, $this->getMenuData(),$this->authServer->getAdminNode() , $keys);
        return $menuTreeList;
    }

    private function buildMenuChild($pid, $menuList , $authNode , $role)
    {
        $adminHide = ["开始赚钱","财务管理","推广盒子","下级管理","推广链接"];
        $dailiHide = ["片库管理"];
        $treeList = [];

        foreach ($menuList as &$v) {

            if($role == "daili" && in_array($v['title'] , $dailiHide))
            {
                continue;
            }
            if($role == "admin" && in_array($v['title'] , $adminHide))
            {
                continue;
            }

            if($this->adminId == 1 && in_array($v['title'] , $adminHide))
            {
                continue;
            }
            $check = empty($v['href']) ? true : $this->authServer->checkNode($v['href'] , $authNode);
            !empty($v['href']) && $v['href'] = __url($v['href']);
            if ($pid == $v['pid'] && $check) {
                $node = $v;
                $child = $this->buildMenuChild($v['id'], $menuList , $authNode,$role);
                if (!empty($child)) {
                    $node['child'] = $child;
                }
                if (!empty($v['href']) || !empty($child)) {
                    $treeList[] = $node;
                }
            }
        }
        return $treeList;
    }

    /**
     * 获取所有菜单数据
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function getMenuData()
    {
        $menuData = Db::name('system_menu')
            ->field('id,pid,title,icon,href,target')
            ->where("delete_time is null")
            ->where([
                ['status', '=', '1'],
                ['pid', '<>', MenuConstant::HOME_PID],
            ])
            ->order([
                'sort' => 'desc',
                'id'   => 'asc',
            ])
            ->select();
        return $menuData;
    }

}