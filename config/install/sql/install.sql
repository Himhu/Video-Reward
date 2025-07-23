-- +----------------------------------------------------------------------
-- | Video-Reward 数据库结构文件 (重构优化版本)
-- +----------------------------------------------------------------------
-- | 支持新的模块化架构，优化表结构和字段定义
-- +----------------------------------------------------------------------
-- | 重构说明：清理示例数据，统一字段规范，适配新架构
-- +----------------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ========================================
-- 核心业务表
-- ========================================

-- 安全删除现有表 (支持覆盖安装)
DROP TABLE IF EXISTS `ds_category`;
DROP TABLE IF EXISTS `ds_admin`;
DROP TABLE IF EXISTS `ds_config`;
DROP TABLE IF EXISTS `ds_user`;
DROP TABLE IF EXISTS `ds_video`;
DROP TABLE IF EXISTS `ds_comment`;
DROP TABLE IF EXISTS `ds_reward`;
DROP TABLE IF EXISTS `ds_order`;
DROP TABLE IF EXISTS `ds_payment`;
DROP TABLE IF EXISTS `ds_log`;

--
-- 分类表 (适配新的Category模块)
--
CREATE TABLE `ds_category` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级分类ID',
  `ctitle` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(1000) DEFAULT NULL COMMENT '分类图片',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  `created_by` int(11) UNSIGNED DEFAULT '0' COMMENT '创建者ID',
  `updated_by` int(11) UNSIGNED DEFAULT '0' COMMENT '更新者ID',
  `version` int(11) UNSIGNED DEFAULT '1' COMMENT '版本号',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`),
  KEY `idx_pid_status` (`pid`, `status`),
  KEY `idx_status_sort` (`status`, `sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';

-- 
-- 系统管理员表 (适配新的认证架构)
-- 
CREATE TABLE `ds_admin` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(40) NOT NULL DEFAULT '' COMMENT '登录密码',
  `nickname` varchar(50) DEFAULT '' COMMENT '昵称',
  `email` varchar(100) DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) DEFAULT '' COMMENT '手机号',
  `head_img` varchar(500) DEFAULT '/static/admin/images/head.jpg' COMMENT '头像',
  `login_num` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_ip` varchar(50) DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) UNSIGNED DEFAULT '0' COMMENT '最后登录时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统管理员表';

-- 
-- 系统配置表 (优化字段定义)
-- 
CREATE TABLE `ds_config` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '配置名称',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '配置分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '配置标题',
  `value` text COMMENT '配置值',
  `type` varchar(20) NOT NULL DEFAULT 'text' COMMENT '配置类型',
  `options` text COMMENT '配置选项',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `idx_group` (`group`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表';

-- 
-- 资源库表 (优化字段定义)
-- 
CREATE TABLE `ds_stock` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '资源名称',
  `url` text COMMENT '资源链接',
  `cover` varchar(500) DEFAULT '' COMMENT '封面图片',
  `description` text COMMENT '资源描述',
  `tags` varchar(500) DEFAULT '' COMMENT '标签',
  `view_count` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '查看次数',
  `download_count` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_cid` (`cid`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`),
  KEY `idx_cid_status` (`cid`, `status`),
  KEY `idx_status_sort` (`status`, `sort`),
  KEY `idx_view_count` (`view_count`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='资源库表';

-- 
-- 链接管理表 (优化字段定义)
-- 
CREATE TABLE `ds_link` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '链接ID',
  `cid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '分类ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '链接标题',
  `video_url` text COMMENT '视频链接',
  `short_url` varchar(500) DEFAULT '' COMMENT '短链接',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '打赏金额',
  `view_count` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '访问次数',
  `pay_count` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '支付次数',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_cid` (`cid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`),
  KEY `idx_uid_status` (`uid`, `status`),
  KEY `idx_cid_status` (`cid`, `status`),
  KEY `idx_view_count` (`view_count`),
  KEY `idx_pay_count` (`pay_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='链接管理表';

-- ========================================
-- 系统管理表
-- ========================================

-- 
-- 系统菜单表 (优化字段定义)
-- 
CREATE TABLE `ds_system_menu` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级菜单ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) DEFAULT '' COMMENT '菜单图标',
  `href` varchar(200) DEFAULT '' COMMENT '链接地址',
  `target` varchar(20) DEFAULT '_self' COMMENT '打开方式',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统菜单表';

-- 
-- 文件上传表 (优化字段定义)
-- 
CREATE TABLE `ds_system_uploadfile` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `upload_type` varchar(20) NOT NULL DEFAULT 'local' COMMENT '存储类型',
  `original_name` varchar(255) DEFAULT '' COMMENT '原始文件名',
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '存储文件名',
  `file_path` varchar(500) NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_ext` varchar(10) DEFAULT '' COMMENT '文件扩展名',
  `mime_type` varchar(100) DEFAULT '' COMMENT 'MIME类型',
  `image_width` int(11) UNSIGNED DEFAULT '0' COMMENT '图片宽度',
  `image_height` int(11) UNSIGNED DEFAULT '0' COMMENT '图片高度',
  `upload_ip` varchar(50) DEFAULT '' COMMENT '上传IP',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_upload_type` (`upload_type`),
  KEY `idx_file_ext` (`file_ext`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件上传表';

-- ========================================
-- 业务数据表
-- ========================================

-- 
-- 支付订单表 (优化字段定义)
-- 
CREATE TABLE `ds_pay_order` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` varchar(50) NOT NULL DEFAULT '' COMMENT '订单号',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `pay_type` varchar(20) NOT NULL DEFAULT '' COMMENT '支付方式',
  `pay_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态 (0:未支付 1:已支付 2:已退款)',
  `pay_time` int(11) UNSIGNED DEFAULT '0' COMMENT '支付时间',
  `trade_no` varchar(100) DEFAULT '' COMMENT '第三方交易号',
  `client_ip` varchar(50) DEFAULT '' COMMENT '客户端IP',
  `user_agent` varchar(500) DEFAULT '' COMMENT '用户代理',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_no` (`order_no`),
  KEY `idx_uid` (`uid`),
  KEY `idx_pay_status` (`pay_status`),
  KEY `idx_pay_time` (`pay_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付订单表';

--
-- 投诉举报表 (优化字段定义)
--
CREATE TABLE `ds_complain` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '投诉ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `vid` int(11) UNSIGNED DEFAULT '0' COMMENT '视频ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 (0:待处理 1:已处理)',
  `remark` varchar(500) DEFAULT '' COMMENT '投诉内容',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='投诉举报表';

--
-- 域名库表 (优化字段定义)
--
CREATE TABLE `ds_domain_lib` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '域名ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理ID',
  `domain` varchar(500) NOT NULL DEFAULT '' COMMENT '中转域名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `q_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '检测状态',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='域名库表';

--
-- 域名规则表 (优化字段定义)
--
CREATE TABLE `ds_domain_rule` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '域名类型 (1:主域名 2:炮灰域名)',
  `domain` varchar(500) NOT NULL DEFAULT '' COMMENT '落地域名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_type` (`type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='域名规则表';

--
-- 盒子管理表 (优化字段定义)
--
CREATE TABLE `ds_hezi` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '盒子ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `hezi_url` varchar(500) DEFAULT '' COMMENT '盒子外链',
  `short_url` varchar(500) DEFAULT '' COMMENT '短链接',
  `view_id` int(11) UNSIGNED DEFAULT '0' COMMENT '模板ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='盒子管理表';

--
-- 模板管理表 (优化字段定义)
--
CREATE TABLE `ds_muban` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '模板ID',
  `uid` int(11) UNSIGNED DEFAULT '0' COMMENT '用户ID',
  `title` varchar(200) DEFAULT '' COMMENT '模板标题',
  `muban` varchar(100) DEFAULT '' COMMENT '模板标识',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='模板管理表';

--
-- 通知公告表 (优化字段定义)
--
CREATE TABLE `ds_notify` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '通知ID',
  `creator_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建者ID',
  `title` varchar(200) DEFAULT '' COMMENT '公告标题',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '公告类型 (1:通知 2:公告)',
  `content` text COMMENT '公告内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_creator_id` (`creator_id`),
  KEY `idx_type` (`type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通知公告表';

--
-- 邀请码表 (优化字段定义)
--
CREATE TABLE `ds_number` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '邀请码ID',
  `number` varchar(50) NOT NULL DEFAULT '' COMMENT '邀请码',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理ID',
  `ua` int(11) UNSIGNED DEFAULT '0' COMMENT '激活用户ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 (0:未激活 1:已激活)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_number` (`number`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请码表';

--
-- 提现记录表 (优化字段定义)
--
CREATE TABLE `ds_outlay` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '提现ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 (0:未支付 1:已支付 2:已拒绝)',
  `image` varchar(500) DEFAULT '' COMMENT '收款码',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='提现记录表';

--
-- 支付展示表 (优化字段定义)
--
CREATE TABLE `ds_payed_show` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '展示ID',
  `ip` varchar(50) DEFAULT '' COMMENT 'IP地址',
  `createtime` int(11) UNSIGNED DEFAULT '0' COMMENT '创建时间',
  `expire` int(11) UNSIGNED DEFAULT '0' COMMENT '过期时间',
  `updatetime` int(11) UNSIGNED DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_ip` (`ip`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付展示表';

--
-- 支付配置表 (优化字段定义)
--
CREATE TABLE `ds_pay_setting` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '支付名称',
  `app_id` varchar(200) NOT NULL DEFAULT '' COMMENT '支付ID',
  `app_key` varchar(200) NOT NULL DEFAULT '' COMMENT '支付密钥',
  `pay_url` varchar(500) NOT NULL DEFAULT '' COMMENT '支付网关',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付配置表';

--
-- 点播券消费记录表 (优化字段定义)
--
CREATE TABLE `ds_point_decr` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `ua` varchar(100) DEFAULT '' COMMENT '用户标识',
  `vid` varchar(50) DEFAULT '' COMMENT '视频ID',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_ua` (`ua`),
  KEY `idx_vid` (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播券消费记录表';

--
-- 短视频日志表 (优化字段定义)
--
CREATE TABLE `ds_point_logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `uid` int(11) UNSIGNED DEFAULT '0' COMMENT '用户ID',
  `ip` varchar(50) DEFAULT '' COMMENT 'IP地址',
  `time` varchar(50) DEFAULT '' COMMENT '时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短视频日志表';

--
-- 价格配置表 (优化字段定义)
--
CREATE TABLE `ds_price` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '价格ID',
  `pay_model` varchar(50) DEFAULT '' COMMENT '支付模式',
  `is_dan` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否单片',
  `dan_fee` decimal(10,2) DEFAULT '0.00' COMMENT '单片金额',
  `is_day` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包天',
  `day_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包天金额',
  `is_week` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包周',
  `week_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包周金额',
  `is_month` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否包月',
  `month_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包月金额',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='价格配置表';

--
-- 抽单配置表 (优化字段定义)
--
CREATE TABLE `ds_quantity` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '抽单ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '代理ID',
  `initial` int(11) NOT NULL DEFAULT '0' COMMENT '初始值',
  `bottom` int(11) NOT NULL DEFAULT '0' COMMENT '倒数值',
  `bottom_all` tinyint(1) NOT NULL DEFAULT '1' COMMENT '全局倒数 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽单配置表';

-- ========================================
-- 系统权限管理表
-- ========================================

--
-- 权限角色表 (优化字段定义)
--
CREATE TABLE `ds_system_auth` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '权限名称',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限角色表';

--
-- 角色节点关系表 (优化字段定义)
--
CREATE TABLE `ds_system_auth_node` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '关系ID',
  `auth_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色ID',
  `node_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '节点ID',
  PRIMARY KEY (`id`),
  KEY `idx_auth_id` (`auth_id`),
  KEY `idx_node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色节点关系表';

--
-- 系统配置表 (优化字段定义)
--
CREATE TABLE `ds_system_config` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `value` text COMMENT '变量值',
  `remark` varchar(200) DEFAULT '' COMMENT '备注信息',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `idx_group` (`group`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表';

--
-- 权限节点表 (优化字段定义)
--
CREATE TABLE `ds_system_node` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '节点ID',
  `node` varchar(100) DEFAULT '' COMMENT '节点代码',
  `title` varchar(200) DEFAULT '' COMMENT '节点标题',
  `type` tinyint(1) NOT NULL DEFAULT '3' COMMENT '节点类型 (1:控制器 2:节点)',
  `is_auth` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启动RBAC权限控制',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_node` (`node`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限节点表';

--
-- 快捷入口表 (优化字段定义)
--
CREATE TABLE `ds_system_quick` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '快捷入口ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '快捷入口名称',
  `icon` varchar(100) DEFAULT '' COMMENT '图标',
  `href` varchar(500) DEFAULT '' COMMENT '快捷链接',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序权重',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用 1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='快捷入口表';

-- ========================================
-- 用户数据统计表
-- ========================================

--
-- 访问统计表 (优化字段定义)
--
CREATE TABLE `ds_tj` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '统计ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `ua` varchar(200) DEFAULT '' COMMENT '用户代理',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='访问统计表';

--
-- 用户资金日志表 (优化字段定义)
--
CREATE TABLE `ds_user_money_log` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `uid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `remark` varchar(500) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户资金日志表';

--
-- 用户点播券表 (优化字段定义)
--
CREATE TABLE `ds_user_point` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '点播券ID',
  `ua` varchar(100) DEFAULT '' COMMENT '用户标识',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '点播券数量',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_ua` (`ua`),
  KEY `idx_point` (`point`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户点播券表';

--
-- 综合统计表 (新增优化)
--
CREATE TABLE `ds_statistics` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '统计ID',
  `date` date NOT NULL COMMENT '统计日期',
  `hour` tinyint(2) UNSIGNED DEFAULT NULL COMMENT '小时（0-23，用于小时级统计）',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '统计类型（pv,uv,order,revenue等）',
  `target_id` int(11) UNSIGNED DEFAULT '0' COMMENT '目标ID（分类ID、用户ID等）',
  `target_type` varchar(20) DEFAULT '' COMMENT '目标类型（category,user,link等）',
  `pv` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '页面访问量',
  `uv` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '独立访客数',
  `ip_count` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'IP数量',
  `revenue` decimal(12,2) DEFAULT '0.00' COMMENT '收入金额',
  `order_count` int(11) UNSIGNED DEFAULT '0' COMMENT '订单数量',
  `conversion_rate` decimal(5,4) DEFAULT '0.0000' COMMENT '转化率',
  `extra_data` json DEFAULT NULL COMMENT '扩展数据（JSON格式）',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_date_hour_type_target` (`date`, `hour`, `type`, `target_id`, `target_type`),
  KEY `idx_date` (`date`),
  KEY `idx_type` (`type`),
  KEY `idx_target` (`target_id`, `target_type`),
  KEY `idx_date_type` (`date`, `type`),
  KEY `idx_revenue` (`revenue`),
  KEY `idx_order_count` (`order_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='综合统计表';

-- ========================================
-- 索引和约束
-- ========================================

-- 设置自增起始值
ALTER TABLE `ds_category` AUTO_INCREMENT = 1;
ALTER TABLE `ds_admin` AUTO_INCREMENT = 1;
ALTER TABLE `ds_config` AUTO_INCREMENT = 1;
ALTER TABLE `ds_stock` AUTO_INCREMENT = 1;
ALTER TABLE `ds_link` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_menu` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_uploadfile` AUTO_INCREMENT = 1;
ALTER TABLE `ds_pay_order` AUTO_INCREMENT = 1;
ALTER TABLE `ds_complain` AUTO_INCREMENT = 1;
ALTER TABLE `ds_domain_lib` AUTO_INCREMENT = 1;
ALTER TABLE `ds_domain_rule` AUTO_INCREMENT = 1;
ALTER TABLE `ds_hezi` AUTO_INCREMENT = 1;
ALTER TABLE `ds_muban` AUTO_INCREMENT = 1;
ALTER TABLE `ds_notify` AUTO_INCREMENT = 1;
ALTER TABLE `ds_number` AUTO_INCREMENT = 1;
ALTER TABLE `ds_outlay` AUTO_INCREMENT = 1;
ALTER TABLE `ds_payed_show` AUTO_INCREMENT = 1;
ALTER TABLE `ds_pay_setting` AUTO_INCREMENT = 1;
ALTER TABLE `ds_point_decr` AUTO_INCREMENT = 1;
ALTER TABLE `ds_point_logs` AUTO_INCREMENT = 1;
ALTER TABLE `ds_price` AUTO_INCREMENT = 1;
ALTER TABLE `ds_quantity` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_auth` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_auth_node` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_config` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_node` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_quick` AUTO_INCREMENT = 1;
ALTER TABLE `ds_tj` AUTO_INCREMENT = 1;
ALTER TABLE `ds_user_money_log` AUTO_INCREMENT = 1;
ALTER TABLE `ds_user_point` AUTO_INCREMENT = 1;
ALTER TABLE `ds_statistics` AUTO_INCREMENT = 1;

-- ========================================
-- 外键约束（可选，根据业务需求启用）
-- ========================================

-- 分类表父子关系约束
-- ALTER TABLE `ds_category` ADD CONSTRAINT `fk_category_pid` FOREIGN KEY (`pid`) REFERENCES `ds_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 资源库分类关联约束
-- ALTER TABLE `ds_stock` ADD CONSTRAINT `fk_stock_cid` FOREIGN KEY (`cid`) REFERENCES `ds_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 链接分类关联约束
-- ALTER TABLE `ds_link` ADD CONSTRAINT `fk_link_cid` FOREIGN KEY (`cid`) REFERENCES `ds_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 系统菜单父子关系约束
-- ALTER TABLE `ds_system_menu` ADD CONSTRAINT `fk_menu_pid` FOREIGN KEY (`pid`) REFERENCES `ds_system_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- 角色节点关系约束
-- ALTER TABLE `ds_system_auth_node` ADD CONSTRAINT `fk_auth_node_auth` FOREIGN KEY (`auth_id`) REFERENCES `ds_system_auth` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
-- ALTER TABLE `ds_system_auth_node` ADD CONSTRAINT `fk_auth_node_node` FOREIGN KEY (`node_id`) REFERENCES `ds_system_node` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- ========================================
-- 数据完整性检查触发器（高级功能）
-- ========================================

-- 分类删除检查触发器
DELIMITER $$
CREATE TRIGGER `tr_category_delete_check`
BEFORE DELETE ON `ds_category`
FOR EACH ROW
BEGIN
    DECLARE child_count INT DEFAULT 0;
    DECLARE stock_count INT DEFAULT 0;
    DECLARE link_count INT DEFAULT 0;

    -- 检查是否有子分类
    SELECT COUNT(*) INTO child_count FROM `ds_category` WHERE `pid` = OLD.`id`;
    IF child_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '该分类下还有子分类，无法删除';
    END IF;

    -- 检查是否有关联的资源
    SELECT COUNT(*) INTO stock_count FROM `ds_stock` WHERE `cid` = OLD.`id`;
    IF stock_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '该分类下还有资源，无法删除';
    END IF;

    -- 检查是否有关联的链接
    SELECT COUNT(*) INTO link_count FROM `ds_link` WHERE `cid` = OLD.`id`;
    IF link_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = '该分类下还有链接，无法删除';
    END IF;
END$$
DELIMITER ;

-- 统计数据自动更新触发器
DELIMITER $$
CREATE TRIGGER `tr_link_view_update`
AFTER UPDATE ON `ds_link`
FOR EACH ROW
BEGIN
    IF NEW.`view_count` != OLD.`view_count` THEN
        INSERT INTO `ds_statistics` (`date`, `type`, `target_id`, `pv`, `create_time`, `update_time`)
        VALUES (CURDATE(), 'link_view', NEW.`id`, NEW.`view_count` - OLD.`view_count`, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
        ON DUPLICATE KEY UPDATE
        `pv` = `pv` + (NEW.`view_count` - OLD.`view_count`),
        `update_time` = UNIX_TIMESTAMP();
    END IF;
END$$
DELIMITER ;

-- 恢复外键检查
SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
