define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'complain/index',
        add_url: 'complain/add',
        edit_url: 'complain/edit',
        delete_url: 'complain/delete',
        export_url: 'complain/export',
        modify_url: 'complain/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['delete',[
                    {
                        text: '全部清空',
                        title:"确认全部清空?",
                        url: init.delete_url,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        method:"request",
                        auth: 'delete',
                    }
                ]],
                search :true,
                searchCols:[[
                    {field: 'ua', title:'链接ID'},
                    {field: 'remark', title:'内容'},
                    {field: 'type', search: 'select', selectList: ["未知","新冠肺炎疫情相关","欺诈","色情","诱导行为","不实信息","犯法犯罪","禁止访问","骚扰","抄袭/洗稿、滥用原创","其它","侵权(冒充他人、侵犯名誉等)"], title: '分类'}

                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 80, field: 'id', title: 'ID'},
                    {minWidth: 80, field: 'ua', title: '链接ID',templet: ea.table.copy},
                    //{field: 'status', search: 'select', selectList: ["禁用","正常"], title: '状态', templet: ea.table.switch},
                    {
                        minWidth: 80,
                        field: 'status',
                        title: '状态',
                        templet:function(val){
                           if(val.status == 0)
                           {
                               return "<button class=\"layui-btn layui-btn-danger layui-btn-radius layui-btn-xs\">屏蔽/待处理</button>";
                           }
                            return "<button class=\"layui-btn layui-btn-xs  layui-btn-radius layui-btn-normal\">正常</button>";

                        }},
                    //{minWidth: 80, field: 'remark', title: '内容'},
                    {minWidth: 80, field: 'type', search: 'select', selectList: ["未知","新冠肺炎疫情相关","欺诈","色情","诱导行为","不实信息","犯法犯罪","禁止访问","骚扰","抄袭/洗稿、滥用原创","其它","侵权(冒充他人、侵犯名誉等)"], title: '分类'},
                    {minWidth: 80, field: 'create_time', title: '创建时间'},
                    {
                        minWidth: 80,
                        title: '操作',
                        templet: ea.table.tool,
                        operat: [
                           /* [{
                                text: '编辑',
                                url: init.edit_url,
                                method: 'open',
                                auth: 'edit',
                                class: 'layui-btn layui-btn-xs layui-btn-success',
                                extend: 'data-full="true"',
                            }, {
                                text: '入库',
                                url: init.stock_url,
                                method: 'open',
                                auth: 'stock',
                                class: 'layui-btn layui-btn-xs layui-btn-normal',
                            }],*/
                            'delete']
                    }
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