define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'outlayj/index',
        add_url: 'outlayj/add',
        edit_url: 'outlayj/edit',
        delete_url: 'outlayj/delete',
        export_url: 'outlayj/export',
        modify_url: 'outlayj/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh'],
                search :true,
                searchCols:[[
                    {field: 'uid', title:'代理ID'},
                    {field: 'money', title: '提现金额'},
                    {field: 'remark', title: '拒绝原因'},
                    {field: 'create_time', title: '创建时间',search:'range'},

                ]],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'ID'},
                    {field: 'uid', title: '代理'},
                    {
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
                    {field: 'money', title: '提现金额'},
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
                    {field: 'image', title: '收款码', templet: ea.table.image},
                    {field: 'create_time', title: '提现时间',minWidth: 170},
                    {field: 'refuse_time', title: '拒绝时间',minWidth: 170},
                    {field: 'remark', title: '拒绝原因', templet: ea.table.text},
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