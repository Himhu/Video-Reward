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
        // 不需要HTML转义的配置项（主要是URL、路径等）
        $noEscapeConfigs = [
            'logo_image',
            'site_url',
            'upload_path',
            'api_url',
            'callback_url',
            'redirect_url'
        ];

        // 如果是不需要转义的配置项，直接返回原值
        if (in_array($this->name, $noEscapeConfigs)) {
            return $value;
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
            'logo_image',
            'site_url',
            'upload_path',
            'api_url',
            'callback_url',
            'redirect_url'
        ];

        // 如果是不需要转义的配置项，直接返回原值
        if (in_array($this->name, $noEscapeConfigs)) {
            return $value;
        }

        // 其他配置项进行HTML解码
        return htmlspecialchars_decode($value, ENT_QUOTES);
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