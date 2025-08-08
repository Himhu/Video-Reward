<?php
// +----------------------------------------------------------------------
// | 控制器名称：邀请码管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统邀请码的生成和使用
// | 包含操作：邀请码列表、添加邀请码、批量生成邀请码等
// | 主要职责：系统邀请注册机制的管理
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="邀请码管理")
 */
class Number extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Number();
        
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
            $count = $this->model
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->page($page, $limit)
                ->with(['AdminUa','Admin'])
                ->order($this->sort)
                ->select()->toArray();
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
            //代理信息
//            $user_arr = SystemAdmin::getUser($post['uid']);
//
//            $yqm = sysconfig('jg','jg_yqm');
//            $money = $yqm* $post['num'];
//            if($user_arr['balance'] < $money){
//                return $this->error('余额不足');
//            }
//            //减去金额
//          SystemAdmin::jmoney($money,$post['uid'],'购买邀请码花费金额'.$money);

            $arr = array();
            $num = $post['num'];
            for($i=1; $i <= $num; $i++){
                //生成邀请码
                $arr[$i]['number'] = md5(microtime().$i);
                $arr[$i]['uid'] = $post['uid'];
                $arr[$i]['create_time'] = time();
            }
            $save = $this->model->insertAll($arr);

            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $admin_user = new SystemAdmin();
        $admin_lists = $admin_user->where('status',1)->select()->toArray();
        $this->assign('admin_lists', $admin_lists);
        return $this->fetch();
    }

    
}