<?php
// +----------------------------------------------------------------------
// | 控制器名称：管理员管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统管理员账户
// | 包含操作：管理员列表、添加管理员、编辑管理员、修改密码、删除管理员等
// | 主要职责：维护系统管理员账户的全生命周期管理
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\model\Outlay;
use app\admin\model\PaySetting;
use app\admin\model\SystemAdmin;
use app\admin\model\UserMoneyLog;
use app\admin\service\TriggerService;
use app\common\constants\AdminConstant;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Db;

/**
 * Class Admin
 * @package app\admin\controller\system
 * @ControllerAnnotation(title="管理员管理")
 */
class Admin extends AdminController
{

    use \app\admin\traits\Curd;
    public $authList = null;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemAdmin();
        $this->modell = new \app\admin\model\PayOrder();
        
        $this->authList = $this->model->getAuthList();
        $this->assign('auth_list', $this->authList);
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {

        
        
        //今日总余额
        $balance = (new SystemAdmin())->sum('balance');
        $this->assign('total_balance',$balance);
        //金日总提现
        $tx = (new Outlay())->whereDay('create_time')->sum('money');
        $this->assign('total_tx',$tx);

        $this->assign('short',getShort());
        $pay = new PaySetting();
        $pay_lists = $pay->select()->toArray();

        $p = [1 => '默认通道'];
        $pay_lists  = array_column($pay_lists , 'title','pay_model');

        $pay_lists = array_merge_recursive($p , $pay_lists);
        //array_unshift($pay_lists,'默认通道');
        //dump($pay_lists);die;
        $this->assign('pay_lists', $pay_lists);


        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->whereNotIn('id',[1])
                ->count();
            $list = $this->model
                ->withoutField('password')
                ->with(['admins','views','orders' => function($q){
                    $q->where(['status' => 1,'is_kouliang' => '1', 'is_tj' => 1])->whereDay('createtime');
                },'domain' => function($q){
                    $q->where(['status' => 0]);
                }])
                ->withSum('outlay','money')
                ->whereNotIn('id',[1])
                ->where($where)
                ->page($page, $limit)
                ->order($this->sort)
                ->select()->toArray();
                
                
                $where = ['is_kouliang' => '1'];
                foreach ($list as &$item)
                {
                    $day_p = 0;
                    
                    $yes_p = 0;
                      //今日订单总金额
                    $dayOrderMoney = dayDsMoney($this->modell ,$where,$item['id']);
                     //昨日订单总金额
                    $yesterdayOrderMoney = yesDsMoney($this->modell ,$where,$item['id']);
                     //下级总额
                     
                      $sid = $this->model->where(['pid' => $item['id']])->field(['id'])->select()->toArray();
                      if($sid)
                      {
                          $sid = array_column($sid,'id');
                          
                          $day_p = dayDsMoney($this->modell,$where,$sid);
                          
                          $yes_p = yesDsMoney($this->modell, $where, $sid);
                          
                
                      }
                       
                      
                      $dayOrderMoney = ['total' => $dayOrderMoney + $day_p , 'day_m' =>$dayOrderMoney ,'day_p' => $day_p  ];
                      $yesterdayOrderMoney = ['total' => $yesterdayOrderMoney + $yes_p , 'yes_m' =>$yesterdayOrderMoney ,'yes_p' => $yes_p  ];
                         
                        
                       
                    $item['day_m'] = $dayOrderMoney;
                    $item['yes_m'] = $yesterdayOrderMoney;
                }


                
            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        $this->assign('auth_list',$this->authList);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {
        $pay = new PaySetting();
        $pay_lists = $pay->select()->toArray();

        $p = [1 => '默认通道'];
        $pay_lists  = array_column($pay_lists , 'title','pay_model');
        $this->assign('pay_lists',$pay_lists);
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $authIds = $this->request->post('auth_ids', []);
            $post['auth_ids'] = implode(',', array_keys($authIds));
            $rule = [];

            if (isset($post['password'])) {

                if ($post['password'] != $post['password_again']) {
                    $this->error('两次密码输入不一致');
                }

                $post['pwd'] = $post['password'];
                $post['password']  =  password($post['password']);

            }
            $post['pid'] = session('admin.id');
            if($post['pid'] == 1)
            {
                $post['pid'] = 0;
            }

            if(!isset($post['is_day']))
            {
                $post['is_day'] = 0;
            }
            if(!isset($post['is_week']))
            {
                $post['is_week'] = 0;
            }
            if(!isset($post['is_month']))
            {
                $post['is_month'] = 0;
            }
            $post['short'] = sysconfig('ff','ff_short');
            if(empty($post['pay_model']))
            {
                $post['pay_model'] = sysconfig('pay','pay_zhifu');
            }
            $post['create_time'] = time();
            //$this->validate($post, $rule);
            unset($post['file'],$post['password_again']);
            try {
                Db::startTrans();
                $id = $this->model->insertGetId($post);
                //分配域名
//                $domain  = (new \app\admin\model\DomainRule())->where(['status' => 1])->find();
//                if($domain)
//                {
//                    (new \app\admin\model\DomainLib())->insert([
//                        'uid' => $id,
//                        'domain' => $domain->domain,
//                        'status' => $domain->status,
//                        'create_time' => time()
//                    ]);
//                    (new \app\admin\model\DomainRule())->where(['id' => $domain->id])->delete();
//                }
                //创建扣量
                addKouliang($id);
                Db::commit();

            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());

            }
            $id ? $this->success('保存成功') : $this->error('保存失12败');
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $pay = new PaySetting();
        $pay_lists = $pay->select()->toArray();

        $p = [1 => '默认通道'];
        $pay_lists  = array_column($pay_lists , 'title','pay_model');
        $this->assign('pay_lists',$pay_lists);
        $this->assign('userinfo',get_user($id));
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $authIds = $this->request->post('auth_ids', []);
            $post['auth_ids'] = implode(',', array_keys($authIds));
            $rule = [];
           // $this->validate($post, $rule);


            if (isset($post['password']) && !empty($post['password']) ) {

                if ($post['password'] != $post['password_again']) {
                    $this->error('两次密码输入不一致');
                }

                $post['pwd'] = $post['password'];
                $post['password']  =  password($post['password']);

            }
            else{
                unset($post['pwd'] , $post['password']);
            }
            if(!isset($post['is_day']))
            {
                $post['is_day'] = 0;
            }
            if(!isset($post['is_week']))
            {
                $post['is_week'] = 0;
            }
            if(!isset($post['is_month']))
            {
                $post['is_month'] = 0;
            }

            // 不再使用系统默认值覆盖用户设置的short值
            // $post['short'] = sysconfig('ff','ff_short');
            
           // $post['create_time'] = time();
            $post['update_time'] = time();
            try {
                $save = $row->save($post);
                TriggerService::updateMenu($id);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $row->auth_ids = explode(',', $row->auth_ids);
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function password($id)
    {
        $row = $this->model->find($id);
        
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [
                'password|登录密码'       => 'require',
               // 'password_again|确认密码' => 'require',
            ];
            
            try {
                
                $save = $row->save([
                    'txpwd' => $post['password'],
                    //'password' => password($post['password']),
                ]);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $row->auth_ids = explode(',', $row->auth_ids);
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="删除")
     */
    public function delete($id)
    {
        $row = $this->model->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        
        
        $id == AdminConstant::SUPER_ADMIN_ID && $this->error('超级管理员不允许修改');
        if (is_array($id)){
            if (in_array(AdminConstant::SUPER_ADMIN_ID, $id)){
                $this->error('超级管理员不允许修改');
            }
        }
        try {
   
            Db::name('SystemAdmin')->where(['id'=>$id])->delete();
          $this->success('删除成功');
            
        } catch (\Exception $e) {
          $this->success('删除成功');
        }
         $this->error('删除失败');
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
        // 确保short字段可以被修改
        $this->allowModifyFields = array_merge($this->allowModifyFields ?? [], ['short', 'pay_model', 'is_ff', 'status']);
        if (!in_array($post['field'], $this->allowModifyFields)) {
            //$this->error('该字段不允许修改：' . $post['field']);
        }
        if ($post['id'] == AdminConstant::SUPER_ADMIN_ID && $post['field'] == 'status') {
            $this->error('超级管理员状态不允许修改');
        }
        $row = $this->model->find($post['id']);
        empty($row) && $this->error('数据不存在');
        try {
            $row->save([
                $post['field'] => $post['value'],
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('保存成功');
    }


}
