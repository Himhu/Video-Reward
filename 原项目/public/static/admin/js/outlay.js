define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'outlay/index',
        add_url: 'outlay/add',
        edit_url: 'outlay/edit',
        delete_url: 'outlay/delete',
        export_url: 'outlay/export',
        modify_url: 'outlay/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                searchCols:[[
                    {field: 'money', title: '提现金额'},
                    {field: 'create_time', title: '提现时间',search:'range'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'ID'},
                    {field: 'money', title: '提现金额',minWidth: 100},
                    {
                        field: 'status',
                        search: 'select', selectList: ["未支付","已支付","已拒绝"],
                        title: '状态',
                        templet:function(val){
                            console.log(val);
                            if(val.status == 1)
                            {
                                return "<span class=\"layui-badge layui-bg-blue\">已支付</span>";
                            }else if(val.status == 2){
                                return "<span class=\"layui-badge layui-bg-red\">已拒绝</span>";
                            }else{
                                return "<span class=\"layui-badge layui-bg-green\">未支付</span>";
                            }
                        }

                    },
                    {field: 'image', title: '收款码', templet: ea.table.image,minWidth: 100},
                    {field: 'create_time', title: '提现时间',minWidth: 170},
                    {field: 'end_time', title: '结算时间',minWidth: 170},
                    {field: 'remark', title: '拒绝原因', minWidth: 100},
                    {width: 250, title: '操作',fixed:'right', templet: ea.table.tool},
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