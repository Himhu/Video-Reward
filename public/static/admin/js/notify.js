define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'notify/index',
        add_url: 'notify/add',
        edit_url: 'notify/edit',
        delete_url: 'notify/delete',
        export_url: 'notify/export',
        modify_url: 'notify/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                searchCols:[[
                    {field: 'title', title: '公告标题'},
                    {field: 'creator_id', title:'创建者'},
                    {field: 'type', search: 'select', selectList: {"1":"通知","2":"公告"}, title: '公告类型'},

                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 80, field: 'id', title: 'id'},
                    {minWidth: 80, field: 'title', title: '公告标题'},
                    {minWidth: 80, field: 'type', search: 'select', selectList: {"1":"通知","2":"公告"}, title: '公告类型'},
                    {minWidth: 80, field: 'is_show', search: 'select', selectList: ["禁用","启用"], title: '状态'},
                    {minWidth: 80, field: 'creator_id', title: '创建者'},
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