<?php
// +----------------------------------------------------------------------
// | 控制器名称：代理片库控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理代理的视频资源库
// | 包含操作：代理视频列表、添加代理视频、编辑代理视频等
// | 主要职责：提供代理专属视频资源的管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\constants\AdminConstant;
use app\admin\model\SystemAdmin;
use app\admin\model\Category;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="代理片库")
 */
class Link extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Link();
        
        $this->assign('getMianfeiList', $this->model->getMianfeiList());

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
            list($page, $limit, $where) = $this->buildTableParames();
            $uid = $this->request->session('admin.id');

            $count = $this->model
                ->where($where)
                ->when(AdminConstant::SUPER_ADMIN_ID != $uid , function($q) use ($uid){
                    return $q->where(['uid' => $uid]);
                } )
                ->count();
            $list = $this->model
                ->where($where)
                ->when(AdminConstant::SUPER_ADMIN_ID != $uid , function($q) use ($uid){
                    return $q->where(['uid' => $uid]);
                } )
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
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
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

        $type = new Category();
        $type_lists = $type->select()->toArray();

        $this->assign('type_lists', $type_lists);
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