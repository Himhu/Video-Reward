define(["jquery", "easy-admin", "echarts", "echarts-theme", "miniAdmin", "miniTab"], function ($, ea, echarts, undefined, miniAdmin, miniTab) {

    var Controller = {
        index: function () {
            var options = {
                iniUrl: ea.url('ajax/initAdmin'),    // 初始化接口
                clearUrl: ea.url("ajax/clearCache"), // 缓存清理接口
                urlHashLocation: true,      // 是否打开hash定位
                bgColorDefault: false,      // 主题默认配置
                multiModule: false,          // 是否开启多模块
                menuChildOpen: false,       // 是否默认展开菜单
                loadingTime: 0,             // 初始化加载时间
                pageAnim: true,             // iframe窗口动画
                maxTabNum: 50,              // 最大的tab打开数量
            };
            miniAdmin.render(options);

            $('.login-out').on("click", function () {
                ea.request.get({
                    url: 'login/out',
                    prefix: true,
                }, function (res) {
                    ea.msg.success(res.msg, function () {
                        window.location = ea.url('login/index');
                    })
                });
            });
        },
        welcome: function () {

            miniTab.listen();

            /**
             * 查看公告信息
             **/
            if(admin_id != 1)
            {

               
                
                setTimeout(function () {

                    var content = $(window.frames.document).find("#contents").val();
                    var title = $(window.frames.document).find("#titless").val();
                    var noticeTime = $(window.frames.document).find("#noticeTime").val();
                    var html = '<div class="t_content" style="padding:15px 20px; text-align:justify; line-height: 22px;border-bottom:1px solid #e2e2e2;background-color: #2f4056;color: #ffffff">\n' +
                        '<div style="text-align: center;margin-bottom: 20px;font-weight: bold;border-bottom:1px solid #718fb5;padding-bottom: 5px"><h4 class="text-danger">' + title + '</h4></div>\n' +
                        '<div style="font-size: 12px">' + content + '</div>\n' +
                        '</div>\n';

                    layer.open({
                        type: 1,
                        title: '通知公告' + '<span style="float: right;right: 1px;font-size: 12px;color: #b1b3b9;margin-top: 1px">' + noticeTime + '</span>',
                        area: ['380px'],
                        //shade: 0.8,
                        //id: 'layuimini-notice',
                        //btn: ['查看', '取消'],
                        //btnAlign: 'c',
                        moveType: 1,
                        content: html,

                    });


                    // 提现按钮事件已在模板中处理，此处删除重复绑定避免冲突
                    // 原代码已移除，避免与welcome.html模板中的事件处理器冲突
                    
                    
                },1000)

            }


          



            /**
             * 报表功能
             */
            var echartsRecords = echarts.init(document.getElementById('echarts-records'), 'walden');
            var optionRecords = {
                title: {
                    text: '访问统计'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['订单数', '待付款']
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: xAxisData
                },
                yAxis: {
                    type: 'value'
                },
                series: [
                    {
                        name: '订单数',
                        type: 'line',
                        stack: '总量',
                        data: hasSeriesOrderData
                    },
                    {
                        name: '待付款',
                        type: 'line',
                        stack: '总量',
                        data: seriesOrderData
                    }
                ]
            };
            echartsRecords.setOption(optionRecords);
            window.addEventListener("resize", function () {
                echartsRecords.resize();
            });
        },
        editAdmin: function () {
            ea.listen();
        },
        editPassword: function () {
            ea.listen();
        }
    };
    return Controller;
});
