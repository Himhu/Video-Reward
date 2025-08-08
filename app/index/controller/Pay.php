<?php

namespace app\index\controller;


use app\admin\model\Link;
use app\admin\model\PayOrder;
use app\admin\model\PayOrder as Order;
use app\admin\model\PaySetting;
use app\admin\model\Quantity;
use app\admin\model\Stock;
use app\admin\model\SystemAdmin;
use app\admin\model\Payed;
use app\common\controller\IndexBaseController;
use think\facade\Db;
use think\Exception;
use think\Request;

class Pay extends IndexBaseController
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    public function initialize()
    {
        //$this->checkFlg();
    }




    public function ee3()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['ordernumber'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "ok";
                die;
            }

        echo 'fail';
        die;
    }
    
    public function xiaocixian(){
        try {
        $json_str = file_get_contents('php://input'); 
            
        $data = json_decode($json_str,true);
                file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($data, 1) . PHP_EOL, FILE_APPEND);
        $transact = $data['upper_goodsno'];
    
        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
       
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
            $res =  $this->saveOrder($transact, $money);
           
            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
    }
    
    public function yczf()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['record'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
    
    public function xunhupay(){
         file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['trade_order_id'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
        public function xunhupay2(){
         file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['trade_order_id'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
    
    public function xleqdmw()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['out_trade_no'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    

     public function easydoc()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['out_trade_no'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
    
         public function easydoc2()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['out_trade_no'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
 public function nosmse()
    {

        $request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", json_encode($request,256) . PHP_EOL, FILE_APPEND);
        #$request = json_decode($request, true);
        $transact = $_REQUEST["trade_no"];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "OK";
            die;
        }
        echo "OK";
    }
    
    
    public function xxzf()
    {

        $request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", json_encode($request,256) . PHP_EOL, FILE_APPEND);
        #$request = json_decode($request, true);
        $transact = $_REQUEST["out_trade_no"];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "success";
            die;
        }
        echo "success";
    }
    
    public function ehealth()
    {

        $request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", json_encode($request,256) . PHP_EOL, FILE_APPEND);
        #$request = json_decode($request, true);
        $transact = $_REQUEST["orderid"];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "OK";
            die;
        }
        echo "OK";
    }
    
    public function xiaosa()
    {

        $request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", json_encode($request,256) . PHP_EOL, FILE_APPEND);
        #$request = json_decode($request, true);
        $transact = $_GET['payId'];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "success";
            die;
        }
        echo "success";
    }

    public function fatotaku()
    {

        //$request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", $_POST['order'] . PHP_EOL, FILE_APPEND);
        #$request = json_decode($request, true);
        $transact = $_POST['order'];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "SUCCESS";
            die;
        }
    }


    public function gangben()
    {


        $request = file_get_contents("php://input");
        //$request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", $request . PHP_EOL, FILE_APPEND);
        $request = json_decode($request, true);
        $transact = $request['merchantOrderCode'];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "SUCCESS";
            die;
        }
    }

    public function cdzsjm()
    {
        $request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", json_encode($request, 1) . PHP_EOL, FILE_APPEND);
        $transact = $request['mchOrderNo'];
        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "success";
            die;
        }
    }


    public function fangyoufang()
    {
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果 fangyoufang " . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['out_trade_no'];

        $result = $_REQUEST;
        $trade_status = $_REQUEST['trade_status']; //TRADE_SUCCESS成功
        $out_trade_no = $_REQUEST['out_trade_no']; //提交的订单号

        $order = (new Order())->where(['transact' => $transact])->find();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }

        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "ok" . PHP_EOL, FILE_APPEND);
            echo "success";
            die;
        }

        echo 'fail';
        die;
    }

    public function chuanqi()
    {
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['orderid'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $pay = (new PaySetting())->where('pay_channel', '=', 'chuanqi')->find();



        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        if ($_REQUEST['returncode'] != '00') {
            echo 'fail';
            die;
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "ok";
            die;
        }

        echo 'fail';
        die;
    }


    public function tiantain()
    {
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['out_trade_no'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $pay = (new PaySetting())->where('pay_channel', '=', 'tiantain')->find();



        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        if ($_REQUEST['trade_status'] != 'TRADE_SUCCESS') {
            echo 'fail';
            die;
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "SUCCESS";
            die;
        }

        echo 'fail';
        die;
    }

    public function qiqi()
    {
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['billNo'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        if ($_REQUEST['tradeStatus'] != '1') {
            echo 'fail';
            die;
        }
        $res =  $this->saveOrder($transact, $money);
        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
        if ((bool) $res == true) {
            file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
            echo "SUCCESS";
            die;
        }

        echo 'fail';
        die;
    }
     public function jiuyizf_wx()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['order'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
     public function ddzf_wx()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['order'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
       
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        //  var_dump($order['uid']);
        // $sign = md5('number='.$number.'&order='.$_GET["order"].'&money='.$_GET["money"].'&otifyUrl='.$otifyUrl.'&returnUrl='.$returnUrl.'&'.$key);//MD5密钥
        // if($sign == $_GET["sign"]){
        //     echo 'success';
        //     exit();
        // }else{
        //     echo 'fail';
        //     exit();
        // }
        
            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
     public function shangzf_wx()
    {


        file_put_contents(ROOT_PATH . "payshang.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['out_trade_no'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
       
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }

            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    
     public function yyzw_wx()
    {


        file_put_contents(ROOT_PATH . "yyzw_wx.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['orderid'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
       
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }

            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    public function xsdwrkj001()
    {


        file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . json_encode($_REQUEST, 1) . PHP_EOL, FILE_APPEND);
        $transact = $_REQUEST['payId'];

        $order = (new Order())->where(['transact' => $transact])->find()->toArray();
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }


            $res =  $this->saveOrder($transact, $money);

            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }

        echo 'fail';
        die;
    }
    public function notify()
    {
        $request = $this->request->param();
        file_put_contents(ROOT_PATH . "pay.txt", json_encode($request, 1) . PHP_EOL, FILE_APPEND);
        $transact = $request['pay_id'];

        $order = PayOrder::getOrderInfo($transact);
        $money = $order['price'];
        $uid = $this->id;
        if (empty($this->id)) {
            $this->id = $order['uid'];
        }
        if ($request['status'] == 1 || $request['ok'] == 1) {
            $res =  $this->saveOrder($transact, $money);
            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . $res . PHP_EOL, FILE_APPEND);
            file_put_contents(ROOT_PATH . "pay.txt", "订单处理返回结果" . gettype($res) . PHP_EOL, FILE_APPEND);
            if ((bool) $res == true) {
                file_put_contents(ROOT_PATH . "pay.txt", "success" . PHP_EOL, FILE_APPEND);
                echo "success";
                die;
            }
        }
        echo "fail";
        die;
    }


    protected function saveOrder($transact = null, $money = 0)
    {
        $orderInfo = Order::getOrderInfo($transact);
        //$link = (new Stock())->find($orderInfo['vid'])->toArray();
        //Db::table("ds_stock")->where(['id' => $link['stock_id']])->inc("number")->update();
        //Db::table("ds_link")->where(['id' => $orderInfo['vid']])->inc("number")->update();
        if ($orderInfo['status'] == 1) {
            return true;
        }
        $userInfo = get_user($this->id);
        $user = $userInfo;
        $uid = $userInfo['id'];

        $is_kouliang = 1;
        $orderInfo['is_kouliang'] = $is_kouliang;
        //if ($user['pid'] > 0)
        if (1) {
            //查找扣量次数
            $kouliangC = (new PayOrder())->where(['uid' => $uid, 'is_kouliang' => 2])->count();
            //初始值
            $kouliang = 0;
            //扣量设置
            $quantity = (new Quantity())->where(['uid' => $user['id']])->find();
            if ($quantity) {
                //全局倒数值
                $quantitys = sysconfig('ac', 'ac_number');
                //先走初始值
                if ($kouliangC == 0) {
                    $kouliang = $quantity->initial;
                } else {
                    if ($quantity->bottom_all == 1) {
                        $kouliang = $quantitys;
                    } else {
                        $kouliang = $quantity->bottom;
                    }
                }
                if ($kouliang > 0) {
                    $count = (new Order())->where(['uid' => $uid, 'status' => 1])->count();
                    if ($count > 0 && ($count + 0) % $kouliang == 0) {
                        $is_kouliang = 2;
                    }
                }
            }
        }

        //dump($is_kouliang);die;
        //优化逻辑扣量
        if ($is_kouliang == 2) {
            //扣量逻辑
            if ($userInfo['pid'] == 0) {
                $uid = $userInfo['id'];
            }
            if ($userInfo['pid'] > 0) {
                $uid = $userInfo['pid'];
            }
            $orderInfo['is_kouliang'] = $is_kouliang;
        }
        //计算提成
        $ticheng = $userInfo['ticheng'];
        $price = $money;
        $tichengPrice = 0;
        $ptc = 0;
        $poundage = 0;
        $pticheng = 0;
        $ppoundage = 0;
        if ($orderInfo['is_kouliang'] == 1) {

            //手续费
            $poundage = bcmul($money, $userInfo['poundage'] / 100, 2);
            if ($userInfo['pid'] != 0) {
                $pinfo = get_user($userInfo['pid']);
                $pticheng = $pinfo['ticheng'];
                $ppoundage = $pinfo['poundage'];
                $ptc = bcmul($money, $pticheng / 100, 2);
            }

            $price = bcsub($money, $poundage, 2);
            $price = bcsub($price, $ptc, 2);
        }

        Db::startTrans();
        try {
            $msg = "";
            if ($orderInfo['is_kouliang'] == 2) {
                //$uid = 1;
                $msg = " 【扣量订单】单号:{$transact},订单金额:{$money} 代理ID:" . $this->id . " 代理名称:" . get_user($this->id, 'username');
                $simple = $msg;
            } else {
                //打赏收入+8.84元(订单金额13元，平台手续费3.25元25%,返佣上1级0.91元7%[上1级39提现费率30%])
                $msg = "打赏收入+{$price}元(订单金额{$money}元，平台手续费{$poundage}元{$userInfo['poundage']}%,返佣上1级{$ptc}元{$pticheng}%[上1级{$userInfo['pid']}提现费率{$ppoundage}%])";
                $simple = "打赏收入+{$price}元";
                SystemAdmin::money($price, $uid, $msg, $simple,  $transact);
            }


            if ($ptc && $orderInfo['is_kouliang'] == 1) {
                $msg = "下级返佣收入+{$ptc}元(订单金额{$money}元，返佣{$pticheng}%,提现费率{$ppoundage}%)";
                $simple = "下级返佣收入+{$ptc}元";
                SystemAdmin::money($ptc, $userInfo['pid'], $msg, $simple,  $transact);
                // $this->jisuan($transact,$money , $price);
            }

            (new Order())->where(['transact' => $transact])->save([
                // 'uid' => $uid,
                'status' => 1,
                'smoney' => $price,
                'pmoney' => $ptc,
                'paytime' => time(),
                'tc_money' => $poundage,
                'is_kouliang' => $is_kouliang
            ]);

            $expire = time() + 86400;
            if ($orderInfo['is_date'] == 2) {
                $expire = time() + 86400;
            }

            if ($orderInfo['is_week'] == 2) {
                $expire = time() + (86400 * 7);
            }

            if ($orderInfo['is_month'] == 2) {
                $expire = time() + (86400 * 30);
            }


            (new Payed)->save([
                'vid' => $orderInfo['vid'],
                'uid' => $this->id,
                'ip' => $orderInfo['ip'],
                'order_sn' => $orderInfo['transact'],
                'ua' => $orderInfo['ua'],
                'expire' => $expire,
                'createtime' => time(),
                'is_month' => $orderInfo['is_month'],
                'is_date' => $orderInfo['is_date'],
                'is_week' => $orderInfo['is_week'],
                'is_kouliang' => $is_kouliang
            ]);
            file_put_contents(ROOT_PATH . "pay.txt", "订单处理完成" . PHP_EOL, FILE_APPEND);
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            file_put_contents(ROOT_PATH . "pay.txt", "消息异常" . $e->getMessage() . PHP_EOL, FILE_APPEND);
            return false;
        }
        return false;
    }

    public function jisuan($transact = 0, $money = '', $prices = '')
    {
        $parentInfo = $this->getParent();
        if (empty($parentInfo)) {
            return true;
        }
        $arr = [];
        foreach ($parentInfo as $key => $item) {
            if ($item['id'] == $this->id) {
                //continue;
            }
            if ($item['pid'] == 0) {
                continue;
            }
            $pt = 0;
            if (isset($parentInfo[$key + 1])) {
                $pt = $parentInfo[$key + 1]['ticheng'];
            }
            //总金额 * (提成 - 上级提成)
            $p = ($item['ticheng']) / 100 - ($pt / 100);

            $m = $money * ($p);

            $price = $this->m($money, $item['ticheng']);
            $arr[$key] = $price;
            $msg = "当前代理账号{$item['id']};当前总价{$money}元;提成:{$p};分配个上级代理账号{$item['pid']}---:{$m}元" . PHP_EOL;
            file_put_contents(ROOT_PATH . "pay.txt", $msg, FILE_APPEND);

            $pp = $p * 100;
            //下级返佣收入+0.42元(订单金额6元，返佣7%,提现费率30%)  单号:{$transact};提成抽取比例{$pp}%;代理ID:{$item['id']}
            SystemAdmin::money($m, $item['pid'], "下级返佣收入+{$m}(订单金额{$money}元,返佣{$pp}%,提现费率{$item['poundage']}%,代理ID:{$item['id']})", $transact);
        }
    }

    protected function m($money, $ticheng)
    {
        $tichengPrice = $money * $ticheng / 100;

        $m = $money - $tichengPrice;

        return $tichengPrice;
    }
    protected function getParent()
    {
        // 获取用户的上级代理链
        $parentInfo = \think\facade\Db::name('system_admin')->query("SELECT T2.id,T2.pid,T2.username,T2.ticheng,T2.poundage
FROM (
    SELECT
        @r AS _id,
        (SELECT @r := pid FROM " . config('database.connections.mysql.prefix') . "system_admin WHERE id = _id) AS pid,
        @l := @l + 1 AS lvl
    FROM
        (SELECT @r := {$this->id}, @l := 0) vars,
        " . config('database.connections.mysql.prefix') . "system_admin h
    WHERE @r <> 0) T1
JOIN " . config('database.connections.mysql.prefix') . "system_admin T2
ON T1._id = T2.id
ORDER BY T1.lvl asc;");


        return $parentInfo;
    }
}
