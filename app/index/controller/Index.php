<?php

namespace app\index\controller;

// 定义Dev辅助类（替代损坏的vendor文件）
if (!class_exists('Dev')) {
    class Dev {
        public static function returnJson($data = [], $code = 200) {
            return json($data, $code);
        }
    }
}

use app\admin\model\Category;
use app\admin\model\Complain;
use app\admin\model\DomainLib;
use app\admin\model\DomainRule;
use app\admin\model\Hezi;
use app\admin\model\Link;
use app\admin\model\Muban;
use app\admin\model\Point;
use app\admin\model\PointDecr;
use app\admin\model\PointLog;
use app\admin\model\Price;
use app\admin\model\Stock;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemConfig;
use app\common\controller\IndexBaseController;
use app\common\service\Arr;
use Endroid\QrCode\QrCode;
use think\Collection;
use app\admin\model\Payed;
use think\Db;
// use think\Dev; // 已损坏，使用自定义Dev类
use think\Model;
use app\admin\model\PaySetting;

class Index extends IndexBaseController
{
    //按钮间距
    public $px = '15px';
    public function initialize()
    {
        parent::initialize();
    }

    //判断微信
    public function cm_isweixin()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER))
        {
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
            {
                return true;
            }
        }
        return false;
    }


    //判断是否为QQ浏览器
    public function cm_qqbrowser()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ') !== false)
        {
            if (strpos($_SERVER['HTTP_USER_AGENT'], '_SQ_') !== false)
            {
                return "QQ";  //QQ内置浏览器
            } else
            {
                return "QQBrowser";  //QQ浏览器
            }
        }
        return false;

    }

    //判断是否为微博浏览器
    public function cm_weibo()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER))
        {
            if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), '__weibo__') !== false)
            {
                return true;
            }
        }
        return false;
    }


    function is_weixin()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)
        {
            return 1;//是
        }
        return 0;//不是
    }

    function is_qqbrowser()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MQQBrowser/') !== false)
        {
            return true;
        }
        return false;
    }

    function get_ip($num_ip = false)
    {
        //判断服务器是否允许$_SERVER
        if ($this->is_weixin() && Arr::get($_SERVER, 'HTTP_X_FORWARDED_FOR_POUND'))
        {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR_POUND'] ?? '';
        } elseif ($this->is_qqbrowser() && Arr::get($_SERVER, 'HTTP_X_FORWARDED_FOR_POUND'))
        {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR_POUND'] ?? '';
        } else
        {
            if (isset($_SERVER))
            {

                if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                {
                    $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } elseif (isset($_SERVER['HTTP_CLIENT_IP']))
                {
                    $realip = $_SERVER['HTTP_CLIENT_IP'];
                } else
                {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
            } else
            {
                //不允许就使用getenv获取
                if (getenv("HTTP_X_FORWARDED_FOR"))
                {
                    $realip = getenv("HTTP_X_FORWARDED_FOR");
                } elseif (getenv("HTTP_CLIENT_IP"))
                {
                    $realip = getenv("HTTP_CLIENT_IP");
                } else
                {
                    $realip = getenv("REMOTE_ADDR");
                }
            }
        }
        if ($num_ip)
        {
            return sprintf('%u', ip2long($realip));
        }
        return $realip;
    }


    //入口
    public function index()
    {

        $ua = $this->request->param('ua');

        $url = $this->request->url();
        $ip  = $this->get_ip();
        if ((new Complain())->where(['ip' => $ip])->select()->toArray())
        {
            return json(['code' => 99, 'data' => ['url' => 'https://weixin110.qq.com/cgi-bin/mmspamsupport-bin/newredirectconfirmcgi?main_type=2&evil_type=0&source=2']]);

        }
        //分享
        if ($ua)
        {
            $ip    = $this->get_ip();
            $udata = (new Point())->find($ua);
            $log   = (new PointLog())->where(['uid' => $ua, 'ip' => $ip])->find();
            if ($udata && empty($log) == true)
            {
                (new PointLog())->insert([
                    'uid'  => $ua,
                    'ip'   => $ip,
                    'time' => date('Y-m-d H:i:s')
                ]);
                $point = Point::find($ua);
                $val   = $point->point += sysconfig('site', 'shar_point');
                $point->save(['point' => $val]);
            }
            //$hezi = (new Hezi())->where(['type' => 2, 'uid' => $this->id])->find();
            $f_param = $this->request->param('f');
            if (!empty($f_param)) {
                $decoded_f = id_decode($f_param);
                if ($decoded_f) {
                    $hezi = (new Hezi())->find($decoded_f);
                    if ($hezi)
                    {
                        return json(['code' => 99, 'data' => ['url' => $hezi->short_url]]);
                        exit();
                    }
                }
            }
            // exit('未找到盒子链接error!');
        }


        $data = [];

        //禁止pc打开
        $ff_pc         = sysconfig('ff', 'ff_pc');
        $data['ff_pc'] = $ff_pc;
        $this->assign('pc', $ff_pc);
        $param    = $this->request->param();
        $userInfo = SystemAdmin::getUser($this->id);


        $f            = $this->request->param('f');
        $data['h_id'] = $f;

        // 安全地解码f参数
        $hezi = null;
        if (!empty($f)) {
            $hezi = id_decode($f);
        }

        $this->assign('hezi', '');
        $this->assign('f', $f ?: 'default'); // 确保f参数有默认值
        $heziUrl = '';
        $view_id = 0;
        $type    = 1;

        // 如果成功解码并找到盒子信息
        if ($hezi)
        {
            $h = (new Hezi())->find($hezi);
            if ($h)
            {
                $type = $h->type;

                $data['hezi'] = $h->hezi_url;
                $this->assign('hezi', $h->hezi_url);
                $data['f'] = $h->f;
                $this->assign('f', $h->f);
                $this->assign('view_id', $h->view_id);
                $data['view_id'] = $h->view_id;
            }
        }
        $domainArr = [
            'https://weibo.com',
            'https://baidu.com',
            'https://sougou.com',
            'https://zhihu.com',
        ];


        $jj = $domainArr[array_rand($domainArr)];
        //$viewId = get_user($this->id, 'view_id');
        //获取炮灰域名
        $d = $this->getDomain(2, $this->id);
        if (empty($d))
        {
            // 如果没有配置炮灰域名，使用当前访问的域名
            $currentDomain = $this->request->domain();
            if (!empty($currentDomain)) {
                $d = $currentDomain;
            } else {
                // 如果当前域名也获取不到，使用本地域名
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $d = $protocol . '://' . $_SERVER['HTTP_HOST'];
            }
        } else {
            // 确保域名格式正确，使用与当前请求相同的协议
            if (!preg_match('/^https?:\/\//', $d)) {
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $d = $protocol . '://' . $d;
            }
        }

        $domain = $d . "/m/#/";
        //统一跳转到首页，不再区分短视频和普通视频路由
        // if ($type == 2)
        // {
        //     $domain .= "site";
        // }
        $data['domain'] = $domain;

        // 如果是AJAX请求或者有特定参数，返回JSON
        if ($this->request->isAjax() || $this->request->param('format') == 'json') {
            return json(['code' => 0, 'data' => $data]);
        }

        // 否则渲染视图
        $this->assign('url', $domain);

        // 确保view_id有默认值，避免Vue应用报"缺少必要参数"错误
        $view_id = $data['view_id'] ?? ($this->id ?? 1);
        $f_param = $this->request->param('f', '');

        // 如果没有f参数，为首页访问设置默认值
        if (empty($f_param)) {
            $f_param = 'default'; // 设置默认f参数
        }

        // 确保hezi参数有默认值
        $hezi_param = $data['hezi'] ?? $this->request->param('hezi', '');

        // 验证和清理参数
        $view_id = is_numeric($view_id) ? intval($view_id) : 1;
        $f_param = htmlspecialchars($f_param, ENT_QUOTES, 'UTF-8');
        $hezi_param = htmlspecialchars($hezi_param, ENT_QUOTES, 'UTF-8');

        $this->assign('view_id', $view_id); // 传递view_id变量
        $this->assign('f', $f_param); // 传递f参数
        $this->assign('hezi', $hezi_param); // 传递hezi参数
        $this->assign('pc', $data['ff_pc']); // 传递PC端配置
        $this->assign('url', $domain); // 传递完整的跳转URL
        return $this->fetch("list/jump");
    }

    //落地
    public function lists($f)
    {


        $ip = $this->request->ip();
        $ip = (new Complain())->where('ip', "=", $ip)->find();
        if ($ip)
        {
            return $this->errors();
        }
        $hezi = $this->request->param('hezi');
        cookie('ua', md5($this->request->server('HTTP_USER_AGENT')), 3600);
        $domain = $this->request->host() . $this->request->url();
        //$this->assign('pay' , $this->pay($f));
        $this->assign('fov', $domain);
        $this->assign('hezi', ['video' => '']);
        if ($hezi)
        {
            $this->assign('hezi', (new Hezi())->find($hezi));
        }
        $userInfo = SystemAdmin::getUser($this->id);
        $view     = $this->muBan;

        $view_id = $userInfo['view_id'];
        if ($this->request->has('view_id'))
        {
            $view_id = $this->request->param('view_id');
            session('view_id', $view_id);
        }
        if (session('view_id'))
        {
            $view_id = session('view_id');
        }
        $muban = (new Muban())->find($view_id);
        if (!empty($muban))
        {
            $view = $muban['muban'];
        }
        $data = Category::field(['id', 'image', 'ctitle as title'])->select()->toArray();
        $this->assign('cat', $data);
        $this->assign('cookieip', '');
        $this->assign('userinfo', $userInfo);
        $this->assign('domain', $this->request->domain());
        //显示前台模版
        return $this->fetch("list/" . $view);
    }


    ///index/index/jiance?f=TURBd01EQXdNREF3TUlXbnF0MA&type=wx   域名检测地址
    public function jiance()
    {


        $type = $this->request->param('type');
        $d    = $this->request->param('d');
        //各代理下域名
        $domain = (new DomainRule())->where(['status' => 1, 'type' => $d])
            ->where('uid', '<>', '10086')
            ->where('uid', '<>', '0')
            ->group('uid')->select()->toArray();
        //公共域名
        $publicDomain = (new DomainRule())->where(['status' => 1, 'type' => $d, 'uid' => '10086'])->limit(1)->select()
            ->toArray();
        //待分配
        $deay   = (new DomainRule())->where(['status' => 1, 'type' => $d, 'uid' => 0])->limit(1)->select()->toArray();
        $domain = array_merge($domain, $publicDomain);
        $domain = array_merge($domain, $deay);
        if (empty($domain))
        {
            echo '没有域名请检查!';
        }

        foreach ($domain as &$item)
        {
            $res = '';
            if ($type == 'qq')
            {
                $res = qq($item['domain']);
            }
            if ($type == 'wx')
            {
                $res = wechat($item['domain']);
            }
            if ($res == 2)
            {
                (new DomainRule())->where(['id' => $item['id']])->save(['status' => 0]);
                echo "检测到域名禁用" . $item['domain'] . PHP_EOL;
                continue;
            }
            if ($res == 1)
            {
                echo "域名正常" . $item['domain'] . PHP_EOL . "<br>";
                continue;
            }
            if (empty($res))
            {
                echo '检测异常' . PHP_EOL . "<br>";
            }
            echo $res;

        }
    }


    ///index/index/qq_jiance?f=TURBd01EQXdNREF3TUlXbnF0MA&type=qq   域名检测地址

    public function qq_jiance()
    {

        //dd(qq("http://pohub.com"));die;
        $domain = (new DomainLib())->where(['q_status' => 1])->select()->toArray();
        dd($domain);
        die;
        $type = $this->request->param('type', 'qq');
        foreach ($domain as &$item)
        {

            $arr  = file_get_contents("https://api.uouin.com/app/qq?username=sd001&key=ZBeyOyOV148Kmo9&url=" . urlencode($item['domain']));
            $json = json_decode($arr, true);
            echo "<pre>";
            print_r($json);
            $code = $json['code'];
            if ($code == "-208")
            {
                echo $json['msg'] . PHP_EOL;
                continue;
            }
            //拦截
            if ($code == "1002")
            {
                (new DomainLib())->where(['id' => $item['id']])->save(['q_status' => 0]);
                echo "检测到域名禁用" . $item['domain'] . PHP_EOL;
            }
            sleep(1);
        }
    }


    //分类
    public function pagecat()
    {
        $data = Category::field(['id', 'image', 'ctitle as title'])->select()->toArray();


        $this->assign('cat', $data);
        $this->assign('cats', $data);
        return $this->fetch("list/cat");
    }

    //视频播放页
    public function video()
    {
        $ip = $this->request->ip();

        $ip = (new Complain())->where('ip', "=", $ip)->find();
        if ($ip)
        {
            return $this->errors();
        }


        $ip  = $this->request->ip();
        $ua  = $this->request->param("murmur");
        $vid = $this->request->param('vid');

        $linkInfo = (new Stock())->find($vid);
        if (empty($linkInfo))
        {
            return $this->error("视频资源丢失!", '', '', 200);
        }

        $payedVid = $this->getPayedVideoId();
        $vidArr   = $payedVid['vid'];
        $pay      = 0;
        $vid      = array_intersect($vidArr, [$vid]);


        $payed = false;
        if ($vid)
        {
            $where['vid'] = array('in', $vid);

            $payed = (new Payed())
                ->whereIn('vid', $vid)
                ->where('expire', '>', time())
                ->where('uid', $this->id) //加这里
                ->where(function ($query) use ($ip, $ua) {
                    return $query->whereOr('ip', "=", $ip)->whereOr('ua', "=", $ua);
                })
                ->find();

            if ($payed)
            {
                $pay = 1;
            }
        }


        if ($payedVid['is_date'] == 2 || $payedVid['is_month'] == 2 || $payedVid['is_week'] == 2)
        {
            $pay   = 1;
            $payed = true;
        }

        if ($payed == false)
        {
            return $this->error('视频不存在!或者已过期，请重新从入口进入点已购观看!', '', '', 200);
        }


        $data = strrev(base64_encode(json_encode(['payed' => $payed, 'link' => $linkInfo])));
        return $this->success('', ['status' => 1, 'msg' => '', 'data' => $data]);
        $this->assign('payed', $payed);
        $this->assign('link', $linkInfo);
        return view('video');
    }

    /**
     * 获取混合视频列表（短视频+普通视频）
     */
    public function mixed_video_list()
    {
        $f     = $this->request->param('f');
        $key   = $this->request->param('key');
        $cid   = $this->request->param('cid');
        $payed = $this->request->param('payed');
        $page  = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 15);

        $domain   = $this->getDomain(1, $this->id);
        $payedVid = $this->getPayedVideoId();

        // 不限制视频类型，获取所有视频
        $where = [];

        $link = (new Stock());
        if ($key) {
            $link = $link->whereLike('title', "%{$key}%");
        }
        if ($cid) {
            $link = $link->where('cid', $cid);
        }
        if ($payed) {
            $link = $link->whereIn('id', $payedVid['vid']);
        }

        $link = $link->where($where)
            ->field(['id', 'cid', 'url', 'title', 'image', 'time', 'is_dsp'])
            ->orderRaw('rand()')
            ->paginate([
                'list_rows' => $limit,
                'page' => $page,
            ]);

        $userInfo = get_user($this->id);
        if (!$userInfo) {
            $userInfo = ['is_dan' => 1, 'dan_fee' => 6];
        }

        $m = $userInfo['is_dan'] == 1 ? $userInfo['dan_fee'] : rand(
            (new SystemConfig())->where('name', 'fb_min_money')->value('value'),
            (new SystemConfig())->where('name', 'fb_max_money')->value('value')
        );

        $dspsk = sysconfig('short_video', 'dspsk');
        $ppvd_params = sysconfig('short_video', 'ppvd_params');

        foreach ($link as &$item) {
            // 短视频特殊处理
            if ($item['is_dsp'] == 1) {
                $item['id'] = $item['id'] . time() . rand(0, 9999);
            }

            if (in_array($item['id'], $payedVid['vid']) ||
                ($payedVid['is_week'] == 2 || $payedVid['is_date'] == 2 || $payedVid['is_month'] == 2)) {
                $item['pay'] = 1;
                $item['video_url'] = $item['url'];
                $item['pay_url'] = $item['url'];
                $item['url'] = $domain . "/index/index/video?vid={$item['id']}&f={$f}";

                // 只对短视频应用试看参数
                if ($dspsk == 1 && $item['is_dsp'] == 1) {
                    $item['video_url'] = $item['video_url'] . $ppvd_params;
                }
            } else {
                $item['rand'] = rand(5000, 9999);
                $item['read_num'] = rand(5000, 9999);
                $item['video_url'] = $item['url'];

                // 只对短视频应用试看参数
                if ($dspsk == 1 && $item['is_dsp'] == 1) {
                    $item['video_url'] = $item['video_url'] . $ppvd_params;
                }

                $item['pay_url'] = $item['url'];
                $item['money'] = $m;
                $item['read_num1'] = rand(91, 99);
                $item['url'] = $domain . "/index/trading/index?vid={$item['id']}&f={$f}&m={$m}";
            }

            // 添加视频类型标识
            $item['video_type'] = $item['is_dsp'] == 1 ? 'short' : 'normal';
            $item['type_name'] = $item['is_dsp'] == 1 ? '短视频' : '普通视频';
            $item['img'] = $item['image'];
        }

        $total = $link->total();
        $list = $link->getCollection()->toArray();
        shuffle($list);

        // 返回配置信息
        $config = [
            'dsp_notify' => sysconfig('site', 'dsp_notify'),
            'shar_box_text' => sysconfig('site', 'shar_box_text'),
            'rvery_point' => sysconfig('site', 'rvery_point'),
            'zbwl' => sysconfig('short_video', 'zbwl'),
            'dspsk' => sysconfig('short_video', 'dspsk'),
        ];

        return json([
            'status' => 1,
            'msg' => '获取成功',
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
                'config' => $config
            ]
        ]);
    }


    public function payed_list()
    {
        return $this->vlist();
    }


    public function getUserInfo()
    {
        $f      = $this->request->param('f');
        $domain = $this->getDomain(1, $this->id);
        if ($domain)
        {
            return json(['status' => 1, 'msg' => '', 'data' => $domain]);

        }
        return json(['status' => 0, 'msg' => '', 'data' => $domain]);


    }

    public function vlist()
    {


        $f     = $this->request->param('f');
        $key   = $this->request->param('key');
        $cid   = $this->request->param('cid');
        $payed = $this->request->param('payed');
        $type  = $this->request->param('type', 'mixed');
        // $userInfo = Admin::getUser($this->id);
        $domain   = $this->getDomain(1, $this->id);
        $payedVid = $this->getPayedVideoId();
        $where    = [
            //'uid' => ['=', $this->id]
        ];

        // 根据type参数决定获取哪种类型的视频
        if ($type !== 'mixed' && in_array($type, [0, 1])) {
            $where['is_dsp'] = $type;
        }
        // 当type='mixed'或未指定时，获取所有类型的视频


        $link = (new Stock());
        //$link = $link->where(['uid' => $this->id]);
        if ($key)
        {
            $link = $link->whereLike('title', "%{$key}%");
        }
        if ($cid)
        {
            $cat   = new Category();
            $cname = $cat->find($cid);
            $cname = $cname->ctitle;
            $link  = $link->whereLike('title', "%{$cname}%");
        }
        if ($payed && ($payedVid['is_week'] == 1 && $payedVid['is_date'] == 1 && $payedVid['is_month'] == 1))
        {
            //$link = $link->whereIn(['id' => $payedVid['vid'], 'uid' => $this->id]);
            // $link = $link->whereIn('id' ,  $payedVid['vid'])->where('uid',"=",$this->id);
            $link = $link->whereIn('id', $payedVid['vid']);
        }
        $pageSize = $this->request->param('row', 15);
        // 统一分页大小，混合显示时使用15个，兼顾两种类型的展示需求
        $link = $link->where($where)->field(['id', 'cid', 'url', 'title', 'image', 'time', 'is_dsp'])->orderRaw('rand()')
            ->paginate($pageSize);


        $userInfo = get_user($this->id);

        // 如果没有用户信息（API调用），使用默认值
        if (!$userInfo) {
            $userInfo = [
                'is_dan' => 1,
                'dan_fee' => 6
            ];
        }

        //固定
        if ($userInfo['is_dan'] == 1)
        {
            $m = $userInfo['dan_fee'];
        }
        //随机
        if ($userInfo['is_dan'] == 2)
        {
            $fb_min_money = (new SystemConfig())->where('name', 'fb_min_money')->value('value');
            $fb_max_money = (new SystemConfig())->where('name', 'fb_max_money')->value('value');
            $m            = rand($fb_min_money, $fb_max_money);
        }

        $dspsk       = sysconfig('short_video', 'dspsk');
        $ppvd_params = sysconfig('short_video', 'ppvd_params');
        foreach ($link as &$item)
        {
            // 根据每个视频的is_dsp字段判断是否为短视频
            if ($item['is_dsp'] == 1)
            {
                $item['id'] = $item['id'] . time() . rand(0, 9999);
            }
            if (in_array($item['id'], $payedVid['vid']) || ($payedVid['is_week'] == 2 || $payedVid['is_date'] == 2 || $payedVid['is_month'] == 2))
            {
                $item['pay']       = 1;
                $item['video_url'] = $item['url'];
                $item['pay_url']   = $item['url'];
                $item['url']       = $domain . "/index/index/video?vid={$item['id']}&f={$f}";
                // 只对短视频应用试看参数
                if ($dspsk == 1 && $item['is_dsp'] == 1)
                {
                    $item['video_url'] = $item['video_url'] . $ppvd_params;
                }

                $item['img']  = $item['image'];
                $item['rand'] = rand(5000, 9999);

            } else
            {

                #$item['pay'] = 1;
                #$item['url'] = $domain . "/index/index/video?vid={$item['id']}&f={$f}";
                // $item['pay'] = 1;
                $item['rand']      = rand(5000, 9999);
                $item['read_num']  = rand(5000, 9999);
                $item['video_url'] = $item['url'];
                // 只对短视频应用试看参数
                if ($dspsk == 1 && $item['is_dsp'] == 1)
                {
                    $item['video_url'] = $item['video_url'] . $ppvd_params;
                }
                $item['pay_url']   = $item['url'];
                $item['money']     = $m;
                $item['img']       = $item['image'];
                $item['read_num1'] = rand(91, 99);
                $item['url']       = $domain . "/index/trading/index?vid={$item['id']}&f={$f}&m={$m}";

            }

            // 添加视频类型标识，方便前端区分显示
            $item['video_type'] = $item['is_dsp'] == 1 ? 'short' : 'normal';
            $item['type_name'] = $item['is_dsp'] == 1 ? '短视频' : '普通视频';

            // $item['pay'] = 1;

        }
        $total = $link->total();
        $list  = $link->getCollection()->toArray();


        shuffle($list);

        $data = $list;

        if ($this->request->param('encode', 0) == 0)
        {
            $data = strrev(base64_encode(json_encode($list)));
        }

        return Dev::returnJson(['status' => 1, 'msg' => '', 'data' => $data, 'total' => $total]);
    }

    public function config()
    {
        $ua       = $this->request->param('murmur', '');
        $zbkg     = (new SystemConfig())->where(['name' => 'zbkg'])->find();
        $zb_t_img = (new SystemConfig())->where(['name' => 'zb_t_img'])->find();

        // 如果没有ua参数，expire设为null，避免数据库查询错误
        $expire = null;
        if (!empty($ua)) {
            $expire = (new Payed())->where(['ua' => $ua, 'is_date' => 2])
                ->where('expire', '>', time())
                ->find();
        }
        $list     = [
            'zbkh'          => $zbkg,
            'zb_t_img'      => $zb_t_img,
            'ff_pc'         => sysconfig('ff', 'ff_pc'),
            'dsp_notify'    => sysconfig('site', 'dsp_notify'),
            'shar_box_text' => sysconfig('site', 'shar_box_text'),
            'rvery_point'   => sysconfig('site', 'rvery_point'),
            'zbwl'          => sysconfig('short_video', 'zbwl'),
            'dspsk'         => sysconfig('short_video', 'dspsk'),
            'money'         => !empty($this->id) ? get_user($this->id, 'date_fee') : 0,
            'ex'            => (empty($expire) || !isset($expire->expire)) ? '0' : date('Y-m-d H:i:s', $expire->expire),
            'z_d'           => !empty($this->id) ? $this->getDomain(1, $this->id) : ''
        ];

        $data = strrev(base64_encode(json_encode($list)));
        return Dev::returnJson(['data' => $data]);
    }

    public function cat()
    {
        $limit = $this->request->param('limit', 9999);
        $data  = Category::field(['id', 'image', 'ctitle as title'])->limit($limit)->select();
        if ($this->request->param('encode') == 1)
        {
            exit(json_encode(['status' => 1, 'msg' => 'success', 'data' => $data]));
        }
        $data = base64_encode(json_encode($data));
        $data = strrev($data);
        $data = str_replace('==', '', $data);
        $data = str_replace('=', '', $data);
        echo json_encode(['status' => 1, 'msg' => 'success', 'data' => $data]);
    }

    public function getPay($config = '', $name = '', $field = '',$userInfo)
    {
        //$userInfo = get_user($this->id);
        $open     = $userInfo[$name];
        $config   = sysconfig('jg', $config);
        if ($config == 0)
        {
            return 0;
        }
        if ($open == 0)
        {
            return 0;
        }

        return 1;
    }

    public function pays($f)
    {

        $userInfo  = get_user($this->id);

        // 如果没有用户信息（API调用），使用默认值
        if (!$userInfo) {
            $userInfo = [
                'is_dan' => 1,
                'dan_fee' => 6
            ];
        }
        $m         = $this->request->param('money', 0);
        $vid       = $this->request->param('vid', 0);
        $murmur    = $this->request->param("murmur");
        $stockInfo = (new Stock())->where(['id' => $vid])->find();

        //模式选择
        $pay_type = sysconfig('pay', 'pay_type');
        $pay_type = 'zhiguan';

        $price1 = (new Price())->where(['pay_model' => 1, 'uid' => $this->id])->select()->toArray();
        $price2 = (new Price())->where(['pay_model' => 2, 'uid' => $this->id])->select()->toArray();


        $px = $this->px;

        $adminUser = get_user(1);
        if (empty($userInfo['pay_model']))
        {
            $userInfo['pay_model'] = sysconfig('pay', 'pay_zhifu');
        }
        if (empty($userInfo['pay_model1']))
        {
            $z                      = sysconfig('pay', 'pay_zhifu1');
            $userInfo['pay_model1'] = empty($z) ? '-' : $z;
        }

        $fu1 = (new PaySetting())->where(['pay_model' => $userInfo['pay_model']])->value('pay_fudong');
        $fu2 = (new PaySetting())->where(['pay_model' => $userInfo['pay_model1']])->value('pay_fudong');

        $pay = [];
        
        
        
        
        
        if($price2 && $userInfo['pay_model1'] != '-')
        {
            $price2 = array_get($price2,0,[]);
            $price2['pay_model'] = $userInfo['pay_model'];
            $price2['pay_model1'] = $userInfo['pay_model1'];
            $pays = $this->payPrice(2,$price2,$murmur,$fu2);
            
            
            $pay = array_merge($pay,$pays);
        }
        
        
        if($price1)
        {
            $price1 = array_get($price1,0,[]);
            $price1['pay_model'] = $userInfo['pay_model'];
            $price1['pay_model1'] = $userInfo['pay_model1'];
            $pays = $this->payPrice(1,$price1,$murmur,$fu1);
            if($userInfo['pay_model1'] != '-')
            {
               $pays[0]['css'] = "{backgroundColor:'#f6ff00',color:'red',height:'35px',lineHeight:'35px',fontWeight:'bold',borderRadius: '15px',marginTop:'$px'}";

            }

            $pay = array_merge($pay,$pays);
        }
        


        if ($pay_type == 'zhiguan')
        {
            $userInfo['pay_model1'] = '-';
        }
        $data['user'] = Arr::except($userInfo, ['push_all', 'username', 'tx_img', 'kouliang', 'ticheng', 'balance', 'pwd', 'txpwd', 'password', 'token', 'tx_password']);
        if ($stockInfo)
        {
            $data['stock'] = [
                'title'  => $stockInfo['title'],
                'ds_img' => $stockInfo['image']
            ];
        }
        if ($vid === 'dsp')
        {
            //此处为短视频弹窗..
            $pay = [
                'name'  => "包月特价 {$userInfo['date_fee']} 元",
                'url'   => "/index/trading/index?is_date=2",
                'flg'   => 'date_fee',
                'css'   => '',
                'money' => $userInfo['date_fee']
            ];
        }
        $data['pay'] = $pay;
        return json($data);
    }

    public function payPrice($payModel, $userInfo,$murmur,$fu)
    {
        
        $domain = "http://".$this->request->server('HTTP_HOST');
        
        $px = $this->px;

        $map = [
            1=>[
                'img' => '微信',
                'text' => '<img src="'.$domain.'/images/weixin.png" class="btn-img" alt="微信">',
                'model' => $userInfo['pay_model']
            ],
            2=>[
                'img' => '支付宝',
                'text' => '<img src="'.$domain.'/images/zhifubao.png" class="btn-img" alt="支付宝">',
                'model' => $userInfo['pay_model1']
            ]
        ];

        //固定
        if ($userInfo['is_dan'] == 1)
        {
            $m = $userInfo['dan_fee'];
        }
        //随机
        if ($userInfo['is_dan'] == 2)
        {
            $fb_min_money = (new SystemConfig())->where('name', 'fb_min_money')->value('value');
            $fb_max_money = (new SystemConfig())->where('name', 'fb_max_money')->value('value');
            $m            = rand($fb_min_money, $fb_max_money);
        }
        $text = array_get($map,"{$payModel}.text");
        $img = array_get($map,"{$payModel}.img");
        $model = array_get($map,"{$payModel}.model");
        //直观模式
        if ($userInfo['pay_model1'] != '-' && $userInfo['pay_model'] != '-')
        {
            //双通道直观模式文案
            $j   = $this->jisuan($m, $fu);
            $pay = [
                [
                    //双通道单片文案
                    'name'  => "{$text}{$img}单部购买<span style='color:red; font-size:24px; font-weight:bold;' > {$j}</span>元",
                    'url'   => "/index/trading/index?&murmur=$murmur&model=$model&money={$j}&self_money={$m}",
                    'flg'   => 'dan',
                    'css'   => "{backgroundColor:'#f6ff00',color:'red',borderBottomLeftRadius: '15px',borderBottomRightRadius: '15px',fontWeight:'bold'}",
                    'money' => $j,
                    'self_money' => $m
                ]
            ];
        } else
        {
           
            $j   = $this->jisuan($m, $fu);
            $pay = [
                [
                     //单通道文案
                    'name'  => "确认支付<span style='color:red; font-size:24px; font-weight:bold;' > {$j}</span>元打赏观看",
                    'url'   => "/index/trading/index?&murmur=$murmur&model=$model&money={$j}&self_money={$m}",
                    'flg'   => 'dan',
                    'css'   => "{backgroundColor:'#f6ff00',color:'red',borderBottomLeftRadius: '15px',borderBottomRightRadius: '15px',fontWeight:'bold'}",
                    'money' => $j,
                    'self_money' => $m
                ]
            ];
        }


        $dp = $this->getPay('jg_topen', 'is_day', 'date_fee',$userInfo);
        if ($dp == 1)
        {

            if ($userInfo['pay_model1'] != '-' && $userInfo['pay_model'] != '-')
            {
                $j = $this->jisuan($userInfo['date_fee'], $fu);
                array_push($pay, [
                    //双通道包天文案
                    'name'  => "{$text}{$img}包天观看{$j}元",
                    'url'   => "/index/trading/index?is_date=2&model=$model&money={$j}&self_money={$userInfo['date_fee']}",
                    'flg'   => 'date_fee',
                    'css'   => "{backgroundColor:'#FD0000',color:'#FFE52C',height:'35px',lineHeight:'35px',borderRadius: '15px',marginTop:'$px'}",
                    'money' => $j,
                    'self_money' => $userInfo['date_fee']
                ]);
            }
            else
            {
               
                $j = $this->jisuan($userInfo['date_fee'], $fu);
                array_push($pay, [
                     //单通道 包天文案
                    'name'  => "包天特价全站观看 {$j} 元【推荐】",
                    'url'   => "/index/trading/index?is_date=2&model=$model&money={$j}&self_money={$userInfo['date_fee']}",
                    'flg'   => 'date_fee',
                    'css'   => "{backgroundColor:'#FD0000',color:'#FFE52C',height:'35px',lineHeight:'35px',borderRadius: '15px',marginTop:'$px'}",
                    'money' => $j,
                    'self_money' => $userInfo['date_fee']
                ]);
            }
        }


        $bz = $this->getPay('jp_wopen', 'is_week', '',$userInfo);
        if ($bz > 0)
        {
            if ($userInfo['pay_model1'] != '-' && $userInfo['pay_model'] != '-')
            {

                //直观模式-双通道包周文案
                $j = $this->jisuan($userInfo['week_fee'], $fu);
                array_push($pay, [
                    'name'  => "{$text}包周全站观看 {$j} 元",
                    'url'   => "/index/trading/index?is_week=2&model=$model&money={$j}&self_money={$userInfo['week_fee']}",
                    'flg'   => 'month_fee',
                    'css'   => "{backgroundColor:'#FD0000',color:'#FFE52C',height:'35px',lineHeight:'35px',borderRadius: '15px',marginTop:'$px'}",
                    'money' => $j,
                    'self_money' => $userInfo['week_fee']
                ]);
            } else
            {
                //直观模式-单通道包周文案
                $j = $this->jisuan($userInfo['week_fee'], $fu);
                array_push($pay, [
                    'name'  => "包周全站观看 {$j} 元",
                    'url'   => "/index/trading/index?is_week=2&model=$model&money={$j}&self_money={$userInfo['week_fee']}",
                    'flg'   => 'month_fee',
                    'css'   => "{backgroundColor:'#FD0000',color:'#FFE52C',height:'35px',lineHeight:'35px',borderRadius: '15px',marginTop:'$px'}",
                    'money' => $j,
                    'self_money' => $userInfo['week_fee']
                ]);
            }
        }

        $by = $this->getPay('jg_yopen', 'is_month','',$userInfo);
        if ($by > 0)
        {
            if ($userInfo['pay_model1'] != '-' && $userInfo['pay_model'])
            {

                //直观模式 双通道文案
                $j = $this->jisuan($userInfo['month_fee'], $fu);
                array_push($pay, [
                    'name'  => "{$text}包月全站观看 {$j} 元",
                    'url'   => "/index/trading/index?is_month=2&model=$model&money={$j}&self_money={$userInfo['month_fee']}",
                    'flg'   => 'month_fee',
                    'css'   => "{backgroundColor:'#FD0000',color:'#FFE52C',height:'35px',lineHeight:'35px',borderRadius: '15px',marginTop:'$px'}",
                    'money' => $j,
                    'self_money' => $userInfo['month_fee']
                ]);
            } else
            {
                //直观模式 单通道文案
                $j = $this->jisuan($userInfo['month_fee'], $fu);
                array_push($pay, [
                    'name'  => "包月全站观看 {$j} 元",
                    'url'   => "/index/trading/index?is_month=2&model=$model&money={$j}&self_money={$userInfo['month_fee']}",
                    'flg'   => 'month_fee',
                    'css'   => "{backgroundColor:'#FD0000',color:'#FFE52C',height:'35px',lineHeight:'35px',borderRadius: '15px',marginTop:'$px'}",
                    'money' => $j,
                    'self_money' => $userInfo['month_fee']
                ]);
            }
        }

        return $pay;
    }


    public function jisuan($m, $fudong = 0)
    {


        if ($fudong == 0 || empty($fudong))
        {

            return $m;
        }

        $j = ($m * $fudong) / 100;


        return ceil($j + $m);

    }

    public function pay($f)
    {

        return $this->pays($f);
    }

    protected function getPayedVideoId()
    {
        $ip = $this->request->ip();
        $ua = $this->request->param("murmur");
        # $ua = $this->request->cookie('ua');
        // $where['ip'] = ['=',$ip];
        //$where['uid'] = ['=',$this->id];
        $where['expire'] = ['>', time()];
        //ua 条件待定
        $pay = (new \app\admin\model\Payed())
            ->where('uid', $this->id)
            ->where('expire', '>', time())
            ->where(function ($q) use ($ip, $ua) {
                //return $q->whereRaw(" (ip = '{$ip}' or ua = '{$ua}') "); //判断ip加浏览器指纹ua
                return $q->whereRaw(" ( ua = '{$ua}') "); //判断ip加浏览器指纹ua
                //   return $q->whereRaw(" (ip = '{$ip}') ");//单判断ip
            })->select()->toArray();


        $is_date  = 1;
        $is_month = 1;
        $is_week  = 1;
        foreach ($pay as $k => $item)
        {
            //是否有包天
            if ($item['is_date'] == 2 && $item['expire'] > time())
            {
                $is_date = 2;
            }
            if ($item['is_month'] == 2 && $item['expire'] > time())
            {
                $is_month = 2;
            }
            if ($item['is_week'] == 2 && $item['expire'] > time())
            {
                $is_week = 2;
            }
        }
        return ['vid' => array_column((array)$pay, 'vid'), 'is_date' => $is_date, 'is_week' => $is_week, 'is_month' => $is_month];
    }


    public function qrcode()
    {
        $text   = $this->request->param('text');
        $qrCode = new QrCode($text);

        header('Content-Type: ' . $qrCode->getContentType());
        return $qrCode->writeString();
    }

    protected function getDomain($type = 1, $uid = '')
    {
        return getDomain($type, $uid);
    }

    public function decr()
    {
        $ua    = $this->request->param('fingerprint');
        $id    = $this->request->param('vid');
        $point = (new Point())->where(['ua' => $ua])->find();
        if (empty($point))
        {
            return json(['status' => -1, 'msg' => '未找到用户信息', 'data' => [], 'total' => 0]);
        }
        $decr = (new PointDecr())->where(['ua' => $ua, 'vid' => $id])->find();
        if ($decr)
        {
            return json(['status' => 1, 'msg' => '已扣减不处理', 'data' => $decr, 'total' => 0]);
        }

        $rvery_point = sysconfig('site', 'rvery_point');
        $s           = $point->point - $rvery_point;
        if ($point->point < 0 || $point->point < $rvery_point)
        {
            return json(['status' => -2, 'msg' => '点播卷不足,请分享或购买包天,免分享全站畅享观看', 'data' => $point, 'total' => 0]);
        }
        (new Point())->where(['ua' => $ua])->save(['point' => $s]);

        $point->point = $s;
        (new PointDecr())->insert(['ua' => $ua, 'vid' => $id]);

        return json(['status' => 0, 'msg' => 'success', 'data' => $point, 'total' => 0]);

    }

    public function create()
    {
        // create方法专门用于AJAX请求，跳过checkFlg检查
        // 因为这是前端指纹识别功能，不需要f参数验证

        $f           = $this->request->param('f');
        $fingerprint = $this->request->param('fingerprint');
        $data        = (new Point())->where(['ua' => $fingerprint])->find();
        if ($data == false)
        {
            $res = (new Point())->insert(['ua' => $fingerprint, 'point' => sysconfig('site', 'add_point'), 'time' => date('Y-m-d H:i:s')]);
            return json(['status' => 1, 'msg' => '', 'data' => $data, 'total' => 0]);
        }
        return json(['status' => 1, 'msg' => '', 'data' => $data, 'total' => 0]);
    }

    public function ua()
    {
        $fingerprint = $this->request->param('fingerprint');
        $data        = (new Point())->where(['ua' => $fingerprint])->find();
        return json(['status' => 1, 'msg' => '', 'data' => $data, 'total' => 0]);
    }
}
