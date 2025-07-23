<?php /*a:2:{s:65:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/peizhi/index.html";i:1654593415;s:67:"/www/wwwroot/xinuocs.testlike.cn/app/admin/view/layout/default.html";i:1649495816;}*/ ?>
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
<link rel="stylesheet" href="/static/admin/css/welcome.css?v=<?php echo time(); ?>" media="all">
<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-md12">
                <div class="layui-card layui-col-md6">
                    <div class="layui-card-header"><i class="fa fa-line-chart icon"></i>价格设置-微信通道1</div>
                    <div class="layui-card-body">


                        <form id="app-form1" method="post" class="layui-form layuimini-form">


                            <input type="hidden" name="pay_model" value="1">


                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">单片1价格</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox"  checked disabled id="is_dan" lay-filter="dianpian" name="is_dan" <?php  if($price1['is_dan'] == 1){ echo "checked";}  ?> value="1"
                                        lay-skin="switch" lay-text="固定价格|随机价格">
                                    </div>
                                </div>


                                <div class="layui-inline">
                                    <?php if($price1['is_dan'] == 2):?>
                                    <label class="layui-form-label caonima  required" style="width: 65px;">随机价格</label>
                                    <?php else:?>
                                    <label class="layui-form-label caonima  required" style="width: 65px;">固定价格</label>

                                    <?php endif;?>
                                    <div class="layui-input-block" style="margin-left: 95px">
                                        <?php if($price1['is_dan'] == 2):?>
                                        <input type="text" lay-verify="dan_fee" placeholder="随机金额最小<?php echo sysconfig('jp','fb_min_money'); ?>元最大<?php echo sysconfig('jp','fb_max_money'); ?>元" id="dan_fee"  name="dan_fee" disabled class="layui-disabled layui-input" style="width: 100%"
                                               value="">
                                        <?php else:?>
                                        <input type="text" lay-verify="dan_fee"  id="dan_fee"  name="dan_fee" class="disabled layui-input" style="width: 100%"
                                               value="<?php echo htmlentities((isset($price1['dan_fee']) && ($price1['dan_fee'] !== '')?$price1['dan_fee']:'')); ?>">
                                        <?php endif;?>
                                    </div>
                                </div>

                            </div>



                            <?php if(sysconfig('jg','jg_topen') == 1): ?>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">包天观看</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" id="is_day" lay-filter="switchTest" name="is_day" <?php  if($price1['is_day']
                                        == 1){ echo "checked";}  ?> value="<?php echo htmlentities($price1['is_day']); ?>"
                                        lay-skin="switch" lay-text="开|关">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label required">包天价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" lay-verify="date_fee"  name="date_fee" class="layui-input" style="width: 100%"
                                               value="<?php echo htmlentities((isset($price1['date_fee']) && ($price1['date_fee'] !== '')?$price1['date_fee']:'')); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endif; if(sysconfig('jg','jp_wopen') == 1): ?>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">包周观看</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" id="is_week" lay-filter="is_week" name="is_week" <?php  if($price1['is_week']
                                        == 1){ echo "checked";}  ?> value="<?php echo htmlentities($price1['is_week']); ?>"
                                        lay-skin="switch" lay-text="开|关">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label required">包周价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="week_fee" lay-verify="week_fee" style="width: 100%" class="layui-input"
                                               value="<?php echo htmlentities((isset($price1['week_fee']) && ($price1['week_fee'] !== '')?$price1['week_fee']:'')); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endif; if(sysconfig('jg','jg_yopen') == 1): ?>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">包月观看</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox"   id="is_month" lay-filter="is_month" name="is_month" <?php  if($price1['is_month']
                                        == 1){ echo "checked";}  ?> value="<?php echo htmlentities($price1['is_month']); ?>"
                                        lay-skin="switch" lay-text="开|关">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label required">包月价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="month_fee" lay-verify="month_fee" style="width: 100%" class="layui-input"
                                               value="<?php echo htmlentities((isset($price1['month_fee']) && ($price1['month_fee'] !== '')?$price1['month_fee']:'')); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>


                            <div class="hr-line"></div>
                            <div class="layui-form-item text-center">
                                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="" lay-filter="demo1">确认
                                </button>
                                <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
                            </div>

                        </form>

                    </div>
                </div>



                <div class="layui-card layui-col-md6">
                    <div class="layui-card-header"><i class="fa fa-line-chart icon"></i>价格设置-支付宝通道2</div>
                    <div class="layui-card-body">


                        <form id="app-form" method="post" class="layui-form layuimini-form">


                            <input type="hidden" name="pay_model" value="2">


                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">单片价格</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" id="is_dan1"  checked disabled lay-filter="dianpian1" name="is_dan" <?php  if($price2['is_dan'] == 1){ echo "checked";}  ?> value="1"
                                        lay-skin="switch" lay-text="固定价格|随机价格">
                                    </div>
                                </div>


                                <div class="layui-inline">
                                    <?php if($userinfo['is_dan'] == 2):?>
                                    <label class="layui-form-label caonima  required" style="width: 65px;">随机价格</label>
                                    <?php else:?>
                                    <label class="layui-form-label caonima  required" style="width: 65px;">固定价格</label>

                                    <?php endif;?>
                                    <div class="layui-input-block" style="margin-left: 95px">
                                        <?php if($userinfo['is_dan'] == 2):?>
                                        <input type="text" lay-verify="dan_fee1" placeholder="随机金额最小<?php echo sysconfig('jp','fb_min_money'); ?>元最大<?php echo sysconfig('jp','fb_max_money'); ?>元" id="dan_fee1"  name="dan_fee" disabled class="layui-disabled layui-input" style="width: 100%"
                                               value="">
                                        <?php else:?>
                                        <input type="text" lay-verify="dan_fee1"  id="dan_fee1"  name="dan_fee" class="disabled layui-input" style="width: 100%"
                                               value="<?php echo htmlentities((isset($price2['dan_fee']) && ($price2['dan_fee'] !== '')?$price2['dan_fee']:'')); ?>">
                                        <?php endif;?>
                                    </div>
                                </div>

                            </div>



                            <?php if(sysconfig('jg','jg_topen') == 1): ?>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">包天观看</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" id="is_day1" lay-filter="switchTest1" name="is_day" <?php  if($price2['is_day']
                                        == 1){ echo "checked";}  ?> value="<?php echo htmlentities($price2['is_day']); ?>"
                                        lay-skin="switch" lay-text="开|关">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label required">包天价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" lay-verify="date_fee"  name="date_fee" class="layui-input" style="width: 100%"
                                               value="<?php echo htmlentities((isset($price2['date_fee']) && ($price2['date_fee'] !== '')?$price2['date_fee']:'')); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endif; if(sysconfig('jg','jp_wopen') == 1): ?>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">包周观看</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox" id="is_week1" lay-filter="is_week1" name="is_week" <?php  if($price2['is_week']
                                        == 1){ echo "checked";}  ?> value="<?php echo htmlentities($price2['is_week']); ?>"
                                        lay-skin="switch" lay-text="开|关">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label required">包周价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="week_fee" lay-verify="week_fee" style="width: 100%" class="layui-input"
                                               value="<?php echo htmlentities((isset($price2['week_fee']) && ($price2['week_fee'] !== '')?$price2['week_fee']:'')); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endif; if(sysconfig('jg','jg_yopen') == 1): ?>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">包月观看</label>
                                    <div class="layui-input-block">
                                        <input type="checkbox"   id="is_month1" lay-filter="is_month1" name="is_month" <?php  if($price2['is_month']
                                        == 1){ echo "checked";}  ?> value="<?php echo htmlentities($price2['is_month']); ?>"
                                        lay-skin="switch" lay-text="开|关">
                                    </div>
                                </div>

                                <div class="layui-inline">
                                    <label class="layui-form-label required">包月价格</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="month_fee" lay-verify="month_fee" style="width: 100%" class="layui-input"
                                               value="<?php echo htmlentities((isset($price2['month_fee']) && ($price2['month_fee'] !== '')?$price2['month_fee']:'')); ?>">
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>


                            <div class="hr-line"></div>
                            <div class="layui-form-item text-center">
                                <button type="submit" class="layui-btn layui-btn-normal layui-btn-sm" lay-submit="" lay-filter="demo1">确认
                                </button>
                                <button type="reset" class="layui-btn layui-btn-primary layui-btn-sm">重置</button>
                            </div>

                        </form>

                    </div>
                </div>


            </div>


            <div class="layui-col-md12">
                <div class="layui-row layui-col-space15">
                    <div class="layui-col-md12">
                         <div class="layui-card">
                             <div class="layui-card-header"><i class="fa fa-warning icon"></i>样式设置</div>


                                     <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): if( count($list)==0 ) : echo "" ;else: foreach($list as $k=>$vo): ?>

                                     <div class="content-main layui-col-md3 layui-col-sm12" style="margin-top: 10px">

                                         <div class="layui-fluid" style="padding: 0">

                                             <div class="layui-card" style="margin-right: 5%">

                                                 <div class="layui-card-header">
                                                     <?php echo htmlentities($vo['title']); ?>
                                                 </div>

                                                 <div class="layui-card-body" style="text-align:center;">
                                                     <form class="layui-form">
                                                         <div class="layui-form-item">
                                                             <img src="<?php echo htmlentities($vo['image']); ?>" style="height:500px;width: 100%"/>
                                                         </div>
                                                         <div class="layui-form-item">
                                                             <input type="hidden"  autocomplete="off" name="title" class="layui-input" value="<?php echo htmlentities($vo['title']); ?>">
                                                             <input type="hidden"  autocomplete="off" name="muban" class="layui-input" value="<?php echo htmlentities($vo['muban']); ?>">
                                                             <input type="hidden" name="id" value="<?php echo htmlentities($vo['id']); ?>">
                                                             <?php if($userinfo['view_id'] == $vo['id']): ?>
                                                             <button class="layui-btn layui-col-md12 layui-col-sm12 layui-btn-disabled">当前模板</button>
                                                             <?php else: ?>
                                                             <button type="button" class="sub layui-btn layui-btn-normal layui-col-md12 layui-col-sm12" lay-submit="" lay-filter="sub">选择该模板</button>
                                                             <?php endif; ?>
                                                         </div>
                                                     </form>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <?php endforeach; endif; else: echo "" ;endif; ?>


                             </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
</div>
</div>


<script>
    layui.use(['jquery', 'form'], function () {

        var form = layui.form

            , $ = layui.jquery;


        $(".sub").click(function () {
            var info = $(this).parents('form').serialize();
            $.ajax({

                url: "",

                type: "POST",

                dataType: 'json',

                data: info,

                cache: false,

                async: false,

                success: function (data) {

                    if (data.code == 0) {

                        layer.msg(data.msg, {time: 1500}, function () {

                            parent.layer.closeAll();

                            setTimeout(function () {
                                window.parent.location.reload();

                            }, 500)
                        });

                    } else {
                        layer.msg(data.msg);
                    }
                }
            });
        });

        //监听提交
        var fb_min_money = "<?php echo sysconfig('jp','fb_min_money'); ?>";
        var fb_max_money = "<?php echo sysconfig('jp','fb_max_money'); ?>";

        form.on('switch(dianpian)', function (data) {
            var val = this.checked ? '1' : '2';
            document.getElementById("is_dan").value = val
            if(val == 2)
            {
                document.getElementById("dan_fee").value = '';
                document.getElementById("dan_fee").setAttribute('disabled',true)
                document.getElementById("dan_fee").classList.add("layui-disabled")
                document.getElementsByClassName("caonima")[0].innerText = "随机价格"
                document.getElementById("dan_fee").setAttribute('placeholder',"随机金额最小"+fb_min_money+"元,最大"+fb_max_money+"元")
            }
            else
            {
                document.getElementById("dan_fee").removeAttribute('disabled')
                document.getElementById("dan_fee").classList.remove("layui-disabled")
                document.getElementsByClassName("caonima")[0].innerText="固定价格";
                document.getElementById("dan_fee").setAttribute('placeholder',"固定金额最小"+fb_min_money+"元,最大"+fb_max_money+"元")

            }
        });

        form.on('switch(dianpian1)', function (data) {
            var val = this.checked ? '1' : '2';
            document.getElementById("is_dan1").value = val
            if(val == 2)
            {
                document.getElementById("dan_fee1").value = '';
                document.getElementById("dan_fee1").setAttribute('disabled',true)
                document.getElementById("dan_fee1").classList.add("layui-disabled")
                document.getElementsByClassName("caonima")[0].innerText = "随机价格"
                document.getElementById("dan_fee1").setAttribute('placeholder',"随机金额最小"+fb_min_money+"元,最大"+fb_max_money+"元")
            }
            else
            {
                document.getElementById("dan_fee1").removeAttribute('disabled')
                document.getElementById("dan_fee1").classList.remove("layui-disabled")
                document.getElementsByClassName("caonima")[0].innerText="固定价格";
                document.getElementById("dan_fee1").setAttribute('placeholder',"固定金额最小"+fb_min_money+"元,最大"+fb_max_money+"元")

            }
        });


        form.on('switch(switchTest)', function (data) {
            document.getElementById("is_day").value = this.checked ? '1' : '0';
        });

        form.on('switch(is_week)', function (data) {
            document.getElementById("is_week").value = this.checked ? '1' : '0';
        });

        form.on('switch(is_month)', function (data) {
            document.getElementById("is_month").value = this.checked ? '1' : '0';
        });



        form.on('switch(switchTest1)', function (data) {
            document.getElementById("is_day1").value = this.checked ? '1' : '0';
        });

        form.on('switch(is_week1)', function (data) {
            document.getElementById("is_week1").value = this.checked ? '1' : '0';
        });

        form.on('switch(is_month1)', function (data) {
            document.getElementById("is_month1").value = this.checked ? '1' : '0';
        });

        //包天
        var jg_topen = "<?php echo sysconfig('jg','jg_topen'); ?>";
        var jg_tmin = "<?php echo sysconfig('jg','jg_tmin'); ?>";
        var jg_tmax = "<?php echo sysconfig('jg','jg_tmax'); ?>";

        //包周
        var jp_wopen = "<?php echo sysconfig('jg','jp_wopen'); ?>";
        var jp_min = "<?php echo sysconfig('jg','jp_min'); ?>";
        var jp_max = "<?php echo sysconfig('jg','jp_max'); ?>";

        //包月
        var jg_yopen = "<?php echo sysconfig('jg','jg_yopen'); ?>";
        var jg_ymin = "<?php echo sysconfig('jg','jg_ymin'); ?>";
        var jg_ymax = "<?php echo sysconfig('jg','jg_ymax'); ?>";

        //表单验证
        form.verify({
            dan_fee:function (value,q,s) {
                if(parseFloat(value)  < fb_min_money || parseFloat(value) > fb_max_money){
                    return "金额最小:"+ fb_min_money + " 最大金额:" + fb_max_money;
                }
            },
            is_dan:function (value,q,s) {
                console.log(value)
            },
            date_fee: function(value,q,s){
                if(jg_topen)
                {

                    if(parseFloat(value)  < jg_tmin || parseFloat(value) > jg_tmax){
                        return "包天金额最小:"+ jg_tmin + " 最大金额:" + jg_tmax;
                    }
                }
            },
            week_fee: function(value,q,s){
                if(jp_wopen)
                {
                    if(parseFloat(value)  < jp_min || parseFloat(value) > jp_max){
                        return "包周金额最小:"+ jp_min + " 最大金额:" + jp_max;
                    }
                }
            },
            month_fee: function(value,q,s){
                if(jg_yopen)
                {
                    if(parseFloat(value)  < jg_ymin || parseFloat(value) > jg_ymax){
                        return "包月金额最小:"+ jg_ymin + " 最大金额:" + jg_ymax;
                    }
                }
            }
        });


        form.on('submit(demo1)', function(data){

            console.log(JSON.stringify(data.field));

            $.getJSON("/admin/peizhi/index",$.param({data:data.field}),function (e) {
                    if(e.code == 0)
                    {
                        layer.msg(e.msg);
                        return
                    }
                layer.msg("error");
                return
            })
            return false;
        });

        form.render();

    });
</script>

</body>
</html>