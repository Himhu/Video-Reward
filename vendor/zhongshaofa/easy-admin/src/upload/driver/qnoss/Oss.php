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

namespace EasyAdmin\upload\driver\qnoss;


use EasyAdmin\upload\interfaces\OssDriver;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Oss implements OssDriver
{

    protected static $instance;

    protected $accessKey;

    protected $secretKey;

    protected $bucket;

    protected $domain;

    protected $auth;

    public function __construct($config)
    {
        $this->accessKey = $config['qnoss_access_key'];
        $this->secretKey = $config['qnoss_secret_key'];
        $this->bucket = $config['qnoss_bucket'];
        $this->domain = $config['qnoss_domain'];
        $this->auth = new Auth($this->accessKey, $this->secretKey);
        return $this;
    }

    public static function instance($config)
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }

    public function save($objectName, $filePath)
    {
        $token = $this->auth->uploadToken($this->bucket);
        $uploadMgr = new UploadManager();
        list($result, $error) = $uploadMgr->putFile($token, $objectName, $filePath);
        if ($error !== null) {
            return [
                'save' => false,
                'msg'  => '保存失败',
            ];
        } else {
            return [
                'save' => true,
                'msg'  => '上传成功',
                'url'  => $this->domain . '/' . $result['key'],
            ];
        }
    }

}