<?php
// +----------------------------------------------------------------------
// | 控制器名称：提现管理控制器（重构版）
// +----------------------------------------------------------------------
// | 控制器功能：统一管理所有提现申请和记录
// | 包含操作：全部提现、待审核、已结算、已拒绝提现的查看和管理
// | 主要职责：管理系统用户的资金提现流程，替代原有的4个分散控制器
// | 重构说明：合并了Outlay、Outlayw、Outlayy、Outlayj四个控制器的功能
// +----------------------------------------------------------------------

namespace app\admin\controller;

use app\admin\service\FinanceService;
use app\admin\model\SystemAdmin;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="提现管理")
 */
class Outlay extends AdminController
{
    /**
     * 财务服务
     * @var FinanceService
     */
    protected $financeService;

    /**
     * 提现模型
     * @var \app\admin\model\Outlay
     */
    protected $model;

    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->model = new \app\admin\model\Outlay();
        $this->financeService = new FinanceService();

        // 传递状态列表给视图
        $this->assign('getStatusList', $this->model->getStatusList());
    }

    /**
     * @NodeAnotation(title="提现列表")
     */
    public function index($status = 'all')
    {
        if ($this->request->isAjax()) {
            return $this->getList();
        }

        // 获取统计数据
        $statistics = $this->getStatisticsData($status);
        $this->assign('statistics', $statistics);
        $this->assign('current_status', $status);

        // 为仪表盘提供专门的待审核统计数据
        // 注意：仪表盘显示的"待处理提现金额"应该只包含待审核状态的记录
        $pendingStatistics = $this->getStatisticsData('pending');

        // 为模板提供兼容的变量名
        // dpayCount 和 dpayMonet 专门用于仪表盘显示待审核数据
        // 这样确保仪表盘只显示需要处理的提现申请，而不是所有状态的记录
        $this->assign('dpayCount', $pendingStatistics['total_count'] ?? 0);
        $this->assign('dpayMonet', $pendingStatistics['total_amount'] ?? '0.00');

        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="待审核列表")
     */
    public function pending()
    {
        return $this->index('pending');
    }

    /**
     * @NodeAnotation(title="已结算列表")
     */
    public function approved()
    {
        return $this->index('approved');
    }

    /**
     * @NodeAnotation(title="已拒绝列表")
     */
    public function rejected()
    {
        return $this->index('rejected');
    }

    /**
     * 获取列表数据（AJAX）
     */
    public function getList()
    {
        try {
            if (input('selectFields')) {
                return $this->selectList();
            }

            $status = input('status', 'all');
            $uid = $this->request->session('admin.id');

            // 检查用户是否已登录
            if (empty($uid)) {
                return json([
                    'code' => 1,
                    'msg' => '用户未登录',
                    'count' => 0,
                    'data' => []
                ]);
            }

            list($page, $limit, $where) = $this->buildTableParames();

            // 根据状态构建查询条件
            $statusWhere = $this->buildStatusWhere($status);
            if ($statusWhere) {
                $where = array_merge($where, $statusWhere);
            }

            // 如果是普通用户，只能看自己的提现记录
            if (!$this->isAdmin()) {
                $where['uid'] = $uid;
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

            $result = $this->financeService->getOutlayList($where, $page, $limit, $order);

            return json([
                'code' => 0,
                'msg' => '',
                'count' => $result['count'],
                'data' => $result['list']
            ]);

        } catch (\Exception $e) {
            // 记录错误日志
            \think\facade\Log::error('提现记录列表获取失败: ' . $e->getMessage());

            return json([
                'code' => 1,
                'msg' => '数据获取失败: ' . $e->getMessage(),
                'count' => 0,
                'data' => []
            ]);
        }
    }

    /**
     * 构建状态查询条件
     * @param string $status 状态
     * @return array
     */
    private function buildStatusWhere($status)
    {
        switch ($status) {
            case 'pending':
                return ['status' => \app\admin\model\Outlay::STATUS_PENDING];
            case 'approved':
                return ['status' => \app\admin\model\Outlay::STATUS_APPROVED];
            case 'rejected':
                return ['status' => \app\admin\model\Outlay::STATUS_REJECTED];
            default:
                return [];
        }
    }

    /**
     * 获取统计数据
     * @param string $status 状态
     * @return array
     */
    private function getStatisticsData($status)
    {
        $statusValue = null;
        switch ($status) {
            case 'pending':
                $statusValue = \app\admin\model\Outlay::STATUS_PENDING;
                break;
            case 'approved':
                $statusValue = \app\admin\model\Outlay::STATUS_APPROVED;
                break;
            case 'rejected':
                $statusValue = \app\admin\model\Outlay::STATUS_REJECTED;
                break;
        }

        return $this->model->getStatistics($statusValue);
    }

    /**
     * 检查是否为管理员
     * @return bool
     */
    private function isAdmin()
    {
        // 这里可以根据实际的权限判断逻辑来实现
        // 暂时返回true，表示都是管理员
        return true;
    }


    /**
     * @NodeAnotation(title="提现申请添加")
     */
    public function add()
    {
        $money =  get_user(session('admin.id'),'balance');
        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $uid = $this->request->session('admin.id');
            $post['uid'] = $uid;


            $info = $this->model->where('uid',$uid)->where('status',0)->select()->toArray();
            
            if($info)
            {
                $this->error('当前已有提现单子正在审批,请勿重复提交.');

            }
            $jg_dbzd = sysconfig('jg','jg_dbzd');
            $jg_dtzg = sysconfig('jg','jg_dtzg');
            $jg_dbzg = sysconfig('jg','jg_dbzg');
            if($post['money'] < $jg_dbzd){
                $this->error('少于单笔最小提现金额,单笔最小提现金额为:'.$jg_dbzd);
            }
            $money_all = $this->model->where(['uid'=>$uid,'status' => 1])->whereDay('end_time', 'today')->sum('money');
            $money_all = $money_all + $post['money'];
            if($money_all > $jg_dtzg){
                $this->error('大于今日提现总额,今日提现总额为:'.$jg_dtzg);
            }
            if($post['money'] > $jg_dbzg){
                $this->error('大于单笔最大提现金额,单笔最大提现金额为:'.$jg_dbzg);
            }
            $row = $this->model->where(['status'=>0])->find($uid);
            if(!empty($row)){
                $this->error('已提交申请');
            }
            $user_arr = SystemAdmin::getUser($uid);
            if($post['money'] > $user_arr['balance']){
                $this->error('余额不足');
            }
            $rule = [];
            $this->validate($post, $rule);

            if($user_arr['txpwd'] != $post['txpwd']){
                $this->error('密码错误');
            }else{
                try {
                    $save = $this->model->save($post);
                } catch (\Exception $e) {
                    $this->error('保存失败:'.$e->getMessage());
                }
                $save ? $this->success('保存成功') : $this->error('保存失败');
            }

        }
        $user_id = $this->request->session('admin.id');
        $tx_arr = $this->model->where(['uid'=>$user_id,'status' => 1])->whereDay('end_time', 'today')->sum('money');
        $this->assign('tx_arr', $tx_arr);
        $this->assign('money',$money);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="批准提现")
     */
    public function approve()
    {
        $id = $this->request->post('id');
        $adminId = $this->request->session('admin.id');
        $remark = $this->request->post('remark', '');

        $result = $this->financeService->approveOutlay($id, [
            'admin_id' => $adminId,
            'remark' => $remark
        ]);

        if ($result === true) {
            $this->success('批准成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * @NodeAnotation(title="拒绝提现")
     */
    public function reject()
    {
        $id = $this->request->post('id');
        $reason = $this->request->post('reason');
        $adminId = $this->request->session('admin.id');

        if (empty($reason)) {
            $this->error('拒绝原因不能为空');
        }

        $result = $this->financeService->rejectOutlay($id, [
            'admin_id' => $adminId,
            'reason' => $reason
        ]);

        if ($result === true) {
            $this->success('拒绝成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * @NodeAnotation(title="批量审核")
     */
    public function batchAudit()
    {
        $ids = $this->request->post('ids');
        $action = $this->request->post('action'); // 1=批准 2=拒绝
        $adminId = $this->request->session('admin.id');
        $reason = $this->request->post('reason', '');

        if (empty($ids)) {
            $this->error('请选择要操作的记录');
        }

        $options = ['admin_id' => $adminId];
        if ($action == 2 && empty($reason)) {
            $this->error('拒绝原因不能为空');
        }
        if (!empty($reason)) {
            $options['reason'] = $reason;
        }

        $result = $this->financeService->batchAudit($ids, $action, $options);

        if ($result === true) {
            $actionText = $action == 1 ? '批准' : '拒绝';
            $this->success($actionText . '成功');
        } else {
            $this->error($result);
        }
    }

    /**
     * @NodeAnotation(title="获取统计信息")
     */
    public function getStatistics()
    {
        $status = input('status');
        $statistics = $this->financeService->getFinanceStatistics('outlay', $status);

        return json([
            'code' => 0,
            'data' => $statistics
        ]);
    }

    /**
     * 兼容原有的selectList方法
     */
    public function selectList()
    {
        $uid = $this->request->session('admin.id');
        $where = [];

        if (!$this->isAdmin()) {
            $where['uid'] = $uid;
        }

        $list = $this->model->where($where)->select();

        return json([
            'code' => 0,
            'data' => $list
        ]);
    }

    /**
     * @NodeAnotation(title="编辑")
     */
    public function edit($id)
    {
        $row = $this->model->find($id);
        $admin_user = new \app\admin\model\SystemAdmin();
        $admin_lists = $admin_user->where('status',1)->select()->toArray();
        empty($row) && $this->error('数据不存在');

        if ($this->request->isAjax()) {
            $post = $this->request->post();
            $rule = [];
            $this->validate($post, $rule);
            $arr = array();

            if(empty($post['status'])){
                return $this->error('请审核');
            }

            if($post['status'] == 1){
                //结算
                $arr['status'] = 1;
                $arr['end_time'] = time();

                //代理信息
                $user_arr = \app\admin\model\SystemAdmin::getUser($post['uid']);
                //提现信息
                $ti_xian = $this->model->where(['id' =>$id])->find()->toArray();

                if($user_arr['balance'] < $ti_xian['money']){
                    return $this->error('余额不足');
                }

                \app\admin\model\SystemAdmin::jmoney($ti_xian['money'],$post['uid'],'代理提现金额'.$ti_xian['money']);
            }elseif($post['status'] == 2){
                //拒绝
                $arr['status'] = 2;
                $arr['refuse_time'] = time();
                $arr['remark'] = $post['remark'];
            }

            try {
                $save = $row->save($arr);
            } catch (\Exception $e) {
                $this->error('保存失败');
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        $this->assign('admin_lists', $admin_lists);
        return $this->fetch();
    }

}