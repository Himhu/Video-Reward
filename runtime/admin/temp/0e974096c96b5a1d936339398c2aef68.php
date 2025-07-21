<?php /*a:2:{s:61:"/www/wwwroot/vdsds.live/app/admin/view/system/admin/edit.html";i:1654668320;s:58:"/www/wwwroot/vdsds.live/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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

      <!--  <div class="layui-form-item">
            <label class="layui-form-label required">用户头像</label>
            <div class="layui-input-block layuimini-upload">
                <input name="head_img" class="layui-input layui-col-xs6" lay-verify="required" lay-reqtext="请上传用户头像" placeholder="请上传用户头像" value="<?php echo htmlentities((isset($row['head_img']) && ($row['head_img'] !== '')?$row['head_img']:'')); ?>">
                <div class="layuimini-upload-btn">
                    <span><a class="layui-btn" data-upload="head_img" data-upload-number="one" data-upload-exts="png|jpg|ico|jpeg"><i class="fa fa-upload"></i> 上传</a></span>
                    <span><a class="layui-btn layui-btn-normal" id="select_head_img" data-upload-select="head_img" data-upload-number="one"><i class="fa fa-list"></i> 选择</a></span>
                </div>
            </div>
        </div>-->

        <div class="layui-form-item">
            <label class="layui-form-label">代理ID </label>
            <div class="layui-input-block">
                <input type="text" name="id" class="layui-input layui-disabled"  readonly  lay-verify="required" lay-reqtext="请输入登录密码" placeholder="请输入登录密码" value="<?php echo htmlentities((isset($row['id']) && ($row['id'] !== '')?$row['id']:'')); ?>">

            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label required">登录账户</label>
            <div class="layui-input-block">
                <input type="text" name="username" class="layui-input" readonly value="<?php echo htmlentities((isset($row['username']) && ($row['username'] !== '')?$row['username']:'')); ?>">
                <tip>填写登录账户。</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">登录密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" class="layui-input"  lay-reqtext="请输入登录密码" placeholder="请输入登录密码" value="">
                <tip>填写登录密码。(留空不修改)</tip>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-block">
                <input type="password" name="password_again" class="layui-input"  lay-reqtext="请输入确认密码" placeholder="请输入确认密码" value="">
                <tip>填写再次登录密码。(留空不修改)</tip>
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">提现费率</label>
            <div class="layui-input-block">
                    <select name="poundage" lay-verify="required" lay-search="">
                        <option value="">请选择费率</option>
                        <?php for($i = 0;$i<=100;$i++):?>
                        <option value="<?php echo $i;?>"  <?php if($row['poundage'] == $i){echo 'selected';}?>    ><?php echo $i.'%';?></option>
                        <?php endfor;?>
                    </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">返佣费率</label>
            <div class="layui-input-block">
                <select name="ticheng" lay-verify="required" lay-search="">
                    <option value="">请选择费率</option>
                    <?php for($i = 0;$i<=100;$i++):?>
                    <option value="<?php echo $i;?>"  <?php if($row['ticheng'] == $i){echo 'selected';}?>    ><?php echo $i.'%';?></option>
                    <?php endfor;?>
                </select>
            </div>
        </div>

        <?php if(sysconfig('jg','jg_topen') == 1): ?>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">包天观看</label>
                <div class="layui-input-block">
                    <input type="checkbox" id="is_day" lay-filter="switchTest" name="is_day" <?php  if($userinfo['is_day']
                    == 1){ echo "checked";}  ?> value="<?php echo htmlentities($userinfo['is_day']); ?>"
                    lay-skin="switch" lay-text="开|关">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label required">包天价格</label>
                <div class="layui-input-block">
                    <input type="text" lay-verify="date_fee"  name="date_fee" class="layui-input" style="width: 300px"
                           value="<?php echo htmlentities((isset($userinfo['date_fee']) && ($userinfo['date_fee'] !== '')?$userinfo['date_fee']:'')); ?>">
                </div>
            </div>
        </div>
        <?php endif; if(sysconfig('jg','jp_wopen') == 1): ?>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">包周观看</label>
                <div class="layui-input-block">
                    <input type="checkbox" id="is_week" lay-filter="is_week" name="is_week" <?php  if($userinfo['is_week']
                    == 1){ echo "checked";}  ?> value="<?php echo htmlentities($userinfo['is_week']); ?>"
                    lay-skin="switch" lay-text="开|关">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label required">包周价格</label>
                <div class="layui-input-block">
                    <input type="text" name="week_fee" lay-verify="week_fee" style="width: 300px" class="layui-input"
                           value="<?php echo htmlentities((isset($userinfo['week_fee']) && ($userinfo['week_fee'] !== '')?$userinfo['week_fee']:'')); ?>">
                </div>
            </div>
        </div>
        <?php endif; if(sysconfig('jg','jg_yopen') == 1): ?>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">包月观看</label>
                <div class="layui-input-block">
                    <input type="checkbox" id="is_month" lay-filter="is_month" name="is_month" <?php  if($userinfo['is_month']
                    == 1){ echo "checked";}  ?> value="<?php echo htmlentities($userinfo['is_month']); ?>"
                    lay-skin="switch" lay-text="开|关">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label required">包月价格</label>
                <div class="layui-input-block">
                    <input type="text" name="month_fee" lay-verify="month_fee" style="width: 300px" class="layui-input"
                           value="<?php echo htmlentities((isset($userinfo['month_fee']) && ($userinfo['month_fee'] !== '')?$userinfo['month_fee']:'')); ?>">
                </div>
            </div>
        </div>
        <?php endif; ?>


        <div class="layui-form-item">
            <label class="layui-form-label">支付渠道</label>
            <div class="layui-input-block">
                <select name="pay_model" lay-verify="required" lay-search="">
                    <option value="0">请选择支付渠道</option>
                    <?php foreach($pay_lists as $k => $v):?>
                    <option value="<?php echo $k;?>"  <?php if($row['pay_model'] == $k){echo 'selected';}?>    ><?php echo $v;?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>



        <div class="layui-form-item">
            <label class="layui-form-label">QQ</label>
            <div class="layui-input-block">
                <input type="number" name="qq" class="layui-input"   lay-reqtext="请输入确认QQ号" placeholder="请输入确认QQ号" value="<?php echo htmlentities((isset($row['qq']) && ($row['qq'] !== '')?$row['qq']:'')); ?>">
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">微信</label>
            <div class="layui-input-block">
                <input type="text" name="wechat_account" class="layui-input"  lay-reqtext="请输入确认微信号" placeholder="请输入确认微信号" value="<?php echo htmlentities((isset($row['wechat_account']) && ($row['wechat_account'] !== '')?$row['wechat_account']:'')); ?>">
            </div>
        </div>


        <div class="layui-form-item">
            <label class="layui-form-label">角色权限</label>
            <div class="layui-input-block">
                <?php foreach($auth_list as $key=>$val): ?>
                <input type="checkbox" name="auth_ids[<?php echo htmlentities($key); ?>]" lay-skin="primary" title="<?php echo htmlentities($val); ?>" <?php if(in_array($key,$row['auth_ids'])): ?>checked="" <?php endif; ?>>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">备注信息</label>
            <div class="layui-input-block">
                <textarea name="remark" class="layui-textarea" placeholder="请输入备注信息"><?php echo htmlentities((isset($row['remark']) && ($row['remark'] !== '')?$row['remark']:'')); ?></textarea>
            </div>
        </div>

        <div class="hr-line"></div>
        <div class="layui-form-item text-center">
            <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit>确认</button>
            <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
        </div>

    </form>
</div>


<script>
    layui.use(['jquery', 'form'], function () {

        var form = layui.form

            , $ = layui.jquery;
        form.on('switch(switchTest)', function (data) {
            document.getElementById("is_day").value = this.checked ? '1' : '0';
        });

        form.on('switch(is_week)', function (data) {
            document.getElementById("is_week").value = this.checked ? '1' : '0';
        });

        form.on('switch(is_month)', function (data) {
            document.getElementById("is_month").value = this.checked ? '1' : '0';
        });

    });
</script>

</body>
</html>