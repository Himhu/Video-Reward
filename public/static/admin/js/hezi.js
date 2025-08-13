define(["jquery", "easy-admin"], function ($, ea) {

    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'hezi/index',
        add_url: 'hezi/add',
        edit_url: 'hezi/edit',
        delete_url: 'hezi/delete',
        export_url: 'hezi/export',
        modify_url: 'hezi/modify',
        buy_url: 'hezi/domain',
    };

    var Controller = {

        index: function () {

           var  clienWidth = '50%';
           var clientHeight = '50%';

            if(ea.checkMobile())
            {
                clienWidth = '100%';
                clientHeight = '100%';
            }
            //生成推广链接
            $(".addpush").click(function (obj) {
                layer.open({
                    type: 2,
                    area: [clienWidth, clientHeight],
                    fixed: false, //不固定
                    maxmin: true,
                    content: "/" + window.location.pathname.split('/')[1] + "/hezi/add?type=" + $(obj.currentTarget).data('active_index')
                });
            })
            //购买域名
            $(".buydomain").click(function (obj) {
                layer.open({
                    type: 2,
                    area: [clienWidth, clientHeight],
                    fixed: false, //不固定
                    maxmin: true,
                    content: "/" + window.location.pathname.split('/')[1] + "/hezi/domain?type=" + $(obj.currentTarget).data('active_index')
                });
            })
            //删除
            $(document).on('click','.delete-items',(obj)=>{
                $.getJSON("/" + window.location.pathname.split('/')[1] + "/hezi/delete?id="+$(obj.currentTarget).data('id'),function () {
                    layer.msg('删除成功');
                    window.location.reload()
                })
            });
            //编辑
            $(document).on('click','.edit-items',(obj)=>{

                layer.open({
                    type: 2,
                    area: [clienWidth, clientHeight],
                    fixed: false, //不固定
                    maxmin: true,
                    content: "/" + window.location.pathname.split('/')[1] + "/hezi/edit?id="+$(obj.currentTarget).data('id')
                });
               // window.location.reload()
            });
            //复制
            $(document).on('click','.item-url',(obj)=>{

                var input = document.createElement('input');
                input.setAttribute('readonly', 'readonly'); // 防止手机上弹出软键盘
                input.setAttribute('value', $(obj.currentTarget).data('url'));
                document.body.appendChild(input);
                // input.setSelectionRange(0, 9999);
                input.select();
                var res = document.execCommand('copy');
                document.body.removeChild(input);
                layer.msg("复制成功!");
            });
            //弹出二维码
            $(document).on('click','.show-qr',(obj)=>{
                var url = $(obj.currentTarget).data('url')

                var  clienWidth = '';
                var clientHeight = '';

                if(ea.checkMobile())
                {
                    clienWidth = '320px';
                    clientHeight = '320px';
                }


                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 1,
                    area: [clienWidth, clientHeight],
                    shadeClose: true,
                    skin: 'yourclass',
                    content: "<img src='/" + window.location.pathname.split('/')[1] + "/hezi/add?qr=1&text="+encodeURIComponent(url)+"'>"
                });
            });

            function urlCrypt(url) {
                var str2 = '';
                str = url;
                str3 = str.substring(0, 7);
                if (str3 == 'http://') {
                    str2 = 'http://';
                    str = str.substring(7, str.length);
                }
                for (i = 0; i < str.length; i++) {
                    if (str.charCodeAt(i) == '47') str2 += '/';
                    else if (str.charCodeAt(i) == '63') str2 += '?';
                    else if (str.charCodeAt(i) == '38') str2 += '&';
                    else if (str.charCodeAt(i) == '61') str2 += '=';
                    else if (str.charCodeAt(i) == '58') str2 += ':';
                    else str2 += '%' + str.charCodeAt(i).toString(16);
                }

                var str2 = ff_url+str2;
                return str2
            }

            function getList(type) {

                var index = layer.load(1, {
                    shade: [0.1,'#fff'] //0.1透明度的白色背景
                });

                let param = type || 1;
                $.getJSON('/' + window.location.pathname.split('/')[1] + '/hezi/index' , {
                    filter: JSON.stringify({type:param}),
                    op: JSON.stringify({type:"%*%"})
                },function (res) {
                    var html = "";
                    if(res.count >0)
                    {
                        $.each(res.data,function (index,obj) {
                            var color = '#FF5722';
                            var hezi = '关闭';
                            if(obj.hezi_url)
                            {
                                color = "#01AAED";
                                hezi = "开启"
                            }
                           var u = obj.short_url
                            if(value == 1)
                            {
                                u = urlCrypt(obj.short_url)
                            }
                            var title = '';

                            if(obj.view != null)
                            {
                                title = obj.view.title;
                            }
                           html += "<div class=\"layui-col-md4 card-item\">\n" +
                               "                            <div class=\"layui-card\">\n" +
                               "                                <div class=\"layui-card-header\">\n" +
                               "                                    <div class=\"header-items\" style=\"text-align: left;font-weight: 600\">\n" +
                               "                                        使用模版:<span style=\"color: #01AAED;\">"+title+"</span>\n" +
                               "                                    </div>\n" +
                               "\n" +
                               "                                    <div class=\"header-items\" style=\"text-align: center;font-weight: 600\">\n" +
                               "                                        盒子试看:<span style=\"color: "+color+";\">"+hezi+"</span>\n" +
                               "                                    </div>\n" +
                               "\n" +
                               "                                    <div class=\"header-items delete-items\" data-id='"+obj.id+"' style=\"text-align: right\">\n" +
                               "                                        <i class=\"layui-icon layui-icon-close\" style=\"font-size: 18px; color: #000000;\"></i>\n" +
                               "                                    </div>\n" +
                               "                                </div>\n" +
                               "                                <div class=\"layui-card-body\">\n" +
                               "                                    <span class=\"body-item layui-elip item-url\" data-url='"+u+"'>"+u+"</span><br>\n" +
                               "                                    <span class=\"body-item layui-elip\">备注:"+obj.remark+"</span><br>\n" +
                               "                                    <span class=\"body-item layui-elip\">生成日期:"+obj.create_time+"</span>\n" +
                               "                                </div>\n" +
                               "\n" +
                               "                                <div class=\"layui-card-footer\">\n" +
                               "                                    <div class=\"footer-items show-qr\" data-url='"+u+"' style=\"text-align: center\">二维码\n" +
                               "                                        <i class=\"layui-icon  layui-icon-cols\" style=\"font-size: 15px; color: #000000;\"></i>\n" +
                               "                                    </div>\n" +
                               "                                    <div class=\"footer-items edit-items\" data-id='"+obj.id+"' style=\"text-align: center\">编辑\n" +
                               "                                        <i class=\"layui-icon  layui-icon-set\" style=\"font-size: 15px; color: #000000;\"></i>\n" +
                               "                                    </div>\n" +
                               "                                </div>\n" +
                               "                            </div>\n" +
                               "                        </div>";
                        })

                        $(".box-"+type).html(html)
                    }
                    layer.closeAll()
                })
            }
            getList(1);
            layui.use('element', function () {
                var $ = layui.jquery
                    , element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块
                element.on('tab(docDemoTabBrief)', function(data){

                    getList(data.index + 1)
                });
            });

            ea.listen();
        },
        add: function () {
            ea.listen();
        },
        edit: function () {
            ea.listen();
        },
        domain: function () {
            ea.listen();
        },
    };
    return Controller;
});