<?php
// +----------------------------------------------------------------------
// | 控制器名称：操作日志管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统操作日志记录
// | 包含操作：日志列表、日志查询、按月份筛选等
// | 主要职责：提供系统操作记录的查询和追踪功能
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\model\SystemLog;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="操作日志管理")
 * Class Auth
 * @package app\admin\controller\system
 */
class Log extends AdminController
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemLog();
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
            [$page, $limit, $where, $excludeFields] = $this->buildTableParames(['month']);

            $month = (isset($excludeFields['month']) && !empty($excludeFields['month']))
                ? date('Ym',strtotime($excludeFields['month']))
                : date('Ym');

            // todo TP6框架有一个BUG，非模型名与表名不对应时（name属性自定义），withJoin生成的sql有问题

            $count = $this->model
                ->setMonth($month)
                ->with('admin')
                ->where($where)
                ->select();
            $list = $this->model
                ->setMonth($month)
                ->with('admin')
                ->where($where)
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