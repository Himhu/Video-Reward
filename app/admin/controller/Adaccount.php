<?php
// +----------------------------------------------------------------------
// | 控制器名称：账户流水管理控制器（重构版）
// +----------------------------------------------------------------------
// | 控制器功能：统一管理系统账户资金流水记录
// | 包含操作：全局流水管理、个人流水查询、流水统计等
// | 主要职责：提供统一的账户流水查询和管理功能
// | 重构说明：合并了Adaccount和Auaccount两个控制器的功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\AccountService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="账户流水管理")
 */
class Adaccount extends AdminController
{
    /**
     * 账户流水服务
     * @var AccountService
     */
    protected $accountService;

    /**
     * 用户资金流水模型
     * @var \app\admin\model\UserMoneyLog
     */
    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\UserMoneyLog();
        $this->accountService = new AccountService();

        // 传递类型列表给视图
        $this->assign('getTypeList', $this->model->getTypeList());
    }

    /**
     * @NodeAnotation(title="账户流水列表")
     */
    public function index($type = 'all')
    {
        if ($this->request->isAjax()) {
            return $this->getList();
        }

        // 获取统计数据
        $currentUid = $this->request->session('admin.id');
        $statistics = $this->accountService->getAccountStatistics(null, $type, $currentUid);
        $this->assign('statistics', $statistics);
        $this->assign('current_type', $type);

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="个人流水")
     */
    public function personal()
    {
        return $this->index('personal');
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
        $currentUid = $this->request->session('admin.id');
        list($page, $limit, $where) = $this->buildTableParames();

        // 构建排序字符串
        $order = 'create_time desc';
        if (!empty($this->sort)) {
            $orderParts = [];
            foreach ($this->sort as $field => $direction) {
                $orderParts[] = $field . ' ' . $direction;
            }
            $order = implode(', ', $orderParts);
        }

        $result = $this->accountService->getAccountList($where, $page, $limit, $order, $type, $currentUid);

        return json([
            'code' => 0,
            'msg' => '',
            'count' => $result['count'],
            'data' => $result['list']
        ]);
    }

    /**
     * @NodeAnotation(title="获取统计信息")
     */
    public function getStatistics()
    {
        $type = input('type', 'all');
        $currentUid = $this->request->session('admin.id');
        $statistics = $this->accountService->getAccountStatistics(null, $type, $currentUid);

        return json([
            'code' => 0,
            'data' => $statistics
        ]);
    }

    /**
     * @NodeAnotation(title="用户流水详情")
     */
    public function getUserRecords()
    {
        $uid = input('uid');
        $currentUid = $this->request->session('admin.id');

        if (!$uid) {
            return json(['code' => 1, 'msg' => '用户ID不能为空']);
        }

        // 权限检查
        if (!$this->accountService->canViewUserAccount($currentUid, $uid)) {
            return json(['code' => 1, 'msg' => '无权限查看该用户流水']);
        }

        $records = $this->accountService->getUserAccountRecords($uid, 20);

        return json([
            'code' => 0,
            'data' => $records
        ]);
    }

    /**
     * @NodeAnotation(title="导出流水数据")
     */
    public function exportData()
    {
        $type = input('type', 'all');
        $currentUid = $this->request->session('admin.id');
        list($page, $limit, $where) = $this->buildTableParames();

        $data = $this->accountService->exportAccountData($where, $type, $currentUid);

        // 这里可以添加导出逻辑，比如生成Excel文件
        return json([
            'code' => 0,
            'msg' => '导出成功',
            'data' => $data
        ]);
    }

    /**
     * 兼容原有的selectList方法
     */
    public function selectList()
    {
        $type = input('type', 'all');
        $currentUid = $this->request->session('admin.id');
        $where = [];

        // 权限控制
        if ($type === 'personal' || $currentUid != 1) {
            $where['uid'] = $currentUid;
        }

        $list = $this->model->where($where)->select();

        return json([
            'code' => 0,
            'data' => $list
        ]);
    }

}