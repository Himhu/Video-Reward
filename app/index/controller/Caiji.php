<?php

namespace app\index\controller;

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
use think\Dev;
use think\Model;
use app\admin\model\PaySetting;

class Caiji extends IndexBaseController
{
    
    public function cjzy()
    {
       $urllist=$this->get_curl('http://czcz11.ltcdrc.cn/api/resource/getList?ldk=BFTWo000o9U3VRPeLkoo00o4nX0xxy7mqmFZ6BQWF3tvnRrez1pdtbNT6IjE4a2ZKwNqFvmeZNN9Mg3pqNYCATCshqWIOg&page=1&limit=50&encode=1&cid=0&key=&payed=0');
    // $urllist=$this->get_curl('http://fmfpi.xyz:8080/tantan/mobile/tantan/videoList/list');
// http://czcz11.ltcdrc.cn/api/resource/getList?ldk=BFTWo000o9U3VRPeLkoo00o4nX0xxy7mqmFZ6BQWF3tvnRrez1pdtbNT6IjE4a2ZKwNqFvmeZNN9Mg3pqNYCATCshqWIOg&page=1&limit=50&encode=1&cid=0&key=&payed=0
//http://www.lreivol.cn/api/resource/getList?ldk=YqCafsKH2IaRVnBjhHkwlZp3kdxoVo2Gi0bDg6w1xD5J0RoxyiMqop7NVZHHi5ZPI7Qhiy62zQrchxInOo000oavfjpzY3wCZFdBiaYYteZmwiE&page=10&limit=50&encode=1&cid=0&key=&payed=0
    $url=json_decode($urllist,TRUE);
    $list=json_decode(base64_decode($url['data']['list']),TRUE);
    
    $name=$list[1]['title'];
    $img=$list[1]['img'];
    $videoUrl=$list[1]['video_url'];
    $cidName=$list[1]['sort']['name'];
    
    var_dump($name,$img,$videoUrl,$cidName);
    // if($cidName=="人妻"){
    //     $cid=16;
    // }elseif ($cidName=="国产") {
    //     $cid=11;
    // }elseif($cidName=="日韩"){
    //     $cid=14;
    // }elseif($cidName=="黑丝"){
    //     $cid=22;
    // }elseif($cidName=="自拍"){
    //     $cid=17;
    // }elseif($cidName=="乱伦"){
    //     $cid=25;
    // }elseif($cidName=="强歼"){
    //     $cid=12;
    // }elseif($cidName=="人兽,人兽"){
    //     $cid=23;
    // }elseif($cidName=="高清"){
    //     $cid=13;
    // }elseif($cidName=="国产熟女"){
    //     $cid=16;
    // }else{
    //     $cid=24;
    // }
    // if($url['code']==1){
    //     $data = [
    //         "title" => $name,
    //         "url" => $videoUrl,
    //         "image" => $img,
    //         "is_dsp"  =>0,
    //         "create_time" =>time(),
    //         "cid" =>$cid,
    //         ];
    //     $result = \app\admin\model\Stock::create($data);
    //     // return $this->success('添加成功');
    //     echo('success');
    // }else{
    //     echo('err');
    // }
    
    }
    
    
    
    
   public function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$ua=0,$nobaody=0,$ip=0,$split=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$httpheader[] = "Accept:*/*";
	$httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
	$httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
	$httpheader[] = "Connection:close";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($header){
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
	}
	if($cookie){
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if($referer){
		if($referer==1){
			curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
		}else{
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
    if($ip){
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$ip, 'CLIENT-IP:'.$ip));
    }
	if($ua){
		curl_setopt($ch, CURLOPT_USERAGENT,$ua);
	}else{
		curl_setopt($ch, CURLOPT_USERAGENT, 'Stream/1.0.3 (iPhone; iOS 12.4; Scale/2.00)');
	}
	if($nobaody){
		curl_setopt($ch, CURLOPT_NOBODY,1);
	}
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
    if ($split) {
			$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($ret, 0, $headerSize);
			$body = substr($ret, $headerSize);
			$ret=array();
			$ret['header']=$header;
			$ret['body']=$body;
		} 
    curl_close($ch);
    return $ret; 
    }



}
    