<?php /*a:2:{s:64:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/number/edit.html";i:1652618776;s:67:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
<div class="layuimini-container">
    <form id="app-form" class="layui-form layuimini-form">
        
        <div class="layui-form-item">
            <label class="layui-form-label">邀请码</label>
            <div class="layui-input-block">
                <input type="text" name="number" class="layui-input" lay-verify="required" placeholder="请输入邀请码" value="<?php echo htmlentities((isset($row['number']) && ($row['number'] !== '')?$row['number']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">代理</label>
            <div class="layui-input-block">
                <input type="text" name="uid" class="layui-input" lay-verify="required" placeholder="请输入代理" value="<?php echo htmlentities((isset($row['uid']) && ($row['uid'] !== '')?$row['uid']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">激活人</label>
            <div class="layui-input-block">
                <input type="text" name="ua" class="layui-input"  placeholder="请输入激活人" value="<?php echo htmlentities((isset($row['ua']) && ($row['ua'] !== '')?$row['ua']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <?php foreach($getStatusList as $k=>$v): ?>
                <input type="radio" name="status" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), is_array($row['status'])?$row['status']:explode(',',$row['status']))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">激活时间</label>
            <div class="layui-input-block">
                <input type="text" name="activate_time" class="layui-input"  placeholder="请输入激活时间" value="<?php echo htmlentities((isset($row['activate_time']) && ($row['activate_time'] !== '')?$row['activate_time']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">代理收益</label>
            <div class="layui-input-block">
                <input type="text" name="earnings" class="layui-input"  placeholder="请输入收益" value="<?php echo htmlentities((isset($row['earnings']) && ($row['earnings'] !== '')?$row['earnings']:'')); ?>">
            </div>
        </div>
        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>
 
    </form>
</div>
</body>
</html>