define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'outlayw/index',
        add_url: 'outlayw/add',
        edit_url: 'outlayw/edit',
        delete_url: 'outlayw/delete',
        export_url: 'outlayw/export',
        modify_url: 'outlayw/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh'],
                search :true,
                searchCols:[[
                    {field: 'uid', title: '代理ID'},
                    {field: 'money', title: '提现金额'},
                    {field: 'create_time', title: '创建时间',search:'range'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {Width: 80, field: 'id', title: 'ID'},
                    {minWidth: 80, field: 'uid', title: '代理'},
                    {
                        minWidth: 80,
                        field: 'username',
                        title: '代理名称',
                        templet:function(val){
                            console.log(val);
                            if(val.Admins == null){
                                return  "-";
                            }else{
                                return  val.Admins.username;
                            }
                        }
                    },
                    {minWidth: 80, field: 'money', title: '提现金额'},
                    {
                        minWidth: 80,
                        field: 'status',
                        search: 'select', selectList: ["待支付","已支付","已拒绝"],
                        title: '状态',
                        templet:function(val){
                            console.log(val);
                            if(val.status == 1)
                            {
                                return "<span class=\"layui-badge layui-bg-blue\">已支付</span>";
                            }else if(val.status == 2){
                                return "<span class=\"layui-badge layui-bg-red\">已拒绝</span>";
                            }else{
                                return "<span class=\"layui-badge layui-bg-green\">等待支付</span>";
                            }
                        }

                    },
                    {minWidth: 180, field: 'create_time', title: '提现时间'},
                    {minWidth: 80, field: 'image', title: '收款码', templet: ea.table.image},
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