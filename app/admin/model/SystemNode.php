<?php
// +----------------------------------------------------------------------
// | 模型名称：系统节点模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统权限节点数据
// | 数据表：system_node
// | 主要字段：node(节点名称)、title(节点标题)、type(节点类型)、is_auth(是否需要验证权限)
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemNode extends TimeModel
{

    public function getNodeTreeList()
    {
        $list = $this->select()->toArray();
        $list = $this->buildNodeTree($list);
        return $list;
    }

    protected function buildNodeTree($list)
    {
        $newList = [];
        $repeatString = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        foreach ($list as $vo) {
            if ($vo['type'] == 1) {
                $newList[] = $vo;
                foreach ($list as $v) {
                    if ($v['type'] == 2 && strpos($v['node'], $vo['node'] . '/') !== false) {
                        $v['node'] = "{$repeatString}├{$repeatString}" . $v['node'];
                        $newList[] = $v;
                    }
                }
            }
        }
        return $newList;
    }


}