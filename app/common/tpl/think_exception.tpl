<?php
$cdnurl = function_exists('config') ? config('view_replace_str.__CDN__') : '';
$publicurl = function_exists('config') ? config('view_replace_str.__PUBLIC__') : '/';
$debug = function_exists('config') ? config('app_debug') : false;

$lang = [
    'An error occurred' => '发生错误',
'Home' => '返回主页',
'Feedback' => '反馈错误',
'The page you are looking for is temporarily unavailable' => '你所浏览的页面暂时无法访问',
'You can return to the previous page and try again' => '你可以返回上一页重试，或直接向我们反馈错误报告'
];

$langSet = '';

if (isset($_GET['lang'])) {
$langSet = strtolower($_GET['lang']);
} elseif (isset($_COOKIE['think_var'])) {
$langSet = strtolower($_COOKIE['think_var']);
} elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
$langSet     = strtolower($matches[1]);
}
$langSet = $langSet && in_array($langSet, ['zh-cn', 'en']) ? $langSet : 'zh-cn';
$langSet == 'en' && $lang = array_combine(array_keys($lang), array_keys($lang));

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>您访问的页面404报错啦，支持一下公益事业吧</title>

</head>

<body>
<script type="text/javascript" src="//qzonestyle.gtimg.cn/qzone/hybrid/app/404/search_children.js" charset="utf-8" homePageUrl="http://baidu.com" homePageName="回到我的主页"></script>
</body>
</html>
