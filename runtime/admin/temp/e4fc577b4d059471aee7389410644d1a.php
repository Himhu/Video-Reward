<?php /*a:2:{s:66:"/www/wwwroot/110.42.64.249/app/admin/view/index/edit_password.html";i:1652626575;s:61:"/www/wwwroot/110.42.64.249/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
    <div class="layuimini-main">

        <form id="app-form" class="layui-form layuimini-form">

            <div class="layui-form-item">
                <label class="layui-form-label required">登录账户</label>
                <div class="layui-input-block">
                    <input type="text" name="username" class="layui-input" readonly value="<?php echo htmlentities((isset($row['username']) && ($row['username'] !== '')?$row['username']:'')); ?>">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">登录密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" class="layui-input"    lay-reqtext="请输入新的登录密码" placeholder="请输入新的登录密码" value="">
                    <tip>修改登录密码。(留空不修改)</tip>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">确认新密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password_again" class="layui-input"    lay-reqtext="请再次新登录密码" placeholder="请再次新登录密码" value="">
                    <tip>再次填写新的登录密码。</tip>
                </div>
            </div>


            <div class="layui-form-item">
                <label class="layui-form-label">提现密码</label>
                <div class="layui-input-block">
                    <input type="password" name="txpwd" class="layui-input"    lay-reqtext="请输入提现密码" placeholder="请输入提现密码" value="">
                    <tip class="">填写提现密码。(留空不修改)</tip>
                </div>
            </div>
            
            
            <div class="layui-form-item">
                <label class="layui-form-label">旧提现密码</label>
                <div class="layui-input-block">
                    <input type="password" name="old_pwd" class="layui-input"    lay-reqtext="请先输入正在使用的提现密码（未设置过请留空）" placeholder="请先输入正在使用的提现密码（未设置过请留空）" value="">
                    <tip class="">请先输入正在使用的提现密码</tip>
                </div>
            </div>

            <div class="hr-line"></div>
            <div class="layui-form-item text-center">
                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
                <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
            </div>

        </form>
 
    </div>
</div>
</body>
</html>