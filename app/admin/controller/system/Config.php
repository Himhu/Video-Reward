<?php
// +----------------------------------------------------------------------
// | 控制器名称：系统配置管理控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统全局配置参数
// | 包含操作：配置列表、保存配置、数据清理、链接替换等
// | 主要职责：维护系统运行的全局配置和数据维护
// +----------------------------------------------------------------------

namespace app\admin\controller\system;


use app\admin\controller\Outlay;
use app\admin\model\Link;
use app\admin\model\Payed;
use app\admin\model\PayOrder;
use app\admin\model\PaySetting;
use app\admin\model\PointDecr;
use app\admin\model\PointLog;
use app\admin\model\Stock;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemConfig;
use app\admin\service\TriggerService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;
use think\facade\Db;

/**
 * Class Config
 * @package app\admin\controller\system
 * @ControllerAnnotation(title="系统配置管理")
 */
class Config extends AdminController
{

    protected $admin = null;
    protected $link = null;
    protected $stock = null;
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new SystemConfig();
        $this->admin = new SystemAdmin();
        $this->link = new Link();
        $this->stock = new Stock();

    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        $pay = new PaySetting();
        $pay_lists = $pay->select()->toArray();
        $config = $this->model->select()->toArray();
        $c = [];
        foreach ($config as $item)
        {
            $c[$item['group']][] = $item;
        }

        $this->assign('config',$c);
        $this->assign('pay_lists', $pay_lists);

        $this->assign('d',$this->request->domain());
        $this->assign('f',id_encode($this->uid));
        $this->assign('short' , getShort());
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="保存")
     */
    public function save()
    {
        $post = $_POST;
        try {
            foreach ($post as $key => $val) {

                //包月
                if($key == "jg_yopen"  )
                {
                    $this->admin->whereRaw(" 1 = 1")->update(['is_month' =>$val]);
                }
                //jp_wopen  包周
                if($key == "jp_wopen"  )
                {
                    $this->admin->whereRaw(" 1 = 1")->update(['is_week' =>$val]);

                }
                //jg_topen 包天
                if($key == "jg_topen"  )
                {
                    $this->admin->whereRaw(" 1 = 1")->update(['is_day' =>$val]);
                }
                if($key =="ff_short")
                {
                    $this->admin->whereRaw(" 1 = 1")->update(['short' =>$val]);
                }
                if($key =="pay_zhifu")
                {
                    //$res = $this->admin->whereRaw(" 1 = 1")->update(['pay_model' =>$val]);
                }
                if($key =="pay_zhifu1")
                {
                      $res = $this->admin->whereRaw(" 1 = 1")->update(['pay_model1' =>$val]);
                }
                if($key =="ff_close")
                {
                    $this->admin->whereRaw(" 1 = 1")->update(['is_ff' =>$val]);
                }
                $this->model
                    ->where('name', $key)
                    ->update([
                        'value' => $val,
                    ]);
            }
            TriggerService::updateMenu();
            TriggerService::updateSysconfig();
        } catch (\Exception $e) {
            $this->error('保存失败');
        }
        $this->success('保存成功');
    }

    //替换图片&视频连接
    public function replace()
    {
        $filed = $this->request->param('field');
        $searchStr = $this->request->param('search_str');
        $replaceStr = $this->request->param('replace_str');

        if($filed == "img")
        {
            $sql = "update ds_stock set image = REPLACE(image,'$searchStr','$replaceStr') where 1 = 1;";
            Db::query($sql);
        }
        if($filed == "video_url")
        {
            $sql = "update ds_stock set url = REPLACE(url,'$searchStr','$replaceStr') where 1 = 1;";
            Db::query($sql);
        }

        $sql = "update ds_link set $filed = REPLACE($filed,'$searchStr','$replaceStr') where 1 = 1;";
        Db::query($sql);

        return $this->success("替换成功!");
    }
    //删除24小时之前的运营数据
    public function del1()
    {
        $date = date('Y-m-d',time()) . " 23:59:59";
        $date = strtotime($date) - 86400;
        //(new PayOrder())->where('createtime','<' ,$date )->save(['is_tj' => 0]);
        (new PayOrder())->where('createtime','<=' ,$date )->delete();

        (new Payed())->where('createtime','<' ,$date )->save(['is_tj' => 0]);
        (new \app\admin\model\Outlay())->where(['status' => 1])->where('create_time',"<",$date)->delete();
        (new \app\admin\model\Outlay())->where(['status' => 2])->where('create_time',"<" ,$date)->delete();
        (new \app\admin\model\Tj())->where('create_time','<=',$date)->delete();
        (new \app\admin\model\PayOrder())->where('createtime',"<" ,$date)->delete();

        (new \app\admin\model\UserMoneyLog())->where('create_time',"<" ,$date)->delete();
       (new PointLog())->whereRaw(" 1 = 1")->delete();
        (new PointDecr())->whereRaw(" 1 = 1")->delete();
        
        return $this->success('删除成功!');
    }

    //删除垃圾数据
    public function del2()
    {
        sleep(1);
        return $this->success('删除垃圾数据成功!');

    }

    //删除不在公共片库里的私有片库
    public function del3()
    {
        //查询公共
        $stock = $this->stock->field(['id'])->select()->toArray();
        $stock_id = array_column($stock , 'id');
        //查询私有
        $link = $this->link->field(['uid','stock_id'])->select()->toArray();
        $link_id = array();
        foreach($link as $k=>$v){
            $link_id[$v['uid']][] = $v['stock_id'];
        }
        foreach ($link_id as $uid => $item)
        {
            //差集
            $res = array_diff($item,$stock_id);
            if($res)
            {
                $this->link->whereIn('stock_id',$res)->delete();
            }
        }
        return $this->success('删除成功!');
    }

    //删除公共片库
    public function del4()
    {
        
 (new \app\admin\model\Stock())->where('id',">=",1)->where(['is_dsp'=>0])->delete();
    return $this->success('删除成功!');
    }

    //删除公共片库
    public function del6()
    {

        (new \app\admin\model\Stock())->where('id',">=",1)->where(['is_dsp'=>1])->delete();
        return $this->success('删除成功!');
    }
    
      //删除私有片库
    public function del5()
    {
     (new \app\admin\model\Link())->where('id',">=",1)->delete();
        return $this->success('删除成功!');
    }
}