<?php
// +----------------------------------------------------------------------
// | 控制器名称：打赏配置控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统打赏相关的配置和价格设置
// | 包含操作：打赏配置列表、价格设置、模板选择等
// | 主要职责：维护系统打赏功能的配置和价格策略
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\Price;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use app\common\service\Arr;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="打赏配置")
 */
class Peizhi extends AdminController
{
    //此文件有增删改查代码 如果有定制可复制出来覆盖
    use \app\admin\traits\Curd;

    protected $muban = null;
    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->muban = new \app\admin\model\Muban();


    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        
      
        
        if ($this->request->isAjax()) {

            $vid = $this->request->param('id');

            //修改包天 & 月 & 周 相关
            if($this->request->has('data'))
            {
                $post = $this->request->param('data');
                if(!isset($post['is_day']))
                {
                    $post['is_day'] = 0;
                }

                if(!isset($post['is_dan']))
                {
                    $post['is_dan'] = 2;
                }

                if(!isset($post['is_month']))
                {
                    $post['is_month'] = 0;
                }

                if(!isset($post['is_week']))
                {
                    $post['is_week'] = 0;
                }
                $post['uid'] = $this->uid;
                $exists = (new Price())->where(['uid' => $this->uid,'pay_model' => $post['pay_model']])->select()->toArray();
                if($exists)
                {
                    Price::update($post, ['uid' => $this->uid,'pay_model' => $post['pay_model']]);
                }
                else
                {
                    Price::create($post);
                }
                $data = [
                    'code'  => 0,
                    'msg'   => '保存成功',
                ];
                return json($data);
            }

            SystemAdmin::update(['view_id' =>$vid ], ['id' => session('admin.id')]);
            $data = [
                'code'  => 0,
                'msg'   => '保存成功',
            ];
            return json($data);
        }

        $list = $this->muban
            ->where(['status' => 1])
            //->order('create_time','desc')
            ->select();

        $this->assign('list',$list);
        $user = SystemAdmin::getUser(session('admin.id'));
        $payModel = array_get($user,'pay_model');
        if(empty($payModel))
        {
            $payModel = sysconfig('pay','pay_zhifu');
        }
        $payModel1 = array_get($user,'pay_model1');
        if(empty($payModel1))
        {
            $payModel1 = sysconfig('pay','pay_zhifu1');
        }

        $arr = [$payModel , $payModel1];
        $pay = (new \app\admin\model\PaySetting())->whereIn('pay_model',$arr)->select()->toArray();

        $payPrice1 = (new Price())->where(['pay_model' => 1,'uid' => $this->uid])->select()->toArray();
        $payPrice2 = (new Price())->where(['pay_model' => 2,'uid' => $this->uid])->select()->toArray();

        $this->assign('price1',Arr::get($payPrice1,0,[
            'pay_model' => 1,
            'is_dan' => '',
            'dan_fee' => '',
            'is_day' => '',
            'date_fee' => '',
            'is_week' => '',
            'week_fee' => '',
            'is_month' => '',
            'month_fee' => ''
        ]));
        $this->assign('price2',Arr::get($payPrice2,0,[
            'pay_model' => 1,
            'is_dan' => '',
            'dan_fee' => '',
            'is_day' => '',
            'date_fee' => '',
            'is_week' => '',
            'week_fee' => '',
            'is_month' => '',
            'month_fee' => ''
        ]));
        $this->assign('pay',$pay);

        $this->assign('userinfo',$user);

        return $this->fetch();
    }


    
}