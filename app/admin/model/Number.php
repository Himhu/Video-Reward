<?php
// +----------------------------------------------------------------------
// | 模型名称：邀请码模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统邀请码数据
// | 数据表：number
// | 主要字段：uid(生成用户ID)、number(邀请码)、ua(使用用户ID)、status(状态)、activate_time(激活时间)
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

class Number extends TimeModel
{

    protected $name = "number";

    protected $deleteTime = false;

    protected $dateFormat = 'Y-m-d H:i:s';
    protected $type = [

        'activate_time'  =>  'timestamp',
    ];
    
    
    public function getStatusList()
    {
        return ['0'=>'未激活','1'=>'已激活',];
    }

    public function AdminUa()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "ua");
    }

    public function Admin()
    {
        // 用户资料表的uid 对应用户表的主键id
        return $this->hasOne(SystemAdmin::class, 'id' , "uid");
    }

    /**
     * 获取已激活的邀请码列表
     * @param int $uid 用户ID
     * @return array
     */
    public function getActivatedNumbers($uid = null)
    {
        $where = ['status' => 1];
        if ($uid) {
            $where['uid'] = $uid;
        }

        return $this->where($where)
            ->order('activate_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取未激活的邀请码列表
     * @param int $uid 用户ID
     * @return array
     */
    public function getUnactivatedNumbers($uid = null)
    {
        $where = ['status' => 0];
        if ($uid) {
            $where['uid'] = $uid;
        }

        return $this->where($where)
            ->order('create_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 根据邀请码查找记录
     * @param string $number 邀请码
     * @return array|null
     */
    public function findByNumber($number)
    {
        $result = $this->where('number', $number)->find();
        return $result ? $result->toArray() : null;
    }

    /**
     * 检查邀请码是否存在
     * @param string $number 邀请码
     * @return bool
     */
    public function numberExists($number)
    {
        return $this->where('number', $number)->count() > 0;
    }

    /**
     * 获取用户生成的邀请码统计
     * @param int $uid 用户ID
     * @return array
     */
    public function getUserNumberStats($uid)
    {
        $total = $this->where('uid', $uid)->count();
        $activated = $this->where(['uid' => $uid, 'status' => 1])->count();
        $unactivated = $this->where(['uid' => $uid, 'status' => 0])->count();

        return [
            'total' => $total,
            'activated' => $activated,
            'unactivated' => $unactivated,
            'activation_rate' => $total > 0 ? round(($activated / $total) * 100, 2) : 0
        ];
    }

    /**
     * 获取下级用户列表（通过激活的邀请码）
     * @param int $uid 用户ID
     * @return array
     */
    public function getSubordinateUsers($uid)
    {
        return $this->where(['uid' => $uid, 'status' => 1])
            ->with(['AdminUa'])
            ->order('activate_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 检查邀请码是否可以删除
     * @param int $id 邀请码ID
     * @return bool
     */
    public function canDelete($id)
    {
        $number = $this->find($id);
        if (!$number) {
            return false;
        }

        // 已激活的邀请码不能删除
        return $number['status'] == 0;
    }

    /**
     * 批量生成邀请码
     * @param int $uid 生成用户ID
     * @param int $count 生成数量
     * @return bool
     */
    public function batchGenerate($uid, $count)
    {
        $data = [];
        $time = time();

        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'uid' => $uid,
                'number' => $this->generateUniqueNumber(),
                'status' => 0,
                'create_time' => $time
            ];
        }

        return $this->saveAll($data) ? true : false;
    }

    /**
     * 生成唯一邀请码
     * @return string
     */
    private function generateUniqueNumber()
    {
        do {
            $number = $this->generateRandomNumber();
            $exists = $this->where('number', $number)->find();
        } while ($exists);

        return $number;
    }

    /**
     * 生成随机邀请码
     * @return string
     */
    private function generateRandomNumber()
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $number = '';
        for ($i = 0; $i < 8; $i++) {
            $number .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $number;
    }
}