define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'number/index',
        add_url: 'number/add',
        edit_url: 'number/edit',
        delete_url: 'number/delete',
        export_url: 'number/export',
        modify_url: 'number/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                searchCols:[[
                    {field: 'number', title: '邀请码'},
                    {
                        field: 'uid',
                        title: '代理ID',
                    },
                    {field: 'ua', title: '激活人ID'},
                    {field: 'status', search: 'select', selectList: ["未激活","已激活"], title: '状态', templet: ea.table.switch},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', width: 80, title: 'id'},
                    {field: 'number', minWidth: 80, title: '邀请码',minWidth: 280,templet: ea.table.copy},
                    {
                        field: 'uid',
                        minWidth: 170,
                        title: '代理',
                        templet:function(val){
                            if(val.Admin == null || val.Admin == 0){
                                return  "-";
                            }else{

                                return  "("+ val.uid + ")" + val.Admin.username;
                            }
                        }

                    },

                    {
                        field: 'ua',minWidth: 170,
                        title: '激活人',
                        templet:function(val){
                            if(val.ua == null || val.ua == 0 || val.AdminUa == null){
                                return  "-";
                            }else{

                                return  "("+ val.ua + ")" + val.AdminUa.username;
                            }
                        }
                    },
                    //{field: 'status', search: 'select', selectList: ["未激活","已激活"], title: '状态', templet: ea.table.switch},
                    {
                        field: 'status',minWidth: 80,
                        search: 'select', selectList: ["未使用","已使用"],
                        title: '状态',
                        templet:function(val){
                            if(val.status == 1)
                            {
                                return "<span class=\"layui-badge layui-bg-blue\">已使用</span>";
                            }else{
                                return "<span class=\"layui-badge layui-bg-green\">未使用</span>";
                            }
                        }

                    },
                    {field: 'create_time', minWidth: 80, title: '生成时间',minWidth: 170},
                    {field: 'activate_time', minWidth: 80, title: '激活时间',minWidth: 170},
                    {
                        field: 'revenue',minWidth: 80,
                        title: '收益',
                        templet:function(val){
                            if(val.AdminUa == null){
                                return  "0.00";
                            }else{
                                return  val.AdminUa.revenue;
                            }
                        }
                    },
                    {minWidth: 80, title: '操作',fixed:"right" ,templet: ea.table.tool,minWidth: 170},
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