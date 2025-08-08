<?php
// +----------------------------------------------------------------------
// | 控制器名称：拒绝提现控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理已被拒绝的提现申请记录
// | 包含操作：拒绝提现列表查看、拒绝原因查询等
// | 主要职责：提供已拒绝提现申请的管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="已拒绝列表")
 */
class Outlayj extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Outlay();
    }

    /**
     * @NodeAnotation(title="已拒绝列表")
     */
    public function index()
    {

        $this->assign('dpayCount',dpayCount(2));
        $this->assign('dpayMonet',dpayMonet(2));
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $count = $this->model
                ->where($where)
                ->where(['status' => 2])
                ->count();
            $list = $this->model
                ->where($where)
                ->with('Admins')
                ->where(['status' => 2])
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