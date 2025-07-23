<?php
// +----------------------------------------------------------------------
// | 模型名称：系统上传文件模型
// +----------------------------------------------------------------------
// | 模型功能：管理系统上传文件记录
// | 数据表：system_uploadfile
// | 主要字段：upload_type(上传类型)、original_name(原始文件名)、mime_type(文件类型)、url(访问URL)
// +----------------------------------------------------------------------

namespace app\admin\model;


use app\common\model\TimeModel;

class SystemUploadfile extends TimeModel
{

}