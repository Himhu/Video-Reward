define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'muban/index',
        add_url: 'muban/add',
        edit_url: 'muban/edit',
        delete_url: 'muban/delete',
        export_url: 'muban/export',
        modify_url: 'muban/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                search :true,
                searchCols:[[
                    {field: 'title', title:'标题'},
                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 80, field: 'id', title: 'ID'},
                    {minWidth: 80, field: 'title', title: '标题'},
                    {minWidth: 80, field: 'muban', title: '模版标识'},
                    {minWidth: 80, field: 'status', search: 'select', selectList: ["禁用","正常"], title: '状态', templet: ea.table.switch},
                    {minWidth: 80, field: 'image', title: '封面图', templet: ea.table.image},
                    {minWidth: 80, field: 'desc', title: '描述'},
                    {
                        minWidth: 80,
                        field: 'uid',
                        title: '创建人',
                        templet:function(val){
                            console.log(val);
                            if(val.uid == null || val.uid == 0){
                                return  "-";
                            }else{
                                return  "("+ val.uid + ")" + val.Admins.username;
                            }
                        }
                    },
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