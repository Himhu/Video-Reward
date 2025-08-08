<?php
// +----------------------------------------------------------------------
// | 模型名称：系统管理员模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统管理员账户数据
// | 数据表：system_admin
// | 主要字段：username(用户名)、password(密码)、head_img(头像)、auth_ids(角色ID集合)、status(状态)
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\MoneyLog;
use app\common\model\TimeModel;

class SystemAdmin extends TimeModel
{

    protected $deleteTime = 'delete_time';

    public function getAuthList()
    {
        $list = (new SystemAuth())
            ->where('status', 1)
            ->column('title', 'id');
        return $list;
    }

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

    //加余额
    public static function money($money, $user_id, $memo ,$simple = '' , $transact = '')
    {
        $user = self::find($user_id);
        if ($user && $money != 0) {
            $before = $user->balance;
            $after = $user->balance + $money;

            $revenue = $user->revenue + $money;
            //更新会员信息
            $user->save(['balance' => $after , 'revenue' => $revenue]);
            //写入日志
            UserMoneyLog::create([
                'uid' => $user_id,
                'type' => 1,
                'money' => $money,
                'before' => $before,
                'after' => $after,
                'memo' => $memo ,
                'simple' => $simple,
                'order_on' => $transact
            ]);
        }
    }

    //减余额
    public static function jmoney($money, $user_id, $memo)
    {
        $user = self::find($user_id);
        if ($user && $money != 0) {
            $before = $user->balance;
            $after = $user->balance - $money;
            //更新会员信息
            $user->save(['balance' => $after]);
            //写入日志
            UserMoneyLog::create([
                'uid' => $user_id,
                'type' => 2 ,
                'money' => $money,
                'before' => $before,
                'after' => $after,
                'memo' => $memo,
                'simple' => $memo
            ]);
        }
    }


    public static function getUser($id)
    {
        $user = self::find($id);
        if(empty($user))
        {
            exit("账号不存在.或者已删除");
        }

        return self::find($id)->toArray();
    }

}