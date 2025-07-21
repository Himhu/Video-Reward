<?php
// +----------------------------------------------------------------------
// | 控制器名称：落地域名控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统落地域名资源
// | 包含操作：域名列表、添加域名、批量添加域名、编辑域名、回收域名等
// | 主要职责：提供系统落地域名资源的分配和管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\DomainLib;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="落地域名")
 */
class Domainrule extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\DomainRule();
        $this->lib   = new \app\admin\model\DomainLib();

        $this->assign('getStatusList', $this->model->getStatusList());
        $this->assign('type', $this->model->getTypeList());

    }


    /**
     * @NodeAnotation(title="回收")
     */
    public function recycling()
    {
        $id = $this->request->param('id');


        $res = $this->model->whereIn('id', $id)->save(['uid' => '']);

        return $this->success("回收成功", $res);
    }


    /**
     * @NodeAnotation(title="批量添加域名")
     */
    public function piliang()
    {
   
        
//        if ($this->request->isPost())
//        {
//            set_time_limit(0);
//            $for    = explode("\n", $this->request->param('video_msg'));
//            $insert = [];
//
//            foreach ($for as $vo)
//            {
//                $data = [
//                    'domain'      => $vo,
//                    'status'      => 1,
//                    'creator_id'  => session('admin.username'),
//                    'create_time' => time(),
//                ];
//                array_push($insert, $data);
//            }
//            $this->model->insertAll($insert);
//            return $this->success('添加成功');
//        }
        $this->assign('d',$this->request->domain());
        $this->assign('f',id_encode($this->uid));
        return $this->fetch();

    }


    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            if (input('selectFields'))
            {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->count();
            $list  = $this->model
                ->where($where)
                ->with(['Admins'])
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
            $data  = [
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
     * @NodeAnotation(title="添加")
     */
    public function add()
    {

        if ($this->request->isAjax())
        {
            $post               = $this->request->post();
            $username           = $this->request->session('admin.username');
            $post['creator_id'] = $username;
            $rule               = [];
            $this->validate($post, $rule);

            set_time_limit(0);
            $for    = explode("\n", $this->request->param('video_msg'));
            $insert = [];

            foreach ($for as $vo)
            {
                $data = [
                    'domain'      => $vo,
                    'status'      => $post['status'],
                    'type'        => $post['type'],
                    'uid'         => $post['uid'],
                    'creator_id'  => session('admin.username'),
                    'create_time' => time(),
                ];
                array_push($insert, $data);
            }
            $save = $this->model->insertAll($insert);

            //print_r($post);die;
            try
            {
                // $save = $this->model->save($post);
            }
            catch (\Exception $e)
            {
                $this->error('保存失败:' . $e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        //代理信息
        $admin_user  = new SystemAdmin();
        $admin_lists = $admin_user->where('status', 1)->select()->toArray();
        $this->assign('admin_lists', $admin_lists);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row         = $this->model->find($id);
        $admin_user  = new SystemAdmin();
        $admin_lists = $admin_user->where('status', 1)->select()->toArray();
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax())
        {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try
            {
                $save = $row->save($post);
            }
            catch (\Exception $e)
            {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        $this->assign('admin_lists', $admin_lists);
        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="属性修改")
     */
    public function modify()
    {
        $post = $this->request->post();
        $rule = [
            'id|ID'    => 'require',
            'field|字段' => 'require',
            'value|值'  => 'require',
        ];
        $this->validate($post, $rule);
        $row = $this->model->find($post['id']);
        if (!$row)
        {
            $this->error('数据不存在');
        }
        try
        {
            $row->save([
                $post['field'] => $post['value'],
            ]);
        }
        catch (\Exception $e)
        {
            $this->error($e->getMessage());
        }
        $this->success('保存成功');
    }


}