<?php
// +----------------------------------------------------------------------
// | 控制器名称：角色权限管理控制器 - 新权限管理系统
// +----------------------------------------------------------------------
// | 最后修改：2025-01-21 - 控制器重构 - 权限管理控制器重构
// | 修改内容：替换SystemAuth、SystemAuthNode为新的Role、RolePermission模型
// | 新架构：基于RBAC权限体系的角色权限管理控制器
// | 兼容性：PHP 7.4+、ThinkPHP 6.x、新数据库架构v3.0
// +----------------------------------------------------------------------

namespace app\admin\controller\system;

use app\admin\model\Role;
use app\admin\model\Permission;
use app\admin\model\RolePermission;
use app\admin\service\TriggerService;
use app\common\controller\AdminController;
use EasyAdmin\annotation\ControllerAnnotation;
use EasyAdmin\annotation\NodeAnotation;
use think\App;

/**
 * @ControllerAnnotation(title="角色权限管理")
 * Class Auth
 * @package app\admin\controller\system
 */
class Auth extends AdminController
{

    use \app\admin\traits\Curd;

    protected $sort = [
        'sort' => 'desc',
        'id'   => 'desc',
    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new Role();
    }

    /**
     * @NodeAnotation(title="授权")
     * 角色权限分配页面和数据获取
     */
    public function authorize($id)
    {
        $row = $this->model->find($id);
        empty($row) && $this->error('角色数据不存在');

        if ($this->request->isAjax()) {
            // 获取角色的权限选择器数据
            $rolePermissions = RolePermission::getRolePermissions($id);
            $selectedIds = array_column($rolePermissions, 'id');
            $list = Permission::getPermissionSelector($selectedIds);
            $this->success('获取成功', $list);
        }

        $this->assign('row', $row);
        return $this->fetch();
    }

    /**
     * @NodeAnotation(title="授权保存")
     * 保存角色权限分配
     */
    public function saveAuthorize()
    {
        $id = $this->request->post('id');
        $permissions = $this->request->post('permissions', "[]");
        $permissions = json_decode($permissions, true);

        $row = $this->model->find($id);
        empty($row) && $this->error('角色数据不存在');

        try {
            // 使用新的RolePermission模型进行权限分配
            RolePermission::assignRolePermissions($id, $permissions);

            // 触发菜单更新
            TriggerService::updateMenu();

        } catch (\Exception $e) {
            $this->error('权限分配失败：' . $e->getMessage());
        }

        $this->success('权限分配成功');
    }

}