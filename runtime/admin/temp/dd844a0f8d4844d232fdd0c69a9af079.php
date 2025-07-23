<?php /*a:2:{s:54:"/www/wwwroot/vdsds.live/app/admin/view/hezi/index.html";i:1652617850;s:58:"/www/wwwroot/vdsds.live/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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


    .layui-tab-item {
        padding-top: 5px;
    }

    .layui-tab-content {
        padding: 0;
    }

    .card-item {
        padding: 10px;
        transition: all .3s;
    }
    .card-item:hover{
        box-shadow:3px 6px 11px 0 #5fb878;
        transform: translate(-3px,-3px);
        transition: all .3s;
    }

    .header-items {
        display: inline-block;
        width: 32%;
    }

    .footer-items {
        display: inline-block;
        width: 49%;
        cursor: pointer;
    }

    .footer-items:nth-child(1) {
        border-right: 1px solid #f6f6f6;
    }

    .layui-card-footer {
        position: relative;
        height: 35px;
        line-height: 35px;
        padding: 0 15px;
        border-top: 1px solid #f6f6f6;
        color: #333;
        border-radius: 2px 2px 0 0;
        font-size: 14px;
    }

    .layui-card-header {
        position: relative;
        height: 35px;
        line-height: 35px;
        padding: 0 15px;
        border-bottom: 1px solid #f6f6f6;
        color: #333;
        border-radius: 2px 2px 0 0;
        font-size: 14px;
    }

    .body-item {
        text-align: center;
        display: inline-block;
        width: 100%;
        height: 25px;
        line-height: 25px;
    }
    .delete-items{
        cursor: pointer;
    }

    .item-url {
        color: #5FB878;
        font-weight: bold;
        font-size: 20px;
        width: 77%;
        margin-left: 15%;
        cursor: pointer;
    }
    .item-url:hover{
        text-decoration: underline;
        color: #393D49;
    }

    .layui-card {
        box-shadow: 0 1px 14px 0 rgb(0 0 0 / 5%);
    }

    @media screen and (max-width: 420px) {
        .header-items:nth-child(1) {
            width: 45%;
        }

        .header-items:nth-child(2) {
            width: 40%;
        }

        .header-items:nth-child(3) {
            width: 5%;
        }
    }


</style>


<div class="layuimini-container">
    <div class="layuimini-main">


        <blockquote class="layui-elem-quote"  style="margin-bottom: 15px;">
            推广链接说明:
            <br>
            全新推广模式
            <br>
            建议使用独享域名进行分流推广
            <br>
            全新模式数百营销互动玩法，实现引流拓客。
            <br>
            【推广链接】生成简约款推广链接【短视频链接】生成短视频裂变版链接
        </blockquote>


        <div class="layui-tab layui-tab-brief " lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this">推广链接</li>
                <li>短视频链接</li>

            </ul>
            <div class="layui-tab-content" style="height: 100%;">
                <div class="layui-tab-item layui-show" style="background-color: #f2f2f2">
                    <div class="layui-row">
                        <button type="button" data-active_index="1" class="layui-btn layui-btn-sm addpush layui-btn-normal">生成推广链接</button>
                        <button type="button" data-active_index="1" class="layui-btn layui-btn-sm buydomain layui-btn-warm">购买分流域名</button>
                    </div>
                    <div class="layui-row card-box box-1" style="margin-top: 10px;"></div>
                </div>
                <div class="layui-tab-item " style="background-color: #f2f2f2">
                    <div class="layui-row">
                        <button type="button" data-active_index="2" class="layui-btn layui-btn-sm addpush layui-btn-normal">生成推广链接</button>
                        <button type="button" data-active_index="2" class="layui-btn layui-btn-sm buydomain layui-btn-warm">购买分流域名</button>
                    </div>
                    <div class="layui-row card-box box-2" style="margin-top: 10px;"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>

    var  value = "<?php echo htmlentities($config['value']); ?>";
    var  ff_url = "<?php echo htmlentities($ff_url['value']); ?>";
</script>

</body>
</html>