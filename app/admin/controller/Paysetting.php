<?php
// +----------------------------------------------------------------------
// | 控制器名称：支付设置控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统支付渠道和支付配置
// | 包含操作：支付配置列表、添加支付渠道、编辑支付配置、属性修改等
// | 主要职责：维护系统支付功能的配置和管理
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="支付管理表")
 */
class Paysetting extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\PaySetting();
        
        $this->assign('getModelList', $this->model->getModelList());

        $this->assign('getStatusList', $this->model->getStatusList());

    }

    /**
     * @NodeAnotation(title="属性修改")
     */
    public function modify()
    {
        $post = $this->request->post();
        $rule = [
            'id|ID'    => 'require',
            'field|字段' => 'require',
            'value|值'  => 'require',
        ];
        $this->validate($post, $rule);
        $row = $this->model->find($post['id']);
        if (!$row) {
            $this->error('数据不存在');
        }
        try {
            $row->save([
                $post['field'] => $post['value'],
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('保存成功');
    }

    
}