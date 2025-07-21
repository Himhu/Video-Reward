// +----------------------------------------------------------------------
// | 角色权限管理前端脚本 - 新权限管理系统
// +----------------------------------------------------------------------
// | 最后修改：2025-01-21 - 前端界面适配 - 权限分配界面重构
// | 修改内容：适配新的RBAC权限体系，支持模块化权限树结构
// | 新架构：基于Permission::getPermissionSelector()的权限分配界面
// | 兼容性：LayUI、EasyAdmin框架、新权限管理系统v3.0
// +----------------------------------------------------------------------

define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.auth/index',
        add_url: 'system.auth/add',
        edit_url: 'system.auth/edit',
        delete_url: 'system.auth/delete',
        export_url: 'system.auth/export',
        modify_url: 'system.auth/modify',
        authorize_url: 'system.auth/authorize',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                cols: [[
                    {type: "checkbox"},
                    {field: 'id', width: 80, title: 'ID'},
                    {field: 'sort', width: 80, title: '排序', edit: 'text'},
                    {field: 'title', minWidth: 80, title: '权限名称'},
                    {field: 'remark', minWidth: 80, title: '备注信息'},
                    {field: 'status', title: '状态', width: 85, search: 'select', selectList: {0: '禁用', 1: '启用'}, templet: ea.table.switch},
                    {field: 'create_time', minWidth: 80, title: '创建时间', search: 'range'},
                    {
                        width: 250,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                            'edit',
                            [{
                                text: '授权',
                                url: init.authorize_url,
                                method: 'open',
                                auth: 'authorize',
                                class: 'layui-btn layui-btn-normal layui-btn-xs',
                            }],
                            'delete'
                        ]
                    }
                ]],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        authorize: function () {
            var tree = layui.tree;

            // 获取权限选择器数据 - 基于新RBAC权限体系
            ea.request.get(
                {
                    url: window.location.href,
                }, function (res) {
                    res.data = res.data || [];

                    // 渲染权限树 - 支持模块化权限结构
                    tree.render({
                        elem: '#node_ids',
                        data: res.data,
                        showCheckbox: true,
                        id: 'permissionTreeId',
                        showLine: true,
                        accordion: false,
                        onlyIconControl: true,
                    });
                }
            );

            // 监听表单提交 - 适配新的权限分配逻辑
            ea.listen(function (data) {
                var checkedData = tree.getChecked('permissionTreeId');
                var permissionIds = [];

                // 递归收集所有选中的权限ID
                function collectPermissionIds(nodes) {
                    $.each(nodes, function (i, node) {
                        // 只收集权限节点的ID（排除模块节点）
                        if (node.type !== 'module' && node.id) {
                            permissionIds.push(node.id);
                        }

                        // 递归处理子节点
                        if (node.children && node.children.length > 0) {
                            collectPermissionIds(node.children);
                        }
                    });
                }

                collectPermissionIds(checkedData);

                // 使用新的参数名 permissions 替代 node
                data.permissions = JSON.stringify(permissionIds);

                return data;
            });

        }
    };
    return Controller;
});