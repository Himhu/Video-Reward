<?php /*a:2:{s:66:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/quantity/edit.html";i:1649495816;s:67:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
            <label class="layui-form-label">代理ID</label>
            <div class="layui-input-block">
                <select name="uid" >
                    <?php foreach($admin_lists as $k=>$v): if($row['uid']!=$v['id']): ?><option></option><?php endif; ?>
                    <option value="<?php echo htmlentities($v['id']); ?>" <?php if($row['uid']==$v['id']): ?>selected=""<?php endif; ?>><?php echo htmlentities($v['username']); ?>(<?php echo htmlentities($v['id']); ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">初始值</label>
            <div class="layui-input-block">
                <input type="text" name="initial" class="layui-input" lay-verify="required" placeholder="请输入初始值" value="<?php echo htmlentities((isset($row['initial']) && ($row['initial'] !== '')?$row['initial']:'')); ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">倒数值</label>
            <div class="layui-input-block">
                <input type="text" name="bottom" class="layui-input" lay-verify="required" placeholder="请输入倒数值" value="<?php echo htmlentities((isset($row['bottom']) && ($row['bottom'] !== '')?$row['bottom']:'')); ?>">
            </div>
        </div>
    <!--    <div class="layui-form-item">
            <label class="layui-form-label">全局倒数</label>
            <div class="layui-input-block">
                <?php foreach($getBottomAllList as $k=>$v): ?>
                <input type="radio" name="bottom_all" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), is_array($row['bottom_all'])?$row['bottom_all']:explode(',',$row['bottom_all']))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>-->

<!--        <div class="layui-form-item">
            <label class="layui-form-label">创建人</label>
            <div class="layui-input-block">
                <input type="text" name="creator_id" class="layui-input"  placeholder="请输入创建人" value="<?php echo htmlentities((isset($row['creator_id']) && ($row['creator_id'] !== '')?$row['creator_id']:'')); ?>">
            </div>
        </div>-->
        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>
</body>
</html>