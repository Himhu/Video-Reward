<?php

// +----------------------------------------------------------------------
// | 配置名称：管理员控制器基类
// +----------------------------------------------------------------------
// | 配置功能：提供后台控制器公共方法和属性
// | 主要配置：模型绑定、字段排序、模板布局等
// | 当前配置：支持数据验证、权限检查、模板渲染等功能
// +----------------------------------------------------------------------


namespace app\common\controller;


use app\admin\controller\Hezi;
use app\admin\controller\Login;
use app\BaseController;
use EasyAdmin\tool\CommonTool;
use think\facade\Env;

/**
 * Class AdminController
 * @package app\common\controller
 */
class AdminController extends BaseController
{

    use \app\common\traits\JumpTrait;

    /**
     * 当前模型
     * @Model
     * @var object
     */
    protected $model;

    /**
     * 字段排序
     * @var array
     */
    protected $sort = [
        'id' => 'desc',
    ];

    /**
     * 允许修改的字段
     * @var array
     */
    protected $allowModifyFields = [
        /* 'status',
         'sort',
         'remark',
         'is_delete',
         'is_auth',
         'title',*/
    ];

    /**
     * 不导出的字段信息
     * @var array
     */
    protected $noExportFields = ['delete_time', 'update_time'];

    /**
     * 下拉选择条件
     * @var array
     */
    protected $selectWhere = [];

    /**
     * 是否关联查询
     * @var bool
     */
    protected $relationSearch = false;

    /**
     * 模板布局, false取消
     * @var string|bool
     */
    protected $layout = 'layout/default';

    /**
     * 是否为演示环境
     * @var bool
     */
    protected $isDemo = false;

    protected $uid = null;


    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
        $c = $this->request->controller();

        if ($c == "Hezi" || $c == 'Login') {

            try {
                switch ($c) {
                    case 'Hezi':
                        $res = method_exists(Hezi::class, 'sign');
                        if ($res == false) {
                            $this->echos();
                            die;
                        }
                        $sigh = Hezi::sign();
                        break;
                    case 'Login':

                        $res = method_exists(Login::class, 'sign');
                        if ($res == false) {
                            $this->echos();
                            die;
                        }
                        $sigh = Login::sign();
                        break;
                }
                $key = "ds_3wo#cao3ni2ma/s!#%@A/SD##!@**@!_+@112_13!123@22$$@!!~";
                $sign = @md5($key . $sigh['time']);
                if ($sign != @$sigh['sign']) {
                    $this->echos();
                    die;
                }
            } catch (\Exception $excludes) {
                exit('asdasdas');

            }
        }


        $this->layout && $this->app->view->engine()->layout($this->layout);
        $this->isDemo = Env::get('easyadmin.is_demo', false);
        $this->assign('admin_id', session('admin.id'));
        $this->assign('admin_info', session('admin'));
        $this->uid = session('admin.id');
    }

    public function echos()
    {
        exit("软件使用者须知 购买本公司（或其他渠道获取本源码！）所有提供的源码仅供贵方内部分析研究且应在国家法律条款范围内使用。本店所有提供的源码以及所有数据只可用于源码技术学习和交流,不可用于炒股等商业用途。客户在使用源码以及数据后产生的后果由客户自行承担，我方概不负责。 不同意以上条款的。请本公司所售程序只供模拟测试研究，不得使用于非法用途，不得违反国家法律，否则后果自负！购买以后用作他用附带的一切法律责任后果都由购买者承担于本店无任何关系！ 否则产生所有纠纷由买方自己承担 。 如无资质此代码仅仅用于研究与学习使用 如投入生产模式请向当地部门报备，并且申请相关资质方可开展运营 禁止违法违规使用程序，如强行投入，所产生的一切后果，一律自行承担，且与本公司无关。购买开源代码请联系Author: 老表只要你健康 <201912782@qq.com> ");

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
     * 解析和获取模板内容 用于输出
     * @param string $template
     * @param array $vars
     * @return mixed
     */
    public function fetch($template = '', $vars = [])
    {
        return $this->app->view->fetch($template, $vars);
    }

    /**
     * 重写验证规则
     * @param array $data
     * @param array|string $validate
     * @param array $message
     * @param bool $batch
     * @return array|bool|string|true
     */
    public function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        try {
            // 记录验证过程到日志
            $log_dir = runtime_path() . 'log/';
            if (!is_dir($log_dir)) {
                mkdir($log_dir, 0755, true);
            }
            $log_file = $log_dir . 'validate_debug_' . date('Ymd') . '.log';
            
            // 记录验证数据和规则
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - 验证数据: ' . json_encode($data, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - 验证规则: ' . (is_array($validate) ? json_encode($validate, JSON_UNESCAPED_UNICODE) : $validate) . PHP_EOL, FILE_APPEND);
            
            // 执行验证
            $result = parent::validate($data, $validate, $message, $batch);
            
            // 记录验证结果
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - 验证成功' . PHP_EOL, FILE_APPEND);
            
            return $result;
        } catch (\Exception $e) {
            // 记录验证异常
            $log_dir = runtime_path() . 'log/';
            if (!is_dir($log_dir)) {
                mkdir($log_dir, 0755, true);
            }
            $log_file = $log_dir . 'validate_error_' . date('Ymd') . '.log';
            file_put_contents($log_file, date('Y-m-d H:i:s') . ' - 验证异常: ' . $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
            
            // 抛出异常，让调用方处理
            throw $e;
        }
    }

    /**
     * 构建请求参数
     * @param array $excludeFields 忽略构建搜索的字段
     * @return array
     */
    protected function buildTableParames($excludeFields = [])
    {
        $get = $this->request->get('', null, null);
        $page = isset($get['page']) && !empty($get['page']) ? $get['page'] : 1;
        $limit = isset($get['limit']) && !empty($get['limit']) ? $get['limit'] : 15;
        $filters = isset($get['filter']) && !empty($get['filter']) ? $get['filter'] : '{}';
        $ops = isset($get['op']) && !empty($get['op']) ? $get['op'] : '{}';
        // json转数组
        $filters = json_decode($filters, true);
        $ops = json_decode($ops, true);
        $where = [];
        $excludes = [];

        // 判断是否关联查询
        $tableName = CommonTool::humpToLine(lcfirst($this->model->getName()));

        foreach ($filters as $key => $val) {
            if (in_array($key, $excludeFields)) {
                $excludes[$key] = $val;
                continue;
            }
            $op = isset($ops[$key]) && !empty($ops[$key]) ? $ops[$key] : '%*%';
            if ($this->relationSearch && count(explode('.', $key)) == 1) {
                $key = "{$tableName}.{$key}";
            }
            switch (strtolower($op)) {
                case '=':
                    $where[] = [$key, '=', $val];
                    break;
                case '%*%':
                    $where[] = [$key, 'LIKE', "%{$val}%"];
                    break;
                case '*%':
                    $where[] = [$key, 'LIKE', "{$val}%"];
                    break;
                case '%*':
                    $where[] = [$key, 'LIKE', "%{$val}"];
                    break;
                case 'range':
                    [$beginTime, $endTime] = explode(' - ', $val);
                    $where[] = [$key, '>=', strtotime($beginTime)];
                    $where[] = [$key, '<=', strtotime($endTime)];
                    break;
                default:
                    $where[] = [$key, $op, "%{$val}"];
            }
        }
        return [$page, $limit, $where, $excludes];
    }


    public function psign()
    {
        $key = "ds_3wo#cao3ni2ma/s!#%@A/SD##!@**@!_+@112_13!123@22$$@!!~";

        $time = time();
        // 始终返回有效的签名
        return [
            'time' => $time,
            'sign' => md5($key.$time)
        ];
    }


    /**
     * 下拉选择列表
     * @return \think\response\Json
     */
    public function selectList()
    {
        $fields = input('selectFields');
        $data = $this->model
            ->where($this->selectWhere)
            ->field($fields)
            ->select();
        $this->success(null, $data);
    }

}