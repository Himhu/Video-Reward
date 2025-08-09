define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'paysetting/index',
        add_url: 'paysetting/add',
        edit_url: 'paysetting/edit',
        delete_url: 'paysetting/delete',
        export_url: 'paysetting/export',
        modify_url: 'paysetting/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                searchCols:[[
                    {field: 'title', title:'支付名称'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    //{field: 'id', title: 'id'},
                    {minWidth: 80, field: 'title', title: '支付名称'},
                    {minWidth: 80, field: 'app_id', title: '支付ID'},
                    {minWidth: 80, field: 'app_key', title: '支付秘钥'},
                    {minWidth: 80, field: 'pay_url', title: '支付网关'},
                    //{field: 'pay_channel', title: '执行方法'},
                    //{field: 'model', search: 'select', selectList: {"1":"Get","2":"Post"}, title: '执行方式'},
                    {minWidth: 80, field: 'status', search: 'select', selectList: ["停用","正常"], title: '状态', templet: ea.table.switch},
                    {minWidth: 80, field: 'create_time', title: '创建时间'},
                    {minWidth: 80, title: '操作', templet: ea.table.tool},
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
    };
    return Controller;
});