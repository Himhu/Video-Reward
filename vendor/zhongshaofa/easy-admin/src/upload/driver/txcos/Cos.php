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

namespace EasyAdmin\upload\driver\txcos;

use EasyAdmin\upload\interfaces\OssDriver;
use Qcloud\Cos\Client;
use think\Exception;

class Cos implements OssDriver
{

    protected static $instance;

    protected $secretId;

    protected $secretKey;

    protected $region;

    protected $bucket;

    protected $schema = 'https';

    protected $cosClient;

    protected function __construct($config)
    {
        $this->secretId = $config['txcos_secret_id'];
        $this->secretKey = $config['txcos_secret_key'];
        $this->region = $config['txcos_region'];
        $this->bucket = $config['tecos_bucket'];
        $this->cosClient = new Client(
            [
                'region'      => $this->region,
                'schema'      => $this->schema,
                'credentials' => [
                    'secretId'  => $this->secretId,
                    'secretKey' => $this->secretKey],
            ]);
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
        try {
            $key = $objectName;
            $file = fopen($filePath, "rb");
            if ($file) {
                $result = $this->cosClient->putObject([
                        'Bucket' => $this->bucket,
                        'Key'    => $key,
                        'Body'   => $file]
                );

                // todo 腾讯云坑逼，返回保护变量，也不提供get方法
                $result = (array)$result;
                $result = $result[' * data'];
            } else {
                throw new Exception('文件信息有误');
            }
        } catch (\Exception $e) {
            return [
                'save' => false,
                'msg'  => $e->getMessage(),
            ];
        }
        if (!isset($result['Location'])) {
            return [
                'save' => false,
                'msg'  => '保存失败',
            ];
        }
        return [
            'save' => true,
            'msg'  => '上传成功',
            'url'  => $this->schema . '://' . $result['Location'],
        ];
    }
}