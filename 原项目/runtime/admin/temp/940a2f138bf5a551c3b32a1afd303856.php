<?php /*a:2:{s:63:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/outlay/add.html";i:1652627045;s:67:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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

    <div style="padding-left: 60px">
        <blockquote class="layui-elem-quote">
            提现说明:
            <br>
            单笔最小提款金额: <?php echo sysconfig('jg','jg_dbzd'); ?> 元
            <br>
            每天可提款总金额: <?php echo sysconfig('jg','jg_dtzg'); ?> 元
            <br>
            今日已申请提款总金额: <?php echo htmlentities($tx_arr); ?> 元
            <br>
            单笔最大提款金额: <?php echo sysconfig('jg','jg_dbzg'); ?> 元
            <br>
            可提现余额: <span style="color: red"><?php echo htmlentities($money); ?></span> 元

        </blockquote>
    </div>

    <form id="app-form" class="layui-form layuimini-form">


        <!--        <div class="layui-form-item">-->
        <!--            <label class="layui-form-label">单笔最小提款金额:</label>-->
        <!--            <div class="layui-input-block">-->
        <!--                <input type="number" name="jg_dbzd" readonly="readonly" class="layui-input layui-disabled"  placeholder="请输入单笔最低提现金额" value="<?php echo sysconfig('jg','jg_dbzd'); ?>">-->
        <!--            </div>-->
        <!--        </div>-->

        <!--        <div class="layui-form-item">-->
        <!--            <label class="layui-form-label">每天可提款总金额:</label>-->
        <!--            <div class="layui-input-block">-->
        <!--                <input type="number" name="jg_dtzg" readonly="readonly" class="layui-input layui-disabled"  placeholder="请输入单天最高提现金额" value="<?php echo sysconfig('jg','jg_dtzg'); ?>">-->
        <!--            </div>-->
        <!--        </div>-->

        <!--        <div class="layui-form-item">-->
        <!--            <label class="layui-form-label">今日已申请提款总金额:</label>-->
        <!--            <div class="layui-input-block">-->
        <!--                <input type="text" name="moneyall" readonly="readonly" class="layui-input layui-disabled" lay-verify="required" placeholder="请输入提现金额" value="<?php echo htmlentities($tx_arr); ?>">-->
        <!--            </div>-->
        <!--        </div>-->

        <!--        <div class="layui-form-item">-->
        <!--            <label class="layui-form-label">单笔最大提款金额:</label>-->
        <!--            <div class="layui-input-block">-->
        <!--                <input type="number" name="jg_dbzg" readonly="readonly" class="layui-input layui-disabled"  placeholder="请输入单笔最高提现金额" value="<?php echo sysconfig('jg','jg_dbzg'); ?>">-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="layui-form-item">-->
        <!--            <label class="layui-form-label">可用余额:</label>-->
        <!--            <div class="layui-input-block">-->
        <!--                <input type="number" name="" readonly="readonly" class="layui-input layui-disabled"  placeholder="可用余额" value="<?php echo htmlentities($money); ?>">-->
        <!--            </div>-->
        <!--        </div>-->


        <div class="layui-form-item">
            <label class="layui-form-label">提现金额</label>
            <div class="layui-input-block">
                <input type="text" name="money" class="layui-input" lay-verify="required" placeholder="请输入提现金额"
                       value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">提现密码</label>
            <div class="layui-input-block">
                <input type="password" name="txpwd" class="layui-input" lay-verify="required" placeholder="请输入提现密码"
                       value="">
                <tip>新用户默认无设置提现密码，请点击右上角头像处设置提现密码!</tip>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label required">收款码</label>
            <div class="layui-input-block layuimini-upload">
                <input name="image" class="layui-input layui-col-xs6" lay-verify="required" placeholder="请上传收款码"
                       value="">
                         <tip><a href="/upload/tp/yhk.png" style="color: pink;">提现银行卡格式</a></tip>
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="image" data-upload-number="one"
                             data-upload-exts="png|jpg|ico|jpeg" data-upload-icon="image"><i class="fa fa-upload"></i> 上传</a></span>
                            
                             
<!--                    <span><a class="layui-btn layui-btn-normal" id="select_image" data-upload-select="image"-->
<!--                             data-upload-number="one" data-upload-mimetype="image/*"><i-->
<!-- class="fa fa-list"></i> 选择</a></span>-->


                </div>
                
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