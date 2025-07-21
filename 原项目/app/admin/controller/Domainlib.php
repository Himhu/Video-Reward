<?php
// +----------------------------------------------------------------------
// | 控制器名称：中转域名控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统中转域名资源
// | 包含操作：域名列表、添加域名、编辑域名、回收域名等
// | 主要职责：提供系统域名资源的分配和管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\DomainRule;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="中转域名")
 */
class Domainlib extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\DomainLib();
        $this->rule = new \app\admin\model\DomainRule();

        $this->assign('getStatusList', $this->model->getStatusList());

    }


    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
             $id = $this->request->session('admin.auth_ids');
        
            list($page, $limit, $where) = $this->buildTableParames();
               
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->with(['Admins'])
                ->page($page, $limit)
                ->order($this->sort)
                ->select();

            foreach ($list as &$item)
            {
                $item['username'] = "asdasdas";
            }
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
     * @NodeAnotation(title="回收")
     */
    public function recycling()
    {
        $id = $this->request->param('id');
        $list = $this->model->whereIn('id',$id)->select()->toArray();
        $arr = array();
        foreach($list as $k=>$v){
            array_pop($v);
            unset($v['id']);
            unset($v['uid'],$v['q_status']);
            $v['create_time'] = time();
            $arr[] = $v;
        }

        try{
            $res = $this->rule->insertAll($arr);
            $res = $this->model->whereIn('id',$id)->delete();
        } catch(\Exception $e) {
            $this->error($e->getMessage());
        }
//        $res = $this->model->whereIn('id',$id)->save(['uid' => '']);
        return $this->success("回收成功",$res);
    }



    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $username = $this->request->session('admin.username');
            $post['creator_id'] = $username;
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $this->model->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败:'.$e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $admin_user = new SystemAdmin();
        $admin_lists = $admin_user->where('status',1)->select()->toArray();
        $this->assign('admin_lists', $admin_lists);
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
            try {
                $save = $row->save($post);
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