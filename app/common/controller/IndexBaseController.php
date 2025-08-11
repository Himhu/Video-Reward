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

        // 根据控制器类型决定是否需要防洪检查
        if ($this->needFloodCheck()) {
            $this->checkFlg();
        }

        // 根据控制器类型决定是否需要统计
        if ($this->needStatistics()) {
            $this->tongji();
        }
    }

    /**
     * 判断是否需要防洪检查
     * @return bool
     */
    protected function needFloodCheck()
    {
        // 支付控制器不需要防洪检查（避免影响支付流程）
        $exemptControllers = ['Pay'];
        $currentController = $this->request->controller();

        return !in_array($currentController, $exemptControllers);
    }

    /**
     * 判断是否需要统计
     * @return bool
     */
    protected function needStatistics()
    {
        // 只有主要页面控制器需要统计
        $statisticsControllers = ['Index'];
        $currentController = $this->request->controller();

        return in_array($currentController, $statisticsControllers);
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

    /**
     * 防洪检查主方法
     */
    public function checkFlg()
    {
        $f = $this->getParameterF();

        if ($this->isExemptAction()) {
            return true;
        }

        $flgArr = id_decode($f);

        if (empty($f) || empty($flgArr)) {
            return $this->handleEmptyParameter();
        }

        $this->setUserId($flgArr);

        if ($this->id >= 1000) {
            $this->handleHighUserId();
        }
    }

    /**
     * 获取参数f
     * @return string
     */
    protected function getParameterF()
    {
        $f = $this->request->param('f');
        if (empty($f)) {
            $f = $this->request->route('f');
        }
        return $f;
    }

    /**
     * 判断是否为免检动作
     * @return bool
     */
    protected function isExemptAction()
    {
        $exemptActions = ['qrcode', 'create', 'config'];
        $apiActions = ['cat', 'vlist', 'pays', 'video'];
        $currentAction = $this->request->action();

        return in_array($currentAction, array_merge($exemptActions, $apiActions));
    }

    /**
     * 处理空参数情况
     * @return bool
     */
    protected function handleEmptyParameter()
    {
        if ($this->isHomePage()) {
            $this->setDefaultUser();
            return true;
        }

        if ($this->isAllowedSpecialPage()) {
            return true;
        }

        $this->redirectToDefault();
    }

    /**
     * 判断是否为首页
     * @return bool
     */
    protected function isHomePage()
    {
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());

        return $controller == 'index' && $action == 'index';
    }

    /**
     * 判断是否为允许的特殊页面
     * @return bool
     */
    protected function isAllowedSpecialPage()
    {
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());
        $allowedPages = ['qrcode'];

        return $controller == 'index' && in_array($action, $allowedPages);
    }

    /**
     * 设置默认用户
     */
    protected function setDefaultUser()
    {
        $this->id = 1; // 使用ID为1的用户作为首页默认用户
    }

    /**
     * 重定向到默认页面
     */
    protected function redirectToDefault()
    {
        $redirectUrl = sysconfig('', 'flood_redirect_url') ?: 'https://m.baidu.com';
        header("Location: {$redirectUrl}");
        exit;
    }

    /**
     * 设置用户ID
     * @param mixed $flgArr
     */
    protected function setUserId($flgArr)
    {
        if (!is_array($flgArr)) {
            $this->id = $flgArr;
        } else {
            $this->id = $flgArr['id'];
        }
    }

    /**
     * 处理高用户ID情况
     */
    protected function handleHighUserId()
    {
        $f = (new Hezi())->find($this->id);
        if ($f && $f = $f->f) {
            $flgArr = id_decode($f);
            $this->setUserId($flgArr);
        } else {
            $this->handleViolation();
        }
    }

    /**
     * 处理违规情况
     */
    protected function handleViolation()
    {
        $violationUrl = sysconfig('', 'violation_redirect_url') ?:
            'https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2';

        $response = json_encode([
            'code' => 99,
            'data' => ['url' => $violationUrl]
        ], 256);

        exit($response);
    }

    /**
     * 访问统计功能
     * 记录用户访问行为，用于数据分析
     */
    public function tongji()
    {
        try {
            $action = $this->request->action();
            $statisticsActions = ['lists', 'index'];

            if (!in_array($action, $statisticsActions)) {
                return;
            }

            $userAgent = $this->request->server('HTTP_USER_AGENT', '');
            if (empty($userAgent)) {
                return;
            }

            $ua = md5($userAgent);
            $tj = new Tj();

            // 检查是否已存在记录，避免重复统计
            if (!$tj->where(['ua' => $ua])->find()) {
                $tj->insert([
                    'uid' => $this->id ?: 0,
                    'create_time' => time(),
                    'ua' => $ua
                ]);
            }
        } catch (\Exception $e) {
            // 统计失败不影响正常业务流程，静默处理
            // 可以在这里记录日志，但不抛出异常
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