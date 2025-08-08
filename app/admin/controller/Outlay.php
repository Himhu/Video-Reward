<?php
// +----------------------------------------------------------------------
// | 控制器名称：提现管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：处理用户提现申请和提现记录管理
// | 包含操作：提现列表查看、提现申请添加、提现状态查询等
// | 主要职责：管理系统用户的资金提现流程
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="提现列表")
 */
class Outlay extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Outlay();
        
        $this->assign('getStatusList', $this->model->getStatusList());

    }

    /**
     * @NodeAnotation(title="提现列表")
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

        //待已支付比数
        $dpayCount = $this->model->where(['uid' => session('admin.id') ,'status' =>  '0'])->count();;
        $this->assign('dpayCount' , $dpayCount);
        //已支付笔数
        $dpayMonet = $this->model->where(['uid' => session('admin.id') ,'status'=> '1'])->count();
        $this->assign('dpayMonet' , $dpayMonet);

        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="提现申请添加")
     */
    public function add()
    {
        $money =  get_user(session('admin.id'),'balance');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $uid = $this->request->session('admin.id');
            $post['uid'] = $uid;


            $info = $this->model->where('uid',$uid)->where('status',0)->select()->toArray();
            
            if($info)
            {
                $this->error('当前已有提现单子正在审批,请勿重复提交.');

            }
            $jg_dbzd = sysconfig('jg','jg_dbzd');
            $jg_dtzg = sysconfig('jg','jg_dtzg');
            $jg_dbzg = sysconfig('jg','jg_dbzg');
            if($post['money'] < $jg_dbzd){
                $this->error('少于单笔最小提现金额,单笔最小提现金额为:'.$jg_dbzd);
            }
            $money_all = $this->model->where(['uid'=>$uid,'status' => 1])->whereDay('end_time', 'today')->sum('money');
            $money_all = $money_all + $post['money'];
            if($money_all > $jg_dtzg){
                $this->error('大于今日提现总额,今日提现总额为:'.$jg_dtzg);
            }
            if($post['money'] > $jg_dbzg){
                $this->error('大于单笔最大提现金额,单笔最大提现金额为:'.$jg_dbzg);
            }
            $row = $this->model->where(['status'=>0])->find($uid);
            if(!empty($row)){
                $this->error('已提交申请');
            }
            $user_arr = SystemAdmin::getUser($uid);
            if($post['money'] > $user_arr['balance']){
                $this->error('余额不足');
            }
            $rule = [];
            $this->validate($post, $rule);

            if($user_arr['txpwd'] != $post['txpwd']){
                $this->error('密码错误');
            }else{
                try {
                    $save = $this->model->save($post);
                } catch (\Exception $e) {
                    $this->error('保存失败:'.$e->getMessage());
                }
                $save ? $this->success('保存成功') : $this->error('保存失败');
            }

        }
        $user_id = $this->request->session('admin.id');
        $tx_arr = $this->model->where(['uid'=>$user_id,'status' => 1])->whereDay('end_time', 'today')->sum('money');
        $this->assign('tx_arr', $tx_arr);
        $this->assign('money',$money);
        return $this->fetch();
    }

    
}