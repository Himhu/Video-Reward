<?php /*a:2:{s:60:"/www/wwwroot/110.42.64.249/app/admin/view/index/welcome.html";i:1656847384;s:61:"/www/wwwroot/110.42.64.249/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo sysconfig('site','site_name'); ?></title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/static/admin/css/public.css?v=<?php echo htmlentities($version); ?>" media="all">
    <script>
        window.CONFIG = {
            ADMIN: "<?php echo htmlentities((isset($adminModuleName) && ($adminModuleName !== '')?$adminModuleName:'admin')); ?>",
            CONTROLLER_JS_PATH: "<?php echo htmlentities((isset($thisControllerJsPath) && ($thisControllerJsPath !== '')?$thisControllerJsPath:'')); ?>",
            ACTION: "<?php echo htmlentities((isset($thisAction) && ($thisAction !== '')?$thisAction:'')); ?>",
            AUTOLOAD_JS: "<?php echo htmlentities((isset($autoloadJs) && ($autoloadJs !== '')?$autoloadJs:'false')); ?>",
            IS_SUPER_ADMIN: "<?php echo htmlentities((isset($isSuperAdmin) && ($isSuperAdmin !== '')?$isSuperAdmin:'false')); ?>",
            VERSION: "<?php echo htmlentities((isset($version) && ($version !== '')?$version:'1.0.0')); ?>",
        };

        var admin_id = "<?php echo htmlentities($admin_id); ?>";
    </script>
    <script src="/static/plugs/layui-v2.5.6/layui.all.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/plugs/require-2.3.6/require.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
    <script src="/static/config-admin.js?v=<?php echo htmlentities($version); ?>" charset="utf-8"></script>
</head>
<body>
<style>

    .t_content img{
        width: 100%;
    }
</style>
<link rel="stylesheet" href="/static/admin/css/welcome.css?v=<?php echo time(); ?>" media="all">
<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-warning icon"></i>数据统计 <span onclick="window.location.reload()" class="label  layui-bg-cyan" style="cursor: pointer">刷新</span></div>
                            <div class="layui-card-body">


                                <div class="welcome-module"  >
                                    <div class="layui-row layui-col-space12">
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12 ">
                                            <?php if($admin_id == 1):?>

                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <h5>待处理提现金额(元)</h5>
                                                    </div>
                                                    <div class="panel-content" style="position: relative">
                                                        <h1 class="no-margins" style="">
                                                            <?php echo $dpayMonet?></h1>
                                                        <a data-title="未结算列表" layuimini-content-href="/admin/outlayw/index.html" class="layui-btn layui-btn-lg layui-btn-sm" style="display: inline-block;position: absolute;right: 0;bottom: 35%;">管理</a>
                                                        <small> 提现审批  &nbsp;</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php else:?>

                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <h5>可提现余额(元)</h5>
                                                    </div>
                                                    <div class="panel-content" style="position: relative">
                                                        <h1 class="no-margins" style="color: #FFD700">
                                                            <?php echo $home_total['yy']?></h1>
                                                        <button class="tixianc layui-btn layui-btn-lg layui-btn-sm" style="display: inline-block;position: absolute;right: 0;bottom: 35%;">提现</button>
                                                        <small>已扣除手续费</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif;?>
                                        </div>
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-cyan">实时</span>
                                                        <h5>今日访问统计</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['fangwen']?></h1>
                                                        <small>新访客</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-orange">实时</span>
                                                        <h5>订单统计</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['orderTotal']?></h1>
                                                        <small>总订单</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-green">实时</span>
                                                        <h5>金额统计</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['money']?></h1>
                                                        <small>总金额</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="welcome-module"  >
                                    <div class="layui-row layui-col-space12">
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12 ">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-blue">实时</span>
                                                        <h5>今日打赏金额</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['dayDsMoney']?></h1>
                                                        <small>新收入</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-cyan">实时</span>
                                                        <h5>今日打赏笔数</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['dayDsOrder']?></h1>
                                                        <small>新订单</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-orange">实时</span>
                                                        <h5>昨日打赏金额</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['yesDsMoney']?></h1>
                                                        <small>总收入</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="layui-col-lg3 layui-col-md3 layui-col-xs12">
                                            <div class="panel layui-bg-number">
                                                <div class="panel-body">
                                                    <div class="panel-title">
                                                        <span class="label pull-right layui-bg-green">实时</span>
                                                        <h5>昨日打赏笔数</h5>
                                                    </div>
                                                    <div class="panel-content">
                                                        <h1 class="no-margins"><?php echo $home_total['yesDsOrder']?></h1>
                                                        <small>总订单</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-md12">
                        <div class="layui-card">
                            <div class="layui-card-header"><i class="fa fa-line-chart icon"></i>报表统计</div>
                            <div class="layui-card-body">
                                <div id="echarts-records" style="width: 100%;min-height:500px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
  
        </div>
    </div>
</div>

<textarea id="contents" style="display: none;"><?php echo htmlspecialchars_decode($notify['content']); ?></textarea>
<textarea id="titless" style="display: none;"><?php echo htmlentities($notify['title']); ?></textarea>
<textarea id="noticeTime" style="display: none;"><?php echo htmlentities($notify['create_time']); ?></textarea>
<script>
    var xAxisData = <?php echo json_encode($xAxisData,1)?>;
    var seriesOrderData = <?php echo json_encode($seriesOrderData,1)?>;
    var hasSeriesOrderData = <?php echo json_encode($hasSeriesOrderData,1)?>;

</script>
</body>
</html>