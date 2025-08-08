define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'auaccount/index',
        add_url: 'auaccount/add',
        edit_url: 'auaccount/edit',
        delete_url: 'auaccount/delete',
        export_url: 'auaccount/export',
        modify_url: 'auaccount/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh'],
                search :true,
                searchCols:[[
                    {field: 'money', title: '打赏金额'},
                    {field: 'order_on', title: '订单号'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {Width: 80, field: 'money', title: '金额'},
                    {
                        minWidth: 80,
                        field: 'memo',
                        title: '标题',
                        templet:function(val){

                            if(val.Admins == null)
                            {
                                return val.simple;
                            }
                            if(val.Admins.pid == 0)
                            {
                                return val.memo;
                            }

                            return val.simple;
                        }
                    },
                    {minWidth: 190, field: 'order_on', title: '订单号'},
                    {minWidth: 180, field: 'create_time', title: '创建时间'},

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