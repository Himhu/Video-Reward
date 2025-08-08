<?php
// +----------------------------------------------------------------------
// | 控制器名称：下级管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理代理下级的邀请码和订单统计
// | 包含操作：下级列表、添加下级邀请码、统计下级订单金额等
// | 主要职责：代理商下级管理和数据统计
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="下级管理")
 */
class Numberx extends AdminController
{

    use \app\admin\traits\Curd;
    public $modell = null;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Number();
        $this->modell = new \app\admin\model\PayOrder();
        
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
            $uid = $this->request->session('admin.id');
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->where(['uid'=> $uid])
                ->count();
            $list = $this->model
                ->where($where)
                ->where(['uid'=> $uid])
                ->with(['AdminUa','Admin'])
                ->page($page, $limit)
                ->order($this->sort)
                ->select();
                //dd($list->toArray());
                
                $where = ['is_kouliang' => '1'];
                foreach ($list as &$item)
                {
                    if(!empty($item->AdminUa))
                    {
                        
                         //今日订单总金额
                        $dayOrderMoney = dayDsMoney($this->modell ,$where,$item->AdminUa['id']);
                        //昨日订单总金额
                        $yesterdayOrderMoney = yesDsMoney($this->modell ,$where,$item->AdminUa['id']);
                        $item->day_m = $dayOrderMoney;
                        $item->yes_m = $yesterdayOrderMoney;
                    }
                    else {
                        $item->day_m = 0;
                        $item->yes_m = 0;
                    }
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
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();

            $uid = $this->request->session('admin.id');
            //代理信息
            $user_arr = SystemAdmin::getUser($uid);


            $yqm = sysconfig('jg','jg_yqm');
            $money = $yqm* $post['num'];
            if($user_arr['balance'] < $money){
                return $this->error('余额不足');
            }

            //减去金额
            SystemAdmin::jmoney($money,$uid,'购买邀请码花费金额'.$money);
            $rule = [];
            $this->validate($post, $rule);
            $arr = array();
            $num = $post['num'];
            for($i=1; $i <= $num; $i++){
                //生成邀请码
                $arr[$i]['number'] = md5(microtime().$i);
                $arr[$i]['uid'] = $uid;
                $arr[$i]['create_time'] = time();
            }
            $save = $this->model->insertAll($arr);
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $user_id = $this->request->session('admin.id');
        $user_list = SystemAdmin::getUser($user_id);
        $this->assign('user_list', $user_list);
        return $this->fetch();
    }



    
}