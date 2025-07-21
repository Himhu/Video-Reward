<?php
// +----------------------------------------------------------------------
// | 控制器名称：系统权限管理控制器 - 新权限管理系统
// +----------------------------------------------------------------------
// | 最后修改：2025-01-21 - 控制器重构 - 权限管理控制器重构
// | 修改内容：替换SystemNode为新的Permission模型，适配新权限模块分组机制
// | 新架构：基于RBAC权限体系的权限节点管理控制器
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\controller\system;

use app\admin\model\Permission;
use app\admin\service\TriggerService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use EasyAdmin\auth\Node as NodeService;
use think\App;

/**
 * @ControllerAnnotation(title="系统节点管理")
 * Class Node
 * @package app\admin\controller\system
 */
class Node extends AdminController
{

    use \app\admin\traits\Curd;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Permission();
    }

    /**
     * @NodeAnotation(title="列表")
     * 权限列表页面和数据获取
     */
    public function index()
    {
        if ($this->request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }

            $count = $this->model->count();
            $list = Permission::getPermissionTree(true);

            $data = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="系统权限更新")
     * 基于新权限系统的权限节点更新
     */
    public function refreshNode($force = 0)
    {
        $nodeList = (new NodeService())->getNodelist();
        empty($nodeList) && $this->error('暂无需要更新的系统权限');

        try {
            // 转换节点为权限格式
            $permissionList = $this->convertNodesToPermissions($nodeList);

            if ($force == 1) {
                // 强制更新现有权限
                foreach ($permissionList as $permission) {
                    $existPermission = Permission::where('slug', $permission['slug'])->find();
                    if ($existPermission) {
                        $existPermission->save([
                            'name' => $permission['name'],
                            'description' => $permission['description'],
                        ]);
                    }
                }
            }

            // 批量创建新权限
            Permission::batchCreate($permissionList);

            TriggerService::updateNode();
        } catch (\Exception $e) {
            $this->error('权限更新失败：' . $e->getMessage());
        }
        $this->success('权限更新成功');
    }

    /**
     * @NodeAnotation(title="清除失效权限")
     * 清除系统中不再存在的权限节点
     */
    public function clearNode()
    {
        $nodeList = (new NodeService())->getNodelist();

        try {
            $existPermissions = Permission::field('id,slug,name')->select()->toArray();
            $formatNodeList = array_format_key($nodeList, 'node');

            foreach ($existPermissions as $permission) {
                // 如果权限对应的节点不存在，则删除该权限
                if (!isset($formatNodeList[$permission['slug']])) {
                    Permission::where('id', $permission['id'])->delete();
                }
            }

            TriggerService::updateNode();
        } catch (\Exception $e) {
            $this->error('权限清理失败：' . $e->getMessage());
        }
        $this->success('权限清理成功');
    }

    /**
     * 将节点转换为权限格式
     *
     * @param array $nodeList 节点列表
     * @return array 权限列表
     */
    protected function convertNodesToPermissions($nodeList)
    {
        $permissions = [];

        foreach ($nodeList as $node) {
            // 解析节点路径确定模块
            $module = $this->parseNodeModule($node['node']);

            $permissions[] = [
                'name' => $node['title'],
                'slug' => $node['node'],
                'module' => $module,
                'description' => $node['title'] . ' - 系统自动生成',
                'status' => Permission::STATUS_ACTIVE,
            ];
        }

        return $permissions;
    }

    /**
     * 解析节点路径确定所属模块
     *
     * @param string $node 节点路径
     * @return string 模块名称
     */
    protected function parseNodeModule($node)
    {
        // 默认映射规则
        $moduleMap = [
            'system' => Permission::MODULE_SYSTEM,
            'agent' => Permission::MODULE_AGENT,
            'content' => Permission::MODULE_CONTENT,
            'payment' => Permission::MODULE_PAYMENT,
            'report' => Permission::MODULE_REPORT,
            'config' => Permission::MODULE_CONFIG,
        ];

        // 解析节点路径的第一部分作为模块
        $parts = explode('/', $node);
        $controllerPath = $parts[0] ?? '';
        $controllerParts = explode('.', $controllerPath);
        $module = $controllerParts[0] ?? 'system';

        return $moduleMap[$module] ?? Permission::MODULE_SYSTEM;
    }
}