<?php

// +----------------------------------------------------------------------
// | 配置名称：权限验证服务
// +----------------------------------------------------------------------
// | 配置功能：提供系统权限验证和节点管理功能
// | 主要配置：权限表、节点表、用户表等相关配置
// | 当前配置：支持节点检查、权限验证、节点解析等功能
// +----------------------------------------------------------------------

namespace app\common\service;

use app\common\constants\AdminConstant;
use EasyAdmin\tool\CommonTool;
use think\facade\Db;

/**
 * 权限验证服务
 * Class AuthService
 * @package app\common\service
 */
class AuthService
{

    /**
     * 用户ID
     * @var null
     */
    protected $adminId = null;

    /**
     * 默认配置
     * @var array
     */
    protected $config = [
        'auth_on'          => true,              // 权限开关
        'system_admin'     => 'system_admin',    // 用户表
        'system_auth'      => 'system_auth',     // 权限表
        'system_node'      => 'system_node',     // 节点表
        'system_auth_node' => 'system_auth_node',// 权限-节点表
    ];

    /***
     * 构造方法
     * AuthService constructor.
     * @param null $adminId
     */
    public function __construct($adminId = null)
    {
        $this->adminId = $adminId;
        return $this;
    }

    /**
     * 检测检测权限
     * @param null $node
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkNode($node = null , $nodeArr = null)
    {
        // 判断是否为超级管理员
        if ($this->adminId == AdminConstant::SUPER_ADMIN_ID) {
            return true;
        }
        // 判断权限验证开关
        if ($this->config['auth_on'] == false) {
            return true;
        }
        // 判断是否需要获取当前节点
        if (empty($node)) {
            $node = $this->getCurrentNode();
        } else {
            $node = $this->parseNodeStr($node);
        }
        // 判断是否加入节点控制，优先获取缓存信息
        /*$nodeInfo = Db::name($this->config['system_node'])
            ->where(['node' => $node])
            ->find();
        if (empty($nodeInfo)) {
            return false;
        }
        if ($nodeInfo['is_auth'] == 0) {
            return true;
        }*/
        // 用户验证，优先获取缓存信息
        /*$adminInfo = Db::name($this->config['system_admin'])
            ->where('id', $this->adminId)
            ->find();
        if (empty($adminInfo) || $adminInfo['status'] != 1 || empty($adminInfo['auth_ids'])) {
            return false;
        }*/
        // 判断该节点是否允许访问
        if($nodeArr)
        {
            $allNode = $nodeArr;
        }
        else
        {
            $allNode = $this->getAdminNode();
        }
        if (in_array($node, $allNode)) {
            return true;
        }
        return false;
    }

    /**
     * 获取当前节点
     * @return string
     */
    public function getCurrentNode()
    {
        $node = $this->parseNodeStr(request()->controller() . '/' . request()->action());
        return $node;
    }

    /**
     * 获取当前管理员所有节点
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminNode()
    {
        $nodeList = [];
        if($this->adminId == 1)
        {
            return $nodeList;
        }
        $adminInfo = Db::name($this->config['system_admin'])
            ->where([
                'id'     => $this->adminId,
                'status' => 1,
            ])->find();
        if (!empty($adminInfo)) {
            // 处理auth_ids字符串转数组
            // 修复：数据库中auth_ids存储为字符串格式（如"7"或"1,2,7"），需要转换为数组
            $authIds = [];
            if (!empty($adminInfo['auth_ids'])) {
                // 将字符串按逗号分割并转换为整数数组，过滤掉无效值（如0、空字符串）
                $authIds = array_filter(array_map('intval', explode(',', $adminInfo['auth_ids'])));
            }

            // 如果没有有效的权限ID，返回空数组（避免后续SQL查询错误）
            if (empty($authIds)) {
                return [];
            }

            $buildAuthSql = Db::name($this->config['system_auth'])
                ->distinct(true)
                ->whereIn('id', $authIds)
                ->field('id')
                ->buildSql(true);
            $buildAuthNodeSql = Db::name($this->config['system_auth_node'])
                ->distinct(true)
                ->where("auth_id IN {$buildAuthSql}")
                ->field('node_id')
                ->buildSql(true);
            $nodeList = Db::name($this->config['system_node'])
                ->distinct(true)
                ->where("id IN {$buildAuthNodeSql}")
                ->column('node');
        }
        return $nodeList;
    }

    /**
     * 驼峰转下划线规则
     * @param string $node
     * @return string
     */
    public function parseNodeStr($node)
    {
        $array = explode('/', $node);
        foreach ($array as $key => $val) {
            if ($key == 0) {
                $val = explode('.', $val);
                foreach ($val as &$vo) {
                    $vo = CommonTool::humpToLine(lcfirst($vo));
                }
                $val = implode('.', $val);
                $array[$key] = $val;
            }
        }
        $node = implode('/', $array);
        return $node;
    }

}