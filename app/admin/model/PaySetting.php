<?php
// +----------------------------------------------------------------------
// | 模型名称：支付设置模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统支付渠道配置
// | 数据表：pay_setting
// | 主要字段：title(支付名称)、pay_model(支付模式)、mchid(商户号)、appid(应用ID)、key(密钥)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class PaySetting extends TimeModel
{

    protected $name = "pay_setting";

    protected $deleteTime = false;

    
    
    public function getModelList()
    {
        return ['1'=>'Get','2'=>'Post',];
    }

    public function getStatusList()
    {
        return ['0'=>'停用','1'=>'正常',];
    }

    public static function getPayInfo($flg = null)
    {
        return self::where(['pay_model' => $flg])->find();
    }


}