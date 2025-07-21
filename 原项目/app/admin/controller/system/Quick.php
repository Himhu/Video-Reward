<?php
// +----------------------------------------------------------------------
// | 控制器名称：快捷入口管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统首页快捷入口
// | 包含操作：快捷入口列表、添加快捷入口、编辑快捷入口、删除快捷入口等
// | 主要职责：维护系统首页的快捷功能入口
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\model\SystemQuick;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="快捷入口管理")
 * Class Quick
 * @package app\admin\controller\system
 */
class Quick extends AdminController
{

    use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemQuick();
    }

}