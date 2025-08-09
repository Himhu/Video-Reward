define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'adaccount/index',
        add_url: 'adaccount/add',
        edit_url: 'adaccount/edit',
        delete_url: 'adaccount/delete',
        export_url: 'adaccount/export',
        modify_url: 'adaccount/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh'],
                search :true,
                searchCols:[[
                    {field: 'uid', title: '代理ID'},
                    {field: 'money', title: '金额'},
                    {field: 'order_on', title: '订单号'},
                    {field: 'memo', title: '描述'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 80, field: 'uid', title: '代理ID'},
                    {
                        minWidth: 80,
                        field: 'username',
                        title: '代理名称',
                        templet:function(val){
                            console.log(val);
                            if(val.Admins == null){
                                return  "-";
                            }else{
                                return val.Admins.username;
                            }

                        }
                    },
                    {minWidth: 80, field: 'money', title: '金额'},
                    {minWidth: 80, field: 'memo', title: '描述',templet: ea.table.copy},
                    {minWidth: 80, field: 'order_on', title: '订单号'},
                    {minWidth: 80, field: 'create_time', title: '创建时间'},
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