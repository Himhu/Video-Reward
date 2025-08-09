<?php
// +----------------------------------------------------------------------
// | 模型名称：系统配置模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统配置数据
// | 数据表：system_config
// | 主要字段：name(配置名称)、group(配置分组)、value(配置值)、remark(备注)
// +----------------------------------------------------------------------


namespace app\admin\model;


use app\common\model\TimeModel;

class SystemConfig extends TimeModel
{
    // 定义可填充字段
    protected $fillable = ['name', 'group', 'value', 'remark', 'sort', 'types'];

    // 定义验证规则
    protected $rule = [
        'name'  => 'require|max:30',
        'group' => 'require|max:30',
        'value' => 'max:1000',
        'remark'=> 'max:100',
        'sort'  => 'integer',
        'types' => 'max:255'
    ];

    // 定义验证提示信息
    protected $message = [
        'name.require'  => '配置名称不能为空',
        'name.max'      => '配置名称不能超过30个字符',
        'group.require' => '配置分组不能为空',
        'group.max'     => '配置分组不能超过30个字符',
        'value.max'     => '配置值不能超过1000个字符',
        'remark.max'    => '备注信息不能超过100个字符',
        'sort.integer'  => '排序必须是整数',
        'types.max'     => '类型不能超过255个字符'
    ];

    /**
     * 值的修改器 - 根据配置类型智能处理XSS防护
     * @param string $value
     * @return string
     */
    public function setValueAttr($value)
    {
        // 不需要HTML转义的配置项（主要是URL、路径、图片地址等）
        $noEscapeConfigs = [
            // 网站基础配置
            'site_url',            // 网站地址
            'site_bg',             // 网站背景图
            'favicon',             // 网站图标

            // 上传和存储配置
            'upload_path',         // 上传路径
            'upload_url',          // 上传URL
            'static_url',          // 静态资源URL

            // API和回调配置
            'api_url',             // API地址
            'callback_url',        // 回调地址
            'redirect_url',        // 重定向地址
            'notify_url',          // 通知地址

            // 第三方服务配置
            'wechat_avatar',       // 微信头像
            'qq_avatar',           // QQ头像
            'alipay_logo',         // 支付宝Logo
            'wechat_qr',           // 微信二维码

            // 短链和域名配置
            'ff_url',              // 短链域名
            'domain_url',          // 主域名

            // 其他媒体资源
            'video_url',           // 视频地址
            'audio_url',           // 音频地址
            'download_url',        // 下载地址
        ];

        // 如果是不需要转义的配置项，进行URL格式验证后返回
        if (in_array($this->name, $noEscapeConfigs)) {
            return $this->validateUrlConfig($value);
        }

        // 其他配置项进行HTML转义以防止XSS
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * 值的访问器 - 根据配置类型智能解码
     * @param string $value
     * @return string
     */
    public function getValueAttr($value)
    {
        // 不需要HTML转义的配置项，直接返回原值
        $noEscapeConfigs = [
            // 网站基础配置
            'site_url', 'site_bg', 'favicon',
            // 上传和存储配置
            'upload_path', 'upload_url', 'static_url',
            // API和回调配置
            'api_url', 'callback_url', 'redirect_url', 'notify_url',
            // 第三方服务配置
            'wechat_avatar', 'qq_avatar', 'alipay_logo', 'wechat_qr',
            // 短链和域名配置
            'ff_url', 'domain_url',
            // 其他媒体资源
            'video_url', 'audio_url', 'download_url'
        ];

        // 如果是不需要转义的配置项，直接返回原值
        if (in_array($this->name, $noEscapeConfigs)) {
            return $value;
        }

        // 其他配置项进行HTML解码
        return htmlspecialchars_decode($value, ENT_QUOTES);
    }

    /**
     * 验证URL类配置项的格式
     * @param string $value
     * @return string
     */
    protected function validateUrlConfig($value)
    {
        // 如果值为空，直接返回
        if (empty($value)) {
            return $value;
        }

        // 对于图片和媒体文件，允许相对路径和绝对路径
        $mediaExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'ico', 'mp4', 'mp3', 'wav'];
        $extension = strtolower(pathinfo(parse_url($value, PHP_URL_PATH), PATHINFO_EXTENSION));

        if (in_array($extension, $mediaExtensions)) {
            // 媒体文件，允许相对路径
            return $value;
        }

        // 对于URL类配置，进行基本的URL格式验证
        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
            return $value;
        }

        // 允许相对路径（以/开头）
        if (strpos($value, '/') === 0) {
            return $value;
        }

        // 如果都不符合，返回原值（保持向后兼容）
        return $value;
    }

    /**
     * 获取配置项类型
     * @param string $name 配置项名称
     * @return string
     */
    protected function getConfigType($name)
    {
        // URL类配置项
        $urlConfigs = [
            'site_url', 'site_bg', 'favicon', 'upload_path', 'upload_url',
            'static_url', 'api_url', 'callback_url', 'redirect_url', 'notify_url',
            'wechat_avatar', 'qq_avatar', 'alipay_logo', 'wechat_qr', 'ff_url',
            'domain_url', 'video_url', 'audio_url', 'download_url'
        ];

        // 数字类配置项
        $numberConfigs = [
            'upload_max_size', 'session_expire', 'cache_expire', 'page_size',
            'max_login_attempts', 'token_expire', 'file_max_size'
        ];

        // 布尔类配置项
        $boolConfigs = [
            'site_status', 'debug_mode', 'cache_enable', 'log_enable',
            'captcha_enable', 'email_enable', 'sms_enable'
        ];

        if (in_array($name, $urlConfigs)) {
            return 'url';
        } elseif (in_array($name, $numberConfigs)) {
            return 'number';
        } elseif (in_array($name, $boolConfigs)) {
            return 'boolean';
        } else {
            return 'text';
        }
    }

    /**
     * 验证配置项值的格式
     * @param string $name 配置项名称
     * @param mixed $value 配置项值
     * @return bool
     */
    public function validateConfigValue($name, $value)
    {
        $type = $this->getConfigType($name);

        switch ($type) {
            case 'url':
                return $this->validateUrl($value);
            case 'number':
                return is_numeric($value) && $value >= 0;
            case 'boolean':
                return in_array($value, ['0', '1', 'true', 'false', true, false, 0, 1]);
            case 'text':
            default:
                return strlen($value) <= 1000;
        }
    }

    /**
     * 验证URL格式
     * @param string $value
     * @return bool
     */
    protected function validateUrl($value)
    {
        if (empty($value)) {
            return true;
        }

        // 允许相对路径
        if (strpos($value, '/') === 0) {
            return true;
        }

        // 验证完整URL格式
        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * 清除配置缓存
     * @return void
     */
    public function clearConfigCache()
    {
        \think\facade\Cache::tag('sysconfig')->clear();
    }

    /**
     * 保存后清除缓存
     * @param array $data
     * @return void
     */
    public static function onAfterWrite($data)
    {
        \think\facade\Cache::tag('sysconfig')->clear();
    }

    /**
     * 删除后清除缓存
     * @param array $data
     * @return void
     */
    public static function onAfterDelete($data)
    {
        \think\facade\Cache::tag('sysconfig')->clear();
    }
}