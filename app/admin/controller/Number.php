<?php
// +----------------------------------------------------------------------
// | 控制器名称：邀请码管理控制器（重构版）
// +----------------------------------------------------------------------
// | 控制器功能：统一管理邀请码生成、激活、下级管理等功能
// | 包含操作：邀请码列表、下级管理、批量生成、激活统计等
// | 主要职责：系统邀请注册机制的管理，替代原有的Number和Numberx控制器
// | 重构说明：合并了Number和Numberx两个控制器的功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\NumberService;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="邀请码管理")
 */
class Number extends AdminController
{
    /**
     * 邀请码服务
     * @var NumberService
     */
    protected $numberService;

    /**
     * 邀请码模型
     * @var \app\admin\model\Number
     */
    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Number();
        $this->numberService = new NumberService();

        // 传递状态列表给视图
        $this->assign('getStatusList', $this->model->getStatusList());
    }



    /**
     * @NodeAnotation(title="邀请码列表")
     */
    public function index($type = 'all')
    {
        if ($this->request->isAjax()) {
            return $this->getList();
        }

        // 获取统计数据
        $uid = $type === 'subordinate' ? $this->request->session('admin.id') : null;
        $statistics = $this->numberService->getNumberStatistics($uid, $type);
        $this->assign('statistics', $statistics);
        $this->assign('current_type', $type);

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="下级管理")
     */
    public function subordinate()
    {
        return $this->index('subordinate');
    }

    /**
     * 获取列表数据（AJAX）
     */
    public function getList()
    {
        if (input('selectFields')) {
            return $this->selectList();
        }

        $type = input('type', 'all');
        $uid = $this->request->session('admin.id');
        list($page, $limit, $where) = $this->buildTableParames();

        // 如果是下级管理，添加当前用户ID
        if ($type === 'subordinate') {
            $where['current_uid'] = $uid;
        }

        // 构建排序字符串
        $order = 'create_time desc';
        if (!empty($this->sort)) {
            $orderParts = [];
            foreach ($this->sort as $field => $direction) {
                $orderParts[] = $field . ' ' . $direction;
            }
            $order = implode(', ', $orderParts);
        }

        $result = $this->numberService->getNumberList($where, $page, $limit, $order, $type);

        return json([
            'code' => 0,
            'msg' => '',
            'count' => $result['count'],
            'data' => $result['list']
        ]);
    }


    /**
     * @NodeAnotation(title="生成邀请码")
     */
    public function add()
    {
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $uid = $post['uid'] ?? $this->request->session('admin.id');
            $num = $post['num'] ?? 1;

            if ($num <= 0 || $num > 100) {
                $this->error('生成数量必须在1-100之间');
            }

            $result = $this->numberService->generateNumbers($uid, $num);
            if ($result === true) {
                $this->success('生成成功');
            } else {
                $this->error($result);
            }
        }

        $admin_user = new SystemAdmin();
        $admin_lists = $admin_user->where('status', 1)->select()->toArray();
        $this->assign('admin_lists', $admin_lists);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="激活邀请码")
     */
    public function activate()
    {
        $number = $this->request->post('number');
        $activateUid = $this->request->post('activate_uid');

        if (empty($number) || empty($activateUid)) {
            $this->error('邀请码和激活用户ID不能为空');
        }

        $result = $this->numberService->activateNumber($number, $activateUid);
        if ($result === true) {
            $this->success('激活成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * @NodeAnotation(title="批量删除")
     */
    public function batchDelete()
    {
        $ids = $this->request->post('ids');
        $onlyUnactivated = $this->request->post('only_unactivated', true);

        if (empty($ids)) {
            $this->error('请选择要删除的记录');
        }

        $result = $this->numberService->batchDeleteNumbers($ids, $onlyUnactivated);
        if ($result === true) {
            $this->success('删除成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * @NodeAnotation(title="获取统计信息")
     */
    public function getStatistics()
    {
        $type = input('type', 'all');
        $uid = $type === 'subordinate' ? $this->request->session('admin.id') : null;
        $statistics = $this->numberService->getNumberStatistics($uid, $type);

        return json([
            'code' => 0,
            'data' => $statistics
        ]);
    }

    /**
     * @NodeAnotation(title="下级订单统计")
     */
    public function getSubordinateOrderStats()
    {
        $subordinateUid = input('uid');
        if (!$subordinateUid) {
            return json(['code' => 1, 'msg' => '用户ID不能为空']);
        }

        $stats = $this->numberService->getUserOrderStats($subordinateUid);

        return json([
            'code' => 0,
            'data' => $stats
        ]);
    }

    /**
     * 兼容原有的selectList方法
     */
    public function selectList()
    {
        $type = input('type', 'all');
        $uid = $type === 'subordinate' ? $this->request->session('admin.id') : null;
        $where = [];

        if ($uid && $type === 'subordinate') {
            $where['uid'] = $uid;
        }

        $list = $this->model->where($where)->select();

        return json([
            'code' => 0,
            'data' => $list
        ]);
    }

}