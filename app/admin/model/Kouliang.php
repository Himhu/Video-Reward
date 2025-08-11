<?php
// +----------------------------------------------------------------------
// | 模型名称：扣量记录模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统扣量记录数据
// | 主要职责：扣量记录的数据操作和业务逻辑
// | 数据表：ds3_kouliang
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\common\model\TimeModel;

/**
 * 扣量记录模型
 * Class Kouliang
 * @package app\admin\model
 */
class Kouliang extends TimeModel
{
    // 数据表名
    protected $name = 'kouliang';

    // 自动时间戳
    protected $autoWriteTimestamp = true;

    // 字段类型转换
    protected $type = [
        'create_time' => 'timestamp',
        'update_time' => 'timestamp',
    ];

    /**
     * 获取状态列表
     * @return array
     */
    public function getStatusList()
    {
        return [
            0 => '禁用',
            1 => '正常'
        ];
    }

    /**
     * 关联用户信息
     * @return \think\model\relation\HasOne
     */
    public function admin()
    {
        return $this->hasOne(SystemAdmin::class, 'id', 'uid');
    }

    /**
     * 获取用户的扣量记录
     * @param int $uid 用户ID
     * @return array
     */
    public function getUserKouliangRecords($uid)
    {
        return $this->where('uid', $uid)
            ->with(['admin'])
            ->order('create_time desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取扣量统计
     * @param array $where 查询条件
     * @return array
     */
    public function getKouliangStatistics($where = [])
    {
        $total = $this->where($where)->count();
        $todayCount = $this->where($where)->whereTime('create_time', 'today')->count();
        $weekCount = $this->where($where)->whereTime('create_time', 'week')->count();
        $monthCount = $this->where($where)->whereTime('create_time', 'month')->count();

        return [
            'total' => $total,
            'today' => $todayCount,
            'week' => $weekCount,
            'month' => $monthCount
        ];
    }

    /**
     * 添加扣量记录
     * @param int $uid 用户ID
     * @param string $remark 备注
     * @return bool
     */
    public function addKouliangRecord($uid, $remark = '')
    {
        $data = [
            'uid' => $uid,
            'create_time' => time(),
            'status' => 1,
            'remark' => $remark
        ];

        return $this->save($data);
    }

    /**
     * 检查用户是否有扣量记录
     * @param int $uid 用户ID
     * @return bool
     */
    public function hasKouliangRecord($uid)
    {
        return $this->where('uid', $uid)->count() > 0;
    }

    /**
     * 获取最近的扣量记录
     * @param int $limit 数量限制
     * @return array
     */
    public function getRecentRecords($limit = 10)
    {
        return $this->with(['admin'])
            ->order('create_time desc')
            ->limit($limit)
            ->select()
            ->toArray();
    }
}
