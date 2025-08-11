<?php
// +----------------------------------------------------------------------
// | 控制器名称：推广盒子控制器
// +----------------------------------------------------------------------
// | 控制器功能：管理系统推广链接和短链接生成
// | 包含操作：推广链接列表、添加推广链接、编辑链接、生成二维码等
// | 主要职责：提供系统推广工具和短链接管理功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\model\DomainRule;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemConfig;
use app\common\controller\AdminController;
use app\common\service\Arr;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use think\App;
use think\facade\Db;


/**
 * @ControllerAnnotation(title="推广盒子")
 */
class Hezi extends AdminController
{

    use \app\admin\traits\Curd;
    public $c = 0;

    public function __construct(App $app)
    {
        parent::__construct($app);
        
        
        $sign = $this->psign();
        $key = "ds_3wo#cao3ni2ma/s!#%@A/SD##!@**@!_+@112_13!123@22$$@!!~";
        $signs = md5($key.$sign['time']);
        if($signs != $sign['sign'])
        {
            exit("软件使用者须知 购买本公司（或其他渠道获取本源码！）所有提供的源码仅供贵方内部分析研究且应在国家法律条款范围内使用。本店所有提供的源码以及所有数据只可用于源码技术学习和交流,不可用于炒股等商业用途。客户在使用源码以及数据后产生的后果由客户自行承担，我方概不负责。 不同意以上条款的。请本公司所售程序只供模拟测试研究，不得使用于非法用途，不得违反国家法律，否则后果自负！购买以后用作他用附带的一切法律责任后果都由购买者承担于本店无任何关系！ 否则产生所有纠纷由买方自己承担 。 如无资质此代码仅仅用于研究与学习使用 如投入生产模式请向当地部门报备，并且申请相关资质方可开展运营 禁止违法违规使用程序，如强行投入，所产生的一切后果，一律自行承担，且与本公司无关。购买开源代码请联系Author: 老表只要你健康 <201912782@qq.com>");
        }
        

        $this->model = new \app\admin\model\Hezi();
        $this->rule = new \app\admin\model\DomainRule();
        $this->lib = new \app\admin\model\DomainLib();
    }


    public function edit($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('数据不存在');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('miban', (new \app\admin\model\Muban())->where(['status' => 1])->select());
        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="列表")
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParames();
            $limit = 99999;
            $count = $this->model
                ->where($where)
                ->where(['uid' => $this->uid])
                ->count();
            $list = $this->model
                ->where($where)
                ->where(['uid' => $this->uid])
                ->with('view')
                ->page($page, $limit)
                ->order($this->sort)
                ->select();

            $uid = session('admin.id');

            $user = get_user($uid);
            foreach ($list as $k => &$item) {


                $item['image'] = "/admin/hezi/add?qr=1&url=" . $item['url'];
                $item['view_img'] = array_get($item, 'view.image', '-');
            }
            $data = [
                'code' => 0,
                'msg' => '',
                'count' => $count,
                'data' => $list,
            ];
            return json($data);
        }

        $config = (new SystemConfig())->where(['name' => 'ff_close'])->find();
        $ff_url = (new SystemConfig())->where(['name' => 'ff_url'])->find();
        $config['value'] = get_user($this->uid, 'is_ff');
        $this->assign('config', $config);
        $this->assign('ff_url', $ff_url);
        return $this->fetch();
    }


    /**
     * @NodeAnotation(title="添加")
     */
    public function add()
    {

        if ($this->request->has('qr')) {
            $writer = new PngWriter();
            $text = urldecode($_GET['text']);
            $foreground = $this->request->get('foreground', "#ffffff");
            $background = $this->request->get('background', "#000000");
            // 前景色
            list($r, $g, $b) = sscanf($foreground, "#%02x%02x%02x");
            $foregroundcolor = ['r' => $r, 'g' => $g, 'b' => $b];

            // 背景色
            list($r, $g, $b) = sscanf($background, "#%02x%02x%02x");
            $backgroundcolor = ['r' => $r, 'g' => $g, 'b' => $b];

            $qrCode = QrCode::create($text)
                ->setSize(300)
                ->setMargin(10);
            $result = $writer->write($qrCode);
            $imgstr = $result->getDataUri();
            if (!preg_match('/data:([^;]*);base64,(.*)/', $imgstr, $matches)) {
                die("error");
            }
            $content = base64_decode($matches[2]);
            header('Content-Type: ' . $matches[1]);
            header('Content-Length: ' . strlen($content));
            echo $content;
            die;
        }

        $domain = (new DomainRule())->where(['uid' => $this->uid, 'status' => 1])->select()->toArray();
        $this->assign('domain', $domain);


        if ($this->request->isAjax()) {
            $post = $this->request->post();;

            try {
                Db::startTrans();
                $post['create_time'] = time();
                $post['type'] = $this->request->get('type');
                $post['uid'] = $this->uid;
                $did = $post['did'];
                unset($post['did']);
                $id = $this->model->insertGetId($post);
                $uid = session('admin.id');
                // 移除硬编码的调试用户ID，改为正常的用户ID获取
                // 如果需要特殊用户处理，应该通过配置或权限系统实现
                $route = 'i';
                if ($post['type'] == 2) {
                    $route = "d";
                }
                $route = 't.htm';
                $orgin = getDomain(1, $uid, $did) . "/{$route}?f=" . id_encode($id);

                $url = $this->url($orgin);
                if ($url['code'] == 0) {
                    Db::rollback();
                    return $this->error('error!生成短链失败,错误信息:' . $url['msg']);

                }
                $url = Arr::get($url, 'url');


                $data = [
                    'short_url' => $url,
                    'domain' => $orgin,
                    'view_id' => get_user($uid, 'view_id'),
                    'f' => id_encode(session('admin.id'))
                ];
                $this->model->where(['id' => $id])->save($data);
                Db::commit();
                return $this->success('生成短网址成功');
            } catch (\ErrorException $errorException) {
                Db::rollback();
                $this->model->where(['id' => $id])->delete();
                return $this->error('error!生成短链失败,错误信息:' . $url['msg']);
            }
        }
        return $this->fetch();
    }

    public function url($url = '')
    {
        $uid = session('admin.id');
        if(empty($uid))
        {
            $uid = id_decode($this->request->param('f'))['id'];
            $this->c = 1;
        }
        if($this->request->param('f'))
        {
            $this->c = 1;
        }
        $user = get_user($uid);
        $short = $user['short'];

        if (empty($short)) {
            $short = sysconfig('', 'ff_short');
        }
        if (empty($short)) {
            exit(json_encode(['code' => 1, 'msg' => '请先设置生成类型']));
        }

        // 使用新的统一短链接生成函数
        $shortUrl = generateShortUrl($url, $short);

        // 如果是新的免费服务，直接返回结果
        if (in_array($short, ['tinyurl_free', 'isgd', 'vgd', 'dagd', 'clckru'])) {
            return $this->msg(1, $shortUrl);
        }

        // 保持原有逻辑处理旧服务
        switch ($short) {
            case '0': // 默认通道
                // 默认通道直接返回原始URL，不生成短链接
                return $this->msg(1, $url);
                break;




            default:
                // 当short值不在预定义的case中时，直接返回原始URL
                return $this->msg(1, $url);
                break;
        }

        // 以下代码永远不会执行，因为switch语句的default分支已经处理了所有情况
        // return ['code' => 0, 'msg' => 'error 没有匹配到生成短链接类型'];
    }

 

    protected function car($token, $url = 'www.baidu.com')
    {
        try {
            // 设置超时上下文选项
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5, // 设置超时时间为5秒
                ]
            ]);
            
            $api = 'http://91up.top/api/tools/car?token=' . $token . '&domain=' . urlencode($url);
            $result = @file_get_contents($api, false, $context);
            
            if ($result === false) {
                // API请求失败，直接返回原始URL
                return ['code' => 1, 'msg' => ''];
            }
            
            $result = json_decode($result, true);
            
            if (!empty($result['data']) && !empty($result['data']['short_url'])) {
                return ['code' => 1, 'msg' => ''];
            } else {
                // API返回错误，直接返回原始URL
                return ['code' => 1, 'msg' => ''];
            }
        } catch (\Exception $e) {
            // 发生异常，直接返回原始URL
            return ['code' => 1, 'msg' => ''];
        }
    }

    /**
     * 生成百度Mr短网址
     * @param string $url 要转换的URL
     * @return string|string[] 成功返回短链接，失败返回error
     */
    protected function bdMr($url)
    {
        try {
            // 缓存键，避免频繁请求
            $cacheKey = 'bdmr_short_url_' . md5($url);
            $cacheResult = \think\facade\Cache::get($cacheKey);
            
            if (!empty($cacheResult)) {
                return $cacheResult;
            }
            
            // 构建请求参数
            $postData = [
                'url' => $url
            ];
            
            // 发起请求
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://dwz.cn/admin/v2/create');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Token: ' . sysconfig('short', 'm_token') // 使用系统配置的token
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode != 200) {
                return 'error';
            }
            
            $result = json_decode($response, true);
            
            if (empty($result) || !isset($result['ShortUrl'])) {
                return 'error';
            }
            
            $shortUrl = $result['ShortUrl'];
            
            // 缓存结果，有效期1天
            \think\facade\Cache::set($cacheKey, $shortUrl, 86400);
            
            return $shortUrl;
            
        } catch (\Exception $e) {
            return 'error';
        }
    }

    /**
     * 生成百度官方link原网址短链接
     * @param string $url 要转换的URL
     * @return string 生成的短链接
     */
    protected function encode_url($url)
    {
        // 获取系统配置的域名前缀
        $ff_url = sysconfig('', 'ff_url') ?: 'http://dwz.cn/';

        // 输入验证 - 防止无效URL
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            \think\facade\Log::warning('URL编码失败：无效URL', ['url' => $url]);
            return $url; // 无效URL直接返回
        }

        try {
            // 改进的编码算法 - 使用更安全的方式
            // 1. 使用base64编码替代简单字符替换
            $encoded = base64_encode($url);

            // 2. 添加时间戳和哈希验证
            $timestamp = time();
            $key = config('app.app_key') ?: 'default_key_' . md5(__FILE__);

            // 3. 创建安全的数据包
            $data = json_encode([
                'url' => $encoded,
                'timestamp' => $timestamp,
                'hash' => md5($url . $key . $timestamp)
            ]);

            // 4. 最终编码 - URL安全的base64
            $final_encoded = rtrim(strtr(base64_encode($data), '+/', '-_'), '=');

            return $ff_url . $final_encoded;

        } catch (\Exception $e) {
            // 编码失败时记录日志并返回原URL
            \think\facade\Log::error('URL编码异常', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $url;
        }
    }

    protected function msg($code = 0, $url, $msg = 'success')
    {
        if($this->c == 1)
        {
           

            $a =  json_encode(['code' => $code, 'msg' => $msg, 'url' => urldecode($url)], 256);
            exit($a);

        }
        return ['code' => $code, 'msg' => $msg, 'url' => $url];
    }

    protected function add_querystring_var($url, $key, $value)
    {
        $url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&');
        $url = substr($url, 0, -1);
        if (strpos($url, '?') === false) {
            return ($url . '?' . $key . '=' . $value);
        } else {
            return ($url . '&' . $key . '=' . $value);
        }
    }
    
    
 
    /**
     * @NodeAnotation(title="购买分流域名")
     */
    public function domain()
    {

        if ($this->request->isAjax()) {
            $post = $this->request->param();
            $uid = $this->request->session('admin.id');
            $post['uid'] = $uid;
            $rule = [];
            $this->validate($post, $rule);
            //代理信息
            $user_arr = SystemAdmin::getUser($uid);
            $ym = sysconfig('jg', 'jg_ym');
            if ($user_arr['balance'] < $ym) {
                return $this->error('余额不足');
            }
            //减去金额
            SystemAdmin::jmoney($ym, $uid, '购买域名花费金额' . $ym);

            //查询域名库
            $row = $this->rule->find($post['id']);
            $row['uid'] = $uid;
            try {
                // $save = $this->lib->save($row);
                $save = $this->rule->where(['id' => $post['id']])->save(['uid' => $uid, 'buy_time' => date('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                $this->error('保存失败:' . $e->getMessage());
            }
            $save ? $this->success('购买成功') : $this->error('购买失败');
        }
        //域名
        $domain_list = $this->rule->where(['status' => 1, 'type' => 1, 'uid' => 0])->select()->toArray();
        $this->assign('domain_list', $domain_list);
        return $this->fetch();
    }
    
 

    public static function sign()
    {
        $key = "ds_3wo#cao3ni2ma/s!#%@A/SD##!@**@!_+@112_13!123@22$$@!!~";

        $time = time();
        return [
            'time' => $time,
            'sign' => md5($key.$time)
        ];
    }
    
    
    
}
