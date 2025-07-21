<?php /*a:2:{s:61:"/www/wwwroot/110.42.64.249/app/admin/view/paysetting/add.html";i:1654397836;s:61:"/www/wwwroot/110.42.64.249/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
            <label class="layui-form-label">支付名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input" lay-verify="required" placeholder="请输入支付名称" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付ID</label>
            <div class="layui-input-block">
                <input type="text" name="app_id" class="layui-input" lay-verify="required" placeholder="请输入支付ID" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付秘钥</label>
            <div class="layui-input-block">
                <input type="text" name="app_key" class="layui-input" lay-verify="required" placeholder="请输入支付秘钥" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付网关</label>
            <div class="layui-input-block">
                <input type="text" name="pay_url" class="layui-input" lay-verify="required" placeholder="请输入支付网关" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">支付渠道</label>
            <div class="layui-input-block">
                <input type="text" name="pay_channel" class="layui-input" lay-verify="required" placeholder="请输入执行方法" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">执行标识</label>
            <div class="layui-input-block">
                <input type="text" name="pay_model" class="layui-input" lay-verify="required" placeholder="请输入执行标识" value="">
            </div>
        </div>
        
        
        <div class="layui-form-item">
            <label class="layui-form-label">浮动费率</label>
            <div class="layui-input-block">
                <input type="number" name="pay_fudong" class="layui-input" lay-verify="required" placeholder="请输入浮动费率" value="">
            </div>
        </div>
        
        
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <?php foreach($getStatusList as $k=>$v): ?>
                <input type="radio" name="status" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), explode(',',"0"))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
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