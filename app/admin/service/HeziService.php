<?php
// +----------------------------------------------------------------------
// | 服务名称：推广盒子管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理推广链接和短链接生成相关的业务逻辑
// | 主要职责：推广链接管理、二维码生成、域名规则处理等
// | 业务规则：推广工具的统一管理和短链接服务
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\Hezi;
use app\admin\model\DomainRule;
use app\admin\model\DomainLib;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemConfig;
use think\facade\Db;

/**
 * 推广盒子管理服务
 * Class HeziService
 * @package app\admin\service
 */
class HeziService extends BaseService
{
    /**
     * DomainRule模型
     * @var DomainRule
     */
    protected $ruleModel;

    /**
     * DomainLib模型
     * @var DomainLib
     */
    protected $libModel;

    /**
     * SystemAdmin模型
     * @var SystemAdmin
     */
    protected $adminModel;

    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new Hezi();
        $this->ruleModel = new DomainRule();
        $this->libModel = new DomainLib();
        $this->adminModel = new SystemAdmin();
    }

    /**
     * 获取推广盒子列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @return array
     */
    public function getHeziList($where = [], $page = 1, $limit = 20, $order = 'create_time desc')
    {
        $count = $this->model->where($where)->count();
        $list = $this->model->where($where)
            ->with(['Admin'])
            ->page($page, $limit)
            ->order($order)
            ->select()
            ->toArray();

        // 格式化数据
        foreach ($list as &$item) {
            $item['create_time_formatted'] = date('Y-m-d H:i:s', strtotime($item['create_time']));
            $item['admin_name'] = $item['admin']['username'] ?? '未知';
            $item['status_text'] = $this->getStatusText($item['status']);
            $item['qr_code_url'] = $this->generateQrCodeUrl($item['short_url']);
        }

        return [
            'count' => $count,
            'list' => $list
        ];
    }

    /**
     * 创建推广盒子
     * @param array $data 盒子数据
     * @return array
     */
    public function createHezi($data)
    {
        // 验证必要字段
        if (empty($data['title']) || empty($data['url'])) {
            return ['success' => false, 'message' => '标题和链接不能为空'];
        }

        // 生成短链接
        $shortUrl = $this->generateShortUrl($data['url']);
        if (!$shortUrl) {
            return ['success' => false, 'message' => '短链接生成失败'];
        }

        $data['short_url'] = $shortUrl;
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['status'] = $data['status'] ?? 1;

        if ($this->model->save($data)) {
            return [
                'success' => true,
                'message' => '创建成功',
                'data' => [
                    'id' => $this->model->id,
                    'short_url' => $shortUrl
                ]
            ];
        }

        return ['success' => false, 'message' => '创建失败'];
    }

    /**
     * 更新推广盒子
     * @param int $id 盒子ID
     * @param array $data 更新数据
     * @return array
     */
    public function updateHezi($id, $data)
    {
        $hezi = $this->model->find($id);
        if (!$hezi) {
            return ['success' => false, 'message' => '记录不存在'];
        }

        // 如果URL发生变化，重新生成短链接
        if (isset($data['url']) && $data['url'] != $hezi['url']) {
            $shortUrl = $this->generateShortUrl($data['url']);
            if ($shortUrl) {
                $data['short_url'] = $shortUrl;
            }
        }

        $data['update_time'] = date('Y-m-d H:i:s');

        if ($hezi->save($data)) {
            return ['success' => true, 'message' => '更新成功'];
        }

        return ['success' => false, 'message' => '更新失败'];
    }

    /**
     * 生成短链接
     * @param string $originalUrl 原始URL
     * @return string|false
     */
    public function generateShortUrl($originalUrl)
    {
        // 获取域名规则
        $domainRule = $this->ruleModel->where('status', 1)->find();
        if (!$domainRule) {
            return false;
        }

        // 生成短码
        $shortCode = $this->generateShortCode();
        
        // 构建短链接
        $shortUrl = rtrim($domainRule['domain'], '/') . '/' . $shortCode;

        // 保存到域名库
        $this->libModel->save([
            'short_code' => $shortCode,
            'original_url' => $originalUrl,
            'short_url' => $shortUrl,
            'create_time' => date('Y-m-d H:i:s'),
            'status' => 1
        ]);

        return $shortUrl;
    }

    /**
     * 生成二维码URL
     * @param string $url 要生成二维码的URL
     * @return string
     */
    public function generateQrCodeUrl($url)
    {
        // 这里返回二维码生成的URL，实际生成在控制器中处理
        return url('admin/hezi/qrcode', ['url' => urlencode($url)]);
    }

    /**
     * 获取推广盒子统计
     * @param int|null $uid 用户ID
     * @return array
     */
    public function getHeziStatistics($uid = null)
    {
        $where = [];
        if ($uid) {
            $where['uid'] = $uid;
        }

        $total = $this->model->where($where)->count();
        $active = $this->model->where($where)->where('status', 1)->count();
        $inactive = $this->model->where($where)->where('status', 0)->count();
        $todayCount = $this->model->where($where)->whereTime('create_time', 'today')->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'today_count' => $todayCount,
            'activation_rate' => $total > 0 ? round(($active / $total) * 100, 2) : 0
        ];
    }

    /**
     * 批量更新状态
     * @param array $ids 盒子ID数组
     * @param int $status 状态
     * @return array
     */
    public function batchUpdateStatus($ids, $status)
    {
        if (empty($ids)) {
            return ['success' => false, 'message' => '请选择要操作的记录'];
        }

        $count = $this->model->whereIn('id', $ids)->update([
            'status' => $status,
            'update_time' => date('Y-m-d H:i:s')
        ]);

        if ($count > 0) {
            return ['success' => true, 'message' => "成功更新{$count}条记录"];
        }

        return ['success' => false, 'message' => '更新失败'];
    }

    /**
     * 获取状态文本
     * @param int $status 状态值
     * @return string
     */
    public function getStatusText($status)
    {
        $statusMap = [
            0 => '禁用',
            1 => '启用'
        ];

        return $statusMap[$status] ?? '未知状态';
    }

    /**
     * 生成短码
     * @return string
     */
    private function generateShortCode()
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $shortCode = '';
        
        do {
            $shortCode = '';
            for ($i = 0; $i < 6; $i++) {
                $shortCode .= $chars[rand(0, strlen($chars) - 1)];
            }
            
            // 检查是否已存在
            $exists = $this->libModel->where('short_code', $shortCode)->find();
        } while ($exists);

        return $shortCode;
    }

    /**
     * 删除推广盒子
     * @param int $id 盒子ID
     * @return array
     */
    public function deleteHezi($id)
    {
        $hezi = $this->model->find($id);
        if (!$hezi) {
            return ['success' => false, 'message' => '记录不存在'];
        }

        // 同时删除相关的短链接记录
        $this->libModel->where('short_url', $hezi['short_url'])->delete();

        if ($this->delete($id)) {
            return ['success' => true, 'message' => '删除成功'];
        }

        return ['success' => false, 'message' => '删除失败'];
    }

    /**
     * 批量删除推广盒子
     * @param array $ids 盒子ID数组
     * @return array
     */
    public function batchDeleteHezi($ids)
    {
        if (empty($ids)) {
            return ['success' => false, 'message' => '请选择要删除的记录'];
        }

        $this->startTrans();
        try {
            // 获取要删除的短链接
            $shortUrls = $this->model->whereIn('id', $ids)->column('short_url');
            
            // 删除相关的短链接记录
            if ($shortUrls) {
                $this->libModel->whereIn('short_url', $shortUrls)->delete();
            }

            // 删除推广盒子记录
            $count = $this->model->whereIn('id', $ids)->delete();

            $this->commit();
            return ['success' => true, 'message' => "成功删除{$count}条记录"];
        } catch (\Exception $e) {
            $this->rollback();
            return ['success' => false, 'message' => '删除失败：' . $e->getMessage()];
        }
    }
}
