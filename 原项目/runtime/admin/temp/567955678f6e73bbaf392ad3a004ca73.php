<?php /*a:2:{s:55:"/www/wwwroot/vdsds.live/app/admin/view/notify/edit.html";i:1652618725;s:58:"/www/wwwroot/vdsds.live/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
        
        <!--<div class="layui-form-item">
            <label class="layui-form-label">uid</label>
            <div class="layui-input-block">
                <input type="text" name="uid" class="layui-input"  placeholder="请输入uid" value="<?php echo htmlentities((isset($row['uid']) && ($row['uid'] !== '')?$row['uid']:'')); ?>">
            </div>
        </div>-->
        <div class="layui-form-item">
            <label class="layui-form-label">公告标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input"  placeholder="请输入公告标题" value="<?php echo htmlentities((isset($row['title']) && ($row['title'] !== '')?$row['title']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">公告类型</label>
            <div class="layui-input-block">
                <select name="type" >
                    <option value=''></option>
                    <?php foreach($getTypeList as $k=>$v): ?>
                    <option value='<?php echo htmlentities($k); ?>' <?php if(in_array(($k), is_array($row['type'])?$row['type']:explode(',',$row['type']))): ?>selected=""<?php endif; ?>><?php echo htmlentities($v); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">公告内容</label>
            <div class="layui-input-block editor" id="editor" name="content">

            </div>

            <textarea style="display: none" id="content" name="content" rows="20" class="layui-textarea " 公告内容 placeholder="请输入公告内容"><?php echo (isset($row['content']) && ($row['content'] !== '')?$row['content']:''); ?></textarea>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <?php foreach($getIsShowList as $k=>$v): ?>
                <input type="radio" name="is_show" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), is_array($row['is_show'])?$row['is_show']:explode(',',$row['is_show']))): ?>checked=""<?php endif; ?>>
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