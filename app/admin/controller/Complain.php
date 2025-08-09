<?php
// +----------------------------------------------------------------------
// | 控制器名称：投诉管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统用户投诉信息
// | 包含操作：投诉列表、投诉处理、批量删除投诉等
// | 主要职责：提供系统投诉信息的管理和处理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="投诉管理")
 */
class Complain extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Complain();
        
        $this->assign('getStatusList', $this->model->getStatusList());

    }

    public function delete()
    {
        //$row = $this->model->whereIn('id', $id)->select();
        //$row->isEmpty() && $this->error('数据不存在');
        try {
            $save = $this->model->whereRaw('1=1')->delete();
        } catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }


}