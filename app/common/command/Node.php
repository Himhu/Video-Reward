<?php

// +----------------------------------------------------------------------
// | 配置名称：权限节点刷新服务 - 新权限管理系统
// +----------------------------------------------------------------------
// | 最后修改：2025-01-21 - 系统清理和优化 - 命令行工具重构
// | 修改内容：替换SystemNode为新的Permission模型，适配新权限体系
// | 新架构：基于RBAC权限体系的权限节点刷新命令行工具
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\common\command;

use app\admin\model\Permission;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use EasyAdmin\auth\Node as NodeService;

class Node extends Command
{
    protected function configure()
    {
        $this->setName('node')
            ->addOption('force', null, Option::VALUE_REQUIRED, '是否强制刷新', 0)
            ->setDescription('系统节点刷新服务');
    }

    protected function execute(Input $input, Output $output)
    {
        $force = $input->getOption('force');
        $output->writeln("========正在刷新节点服务：=====" . date('Y-m-d H:i:s'));
        $check = $this->refresh($force);
        $check !== true && $output->writeln("节点刷新失败：" . $check);
        $output->writeln("刷新完成：" . date('Y-m-d H:i:s'));
    }

    protected function refresh($force)
    {
        $nodeList = (new NodeService())->getNodelist();
        if (empty($nodeList)) {
            return true;
        }

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

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
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