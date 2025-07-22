define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'domainlib/index',
        add_url: 'domainlib/add',
        edit_url: 'domainlib/edit',
        delete_url: 'domainlib/delete',
        export_url: 'domainlib/export',
        modify_url: 'domainlib/modify',
        recycling: 'domainlib/recycling'
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                toolbar:['refresh', 'delete','recycling'],
                searchCols:[[
                    {field: 'uid', title:'代理'},
                    {field: 'domain', title: '主域名'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 80, field: 'id', title: '域名ID'},
                    {
                        minWidth: 80,
                        field: 'uid',
                        title: '代理ID',
                        templet:function(val){
                            console.log(val);
                            if(val.Admins == null)
                            {
                                return "-";
                            }
                            //return "<span class=\"layui-badge layui-bg-orange\">正常</span>";
                            return  "("+ val.uid + ")" + val.Admins.username;
                        }
                    },
                    {minWidth: 80, field: 'domain', title: '中转域名',templet: ea.table.copy},
                    //{field: 'status', search: 'select', selectList: ["禁用","正常"], title: '状态', templet: ea.table.switch},
                    {
                        minWidth: 80,
                        field: 'status',
                        search: 'select', selectList: ["禁用","正常"],
                        title: '微信状态',
                        templet:function(val){
                            console.log(val);
                            if(val.status == 1)
                            {
                                return "<span class=\"layui-badge layui-bg-green\">正常</span>";
                            }else{
                                return "<span class=\"layui-badge layui-bg-red\">禁用</span>";
                            }
                        }

                    },
                    
                    {
                        minWidth: 80,
                        field: 'q_status',
                        search: 'select', selectList: ["禁用","正常"],
                        title: 'QQ状态',
                        templet:function(val){
                            console.log(val);
                            if(val.q_status == 1)
                            {
                                return "<span class=\"layui-badge layui-bg-green\">正常</span>";
                            }else{
                                return "<span class=\"layui-badge layui-bg-red\">禁用</span>";
                            }
                        }

                    },
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