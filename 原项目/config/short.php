<?php
// +----------------------------------------------------------------------
// | EasyAdmin
// +----------------------------------------------------------------------
// | 版权所有:201912782@qq.com
// +----------------------------------------------------------------------
// | 开源协议  https://mit-license.org
// +----------------------------------------------------------------------
// | 无论您是从何处取得本代码，请遵守开源协议，及国家法律法规，在法律许可内使用该源代码。
// +----------------------------------------------------------------------
return [

    [
        'model' => '0',
        'title' => '默认通道'
    ],
    [
        'model' => 'car',
        'title' => '【猫咪】薪火cos短网址',
        'app_key' => 'c110ec83a0a48108784765864d8231c3'//填写你的猫咪薪火接口token【91up.top】开通接口
    ],
    [
        'model' => 'self',
        'title' => '【薪火】原链接短网址',
        'app_key' => 'c7bc43a9e847fbca19e7515ab484849a'
    ],
    [
        'model' => 'bdmr',
        'title' => '官方百度Mr短网址',//需要点击访问提示
        'app_key' => ''//这里不需要填，薪火cos短网址填写token即可使用该短网址
    ],
    [
        'model' => 'tcn',
        'title' => '官方新浪t.cn短网址',
        'app_key' => '',//这里不需要填，薪火cos短网址填写token即可使用该短网址
        'url' => 'https://www.yuque.com/r/goto?url='//填写新浪白名单域名url   如申请不到官方白名单请留空 把主域名配置公众号  url如http://mp.weixinbridge.com/mp/wapredirect?url=  http://m.toutiao.com/search/jump?url= http://chat.fenhao.me/ http://links.jianshu.com/go?to=
    ],
    [
        'model' => 'sina',
        'title' => '官方新浪sina公众号短网址',//使用该短网址需主域名配置公众号，不然会有继续访问提示
        'app_key' => ''//这里不需要填，薪火cos短网址填写token即可使用该短网址
    ],
    [
        'model' => 'link',
        'title' => '百度官方link原网址',//QQ绿标百度官方源网址
        'app_key' => ''//这里不需要填，薪火cos短网址填写token即可使用该短网址
    ],
    [
        'model' => 'tinyurl',
        'title' => '【猫咪】tinyurl.com(短链接)',
        'app_key' => '60f6b3e0788809b33950371204dc4461'//填写你的猫咪tinyurl.com接口token【91up.top】开通接口
    ],
    [
        'model' => 'z3',
        'title' => '【猫咪】GG.GG短网址',
        'app_key' => 'c95f926891049d418e7b797ff7c64b9e'//填写你的猫咪GG.GG接口token【91up.top】开通接口
    ]

];