-- =====================================================
-- 视频奖励平台数据库安装文件
-- 
-- 版本: 1.0.1 (重构版)
-- 创建时间: 2025-07-20
-- 作者: 迪迦奥特曼之父
-- 
-- 重构改进:
-- 1. 实现动态表前缀支持
-- 2. 添加标准字段(created_at, updated_at, deleted_at, status)
-- 3. 优化字段类型和索引设计
-- 4. 增强数据安全性和完整性约束
-- 5. 符合现代数据库设计标准
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- =====================================================
-- 系统管理员表
-- =====================================================
CREATE TABLE `{prefix}system_admin` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级代理ID',
  `auth_ids` varchar(255) DEFAULT NULL COMMENT '角色权限ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `password` char(64) NOT NULL COMMENT '密码哈希',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `avatar` varchar(255) DEFAULT '/static/admin/images/default-avatar.jpg' COMMENT '头像',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `revenue` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '总收益',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '佣金比例(%)',
  `withdrawal_fee` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '提现手续费(%)',
  `login_count` int(11) NOT NULL DEFAULT '0' COMMENT '登录次数',
  `last_login_ip` varchar(45) DEFAULT NULL COMMENT '最后登录IP',
  `last_login_time` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `remark` text COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`),
  KEY `idx_pid` (`pid`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统管理员表';

-- =====================================================
-- 系统权限表
-- =====================================================
CREATE TABLE `{prefix}system_auth` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `title` varchar(50) NOT NULL COMMENT '权限名称',
  `description` varchar(255) DEFAULT NULL COMMENT '权限描述',
  `auth_key` varchar(100) NOT NULL COMMENT '权限标识',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_auth_key` (`auth_key`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统权限表';

-- =====================================================
-- 系统菜单表
-- =====================================================
CREATE TABLE `{prefix}system_menu` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` bigint(20) NOT NULL DEFAULT '0' COMMENT '父级菜单ID',
  `title` varchar(100) NOT NULL COMMENT '菜单名称',
  `icon` varchar(100) DEFAULT NULL COMMENT '菜单图标',
  `href` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `target` varchar(20) NOT NULL DEFAULT '_self' COMMENT '打开方式',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示(0:隐藏,1:显示)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统菜单表';

-- =====================================================
-- 系统配置表
-- =====================================================
CREATE TABLE `{prefix}system_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `name` varchar(50) NOT NULL COMMENT '配置名称',
  `group` varchar(30) NOT NULL DEFAULT 'basic' COMMENT '配置分组',
  `title` varchar(100) NOT NULL COMMENT '配置标题',
  `value` text COMMENT '配置值',
  `type` varchar(20) NOT NULL DEFAULT 'input' COMMENT '配置类型',
  `options` text COMMENT '配置选项',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `idx_group` (`group`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表';

-- =====================================================
-- 视频分类表
-- =====================================================
CREATE TABLE `{prefix}category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `description` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `image` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='视频分类表';

-- =====================================================
-- 视频资源表
-- =====================================================
CREATE TABLE `{prefix}video` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '视频ID',
  `uid` int(11) NOT NULL COMMENT '发布者ID',
  `category_id` int(11) DEFAULT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL COMMENT '视频标题',
  `description` text COMMENT '视频描述',
  `cover_image` varchar(255) DEFAULT NULL COMMENT '封面图片',
  `video_url` varchar(500) DEFAULT NULL COMMENT '视频地址',
  `price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '观看价格',
  `view_count` int(11) NOT NULL DEFAULT '0' COMMENT '观看次数',
  `like_count` int(11) NOT NULL DEFAULT '0' COMMENT '点赞次数',
  `pay_count` int(11) NOT NULL DEFAULT '0' COMMENT '付费次数',
  `duration` int(11) DEFAULT NULL COMMENT '视频时长(秒)',
  `file_size` bigint(20) DEFAULT NULL COMMENT '文件大小(字节)',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐(0:否,1:是)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用,2:审核中)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='视频资源表';

-- =====================================================
-- 支付订单表
-- =====================================================
CREATE TABLE `{prefix}pay_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` varchar(32) NOT NULL COMMENT '订单号',
  `uid` int(11) NOT NULL COMMENT '代理ID',
  `video_id` int(11) NOT NULL COMMENT '视频ID',
  `video_title` varchar(255) DEFAULT NULL COMMENT '视频标题',
  `amount` decimal(10,2) NOT NULL COMMENT '订单金额',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `pay_channel` varchar(50) DEFAULT NULL COMMENT '支付渠道',
  `pay_method` varchar(20) DEFAULT NULL COMMENT '支付方式',
  `transaction_id` varchar(64) DEFAULT NULL COMMENT '第三方交易号',
  `client_ip` varchar(45) DEFAULT NULL COMMENT '客户端IP',
  `user_agent` varchar(500) DEFAULT NULL COMMENT '用户代理',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态(0:待支付,1:已支付,2:已取消,3:已退款)',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_no` (`order_no`),
  KEY `idx_uid` (`uid`),
  KEY `idx_video_id` (`video_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付订单表';

-- =====================================================
-- 提现记录表
-- =====================================================
CREATE TABLE `{prefix}withdrawal` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '提现ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `amount` decimal(10,2) NOT NULL COMMENT '提现金额',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `actual_amount` decimal(10,2) NOT NULL COMMENT '实际到账金额',
  `payment_method` varchar(20) NOT NULL COMMENT '提现方式(alipay:支付宝,wechat:微信,bank:银行卡)',
  `payment_account` varchar(100) NOT NULL COMMENT '收款账户',
  `payment_name` varchar(50) DEFAULT NULL COMMENT '收款人姓名',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:待审核,1:已通过,2:已拒绝,3:已完成)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `processed_at` timestamp NULL DEFAULT NULL COMMENT '处理时间',
  `completed_at` timestamp NULL DEFAULT NULL COMMENT '完成时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='提现记录表';

-- =====================================================
-- 系统通知表
-- =====================================================
CREATE TABLE `{prefix}notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '通知ID',
  `title` varchar(255) NOT NULL COMMENT '通知标题',
  `content` text NOT NULL COMMENT '通知内容',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '通知类型(1:系统通知,2:公告,3:活动)',
  `target_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '目标类型(0:全部,1:管理员,2:代理)',
  `target_ids` text COMMENT '目标用户ID(JSON格式)',
  `is_popup` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否弹窗(0:否,1:是)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统通知表';

-- =====================================================
-- 操作日志表
-- =====================================================
CREATE TABLE `{prefix}operation_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `user_id` int(11) DEFAULT NULL COMMENT '操作用户ID',
  `username` varchar(50) DEFAULT NULL COMMENT '操作用户名',
  `module` varchar(50) NOT NULL COMMENT '操作模块',
  `action` varchar(50) NOT NULL COMMENT '操作动作',
  `description` varchar(255) DEFAULT NULL COMMENT '操作描述',
  `request_url` varchar(255) DEFAULT NULL COMMENT '请求URL',
  `request_method` varchar(10) DEFAULT NULL COMMENT '请求方法',
  `request_params` text COMMENT '请求参数',
  `response_data` text COMMENT '响应数据',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP地址',
  `user_agent` varchar(500) DEFAULT NULL COMMENT '用户代理',
  `execution_time` int(11) DEFAULT NULL COMMENT '执行时间(毫秒)',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态(0:失败,1:成功)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_module` (`module`),
  KEY `idx_action` (`action`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='操作日志表';

-- =====================================================
-- 文件上传表
-- =====================================================
CREATE TABLE `{prefix}upload_file` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `user_id` int(11) DEFAULT NULL COMMENT '上传用户ID',
  `original_name` varchar(255) NOT NULL COMMENT '原始文件名',
  `file_name` varchar(255) NOT NULL COMMENT '存储文件名',
  `file_path` varchar(500) NOT NULL COMMENT '文件路径',
  `file_url` varchar(500) NOT NULL COMMENT '访问URL',
  `file_size` bigint(20) NOT NULL COMMENT '文件大小(字节)',
  `file_type` varchar(50) NOT NULL COMMENT '文件类型',
  `mime_type` varchar(100) NOT NULL COMMENT 'MIME类型',
  `file_ext` varchar(10) NOT NULL COMMENT '文件扩展名',
  `file_hash` varchar(64) NOT NULL COMMENT '文件哈希值',
  `storage_type` varchar(20) NOT NULL DEFAULT 'local' COMMENT '存储类型(local:本地,oss:阿里云,cos:腾讯云)',
  `is_temp` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否临时文件(0:否,1:是)',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_file_hash` (`file_hash`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_file_type` (`file_type`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件上传表';

-- =====================================================
-- 初始数据插入
-- =====================================================

-- 插入默认管理员账户
INSERT INTO `{prefix}system_admin` (
  `id`, `pid`, `username`, `password`, `nickname`, `email`,
  `balance`, `revenue`, `status`, `remark`
) VALUES (
  1, 0, 'admin',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  '超级管理员', 'admin@example.com',
  '0.00', '0.00', 1, '系统默认超级管理员账户'
);

-- 插入测试代理账户
INSERT INTO `{prefix}system_admin` (
  `id`, `pid`, `username`, `password`, `nickname`, `email`,
  `balance`, `revenue`, `commission_rate`, `status`, `remark`
) VALUES (
  2, 1, 'agent001',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  '测试代理', 'agent001@example.com',
  '1000.00', '500.00', '10.00', 1, '测试代理账户'
);

-- 插入系统权限
INSERT INTO `{prefix}system_auth` (`id`, `title`, `description`, `auth_key`, `sort`, `status`) VALUES
(1, '超级管理员', '拥有所有权限', 'super_admin', 1, 1),
(2, '系统管理员', '系统管理相关权限', 'system_admin', 2, 1),
(3, '内容管理员', '内容管理相关权限', 'content_admin', 3, 1),
(4, '财务管理员', '财务管理相关权限', 'finance_admin', 4, 1),
(5, '普通代理', '代理基础权限', 'agent', 5, 1);

-- 插入系统菜单
INSERT INTO `{prefix}system_menu` (`id`, `pid`, `title`, `icon`, `href`, `sort`, `status`) VALUES
(1, 0, '系统管理', 'layui-icon-set', '', 1, 1),
(2, 1, '管理员管理', 'layui-icon-user', 'admin/system_admin/index', 1, 1),
(3, 1, '权限管理', 'layui-icon-vercode', 'admin/system_auth/index', 2, 1),
(4, 1, '菜单管理', 'layui-icon-menu-fill', 'admin/system_menu/index', 3, 1),
(5, 1, '系统配置', 'layui-icon-set-sm', 'admin/system_config/index', 4, 1),
(6, 0, '内容管理', 'layui-icon-template-1', '', 2, 1),
(7, 6, '分类管理', 'layui-icon-tabs', 'admin/category/index', 1, 1),
(8, 6, '视频管理', 'layui-icon-play', 'admin/video/index', 2, 1),
(9, 0, '财务管理', 'layui-icon-dollar', '', 3, 1),
(10, 9, '订单管理', 'layui-icon-form', 'admin/pay_order/index', 1, 1),
(11, 9, '提现管理', 'layui-icon-transfer', 'admin/withdrawal/index', 2, 1),
(12, 0, '系统工具', 'layui-icon-util', '', 4, 1),
(13, 12, '操作日志', 'layui-icon-file', 'admin/operation_log/index', 1, 1),
(14, 12, '文件管理', 'layui-icon-upload', 'admin/upload_file/index', 2, 1);

-- 插入系统配置
INSERT INTO `{prefix}system_config` (`name`, `group`, `title`, `value`, `type`, `sort`) VALUES
('site_name', 'basic', '网站名称', '视频奖励平台', 'input', 1),
('site_logo', 'basic', '网站LOGO', '/static/images/logo.png', 'image', 2),
('site_keywords', 'basic', '网站关键词', '视频,奖励,平台', 'input', 3),
('site_description', 'basic', '网站描述', '专业的视频奖励平台', 'textarea', 4),
('admin_url', 'basic', '后台入口', 'admin', 'input', 5),
('upload_max_size', 'upload', '上传文件最大大小(MB)', '100', 'number', 1),
('upload_allowed_ext', 'upload', '允许上传的文件类型', 'jpg,jpeg,png,gif,mp4,avi', 'input', 2),
('pay_min_amount', 'payment', '最小支付金额', '1.00', 'number', 1),
('withdraw_min_amount', 'payment', '最小提现金额', '10.00', 'number', 2),
('withdraw_fee_rate', 'payment', '提现手续费率(%)', '2.00', 'number', 3);

-- 插入默认分类
INSERT INTO `{prefix}category` (`id`, `name`, `description`, `sort`, `status`) VALUES
(1, '热门推荐', '热门推荐视频分类', 1, 1),
(2, '最新上传', '最新上传视频分类', 2, 1),
(3, '精品内容', '精品内容视频分类', 3, 1),
(4, '免费观看', '免费观看视频分类', 4, 1);

-- 插入系统通知
INSERT INTO `{prefix}notification` (`title`, `content`, `type`, `target_type`, `status`) VALUES
('欢迎使用视频奖励平台', '欢迎使用视频奖励平台！请仔细阅读使用说明，如有问题请联系客服。', 1, 0, 1),
('系统维护通知', '系统将于每周日凌晨2:00-4:00进行例行维护，期间可能影响正常使用，请合理安排时间。', 2, 0, 1);

-- =====================================================
-- 设置自增起始值
-- =====================================================
ALTER TABLE `{prefix}system_admin` AUTO_INCREMENT = 100;
ALTER TABLE `{prefix}system_auth` AUTO_INCREMENT = 10;
ALTER TABLE `{prefix}system_menu` AUTO_INCREMENT = 100;
ALTER TABLE `{prefix}system_config` AUTO_INCREMENT = 100;
ALTER TABLE `{prefix}category` AUTO_INCREMENT = 10;
ALTER TABLE `{prefix}video` AUTO_INCREMENT = 1000;
ALTER TABLE `{prefix}pay_order` AUTO_INCREMENT = 10000;
ALTER TABLE `{prefix}withdrawal` AUTO_INCREMENT = 1000;
ALTER TABLE `{prefix}notification` AUTO_INCREMENT = 100;
ALTER TABLE `{prefix}operation_log` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}upload_file` AUTO_INCREMENT = 1;

-- =====================================================
-- 添加外键约束（在所有表创建完成后）
-- =====================================================
ALTER TABLE `{prefix}video` ADD CONSTRAINT `fk_video_category` FOREIGN KEY (`category_id`) REFERENCES `{prefix}category` (`id`) ON DELETE SET NULL;
ALTER TABLE `{prefix}pay_order` ADD CONSTRAINT `fk_order_video` FOREIGN KEY (`video_id`) REFERENCES `{prefix}video` (`id`) ON DELETE CASCADE;

-- =====================================================
-- 恢复SQL设置
-- =====================================================
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

COMMIT;

-- =====================================================
-- 安装完成
-- =====================================================
-- 数据库结构创建完成
-- 默认管理员账户: admin / password
-- 测试代理账户: agent001 / password
-- 请及时修改默认密码以确保系统安全
-- =====================================================
