<?php
// +----------------------------------------------------------------------
// | 控制器名称：未结算提现控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理待审核的提现申请
// | 包含操作：未结算提现列表、提现申请审核、提现申请结算或拒绝等
// | 主要职责：处理系统中待处理的提现申请
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="未结算列表")
 */
class Outlayw extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Outlay();
        
        $this->assign('getStatusList', $this->model->getStatusList());

    }

    /**
     * @NodeAnotation(title="未结算列表")
     */
    public function index()
    {
        $this->assign('dpayCount',dpayCount(0));
        $this->assign('dpayMonet',dpayMonet(0));
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->where(['status' => 0])
                ->count();
            $list = $this->model
                ->where($where)
                ->where(['status' => 0])
                ->with(['Admins'])
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $admin_user = new SystemAdmin();
        $admin_lists = $admin_user->where('status',1)->select()->toArray();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            $arr = array();
            if(empty($post['status'])){
                return $this->error('请审核');
            }
            if($post['status'] == 1){
                //结算
                $arr['status'] = 1;
                $arr['end_time'] = time();

                //代理信息
                $user_arr = SystemAdmin::getUser($post['uid']);
                //提现信息
                $ti_xian = $this->model->where(['id' =>$id])->find()->toArray();
             
                if($user_arr['balance'] < $ti_xian['money']){
                    return $this->error('余额不足');
                }

                SystemAdmin::jmoney($ti_xian['money'],$post['uid'],'代理提现金额'.$ti_xian['money']);
            }elseif($post['status'] == 2){
                //拒绝
                $arr['status'] = 2;
                $arr['refuse_time'] = time();
                $arr['remark'] = $post['remark'];
            }

            try {
                $save = $row->save($arr);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        $this->assign('admin_lists', $admin_lists);
        return $this->fetch();
    }

    
}