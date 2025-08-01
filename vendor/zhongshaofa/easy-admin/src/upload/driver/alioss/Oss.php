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

namespace EasyAdmin\upload\driver\alioss;

use EasyAdmin\upload\interfaces\OssDriver;
use OSS\Core\OssException;
use OSS\OssClient;

class Oss implements OssDriver
{

    protected static $instance;

    protected $accessKeyId;

    protected $accessKeySecret;

    protected $endpoint;

    protected $bucket;

    protected $domain;

    protected $ossClient;

    protected function __construct($config)
    {
        $this->accessKeyId = $config['alioss_access_key_id'];
        $this->accessKeySecret = $config['alioss_access_key_secret'];
        $this->endpoint = $config['alioss_endpoint'];
        $this->bucket = $config['alioss_bucket'];
        $this->domain = $config['alioss_domain'];
        $this->ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        return $this;
    }

    public static function instance($config)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    public function save($objectName,$filePath)
    {
        try {
            $upload = $this->ossClient->uploadFile($this->bucket, $objectName, $filePath);
        } catch (OssException $e) {
            return [
                'save' => false,
                'msg'  => $e->getMessage(),
            ];
        }
        if (!isset($upload['info']['url'])) {
            return [
                'save' => false,
                'msg'  => '保存失败',
            ];
        }
        return [
            'save' => true,
            'msg'  => '上传成功',
            'url'  => $upload['info']['url'],
        ];
    }

}