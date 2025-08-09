define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'category/index',
        add_url: 'category/add',
        edit_url: 'category/edit',
        delete_url: 'category/delete',
        export_url: 'category/export',
        modify_url: 'category/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                searchCols:[[
                    {field: 'ctitle', title:'分类名称'},

                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 80, field: 'id', title: 'ID'},
                    {minWidth: 80, field: 'ctitle', title: '分类名称'},
                    {minWidth: 80, field: 'image', title: '分类图片', templet: ea.table.image},
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