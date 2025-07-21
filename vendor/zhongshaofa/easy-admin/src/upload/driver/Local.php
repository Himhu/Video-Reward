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

namespace EasyAdmin\upload\driver;

use EasyAdmin\upload\FileBase;
use EasyAdmin\upload\trigger\SaveDb;

/**
 * 本地上传
 * Class Local
 * @package EasyAdmin\upload\driver
 */
class Local extends FileBase
{

    /**
     * 重写上传方法
     * @return array|void
     */
    public function save()
    {
        parent::save();
        SaveDb::trigger($this->tableName, [
            'upload_type'   => $this->uploadType,
            'original_name' => $this->file->getOriginalName(),
            'mime_type'     => $this->file->getOriginalMime(),
            'file_ext'      => strtolower($this->file->getOriginalExtension()),
            'url'           => $this->completeFileUrl,
            'create_time'   => time(),
        ]);
        return [
            'save' => true,
            'msg'  => '上传成功',
            'url'  => $this->completeFileUrl,
        ];
    }

}