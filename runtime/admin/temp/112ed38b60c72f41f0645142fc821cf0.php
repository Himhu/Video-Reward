<?php /*a:2:{s:62:"/www/wwwroot/vdsds.live/app/admin/view/system/admin/index.html";i:1654743946;s:58:"/www/wwwroot/vdsds.live/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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


    .layui-table-cell {
        overflow: visible !important;
    }
    .layui-table-fixed tr td{
        height: 48.4px;
    }

</style>
<div class="layuimini-container">
    <div class="layuimini-main">

        <fieldset id="" class="table-search-fieldset " style="margin-bottom: 1%">
            <legend>数据统计</legend>
            <table class="table layui-table " >
                <thead>
                <tr>
                    <th style="text-align: center;">今日总余额</th>
                    <th style="text-align: center;">今日总提现</th>

                </tr>
                </thead>
                <tbody>
                <tr class="text-center">
                    <td><?php echo htmlentities($total_balance); ?></td>
                    <td><?php echo htmlentities($total_tx); ?></td>

                </tr>
                </tbody>
            </table>
        </fieldset>


        <table id="currentTable" class="layui-table layui-hide"
               data-auth-add="<?php echo auth('system.admin/add'); ?>"
               data-auth-edit="<?php echo auth('system.admin/edit'); ?>"
               data-auth-delete="<?php echo auth('system.admin/delete'); ?>"
               data-auth-password="<?php echo auth('system.admin/password'); ?>"
               lay-filter="currentTable">
        </table>
    </div>
</div>

<script>
    
    var authlist = '<?php echo json_encode($auth_list, 256);?>';
    
</script>

<input type="hidden" id="short" value='<?php echo json_encode($short , 1)?>'>
<input type="hidden" id="pay_lists" value='<?php echo json_encode($pay_lists , 1)?>'>

</body>
</html>