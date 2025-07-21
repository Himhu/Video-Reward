<?php
// +----------------------------------------------------------------------
// | 控制器名称：订单管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统中的支付订单
// | 包含操作：订单列表、订单统计、按条件筛选订单等
// | 主要职责：提供订单数据的查询和统计分析功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\constants\AdminConstant;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\console\Input;

/**
 * @ControllerAnnotation(title="订单列表")
 */
class Payorder extends AdminController
{
    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\PayOrder();
        $this->pay = new \app\admin\model\PaySetting();
        
    }



    //订单代理统计
    protected function count()
    {
        $param = $this->request->param();
        $model = array_get($param,'data.pay_channel');
        $uid = $this->request->param('uid');
        $type = \input('type');
        $userId = session('admin.id');
        $where = ['is_kouliang' => 1];
        if(!empty($model))
        {
            $where['pay_channel'] = $model;
        }
        //今日订单笔数
        $dayOrderCount = dayDsOrder($this->model , $where , $uid);
        //昨日订单笔数
        $yesterdayOrderCount = yesDsOrder($this->model,$where , $uid);
        //今日订单总金额
        $dayOrderMoney = dayDsMoney($this->model,$where , $uid);
        //昨日订单总金额
        $yesterdayOrderMoney = yesDsMoney($this->model,$where , $uid);
        //历史总订单笔数
        $OrderCount = orderTotal($this->model,$where , $uid);
        //历史总订单金额
        $OrderMoney = money($this->model,$where , $uid);


        $datas = [[
            'dayOrderCount' =>  $dayOrderCount,
            'yesterdayOrderCount' => $yesterdayOrderCount,
            'dayOrderMoney' => $dayOrderMoney,
            'yesterdayOrderMoney' => $yesterdayOrderMoney,
            'OrderCount' => $OrderCount,
            'OrderMoney' => $OrderMoney
        ]];
        $data = [
            'code'  => 0,
            'msg'   => '',
            'data' => $datas
        ];
        if($this->request->isAjax() && isset($param['a']))
        {
            return $this->success('',$datas);
        }

        if($this->request->isAjax() )
        {
            return json($data);
        }

        return $datas;

    }
    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        
        if(input('orderCount'))
        {
            return $this->count();
        }
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where(['status' => 1,'is_kouliang' => 1])
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->where(['status' => 1 , 'is_kouliang' => 1])
                ->with(['Admins' , 'Link'])
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
        
        $res = $this->pay->where(['status' => 1])->select()->toArray();

        $res = array_column($res,'title','pay_model');
        $this->assign('pay',json_encode($res,256));
        
        $count = $this->count();

        $this->assign('count',$count);
        return $this->fetch();
    }

    
}