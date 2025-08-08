define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'quantity/index',
        add_url: 'quantity/add',
        edit_url: 'quantity/edit',
        delete_url: 'quantity/delete',
        export_url: 'quantity/export',
        modify_url: 'quantity/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh',[
                    {
                        text: '模式说明',
                        title:"开启全局倒数=读取网站设置里的全局倒数值<br> 初值值10=第一次抽单为10笔订单后抽成1笔，之后读取倒数值的设置进行抽单，如倒数值为3就是接下来每隔3笔抽成1笔。<br>傻瓜版设置方法：直接倒数值、初始值都设置一样就是几抽几。",
                        icon:'fa fa-question-circle',
                       // url: init.add_url,
                        class: 'layui-btn layui-btn-normal layui-btn-sm',
                        method:"tips",
                        //auth: 'delete',
                    }
                ]],
                search :true,
                searchCols:[[
                    {field: 'uid', title:'代理'},

                ]],
                cols: [[
                    {type: 'checkbox'},
                    {width: 90, field: 'id', title: '抽单ID'},
                    //{minWidth: 80, field: 'uid', title: '代理ID'},
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
                            return  "("+ val.uid + ")" + val.Admins.username;
                        }
                    },
                    {minWidth: 80, field: 'initial', title: '初始值'},
                    {minWidth: 80, field: 'bottom', title: '倒数值'},
                    //{field: 'bottom_all', search: 'select', selectList: ["禁用","正常"], title: '全局倒数'},
                    {minWidth: 80, field: 'bottom_all', search: 'select', selectList: ["禁用","正常"], title: '全局倒数', templet: ea.table.switch},
                    {minWidth: 80, field: 'creator_id', title: '创建人'},
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