<?php
// +----------------------------------------------------------------------
// | 服务名称：公共片库管理服务
// +----------------------------------------------------------------------
// | 服务功能：处理公共视频资源库相关的业务逻辑
// | 主要职责：视频导入、批量发布、资源管理等
// | 业务规则：视频资源的统一管理和分发
// +----------------------------------------------------------------------

namespace app\admin\service;

use app\admin\model\Stock;
use app\admin\model\Category;
use app\admin\model\SystemAdmin;
use app\admin\model\Link;
use think\facade\Db;

/**
 * 公共片库管理服务
 * Class StockService
 * @package app\admin\service
 */
class StockService extends BaseService
{
    /**
     * Category模型
     * @var Category
     */
    protected $categoryModel;

    /**
     * SystemAdmin模型
     * @var SystemAdmin
     */
    protected $adminModel;

    /**
     * Link模型
     * @var Link
     */
    protected $linkModel;

    /**
     * 初始化模型
     */
    protected function initModel()
    {
        $this->model = new Stock();
        $this->categoryModel = new Category();
        $this->adminModel = new SystemAdmin();
        $this->linkModel = new Link();
    }

    /**
     * 获取片库列表
     * @param array $where 查询条件
     * @param int $page 页码
     * @param int $limit 每页数量
     * @param string $order 排序
     * @return array
     */
    public function getStockList($where = [], $page = 1, $limit = 20, $order = 'create_time desc')
    {
        $count = $this->model->where($where)->count();
        $list = $this->model->where($where)
            ->with(['Category', 'Admin'])
            ->page($page, $limit)
            ->order($order)
            ->select()
            ->toArray();

        // 格式化数据
        foreach ($list as &$item) {
            $item['create_time_formatted'] = date('Y-m-d H:i:s', strtotime($item['create_time']));
            $item['category_name'] = $item['category']['name'] ?? '未分类';
            $item['admin_name'] = $item['admin']['username'] ?? '未知';
        }

        return [
            'count' => $count,
            'list' => $list
        ];
    }

    /**
     * 批量导入视频
     * @param string $videoMsg 视频信息（换行分隔）
     * @param int $categoryId 分类ID
     * @param int $adminId 管理员ID
     * @return array
     */
    public function batchImportVideos($videoMsg, $categoryId, $adminId)
    {
        $videos = explode("\n", $videoMsg);
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        $this->startTrans();
        try {
            foreach ($videos as $video) {
                $video = trim($video);
                if (empty($video)) {
                    continue;
                }

                // 解析视频信息
                $videoData = $this->parseVideoInfo($video);
                if (!$videoData) {
                    $failCount++;
                    $errors[] = "视频信息格式错误: {$video}";
                    continue;
                }

                // 添加额外信息
                $videoData['cid'] = $categoryId;
                $videoData['uid'] = $adminId;
                $videoData['create_time'] = date('Y-m-d H:i:s');

                // 保存视频
                if ($this->model->save($videoData)) {
                    $successCount++;
                } else {
                    $failCount++;
                    $errors[] = "保存失败: {$video}";
                }
            }

            $this->commit();
            return [
                'success' => true,
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'errors' => $errors
            ];
        } catch (\Exception $e) {
            $this->rollback();
            return [
                'success' => false,
                'message' => '导入失败：' . $e->getMessage()
            ];
        }
    }

    /**
     * 批量发布视频到用户
     * @param array $stockIds 片库ID数组
     * @param int $targetUid 目标用户ID
     * @return array
     */
    public function batchPublishToUser($stockIds, $targetUid)
    {
        if (empty($stockIds)) {
            return ['success' => false, 'message' => '请选择要发布的视频'];
        }

        $successCount = 0;
        $failCount = 0;

        $this->startTrans();
        try {
            foreach ($stockIds as $stockId) {
                $stock = $this->model->find($stockId);
                if (!$stock) {
                    $failCount++;
                    continue;
                }

                // 创建用户链接记录
                $linkData = [
                    'uid' => $targetUid,
                    'cid' => $stock['cid'],
                    'title' => $stock['title'],
                    'url' => $stock['url'],
                    'img' => $stock['img'],
                    'create_time' => date('Y-m-d H:i:s'),
                    'status' => 1
                ];

                if ($this->linkModel->save($linkData)) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            }

            $this->commit();
            return [
                'success' => true,
                'success_count' => $successCount,
                'fail_count' => $failCount
            ];
        } catch (\Exception $e) {
            $this->rollback();
            return [
                'success' => false,
                'message' => '发布失败：' . $e->getMessage()
            ];
        }
    }

    /**
     * 获取片库统计信息
     * @return array
     */
    public function getStockStatistics()
    {
        $total = $this->model->count();
        $todayCount = $this->model->whereTime('create_time', 'today')->count();
        $categories = $this->categoryModel->column('name', 'id');
        
        $categoryStats = [];
        foreach ($categories as $id => $name) {
            $categoryStats[] = [
                'category_name' => $name,
                'count' => $this->model->where('cid', $id)->count()
            ];
        }

        return [
            'total' => $total,
            'today_count' => $todayCount,
            'category_stats' => $categoryStats
        ];
    }

    /**
     * 解析视频信息
     * @param string $videoInfo 视频信息字符串
     * @return array|false
     */
    private function parseVideoInfo($videoInfo)
    {
        // 这里可以根据实际的视频信息格式进行解析
        // 假设格式为：标题|URL|图片URL
        $parts = explode('|', $videoInfo);
        
        if (count($parts) >= 2) {
            return [
                'title' => trim($parts[0]),
                'url' => trim($parts[1]),
                'img' => isset($parts[2]) ? trim($parts[2]) : ''
            ];
        }

        return false;
    }

    /**
     * 删除片库记录
     * @param int $id 记录ID
     * @return bool
     */
    public function deleteStock($id)
    {
        return $this->delete($id);
    }

    /**
     * 批量删除片库记录
     * @param array $ids 记录ID数组
     * @return bool
     */
    public function batchDeleteStock($ids)
    {
        return $this->batchDelete($ids);
    }
}
