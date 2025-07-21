<?php
// +----------------------------------------------------------------------
// | 控制器名称：用户账户流水控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理当前登录用户的账户资金流水记录
// | 包含操作：个人账户流水列表、流水记录查询、按条件筛选等
// | 主要职责：提供用户个人资金流水的查询功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="用户账户流水")
 */
class auaccount extends AdminController
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
            list($page, $limit, $where) = $this->buildTableParames();
            $uid = $this->request->session('admin.id');
            $count = $this->model
                ->where($where)
                ->where(['uid'=> $uid])
                ->count();
            $list = $this->model
                ->where($where)
                ->with(['Admins'])
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
        return $this->fetch();
    }
}