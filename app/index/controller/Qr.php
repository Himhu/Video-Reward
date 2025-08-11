<?php


namespace app\index\controller;


use app\common\controller\IndexBaseController;
use app\common\service\Poster;
use app\common\service\QRcode;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Writer\PngWriter;


class Qr extends IndexBaseController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {

        require_once dirname(__FILE__) . '/../../common/service/PhpQrcode.php';

        //海报图
        $qrcode_bg = sysconfig('short_video','qrcode_bg');
        //logo
        $qrcode_logo = sysconfig('short_video','qrcode_logo');
        //text
        $qrcode_text = sysconfig('short_video','qrcode_text');

//        $code = urldecode($this->request->param('q'));
//        //二维码生成内容
//        //$code = 'http://域名m/d?f=d8Jv&ua=4c45008cf13c790a4fffb873e56d80e2';
//        //生成二维码图片
//        $qrCodeData = QRcode::pngData($code, 13);

        $writer = new PngWriter();
        //$text = $code;
        $foreground = $this->request->get('foreground', "#ffffff");
        $background = $this->request->get('background', "#000000");
        // 前景色
        list($r, $g, $b) = sscanf($foreground, "#%02x%02x%02x");
        $foregroundcolor = ['r' => $r, 'g' => $g, 'b' => $b];

        // 背景色
        list($r, $g, $b) = sscanf($background, "#%02x%02x%02x");
        $backgroundcolor = ['r' => $r, 'g' => $g, 'b' => $b];
        //$code = 'http://域名/d?f=d8Jv&ua=4c45008cf13c790a4fffb873e56d80e2';
        $qrCode = \Endroid\QrCode\QrCode::create(urldecode($_GET['q']))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(500)
            ->setMargin(10);
        $result = $writer->write($qrCode);
        $imgstr = $result->getDataUri();
        if (!preg_match('/data:([^;]*);base64,(.*)/', $imgstr, $matches)) {
            die("error");
        }
        $content = base64_decode($matches[2]);

        $config = array(
            'bg_url' => $qrcode_bg,//背景图片路径
            'text' => array(
//                array(
//                    'text' => '初夏',//文本内容
//                    'left' => 312, //左侧字体开始的位置
//                    'top' => 676, //字体的下边框
//                    'fontSize' => 16, //字号
//                    'fontColor' => '255,0,0', //字体颜色
//                    'angle' => 0,
//                ),
            ),
            'image' => array(
                array(
                    'name' => '二维码', //图片名称，用于出错时定位
                    'url' => $qrcode_bg,
                    'stream' => $content,
                    'left' => sysconfig('short_video','q_l'), //左
                    'top' => sysconfig('short_video','q_t'), //上
                    'right' => sysconfig('short_video','q_r'),//右
                    'bottom' => sysconfig('short_video','q_x'),//下
                    'width' => 184, //宽
                    'height' => 184,//高
                    'radius' => 0,
                    'opacity' => 100
                ),
                array(
                    'name' => 'logo', //图片名称，用于出错时定位
                    'url' => $qrcode_logo,
                    'stream' => 0,
                    'left' => sysconfig('short_video','l_l'),
                    'top' => sysconfig('short_video','l_t'),
                    'right' => sysconfig('short_video','l_r'),
                    'bottom' => sysconfig('short_video','l_x'),
                    'width' => 30,
                    'height' => 30,
                    'radius' => 5,
                    'opacity' => 99
                ),

            )
        );

        //设置海报背景图
        poster::setConfig($config);
        //设置保存路径
        $res = poster::make();
        //是否要清理缓存资源
        poster::clear();
        if (!$res) {
            echo '生成失败：', poster::getErrMessage();
        } else {
            header("Content-Type: image/png");
            header('Content-Length: '.strlen($res));
            echo $res;die;
        }
    }

    function changeFileSize($size, $dec = 2)
    {
        $a = array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
        $pos = 0;
        while ($size >= 1024) {
            $size /= 1024;
            $pos++;
        }
        return round($size, $dec) . ' ' . $a[$pos];
    }
}