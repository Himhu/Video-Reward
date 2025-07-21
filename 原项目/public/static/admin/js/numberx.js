define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'numberx/index',
        add_url: 'numberx/add',
        edit_url: 'numberx/edit',
        delete_url: 'numberx/delete',
        export_url: 'numberx/export',
        modify_url: 'numberx/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh',[
                    {
                        text: '购买邀请码',
                        title:"购买邀请码",
                        url: init.add_url,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                        auth: 'add',
                        extend: ' data-full="true"',
                    }
                ]],
                search :true,
                searchCols:[[
                    {field: 'number', title: '邀请码'},
                    {field: 'ua', title: '激活人'},
                    {
                        field: 'status',
                        search: 'select', selectList: ["未激活","已激活"],
                        title: '状态',
                        templet: ea.table.switch
                    },
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {field: 'id', title: 'id'},
                    {field: 'number', title: '邀请码',minWidth: 280},
                    {field: 'create_time', title: '生成时间',minWidth: 170},

                    //{field: 'status', search: 'select', selectList: ["未激活","已激活"], title: '状态', templet: ea.table.switch},
                    {
                        field: 'status',
                        search: 'select', selectList: ["未使用","已使用"],
                        title: '状态',
                        templet:function(val){
                            console.log(val);
                            if(val.status == 1)
                            {
                                return "<span class=\"layui-badge layui-bg-blue\">已使用</span>";
                            }else{
                                return "<span class=\"layui-badge layui-bg-green\">未使用</span>";
                            }
                        }

                    },
                    {
                        field: 'ua',
                        title: '激活人',
                        templet:function(val){
                            if(val.ua == null || val.ua == 0){
                                return  "-";
                            }else{

                                if(val.AdminUa == null || val.AdminUa == 0 || val.AdminUa.hasOwnProperty("username") == false)
                                {
                                    return "-";
                                }
                            }
                            
                           return  "("+ val.ua + ")" + val.AdminUa.username;
                        },
                        width:100
                    },
                    {field: 'day_m', title: '今日销售',minWidth: 100},
                    {field: 'yes_m', title: '昨日销售',minWidth: 100},
                    
                    {
                        field: 'revenue',
                        title: '收益',
                        templet:function(val){
                            if(val.AdminUa == null){
                                return  "0.00";
                            }else{
                                return  val.AdminUa.revenue;
                            }
                        }
                    },
                    {field: 'activate_time', title: '激活时间',minWidth: 170},

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