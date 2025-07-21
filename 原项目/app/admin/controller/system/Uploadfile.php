<?php
// +----------------------------------------------------------------------
// | 控制器名称：上传文件管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统上传的文件资源
// | 包含操作：文件列表、文件查询、文件删除等
// | 主要职责：提供系统上传文件的管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\model\SystemUploadfile;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="上传文件管理")
 * Class Uploadfile
 * @package app\admin\controller\system
 */
class Uploadfile extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemUploadfile();
    }

}