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

namespace EasyAdmin\annotation;


use Doctrine\Common\Annotations\Annotation\Attributes;

/**
 * 创建节点注解类
 *
 * @Annotation
 * @Target({"METHOD","CLASS"})
 * @Attributes({
 *   @Attribute("time", type = "int")
 * })
 */
final class NodeAnotation
{

    /**
     * 节点名称
     * @Required()
     * @var string
     */
    public $title;

    /**
     * 是否开启权限控制
     * @Enum({true,false})
     * @var bool
     */
    public $auth = true;

}