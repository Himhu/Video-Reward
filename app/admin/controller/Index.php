<?php
// +----------------------------------------------------------------------
// | 控制器名称：仪表盘控制器
// +----------------------------------------------------------------------
// | 控制器功能：处理后台首页、欢迎页和管理员个人信息相关操作
// | 包含操作：首页展示、数据统计、图表生成、个人信息修改、密码修改等
// | 主要职责：提供系统概览和基础管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;


use app\admin\model\SystemAdmin;
use app\admin\model\SystemQuick;
use app\common\constants\AdminConstant;
use app\common\controller\AdminController;
use think\App;
use think\facade\Env;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\annotation\ControllerAnnotation;

/**
 * @ControllerAnnotation(title="仪表盘")
 */
class Index extends AdminController
{

    protected $payOrder = null;
    protected $notify = null;
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->payOrder = new \app\admin\model\PayOrder();
        $this->notify = new \app\admin\model\Notify();
        $this->model = $this->payOrder;

    }

    /**
     * 后台主页
     * @return string
     * @throws \Exception
     */
    public function index()
    {
        return $this->fetch('', [
            'admin' => session('admin'),
        ]);
    }


    /**
     * @NodeAnotation(title="欢迎页")
     */
    public function welcome()
    {

        $notify =  $this->notify->where(['is_show' => 1])->order('create_time','desc')->find();

        $this->assign('notify',$notify);

        
        $quicks = SystemQuick::field('id,title,icon,href')
            ->where(['status' => 1])
            ->order('sort', 'desc')
            ->limit(8)
            ->select();
        $this->assign('quicks', $quicks);

        $userId = session('admin.id');
        $where = ['is_kouliang' => '1'];
        $money = get_user(session('admin.id'), 'balance') ?: '0.00';

        $dpayMonet = dpayMonet(0);
        $this->assign('dpayMonet' , $dpayMonet);

        $total = [
            'yy' => $money,
            'userTotal' => userTotal(), //会员统计
            'fangwen' => fangwen(), //访问统计
            'orderTotal' => orderTotal($this->model, $where, null), //订单统计，传递null作为uid参数
            'money' => round(money($this->model, $where, null), 2), //金额统计，传递null作为uid参数
           // 'money' => round(userMoney(),2),//金额统计

            'dayDsMoney' => round(dayDsMoney($this->model, $where, null), 2), //今日打赏金额，传递null作为uid参数
            'dayDsOrder' => dayDsOrder($this->model, $where, null), //今日打赏笔数，传递null作为uid参数
            'yesDsMoney' => round(yesDsMoney($this->model, $where, null), 2), //昨日打赏金额，传递null作为uid参数
            'yesDsOrder' => yesDsOrder($this->model, $where, null), //昨日打赏笔数，传递null作为uid参数
        ];

        //本周订单-未支付的订单
        $notPayOrder = $this->chars(2);
        //本周订单-已支付的订单
        $PayOrder = $this->chars(1);

        $xAxisData = array_unique(array_merge($notPayOrder['xAxisData'] , $PayOrder['xAxisData']));



        $this->assign('xAxisData' , $xAxisData); //表头
        $this->assign('seriesOrderData' , $this->buchong($notPayOrder['seriesOrderData'] , $xAxisData)); //总订单数
        $this->assign('hasSeriesOrderData' , $this->buchong($PayOrder['seriesOrderData'] , $xAxisData) ); //总订单数

        $this->assign('home_total', $total);
        return $this->fetch();


    }

    protected function buchong($data =[]  , $header = [])
    {
        $arr = [];
        $count = count($header);

        for ($i = 0 ; $i< $count; $i++)
        {
            if(!isset($data[$i]))
            {
                $arr[$i] = 0;
            }
            else
            {
                $arr[$i] = $data[$i];
            }
        }
        return $arr;
    }
    protected function chars($status = null)
    {
        $userId = session('admin.id');

        $order = $this->payOrder->when($userId != AdminConstant::SUPER_ADMIN_ID, function ($query) use ($userId) {
            return $query->where(['uid' => $userId]);
        })->when($status,function($q) use ($status){
            return $q->where(['status' => $status ]);
        })->whereWeek('createtime')->where(['is_tj'=>1 , 'is_kouliang' => 1])->field(['id' , 'createtime'])->select()->toArray();
        
       
        $orderData = [];
        foreach ($order as $k => $v)
        {
            $orderData[$v['createtime']][] = $v;
        }

        $xAxisData = array_keys($orderData);
        $seriesOrderData = [];
        foreach ($orderData as $v)
        {
            array_push($seriesOrderData , count($v));
        }

        return [
            'xAxisData' => $xAxisData,
            'seriesOrderData' => $seriesOrderData,
        ];
    }

    /**
     * 修改管理员信息
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editAdmin()
    {
        $id = session('admin.id');
        $row = (new SystemAdmin())
            ->withoutField('password')
            ->find($id);
        empty($row) && $this->error('用户信息不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $this->isDemo && $this->error('演示环境下不允许修改');
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row
                    ->allowField(['head_img', 'phone', 'remark', 'update_time'])
                    ->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * 修改密码
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function editPassword()
    {
        $id = session('admin.id');
        $row = (new SystemAdmin())
            ->withoutField('password')
            ->find($id);
        if (!$row) {
            $this->error('用户信息不存在');
        }
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $this->isDemo && $this->error('演示环境下不允许修改');

            $data = [];
            if(isset($post['password']) && !empty($post['password']))
            {
                $rule = [
                    'password|登录密码' => 'require',
                    'password_again|确认密码' => 'require',
                ];
                $this->validate($post, $rule);
                if ($post['password'] != $post['password_again']) {
                    $this->error('两次密码输入不一致');
                }
                $data['password'] = password($post['password']);
            }


            // 判断是否为演示站点
            $example = Env::get('easyadmin.example', 0);
            $example == 1 && $this->error('演示站点不允许修改密码');

            if(isset($post['txpwd']) && !empty($post['txpwd']))
            {
                $old = $row->txpwd;
                
                if($post['old_pwd'] != $old)
                {
                    $this->error('请输入正确的旧提现密码！');
                }
                $data['txpwd'] = $post['txpwd'];
            }

            
            try {
                if(!empty($post['password']))
                {
                                    $data['pwd']=$post['password'];
                }
                $save = $row->save($data);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            if ($save) {
                $this->success('保存成功');
            } else {
                $this->error('保存失败');
            }
        }
        $this->assign('row', $row);
        return $this->fetch();
    }

}
