<?php
// +----------------------------------------------------------------------
// | 控制器名称：资源分类控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统资源的分类信息
// | 包含操作：分类列表、添加分类、编辑分类、删除分类等
// | 主要职责：维护系统资源的分类体系
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="资源分类")
 */
class Category extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Category();
        
    }

    
}