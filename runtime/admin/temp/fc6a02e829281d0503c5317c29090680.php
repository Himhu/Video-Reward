<?php /*a:2:{s:53:"/www/wwwroot/vdsds.live/app/admin/view/login/reg.html";i:1652618566;s:58:"/www/wwwroot/vdsds.live/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
        html, body {width: 100%;height: 100%;overflow: hidden}
        body {background: #1E9FFF;  background: url(<?php echo sysconfig('site','site_bg'); ?>) 0% 0% / cover no-repeat;}
        body:after {content:'';background-repeat:no-repeat;background-size:cover;-webkit-filter:blur(3px);-moz-filter:blur(3px);-o-filter:blur(3px);-ms-filter:blur(3px);filter:blur(3px);position:absolute;top:0;left:0;right:0;bottom:0;z-index:-1;}
        .layui-container {width: 100%;height: 100%;overflow: hidden}
        .admin-login-background {width:360px;height:300px;position:absolute;left:50%;top:40%;margin-left:-180px;margin-top:-100px;}
        .logo-title {text-align:center;letter-spacing:2px;padding:14px 0;}
        .logo-title h1 {color:#1E9FFF;font-size:25px;font-weight:bold;}
        .login-form {background-color:#fff;border:1px solid #fff;border-radius:3px;padding:14px 20px;box-shadow:0 0 8px #eeeeee;}
        .login-form .layui-form-item {position:relative;}
        .login-form .layui-form-item label {position:absolute;left:1px;top:1px;width:38px;line-height:36px;text-align:center;color:#d2d2d2;}
        .login-form .layui-form-item input {padding-left:36px;}
        .captcha {width:60%;display:inline-block;}
        .captcha-img {display:inline-block;width:34%;float:right;}
        .captcha-img img {height:34px;border:1px solid #e6e6e6;height:36px;width:100%;}

    </style>

<body>
<div class="layui-container">
    <div class="admin-login-background">
        <div class="layui-form login-form">
            <form class="layui-form" action="" id = 'databox'  method="post">
                <div class="layui-form-item logo-title">
                    <h1><?php echo sysconfig('site','site_name'); ?>-注册</h1>
                </div>

                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-share" for="ypm"></label>
                    <input type="text" name="ypm"  lay-verify="required" placeholder="邀请码" autocomplete="off" class="layui-input" value="">
                </div>

                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-username" for="username"></label>
                    <input type="text" name="username"  lay-verify="required|account" placeholder="用户名" autocomplete="off" class="layui-input" value="">
                </div>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-password" for="password"></label>
                    <input type="password" name="password"  lay-verify="required|password" placeholder="密码" autocomplete="off" class="layui-input" value="">
                </div>







                <?php if($captcha == 1): ?>
                <div class="layui-form-item">
                    <label class="layui-icon layui-icon-vercode" for="captcha"></label>
                    <input type="text" name="captcha"  lay-verify="required|captcha" placeholder="请输入验证码" autocomplete="off" class="layui-input verification captcha" value="">
                    <div class="captcha-img">
                        <img id="refreshCaptcha" class="validateImg"  src="<?php echo url('login/captcha'); ?>" onclick="this.src='<?php echo url('login/captcha'); ?>?seed='+Math.random()">
                    </div>
                </div>
                <?php endif; ?>

                <div class="layui-form-item">
                    <a href="javascript:;" class="layui-btn layui-btn layui-btn-normal layui-btn-fluid" lay-submit="" lay-filter="demo1">注册</a>
                </div>
                <div class="layui-form-item">
                    <a href="/admin/login/"  style="float: right;">立即登录</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.bootcdn.net/ajax/libs/jquery/2.2.1/jquery.min.js"></script>

<script src="/static/admin/js/jquery.particleground.min.js" charset="utf-8"></script>
<script>
    layui.use(['form','layer','jquery'], function () {
        var form = layui.form;
        var layer = layui.layer;


        form.on('submit(demo1)', function(data){

            $.getJSON("",data.field,function(e){
                if(e.code == 1)
                {
                    layer.msg(e.msg);

                    window.location.href = e.url;

                }
                layer.msg(e.msg);
            });
            return false;
        });

        // 粒子线条背景
        $(document).ready(function(){
            $('.layui-container').particleground({
                dotColor:'#7ec7fd',
                lineColor:'#7ec7fd'
            });
        });



    });
</script>
 
</body>
</html>
</body>
</html>