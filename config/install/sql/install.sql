-- =====================================================
-- 视频奖励平台数据库安装文件 v3.0.0
-- =====================================================
-- 创建时间: 2025-01-21
-- 设计原则: 全新系统构建，不考虑向后兼容性
-- 技术标准: 遵循项目重构规则文档第6章数据库设计规范
-- 支持特性: 动态表前缀、现代化架构、完整约束
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
-- 1. 代理管理模块
-- =====================================================

-- 代理用户表
CREATE TABLE `{prefix}agents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '代理ID',
  `parent_id` bigint(20) UNSIGNED DEFAULT 0 COMMENT '上级代理ID',
  `username` varchar(50) NOT NULL COMMENT '用户名',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `password_hash` varchar(255) NOT NULL COMMENT '密码哈希',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像URL',
  `role_type` enum('super_admin','agent') NOT NULL DEFAULT 'agent' COMMENT '角色类型',
  `status` enum('active','inactive','banned') NOT NULL DEFAULT 'active' COMMENT '状态',
  `device_fingerprint` varchar(255) DEFAULT NULL COMMENT '设备指纹',
  `last_login_at` timestamp NULL DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(45) DEFAULT NULL COMMENT '最后登录IP',
  `login_count` int(11) UNSIGNED DEFAULT 0 COMMENT '登录次数',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_role_type` (`role_type`),
  KEY `idx_status` (`status`),
  KEY `idx_device_fingerprint` (`device_fingerprint`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理用户表';

-- 代理配置表
CREATE TABLE `{prefix}agent_configs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `commission_rate` decimal(5,2) DEFAULT 0.00 COMMENT '佣金比例(%)',
  `withdrawal_fee_rate` decimal(5,2) DEFAULT 0.00 COMMENT '提现手续费率(%)',
  `min_withdrawal_amount` decimal(10,2) DEFAULT 0.00 COMMENT '最小提现金额',
  `daily_enabled` tinyint(1) DEFAULT 1 COMMENT '是否启用包天',
  `weekly_enabled` tinyint(1) DEFAULT 1 COMMENT '是否启用包周', 
  `monthly_enabled` tinyint(1) DEFAULT 1 COMMENT '是否启用包月',
  `daily_price` decimal(8,2) DEFAULT 0.00 COMMENT '包天价格',
  `weekly_price` decimal(8,2) DEFAULT 0.00 COMMENT '包周价格',
  `monthly_price` decimal(8,2) DEFAULT 0.00 COMMENT '包月价格',
  `single_price` decimal(8,2) DEFAULT 0.00 COMMENT '单片价格',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_agent_id` (`agent_id`),
  CONSTRAINT `fk_agent_configs_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理配置表';

-- 代理余额表
CREATE TABLE `{prefix}agent_balances` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '余额ID',
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `available_balance` decimal(15,2) DEFAULT 0.00 COMMENT '可用余额',
  `frozen_balance` decimal(15,2) DEFAULT 0.00 COMMENT '冻结余额',
  `total_revenue` decimal(15,2) DEFAULT 0.00 COMMENT '总收益',
  `total_withdrawal` decimal(15,2) DEFAULT 0.00 COMMENT '总提现',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_agent_id` (`agent_id`),
  CONSTRAINT `fk_agent_balances_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理余额表';

-- 余额变动日志表
CREATE TABLE `{prefix}balance_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `type` enum('income','withdrawal','commission','refund','adjustment') NOT NULL COMMENT '变动类型',
  `amount` decimal(15,2) NOT NULL COMMENT '变动金额',
  `balance_before` decimal(15,2) NOT NULL COMMENT '变动前余额',
  `balance_after` decimal(15,2) NOT NULL COMMENT '变动后余额',
  `description` varchar(255) DEFAULT NULL COMMENT '变动描述',
  `reference_type` varchar(50) DEFAULT NULL COMMENT '关联类型',
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '关联ID',
  `operator_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '操作员ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_type` (`type`),
  KEY `idx_reference` (`reference_type`, `reference_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_balance_logs_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='余额变动日志表';

-- =====================================================
-- 2. 权限管理模块
-- =====================================================

-- 角色表
CREATE TABLE `{prefix}roles` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name` varchar(50) NOT NULL COMMENT '角色名称',
  `slug` varchar(50) NOT NULL COMMENT '角色标识',
  `description` varchar(255) DEFAULT NULL COMMENT '角色描述',
  `is_system` tinyint(1) DEFAULT 0 COMMENT '是否系统角色',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT '状态',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色表';

-- 权限表
CREATE TABLE `{prefix}permissions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `name` varchar(100) NOT NULL COMMENT '权限名称',
  `slug` varchar(100) NOT NULL COMMENT '权限标识',
  `module` varchar(50) NOT NULL COMMENT '所属模块',
  `description` varchar(255) DEFAULT NULL COMMENT '权限描述',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT '状态',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`),
  KEY `idx_module` (`module`),
  KEY `idx_status` (`status`),
  KEY `idx_module_status` (`module`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限表';

-- 角色权限关联表
CREATE TABLE `{prefix}role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色ID',
  `permission_id` int(11) UNSIGNED NOT NULL COMMENT '权限ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_role_permission` (`role_id`, `permission_id`),
  CONSTRAINT `fk_role_permissions_role_id` FOREIGN KEY (`role_id`) REFERENCES `{prefix}roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_role_permissions_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `{prefix}permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色权限关联表';

-- 代理角色关联表
CREATE TABLE `{prefix}agent_roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_agent_role` (`agent_id`, `role_id`),
  CONSTRAINT `fk_agent_roles_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_agent_roles_role_id` FOREIGN KEY (`role_id`) REFERENCES `{prefix}roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理角色关联表';

-- =====================================================
-- 3. 内容管理模块
-- =====================================================

-- 内容分类表
CREATE TABLE `{prefix}categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `parent_id` int(11) UNSIGNED DEFAULT 0 COMMENT '父分类ID',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `slug` varchar(100) NOT NULL COMMENT '分类标识',
  `description` varchar(255) DEFAULT NULL COMMENT '分类描述',
  `image` varchar(255) DEFAULT NULL COMMENT '分类图片',
  `sort_order` int(11) DEFAULT 0 COMMENT '排序权重',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT '状态',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_slug` (`slug`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容分类表';

-- 内容表
CREATE TABLE `{prefix}contents` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '内容ID',
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT '发布者ID',
  `category_id` int(11) UNSIGNED NOT NULL COMMENT '分类ID',
  `title` varchar(255) NOT NULL COMMENT '内容标题',
  `description` text DEFAULT NULL COMMENT '内容描述',
  `cover_image` varchar(255) DEFAULT NULL COMMENT '封面图片',
  `video_url` varchar(500) DEFAULT NULL COMMENT '视频地址',
  `duration` int(11) DEFAULT 0 COMMENT '视频时长(秒)',
  `file_size` bigint(20) DEFAULT 0 COMMENT '文件大小(字节)',
  `view_count` int(11) UNSIGNED DEFAULT 0 COMMENT '观看次数',
  `like_count` int(11) UNSIGNED DEFAULT 0 COMMENT '点赞次数',
  `price_type` enum('free','single','daily','weekly','monthly') NOT NULL DEFAULT 'single' COMMENT '价格类型',
  `price` decimal(8,2) DEFAULT 0.00 COMMENT '价格',
  `status` enum('draft','published','reviewing','rejected','banned') NOT NULL DEFAULT 'draft' COMMENT '状态',
  `published_at` timestamp NULL DEFAULT NULL COMMENT '发布时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`),
  KEY `idx_price_type` (`price_type`),
  KEY `idx_published_at` (`published_at`),
  KEY `idx_view_count` (`view_count`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_deleted_at` (`deleted_at`),
  CONSTRAINT `fk_contents_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_contents_category_id` FOREIGN KEY (`category_id`) REFERENCES `{prefix}categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='内容表';

-- =====================================================
-- 4. 支付订单模块
-- =====================================================

-- 支付渠道表
CREATE TABLE `{prefix}payment_channels` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '渠道ID',
  `name` varchar(100) NOT NULL COMMENT '渠道名称',
  `code` varchar(50) NOT NULL COMMENT '渠道代码',
  `config` json DEFAULT NULL COMMENT '渠道配置',
  `fee_rate` decimal(5,4) DEFAULT 0.0000 COMMENT '手续费率',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT '状态',
  `sort_order` int(11) DEFAULT 0 COMMENT '排序权重',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_code` (`code`),
  KEY `idx_status` (`status`),
  KEY `idx_sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付渠道表';

-- 支付订单表
CREATE TABLE `{prefix}payment_orders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` varchar(64) NOT NULL COMMENT '订单号',
  `agent_id` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `content_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '内容ID',
  `channel_id` int(11) UNSIGNED NOT NULL COMMENT '支付渠道ID',
  `payment_method` varchar(50) NOT NULL COMMENT '支付方式',
  `order_type` enum('single','daily','weekly','monthly','recharge') NOT NULL COMMENT '订单类型',
  `amount` decimal(10,2) NOT NULL COMMENT '订单金额',
  `actual_amount` decimal(10,2) NOT NULL COMMENT '实际支付金额',
  `commission_amount` decimal(10,2) DEFAULT 0.00 COMMENT '佣金金额',
  `fee_amount` decimal(10,2) DEFAULT 0.00 COMMENT '手续费金额',
  `currency` varchar(10) DEFAULT 'CNY' COMMENT '货币类型',
  `status` enum('pending','paid','failed','cancelled','refunded') NOT NULL DEFAULT 'pending' COMMENT '订单状态',
  `payment_time` timestamp NULL DEFAULT NULL COMMENT '支付时间',
  `expire_time` timestamp NULL DEFAULT NULL COMMENT '过期时间',
  `client_ip` varchar(45) DEFAULT NULL COMMENT '客户端IP',
  `user_agent` varchar(500) DEFAULT NULL COMMENT '用户代理',
  `device_fingerprint` varchar(255) DEFAULT NULL COMMENT '设备指纹',
  `third_party_order_no` varchar(100) DEFAULT NULL COMMENT '第三方订单号',
  `third_party_response` json DEFAULT NULL COMMENT '第三方响应',
  `refund_amount` decimal(10,2) DEFAULT 0.00 COMMENT '退款金额',
  `refund_time` timestamp NULL DEFAULT NULL COMMENT '退款时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_no` (`order_no`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_channel_id` (`channel_id`),
  KEY `idx_status` (`status`),
  KEY `idx_order_type` (`order_type`),
  KEY `idx_payment_time` (`payment_time`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_payment_orders_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_payment_orders_content_id` FOREIGN KEY (`content_id`) REFERENCES `{prefix}contents` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_payment_orders_channel_id` FOREIGN KEY (`channel_id`) REFERENCES `{prefix}payment_channels` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付订单表';

-- 用户购买记录表
CREATE TABLE `{prefix}user_purchases` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '购买记录ID',
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT '订单ID',
  `device_fingerprint` varchar(255) NOT NULL COMMENT '设备指纹',
  `content_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '内容ID',
  `purchase_type` enum('single','daily','weekly','monthly') NOT NULL COMMENT '购买类型',
  `expire_time` timestamp NULL DEFAULT NULL COMMENT '过期时间',
  `view_count` int(11) UNSIGNED DEFAULT 0 COMMENT '观看次数',
  `last_view_time` timestamp NULL DEFAULT NULL COMMENT '最后观看时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_device_fingerprint` (`device_fingerprint`),
  KEY `idx_content_id` (`content_id`),
  KEY `idx_purchase_type` (`purchase_type`),
  KEY `idx_expire_time` (`expire_time`),
  CONSTRAINT `fk_user_purchases_order_id` FOREIGN KEY (`order_id`) REFERENCES `{prefix}payment_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_purchases_content_id` FOREIGN KEY (`content_id`) REFERENCES `{prefix}contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户购买记录表';

-- =====================================================
-- 5. 系统配置模块
-- =====================================================

-- 系统配置表
CREATE TABLE `{prefix}system_configs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `group_name` varchar(50) NOT NULL COMMENT '配置分组',
  `config_key` varchar(100) NOT NULL COMMENT '配置键',
  `config_value` text DEFAULT NULL COMMENT '配置值',
  `config_type` enum('string','integer','decimal','boolean','json','text') NOT NULL DEFAULT 'string' COMMENT '配置类型',
  `description` varchar(255) DEFAULT NULL COMMENT '配置描述',
  `is_public` tinyint(1) DEFAULT 0 COMMENT '是否公开配置',
  `sort_order` int(11) DEFAULT 0 COMMENT '排序权重',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_group_key` (`group_name`, `config_key`),
  KEY `idx_group_name` (`group_name`),
  KEY `idx_is_public` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表';

-- 系统日志表
CREATE TABLE `{prefix}system_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `level` enum('emergency','alert','critical','error','warning','notice','info','debug') NOT NULL COMMENT '日志级别',
  `module` varchar(50) NOT NULL COMMENT '模块名称',
  `action` varchar(100) NOT NULL COMMENT '操作动作',
  `message` text NOT NULL COMMENT '日志消息',
  `context` json DEFAULT NULL COMMENT '上下文数据',
  `agent_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '操作员ID',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP地址',
  `user_agent` varchar(500) DEFAULT NULL COMMENT '用户代理',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_level` (`level`),
  KEY `idx_module` (`module`),
  KEY `idx_agent_id` (`agent_id`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_system_logs_agent_id` FOREIGN KEY (`agent_id`) REFERENCES `{prefix}agents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统日志表';

-- 系统通知表
CREATE TABLE `{prefix}system_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '通知ID',
  `title` varchar(255) NOT NULL COMMENT '通知标题',
  `content` text NOT NULL COMMENT '通知内容',
  `type` enum('system','announcement','warning','maintenance') NOT NULL DEFAULT 'system' COMMENT '通知类型',
  `target_type` enum('all','role','agent') NOT NULL DEFAULT 'all' COMMENT '目标类型',
  `target_ids` json DEFAULT NULL COMMENT '目标ID列表',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal' COMMENT '优先级',
  `is_popup` tinyint(1) DEFAULT 0 COMMENT '是否弹窗显示',
  `start_time` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `end_time` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `status` enum('draft','published','expired') NOT NULL DEFAULT 'draft' COMMENT '状态',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT '创建者ID',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_target_type` (`target_type`),
  KEY `idx_status` (`status`),
  KEY `idx_start_time` (`start_time`),
  KEY `idx_end_time` (`end_time`),
  KEY `idx_created_by` (`created_by`),
  CONSTRAINT `fk_system_notifications_created_by` FOREIGN KEY (`created_by`) REFERENCES `{prefix}agents` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统通知表';

-- =====================================================
-- 6. 初始数据插入
-- =====================================================

-- 插入默认超级管理员 (ID=1)
INSERT INTO `{prefix}agents` (`id`, `username`, `email`, `password_hash`, `role_type`, `status`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active', NOW());

-- 插入默认代理配置
INSERT INTO `{prefix}agent_configs` (`agent_id`, `commission_rate`, `withdrawal_fee_rate`, `min_withdrawal_amount`, `daily_price`, `weekly_price`, `monthly_price`, `single_price`) VALUES
(1, 0.00, 0.00, 100.00, 28.00, 68.00, 138.00, 6.00);

-- 插入默认余额记录
INSERT INTO `{prefix}agent_balances` (`agent_id`, `available_balance`, `frozen_balance`, `total_revenue`, `total_withdrawal`) VALUES
(1, 0.00, 0.00, 0.00, 0.00);

-- 插入默认角色
INSERT INTO `{prefix}roles` (`id`, `name`, `slug`, `description`, `is_system`, `status`) VALUES
(1, '超级管理员', 'super_admin', '系统超级管理员，拥有所有权限', 1, 'active'),
(2, '代理用户', 'agent', '普通代理用户，拥有基础业务权限', 1, 'active');

-- 插入默认权限
INSERT INTO `{prefix}permissions` (`name`, `slug`, `module`, `description`) VALUES
('查看仪表盘', 'dashboard.view', 'dashboard', '查看系统仪表盘'),
('用户管理', 'agents.manage', 'agents', '管理代理用户'),
('内容管理', 'contents.manage', 'contents', '管理内容资源'),
('订单管理', 'orders.manage', 'orders', '管理支付订单'),
('财务管理', 'finance.manage', 'finance', '管理财务数据'),
('系统配置', 'system.config', 'system', '管理系统配置'),
('日志查看', 'logs.view', 'system', '查看系统日志');

-- 插入角色权限关联 (超级管理员拥有所有权限)
INSERT INTO `{prefix}role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7),
(2, 1), (2, 3), (2, 4), (2, 5);

-- 插入用户角色关联
INSERT INTO `{prefix}agent_roles` (`agent_id`, `role_id`) VALUES
(1, 1);

-- 插入默认分类
INSERT INTO `{prefix}categories` (`name`, `slug`, `description`, `status`, `sort_order`) VALUES
('默认分类', 'default', '系统默认分类', 'active', 0),
('热门推荐', 'hot', '热门推荐内容', 'active', 1),
('最新发布', 'latest', '最新发布内容', 'active', 2);

-- 插入默认支付渠道
INSERT INTO `{prefix}payment_channels` (`name`, `code`, `config`, `fee_rate`, `status`, `sort_order`) VALUES
('支付宝', 'alipay', '{"app_id":"","private_key":"","public_key":""}', 0.0060, 'active', 1),
('微信支付', 'wechat', '{"app_id":"","mch_id":"","key":""}', 0.0060, 'active', 2),
('银联支付', 'unionpay', '{"merchant_id":"","key":""}', 0.0080, 'active', 3);

-- 插入默认系统配置
INSERT INTO `{prefix}system_configs` (`group_name`, `config_key`, `config_value`, `config_type`, `description`, `is_public`) VALUES
('basic', 'site_name', '视频奖励平台', 'string', '网站名称', 1),
('basic', 'site_logo', '/static/images/logo.png', 'string', '网站Logo', 1),
('basic', 'site_description', '专业的视频奖励平台', 'string', '网站描述', 1),
('basic', 'admin_url', 'admin', 'string', '后台管理URL', 0),
('payment', 'default_currency', 'CNY', 'string', '默认货币', 0),
('payment', 'min_recharge_amount', '10.00', 'decimal', '最小充值金额', 0),
('payment', 'max_recharge_amount', '10000.00', 'decimal', '最大充值金额', 0),
('security', 'password_min_length', '6', 'integer', '密码最小长度', 0),
('security', 'login_max_attempts', '5', 'integer', '登录最大尝试次数', 0),
('security', 'session_timeout', '7200', 'integer', '会话超时时间(秒)', 0);

-- =====================================================
-- 7. 自增ID起始值设置
-- =====================================================

ALTER TABLE `{prefix}agents` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}agent_configs` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}agent_balances` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}balance_logs` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}roles` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}permissions` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}role_permissions` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}agent_roles` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}categories` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}contents` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}payment_channels` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}payment_orders` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}user_purchases` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}system_configs` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}system_logs` AUTO_INCREMENT = 1;
ALTER TABLE `{prefix}system_notifications` AUTO_INCREMENT = 1;

-- =====================================================
-- 8. 提交事务
-- =====================================================

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- =====================================================
-- 安装完成
-- =====================================================
-- 数据库版本: v3.0.0
-- 创建时间: 2025-01-21
-- 表数量: 16张核心业务表
-- 特性: 动态表前缀、现代化架构、完整约束、初始数据
-- 超级管理员: admin / password (请及时修改密码)
-- =====================================================
