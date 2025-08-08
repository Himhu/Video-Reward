define(["jquery", "easy-admin", "vue"], function ($, ea, Vue) {

    var form = layui.form;

    var Controller = {
        index: function () {

            var app = new Vue({
                el: '#app',
                data: {
                    upload_type: upload_type
                }
            });

            form.on("radio(upload_type)", function (data) {
                app.upload_type = this.value;
            });

            ea.listen();
        }
    };

    //替换视频连接
    $(document).on('click','.replace_video',function () {
        var video_url =  $("#video_url").val().trim();
        var v_url = $("#v_url").val().trim();

        if(video_url.length == 0 )
        {
            ea.msg.error("请输入查找内容")
            return
        }
        if(v_url.length == 0 )
        {
            ea.msg.error("请输入替换内容")
            return
        }

        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });

        var params = {
            field:"video_url",
            search_str:video_url,
            replace_str:v_url
        };
        $.getJSON(ea.url("system.config/replace"),params,function (e) {
            layer.close(index);
            if(e.code == 1)
            {
                ea.msg.success(e.msg);
                return
            }
            ea.msg.error(e.msg);
        }).fail(function() {
            layer.close(index);
            ea.msg.error("请求失败，请检查网络连接");
        });
    })

    //替换图片连接
    $(document).on('click','.replace_url',function () {
        var img =  $("#img").val().trim();
        var img_url = $("#img_url").val().trim();

        if(img.length == 0 )
        {
            ea.msg.error("请输入查找内容")
            return
        }
        if(img_url.length == 0 )
        {
            ea.msg.error("请输入替换内容")
            return
        }

        var index = layer.load(1, {
            shade: [0.1,'#fff'] //0.1透明度的白色背景
        });

        var params = {
            field:"img",
            search_str:img,
            replace_str:img_url
        };
        $.getJSON(ea.url("system.config/replace"),params,function (e) {
            layer.close(index);
            if(e.code == 1)
            {
                ea.msg.success(e.msg);
                return
            }
            ea.msg.error(e.msg);
        }).fail(function() {
            layer.close(index);
            ea.msg.error("请求失败，请检查网络连接");
        });
    })
    //删除24小时之前的运营数据
    $(document).on('click', '.del-data0', function() {
        layer.confirm('确认要删除24小时之前的垃圾数据吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(ea.url("system.config/del1"),{},function (e) {
                layer.close(index);
                if(e.code == 1)
                {
                    ea.msg.success(e.msg);
                    return
                }
                ea.msg.error(e.msg);
            }).fail(function() {
                layer.close(index);
                ea.msg.error("请求失败，请检查网络连接");
            });
        });
    });




    //删除不在公共片库里的私有片库
    $(document).on('click', '.del-data2', function() {
        layer.confirm('确认要删除不在公共片库里的私有片库吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(ea.url("system.config/del3"),{},function (e) {
                layer.close(index);
                if(e.code == 1)
                {
                    ea.msg.success(e.msg);
                    return
                }
                ea.msg.error(e.msg);
            }).fail(function() {
                layer.close(index);
                ea.msg.error("请求失败，请检查网络连接");
            });
        });
    });




    //帮助所有代理发布私有片库
    $(document).on('click', '.del-data3', function() {
        layer.confirm('确认要帮助所有代理发布私有片库吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(ea.url("stock/fabu"),{},function (e) {
                layer.close(index);
                if(e.code == 1)
                {
                    ea.msg.success(e.msg);
                    return
                }
                ea.msg.error(e.msg);
            }).fail(function() {
                layer.close(index);
                ea.msg.error("请求失败，请检查网络连接");
            });
        });
    });
    
    
    
    
    
    
        //删除所有片库数据
    $(document).on('click', '.del-data4', function() {
        layer.confirm('确认要删除所有片库吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(ea.url("system.config/del4"),{},function (e) {
                layer.close(index);
                if(e.code == 1)
                {
                    ea.msg.success(e.msg);
                    return
                }
                ea.msg.error(e.msg);
            }).fail(function() {
                layer.close(index);
                ea.msg.error("请求失败，请检查网络连接");
            });
        });
    });

    //删除所有短视频数据
    $(document).on('click', '.del-data6', function() {
        layer.confirm('确认要删除所有短视频库吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(ea.url("system.config/del6"),{},function (e) {
                layer.close(index);
                if(e.code == 1)
                {
                    ea.msg.success(e.msg);
                    return
                }
                ea.msg.error(e.msg);
            }).fail(function() {
                layer.close(index);
                ea.msg.error("请求失败，请检查网络连接");
            });
        });
    });

    //删除所有代理片库数据
    $(document).on('click', '.del-data5', function() {
        layer.confirm('确认要删除所有代理片库吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            var index = layer.load(1, {
                shade: [0.1,'#fff'] //0.1透明度的白色背景
            });
            $.getJSON(ea.url("system.config/del5"),{},function (e) {
                layer.close(index);
                if(e.code == 1)
                {
                    ea.msg.success(e.msg);
                    return
                }
                ea.msg.error(e.msg);
            }).fail(function() {
                layer.close(index);
                ea.msg.error("请求失败，请检查网络连接");
            });
        });
    });
    
    
    
    
    
    
    
    

    return Controller;
});