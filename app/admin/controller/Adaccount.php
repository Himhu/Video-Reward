<?php
// +----------------------------------------------------------------------
// | 控制器名称：会员账户流水控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统会员的账户资金流水记录
// | 包含操作：账户流水列表、流水记录查询、按条件筛选等
// | 主要职责：提供会员资金流水的查询和管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="会员账户流水")
 */
class Adaccount extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\UserMoneyLog();
        
    }

    /**
     * @NodeAnotation(title="账户流水")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            $uid = session('admin.id');
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->when($uid != 1, function($q) use ($uid){
                    return $q->where(['uid' => $uid]);
                })
               // ->where(['type' => 1])
                ->count();
            $list = $this->model
                ->where($where)
                ->when($uid != 1, function($q) use ($uid){
                    return $q->where(['uid' => $uid]);
                })
                //->where(['type' => 1])
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