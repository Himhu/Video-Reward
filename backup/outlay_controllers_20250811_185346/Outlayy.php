<?php
// +----------------------------------------------------------------------
// | 控制器名称：已结算提现控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理已完成结算的提现记录
// | 包含操作：已结算提现列表、提现统计数据展示等
// | 主要职责：提供已完成提现的查询和统计功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="已结算列表")
 */
class Outlayy extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model =  new \app\admin\model\Outlay();

    }


    /**
     * @NodeAnotation(title="已结算列表")
     */
    public function index()
    {
        //今日提现
        $this->assign('daytxmoney',daytxmoney('today'));
        //今日结算
        $this->assign('daytxmoney1',daytxmoney1('today'));
        //昨日提现
        $this->assign('yesterdaytxmoney',daytxmoney('yesterday'));
        //昨日结算
        $this->assign('yesterdaytxmoney1',daytxmoney1('yesterday'));
        //前日提现
        $this->assign('qtxmoney',daytxmoney(date("Y-m-d",strtotime("-2 day"))));
        //前日结算
        $this->assign('qtxmoney1',daytxmoney1(date("Y-m-d",strtotime("-2 day"))));
        //已支付笔数
        $this->assign('dpayCount',dpayCount(1));
        //已支付金额
        $this->assign('dpayMonet',dpayMonet(1));
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where(['status' => 1])
                ->where($where)
                ->count();
            $list = $this->model
                ->where($where)
                ->where(['status' => 1])
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

    
}