<?php

// +----------------------------------------------------------------------
// | 配置名称：前台控制器基类
// +----------------------------------------------------------------------
// | 配置功能：提供前台控制器公共方法和属性
// | 主要配置：模板变量赋值、访问检查、统计功能等
// | 当前配置：支持页面渲染、跳转响应、访问统计等功能
// +----------------------------------------------------------------------

namespace app\common\controller;


use app\admin\model\Hezi;
use app\admin\model\Tj;
use app\BaseController;
use think\facade\Env;
use think\Request;

class IndexBaseController extends  BaseController
{
    use \app\common\traits\JumpTrait;


    protected $id = null;
    protected $hid = null;

    protected $pid_top = 1;


    protected $muBan = 'muban6';

    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 模板变量赋值
     * @param string|array $name 模板变量
     * @param mixed $value 变量值
     * @return mixed
     */
    public function assign($name, $value = null)
    {
        return $this->app->view->assign($name, $value);
    }

    public function checkFlg()
    {
        $f = $this->request->param('f');
        if(empty($f))
        {
            $f = $this->request->route('f');
        }
        $flgArr = id_decode($f);

        if($this->request->action() == "qrcode")
        {
            return true;
        }

        // create方法用于前端指纹识别，跳过f参数检查
        if($this->request->action() == "create")
        {
            return true;
        }

        // config方法用于前端配置获取，跳过f参数检查
        if($this->request->action() == "config")
        {
            return true;
        }

        // Vue应用API接口，跳过f参数检查
        $apiActions = ['cat', 'vlist', 'pays', 'video'];
        if(in_array($this->request->action(), $apiActions))
        {
            return true;
        }
        
        if(empty($f) || empty($flgArr))
        {
            // 检查是否为首页访问或特殊页面
            $action = $this->request->action();
            $controller = $this->request->controller();

            // 对于首页访问，设置默认用户ID（PC端检测由前端JavaScript处理）
            if (strtolower($controller) == 'index' && strtolower($action) == 'index') {
                // 为首页访问设置默认用户ID
                $this->id = 1; // 使用ID为1的用户作为首页默认用户
                return true;
            }

            // 其他特殊页面
            $allowedPages = ['qrcode'];
            if (strtolower($controller) == 'index' && in_array(strtolower($action), $allowedPages)) {
                return true;
            }

            header("Location: https://m.baidu.com");//重定向浏览器
            exit;//确保重定向后，后续代码不会被执行
        }
        
        if(!is_array($flgArr)) {
            $this->id = $flgArr;
        } else {
            $this->id = $flgArr['id'];
        }
        
        if($this->id >= 1000)
        {
           $f =  (new Hezi())->find($this->id);
           if($f && $f = $f->f)
           {
               $flgArr = id_decode($f);
               if(!is_array($flgArr)) {
                   $this->id = $flgArr;
               } else {
                   $this->id = $flgArr['id'];
               }
           }
           else
           {
                $a = json_encode(['code' => 99,'data'=>['url' => 'https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2']],256);//链接删除跳转
                exit($a);

                exit("<script>location.href='https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2'</script>");
           }
        }
    }

    public function tongji()
    {
        $action = $this->request->action();
        $arr = ['lists','index'];
        if(in_array($action , $arr))
        {
            $ua = md5($this->request->server('HTTP_USER_AGENT'));

            $tj = new Tj();

            if(!$tj->where(['ua' => $ua])->find())
            {
                $tj->insert(['uid' => $this->id , 'create_time' => time(),'ua' => $ua]);
            }
        }
    }


    /**
     * 解析和获取模板内容 用于输出
     * @param string $template
     * @param array $vars
     * @return mixed
     */
    public function fetch($template = '', $vars = [])
    {
        return $this->app->view->fetch($template, $vars);
    }

}