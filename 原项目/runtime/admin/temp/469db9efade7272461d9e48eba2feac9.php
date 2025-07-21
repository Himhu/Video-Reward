<?php /*a:12:{s:72:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/index.html";i:1653453876;s:67:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/layout/default.html";i:1649495816;s:72:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/weihu.html";i:1652625092;s:73:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/peizhi.html";i:1652624242;s:69:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/ff.html";i:1652624840;s:72:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/price.html";i:1652624377;s:78:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/ankoupeizhi.html";i:1652623910;s:70:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/dsp.html";i:1652632199;s:73:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/wechat.html";i:1652623873;s:70:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/pay.html";i:1654531941;s:72:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/short.html";i:1653453916;s:73:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/system/config/upload.html";i:1652623872;}*/ ?>
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
    <div class="layuimini-main" id="app">

        <div class="layui-tab layui-tab-card" lay-filter="docDemoTabBrief">
            <ul class="layui-tab-title">
                <li class="layui-this">网站维护</li>
                <li >网站配置</li>
                <li >防洪配置</li>
                <li >价格配置</li>
                <li >抽单配置</li>
                <li >短视频配置</li>
          <!--      <li >微信公众号配置</li> -->
                <li >支付配置</li>
                <li >自动换域名配置</li>
                <li>上传配置</li>


            </ul>
            <div class="layui-tab-content">

                <div class="layui-tab-item layui-show" data-desc="网站维护">
                    
<style>
.span-margin{
    margin-right: 10px;
}
</style>
<form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label">清理数据</label>
        <div class="layui-input-block">

            <button type="button" class="layui-btn layui-btn-radius layui-btn-sm del-data0">删除24小时之前的垃圾数据</button>
            <button type="button" class="layui-btn layui-btn-normal layui-btn-radius layui-btn-sm del-data1">删除垃圾数据(保证每日不卡)</button>
<!--            <button type="button" class="layui-btn layui-btn-warm layui-btn-radius layui-btn-sm del-data2">删除不在公共片库里的私有片库</button>-->
<!--            <button type="button" class="layui-btn layui-btn-danger layui-btn-radius layui-btn-sm del-data3">帮助所有代理发布私有片库</button>-->

        </div>
    </div>


        <div class="layui-form-item">
        <label class="layui-form-label">片库清理</label>
        <div class="layui-input-block">


            <button type="button" class="layui-btn layui-btn-danger layui-btn-radius layui-btn-sm del-data4">清理更新片库数据</button>
            <button type="button" class="layui-btn layui-btn-danger layui-btn-radius layui-btn-sm del-data6">清理更新短视频数据</button>
<!--            <button type="button" class="layui-btn layui-btn-danger layui-btn-radius layui-btn-sm del-data5">清理代理片库数据</button>-->

        </div>
    </div>


    
    

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">替换视频链接</label>
            <div class="layui-input-inline" style="width: 240px;">
                <input type="text" name="video_url" id="video_url" lay-verify="required" placeholder="查找" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 240px;">
                <input type="text" name="v_url" id="v_url" lay-verify="required" placeholder="替换" autocomplete="off" class="layui-input">
            </div>
            <!--<button type="button" class="layui-btn layui-btn-normal layui-btn-radius ">替换视频链接</button>-->
            <button type="button" class="layui-btn layui-btn-normal layui-btn-sm replace_video"    >替换视频链接</button>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">替换图片链接</label>
            <div class="layui-input-inline" style="width: 240px;">
                <input type="text" name="img" id="img" lay-verify="required" placeholder="查找" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 240px;">
                <input type="text" name="img_url" id="img_url" lay-verify="required" placeholder="替换" autocomplete="off" class="layui-input">
            </div>
            <!--<button type="button" class="layui-btn layui-btn-normal layui-btn-radius ">替换视频链接</button>-->
            <button type="button" class="layui-btn layui-btn-normal layui-btn-sm replace_url"  >替换图片链接</button>
        </div>
    </div>
</form>
                </div>

                <div class="layui-tab-item " data-desc="网站配置">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label">订单前缀</label>
        <div class="layui-input-block">
            <input type="text" name="site_order" class="layui-input" placeholder="请输入订单前缀，不建议填写" value="<?php echo sysconfig('site','site_order'); ?>">
            <tip>填写订单前缀。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">站点名称</label>
        <div class="layui-input-block">
            <input type="text" name="site_name" class="layui-input" placeholder="请输入站点名称" value="<?php echo sysconfig('site','site_name'); ?>">
            <tip>填写站点名称。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">网站标题</label>
        <div class="layui-input-block">
            <input type="text" name="site_title" class="layui-input" placeholder="请输入网站标题" value="<?php echo sysconfig('site','site_title'); ?>">
            <tip>填写网站标题。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">网站标语</label>
        <div class="layui-input-block">
            <input type="text" name="site_slogan" class="layui-input" placeholder="请输入网站标语" value="<?php echo sysconfig('site','site_slogan'); ?>">
            <tip>填写网站标语。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">网站描述</label>
        <div class="layui-input-block">
            <textarea name="site_content" class="layui-textarea"  placeholder="请输入网站描述"><?php echo sysconfig('site','site_content'); ?></textarea>
            <tip>填写网站描述。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">LOGO图标</label>
        <div class="layui-input-block layuimini-upload">
            <input name="logo_image" class="layui-input layui-col-xs6" placeholder="请上传LOGO图标" value="<?php echo sysconfig('site','logo_image'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn" data-upload="logo_image" data-upload-number="one" data-upload-exts="ico|png|jpg|jpeg"><i class="fa fa-upload"></i> 上传</a></span>
                <span><a class="layui-btn layui-btn-normal" id="select_logo_image" data-upload-select="logo_image" data-upload-number="one"><i class="fa fa-list"></i> 选择</a></span>
            </div>
        </div>
    </div>



    <div class="layui-form-item">
        <label class="layui-form-label">登陆背景图</label>
        <div class="layui-input-block layuimini-upload">
            <input name="site_bg" class="layui-input layui-col-xs6" placeholder="请上传浏览器背景图png,jpg类型" value="<?php echo sysconfig('site','site_bg'); ?>">
            <div class="layuimini-upload-btn">
                <span><a class="layui-btn" data-upload="site_bg" data-upload-number="one" data-upload-exts="ico|png|jpg|jpeg"><i class="fa fa-upload"></i> 上传</a></span>
                <span><a class="layui-btn layui-btn-normal" id="select_site_site_bg" data-upload-select="site_bg" data-upload-number="one"><i class="fa fa-list"></i> 选择</a></span>
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">备案信息</label>
        <div class="layui-input-block">
            <input type="text" name="site_beian" class="layui-input" placeholder="请输入备案信息" value="<?php echo sysconfig('site','site_beian'); ?>">
            <tip>填写备案信息。</tip>
        </div>
    </div>

<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">默认中转域名</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="text" name="site_domain" class="layui-input" placeholder="请输入默认中转域名" value="<?php echo sysconfig('site','site_domain'); ?>">-->
<!--            <tip>填写默认中转域名。</tip>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">支付域名</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="text" name="site_pay" class="layui-input" placeholder="请输入支付域名" value="<?php echo sysconfig('site','site_pay'); ?>">-->
<!--            <tip>填写支付域名。</tip>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">支付回调域名</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="text" name="site_payback" class="layui-input" placeholder="请输入支付回调域名" value="<?php echo sysconfig('site','site_payback'); ?>">-->
<!--            <tip>填写支付回调域名。</tip>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">短网址接口</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="text" name="site_urlapi" class="layui-input" placeholder="请输入短网址接口" value="<?php echo sysconfig('site','site_urlapi'); ?>">-->
<!--            <tip>填写短网址接口。</tip>-->
<!--        </div>-->
<!--    </div>-->

<!--    <div class="layui-form-item">-->
<!--        <label class="layui-form-label">CDN加速域名</label>-->
<!--        <div class="layui-input-block">-->
<!--            <input type="text" name="site_cdn" class="layui-input" placeholder="请输入CDN加速域名" value="<?php echo sysconfig('site','site_cdn'); ?>">-->
<!--            <tip>填写CDN加速域名。</tip>-->
<!--        </div>-->
<!--    </div>-->

    <div class="layui-form-item">
        <label class="layui-form-label">客服QQ</label>
        <div class="layui-input-block">
            <input type="text" name="site_qq" class="layui-input" placeholder="请输入客服QQ" value="<?php echo sysconfig('site','site_qq'); ?>">
            <tip>填写客服QQ。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">客服微信</label>
        <div class="layui-input-block">
            <input type="text" name="site_wechat" class="layui-input" placeholder="请输入客服微信" value="<?php echo sysconfig('site','site_wechat'); ?>">
            <tip>填写客服微信。</tip>
        </div>
    </div>





    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>

                <div class="layui-tab-item " data-desc="防封配置">
                    <form id="app-form" class="layui-form layuimini-form" lay-filter="example">

    <div class="layui-form-item">
        <label class="layui-form-label">URL加密</label>
        <div class="layui-input-block">
            <input type="checkbox" lay-filter="switchTest" name="" <?php  if(sysconfig('ff','ff_close') == 1){ echo "checked";}  ?> value="<?php echo sysconfig('ff','ff_close'); ?>" lay-skin="switch" lay-text="ON|OFF">
        </div>
       
    </div>

    <input type="hidden" id="ff_close" name="ff_close" value="<?php echo sysconfig('ff','ff_close'); ?>">


    <div class="layui-form-item">
        <label class="layui-form-label">域名随机前缀</label>
        <div class="layui-input-block">
            <input type="checkbox" lay-filter="ff_fix" name="" <?php  if(sysconfig('ff','ff_fix') == 1){ echo "checked";}  ?> value="<?php echo sysconfig('ff','ff_fix'); ?>" lay-skin="switch" lay-text="ON|OFF">
        </div>
    </div>

    <input type="hidden" id="ff_fix" name="ff_fix" value="<?php echo sysconfig('ff','ff_fix'); ?>">



    <div class="layui-form-item">
        <label class="layui-form-label">禁止pc端打开</label>
        <div class="layui-input-block">
            <input type="checkbox" lay-filter="ff_pc" name="" <?php  if(sysconfig('ff','ff_pc') == 1){ echo "checked";}  ?> value="<?php echo sysconfig('ff','ff_pc'); ?>" lay-skin="switch" lay-text="ON|OFF">
        </div>
    </div>

    <input type="hidden" id="ff_pc" name="ff_pc" value="<?php echo sysconfig('ff','ff_pc'); ?>">



    <div class="layui-form-item">
        <label class="layui-form-label">URL加密地址</label>
        <div class="layui-input-block">
            <input type="text" name="ff_url" class="layui-input"  placeholder="请输入url加密入口地址" value="<?php echo sysconfig('ff','ff_url'); ?>">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">短地址</label>
        <div class="layui-input-block">
            <select name="ff_short" lay-filter="aihao">

                <?php foreach($short as $k => $v):?>
                <option value="<?php echo $k?>" <?php if($k == sysconfig('ff','ff_short')  ){ echo 'selected';}?> ><?php echo $v?></option>
                <?php endforeach;?>

            </select>
        </div>
    </div>




    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-filter="demo1"  lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>

<script>
    layui.use(['form', 'layedit', 'laydate'], function(){

        var form = layui.form,layer = layui.layer
        form.on('switch(switchTest)', function(data){
            document.getElementById("ff_close").value = this.checked ? '1' : '0';
        });

        form.on('switch(ff_pc)', function(data){
            document.getElementById("ff_pc").value = this.checked ? '1' : '0';
        });

        form.on('switch(ff_fix)', function(data){
            document.getElementById("ff_fix").value = this.checked ? '1' : '0';
        });
    })
</script>
                </div>

                <div class="layui-tab-item " data-desc="价格配置">
                    <form id="app-form" class="layui-form layuimini-form">


    <div class="layui-form-item">
        <label class="layui-form-label">单视频最低金额</label>
        <div class="layui-input-block">
            <input type="number" name="fb_min_money" class="layui-input"  placeholder="请输入代理后台可设置的最低金额" value="<?php echo sysconfig('jp','fb_min_money'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">单视频最高金额</label>
        <div class="layui-input-block">
            <input type="number" name="fb_max_money" class="layui-input"  placeholder="请输入代理后台可设置的最高金额" value="<?php echo sysconfig('jp','fb_max_money'); ?>">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">默认提现费率</label>
        <div class="layui-input-block">
            <input type="number" name="jg_mrfx" class="layui-input"  placeholder="请输入默认提现费率" value="<?php echo sysconfig('jg','jg_mrfx'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">默认返佣费率</label>
        <div class="layui-input-block">
            <input type="number" name="jg_mrfy" class="layui-input"  placeholder="请输入默认返佣费率" value="<?php echo sysconfig('jg','jg_mrfy'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">邀请码价格</label>
        <div class="layui-input-block">
            <input type="number" name="jg_yqm" class="layui-input"  placeholder="请输入邀请码价格" value="<?php echo sysconfig('jg','jg_yqm'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">域名价格</label>
        <div class="layui-input-block">
            <input type="number" name="jg_ym" class="layui-input"  placeholder="请输入域名价格" value="<?php echo sysconfig('jg','jg_ym'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">单笔最低提现金额</label>
        <div class="layui-input-block">
            <input type="number" name="jg_dbzd" class="layui-input"  placeholder="请输入单笔最低提现金额" value="<?php echo sysconfig('jg','jg_dbzd'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">单笔最高提现金额</label>
        <div class="layui-input-block">
            <input type="number" name="jg_dbzg" class="layui-input"  placeholder="请输入单笔最高提现金额" value="<?php echo sysconfig('jg','jg_dbzg'); ?>">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">单天最高提现金额</label>
        <div class="layui-input-block">
            <input type="number" name="jg_dtzg" class="layui-input"  placeholder="请输入单天最高提现金额" value="<?php echo sysconfig('jg','jg_dtzg'); ?>">
        </div>
    </div>



    <input type="hidden" name="jg_topen" id="bt_switch" value="<?php echo sysconfig('jg','jg_topen'); ?>">

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">包天总闸</label>
            <div class="layui-input-inline" style="width: 50px;">
                <input type="checkbox" class=" layui-input-inline" <?php  if(sysconfig('jg','jg_topen') == 1){ echo "checked";}  ?>  name="" lay-skin="switch" lay-filter="bt_switch" title="开关">
            </div>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="jg_tmin" value="<?php echo sysconfig('jg','jg_tmin'); ?>" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="jg_tmax" value="<?php echo sysconfig('jg','jg_tmax'); ?>" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>


    <input type="hidden" name="jp_wopen" id="jp_wopen" value="<?php echo sysconfig('jg','jp_wopen'); ?>">

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">包周总闸<br>不建议开启</label>
            <div class="layui-input-inline" style="width: 50px;">
                <input type="checkbox" class=" layui-input-inline" <?php  if(sysconfig('jg','jp_wopen') == 1){ echo "checked";}  ?>  name="" lay-skin="switch" lay-filter="jp_wopen" title="开关">
            </div>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="jp_min" value="<?php echo sysconfig('jg','jp_min'); ?>" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="jp_max" value="<?php echo sysconfig('jg','jp_max'); ?>" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>


    <input type="hidden" name="jg_yopen" id="jg_yopen" value="<?php echo sysconfig('jg','jg_yopen'); ?>">

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">包月总闸<br>不建议开启</label>
            <div class="layui-input-inline" style="width: 50px;">
                <input type="checkbox" class=" layui-input-inline" <?php  if(sysconfig('jg','jg_yopen') == 1){ echo "checked";}  ?> name="open" lay-skin="switch" lay-filter="jg_yopen" title="开关">
            </div>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="jg_ymin" value="<?php echo sysconfig('jg','jg_ymin'); ?>" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline" style="width: 200px;">
                <input type="text" name="jg_ymax" value="<?php echo sysconfig('jg','jg_ymax'); ?>" placeholder="￥" autocomplete="off" class="layui-input">
            </div>
        </div>
    </div>









    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>



<script>
    layui.use(['form', 'layedit', 'laydate'], function(){

        var form = layui.form,layer = layui.layer
        form.on('switch(bt_switch)', function(data){
            document.getElementById("bt_switch").value = this.checked ? '1' : '0';
        });

        form.on('switch(jg_yopen)', function(data){
            document.getElementById("jg_yopen").value = this.checked ? '1' : '0';
        });

        form.on('switch(jp_wopen)', function(data){
            document.getElementById("jp_wopen").value = this.checked ? '1' : '0';
        });
    })
</script>
                </div>

                <div class="layui-tab-item " data-desc="抽单配置">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label">全局抽单倒数</label>
        <div class="layui-input-block">
            <input type="number" name="ac_number" class="layui-input"  placeholder="请输入全局抽单倒数值" value="<?php echo sysconfig('ac','ac_number'); ?>">


        </div>
    </div>
    
    
     <div class="layui-form-item">
        <label class="layui-form-label">全局初始值</label>
        <div class="layui-input-block">
            <input type="number" name="ac_init_number" class="layui-input"  placeholder="全局初始值" value="<?php echo sysconfig('ac','ac_init_number'); ?>">


        </div>
    </div>
    
    
    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>

                <div class="layui-tab-item " data-desc="自动换域名配置">
                    <form id="app-form" class="layui-form layuimini-form">

    <?php foreach($config['short_video'] as $items):if($items['types'] == "input"):?>
            <div class="layui-form-item">
                <label class="layui-form-label"><?php echo $items['remark']?></label>
                <div class="layui-input-block">
                    <input type="text" name="<?php echo $items['name']?>" class="layui-input" placeholder="<?php echo $items['remark']?>" value="<?php echo $items['value'] ?>" >
                </div>
            </div>
        <?php endif;if($items['types'] == "textarea"):?>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo $items['remark']?></label>
            <div class="layui-input-block">
                <textarea type="text" style="height: 150px;" name="<?php echo $items['name']?>" class="layui-input" placeholder="<?php echo $items['remark']?>" >

                    <?php echo $items['value'] ?>
                </textarea>
            </div>
        </div>
        <?php endif;if($items['types'] == "file"):?>
            <div class="layui-form-item">
                <label class="layui-form-label"><?php echo $items['remark']?></label>
                <div class="layui-input-block layuimini-upload">
                    <input name="<?php echo $items['name']?>" class="layui-input layui-col-xs6" placeholder="请上传<?php echo $items['remark']?>" value="<?php echo $items['value']?>">
                    <div class="layuimini-upload-btn">
                        <span><a class="layui-btn" data-upload="<?php echo $items['name']?>" data-upload-number="one" data-upload-exts="ico|png|jpg|jpeg"><i class="fa fa-upload"></i> 上传</a></span>
                    </div>
                </div>
            </div>
        <?php endif;if($items['types'] == "radio"):?>
        <div class="layui-form-item">
            <label class="layui-form-label"><?php echo $items['remark']?></label>
            <div class="layui-input-block">
                <input type="checkbox" lay-filter="<?php echo $items['name']?>" name="" <?php echo $items['value'] == 1 ? 'checked':  ''?> value="<?php echo $items['value']?>" lay-skin="switch" lay-text="ON|OFF">
            </div>
        </div>
    <input type="hidden" id="<?php echo $items['name']?>" name="<?php echo $items['name']?>" value="<?php echo $items['value']?>">
    <script>
        layui.use(['form', 'layedit', 'laydate'], function(){

            var form = layui.form,layer = layui.layer
            form.on("switch(<?php echo $items['name']?>)", function(data){
                document.getElementById("<?php echo $items['name']?>").value = this.checked ? '1' : '0';
            });
        })
    </script>

    <?php endif;?>

    <?php endforeach;?>




    <div class="layui-form-item">
        <label class="layui-form-label">二维码位置</label>
        <div class="layui-input-block">
            上: <input type="text" name="q_t"  value="<?php echo sysconfig('short_video','q_t'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
            右: <input type="text" name="q_r" value="<?php echo sysconfig('short_video','q_r'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
            下: <input type="text" name="q_x" value="<?php echo sysconfig('short_video','q_x'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
            左: <input type="text" name="q_l" value="<?php echo sysconfig('short_video','q_l'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">二维码logo位置</label>
        <div class="layui-input-block">
            上: <input type="text" name="l_t" value="<?php echo sysconfig('short_video','l_t'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
            右: <input type="text" name="l_r" value="<?php echo sysconfig('short_video','l_r'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
            下: <input type="text" name="l_x" value="<?php echo sysconfig('short_video','l_x'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
            左: <input type="text" name="l_l" value="<?php echo sysconfig('short_video','l_l'); ?>" autocomplete="off" class="layui-input" style="display: inline;width: 50px;">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">二维码测试地址</label>
        <div class="layui-input-block">
            <?php echo htmlentities($d); ?>/q?f=<?php echo htmlentities($f); ?>&q=123
        </div>
    </div>
<p>开启短视频试看，需短视频导入ppvod云转码系统视频外链，如果不是请不要开启试看功能。</p>














    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>

          <!--      <div class="layui-tab-item " data-desc="公众号配置">
                    <form id="app-form" class="layui-form layuimini-form">



    <div class="layui-form-item">
        <label class="layui-form-label">微信公众号appid</label>
        <div class="layui-input-block">
            <input type="text" name="wx_appid" class="layui-input"  placeholder="请输入微信公众号appid" value="<?php echo sysconfig('wx','wx_appid'); ?>">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">微信公众号secret</label>
        <div class="layui-input-block">
            <input type="text" name="wx_secret" class="layui-input"  placeholder="请输入微信公众号secret" value="<?php echo sysconfig('wx','wx_secret'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">微信公众号token</label>
        <div class="layui-input-block">
            <input type="text" name="wx_token" class="layui-input"  placeholder="请输入微信公众号token" value="<?php echo sysconfig('wx','wx_token'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">微信公众号aeskey</label>
        <div class="layui-input-block">
            <input type="text" name="wx_aeskey" class="layui-input"  placeholder="请输入微信公众号aeskey" value="<?php echo sysconfig('wx','wx_aeskey'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">微信公众号授权url</label>
        <div class="layui-input-block">
            <input type="text" name="wx_url" class="layui-input"  placeholder="请输入微信公众号授权url" value="<?php echo sysconfig('wx','wx_url'); ?>">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">微信防封接口</label>
        <div class="layui-input-block">
            <input type="text" name="wx_ffapi" class="layui-input"  placeholder="请输入微信防封接口:" value="<?php echo sysconfig('wx','wx_ffapi'); ?>">
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">微信风控落地</label>
        <div class="layui-input-block">
            <input type="text" name="wx_fk" class="layui-input"  placeholder="请输入微信风控落地" value="<?php echo sysconfig('wx','wx_fk'); ?>">
        </div>
    </div>


    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div> -->

                <div class="layui-tab-item " data-desc="支付配置">
                    <form id="app-form" class="layui-form layuimini-form">




    <div class="layui-form-item">
        <label class="layui-form-label">微信通道1</label>
        <div class="layui-input-block">
            <select name="pay_zhifu" lay-filter="aihao">
                <option value="0" >请选择</option>

                <?php foreach($pay_lists as $k=>$v): ?>
                <option value="<?php echo htmlentities($v['pay_model']); ?>"  <?php if($v['pay_model'] == sysconfig('pay','pay_zhifu')  ){ echo 'selected';}?>  ><?php echo htmlentities($v['title']); ?></option>
                <?php endforeach; ?>


            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">支付宝通道2</label>
        <div class="layui-input-block">
            <select name="pay_zhifu1" lay-filter="aihao">
                <option value="" >通道关闭（关闭后将不使用双通道模式直接使用微信通道1选择的通道拉起支付）</option>

                <?php foreach($pay_lists as $k=>$v): ?>
                <option value="<?php echo htmlentities($v['pay_model']); ?>"  <?php if($v['pay_model'] == sysconfig('pay','pay_zhifu1')  ){ echo 'selected';}?>  ><?php echo htmlentities($v['title']); ?></option>
                <?php endforeach; ?>


            </select>
        </div>
    </div>
    
    
    
     <!-- <div class="layui-form-item">
        <label class="layui-form-label required">模式</label>
        <div class="layui-input-block">
            <?php foreach(['local'=>'选择模式','zhiguan'=>'直观模式'] as $key=>$val): ?>
            <input type="radio"  name="pay_type" lay-filter="pay_type" value="<?php echo htmlentities($key); ?>" title="<?php echo htmlentities($val); ?>" <?php if($key==sysconfig('pay','pay_type')): ?>checked=""<?php endif; ?>>
            <?php endforeach; ?>
        </div>
    </div>
    -->
    
    
    




    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>


                <div class="layui-tab-item " data-desc="支付配置">
                    <form id="app-form" class="layui-form layuimini-form">




    <div class="layui-form-item">
        <label class="layui-form-label">猫咪检测Token</label>
        <div class="layui-input-block">
            <input type="text" name="m_token" class="layui-input" placeholder="猫咪API：91up.top获取token" value="<?php echo sysconfig('short','m_token'); ?>">
            <tip>请改成你的token</tip>
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">微博短网址cookie</label>
        <div class="layui-input-block">
            <input type="text" name="sina" class="layui-input" placeholder="" value="<?php echo sysconfig('short','sina'); ?>">
            <tip>请填写你的微博cookie，系统将自动通过您的ck登陆微博请求官方接口获取短网址</tip>
        </div>
    </div>





    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
                </div>


                <div class="layui-tab-item" data-desc="上传配置">
                    <form id="app-form" class="layui-form layuimini-form">

    <div class="layui-form-item">
        <label class="layui-form-label required">存储方式</label>
        <div class="layui-input-block">
            <?php foreach(['local'=>'本地存储','alioss'=>'阿里云oss','qnoss'=>'七牛云oss','txcos'=>'腾讯云cos'] as $key=>$val): ?>
            <input type="radio" v-model="upload_type" name="upload_type" lay-filter="upload_type" value="<?php echo htmlentities($key); ?>" title="<?php echo htmlentities($val); ?>" <?php if($key==sysconfig('upload','upload_type')): ?>checked=""<?php endif; ?>>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">允许类型</label>
        <div class="layui-input-block">
            <input type="text" name="upload_allow_ext" class="layui-input" lay-verify="required" lay-reqtext="请输入允许类型" placeholder="请输入允许类型" value="<?php echo sysconfig('upload','upload_allow_ext'); ?>">
            <tip>英文逗号做分隔符。</tip>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label required">允许大小</label>
        <div class="layui-input-block">
            <input type="text" name="upload_allow_size" class="layui-input" lay-verify="required" lay-reqtext="请输入允许上传大小" placeholder="请输入允许上传大小" value="<?php echo sysconfig('upload','upload_allow_size'); ?>">
            <tip>设置允许上传大小。</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">公钥信息</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_access_key_id" class="layui-input" lay-verify="required" lay-reqtext="请输入公钥信息" placeholder="请输入公钥信息" value="<?php echo sysconfig('upload','alioss_access_key_id'); ?>">
            <tip>例子：FSGGshu64642THSk</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">私钥信息</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_access_key_secret" class="layui-input" lay-verify="required" lay-reqtext="请输入私钥信息" placeholder="请输入私钥信息" value="<?php echo sysconfig('upload','alioss_access_key_secret'); ?>">
            <tip>例子：5fsfPReYKkFSGGshu64642THSkmTInaIm</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">数据中心</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_endpoint" class="layui-input" lay-verify="required" lay-reqtext="请输入数据中心" placeholder="请输入数据中心" value="<?php echo sysconfig('upload','alioss_endpoint'); ?>">
            <tip>例子：https://oss-cn-shenzhen.aliyuncs.com</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">空间名称</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_bucket" class="layui-input" lay-verify="required" lay-reqtext="请输入空间名称" placeholder="请输入空间名称" value="<?php echo sysconfig('upload','alioss_bucket'); ?>">
            <tip>例子：easy-admin</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'alioss'" v-cloak>
        <label class="layui-form-label required">访问域名</label>
        <div class="layui-input-block">
            <input type="text" name="alioss_domain" class="layui-input" lay-verify="required" lay-reqtext="请输入访问域名" placeholder="请输入访问域名" value="<?php echo sysconfig('upload','alioss_domain'); ?>">
            <tip>例子：easy-admin.oss-cn-shenzhen.aliyuncs.com</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">公钥信息</label>
        <div class="layui-input-block">
            <input type="text" name="txcos_secret_id" class="layui-input" lay-verify="required" lay-reqtext="请输入公钥信息" placeholder="请输入公钥信息" value="<?php echo sysconfig('upload','txcos_secret_id'); ?>">
            <tip>例子：AKIDta6OQCbALQGrCI6ngKwQffR3dfsfrwrfs</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">私钥信息</label>
        <div class="layui-input-block">
            <input type="text" name="txcos_secret_key" class="layui-input" lay-verify="required" lay-reqtext="请输入私钥信息" placeholder="请输入私钥信息" value="<?php echo sysconfig('upload','txcos_secret_key'); ?>">
            <tip>例子：VllEWYKtClAbpqfFdTqysXxGQM6dsfs</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">存储桶地域</label>
        <div class="layui-input-block">
            <input type="text" name="txcos_region" class="layui-input" lay-verify="required" lay-reqtext="请输入存储桶地域" placeholder="请输入存储桶地域" value="<?php echo sysconfig('upload','txcos_region'); ?>">
            <tip>例子：ap-guangzhou</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'txcos'" v-cloak>
        <label class="layui-form-label required">存储桶名称</label>
        <div class="layui-input-block">
            <input type="text" name="tecos_bucket" class="layui-input" lay-verify="required" lay-reqtext="请输入存储桶名称" placeholder="请输入存储桶名称" value="<?php echo sysconfig('upload','tecos_bucket'); ?>">
            <tip>例子：easyadmin-1251997243</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">公钥信息</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_access_key" class="layui-input" lay-verify="required" lay-reqtext="请输入公钥信息" placeholder="请输入公钥信息" value="<?php echo sysconfig('upload','qnoss_access_key'); ?>">
            <tip>例子：v-lV3tXev7yyfsfa1jRc6_8rFOhFYGQvvjsAQxdrB</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">私钥信息</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_secret_key" class="layui-input" lay-verify="required" lay-reqtext="请输入私钥信息" placeholder="请输入私钥信息" value="<?php echo sysconfig('upload','qnoss_secret_key'); ?>">
            <tip>例子：XOhYRR9JNqxsWVEO-mHWB4193vfsfsQADuORaXzr</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">存储空间</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_bucket" class="layui-input" lay-verify="required" lay-reqtext="请输入存储桶地域" placeholder="请输入存储桶地域" value="<?php echo sysconfig('upload','qnoss_bucket'); ?>">
            <tip>例子：easyadmin</tip>
        </div>
    </div>

    <div class="layui-form-item" v-if="upload_type == 'qnoss'" v-cloak>
        <label class="layui-form-label required">访问域名</label>
        <div class="layui-input-block">
            <input type="text" name="qnoss_domain" class="layui-input" lay-verify="required" lay-reqtext="请输入访问域名" placeholder="请输入访问域名" value="<?php echo sysconfig('upload','qnoss_domain'); ?>">
            <tip>例子：http://q0xqzappp.bkt.clouddn.com</tip>
        </div>
    </div>

    <div class="hr-line"></div>
    <div class="layui-form-item text-center">
        <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="system.config/save" data-refresh="false">确认</button>
        <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
    </div>

</form>
<script>
    var upload_type = "<?php echo sysconfig('upload','upload_type'); ?>";
</script>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>