define(["jquery", "easy-admin"], function ($, ea) {

    let p = '';
    if(location.search == "?d=dsp")
    {
        p = '?d=dsp';
    }
    console.log(location.search)
    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'stock/index'+p,
        add_url: 'stock/add'+p,
        edit_url: 'stock/edit',
        delete_url: 'stock/delete',
        export_url: 'stock/export',
        modify_url: 'stock/modify',
        import_url:'stock/import'+p,
        piliang_url:'stock/piliang',
        push_all_url:'stock/push_all',
    };

    var search = [];
    if(admin_id == 1)
    {
        search = [[
            {field: 'title', title:'资源名称'},
            {field: 'url', title:'资源地址'},
            {field: 'number', title: '打赏人数'},

        ]];
    }
    else
    {
        search = [[
            {field: 'id', title:'资源ID'},
            {field: 'title', title:'资源名称'},
            {field: 'number', title: '打赏人数'},

        ]];
    }

    var field = null;
    if(admin_id == 1)
    {
        field = [[
            {type: 'checkbox'},
            {field: 'id', title: '资源ID'},
            {field: 'title', title: '资源名称'},
          //  {field: 'url', title: '资源链接'},
            {field: 'image', title: '视频图片', templet: ea.table.image,minWidth: 100},
            {field: 'time', title: '时长',minWidth: 100},
            {field: 'number', title: '打赏人数'},
            {field: 'create_time', title: '创建时间',minWidth: 170},
            {width: 250, title: '操作', templet: ea.table.tool,fixed:"right"},
        ]];
    }
    else
    {
        field = [[
            {type: 'checkbox'},
            {field: 'id', title: '资源ID'},
            {field: 'title', title: '资源名称'},
         //   {field: 'url', title: '资源链接',templet: ea.table.copy},
            {field: 'image', title: '视频图片', templet: ea.table.image,minWidth: 100},
            {field: 'time', title: '时长',minWidth: 100},
            {field: 'number', title: '打赏人数'},
            {field: 'create_time', title: '创建时间',minWidth: 170},
            // {
            //     field: 'is_push',
            //     title: '操作',
            //     minWidth: 170,
            //     templet:function(val){
            //         if(val.Links != null)
            //         {
            //             return "<button type=\"button\" class=\"layui-btn layui-btn-xs layui-btn-warm\">已发布</button>";
            //         }
            //         return "<button type=\"button\" class=\"layui-btn layui-btn-xs layui-btn-danger\">未发布</button>";
            //     }
            // },

        ]];
    }

    var toolbar = ['refresh', 'add', 'delete','piliang','push_all'];
    if(admin_id == 1)
    {
         toolbar = ['refresh', 'add', 'delete','import'];

    }
    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:toolbar,
                done:function(res, curr, count){
                    $(".layui-table-main  tr").each(function (index ,val) {
                        $($(".layui-table-fixed .layui-table-body tbody tr")[index]).height($(val).height());
                    });
                },
                searchCols:search,
                cols: field,
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        import: function () {


            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
    };
    return Controller;
});