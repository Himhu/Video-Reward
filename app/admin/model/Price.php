<?php
// +----------------------------------------------------------------------
// | 模型名称：价格设置模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统价格配置数据
// | 数据表：price
// | 主要字段：uid(用户ID)、pay_model(支付模式)、is_dan(单次开关)、dan_fee(单次价格)、is_day(包天开关)
// +----------------------------------------------------------------------

namespace app\admin\model;



use app\common\model\TimeModel;

class Price extends TimeModel
{

    protected $name = "price";



    public function admins()
    {
        return $this->hasOne(SystemAdmin::class , 'id' ,'pid');
    }

    public function outlay()
    {
        return $this->hasMany(Outlay::class , 'uid' , 'id');
    }
    public function domain()
    {
        return $this->hasMany(DomainLib::class , 'uid' , 'id');
    }

    public function orders()
    {
        return $this->hasMany(PayOrder::class,"uid","id");
    }
    public function views()
    {
        return $this->hasOne(Muban::class  ,'id' ,'view_id' );
    }




}