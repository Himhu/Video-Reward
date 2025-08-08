define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'paylist/index',
        add_url: 'paylist/add',
        edit_url: 'paylist/edit',
        delete_url: 'paylist/delete',
        export_url: 'paylist/export',
        modify_url: 'paylist/modify',
    };

    var Controller = {

        index: function () {
            ea.table.render({
                init: init,
                toolbar:['refresh'],
                search :true,
                searchCols:[[
                    {field: 'vid', title: '资源ID'},
                    {field: 'vtitle', title: '资源名称'},
                    {field: 'price', title: '打赏金额'},
                    {field: 'transact', title: '订单号'},
                    {field: 'pay_channel',search: 'select', title: '通道统计',selectList: JSON.parse(pays),},
                ]],
                cols: [[

                    {type: 'checkbox'},
                    {Width: 80, field: 'vid', title: '资源ID'},
                    {minWidth: 80, field: 'vtitle', title: '资源名称'},
                   /* {
                        field: '',
                        title: '资源名称',
                        templet:function(val){
                            if(val.link != null)
                            {
                                return  val.Link.title;
                            }

                            return "-"
                        }

                    },*/
                    {minWidth: 80, field: 'price', title: '打赏金额'},
                    {field: 'transact', title: '订单号',minWidth: 190},
                    {field: 'createtime', title: '下单时间', minWidth: 160,},

                ]],
            });

            layui.table.on('tool(currentTableRenderId_LayFilter)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                console.log("asdasd");
                var data = obj.data; //获得当前行数据
                var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
                // 异常不要用它原来的这个作为tr的dom
                // var tr = obj.tr; //获得当前行 tr 的DOM对象
                var $this = $(this);
                var tr = $this.parents('tr');
                console.log(data);
                var trIndex = tr.data('index');
                if (layEvent === 'addRowTable') {
                    // 外围的table的id + tableIn_ + 当前的tr的data-index
                    $(this).attr('lay-event', 'fold').html('-');
                    var tableId = 'tableOut_tableIn_' + trIndex;
                    var _html = [
                        '<tr class="table-item">',
                        '<td colspan="' + tr.find('td').length + '" style="padding: 6px 12px;">',
                        '<table class="layui-table" id="' + tableId + '"></table>',//可以嵌套表格也可以是其他内容，如是其他内容则无须渲染该表格
                        '</td>',
                        '</tr>'
                    ];
                    tr.after(_html.join('\n'));
                    // 渲染table

                    $.getJSON('/payorder/index',$.param({'uid':data.Admins.id,orderCount:1}),function(e){
                        if(e.code == 0)
                        {
                            layui.table.render({
                                elem: '#' + tableId,
                                data: e.data || [],
                                cols: [[
                                    {field: 'dayOrderCount', title: '今日订单笔数', align: 'center',},
                                    {field: 'dayOrderMoney', title: '今日订单总金额', align: 'center',},
                                    {field: 'yesterdayOrderCount', title: '昨日订单笔数', align: 'center',},
                                    {field: 'yesterdayOrderMoney', title: '昨日订单总金额', align: 'center',},
                                    {field: 'OrderCount', title: '历史总订单笔数', align: 'center',},
                                    {field: 'OrderMoney', title: '历史总订单金额', align: 'center',},
                                ]],

                            });
                        }

                    });

                    // $(window).resize();

                } else if (layEvent === 'fold') {
                    $(this).attr('lay-event', 'addRowTable').html('+');
                    tr.next().remove();
                }
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