define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.admin/index',
        add_url: 'system.admin/add',
        edit_url: 'system.admin/edit',
        delete_url: 'system.admin/delete',
        modify_url: 'system.admin/modify',
        export_url: 'system.admin/export',
        password_url: 'system.admin/password',
    };


    var short = $("#short").attr('value');
    var pay_lists = $("#pay_lists").attr('value');


    var Controller = {

        index: function () {



            ea.table.render({
                init: init,
                height: '912px',
                done:function(res, curr, count){
                    $(".layui-table-main  tr").each(function (index ,val) {
                        $($(".layui-table-fixed .layui-table-body tbody tr")[index]).height($(val).height());
                    });
                    $(".layui-table-fixed").find(".layui-table-cell:eq(0)") .css({
                        margin:"0 auto",
                        position:"relative",
                        top:"35%"
                    })
                },
                searchCols:[[
                    {field: 'id', title: '代理ID'},
                    {field: 'username', title: '账号'},
                    {field: 'remark', title: '备注'},
                    {field: 'qq', title: 'qq'},
                    {field: 'wechat_account', title: '微信'},
                    {field: 'auth_ids',search: 'select', title: '用户组',selectList: JSON.parse(authlist)},
                ]],
                cols: [

                    [ //标题栏
                        {type: "checkbox", rowspan: 2},
                        {align: 'center', title: '地址', colspan: 5},
                        {
                            field: 'short',
                            title: '短链接',
                            width: 180, rowspan: 2,
                            wonima:true,
                            selectList: $.parseJSON(short),
                            templet: ea.table.selects
                        },
                        {
                            field: 'pay_model',
                            title: '支付渠道',
                            width: 180,
                            rowspan: 2,
                            wonima:true,
                            selectList: $.parseJSON(pay_lists),
                            templet: ea.table.selects
                        },
                        {
                            field: 'is_ff',
                            minWidth: 80,
                            title: 'URL加密',
                            rowspan: 2,
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch

                        },
                 /*       {
                            field: 'is_zn',
                            minWidth: 80,
                            title: '站内',
                            rowspan: 2,
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch
                        },*/
                /*         {
                            field: 'is_zw',
                            minWidth: 80,
                            title: '站外',
                            rowspan: 2,
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch
                        },*/
                        {align: 'center', title: '业绩', colspan: 5},
                        {align: 'center', title: '费率', colspan: 2},
                        {align: 'center', title: '包天包月配置', colspan: 3},

                        {
                            field: 'view_id',
                            minWidth: 120,
                            title: '前端界面',
                            rowspan: 3,
                            templet:function(val){

                                if(val.views == null)
                                {
                                    return "-";
                                }

                                return val.views.title;
                            }
                        },


                        {
                            field: 'status',
                            title: '状态',
                            width: 85,
                            search: 'select',
                            rowspan: 2,
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch
                        },
                        {field: 'login_num', minWidth: 120, title: '登录次数',rowspan: 2},
                        {field: 'create_time', minWidth: 170, title: '注册时间', search: 'range',rowspan: 2},
                        {
                            width: 190,

                            rowspan: 2,
                            title: '操作',
                            fixed:"right",
                            templet: ea.table.tool,
                            operat: [
                                'edit',
                                [{
                                    text: '修改提现密码',
                                    url: init.password_url,
                                    method: 'open',
                                    auth: 'password',
                                    class: 'layui-btn layui-btn-normal layui-btn-xs',
                                }],
                                'delete'
                            ]
                        }

                    ],
                    [
                        {field: 'id', title: 'ID', width: 80, rowspan: 2}, //rowspan即纵向跨越的单元格数
                        {field: 'username', title: '账户', width: 80},
                        {field: 'pwd', title: '密码', width: 120},
                        {
                            field: 'pid',
                            title: '上级',
                            width: 80,
                            templet:function(val){
                                if(val.pid == 0)
                                {
                                    return "台主";
                                }
                              if(val.admins == null)
                              {
                                  return "-";
                              }

                              return val.admins.id + "("+val.admins.username+")";
                            }
                        },
                        {
                            field: 'remark',
                            title: '备注',
                            width: 80,
                        },

                        {field: 'is_zn', minWidth: 120,
                            title: '今日订单',
                            templet:function(val){
                                if(val.orders == null)
                                {
                                    return "0";
                                }

                                return val.orders.length;
                            }

                        },
                        {field: 'day_m', minWidth: 120,
                            title: '今日业绩',
                            templet:function(val){
                                return val.day_m.total + "<br>("+val.day_m.day_m+"+"+val.day_m.day_p+")";
                            }
                            

                        },
                        {field: 'yes_m', minWidth: 120,
                            title: '昨日业绩',
                            templet:function(val){
                                return val.yes_m.total + "<br>("+val.yes_m.yes_m+"+"+val.yes_m.yes_p+")";
                            }
                             

                        },
                        {field: 'balance', minWidth: 80, title: '余额'},
                        {field: 'outlay_sum', minWidth: 80, title: '已提现'},
                     /*   {
                            field: 'domain',
                            minWidth: 80,
                            title: '死域名',
                            templet:function(val){
                                if(val.domain == null)
                                {
                                    return "0";
                                }

                                return val.domain.length;
                            }
                        },*/


                        {
                            field: 'poundage',
                            minWidth: 120,
                            title: '提现费率',
                            templet:function(val){
                                return val.poundage+"%";
                            }
                        },
                        {
                            field: 'ticheng',
                            minWidth: 120,
                            title: '返佣费率',
                            templet:function(val){
                                return val.ticheng+"%";
                            }},


                        {
                            field: 'is_day',
                            minWidth: 80,
                            filter:"is_day",
                            title: '包天',
                            search: 'select',
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch
                        },
                        {
                            field: 'is_week',
                            minWidth: 80,
                            filter:"is_week",
                            title: '包周',
                            search: 'select',
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch
                        },
                        {
                            field: 'is_month',
                            minWidth: 80,
                            filter:"is_month",
                            title: '包月',
                            search: 'select',
                            selectList: {0: '禁用', 1: '启用'},
                            templet: ea.table.switch
                        },

                    ]
                ],
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        password: function () {
            ea.listen();
        }
    };
    return Controller;
});