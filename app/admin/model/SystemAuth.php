<?php
// +----------------------------------------------------------------------
// | 模型名称：系统角色权限模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统角色权限数据
// | 数据表：system_auth
// | 主要字段：title(角色名称)、remark(备注)、status(状态)、sort(排序)
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemAuth extends TimeModel
{

    protected $deleteTime = 'delete_time';

    /**
     * 根据角色ID获取授权节点
     * @param $authId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAuthorizeNodeListByAdminId($authId)
    {
        $checkNodeList = (new SystemAuthNode())
            ->where('auth_id', $authId)
            ->column('node_id');
        $systemNode = new SystemNode();
        $nodelList = $systemNode
            ->where('is_auth', 1)
            ->field('id,node,title,type,is_auth')
            ->select()
            ->toArray();
        $newNodeList = [];
        foreach ($nodelList as $vo) {
            if ($vo['type'] == 1) {
                $vo = array_merge($vo, ['field' => 'node', 'spread' => true]);
                $vo['checked'] = false;
                $vo['title'] = "{$vo['title']}【{$vo['node']}】";
                $children = [];
                foreach ($nodelList as $v) {
                    if ($v['type'] == 2 && strpos($v['node'], $vo['node'] . '/') !== false) {
                        $v = array_merge($v, ['field' => 'node', 'spread' => true]);
                        $v['checked'] = in_array($v['id'], $checkNodeList) ? true : false;
                        $v['title'] = "{$v['title']}【{$v['node']}】";
                        $children[] = $v;
                    }
                }
                !empty($children) && $vo['children'] = $children;
                $newNodeList[] = $vo;
            }
        }
        return $newNodeList;
    }

}