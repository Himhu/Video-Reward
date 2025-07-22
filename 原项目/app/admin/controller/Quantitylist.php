<?php
// +----------------------------------------------------------------------
// | 控制器名称：扣量记录控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统中的扣量订单记录
// | 包含操作：扣量记录列表、扣量数据统计、扣量记录查询等
// | 主要职责：提供系统扣量订单的查询和管理功能
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
 * @ControllerAnnotation(title="扣量抽单列表")
 */
class Quantitylist extends AdminController
{
    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\PayOrder();
        
    }



    //订单代理统计
    protected function count()
    {
        $param = $this->request->param();
        $type = \input('type');
        $userId = session('admin.id');
        $where = ['is_kouliang' => 2 , 'status' => 1];
        //今日订单笔数
        $dayOrderCount = dayDsOrder($this->model,$where);
        //昨日订单笔数
        $yesterdayOrderCount = yesDsOrder($this->model,$where);
        //今日订单总金额
        $dayOrderMoney = dayDsMoney($this->model,$where);
        //昨日订单总金额
        $yesterdayOrderMoney = yesDsMoney($this->model,$where);
        //历史总订单笔数
        $OrderCount = orderTotal($this->model,$where);
        //历史总订单金额
        $OrderMoney = money($this->model,$where);


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
        if($this->request->isAjax())
        {
            return json($data);
        }

        return $datas;

    }
    /**
     * @NodeAnotation(title="扣量列表")
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
                ->where($where)
                ->where(['status'=>1,'is_kouliang' => 2])
                ->count();
            $list = $this->model
                ->where($where)
                ->where(['status'=>1 ,'is_kouliang' => 2])
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

        $count = $this->count();

        $this->assign('count',$count);
        return $this->fetch();
    }

    
}