<?php /*a:2:{s:56:"/www/wwwroot/vdsds.live/app/admin/view/outlayw/edit.html";i:1652621533;s:58:"/www/wwwroot/vdsds.live/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
            <label class="layui-form-label">代理</label>
            <div class="layui-input-block">
                <input type="text" name="uid" class="layui-input layui-disabled" readonly="readonly" placeholder="请输入代理" value="<?php echo htmlentities((isset($row['uid']) && ($row['uid'] !== '')?$row['uid']:'')); ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">代理名称</label>
            <div class="layui-input-block">
                <select name="uid" lay-verify="required" readonly="readonly" disabled>
                    <?php foreach($admin_lists as $k=>$v): if($row['uid']!=$v['id']): ?><option></option><?php endif; ?>
                    <option value="<?php echo htmlentities($v['id']); ?>" <?php if($row['uid']==$v['id']): ?>selected=""<?php endif; ?>><?php echo htmlentities($v['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">提现金额</label>
            <div class="layui-input-block">
                <input type="text" name="money" readonly="readonly" class="layui-input layui-disabled" lay-verify="required" placeholder="请输入提现金额" value="<?php echo htmlentities((isset($row['money']) && ($row['money'] !== '')?$row['money']:'')); ?>">
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label required">收款码</label>
            <div class="layui-input-block layuimini-upload">
                <!--<img name="image" class="layui-input layui-col-xs6" lay-verify="required"  placeholder="请上传收款码" value="<?php echo htmlentities((isset($row['image']) && ($row['image'] !== '')?$row['image']:'')); ?>" src="<?php echo htmlentities((isset($row['image']) && ($row['image'] !== '')?$row['image']:'')); ?>" width="42" height="42">-->
                <img  src="<?php echo htmlentities((isset($row['image']) && ($row['image'] !== '')?$row['image']:'')); ?>" width="100" height="130">
                <!--<div class="layuimini-upload-btn">-->
                    <!--<span><a class="layui-btn" data-upload="image" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>-->
                    <!--<span><a class="layui-btn layui-btn-normal" id="select_image" data-upload-select="image" data-upload-number="one" data-upload-mimetype="image/*"><i class="fa fa-list"></i> 选择</a></span>-->
                <!--</div>-->
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-block">
                <?php foreach($getStatusList as $k=>$v): ?>
                <input type="radio" name="status" value="<?php echo htmlentities($k); ?>" title="<?php echo htmlentities($v); ?>" <?php if(in_array(($k), is_array($row['status'])?$row['status']:explode(',',$row['status']))): ?>checked=""<?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">拒绝原因</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea"  placeholder="请输入拒绝原因"><?php echo (isset($row['remark']) && ($row['remark'] !== '')?$row['remark']:''); ?></textarea>
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