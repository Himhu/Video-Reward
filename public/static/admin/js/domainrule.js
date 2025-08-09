define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'domainrule/index',
        add_url: 'domainrule/add',
        edit_url: 'domainrule/edit',
        delete_url: 'domainrule/delete',
        export_url: 'domainrule/export',
        modify_url: 'domainrule/modify',
        recycling: 'domainrule/recycling',
        piliang: 'domainrule/piliang'
    };

    var Controller = {

        //回收 recycling
        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh',  'delete',

 
                    
                    [
                        
                      {
                        text: '模式说明',
                        title:"待分配=代理后台可以购买这条域名（只能主域名）<br>公共域名=所有代理共用此条域名（建议炮灰设置公共）<br>把域名分配到代理名下代理将独享那条域名使用。<br>全分流模式：每个代理配置独立的主域名跟炮灰<br>cos模式：每个代理分配独立的cos域名、炮灰公共",
                        icon:'fa fa-question-circle',
                       // url: init.add_url,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        method:"tips",
                        //auth: 'delete',
                    }  , 
                        
                        
                        
                        
                        {
                        text: '添加域名',
                        title:"添加域名库",
                        url: init.add_url,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        icon: 'fa fa-plus ',
                        title: '添加',
                        auth: 'piliang',
                       // extend: ' data-full="true"',
                    },{
                        text: '自动换域名检测地址',
                        title:"自动换域名检测地址",
                        url: init.piliang,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                       // icon: 'fa fa-plus ',
                        title: '添加',
                        auth: 'piliang',
                        extend: ' data-full="false"',
                    }],
                ],
                search :true,
                searchCols:[[
                    {field: 'uid', title:'代理'},
                    {field: 'domain', title:'域名'},

                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 90, field: 'id', title: '域名ID'},
                    {
                        minWidth: 80,
                        field: 'uid',
                        title: '代理ID',
                        templet:function(val){
                            console.log(val);
                            if(val.uid == null || val.uid == 0){
                                return  "待分配";
                            }
                            else if(val.uid == 10086)
                            {
                                return '公共域名'
                            }
                            else
                            {
                                return  "("+ val.uid + ")" + val.Admins.username;
                            }
                        }
                    },
                    {minWidth: 80, field: 'domain', title: '域名',templet: ea.table.copy},
                    {minWidth: 80, field: 'type', title: '类型',templet: function(val){
                        if(val.type == "1")
                        {
                            return "<span class=\"layui-badge layui-bg-green\">主域名</span>"
                        }

                        if(val.type == "2")
                        {
                            return "<span class=\"layui-badge\">炮灰域名</span>"
                        }

                        }},
                    {minWidth: 80, field: 'status', search: 'select', tips:'正常|拦截',selectList: ["禁用","正常"], title: '状态', templet: ea.table.switch},
                    // {minWidth: 80, field: 'creator_id', title: '创建者'},
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