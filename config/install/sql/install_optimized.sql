-- +----------------------------------------------------------------------
-- | Video-Reward 优化版数据库安装脚本
-- +----------------------------------------------------------------------
-- | 重构版本：基于重构文档第3.3节"数据库优化设计"原则
-- | 优化内容：统一存储引擎、规范字段类型、添加索引和外键约束
-- | 兼容性：保持与现有install.php安装程序的完全兼容
-- +----------------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- 分类表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_category` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ctitle` varchar(30) NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(250) DEFAULT NULL COMMENT '分类图片',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  INDEX `idx_create_time` (`create_time`),
  INDEX `idx_delete_time` (`delete_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';

-- --------------------------------------------------------
-- 投诉表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_complain` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` varchar(250) DEFAULT NULL COMMENT '用户ID',
  `status` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '状态 (0:禁止访问,1:正常)',
  `vid` int(11) UNSIGNED DEFAULT NULL COMMENT '视频ID',
  `remark` varchar(255) DEFAULT NULL COMMENT '内容',
  `type` tinyint(2) UNSIGNED DEFAULT 3 COMMENT '分类 (1:新冠肺炎疫情相关, 2:欺诈, 3:色情, 4:诱导行为, 5:不实信息, 6:犯法犯罪, 7:骚扰, 8:抄袭/洗稿、滥用原创, 9:其它, 10:侵权(冒充他人、侵犯名誉等), 0:未知)',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `ip` varchar(45) DEFAULT NULL COMMENT 'IP地址(支持IPv6)',
  `ua` varchar(500) DEFAULT NULL COMMENT '用户代理',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_vid` (`vid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_type` (`type`),
  INDEX `idx_create_time` (`create_time`),
  INDEX `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='投诉表';

-- --------------------------------------------------------
-- 系统配置表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '变量标题',
  `value` text NOT NULL COMMENT '变量值',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name_group` (`name`, `group`),
  INDEX `idx_group` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';

-- --------------------------------------------------------
-- 代理片库表 (优化版) - 重点优化
-- --------------------------------------------------------

CREATE TABLE `ds_link` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `cid` int(11) UNSIGNED DEFAULT NULL COMMENT '分类ID',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `video_url` text COMMENT '视频链接',
  `money` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '单次打赏金额',
  `money1` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '包日价格',
  `money2` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '包月价格',
  `mianfei` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '是否免费 (0:收费,1:免费)',
  `effect_time` int(11) UNSIGNED DEFAULT NULL COMMENT '有效时间',
  `input_time` int(11) UNSIGNED DEFAULT NULL COMMENT '添加时间',
  `over_time` int(11) UNSIGNED DEFAULT NULL COMMENT '过期时间',
  `long_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '长按二维码次数',
  `lose_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '失去焦点次数',
  `read_num` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '访问量',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (1:启用,2:禁用)',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `img` varchar(500) NOT NULL DEFAULT '' COMMENT '资源图片',
  `number` int(11) UNSIGNED DEFAULT 0 COMMENT '打赏人数',
  `stock_id` int(11) UNSIGNED DEFAULT 0 COMMENT '来自公共片库ID',
  `try_see` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '试看时长(秒)',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_cid` (`cid`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_mianfei` (`mianfei`),
  INDEX `idx_create_time` (`create_time`),
  INDEX `idx_stock_id` (`stock_id`),
  INDEX `idx_uid_status` (`uid`, `status`),
  INDEX `idx_cid_status` (`cid`, `status`),
  FULLTEXT INDEX `ft_title` (`title`),
  CONSTRAINT `fk_link_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_link_cid` FOREIGN KEY (`cid`) REFERENCES `ds_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理片库';

-- --------------------------------------------------------
-- 模板表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_muban` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `title` varchar(150) DEFAULT NULL COMMENT '标题',
  `muban` varchar(150) DEFAULT NULL COMMENT '模版标识',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (0:禁用,1:正常)',
  `image` varchar(500) DEFAULT NULL COMMENT '封面图',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_create_time` (`create_time`),
  CONSTRAINT `fk_muban_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='模板表';

-- --------------------------------------------------------
-- 通知公告表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_notify` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `creator_id` varchar(250) DEFAULT '' COMMENT '创建者',
  `title` varchar(255) DEFAULT NULL COMMENT '公告标题',
  `type` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '公告类型 (1:通知, 2:公告)',
  `content` text COMMENT '公告内容',
  `is_show` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (0:禁用,1:启用)',
  `create_time` int(11) UNSIGNED NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_type` (`type`),
  INDEX `idx_is_show` (`is_show`),
  INDEX `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通告通知';

-- --------------------------------------------------------
-- 邀请码表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_number` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `number` varchar(200) NOT NULL COMMENT '邀请码',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `ua` bigint(20) UNSIGNED DEFAULT NULL COMMENT '激活人ID',
  `status` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '状态 (0:未激活,1:已激活)',
  `activate_time` int(11) UNSIGNED DEFAULT NULL COMMENT '激活时间',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '生成时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_number` (`number`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_ua` (`ua`),
  INDEX `idx_status` (`status`),
  INDEX `idx_create_time` (`create_time`),
  CONSTRAINT `fk_number_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请码表';

-- --------------------------------------------------------
-- 提现表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_outlay` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '代理ID',
  `money` decimal(15,2) NOT NULL COMMENT '提现金额',
  `status` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '状态 (0:未支付,1:已支付,2:已拒绝)',
  `image` varchar(500) NOT NULL COMMENT '收款码',
  `create_time` int(11) UNSIGNED DEFAULT 0 COMMENT '提现时间',
  `end_time` int(11) UNSIGNED DEFAULT NULL COMMENT '结算时间',
  `refuse_time` int(11) UNSIGNED DEFAULT NULL COMMENT '拒绝时间',
  `remark` varchar(500) DEFAULT NULL COMMENT '拒绝原因',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_create_time` (`create_time`),
  INDEX `idx_money` (`money`),
  CONSTRAINT `fk_outlay_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='提现表';

-- --------------------------------------------------------
-- 已支付视频表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_payed_show` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) DEFAULT NULL COMMENT 'IP地址(支持IPv6)',
  `createtime` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `expire` int(11) UNSIGNED DEFAULT 0 COMMENT '过期时间',
  `updatetime` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  `order_sn` varchar(80) DEFAULT NULL COMMENT '订单号',
  `ua` varchar(500) DEFAULT NULL COMMENT '用户代理',
  `vid` int(11) UNSIGNED DEFAULT NULL COMMENT '视频ID',
  `is_month` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否包月 (1:否,2:是)',
  `is_week` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否包周 (1:否,2:是)',
  `is_tj` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '推荐标识',
  `is_date` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否包天 (1:否,2:是)',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `is_kouliang` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '扣量 (1:不扣量,2:扣量)',
  PRIMARY KEY (`id`),
  INDEX `idx_ip` (`ip`),
  INDEX `idx_order_sn` (`order_sn`),
  INDEX `idx_vid` (`vid`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_expire` (`expire`),
  INDEX `idx_createtime` (`createtime`),
  INDEX `idx_uid_vid` (`uid`, `vid`),
  CONSTRAINT `fk_payed_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='已支付视频';

-- --------------------------------------------------------
-- 支付订单表 (优化版) - 核心业务表
-- --------------------------------------------------------

CREATE TABLE `ds_pay_order` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '代理ID',
  `nickname` varchar(50) DEFAULT NULL COMMENT '用户昵称',
  `ua` varchar(500) DEFAULT NULL COMMENT '客户端唯一标识',
  `des` varchar(255) DEFAULT NULL COMMENT '描述记录',
  `vid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '资源ID',
  `vtitle` varchar(250) DEFAULT NULL COMMENT '资源名称',
  `pay_channel` varchar(255) DEFAULT '' COMMENT '支付渠道',
  `ip` varchar(45) NOT NULL DEFAULT '0' COMMENT 'IP地址',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '支付金额(元)',
  `tc_money` decimal(15,2) NOT NULL DEFAULT 0.00 COMMENT '提现金额',
  `smoney` decimal(15,2) DEFAULT 0.00 COMMENT '打赏收入',
  `pmoney` decimal(15,2) DEFAULT 0.00 COMMENT '返佣上级',
  `createtime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '订单时间',
  `updatetime` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  `paytime` int(11) UNSIGNED DEFAULT NULL COMMENT '支付时间',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '支付状态 (1:已支付,2:未支付)',
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级代理ID',
  `pid_top` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '总代理ID',
  `is_kouliang` tinyint(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '是否扣量 (1:不扣量,2:扣量)',
  `transact` varchar(64) NOT NULL COMMENT '支付单号',
  `is_month` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否包月 (1:否,2:是)',
  `is_date` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否包日 (1:否,2:是)',
  `is_tj` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '推荐标识',
  `is_week` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否包周 (1:否,2:是)',
  `is_dsp` varchar(30) DEFAULT '' COMMENT 'DSP标识',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_transact` (`transact`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_vid` (`vid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_createtime` (`createtime`),
  INDEX `idx_paytime` (`paytime`),
  INDEX `idx_pid` (`pid`),
  INDEX `idx_pid_top` (`pid_top`),
  INDEX `idx_pay_channel` (`pay_channel`),
  INDEX `idx_uid_status_time` (`uid`, `status`, `createtime`),
  INDEX `idx_vid_status` (`vid`, `status`),
  CONSTRAINT `fk_order_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_pid` FOREIGN KEY (`pid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单列表';

-- --------------------------------------------------------
-- 支付设置表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_pay_setting` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL COMMENT '支付名称',
  `app_id` varchar(255) NOT NULL COMMENT '支付ID',
  `app_key` varchar(255) NOT NULL COMMENT '支付秘钥',
  `pay_url` varchar(500) NOT NULL DEFAULT '' COMMENT '支付网关',
  `pay_channel` varchar(255) NOT NULL COMMENT '执行方法',
  `model` tinyint(1) UNSIGNED DEFAULT NULL COMMENT '执行方式 (1:Get, 2:Post)',
  `pay_model` varchar(255) NOT NULL COMMENT '标识',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态 (0:停用,1:正常)',
  `create_time` int(11) UNSIGNED NOT NULL COMMENT '创建时间',
  `pay_fudong` varchar(100) DEFAULT NULL COMMENT '浮动',
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_pay_model` (`pay_model`),
  INDEX `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付管理表';

-- --------------------------------------------------------
-- 公共片库表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_stock` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '资源ID',
  `cid` int(11) UNSIGNED DEFAULT NULL COMMENT '分类ID',
  `title` varchar(250) NOT NULL COMMENT '资源名称',
  `url` text COMMENT '资源链接',
  `time` varchar(255) DEFAULT '' COMMENT '时长',
  `img` varchar(500) DEFAULT NULL COMMENT '封面图片',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_cid` (`cid`),
  INDEX `idx_create_time` (`create_time`),
  FULLTEXT INDEX `ft_title` (`title`),
  CONSTRAINT `fk_stock_cid` FOREIGN KEY (`cid`) REFERENCES `ds_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公共片库';

-- --------------------------------------------------------
-- 系统管理员表 (优化版) - 核心用户表
-- --------------------------------------------------------

CREATE TABLE `ds_system_admin` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) UNSIGNED DEFAULT 0 COMMENT '上级ID',
  `auth_ids` varchar(255) DEFAULT NULL COMMENT '角色权限ID',
  `head_img` varchar(500) DEFAULT '/static/admin/images/head.jpg' COMMENT '头像',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户登录名',
  `qq` varchar(20) DEFAULT NULL COMMENT 'QQ号',
  `wechat_account` varchar(50) DEFAULT NULL COMMENT '微信号',
  `password` varchar(255) NOT NULL COMMENT '登录密码(加密)',
  `pwd` varchar(50) DEFAULT NULL COMMENT '明文密码(临时)',
  `balance` decimal(15,2) DEFAULT 0.00 COMMENT '账户余额',
  `revenue` decimal(15,2) DEFAULT 0.00 COMMENT '总收益',
  `short` varchar(50) DEFAULT 'self' COMMENT '短链接类型',
  `pay_model` varchar(50) DEFAULT 'chuanqi' COMMENT '支付模式',
  `pay_model1` varchar(50) DEFAULT '' COMMENT '备用支付模式',
  `is_zn` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '智能标识',
  `is_ff` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '防封标识',
  `poundage` decimal(5,2) DEFAULT 25.00 COMMENT '手续费率(%)',
  `ticheng` decimal(5,2) DEFAULT 0.00 COMMENT '提成比例(%)',
  `is_day` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否支持包天',
  `is_week` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否支持包周',
  `is_month` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否支持包月',
  `is_dan` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否支持单次',
  `date_fee` decimal(10,2) DEFAULT 0.00 COMMENT '包天费用',
  `month_fee` decimal(10,2) DEFAULT 0.00 COMMENT '包月费用',
  `dan_fee` decimal(10,2) DEFAULT NULL COMMENT '单次费用',
  `week_fee` decimal(10,2) DEFAULT 0.00 COMMENT '包周费用',
  `view_id` tinyint(2) UNSIGNED DEFAULT 4 COMMENT '模板ID',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `remark` varchar(500) DEFAULT NULL COMMENT '备注',
  `login_num` int(11) UNSIGNED DEFAULT 0 COMMENT '登录次数',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (0:禁用,1:正常)',
  `push_all` text COMMENT '推送配置',
  `txpwd` varchar(20) DEFAULT NULL COMMENT '提现密码',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) UNSIGNED DEFAULT NULL COMMENT '删除时间',
  `is_zw` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '站外标识',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  INDEX `idx_pid` (`pid`),
  INDEX `idx_phone` (`phone`),
  INDEX `idx_status` (`status`),
  INDEX `idx_create_time` (`create_time`),
  INDEX `idx_pid_status` (`pid`, `status`),
  CONSTRAINT `fk_admin_pid` FOREIGN KEY (`pid`) REFERENCES `ds_system_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统用户表';

-- --------------------------------------------------------
-- 插入默认数据
-- --------------------------------------------------------

-- 插入默认管理员账户
INSERT INTO `ds_system_admin` (`id`, `pid`, `username`, `password`, `pwd`, `status`, `create_time`) VALUES
(1, 0, 'admin', '9f98ea3da8025db5bc4235dfac1f7a7650211592', 'admin', 1, UNIX_TIMESTAMP());

-- 插入默认分类
INSERT INTO `ds_category` (`id`, `ctitle`, `create_time`) VALUES
(1, '默认分类', UNIX_TIMESTAMP());

-- 插入默认配置
INSERT INTO `ds_config` (`name`, `group`, `title`, `value`) VALUES
('site_name', 'basic', '网站名称', 'Video-Reward'),
('site_title', 'basic', '网站标题', '商业化知识付费打赏系统'),
('site_logo', 'basic', '网站LOGO', '/static/images/logo.png');

-- --------------------------------------------------------
-- 权限管理表 (优化版) - 高优先级补充
-- --------------------------------------------------------

CREATE TABLE `ds_system_auth` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '权限名称',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态(1:禁用,2:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_sort` (`sort`),
  INDEX `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统权限表';

-- --------------------------------------------------------
-- 权限节点关系表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_system_auth_node` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `auth_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '角色ID',
  `node_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '节点ID',
  PRIMARY KEY (`id`),
  INDEX `idx_auth_id` (`auth_id`),
  INDEX `idx_node_id` (`node_id`),
  INDEX `idx_auth_node` (`auth_id`, `node_id`),
  CONSTRAINT `fk_auth_node_auth` FOREIGN KEY (`auth_id`) REFERENCES `ds_system_auth` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色与节点关系表';

-- --------------------------------------------------------
-- 系统配置表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_system_config` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `value` text COMMENT '变量值（开关1开启0关闭）',
  `remark` varchar(100) DEFAULT '' COMMENT '备注信息',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name_group` (`name`, `group`),
  INDEX `idx_group` (`group`),
  INDEX `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表';

-- --------------------------------------------------------
-- 系统菜单表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_system_menu` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `href` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `params` varchar(500) DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态(0:禁用,1:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_pid` (`pid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_sort` (`sort`),
  CONSTRAINT `fk_menu_pid` FOREIGN KEY (`pid`) REFERENCES `ds_system_menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统菜单表';

-- --------------------------------------------------------
-- 系统节点表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_system_node` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `node` varchar(100) DEFAULT NULL COMMENT '节点代码',
  `title` varchar(500) DEFAULT NULL COMMENT '节点标题',
  `type` tinyint(1) DEFAULT 3 COMMENT '节点类型（1：控制器，2：节点）',
  `is_auth` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '是否启动RBAC权限控制',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_node` (`node`),
  INDEX `idx_type` (`type`),
  INDEX `idx_is_auth` (`is_auth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统节点表';

-- --------------------------------------------------------
-- 价格配置表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_price` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pay_model` varchar(255) DEFAULT NULL COMMENT '支付模式',
  `is_dan` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '是否支持单次',
  `dan_fee` decimal(10,2) DEFAULT NULL COMMENT '单次费用',
  `is_day` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '是否支持包天',
  `day_fee` decimal(10,2) DEFAULT NULL COMMENT '包天费用',
  `is_week` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '是否支持包周',
  `week_fee` decimal(10,2) DEFAULT NULL COMMENT '包周费用',
  `is_month` tinyint(1) UNSIGNED DEFAULT 0 COMMENT '是否支持包月',
  `month_fee` decimal(10,2) DEFAULT NULL COMMENT '包月费用',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_pay_model` (`pay_model`),
  INDEX `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='价格配置表';

-- --------------------------------------------------------
-- 用户资金流水表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_user_money_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员ID',
  `money` decimal(15,2) NOT NULL COMMENT '变更余额',
  `before` decimal(15,2) NOT NULL COMMENT '变更前余额',
  `after` decimal(15,2) NOT NULL COMMENT '变更后余额',
  `memo` varchar(255) DEFAULT '' COMMENT '备注说明',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_create_time` (`create_time`),
  INDEX `idx_uid_time` (`uid`, `create_time`),
  CONSTRAINT `fk_money_log_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户资金流水';

-- --------------------------------------------------------
-- 域名管理库表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_domain_lib` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '域名ID',
  `uid` bigint(20) UNSIGNED DEFAULT 0 COMMENT '代理ID',
  `domain` varchar(1000) NOT NULL COMMENT '中转域名',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (0:禁用,1:正常)',
  `q_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '质量状态',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_status` (`status`),
  INDEX `idx_q_status` (`q_status`),
  CONSTRAINT `fk_domain_lib_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='域名管理库';

-- --------------------------------------------------------
-- 域名规则表 (优化版)
-- --------------------------------------------------------

CREATE TABLE `ds_domain_rule` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '域名ID',
  `uid` bigint(20) UNSIGNED DEFAULT 0 COMMENT '代理ID',
  `type` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '域名类型 (1:主域名,2:炮灰域名)',
  `domain` varchar(500) NOT NULL COMMENT '落地域名',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (0:禁用,1:正常)',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_type` (`type`),
  INDEX `idx_status` (`status`),
  CONSTRAINT `fk_domain_rule_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='域名规则表';

-- --------------------------------------------------------
-- 中优先级表补充
-- --------------------------------------------------------

-- 盒子外链表 (优化版)
CREATE TABLE `ds_hezi` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` bigint(20) UNSIGNED DEFAULT 0 COMMENT '用户ID',
  `hezi_url` varchar(255) DEFAULT '' COMMENT '盒子外链',
  `short_url` varchar(250) DEFAULT NULL COMMENT '短连接',
  `view_id` int(11) UNSIGNED DEFAULT NULL COMMENT '模板ID',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_view_id` (`view_id`),
  INDEX `idx_create_time` (`create_time`),
  CONSTRAINT `fk_hezi_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='盒子外链表';

-- 点播卷消费记录表 (优化版)
CREATE TABLE `ds_point_decr` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ua` varchar(255) DEFAULT NULL COMMENT '用户标识',
  `vid` varchar(30) DEFAULT NULL COMMENT '视频ID',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_ua` (`ua`),
  INDEX `idx_vid` (`vid`),
  INDEX `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播卷消费记录';

-- 短视频日志表 (优化版)
CREATE TABLE `ds_point_logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED DEFAULT NULL COMMENT '用户ID',
  `ip` varchar(45) DEFAULT NULL COMMENT 'IP地址',
  `time` varchar(222) DEFAULT NULL COMMENT '时间',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_ip` (`ip`),
  INDEX `idx_create_time` (`create_time`),
  CONSTRAINT `fk_point_logs_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短视频日志';

-- 抽单配置表 (优化版)
CREATE TABLE `ds_quantity` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '抽单ID',
  `uid` bigint(20) UNSIGNED NOT NULL COMMENT '代理ID',
  `initial` int(11) UNSIGNED NOT NULL COMMENT '初始值',
  `bottom` int(11) UNSIGNED NOT NULL COMMENT '倒数值',
  `bottom_all` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '全局倒数 (0:禁用,1:正常)',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_bottom_all` (`bottom_all`),
  CONSTRAINT `fk_quantity_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽单配置表';

-- 文件上传管理表 (优化版)
CREATE TABLE `ds_system_uploadfile` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `upload_type` varchar(20) NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `original_name` varchar(255) DEFAULT NULL COMMENT '文件原名',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '物理路径',
  `image_width` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `image_height` varchar(30) NOT NULL DEFAULT '' COMMENT '高度',
  `image_type` varchar(30) NOT NULL DEFAULT '' COMMENT '图片类型',
  `image_frames` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '图片帧数',
  `file_size` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件大小',
  `file_type` varchar(100) NOT NULL DEFAULT '' COMMENT '文件类型',
  `file_ext` varchar(100) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `file_md5` varchar(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `upload_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传时间',
  `upload_ip` varchar(45) NOT NULL DEFAULT '' COMMENT '上传IP',
  PRIMARY KEY (`id`),
  INDEX `idx_upload_type` (`upload_type`),
  INDEX `idx_file_md5` (`file_md5`),
  INDEX `idx_upload_time` (`upload_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='文件上传管理';

-- 访问量统计表 (优化版)
CREATE TABLE `ds_tj` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) UNSIGNED DEFAULT 0 COMMENT '用户ID',
  `ua` varchar(255) DEFAULT NULL COMMENT '用户标识',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_uid` (`uid`),
  INDEX `idx_ua` (`ua`),
  INDEX `idx_create_time` (`create_time`),
  CONSTRAINT `fk_tj_uid` FOREIGN KEY (`uid`) REFERENCES `ds_system_admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='访问量统计';

-- 点播卷管理表 (优化版)
CREATE TABLE `ds_user_point` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ua` varchar(255) DEFAULT NULL COMMENT '用户标识',
  `point` int(11) UNSIGNED DEFAULT 0 COMMENT '点播卷数量',
  `time` datetime DEFAULT NULL COMMENT '时间',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_ua` (`ua`),
  INDEX `idx_point` (`point`),
  INDEX `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播卷管理';

-- 快捷入口表 (优化版)
CREATE TABLE `ds_system_quick` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '快捷入口名称',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `href` varchar(255) DEFAULT NULL COMMENT '快捷链接',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `status` tinyint(1) UNSIGNED DEFAULT 1 COMMENT '状态 (0:禁用,1:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(11) UNSIGNED DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) UNSIGNED DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='快捷入口表';

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
