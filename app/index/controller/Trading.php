<?php

namespace app\index\controller;

use app\admin\model\Admin;
use app\admin\model\Link;
use app\admin\model\Order;
use app\admin\model\PayOrder;
use app\admin\model\Payset;
use app\admin\model\PaySetting;
use app\admin\model\Stock;
use app\admin\model\SystemAdmin;
use app\common\controller\Frontend;
use app\common\controller\IndexBaseController;
use fast\WxpayService;
use think\Cache;
use think\cache\driver\Redis;
use think\Controller;
use think\Request;


class Trading extends IndexBaseController
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $model = $this->request->get('model');
        
        $user = SystemAdmin::getUser($this->id);

        if (empty($model)) {
            $model = $user['pay_model'];
        }




        if (empty($model)) {

            $model = sysconfig('pay', 'pay_zhifu');
        }



        $payInfo = PaySetting::getPayInfo($model);
        switch ($model) {
            case "xiaocixian":
                 $this->xiaocixian($payInfo, $user, $model);
                break;
            case "xiaosa":
                $this->xiaosa($payInfo, $user, $model);
                break;
            case "dingcheng":
                $this->dingcheng($payInfo, $user, $model);
                break;
            case "fatotaku":
                $this->fatotaku($payInfo, $user, $model);
            case "paydczf":
                $this->paydczf($payInfo, $user, $model);
                break;

            case 'xsdwrkj001':
                $this->xsdwrkj001($payInfo, $user, $model);
                break;
            case 'xsdwrkj002':
                $this->xsdwrkj002($payInfo, $user, $model);
                break;
            case 'tiantain':
                $this->tiantain($payInfo, $user, $model);
                break;
            case 'qiqi':
                $this->qiqi($payInfo, $user, $model);
                break;
            case "wechat":
                $this->wechat($payInfo, $user, $model);
                break;
            case "sanliu":
                $this->aliPay($payInfo, $user, $model);
                break;
            case "codepay_wx":
                $this->codepay_wx($payInfo, $user, $model);
                break;
            case "dp1010":
                return $this->dp1010($payInfo, $user, $model);
                break;
            case "chuanqi":
                return $this->chuanqi($payInfo, $user, $model);
                break;
            case 'fangyoufang':
                $this->fangyoufang($payInfo, $user, $model);
                break;
            case 'gangben':
                $this->gangben($payInfo, $user, $model);
                break;
            case 'xunhupay':
                 $this->xunhupay($payInfo, $user, $model);
                break;
            case 'xunhupay2':
                 $this->xunhupay2($payInfo, $user, $model);
                break;
            case 'cdzsjm':
                $this->cdzsjm($payInfo, $user, $model);
                break;
            case 'ehealth':
                $this->ehealth($payInfo, $user, $model);
                break;
            case 'xxzf':
                $this->xxzf($payInfo, $user, $model);
                break;
            case 'easydoc':
                $this->easydoc($payInfo, $user, $model);
                break;
            case 'easydoc2':
                $this->easydoc($payInfo, $user, $model);
                break;
            case 'nosmse':
                $this->nosmse($payInfo, $user, $model);
                break;
            case 'xleqdmw':
                $this->xleqdmw($payInfo, $user, $model);
                break;
            case 'yczf':
                $this->yczf($payInfo, $user, $model);
                break;
            case 'ddzf_wx':
                $this->ddzf_wx($payInfo, $user, $model);
                break;
            case 'shangzf_wx':
                $this->shangzf_wx($payInfo, $user, $model);
                break;
            case 'yyzw_wx':
                $this->yyzw_wx($payInfo, $user, $model);
                break;
            case 'ee3':    
             $this->ee3($payInfo, $user, $model);     
            default:
                $this->error("未匹配到{$model}支付渠道,请确认");
                break;
        }
    }
    
    
    public function createWechatPaySignWithMd5 ($data, $mach_key) {
        ksort($data);
        $data = array_filter($data, function ($v, $k) {
            if ($k == "sign" && $v == '' && is_array($v)) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
        $str = http_build_query($data)."&key=".$mach_key;
        
       
        $str=urldecode($str);
    
        return strtoupper(md5($str));
    }
    
    protected function xiaocixian($payInfo, $user, $model){
        
             $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
         $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xiaocixian");
     
        $arr=array(
            "username"=>$appId,
            "password"=>$payChannel,
            "upperGoodsno"=>$transact,
            "totalFeeCent"=>$payMoney*100,
            "userId"=>$_SERVER["REMOTE_ADDR"],
            "notifyUrl"=>$payNotifyUrl,
            "jumpUrl"=>$payCallBackUrl,
  );
        
        
        
   
        
        
        $arr['sign'] = $this->createWechatPaySignWithMd5($arr,$appKey);
        
 
   
        
        
        
        
   
        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL =>$payGateWayUrl,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query($arr),
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
        
       
       
     
        
     
   
           $result       = $response?json_decode($response,true):null;
             $pay_url =$result['obj'];
    header("Location: $pay_url");
    exit;
    }
    
    protected function xunhupay($payInfo, $user, $model){
          $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
         $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xunhupay");
          $protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
        $siteurl= $protocol.$_SERVER['HTTP_HOST'];
        $data=array(
    'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
    'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
    'plugins'   => "my_plugin_id",//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
    'appid'     => $appId, //必须的，APPID
    'trade_order_id'=> $transact, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
    'payment'   => 'wechat',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
    'type'   => 'WAP',//固定值"WAP" H5支付必填
    'wap_url'   => $siteurl,//网站域名，H5支付必填
    'wap_name'   => $siteurl,//网站域名，或者名字，必填，长度32或以内 H5支付必填
    'total_fee' => $payMoney,//人民币，单位精确到分(测试账户只支持0.1元内付款)
    'title'     => $payDesc, //必须的，订单标题，长度32或以内
    'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
    'notify_url'=> $payNotifyUrl, //必须的，支付成功异步回调接口
    'return_url'=> $payCallBackUrl,//必须的，支付成功后的跳转地址
    'callback_url'=>$payCallBackUrl,//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
	'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
    'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
);
        $data['hash']=$this->generate_xh_hash($data,$appKey);
        $response=$this->http_post_($payGateWayUrl,json_encode($data));
        
           $result       = $response?json_decode($response,true):null;
             $pay_url =$result['url'];
    header("Location: $pay_url");
    exit;
    }
    
    
        protected   function generate_xh_hash(array $datas,$hashkey){
        ksort($datas);
        reset($datas);
         
        $pre =array();
        foreach ($datas as $key => $data){
            if(is_null($data)||$data===''){continue;}
            if($key=='hash'){
                continue;
            }
            $pre[$key]=stripslashes($data);
        }
         
        $arg  = '';
        $qty = count($pre);
        $index=0;
         
        foreach ($pre as $key=>$val){
            $arg.="$key=$val";
            if($index++<($qty-1)){
                $arg.="&";
            }
        }
         
        return md5($arg.$hashkey);
    }
    
    
       protected function xunhupay2($payInfo, $user, $model){
          $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
         $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xunhupay2");
          $protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
        $siteurl= $protocol.$_SERVER['HTTP_HOST'];
        $data=array(
    'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
    'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
    'plugins'   => "my_plugin_id",//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+
    'appid'     => $appId, //必须的，APPID
    'trade_order_id'=> $transact, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+
    'payment'   => 'wechat',//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
    'type'   => 'WAP',//固定值"WAP" H5支付必填
    'wap_url'   => $siteurl,//网站域名，H5支付必填
    'wap_name'   => $siteurl,//网站域名，或者名字，必填，长度32或以内 H5支付必填
    'total_fee' => $payMoney,//人民币，单位精确到分(测试账户只支持0.1元内付款)
    'title'     => $payDesc, //必须的，订单标题，长度32或以内
    'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
    'notify_url'=> $payNotifyUrl, //必须的，支付成功异步回调接口
    'return_url'=> $payCallBackUrl,//必须的，支付成功后的跳转地址
    'callback_url'=>$payCallBackUrl,//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
	'modal'=>null, //可空，支付模式 ，可选值( full:返回完整的支付网页; qrcode:返回二维码; 空值:返回支付跳转链接)
    'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
);
        $data['hash']=$this->generate_xh_hash($data,$appKey);
        $response=$this->http_post_($payGateWayUrl,json_encode($data));
        
           $result       = $response?json_decode($response,true):null;
             $pay_url =$result['url'];
    header("Location: $pay_url");
    exit;
    }
    
    
    
    
     protected function ee3($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "ee3");


        $url = $payGateWayUrl;
        $key = $appKey;
        $arr = [
            'price' => (int) $payMoney,
            'id' => $appId,
            'on_order' => $transact,
            //'mid' => "test",
            //'pay_type' => $payChannel,
            
             
            'ret' => $payCallBackUrl,
            'notify_url' => $payNotifyUrl,
            
            

            
           
        ];
        
        

   
        
        
    

       $key = $appKey;
        //按照键名对关联数组进行升序排序
        ksort($arr);   
        $buff="";
        foreach($arr as $k => $v){
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        //签名步骤二：在string后加入KEY
        $string=$buff."&key=".$key;
        //签名步骤三：MD5加密
        $string=md5($string);
        //签名步骤四：所有字符转为大写
        $sign=strtoupper($string);
        
        
        $arr['sign'] = $sign;
        
        
        
        $url = "{$url}/index/index/index";
        $q = http_build_query($arr);
        $url = "{$url}?{$q}";//创建订单链接 post方式也可

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);

                $content = curl_exec($ch);

                curl_close($ch);

                echo $content;//直接输出,在自己站完成支付
        
        die;
        
        



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "/index/index/index' method='post'>";
        foreach ($arr as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }


    protected function yczf($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "yczf");


        $url = $payGateWayUrl;
        $key = $appKey;
        $data = [
            'api_id' => $appId,
            'record' => $transact,
            //'mid' => "test",
            // 'type' => $payChannel,
            'money' => sprintf("%.2f",$payMoney),
            'refer' => $payCallBackUrl,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,

        ];




        $datas = [
            'api_id' => $appId,//商户ID
            'record' => $transact,//附加参数
            'money' => sprintf("%.2f",$payMoney)//金额
        ];

        ksort($datas);
        $str1 = '';
        foreach ($datas as $k => $v) {//组装参数
            $str1 .= '&' . $k . "=" . $v;
        }

        $sign_ok = md5(trim($str1) . $appKey);//md5加密参数


        $data['sign'] = $sign_ok;







        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }


    protected function xleqdmw($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xleqdmw");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'out_trade_no' => $transact,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $data = array_filter($data);
        if (@get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $url = $payGateWayUrl;
        $key = $appKey;
        $sign = md5(trim($str1 . $key, '&'));

        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "/submit.php' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }

    protected function easydoc($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "easydoc");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'out_trade_no' => $transact,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $data = array_filter($data);
        if (@get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $url = $payGateWayUrl;
        $key = $appKey;
        $sign = md5(trim($str1 . $key, '&'));

        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "/submit.php' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }


  protected function easydoc2($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "easydoc2");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'out_trade_no' => $transact,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $data = array_filter($data);
        if (@get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $url = $payGateWayUrl;
        $key = $appKey;
        $sign = md5(trim($str1 . $key, '&'));

        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "/submit.php' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }


    protected function nosmse($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "nosmse");

        $data = [
            'id' => $appId,
            'trade_no' => $transact,
            'name' => 'test',
            'money' => $payMoney,
            // 'type' => $payChannel,
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,

        ];
        $data = array_filter($data);
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $str = $str1 . $appKey;
        $str = trim($str, '&');
        $sign = md5($str);
        $data['sign'] = $sign;

        $url = $payGateWayUrl . '?' . http_build_query($data);

        $json = file_get_contents($url);
        $json = json_decode($json , true);
        if($json['result'] == "true")
        {
            header("Location:{$json['url']}"); //跳转到支付页面
            die;

        }

        die;

    }

    protected function xxzf($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xxzf");

        $data = [
            'id' => $appId,
            'trade_no' => $transact,
            'name' => 'test',
            'money' => $payMoney,
            'type' => $payChannel,
            'notify_url' => $payNotifyUrl,
            'sync_notify_url' => $payCallBackUrl,
            'mchid' => ''
        ];
        $data = array_filter($data);
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $str = $str1 . $appKey;
        $str = trim($str, '&');
        $sign = md5($str);
        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';
        $url = $payGateWayUrl . '?' . http_build_query($data);

        header("Location:{$url}"); //跳转到支付页面
        die;

    }

    protected function ehealth($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "ehealth");

        $native = array(
            "pay_memberid" => $appId,
            "pay_orderid" => $transact,
            "pay_amount" => $payMoney,
            "pay_applydate" => date('Y-m-d H:i:s'),
            "pay_bankcode" => $payChannel,
            "pay_notifyurl" => $payNotifyUrl,
            "pay_callbackurl" => $payCallBackUrl,
        );
        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
//echo($md5str . "key=" . $Md5key);
        $sign = strtoupper(md5($md5str . "key=" . $appKey));
        $native["pay_md5sign"] = $sign;
        $native['pay_attach'] = "1234|456";
        $native['pay_productname'] ='团购商品';

        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $payGateWayUrl . "' method='post'>";
        foreach ($native as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }
    protected function paydczf($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xsdwrkj001");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'out_trade_no' => $transact,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $data = array_filter($data);
        if (@get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $url = $payGateWayUrl;
        $key = $appKey;
        $sign = md5(trim($str1 . $key, '&'));

        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }

    public function markSign2($paydata, $signkey)
    {
        ksort($paydata);
        $str = '';
        foreach ($paydata as $k => $v) {
            if ($k != "sign" && $v != "") {
                $str .= $k . "=" . $v . "&";
            }
        }
        return strtoupper(md5($str . "cert=" . $signkey));
    }
    protected function fatotaku($payInfo, $user, $model)
    {
        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }

        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);

        $payNotifyUrl = $this->getNotifyUrl(['f' => id_encode($this->id)], "fatotaku");
        $amount = $payMoney * 1 * 100; //元转换为分
        $data = array(
            "key" => $appId,
            "fee" => $amount,
            "body" => "test",
            "order" => $transact,
            "randStr" => "randStr",
            "notify" => $payCallBackUrl,
            "notice_url" => $payNotifyUrl
        );

        $data['sign'] = $this->markSign2($data, $appKey);
        $dataStr = http_build_query($data);
        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $payGateWayUrl . "' method='get'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        echo $htmls;
        echo "  <script>
        document.forms['aicaipay'].submit();
    </script>";
        exit();
    }

    protected function cdzsjm($payInfo, $user, $model)
    {

        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);

        $payNotifyUrl = $this->getNotifyUrl(['f' => id_encode($this->id)], "cdzsjm");
        $mchKey = $payChannel; //'ER2PG8OWHKXTPONZR0E4DXIWEG2KORFOKLX8S3WTKQLN7DVOAFA0LICXX94JL4N6WXWYVV3L2MM1LEKAHIUH2O9BLG30RQWQE8LAIG7VUJKZ4SJYF1L2PHUVTP4XNWHK';

        $amount = $payMoney * 1 * 100; //元转换为分
        $paramArray = array(
            "mchId" => $appId, //商户ID
            "appId" => $appKey,  //商户应用ID
            "productId" => '8004',  //支付产品ID
            "mchOrderNo" => $transact,  // 商户订单号
            "currency" => 'cny',  //币种
            "amount" => $amount . "", // 支付金额
            "returnUrl" => $payCallBackUrl,     //支付结果前端跳转URL
            "notifyUrl" => $payNotifyUrl,     //支付结果后台回调URL
            "subject" => '网络购物',     //商品主题
            "body" => '网络购物',     //商品描述信息

            "extra" =>  ''     //附加参数
        );


        $sign = $this->paramArraySign($paramArray, $mchKey);  //签名
        $paramArray["sign"] = $sign;



        $paramsStr = http_build_query($paramArray); //请求参数str

        $response = $this->httpPost("{$payGateWayUrl}/api/pay/create_order", $paramsStr);


        $jsonObj = json_decode($response, true);
        die;


        $payMethod = $jsonObj['payParams']['payMethod'];
        if ($payMethod == 'formJump') {
            $payUrl = $jsonObj['payParams']['payUrl'];
            return $payUrl;
        }
    }

    protected function httpPost($url, $paramStr)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $paramStr,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return $err;
        }
        return $response;
    }

    protected function paramArraySign($paramArray, $mchKey)
    {

        ksort($paramArray);  //字典排序
        reset($paramArray);

        $md5str = "";
        foreach ($paramArray as $key => $val) {
            if (strlen($key)  && strlen($val)) {
                $md5str = $md5str . $key . "=" . $val . "&";
            }
        }
        $sign = strtoupper(md5($md5str . "key=" . $mchKey));  //签名

        return $sign;
    }

    protected function fangyoufang($payInfo, $user, $model)
    {

        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "fangyoufang");

        $pay_url = $payGateWayUrl;
        $merchantCode = $appId;
        $key = $appKey;


        // $payGateWayUrl = "http://api.youpay.chongxiaole.net/submit.php";

        $amt = $payMoney; //金额
        $payid = $appId; //商户id
        $paykey = $key; //商户KEY
        $path = "http://" . $_SERVER['HTTP_HOST'] . "/fan/rquery.php"; //，您根据您的文件存放位置进行修改即可
        $payurl = "http://" . $_SERVER['HTTP_HOST'] . "/fan/submit.php"; //，您根据您的文件存放位置进行修改即可
        $pdata = array(
            'url' => $payurl, //
            'id' => $payid, //商户id
            'trade_no' => $transact, //订单号
            'name' => '测试', //名称
            'money' => $payMoney, //金额'
            'notify_url' => $payNotifyUrl, //这里是通知的地址，填入要给您get数据的地址
            'return_url' => $payCallBackUrl, //这里是支付成功后跳转的地址，填入您想跳转的地址即可
            'AGENT' => $_SERVER['HTTP_USER_AGENT'],
            'json' => 1, //输出页面
        );


        if (strpos($_SERVER['HTTP_USER_AGENT'], 'Weibo') !== false) {
            $pdata['csds'] = "ok";
        }



        $parameter = $pdata;
        unset($parameter['url']);
        ksort($parameter);
        reset($parameter);
        $fieldString = http_build_query($parameter);
        $sign = md5(substr(md5($parameter['trade_no'] . $paykey), 10));
        $parameter['sign'] = $sign;
        $parameter['sign_type'] = 'MD5';
        $parameter['path'] = $path;


        $tm = null;
        $tm = '<title>正在前往支付</title>';
        foreach ($parameter as $key => $val) {
            $tm .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }

        $tmp = '<form class="form-inline" id="test_form" method="PSOT" action="' . $pdata['url'] . '">' . $tm
            . '<button type="submit" class="btn btn-success btn-lg">正在前往支付</button></form>'
            . '<script>var form = document.getElementById("test_form").submit();</script>';
        echo $tmp;

        die;
    }

    protected function gangben($payInfo, $user, $model)
    {

        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "gangben");



        $domain = $payGateWayUrl;
        $merchantCode = $appId;
        $merchantKey = $appKey;

        $bodyJson = [
            'amount' => $payMoney * 100,
            'channelType' => $payChannel,
            'merchantOrderCode' => $transact,
            'noticeUrl' => $payNotifyUrl,
            'returnUrl' => $payCallBackUrl
        ];

        $signStr = '';
        foreach ($bodyJson as $key => $value) {
            $signStr .= $key . '=' . $value;
        }
        $signStr .= 'key=' . $merchantKey;



        $requestJson = [

            'merchantCode' => $merchantCode,
            'sign' => md5($signStr),
            'body' => $bodyJson
        ];

        $ch = curl_init($domain . '/order/payment/create');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestJson, 256));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($response, true);
        if ($res['code'] == 200) {


            $path = $res['body']['paymentUrl'];

            header("Location:{$path}"); //跳转到支付页面
            die;
        }

        exit($res['message']);

        die;
    }


    protected function cccc($url, $data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    protected function chuanqi($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "chuanqi");



        $pay_memberid = $appId;   //商户ID
        $pay_orderid = $transact;
        $pay_amount = $payMoney;
        $pay_applydate = date("Y-m-d H:i:s");  //订单时间
        $pay_notifyurl = $payNotifyUrl;  //服务端返回地址
        $pay_callbackurl = $payCallBackUrl; //页面跳转返回地址
        $Md5key = $appKey;   //密钥
        $tjurl = $payGateWayUrl;   //提交地址
        $pay_bankcode = $payChannel;   //银行编码（公众号支付）
        //扫码
        $native = array(
            "pay_memberid" => $pay_memberid,
            "pay_orderid" => $pay_orderid,
            "pay_amount" => $pay_amount,
            "pay_applydate" => $pay_applydate,
            "pay_bankcode" => $pay_bankcode,
            "pay_notifyurl" => $pay_notifyurl,
            "pay_callbackurl" => $pay_callbackurl,
        );
        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        //echo($md5str . "key=" . $Md5key);
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));
        $native["pay_md5sign"] = $sign;
        $native['pay_attach'] = "1234|456";
        $native['pay_productname'] = 'test';


        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $tjurl . "' method='post'>";
        foreach ($native as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }

    protected function qiqi($payInfo, $user, $model)
    {
        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "qiqi");



        $m = $this->payTypes();
        $appSecret = $appKey;
        $data = [
            'mchId' => $appId,
            'way' => $m,
            'totalAmount' => $payMoney * 100,
            'billDesc' => 'test',
            'billNo' => $transact,
            'payment' => "$payChannel",
            'notifyUrl' => $payNotifyUrl,
            'returnUrl' => $payCallBackUrl,

        ];



        ksort($data);
        $str = '';
        foreach ($data as $k => $v) {
            if ($k != "sign" && $v != "") {
                $str .= $k . "=" . $v . "&";
            }
        }
        $sign =  strtoupper(md5($str . "key=" . $appSecret));

        $data['sign'] = $sign;

        $ret = $this->curl($payGateWayUrl, json_encode($data));






        $payDomain = getDomain(1, $user['id']);
        $checkUrl = "$payDomain/index/trading/checkOrderStatus?f=" . id_encode($user['id']) . "&transact=" . id_encode($transact); //主动查单地址



        $datas = json_decode($ret, true);

        $datas['result']['totalAmount'] = $payMoney;
        $datas['result']["returnUrl"] = $data["returnUrl"];
        $datas['result']["checkUrl"] = $checkUrl;


        if ($datas['code'] != 0) {
            exit($datas['message']);
            die;
        }

        if ($m == "qrcode") {
            $turl = "/qiqi/pay.php";
        } else {

            //$turl ="/qiqi/h5.php";
            $url = $datas['result']['linkUrl'];
            header("Location:{$url}");
            die;
        }




        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $turl . "' method='post'>";
        foreach ($datas['result'] as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
        die;
    }
    protected function xsdwrkj002($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xsdwrkj001");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'out_trade_no' => $transact,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $data = array_filter($data);
        if (@get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $url = $payGateWayUrl;
        $key = $appKey;
        $sign = md5(trim($str1 . $key, '&'));

        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }
    protected function tiantain($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "tiantain");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            'out_trade_no' => $transact,
            'mchid' => 'yLm30Lu3KEM4qza2ZEzM',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $signPars = "";
        ksort($data);
        foreach ($data as $k => $v) {
            if ("sign" != $k && "" != $v) {
                $signPars .= $k . "=" . $v . "&";
            }
        }
        $signPars = rtrim($signPars, '&');
        $signPars .= $appKey;
        $sign = md5($signPars);


        $data['sign'] = $sign;







        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $payGateWayUrl . "' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);


        //$data['sign_type'] = 'MD5';
        $row_curl = curl_init();
        curl_setopt($row_curl, CURLOPT_URL, $payGateWayUrl);
        curl_setopt($row_curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($row_curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($row_curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($row_curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($row_curl, CURLOPT_POST, 1);
        curl_setopt($row_curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($row_curl, CURLOPT_ENCODING, "gzip");
        curl_setopt($row_curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($row_curl);
        curl_close($row_curl);
        $packge = json_decode($data, true);

        if ($packge['code'] == 1) {
        }
        die;
    }

    protected function xsdwrkj001($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xsdwrkj001");


        $key = $appKey;
        $data = [
            'pid' => $appId,
            'out_trade_no' => $transact,
            'name' => "test",
            'type' => $payChannel,
            'money' => $payMoney,
            //'mchid' => '5jl5dXZ5ss7R2DGks9GS',
            'notify_url' => $payNotifyUrl,
            'return_url' => $payCallBackUrl,
        ];


        $data = array_filter($data);
        if (@get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $url = $payGateWayUrl;
        $key = $appKey;
        $sign = md5(trim($str1 . $key, '&'));

        $data['sign'] = $sign;
        $data['sign_type'] = 'MD5';



        $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "' method='post'>";
        foreach ($data as $key => $val) {
            $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        }
        $htmls .= "</form>";
        $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        exit($htmls);
    }
    protected function ddzf_wx($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        // $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "ddzf_wx");
        $payCallBackUrl='';


        // $key = $appKey;
        // $data = [
        //     'pid' => $appId,
        //     'out_trade_no' => $transact,
        //     'name' => "test",
        //     'type' => $payChannel,
        //     'money' => $payMoney,
        //     'notify_url' => $payNotifyUrl,
        //     'return_url' => $payCallBackUrl,
        // ];


        // $data = array_filter($data);
        // if (@get_magic_quotes_gpc()) {
        //     $data = stripslashes($data);
        // }
        // ksort($data);
        // $str1 = '';
        // foreach ($data as $k => $v) {
        //     $str1 .= '&' . $k . "=" . $v;
        // }
        // $url = $payGateWayUrl;
        // $key = $appKey;
        // $sign = md5(trim($str1 . $key, '&'));

        // $data['sign'] = $sign;
        // $data['sign_type'] = 'MD5';
        $ip="43.129.225.26";
        $sign = md5('number='.$appId.'&order='.$transact.'&money='.$payMoney.'&otifyUrl='.$payCallBackUrl.'&returnUrl='.$payNotifyUrl.'&'.$appKey);//MD5密钥
        $url = $payGateWayUrl.'/pay/index.php?number='.$appId.'&ip='.$ip.'&order='.$transact.'&money='.$payMoney.'&otifyUrl='.$payCallBackUrl.'&returnUrl='.$payNotifyUrl.'&sign='.$sign;
        $data= file_get_contents($url);
        $dta = json_decode($data, true);
        if($dta["success"]== '请求成功'){
            Header("Location: ".$dta["url"]);//获取到了 地址你们在入库数据 跳转支付 
        }else{
            echo $dta["success"];//错误提示
            exit();
        }

        // $htmls = "<form id='aicaipay' name='aicaipay' action='" . $url . "/submit.php' method='post'>";
        // foreach ($data as $key => $val) {
        //     $htmls .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
        // }
        // $htmls .= "</form>";
        // $htmls .= "<script>document.forms['aicaipay'].submit();</script>";
        // exit($htmls);
    }
    protected function shangzf_wx($payInfo, $user, $model)
    {

        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "shangzf_wx");
        
        $api=$payGateWayUrl;
        $data=[
         'mchId'=>$appId, //商户号
         'wayCode'=>'yy_zstopup',//充值产品
         'amount'=>$payMoney,//充值金额
         'mchOrderNo'=>$transact,//订单号
         'return_url'=>$payCallBackUrl,//同步请求地址
         'notify_url'=>$payNotifyUrl,//异步回调地址
         'type'=>$payChannel,//支付方式
        //  'extra'=>json_encode(array('id'=>'1'))//附加信息可留空
        ]; 
        $data['clientIp']='43.248.118.49';//客户ip
        $data['format']='json';//返回类型
        $sign = '';
                ksort($data);
                foreach ($data as $k => $v) {
                if($k == "sign" || $k == "sign_type" || $k=="clientIp" ||$k=='format' || $v == "")continue;
                    if ($v) {
                        $sign .= $k . '=' . $v . '&';
                    }

                }
        $data['sign']=strtoupper(md5($sign .'key='.$appKey));
        $data['sign_type']='MD5';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $payGateWayUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        //解析数据
        $ret = json_decode ($output,true);
        if(!$ret){
      // $data['format']='';//该变量名所传的数据为空时候，直接使用return $output;
        return $output;
        }
        if($ret['code']==1 && $ret['payUrl']!==''){
        exit("<script>window.location.replace('{$ret['payUrl']}');</script>");  
        }else{
         return $ret['msg'];  
        }

    }
    
     protected function yyzw_wx($payInfo, $user, $model)
    {
        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "yyzw_wx");
        $paydata=[
	        'amount'=>$payMoney,   //金额
	        'paytype'=>3,    //3是H5/1是扫码/2微信是内付
	        'channel'=>"wechat",
	        'code'=>$payChannel,
	        'orderid'=>$transact,
	        'notify_url'=>$payNotifyUrl, //回调地址 ,
	        'callback_url'=>$payCallBackUrl, //同步地址 ,
	        'acckey'=>"",  //账号编码/为空则轮询
	        'appid'=>$appId
        ]; 
        
        $Md5key = $appKey;
        $paydata['sign']=$this->markSignyyzf($paydata,$appKey);
        $url = "http://116.62.26.228:889/gateway/unifiedorder";   //网关地址
        $ret= $this->curlyyzf($payGateWayUrl,$paydata);
        $data = json_decode($ret,true);
        if($data['code'] == 0){
	        $url = $data['result']['linkUrl'];
            header("Location:{$url}");
        }else{
	        exit($data['msg']);
        }

    }
    public function curlyyzf($url,$post_data){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// 我们在POST数据哦！
	    curl_setopt($ch, CURLOPT_POST, 1);
	// 把post的变量加上
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
    }
    public function markSignyyzf($paydata,$signkey){
        ksort($paydata);
        $str='';
        foreach($paydata as $k=>$v){ 
           if($k != "sign" && $v!= ""){
                $str.=$k."=".$v."&";
            }
        } 
        return md5($str.$signkey);
    }
    
    protected function xiaosa($payInfo, $user, $model)
    {
        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = \array_get($res, 'data.price', 0);
        $payDesc = \array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "xiaosa");

        $payurl = $payGateWayUrl; //支付网关联系客服获取
        //-----------------------------统一下单接口-----------------------------------------

        $data = array(
            "mid" => $appId, 					//商户ID
            "payId" => $transact,				//商户订单号
            "param" => "测试订单", 		//自定义参数，可以传入 用户名称,商品名称,订单标题 等根据自己需求传入,将会原样返回到同步和异步通知接口
            "type" => $payChannel, 	//微信支付传入1 支付宝支付传入2
            "price" => $payMoney,		//订单金额
            // "sign" => $sign, 				//签名，计算方式为 md5(mid+payId+param+type+price+商户密钥)
            "notifyUrl" => $payNotifyUrl, 		//传入则设置该订单的异步通知接口为该参数，不传或传空则使用后台设置的接口
            "returnUrl" => $payCallBackUrl, 		//传入则设置该订单的同步跳转接口为该参数，不传或传空则使用后台设置的接口
            "isHtml" => 1, 					//传入1则跳转到支付页面，不传或“0”返回创建结果的json数据,建议填 1
        );

        $Md5key = $appKey; //签名密钥，后台提取
        $sign = md5($appId.$data['payId'].$data['param'].$data['type'].$data['price'].$Md5key);
        $data['sign'] = $sign;




        $bbspayurl = 'isHtml='.$data['isHtml']."&mid=".$appId."&payId=".$data['payId'].'&type='.$data['type'].'&sign='.$sign.'&param='.$data['param']."&price=".$data['price'].'&notifyUrl='.$data['notifyUrl'].'&returnUrl='.$data['returnUrl'];


        $url = $payGateWayUrl .'?' . $bbspayurl;

        header('Location:' . $url);

        exit;


    }

    protected function dingcheng($payInfo, $user, $model)
    {
        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = \array_get($res, 'data.price', 0);
        $payDesc = \array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl([], $transact, $this->id);
        $payNotifyUrl = $this->getNotifyUrl([], "dingcheng");

        $payurl = $payGateWayUrl; //支付网关联系客服获取
        //-----------------------------统一下单接口-----------------------------------------

        $paydata = array(
            'mchId' => $appId, //商户ID，后台提取
            'billNo' => $transact, //商户订单号
            'totalAmount' => $payMoney * 100, //金额
            'billDesc' => "在线充值", //商品名称
            'way' => $this->payType(), //支付模式
            'payment' => $payChannel, //微信支付
            'notifyUrl' => $payNotifyUrl, //回调地址
            'returnUrl' => $payCallBackUrl, //同步跳转
            'attach' => "",
            "accKey" => "" //收款账号
        );

        $Md5key = $appKey; //签名密钥，后台提取
        $paydata['sign'] = $this->markSign($paydata, $Md5key);

        file_put_contents(ROOT_PATH . "pay.txt", "下单" . json_encode($paydata, 1) . PHP_EOL, FILE_APPEND);

        $payUrl = "http://$payurl/game/unifiedorder"; //请求订单地址
        $checkUrl = "http://$payurl/pay/checkTradeNo"; //主动查单地址
        $ret = $this->curl($payUrl, json_encode($paydata));

        $data = json_decode($ret, true);
        if ($data['code'] == 0) {
            $data['result']["returnUrl"] = $paydata["returnUrl"];
            $data['result']["checkUrl"] = $checkUrl;
            if ($this->payType() == "qrcode") {
                $url = "/dingcheng/html/qrcode.php"; //付款页面
                $this->jumpPost($url, $data['result']);
            } else {
                $url = "/dingcheng/html/h5.php"; //付款页面
                $this->jumpPost($url, $data['result']);
            }
        } else {
            exit($data['message']);
        }
    }
    protected function wechat($payInfo, $user, $model)
    {
        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = \array_get($res, 'data.price', 0);
        $payDesc = \array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl(['transact' => id_encode($transact), 'f' => id_encode($this->id)]);
        $payNotifyUrl = $this->getNotifyUrl([], $transact, "wechat");

        $mchid = $appId;          //微信支付商户号 PartnerID 通过微信支付商户资料审核后邮件发送
        $appid = $appKey;  //微信支付申请对应的公众号的APPID
        $appKey = $payChannel;   //微信支付申请对应的公众号的APP Key
        $apiKey = $payGateWayUrl;   //https://pay.weixin.qq.com 帐户设置-安全设置-API安全-API密钥-设置API密钥
        //①、获取用户openid
        $wxPay = new WxpayService($mchid, $appid, $appKey, $apiKey);
        $openId = $wxPay->GetOpenid();      //获取openid
        if (!$openId) exit('获取openid失败');
        $outTradeNo = $transact;     //你自己的商品订单号
        $payAmount = $payMoney;          //付款金额，单位:元
        $orderName = '支付测试';    //订单标题
        $notifyUrl = $payNotifyUrl;     //付款成功后的回调地址(不要有问号)
        $payTime = time();      //付款时间
        $jsApiParameters = $wxPay->createJsBizPackage($openId, $payAmount, $outTradeNo, $orderName, $notifyUrl, $payTime);
        $jsApiParameters = json_encode($jsApiParameters);


        $html = "<html>
    <head>
        <meta charset=\"utf-8\" />
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"/>
        <title>微信支付样例-支付</title>
        <script type=\"text/javascript\">
            //调用微信JS api 支付
            function jsApiCall()
            {
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest',
                    $jsApiParameters,
                    function(res){
                        WeixinJSBridge.log(res.err_msg);
						if(res.err_msg=='get_brand_wcpay_request:ok'){
							alert('支付成功！');
							location.href = '{$payCallBackUrl}'
						}else{
							alert('支付失败：'+res.err_code+res.err_desc+res.err_msg);
						}
                    }
                );
            }
            function callpay()
            {
                if (typeof WeixinJSBridge == \"undefined\"){
                    if( document.addEventListener ){
                        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                    }else if (document.attachEvent){
                        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                    }
                }else{
                    jsApiCall();
                }
            }
            callpay();
        </script>
    </head>
    <body>    
    </body>
    </html>";

        echo $html;
        die;
    }
    protected function dp1010($payInfo, $user, $model)
    {
        $transact = date("YmdHis") . rand(100000, 999999);
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl(['transact' => id_encode($transact), 'f' => id_encode($this->id)]);
        $payNotifyUrl = $this->getNotifyUrl(['f' => id_encode($this->id)], "dp1010");

        $money = $payMoney; //订单金额
        $trade_no = $transact; //订单号
        $uid = $appId; //UID
        $tongdao_id = $payChannel; //通道ID  网易801 ， 捕鱼802
        $token = $appKey; //令牌
        $url = $payGateWayUrl; //网关
        $notify_url = $payNotifyUrl; //异步跳转地址
        $return_url = $payCallBackUrl; //同步跳转地址
        return $this->pay($trade_no, $money, $uid, $token, $tongdao_id, $notify_url, $return_url, $url);
    }
    protected function codepay_wx($payInfo = null, $user = [], $model)
    {
        $transact = $this->transact();
        $res = $this->createOrder($user, $transact, $model);
        $appId = $payInfo['app_id'];
        $appKey = $payInfo['app_key'];
        $payChannel = $payInfo['pay_channel'];
        $payGateWayUrl = $payInfo['pay_url'];
        $payMoney = array_get($res, 'data.price', 0);
        $payDesc = array_get($res, 'data.des');
        if ($res['code'] == 0) {
            return $this->error('下单失败');
        }
        $payCallBackUrl = $this->getCallbackUrl(['transact' => id_encode($transact,30), 'f' => id_encode($this->id)]);


        $payNotifyUrl = $this->getNotifyUrl(['f' => id_encode($this->id)]);
        //todo 到这里为运营商下单逻辑

        $data = array(
            "id" => $appId, //你的码支付ID
            "pay_id" => $transact, //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
            "type" => $payChannel, //1支付宝支付 3微信支付 2QQ钱包
            "price" => $payMoney, //金额100元
            "param" => "", //自定义参数
            "notify_url" => $payNotifyUrl, //通知地址
            "return_url" => $payCallBackUrl, //跳转地址
        );


        //构造需要传递的参数
        ksort($data); //重新排序$data数组
        reset($data); //内部指针指向数组中的第一个元素
        $sign = ''; //初始化需要签名的字符为空
        $urls = ''; //初始化URL参数为空


        foreach ($data as $key => $val) { //遍历需要传递的参数
            if ($val == '' || $key == 'sign') continue; //跳过这些不参数签名
            if ($sign != '') { //后面追加&拼接URL
                $sign .= "&";
                $urls .= "&";
            }
            $sign .= "$key=$val"; //拼接为url参数形式
            $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

        }
        $query = $urls . '&sign=' . md5($sign . $appKey); //创建订单所需的参数
        $url = "$payGateWayUrl/?{$query}"; //支付页面

        header("Location:{$url}"); //跳转到支付页面
        die;
    }
    protected function aliPay($payInfo = null, $user = [], $model)
    {
    }
    protected function pay($trade_no, $money, $uid, $token, $tongdao_id, $notify_url, $return_url, $url)
    {
        $arr_data = array(
            'uid' => $uid, //商户id
            'token' => $token, //商户密钥
            'trade_no' => $trade_no, //订单号
            'money' => $money, //金额'
            'tongdao_id' => $tongdao_id,
            'notify_url' => $notify_url, //这里是通知的地址，填入要给您get数据的地址
            'return_url' => $return_url //这里是支付成功后跳转的地址，填入您想跳转的地址即可
        );
        $parameter = $arr_data;
        $fieldString = http_build_query($parameter);
        $sign = md5($fieldString . $parameter['token']);
        $arr_data['sign'] = $sign; //签名
        $header = array('Ktype:iscurl', 'User-Agent:' . $_SERVER['HTTP_USER_AGENT']);

        //发起请求
        $data = $this->http_post($url, $header, $arr_data);
        $data = json_decode($data, true);
        if ($data['code'] == 1) {
            //网易
            if ($tongdao_id == 801) {


                //捕鱼
            } elseif ($tongdao_id == 802) {
                $fdata = $data['data'];

                //参数加密
                $check_str = array(
                    "check_url" => $data['data']['check_url'],
                    "check_sign" => $data['data']['check_sign'],
                );
                $check_str = json_encode($check_str);
                $check_str = $this->encrypt($check_str, "E"); //加密
                //重组参数
                $jm  = array(
                    "money" => $money,
                    "return_url" => $return_url,
                    "wxurl" => $data['data']['wxurl'],
                    "check_str" => $check_str
                );
                $jm_json = json_encode($jm);
                $jm_string = $this->encrypt($jm_json, "E"); //加密
                // 微信内付
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) { //判断是否在微信里打开
                    $tjurl = "/html/802/wxpay.php?code=" . $jm_string; //付款页面文件的路径  请注意路径
                    //echo $tjurl;
                    header("Location:$tjurl");
                    // 微信H5
                } else {
                    //$tjurl路径要对
                    $tjurl = "/html/802/wxpayh5.php?code=" . $jm_string;  //付款页面文件的路径    请注意路径
                    header("Location:$tjurl");
                }
            } else {
                echo "通道ID未指定";
            }
        } else {
            echo $data['msg'];
        }
    }
    protected function encrypt($string, $operation)
    {
        $src = array(
            "/",
            "+",
            "="
        );
        $dist = array(
            "_a",
            "_b",
            "_c"
        );
        if ($operation == 'D') {
            $string = str_replace($dist, $src, $string);
        }
        $key = md5("as4zaz1a4d4aad");
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            $rdate = str_replace('=', '', base64_encode($result));
            $rdate = str_replace($src, $dist, $rdate);
            return $rdate;
        }
    }
    
     protected function  http_post_($url,$data){
           $protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
        $siteurl= $protocol.$_SERVER['HTTP_HOST'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_REFERER,$siteurl);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data)
        )
    );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error=curl_error($ch);
        curl_close($ch);
        return $response;
     }
    protected function http_post($sUrl, $aHeader, $aData)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $sUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aData));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $sResult = curl_exec($ch);
        $sCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($sCode == 200) {
            return $sResult;
        } else {
            return null;
        }
    }

    protected function transact()
    {
        //$transact = sysconfig('site', 'site_order') . date("YmdHis") . rand(100000, 999999);
        $transact = date("YmdHis") . rand(1000, 9999);

        return $transact;
    }
    protected function payTypes()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return "qrcode";
        } else {
            return "wap";
        }
    }
    protected function payType()
    {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return "qrcode";
        } else {
            return "wap";
        }
    }
    /**
     * 签名方法
     */
    protected function markSign($paydata, $signkey)
    {
        ksort($paydata);
        $str = '';
        foreach ($paydata as $k => $v) {
            if ($k != "sign" && $v != "") {
                $str .= $k . "=" . $v . "&";
            }
        }
        return strtoupper(md5($str . "key=" . $signkey));
    }

    /**
     * 表单跳转模式
     * $url 地址
     * $data 数据,支持数组或字符串，可留空
     * $target 是否新窗口提交，默认关闭
     */
    protected function jumpPost($url, $data)
    {
        $html = "<form id='form' name='form' action='" . $url . "' method='post'>";
        if (!empty($data)) {
            if (is_array($data)) {
                foreach ($data as $key => $val) {
                    $html .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
                }
            } else {
                $html .= "<input type='hidden' name='value' value='" . $data . "'/>";
            }
        }
        $html .= "</form>";
        $html .= "<script>document.forms['form'].submit();</script>";
        exit($html);
    }
    protected function curl($url, $post_data)
    {
        $ch = curl_init();
        $header = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post_data)
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //同步检测回调
    public function callBack($transact = '', $f = '')
    {
        $request = $this->request->param();
        $transact = $this->request->param('transact');
        $transact = id_decode($transact,30);
        $orderInfo = (new PayOrder())->where(['transact' => $transact['id']])->find()->toArray();
        if (empty($orderInfo)) {
            return $this->error('订单不存在,请重试!', '', '', 333);
        }
        $_GET['id'] = $orderInfo['id'];
        $_GET['v'] = $orderInfo['vid'];
        $_GET['f'] = array_get($request, 'f');
        $_GET['transact'] = array_get($request, 'transact');

        if (empty($_GET['transact'])) {
            $_GET['transact'] = $this->request->route('transact');
        }
        if (empty($_GET['f'])) {
            $_GET['f'] = $this->request->route('f');
        }


        $this->assign('v', $_GET['v']);

        $this->assign('f', $_GET['f']);
        $this->assign('transact', $_GET['transact']);

        // echo "已支付,缺少前台轮询调用检测";
        $this->assign('order', $orderInfo);
        return view('callback');
    }

    public function checkOrderStatus()
    {
        $request = $this->request->param();
        $transact = id_decode(array_get($request, 'transact', ''),30);
        $orderInfo = (new PayOrder())->where(['transact' => $transact['id']])->find()->toArray();
        if ($orderInfo['status'] == 1) {
            return json(['code' => 1, 'data' => $orderInfo, 'msg' => 'success']);
        }
        return json(['code' => 0, 'msg' => 'notPay', 'data' => $orderInfo]);
    }
    protected function createOrder($user, $transact, $model = null)
    {
        $uid = $this->id;
        $vid = $this->request->all('vid');
        $isDate = $this->request->all('is_date', 0);
        $isMonth = $this->request->all('is_month', 0);
        $isWeek = $this->request->all('is_week', 0);

        //浮动后加个
        $pay_monery = $this->request->all('money');
        //原价
        $self_money = $this->request->all('self_money');

        $ip = $this->request->ip();
        if($vid == 'dsp')
        {
            $linkInfo = (new Stock())->limit(1)->select()->toArray()[0];
        }
        else
        {
            $linkInfo = (new Stock())->where(['id' => $vid])->find()->toArray();
        }
        //$payMoney = array_get($linkInfo, 'money', 0);
        $map = [
            '1' => 'dan_fee',
            '2' => 'rand_dan_fee'
        ];
        //$payMoney = array_get($user,$map[$user['is_dan']]);
        $payMoney = $self_money;
        $payDesc = '支付';
        if ($isDate == 2) {
            $payMoney = $user['date_fee'];
            $payMoney = $self_money;
            $payDesc = "包天_1";
        }
        if ($isWeek == 2) {
            $payMoney = $user['week_fee'];
            $payMoney = $self_money;
            $payDesc = "包周_2";
        }
        if ($isMonth == 2) {
            $payMoney = $user['month_fee'];
            $payMoney = $self_money;
            $payDesc = "包月_3";
        }

        //扣量判断
        $is_kouliang = 1;
        //统一下单
        $data = [
            'vid' => $linkInfo['id'],
            'uid' => $uid,
            'ip' => $ip,
            'transact' => $transact,
            'price' => $payMoney,
            'status' => 2,
            'vtitle' => $linkInfo['title'],
            'pid' => $user['pid'],
            'ua' => $this->request->param("murmur"),
            'pid_top' => $this->pid_top,
            'is_kouliang' => $is_kouliang,
            'is_date' => $isDate == 2 ? 2 : 1,
            'is_month' => $isMonth == 2 ? 2 : 1,
            'is_week' => $isWeek == 2 ? 2 : 1,
            'is_dsp' => $vid,
            'pay_channel' => $model,
            'des' => $payDesc,
            'createtime' => time()
        ];
        /* $redis = redisInstance();
        $key = "order_{$uid}_".date('Y-m-d');
        $redis->handler()->zadd($key ,time() , $transact );*/
        $res = (new PayOrder())->save($data);
        if ($res) {
            $data['price'] = $pay_monery;
            return ['code' => 1, 'data' => $data, 'link' => $linkInfo];
        }
        return ['code' => 0, 'data' => []];
    }
    //异步通知地址
    protected function getNotifyUrl($param = [], $action = "notify")
    {
        $host = $this->request->host(true);
        $scheme = $this->request->scheme();
        $port = $this->request->port();

        if ($param) {
            return $scheme . "://" . $host . ":$port" . "/index/pay/$action?a=a&" . http_build_query($param);
        }
        return $scheme . "://" . $host . ":$port" . "/index/pay/$action" . http_build_query($param);
    }
    //同步通知地址
    protected function getCallbackUrl($params = [], $order = '', $id = '')
    {
        $host = $this->request->server('HTTP_ORIGIN');
        $scheme = '';
        if(empty($host))
        {

            $host = $this->request->host(true);
            $scheme = $this->request->scheme() . "://";
        }

        $port = $this->request->port();
        if ($params) {
            return $scheme . $host . ":$port" . "/m/#/callback?a=a&" . http_build_query($params);
        }
        //['transact' => id_encode($transact) , 'f' => id_encode($this->id)
        return $scheme . $host . "/m/#/callback?a=a&transact=" . id_encode($order,30) . "&f=" . id_encode($id);
    }
}
