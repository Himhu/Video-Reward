define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'link/index',
        add_url: 'link/add',
        edit_url: 'link/edit',
        delete_url: 'link/delete',
        export_url: 'link/export',
        modify_url: 'link/modify',
    };

    var field = [];
    var search = [];
    if(admin_id == 1)
    {
        //如果是admin 账号显示的字段
        field = [[
            {type: 'checkbox'},
            {field: 'id', title: 'id'},
           /* {
                field: 'cid',
                title: '类型ID',
                minWidth: 100,
                templet:function(val){
                    if(val.cid == null || val.cid == 0 || val.Category == null){
                        return  "-";
                    }else{
                        return  "("+ val.cid + ")" + val.Category.ctitle;
                    }
                }
            },*/
            {field: 'title', title: '名称', minWidth: 160},
            //{field: 'img', title: '图片'},
            {field: 'img', title: '图片', templet: ea.table.image,minWidth: 160},
            {field: 'video_url', title: '链接',minWidth: 160},
            {field: 'money', title: '价格'},
            {field: 'number', title: '打赏人数',minWidth: 160},
            {
                field: 'uid',
                title: '代理',
                templet:function(val){
                    if( val.Admins == null){
                        return  "-";
                    }else{
                        return  "("+ val.uid + ")" + val.Admins.username;
                    }
                }

            },
            {field: 'stock_id', title: '来自公共',minWidth: 100},
            //{field: 'try_see', title: '试看'},
            {field: 'create_time', title: '创建时间',minWidth: 170},
            {width: 250, title: '操作', templet: ea.table.tool,fixed:"right",height:67},
        ]];
        search = [[
            {field: 'title', title:'资源名称'},
            {field: 'video_url', title:'资源地址'},
            {field: 'money', title:'价格'},
            {field: 'uid', title:'代理'},
            {field: 'number', title: '打赏人数'},
            {field: 'stock_id', title:'来自公共'},
        ]];
    }
    else
    {
        field = [[
            {type: 'checkbox'},
            {field: 'id', title: 'id'},
            /*{
                field: 'cid',
                title: '类型ID',
                minWidth: 100,
                templet:function(val){
                    if(val.cid == null || val.cid == 0 || val.Category == null){
                        return  "-";
                    }else{
                        return  "("+ val.cid + ")" + val.Category.ctitle;
                    }
                }
            },*/
            {field: 'title', title: '资源名称', minWidth: 160},
            {field: 'img', title: '图片', templet: ea.table.image,minWidth: 160},
            {field: 'money', title: '价格'},
            {field: 'stock_id', title: '来自公共',minWidth: 100},
            {field: 'create_time', title: '发布时间',minWidth: 170},
            {width: 250, title: '操作', templet: ea.table.tool,fixed:"right"},
        ]];
        search =  [[
            {field: 'title', title:'资源名称'},
            {field: 'money', title:'价格'},
            {field: 'stock_id', title:'来自公共'}
        ]];
    }
    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                done:function(res, curr, count){
                    $(".layui-table-main  tr").each(function (index ,val) {
                        $($(".layui-table-fixed .layui-table-body tbody tr")[index]).height($(val).height());
                    });
                },
                searchCols:search,
                cols: field,
                toolbar:['refresh','delete']
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