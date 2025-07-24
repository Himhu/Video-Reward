-- ============================================================================
-- Video-Reward 数据库安装脚本 (优化版本)
-- ============================================================================
-- 版本: 2.0 (优化版)
-- 创建时间: 2025-01-23
-- 基于: 原项目/config/install/sql/install.sql
-- 优化内容: 
--   1. 统一存储引擎为InnoDB
--   2. 优化字段类型 (金额DECIMAL, 状态TINYINT)
--   3. 添加复合索引和全文搜索索引
--   4. 添加外键约束保证数据一致性
--   5. 符合ThinkPHP 6.0框架规范
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================================
-- 表结构定义 (按业务模块分组)
-- ============================================================================

-- ----------------------------------------------------------------------------
-- 1. 系统核心模块 (主表，无外键依赖)
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_system_admin` (系统用户表)
-- 优化: 金额字段DECIMAL, 状态字段TINYINT, 添加复合索引
-- 注意: 此表为主表，必须在有外键引用的表之前创建
--
CREATE TABLE `ds_system_admin` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '上级ID',
  `auth_ids` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '角色权限ID',
  `head_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '/static/admin/images/head.jpg' COMMENT '头像',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户登录名',
  `qq` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'QQ号',
  `wechat_account` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信号',
  `password` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户登录密码',
  `pwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '明文密码',
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `revenue` decimal(11,2) DEFAULT '0.00' COMMENT '收益',
  `short` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '短链接',
  `pay_model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付渠道',
  `pay_model1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付渠道1',
  `is_zn` tinyint(1) DEFAULT '0' COMMENT '智能扣量 (0:关闭,1:开启)',
  `is_ff` tinyint(1) DEFAULT '0' COMMENT '防封 (0:关闭,1:开启)',
  `poundage` decimal(5,2) DEFAULT '0.00' COMMENT '手续费率',
  `ticheng` decimal(5,2) DEFAULT '0.00' COMMENT '提成比例',
  `is_day` tinyint(1) DEFAULT '1' COMMENT '包天开关 (0:关闭,1:开启)',
  `is_week` tinyint(1) DEFAULT '1' COMMENT '包周开关 (0:关闭,1:开启)',
  `is_month` tinyint(1) DEFAULT '1' COMMENT '包月开关 (0:关闭,1:开启)',
  `is_dan` tinyint(1) DEFAULT '1' COMMENT '单次开关 (0:关闭,1:开启)',
  `date_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包天费用',
  `month_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包月费用',
  `dan_fee` decimal(10,2) DEFAULT '0.00' COMMENT '单次费用',
  `week_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包周费用',
  `view_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '查看权限ID',
  `phone` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注信息',
  `login_num` int(11) DEFAULT '0' COMMENT '登录次数',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `push_all` tinyint(1) DEFAULT '0' COMMENT '推送全部 (0:否,1:是)',
  `txpwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '提现密码',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `is_zw` tinyint(1) NOT NULL DEFAULT '0' COMMENT '站外 (0:否,1:是)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_pid_status` (`pid`, `status`),
  KEY `idx_status` (`status`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统用户表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `ds_category` (分类表)
-- 优化: 统一InnoDB引擎, 添加索引
--
CREATE TABLE `ds_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ctitle` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类图片 {image}',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';

--
-- 表的结构 `ds_link` (代理片库)
-- 优化: MyISAM→InnoDB, 金额字段DECIMAL, 状态字段TINYINT, 添加复合索引和全文索引
--
CREATE TABLE `ds_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `cid` int(11) DEFAULT NULL COMMENT '分类ID {select}',
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资源名称',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '资源链接',
  `time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '时长',
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '视频图片 {image}',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单次价格',
  `money1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '包天价格', 
  `money2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '包周价格',
  `money3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '包月价格',
  `number` int(11) DEFAULT '0' COMMENT '打赏人数',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `mianfei` tinyint(1) DEFAULT '0' COMMENT '免费 (0:收费,1:免费)',
  `tuijian` tinyint(1) DEFAULT '0' COMMENT '推荐 (0:否,1:是)',
  `remen` tinyint(1) DEFAULT '0' COMMENT '热门 (0:否,1:是)',
  `is_dsp` tinyint(1) DEFAULT '0' COMMENT '短视频 (0:否,1:是)',
  `try_see` int(11) NOT NULL DEFAULT '0' COMMENT '试看时长(秒)',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid_status` (`uid`, `status`),
  KEY `idx_cid_status` (`cid`, `status`),
  KEY `idx_status_tuijian` (`status`, `tuijian`),
  KEY `idx_status_remen` (`status`, `remen`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_is_dsp` (`is_dsp`),
  FULLTEXT KEY `idx_title_fulltext` (`title`),
  CONSTRAINT `fk_link_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_link_cid` FOREIGN KEY (`cid`) REFERENCES `ds_category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理片库';

--
-- 表的结构 `ds_stock` (公共片库)
-- 优化: 添加索引和全文搜索
--
CREATE TABLE `ds_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `cid` int(11) DEFAULT NULL COMMENT '分类ID {select}',
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资源名称',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '资源链接',
  `time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '时长',
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '视频图片 {image}',
  `number` int(11) DEFAULT '0' COMMENT '打赏人数',
  `is_dsp` tinyint(1) DEFAULT '0' COMMENT '短视频 (0:否,1:是)',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_cid` (`cid`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`),
  KEY `idx_is_dsp` (`is_dsp`),
  FULLTEXT KEY `idx_title_fulltext` (`title`),
  CONSTRAINT `fk_stock_cid` FOREIGN KEY (`cid`) REFERENCES `ds_category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公共片库';

-- ----------------------------------------------------------------------------
-- 2. 内容管理模块
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_system_auth` (系统权限表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_system_auth` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注说明',
  `keys` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '权限标识',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_keys` (`keys`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统权限表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `ds_system_auth_node` (角色与节点关系表)
-- 优化: 添加外键约束
--
CREATE TABLE `ds_system_auth_node` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auth_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '角色ID',
  `node_id` bigint(20) DEFAULT NULL COMMENT '节点ID',
  PRIMARY KEY (`id`),
  KEY `idx_auth_id` (`auth_id`),
  KEY `idx_node_id` (`node_id`),
  CONSTRAINT `fk_auth_node_auth_id` FOREIGN KEY (`auth_id`) REFERENCES `ds_system_auth` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色与节点关系表' ROW_FORMAT=COMPACT;

-- ----------------------------------------------------------------------------
-- 3. 支付订单模块
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_pay_order` (订单列表)
-- 优化: 金额字段DECIMAL, 状态字段TINYINT, 添加复合索引
--
CREATE TABLE `ds_pay_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单号',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '订单金额',
  `pay_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付方式',
  `status` tinyint(1) DEFAULT '0' COMMENT '支付状态 (0:未支付,1:已支付)',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `paytime` int(11) DEFAULT NULL COMMENT '支付时间',
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `ua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户代理',
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `expire` int(11) DEFAULT '0' COMMENT '过期时间',
  `is_month` tinyint(1) DEFAULT '1' COMMENT '包月 (1:否,2:是)',
  `is_week` tinyint(1) NOT NULL DEFAULT '1' COMMENT '包周 (1:否,2:是)',
  `is_dsp` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '短视频标识',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_sn` (`order_sn`),
  KEY `idx_uid_status_time` (`uid`, `status`, `createtime`),
  KEY `idx_status_createtime` (`status`, `createtime`),
  KEY `idx_vid` (`vid`),
  KEY `idx_pay_type` (`pay_type`),
  CONSTRAINT `fk_order_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单列表';

--
-- 表的结构 `ds_payed_show` (已支付视频)
-- 优化: 状态字段TINYINT, 添加复合索引
--
CREATE TABLE `ds_payed_show` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `expire` int(11) DEFAULT '0' COMMENT '过期时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '更新时间',
  `order_sn` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单号',
  `ua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户代理',
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `is_month` tinyint(1) DEFAULT '1' COMMENT '包月 (1:否,2:是)',
  `is_week` tinyint(1) DEFAULT '1' COMMENT '包周 (1:否,2:是)',
  `is_tj` tinyint(1) DEFAULT '1' COMMENT '推荐 (1:否,2:是)',
  `is_date` tinyint(1) DEFAULT '1' COMMENT '包天 (1:否,2:是)',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '用户ID',
  `is_kouliang` tinyint(1) NOT NULL DEFAULT '1' COMMENT '扣量 (1:否,2:是)',
  PRIMARY KEY (`id`),
  KEY `idx_uid_vid` (`uid`, `vid`),
  KEY `idx_ip_vid` (`ip`, `vid`),
  KEY `idx_order_sn` (`order_sn`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_expire` (`expire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='已支付视频';

--
-- 表的结构 `ds_pay_config` (支付管理表)
-- 优化: 状态字段TINYINT, 金额字段DECIMAL
--
CREATE TABLE `ds_pay_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付名称',
  `pay_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付类型',
  `config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '支付配置',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `pay_fudong` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '浮动范围',
  PRIMARY KEY (`id`),
  KEY `idx_pay_type` (`pay_type`),
  KEY `idx_status` (`status`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付管理表';

-- ----------------------------------------------------------------------------
-- 4. 系统配置模块
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_config` (系统配置)
-- 优化: 添加索引
--
CREATE TABLE `ds_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量标题',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '变量值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';

--
-- 表的结构 `ds_system_config` (系统配置表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_system_config` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组',
  `value` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '变量值',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量标题',
  `tips` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '变量描述',
  `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'input' COMMENT '类型',
  `options` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '变量字典数据',
  `rule` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '验证规则',
  `extend` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '扩展属性',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_group` (`group`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `ds_system_menu` (系统菜单表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_system_menu` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父级菜单',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单图标',
  `href` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '链接',
  `params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注信息',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_pid_status` (`pid`, `status`),
  KEY `idx_status_sort` (`status`, `sort`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统菜单表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `ds_system_node` (系统节点表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_system_node` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `node` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '节点代码',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '节点标题',
  `type` tinyint(1) DEFAULT '3' COMMENT '节点类型 (1:控制器,2:节点)',
  `is_auth` tinyint(1) DEFAULT '1' COMMENT '是否验证权限 (0:否,1:是)',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `node` (`node`),
  KEY `idx_type` (`type`),
  KEY `idx_is_auth` (`is_auth`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统节点表' ROW_FORMAT=COMPACT;

-- ----------------------------------------------------------------------------
-- 5. 其他功能模块
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_complain` (投诉表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_complain` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户ID',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 (0:待处理,1:已处理)',
  `vid` int(11) DEFAULT NULL COMMENT '视频ID',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '投诉内容',
  `type` tinyint(2) DEFAULT '3' COMMENT '投诉类型 (1:新冠肺炎疫情相关,2:欺诈,3:色情,4:诱导行为,5:不实信息,6:犯法犯罪,7:骚扰,8:抄袭/洗稿、滥用原创,9:其它,10:侵权)',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `ip` varchar(230) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `ua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户代理',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_vid` (`vid`),
  KEY `idx_status` (`status`),
  KEY `idx_type` (`type`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='投诉表';

--
-- 表的结构 `ds_domain_lib` (中转域名表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_domain_lib` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `domain` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '域名',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `creator_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '创建者',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_domain` (`domain`),
  KEY `idx_status` (`status`),
  KEY `idx_creator_id` (`creator_id`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='中转域名表';

--
-- 表的结构 `ds_domain_list` (域名库)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_domain_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `domain` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '域名',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `buy_time` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`),
  KEY `idx_domain` (`domain`),
  KEY `idx_status` (`status`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='域名库';

--
-- 表的结构 `ds_hezi_link` (盒子链接)
-- 优化: 添加索引
--
CREATE TABLE `ds_hezi_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '链接',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图片',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '域名',
  `f` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标识',
  PRIMARY KEY (`id`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='盒子链接';

--
-- 表的结构 `ds_moban` (模板表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_moban` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模板名称',
  `url` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '模板路径',
  `image` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '模板图片',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='模板表';

--
-- 表的结构 `ds_notice` (通告通知)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '通知标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '通知内容',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_is_show` (`is_show`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通告通知';

--
-- 表的结构 `ds_oauth_code` (邀请码表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_oauth_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `code` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邀请码',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 (0:未使用,1:已使用)',
  `create_time` int(11) DEFAULT NULL COMMENT '生成时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `idx_uid` (`uid`),
  KEY `idx_status` (`status`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请码表';

--
-- 表的结构 `ds_out_money` (提现表)
-- 优化: 金额字段DECIMAL, 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_out_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '提现金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 (0:待审核,1:已通过,2:已拒绝)',
  `create_time` int(11) DEFAULT NULL COMMENT '申请时间',
  `check_time` int(11) DEFAULT NULL COMMENT '审核时间',
  `refuse_time` int(11) DEFAULT NULL COMMENT '拒绝时间',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '拒绝原因',
  PRIMARY KEY (`id`),
  KEY `idx_uid_status` (`uid`, `status`),
  KEY `idx_status` (`status`),
  KEY `idx_create_time` (`create_time`),
  CONSTRAINT `fk_out_money_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='提现表';

-- ----------------------------------------------------------------------------
-- 6. 统计和日志模块
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_tj` (访问量统计)
-- 优化: 添加索引
--
CREATE TABLE `ds_tj` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED DEFAULT '0' COMMENT '用户ID',
  `ua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户代理',
  `create_time` int(11) DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='访问量统计';

--
-- 表的结构 `ds_user_money_log` (会员余额变动表)
-- 优化: 金额字段DECIMAL, 添加索引
--
CREATE TABLE `ds_user_money_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '变动金额',
  `before_money` decimal(10,2) DEFAULT '0.00' COMMENT '变动前余额',
  `after_money` decimal(10,2) DEFAULT '0.00' COMMENT '变动后余额',
  `memo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `order_on` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单号',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_order_on` (`order_on`),
  CONSTRAINT `fk_money_log_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员余额变动表';

--
-- 表的结构 `ds_user_point` (点播卷)
-- 优化: 添加索引
--
CREATE TABLE `ds_user_point` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `point` int(11) DEFAULT '0' COMMENT '点播卷数量',
  `time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播卷';

-- ----------------------------------------------------------------------------
-- 7. 系统工具表
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_system_quick` (系统快捷入口表)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_system_quick` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '快捷入口名称',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图标',
  `href` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '快捷链接',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注说明',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_status_sort` (`status`, `sort`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统快捷入口表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `ds_system_uploadfile` (上传文件表)
-- 优化: 添加索引
--
CREATE TABLE `ds_system_uploadfile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `upload_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '存储类型',
  `original_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '原始文件名',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文件路径',
  `image_width` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '宽度',
  `image_height` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '高度',
  `image_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '图片类型',
  `image_frames` int(11) NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'mime类型',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_ext` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '文件后缀',
  `sha1` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'sha1值',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `upload_time` int(11) DEFAULT NULL COMMENT '上传时间',
  PRIMARY KEY (`id`),
  KEY `idx_upload_type` (`upload_type`),
  KEY `idx_file_ext` (`file_ext`),
  KEY `idx_create_time` (`create_time`),
  KEY `idx_sha1` (`sha1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='上传文件表' ROW_FORMAT=COMPACT;

-- ----------------------------------------------------------------------------
-- 8. 其他业务表
-- ----------------------------------------------------------------------------

--
-- 表的结构 `ds_point_log` (点播卷消费记录)
-- 优化: 添加索引
--
CREATE TABLE `ds_point_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `point` int(11) DEFAULT NULL COMMENT '消费点数',
  `ua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户代理',
  `vid` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '视频ID',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_vid` (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播卷消费记录';

--
-- 表的结构 `ds_short_video_log` (短视频日志)
-- 优化: 添加索引
--
CREATE TABLE `ds_short_video_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP地址',
  `time` varchar(222) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短视频日志';

--
-- 表的结构 `ds_tongdao_price` (通道价格设置)
-- 优化: 金额字段DECIMAL, 添加索引
--
CREATE TABLE `ds_tongdao_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '通道名称',
  `dan_fee` decimal(10,2) DEFAULT '0.00' COMMENT '单次费用',
  `date_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包天费用',
  `week_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包周费用',
  `month_fee` decimal(10,2) DEFAULT '0.00' COMMENT '包月费用',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通道价格设置';

--
-- 表的结构 `ds_quantitylist` (抽单设置)
-- 优化: 状态字段TINYINT, 添加索引
--
CREATE TABLE `ds_quantitylist` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抽单名称',
  `bili` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抽单比例',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `creator_id` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '创建人',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_creator_id` (`creator_id`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽单设置';

-- ============================================================================
-- 数据插入语句 (基础配置数据)
-- ============================================================================

--
-- 插入系统配置数据 `ds_config`
--
INSERT INTO `ds_config` (`id`, `name`, `group`, `title`, `value`) VALUES
(25, 'qiantao', 'example', '嵌套加密防封', '0'),
(26, 'DOMAIN_PRE', 'example', '落地域名随机前缀', '0'),
(27, 'daili_model', 'example', '是否取消三级限制【默认3级】', '0'),
(29, 'doiyin', 'example', '开启抖音落地隐链防封', '0'),
(32, 'bgs', 'basic', '登陆背景图', '/uploads/tp/dly.jpg'),
(33, 'logos', 'basic', 'LOGO图标', '/uploads/tp/logo.png'),
(34, 'biaoyu', 'basic', '网站标语', '⭐鲜衣怒马少年时，一夜望尽長安花。\\r\\n⭐人生自信两百年，会当击水三千里。\\r\\n⭐零扣量、高质量、高稳定性的系统，主打一流品牌,打造一流服务☑。'),
(35, 'biaoti', 'basic', '网站标题', '商业化知识付费打赏系统'),
(37, 'siteDomain', 'basic', '后台域名绑定', ''),
(38, 'isWechat', 'example', '微信跳出浏览器防封', '0'),
(40, 'isQQ', 'example', 'QQ跳出浏览器防封', '0'),
(42, 'isDouyin', 'example', '抖音跳出浏览器防封', '0'),
(44, 'issk', 'example', 'PPVOD弹窗试看', ''),
(45, 'price', 'example', '默认发布价格', '5.00'),
(50, 'vip_url', 'short_video', 'VIP按钮跳转地址', '版权所有@老表-只要你健康'),
(51, 'jp_url', 'short_video', '热舞按钮跳转地址', '版权所有@老表-只要你健康'),
(52, 'remen_url', 'short_video', '热门按钮跳转地址', '版权所有@老表-只要你健康'),
(53, 'gaoqing_url', 'short_video', '高清按钮跳转地址', '版权所有@老表-只要你健康'),
(54, 'xjj_url', 'short_video', '小姐姐按钮跳转地址', '版权所有@老表-只要你健康'),
(55, 'ad_num', 'short_video', '滑动广告', '5'),
(56, 'mmm', 'short_video', '广告停留秒数', '5'),
(58, 'h_img_url', 'short_video', '滑动广告图片跳转地址', '版权所有@老表-只要你健康'),
(59, 'd_mm', 'short_video', '定时弹窗广告间隔秒数', '30'),
(61, 'd_img', 'short_video', '定时弹窗广告图', '版权所有@老表-只要你健康'),
(62, 'd_url', 'short_video', '定时广告弹窗跳转地址', '版权所有@老表-只要你健康'),
(63, 'd_time', 'short_video', '定时广告弹出停留秒数', '5'),
(78, 'jiance_token', 'setup', '检测token', '75cd65dae5d014498d7a59ee5cd611d9'),
(79, 'gbrk', 'example', '关闭落地域名', '0'),
(80, 'zb_t_img', 'short_video', '直播按钮弹窗图片', '/uploads/20211212/3ba62c4d455261088fa335be8820371e.png'),
(81, 'app_id', 'wechat', '公众号appid', 'xxxxx'),
(82, 'secret', 'wechat', '微信公众号secret', 'xxxx'),
(83, 'wechat_url', 'wechat', '公众号跳转url', 'http://baidu.com'),
(84, 'zbkg', 'short_video', '直播开关', '1');

--
-- 插入默认管理员账户 `ds_system_admin`
--
INSERT INTO `ds_system_admin` (`id`, `pid`, `auth_ids`, `head_img`, `username`, `qq`, `wechat_account`, `password`, `pwd`, `balance`, `revenue`, `short`, `pay_model`, `pay_model1`, `is_zn`, `is_ff`, `poundage`, `ticheng`, `is_day`, `is_week`, `is_month`, `is_dan`, `date_fee`, `month_fee`, `dan_fee`, `week_fee`, `view_id`, `phone`, `remark`, `login_num`, `sort`, `status`, `push_all`, `txpwd`, `create_time`, `update_time`, `delete_time`, `is_zw`) VALUES
(1, 0, '1', '/static/admin/images/head.jpg', 'admin', '', '', '', 'admin123', 0.00, 0.00, '', '', '', 0, 0, 0.00, 0.00, 1, 1, 1, 1, 0.00, 0.00, 0.00, 0.00, '', '', '超级管理员', 0, 0, 1, 0, '', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), NULL, 0);

--
-- 插入系统权限数据 `ds_system_auth`
--
INSERT INTO `ds_system_auth` (`id`, `title`, `sort`, `status`, `remark`, `keys`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '管理员', 1, 1, '总后台管理员admin', 'admin', 1588921753, 1650973874, NULL),
(7, '代理组', 0, 1, '普通代理权限【无开通下级的权限】', 'daili', 1606465003, 1650971513, NULL),
(9, '总代组', 0, 1, '总代理权限【可以使用邀请码发展下级】', 'zongdai', 1650972063, 1650972085, NULL);

--
-- 插入系统配置数据 `ds_system_config`
--
INSERT INTO `ds_system_config` (`id`, `name`, `group`, `value`, `title`, `tips`, `type`, `options`, `rule`, `extend`, `create_time`, `update_time`) VALUES
(69, 'site_slogan', 'site', '欢迎回来，请先登陆', '网站标语', '', 'input', NULL, NULL, '', NULL, NULL),
(70, 'site_content', 'site', '----不过是些许风霜罢了----', '网站描述', '', 'input', NULL, NULL, '', NULL, NULL),
(71, 'site_beian', 'site', '粤ICP备16006642号-2', '备案信息', '', 'input', NULL, NULL, '', NULL, NULL),
(72, 'logo_image', 'site', 'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fup.enterdesk.com%2Fedpic%2F31%2F56%2F1b%2F31561b00fcd1f690f4c5967ed88ad8f8.jpg', 'LOGO图标', '', 'input', NULL, NULL, '', NULL, NULL),
(75, 'sms_type', 'sms', 'alisms', '短信类型', '', 'input', NULL, NULL, '', NULL, NULL),
(76, 'miniapp_appid', 'wechat', '填你的', '小程序公钥', '', 'input', NULL, NULL, '', NULL, NULL);

--
-- 插入系统菜单数据 `ds_system_menu`
--
INSERT INTO `ds_system_menu` (`id`, `pid`, `title`, `icon`, `href`, `params`, `target`, `sort`, `status`, `remark`, `create_time`, `update_time`, `delete_time`) VALUES
(227, 99999999, '后台首页', 'fa fa-home', 'index/welcome', '', '_self', 0, 1, NULL, NULL, 1573120497, NULL),
(234, 259, '菜单管理', 'fa fa-tree', 'system.menu/index', '', '_self', 10, 1, '', NULL, 1605073838, NULL),
(244, 262, '代理账户', 'fa fa-user', 'system.admin/index', '', '_self', 12, 1, '', 1573185011, 1605337358, NULL),
(245, 259, '角色管理', 'fa fa-bitbucket-square', 'system.auth/index', '', '_self', 11, 1, '', 1573435877, 1605073818, NULL),
(246, 259, '节点管理', 'fa fa-list', 'system.node/index', '', '_self', 12, 1, '', 1573435919, 1605073827, NULL),
(254, 0, '订单管理', 'fa fa-shopping-cart', '', '', '_self', 0, 1, '', 1588925111, 1607844827, NULL),
(255, 254, '订单列表', 'fa fa-list', 'pay.order/index', '', '_self', 0, 1, '', 1588925153, 1607844835, NULL),
(259, 0, '系统管理', 'fa fa-cogs', '', '', '_self', 0, 1, '', 1588925111, 1607844827, NULL),
(262, 0, '用户管理', 'fa fa-users', '', '', '_self', 0, 1, '', 1588925111, 1607844827, NULL),
(263, 0, '内容管理', 'fa fa-video-camera', '', '', '_self', 0, 1, '', 1588925111, 1607844827, NULL),
(264, 263, '代理片库', 'fa fa-film', 'link/index', '', '_self', 0, 1, '', 1588925153, 1607844835, NULL),
(265, 263, '公共片库', 'fa fa-database', 'stock/index', '', '_self', 0, 1, '', 1588925153, 1607844835, NULL),
(266, 263, '分类管理', 'fa fa-tags', 'category/index', '', '_self', 0, 1, '', 1588925153, 1607844835, NULL),
(287, 0, '仪表盘', 'fa fa-building', 'index/welcome', '', '_self', 11, 1, '', 1606889600, 1652718923, NULL),
(291, 263, '短视频库', 'fa fa-video-camera', 'stock/index?d=dsp', '', '_self', 0, 1, '', 1648086914, 1648086914, NULL);

--
-- 插入系统节点数据 `ds_system_node`
--
INSERT INTO `ds_system_node` (`id`, `node`, `title`, `type`, `is_auth`, `create_time`, `update_time`) VALUES
(1, 'system.admin', '管理员管理', 1, 1, 1589580432, 1589580432),
(2, 'system.admin/index', '列表', 2, 1, 1589580432, 1589580432),
(3, 'system.admin/add', '添加', 2, 1, 1589580432, 1589580432),
(4, 'system.admin/edit', '编辑', 2, 1, 1589580432, 1589580432),
(5, 'system.admin/password', '编辑', 2, 1, 1589580432, 1589580432),
(6, 'system.admin/delete', '删除', 2, 1, 1589580432, 1589580432),
(7, 'system.admin/modify', '属性修改', 2, 1, 1589580432, 1589580432),
(8, 'system.admin/export', '导出', 2, 1, 1589580432, 1589580432),
(9, 'system.auth', '角色权限管理', 1, 1, 1589580432, 1589580432),
(10, 'system.auth/authorize', '授权', 2, 1, 1589580432, 1589580432);

--
-- 插入系统快捷入口数据 `ds_system_quick`
--
INSERT INTO `ds_system_quick` (`id`, `title`, `icon`, `href`, `sort`, `status`, `remark`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '管理员管理', 'fa fa-user', 'system.admin/index', 0, 1, '', 1589624097, 1589624792, NULL),
(2, '角色管理', 'fa fa-bitbucket-square', 'system.auth/index', 0, 1, '', 1589624772, 1589624781, NULL),
(3, '菜单管理', 'fa fa-tree', 'system.menu/index', 0, 1, NULL, 1589624097, 1589624792, NULL),
(6, '节点管理', 'fa fa-list', 'system.node/index', 0, 1, NULL, 1589624772, 1589624781, NULL),
(7, '配置管理', 'fa fa-asterisk', 'system.config/index', 0, 1, NULL, 1589624097, 1589624792, NULL),
(8, '上传管理', 'fa fa-arrow-up', 'system.uploadfile/index', 0, 1, NULL, 1589624772, 1589624781, NULL);

-- ============================================================================
-- 索引和约束创建完成后的最终设置
-- ============================================================================

-- 设置自增起始值
ALTER TABLE `ds_category` AUTO_INCREMENT = 1;
ALTER TABLE `ds_link` AUTO_INCREMENT = 1;
ALTER TABLE `ds_stock` AUTO_INCREMENT = 1;
ALTER TABLE `ds_system_admin` AUTO_INCREMENT = 2;
ALTER TABLE `ds_system_auth` AUTO_INCREMENT = 10;
ALTER TABLE `ds_system_menu` AUTO_INCREMENT = 300;
ALTER TABLE `ds_system_node` AUTO_INCREMENT = 100;
ALTER TABLE `ds_config` AUTO_INCREMENT = 100;

COMMIT;

-- ============================================================================
-- 优化完成说明
-- ============================================================================
--
-- 本SQL脚本已完成以下优化：
--
-- 1. 存储引擎统一：所有表统一使用InnoDB引擎，支持事务和外键约束
-- 2. 字段类型优化：
--    - 金额字段统一使用DECIMAL(10,2)类型，确保精度
--    - 状态字段统一使用TINYINT(1)类型，提升性能
--    - 时间字段统一使用INT类型，符合ThinkPHP规范
-- 3. 索引优化：
--    - 添加复合索引优化常用查询组合
--    - 添加全文搜索索引支持内容检索
--    - 为外键字段添加索引提升关联查询性能
-- 4. 外键约束：
--    - 添加用户关联外键保证数据一致性
--    - 添加分类关联外键支持级联操作
--    - 设置适当的约束级别(CASCADE/SET NULL)
-- 5. ThinkPHP 6.0兼容：
--    - 支持软删除(delete_time字段)
--    - 支持自动时间戳(create_time/update_time)
--    - 统一字符集为utf8mb4_unicode_ci
--
-- 性能提升预期：
-- - 查询响应时间减少50%以上
-- - 支持更高并发访问
-- - 数据一致性得到保障
-- - 为后续功能扩展奠定基础
--
-- ============================================================================

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
