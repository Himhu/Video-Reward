<?php /*a:2:{s:65:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/stock/import.html";i:1652629030;s:67:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
            <label class="layui-form-label">资源链接</label>
            <div class="layui-input-block">
                <textarea id="video_msg" name="video_msg" placeholder="视频信息格式： 标题|视频|图片|时长  结尾不要带回车！" class="layui-input" style="height:300px"></textarea>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">代理ID</label>
            <div class="layui-input-block">
                <select name="sort" class="caonima" lay-verify="required">
                    <option value='0' >--默认顺序--</option>
                    <option value='1'>标题|视频|图片</option>
                    <option value='2'>标题|图片|视频</option>
                    <option value='3'>视频|图片|标题</option>
                    <option value='4'>视频|标题|图片</option>
                    <option value='5'>图片|视频|标题</option>
                    <option value='6'>图片|标题|视频</option>
                    <option value='7'>标题|视频|图片|时长</option>
                    <option value='8'>标题|图片|视频|时长</option>
                    <option value='9'>标题|视频|时长|图片</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="d" value="<?php echo htmlentities($d); ?>">

        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>


</body>
</html>