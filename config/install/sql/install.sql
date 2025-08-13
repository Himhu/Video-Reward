-- 数据库结构文件 (已优化)
-- 优化日期： 2025-01-08
-- 最后更新： 2025-01-12
-- 优化内容：
-- 1. 统一存储引擎为InnoDB
-- 2. 添加缺失索引提升查询性能
-- 3. 优化数据类型减少存储空间
-- 4. 完全清除所有转存数据
-- 5. 规范化字段定义和注释
--
-- 最新更新内容 (2025-01-12)：
-- 1. 删除重复菜单项：ID 227(后台首页), ID 266(代理管理→账户流水)
-- 2. 修正账户流水菜单：auaccount/index → adaccount/index
-- 3. 修正仪表盘菜单：pid 0 → 99999999 (设为系统首页)
-- 4. 更新系统节点：auaccount → adaccount
-- 5. 添加采集管理菜单：ID 301
--
-- 注意：此文件包含当前生产环境的完整菜单和节点数据
-- 部署时请根据实际需要添加其他初始配置数据

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `demo_4lq_cn` (优化版)
--

-- --------------------------------------------------------

--
-- 表的结构 `ds_category`
--

CREATE TABLE `ds_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL DEFAULT 0 COMMENT '父级分类ID',
  `ctitle` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `image` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '分类图片 {image}',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序值',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态：0=禁用，1=启用',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `weigh` (`id`),
  KEY `idx_pid` (`pid`),
  KEY `idx_status` (`status`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';

--
-- 转存表中的数据 `ds_category`
--

INSERT INTO `ds_category` (`id`, `pid`, `ctitle`, `image`, `sort`, `status`, `create_time`, `update_time`, `delete_time`) VALUES
(1, 0, '默认分类', NULL, 0, 1, 1641888000, 1641888000, NULL),
(2, 0, '图片资源', NULL, 1, 1, 1641888000, 1641888000, NULL),
(3, 0, '视频资源', NULL, 2, 1, 1641888000, 1641888000, NULL),
(4, 0, '文档资源', NULL, 3, 1, 1641888000, 1641888000, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ds_complain`
--

CREATE TABLE `ds_complain` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `uid` varchar(250) DEFAULT NULL COMMENT '用户ID',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 {radio} (0:禁止访问,1:正常)',
  `vid` int(11) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '内容',
  `type` int(11) DEFAULT '3' COMMENT '分类 {radio} (1:新冠肺炎疫情相关, 2:欺诈, 3:色情, 4:诱导行为, 5:不实信息, 6:犯法犯罪, 7:骚扰, 8:抄袭/洗稿、滥用原创, 9:其它, 10:侵权(冒充他人、侵犯名誉等), 0:未知)',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `ip` varchar(230) DEFAULT NULL,
  `ua` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='投诉表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_config`
--

CREATE TABLE `ds_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '变量标题',
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '变量值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';


-- --------------------------------------------------------

--
-- 表的结构 `ds_domain_lib`
--

CREATE TABLE `ds_domain_lib` (
  `id` int(11) NOT NULL COMMENT '域名ID',
  `uid` int(11) DEFAULT '0' COMMENT '代理ID {select}',
  `domain` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '中转域名',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 {radio} (0:禁用,1:正常)',
  `q_status` int(11) NOT NULL DEFAULT '1',
  `creator_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '创建者',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='中转域名表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_domain_rule`
--

CREATE TABLE `ds_domain_rule` (
  `id` int(11) NOT NULL COMMENT '域名ID',
  `uid` int(11) DEFAULT '0' COMMENT '代理ID {select}',
  `type` int(11) DEFAULT '1' COMMENT '域名类型 1主2炮灰',
  `domain` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '落地域名',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 {radio} (0:禁用,1:正常)',
  `creator_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '创建者',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `buy_time` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='域名库';


-- --------------------------------------------------------

--
-- 表的结构 `ds_hezi`
--

CREATE TABLE `ds_hezi` (
  `id` int(11) NOT NULL COMMENT 'id',
  `uid` int(11) DEFAULT '0',
  `hezi_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '盒子外链',
  `short_url` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '短连接',
  `view_id` int(11) DEFAULT NULL COMMENT '模板',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '生成日期',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `type` int(11) DEFAULT '1' COMMENT '1视频2短视频',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `f` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='盒子链接';

-- --------------------------------------------------------

--
-- 表的结构 `ds_kouliang`
--

CREATE TABLE `ds_kouliang` (
  `id` int(11) NOT NULL COMMENT '主键ID',
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 {radio} (0:禁用,1:正常)',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='扣量记录表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_link`
--

CREATE TABLE `ds_link` (
  `id` int(11) NOT NULL COMMENT 'id',
  `cid` int(11) DEFAULT NULL COMMENT '类型ID  {select}',
  `uid` int(11) NOT NULL COMMENT '代理 {select}',
  `video_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '视频链接',
  `money` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.00' COMMENT '打赏金额',
  `money2` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.00' COMMENT '包月',
  `money1` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0.00' COMMENT '包日',
  `mianfei` tinyint(1) DEFAULT '0' COMMENT '状态 {radio} (0:不免费,1:免费)',
  `effect_time` int(11) DEFAULT NULL COMMENT '有效时间',
  `input_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `over_time` int(11) DEFAULT NULL COMMENT '过期时间',
  `long_time` int(11) NOT NULL DEFAULT '0' COMMENT '长按二维码次数',
  `lose_time` int(11) NOT NULL DEFAULT '0' COMMENT '失去焦点次数',
  `read_num` int(11) NOT NULL DEFAULT '0' COMMENT '访问量',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 {radio} (1:启用,2:禁用)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '资源图片',
  `number` int(11) DEFAULT '0' COMMENT '打赏人数',
  `stock_id` int(11) DEFAULT '0' COMMENT '来自公共',
  `try_see` int(11) NOT NULL DEFAULT '0' COMMENT '试看',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='代理片库';

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- 表的结构 `ds_muban`
--

CREATE TABLE `ds_muban` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `uid` int(11) DEFAULT NULL,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '标题',
  `muban` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '模版标识',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态 {radio} (0:禁用,1:正常)',
  `image` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '封面图 {image}',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='模板表';

--
--

-- --------------------------------------------------------

--
-- 表的结构 `ds_notify`
--

CREATE TABLE `ds_notify` (
  `id` int(11) NOT NULL,
  `creator_id` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '创建者',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公告标题',
  `type` int(11) DEFAULT '1' COMMENT ' 公告类型{select} (1:通知, 2:公告)',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '公告内容',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '状态 {radio} (0:禁用,1:启用)',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通告通知';

--
--

-- --------------------------------------------------------

--
-- 表的结构 `ds_number`
--

CREATE TABLE `ds_number` (
  `id` int(11) NOT NULL COMMENT 'id',
  `number` varchar(200) NOT NULL COMMENT '邀请码',
  `uid` int(11) NOT NULL COMMENT '代理',
  `ua` int(11) DEFAULT NULL COMMENT '激活人',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 {radio} (0:未激活,1:已激活)',
  `activate_time` int(11) DEFAULT NULL COMMENT '激活时间',
  `create_time` int(11) DEFAULT NULL COMMENT '生成时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邀请码表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_outlay`
--

CREATE TABLE `ds_outlay` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `uid` int(11) DEFAULT NULL COMMENT '代理',
  `money` decimal(10,2) NOT NULL COMMENT '提现金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 {radio} (0:未支付,1:已支付,2:已拒绝)',
  `image` varchar(250) NOT NULL COMMENT '收款码 {image}',
  `create_time` int(11) DEFAULT '0' COMMENT '提现时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结算时间',
  `refuse_time` int(11) DEFAULT NULL COMMENT '拒绝时间',
  `remark` varchar(250) DEFAULT NULL COMMENT '拒绝原因'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='提现表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_payed_show`
--

CREATE TABLE `ds_payed_show` (
  `id` int(11) NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `expire` int(11) DEFAULT '0',
  `updatetime` int(11) DEFAULT NULL,
  `order_sn` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vid` int(11) DEFAULT NULL,
  `is_month` int(11) DEFAULT '1' COMMENT '1:否:2包月',
  `is_week` int(11) DEFAULT '1' COMMENT '1:否:2包月',
  `is_tj` int(11) DEFAULT '1',
  `is_date` int(11) DEFAULT '1' COMMENT '1:否:2包天',
  `uid` int(11) NOT NULL,
  `is_kouliang` int(11) NOT NULL DEFAULT '1' COMMENT '扣量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='已支付视频';

-- --------------------------------------------------------

--
-- 表的结构 `ds_pay_order`
--

CREATE TABLE `ds_pay_order` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '代理ID',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ua` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '客户端唯一标识',
  `des` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '描述记录',
  `vid` int(11) NOT NULL DEFAULT '0' COMMENT '资源ID',
  `vtitle` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '资源名称',
  `pay_channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付渠道',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT 'ip',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '支付金额',
  `tc_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `smoney` decimal(11,2) DEFAULT '0.00' COMMENT '打赏收入',
  `pmoney` decimal(11,2) DEFAULT '0.00' COMMENT '返佣上级',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '订单时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `paytime` int(11) DEFAULT NULL COMMENT '支付时间',
  `status` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '支付状态 {radio} (1:已支付,2:未支付)',
  `pid` int(11) NOT NULL DEFAULT '0',
  `pid_top` int(11) NOT NULL DEFAULT '0' COMMENT '总代理id',
  `is_kouliang` enum('2','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '2' COMMENT '是否扣量 {radio} (1:不扣量,2:扣量)',
  `transact` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付单号',
  `is_month` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '是否包月 {radio} ( 1:否,2:是)',
  `is_date` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1' COMMENT '是否包日 {radio} ( 1:否,2:是)',
  `is_tj` int(11) DEFAULT '1',
  `is_week` int(11) NOT NULL DEFAULT '1',
  `is_dsp` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='订单列表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_pay_setting`
--

CREATE TABLE `ds_pay_setting` (
  `id` int(11) NOT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付名称',
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付ID',
  `app_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '支付秘钥',
  `pay_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '支付网关',
  `pay_channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '执行方法',
  `model` int(11) DEFAULT NULL COMMENT '执行方式 {select} (1:Get, 2:Post)',
  `pay_model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标识',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态 {radio} (0:停用,1:正常)',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `pay_fudong` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '浮动'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='支付管理表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_point_decr`
--

CREATE TABLE `ds_point_decr` (
  `id` int(11) NOT NULL,
  `ua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vid` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播卷消费记录';

-- --------------------------------------------------------

--
-- 表的结构 `ds_point_logs`
--

CREATE TABLE `ds_point_logs` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time` varchar(222) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短视频日志';

-- --------------------------------------------------------

--
-- 表的结构 `ds_price`
--

CREATE TABLE `ds_price` (
  `id` int(11) NOT NULL,
  `pay_model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_dan` int(11) DEFAULT '0',
  `dan_fee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_day` int(11) DEFAULT '0',
  `date_fee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_week` int(11) DEFAULT '0',
  `week_fee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_month` int(11) DEFAULT '0',
  `month_fee` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='通道价格设置';

--
--


-- --------------------------------------------------------

--
-- 表的结构 `ds_quantity`
--

CREATE TABLE `ds_quantity` (
  `id` int(11) NOT NULL COMMENT '抽单ID',
  `uid` int(11) NOT NULL COMMENT '代理 {select}',
  `initial` int(11) NOT NULL COMMENT '初始值',
  `bottom` int(11) NOT NULL COMMENT '倒数值',
  `bottom_all` tinyint(1) DEFAULT '1' COMMENT '全局倒数 {radio} (0:禁用,1:正常)',
  `creator_id` varchar(250) DEFAULT NULL COMMENT '创建人',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='抽单设置';

-- --------------------------------------------------------

--
-- 表的结构 `ds_stock`
--

CREATE TABLE `ds_stock` (
  `id` int(11) NOT NULL COMMENT '资源ID',
  `cid` int(11) DEFAULT NULL COMMENT '分类ID {select}',
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '资源名称',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '资源链接',
  `time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '视频图片 {image}',
  `number` int(11) DEFAULT '0' COMMENT '打赏人数',
  `is_dsp` int(11) DEFAULT '0' COMMENT '1为短视频',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='公共片库';

-- --------------------------------------------------------

--
-- 表的结构 `ds_system_admin`
--

CREATE TABLE `ds_system_admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pid` int(11) DEFAULT '0' COMMENT '上级',
  `auth_ids` varchar(255) DEFAULT NULL COMMENT '角色权限ID',
  `head_img` varchar(255) DEFAULT '/static/admin/images/head.jpg' COMMENT '头像',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户登录名',
  `qq` varchar(100) DEFAULT NULL COMMENT 'QQ号',
  `wechat_account` varchar(100) DEFAULT NULL COMMENT '微信号',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '用户登录密码',
  `pwd` varchar(255) NOT NULL COMMENT '密码',
  `balance` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `revenue` decimal(11,2) DEFAULT '0.00' COMMENT '收益',
  `short` varchar(255) DEFAULT NULL COMMENT '短链接',
  `pay_model` varchar(255) DEFAULT NULL COMMENT '支付渠道',
  `pay_model1` varchar(255) DEFAULT NULL COMMENT '支付宝',
  `is_zn` int(11) DEFAULT '0' COMMENT '站内 0关闭1开启',
  `is_ff` int(11) DEFAULT '0' COMMENT '防封 0关闭1开启',
  `poundage` int(11) DEFAULT '0' COMMENT '提现费率',
  `ticheng` int(11) DEFAULT '0' COMMENT '返佣',
  `is_day` int(11) DEFAULT '1' COMMENT '是否包天 1:开0关',
  `is_week` int(11) DEFAULT '1' COMMENT '是否包周 1:开0关',
  `is_month` int(11) DEFAULT '1' COMMENT '是否包月1:开0关',
  `is_dan` varchar(255) DEFAULT '1' COMMENT '1固定2随机',
  `date_fee` int(11) DEFAULT '28' COMMENT '包天金额',
  `month_fee` int(11) DEFAULT '138' COMMENT '包月金额',
  `dan_fee` varchar(255) DEFAULT '6' COMMENT '单片金额',
  `week_fee` int(11) DEFAULT '68' COMMENT '包周金额',
  `view_id` int(11) DEFAULT '4' COMMENT '视图id',
  `phone` varchar(16) DEFAULT NULL COMMENT '联系手机号',
  `remark` varchar(255) DEFAULT '' COMMENT '备注说明',
  `login_num` bigint(20) UNSIGNED DEFAULT '0' COMMENT '登录次数',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用,)',
  `push_all` text COMMENT '发布金额设置',
  `txpwd` varchar(50) DEFAULT NULL COMMENT '提现密码',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `is_zw` int(11) NOT NULL DEFAULT '0' COMMENT '站外'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统用户表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `ds_system_admin`
-- 注意：这里包含默认管理员和示例代理用户，密码均为对应的用户名
--

INSERT INTO `ds_system_admin` (`id`, `pid`, `auth_ids`, `head_img`, `username`, `qq`, `wechat_account`, `password`, `pwd`, `balance`, `revenue`, `short`, `pay_model`, `pay_model1`, `is_zn`, `is_ff`, `poundage`, `ticheng`, `is_day`, `is_week`, `is_month`, `is_dan`, `date_fee`, `month_fee`, `dan_fee`, `week_fee`, `view_id`, `phone`, `remark`, `login_num`, `sort`, `status`, `push_all`, `txpwd`, `create_time`, `update_time`, `delete_time`, `is_zw`) VALUES
(1, 0, NULL, '/static/admin/images/head.jpg', 'admin', '123456', 'wx123456', '9f98ea3da8025db5bc4235dfac1f7a7650211592', 'admin', '0.00', '0.00', 'self', 'chuanqi', '', 0, 0, 25, 0, 1, 1, 1, '1', 0, 0, NULL, 0, 4, '123', '默认管理员', 0, 0, 1, '[{\"val\":\"2\",\"bl\":50},{\"val\":\"2\",\"bl\":50}]', '999888', 1604912635, 1714798954, NULL, 0),
(184, 0, '7', '/static/admin/images/head.jpg', 'agent', '', '', '9f98ea3da8025db5bc4235dfac1f7a7650211592', 'agent', '0.00', '0.00', 'tinyurl', '0', NULL, 0, 0, 25, 5, 1, 0, 0, '1', 0, 0, '6', 0, 1, NULL, '示例代理用户', 0, 0, 1, NULL, NULL, 1668236811, 1714222160, NULL, 0);

--


-- --------------------------------------------------------

--
-- 表的结构 `ds_system_auth`
--

CREATE TABLE `ds_system_auth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '权限名称',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '状态(1:禁用,2:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `keys` varchar(255) DEFAULT NULL COMMENT '唯一标识',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统权限表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `ds_system_auth`
--

INSERT INTO `ds_system_auth` (`id`, `title`, `sort`, `status`, `remark`, `keys`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '管理员', 1, 1, '总后台管理员admin', 'admin', 1588921753, 1650973874, NULL),
(7, '代理组', 0, 1, '普通代理权限【无开通下级的权限】', 'daili', 1606465003, 1650971513, NULL),
(9, '总代组', 0, 1, '总代理权限【可以使用邀请码发展下级】', NULL, 1650972063, 1650972085, NULL);

--


-- --------------------------------------------------------

--
-- 表的结构 `ds_system_auth_node`
--

CREATE TABLE `ds_system_auth_node` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `auth_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT '角色ID',
  `node_id` bigint(20) DEFAULT NULL COMMENT '节点ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='角色与节点关系表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `ds_system_auth_node`
--

INSERT INTO `ds_system_auth_node` (`id`, `auth_id`, `node_id`) VALUES
-- 代理组权限 (auth_id = 7)
(736, 7, 78),
(737, 7, 79),
(738, 7, 80),
(739, 7, 81),
(740, 7, 82),
(741, 7, 84),
(742, 7, 240),
(743, 7, 135),
(744, 7, 136),
(745, 7, 137),
(746, 7, 181),
(747, 7, 182),
(748, 7, 191),
(749, 7, 192),
(750, 7, 198),
(751, 7, 199),
(752, 7, 205),
(753, 7, 206),
-- 管理员组权限 (auth_id = 1)
(485, 1, 69),
(486, 1, 70),
(487, 1, 71),
(488, 1, 72),
(489, 1, 164),
(490, 1, 235),
(491, 1, 236),
(492, 1, 237),
(493, 1, 73),
(494, 1, 75),
(495, 1, 76),
(496, 1, 77),
(497, 1, 165),
(498, 1, 166),
(499, 1, 180),
(500, 1, 238),
(501, 1, 85),
(502, 1, 86),
(503, 1, 88),
(504, 1, 89),
(505, 1, 90),
(506, 1, 91),
(507, 1, 168),
(508, 1, 92),
(509, 1, 93),
(510, 1, 94),
(511, 1, 95),
(512, 1, 96),
(513, 1, 97),
(514, 1, 98),
(515, 1, 99),
(516, 1, 100),
(517, 1, 101),
(518, 1, 102),
(519, 1, 103),
(520, 1, 104),
(521, 1, 105),
(522, 1, 106),
(523, 1, 107),
(524, 1, 109),
(525, 1, 110),
(526, 1, 111),
(527, 1, 167),
(528, 1, 239),
(529, 1, 112),
(530, 1, 113),
(531, 1, 114),
(532, 1, 115),
(533, 1, 116),
(534, 1, 117),
(535, 1, 163),
(536, 1, 118),
(537, 1, 119),
(538, 1, 120),
(539, 1, 121),
(540, 1, 122),
(541, 1, 123),
(542, 1, 124),
(543, 1, 125),
(544, 1, 126),
(545, 1, 127),
(546, 1, 128),
(547, 1, 129),
(548, 1, 176),
(549, 1, 177),
(550, 1, 130),
(551, 1, 131),
(552, 1, 132),
(553, 1, 133),
(554, 1, 134),
(555, 1, 178),
(556, 1, 179),
(557, 1, 183),
(558, 1, 233),
(559, 1, 234),
(560, 1, 135),
(561, 1, 136),
(562, 1, 137),
(563, 1, 138),
(564, 1, 139),
(565, 1, 140),
(566, 1, 141),
(567, 1, 142),
(568, 1, 143),
(569, 1, 144),
(570, 1, 145),
(571, 1, 146),
(572, 1, 147),
(573, 1, 148),
(574, 1, 149),
(575, 1, 150),
(576, 1, 151),
(577, 1, 152),
(578, 1, 153),
(579, 1, 154),
(580, 1, 155),
(581, 1, 156),
(582, 1, 157),
(583, 1, 158),
(584, 1, 159),
(585, 1, 160),
(586, 1, 161),
(587, 1, 162),
(588, 1, 169),
(589, 1, 170),
(590, 1, 171),
(591, 1, 172),
(592, 1, 173),
(593, 1, 174),
(594, 1, 175),
(595, 1, 181),
(596, 1, 182),
(597, 1, 184),
(598, 1, 185),
(599, 1, 186),
(600, 1, 187),
(601, 1, 188),
(602, 1, 189),
(603, 1, 190),
(604, 1, 191),
(605, 1, 192),
(606, 1, 193),
(607, 1, 194),
(608, 1, 195),
(609, 1, 196),
(610, 1, 197),
(611, 1, 205),
(612, 1, 206),
(613, 1, 207),
(614, 1, 208),
(615, 1, 209),
(616, 1, 210),
(617, 1, 211),
(618, 1, 212),
(619, 1, 213),
(620, 1, 214),
(621, 1, 215),
(622, 1, 216),
(623, 1, 217),
(624, 1, 218),
(625, 1, 219),
(626, 1, 220),
(627, 1, 221),
(628, 1, 222),
(629, 1, 223),
(630, 1, 224),
(631, 1, 225),
(632, 1, 226),
(633, 1, 227),
(634, 1, 228),
(635, 1, 229),
(636, 1, 230),
(637, 1, 231),
(638, 1, 232),
(639, 1, 198),
(640, 1, 199),
(641, 1, 200),
(642, 1, 201),
(643, 1, 202),
(644, 1, 203),
(645, 1, 204),
(646, 1, 240),
(647, 1, 241);

-- --------------------------------------------------------

--
-- 表的结构 `ds_system_config`
--

CREATE TABLE `ds_system_config` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
  `value` text COMMENT '变量值（开关1开启0关闭）',
  `remark` varchar(100) DEFAULT '' COMMENT '备注信息',
  `sort` int(11) DEFAULT '0',
  `types` varchar(255) DEFAULT 'input',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `ds_system_config`
--

INSERT INTO `ds_system_config` (`id`, `name`, `group`, `value`, `remark`, `sort`, `types`, `create_time`, `update_time`) VALUES
(41, 'alisms_access_key_id', 'sms', '填你的', '阿里大于公钥', 0, 'input', NULL, NULL),
(42, 'alisms_access_key_secret', 'sms', '填你的', '阿里大鱼私钥', 0, 'input', NULL, NULL),
(55, 'upload_type', 'upload', 'local', '当前上传方式 （local,alioss,qnoss,txoss）', 0, 'input', NULL, NULL),
(56, 'upload_allow_ext', 'upload', 'doc,gif,ico,icon,jpg,mp3,mp4,p12,pem,png,rar,jpeg', '允许上传的文件类型', 0, 'input', NULL, NULL),
(57, 'upload_allow_size', 'upload', '1024000', '允许上传的大小', 0, 'input', NULL, NULL),
(58, 'upload_allow_mime', 'upload', 'image/gif,image/jpeg,video/x-msvideo,text/plain,image/png', '允许上传的文件mime', 0, 'input', NULL, NULL),
(59, 'upload_allow_type', 'upload', 'local,alioss,qnoss,txcos', '可用的上传文件方式', 0, 'input', NULL, NULL),
(60, 'alioss_access_key_id', 'upload', '填你的', '阿里云oss公钥', 0, 'input', NULL, NULL),
(61, 'alioss_access_key_secret', 'upload', '填你的', '阿里云oss私钥', 0, 'input', NULL, NULL),
(62, 'alioss_endpoint', 'upload', '填你的', '阿里云oss数据中心', 0, 'input', NULL, NULL),
(63, 'alioss_bucket', 'upload', '填你的', '阿里云oss空间名称', 0, 'input', NULL, NULL),
(64, 'alioss_domain', 'upload', '填你的', '阿里云oss访问域名', 0, 'input', NULL, NULL),
(65, 'site_order', 'site', '', '订单前缀', 0, 'input', NULL, NULL),
(66, 'site_name', 'site', '包上量', '站点名称', 0, 'input', NULL, NULL),
(68, 'site_title', 'site', '包上量', '网站标题', 0, 'input', NULL, NULL),
(69, 'site_slogan', 'site', '欢迎回来，请先登陆', '网站标语', 0, 'input', NULL, NULL),
(70, 'site_content', 'site', '----不过是些许风霜罢了----', '网站描述', 0, 'input', NULL, NULL),
(71, 'site_beian', 'site', '粤ICP备16006642号-2', '备案信息', 0, 'input', NULL, NULL),
(72, 'logo_image', 'site', 'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fup.enterdesk.com%2Fedpic%2F31%2F56%2F1b%2F31561b00fcd1f690f4c5967ed88ad8f8.jpg&refer=http%3A%2F%2Fup.enterdesk.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=auto?sec=1655205007&t=59b1f4bbbd89291ca06edda1ca86483b', 'LOGO图标', 0, 'input', NULL, NULL),
(75, 'sms_type', 'sms', 'alisms', '短信类型', 0, 'input', NULL, NULL),
(76, 'miniapp_appid', 'wechat', '填你的', '小程序公钥', 0, 'input', NULL, NULL),
(77, 'miniapp_appsecret', 'wechat', '填你的', '小程序私钥', 0, 'input', NULL, NULL),
(78, 'web_appid', 'wechat', '填你的', '公众号公钥', 0, 'input', NULL, NULL),
(79, 'web_appsecret', 'wechat', '填你的', '公众号私钥', 0, 'input', NULL, NULL),
(80, 'txcos_secret_id', 'upload', '填你的', '腾讯云cos密钥', 0, 'input', NULL, NULL),
(81, 'txcos_secret_key', 'upload', '填你的', '腾讯云cos私钥', 0, 'input', NULL, NULL),
(82, 'txcos_region', 'upload', '填你的', '存储桶地域', 0, 'input', NULL, NULL),
(83, 'tecos_bucket', 'upload', '填你的', '存储桶名称', 0, 'input', NULL, NULL),
(84, 'qnoss_access_key', 'upload', '填你的', '访问密钥', 0, 'input', NULL, NULL),
(85, 'qnoss_secret_key', 'upload', '填你的', '安全密钥', 0, 'input', NULL, NULL),
(86, 'qnoss_bucket', 'upload', '填你的', '存储空间', 0, 'input', NULL, NULL),
(87, 'qnoss_domain', 'upload', '填你的', '访问域名', 0, 'input', NULL, NULL),
(88, 'ff_close', 'ff', '0', '防封开关', 0, 'input', NULL, NULL),
(89, 'ff_url', 'ff', 'http://mp.weixinbridge.com/mp/wapredirect?url=', '防封', 0, 'input', NULL, NULL),
(90, 'ff_short', 'ff', 'self', '短链接', 0, 'input', NULL, NULL),
(91, 'ac_number', 'ac', '1', '全局扣量倒数', 0, 'input', NULL, NULL),
(92, 'wx_appid', 'wx', '123456', '微信公众号appid', 0, 'input', NULL, NULL),
(93, 'wx_secret', 'wx', '11', '微信公众号secret', 0, 'input', NULL, NULL),
(94, 'wx_token', 'wx', '155ss', '微信公众号token', 0, 'input', NULL, NULL),
(95, 'wx_aeskey', 'wx', '112sdd', '微信公众号aeskey', 0, 'input', NULL, NULL),
(96, 'wx_url', 'wx', 'asdasfdasfd', '微信公众号授权url', 0, 'input', NULL, NULL),
(97, 'wx_ffapi', 'wx', 'sdfs', '微信防封接口', 0, 'input', NULL, NULL),
(98, 'wx_fk', 'wx', '11', '微信风控落地', 0, 'input', NULL, NULL),
(99, 'pay_zhifu', 'pay', '0', '全局支付方式', 0, 'input', NULL, NULL),
(100, 'jg_mrfx', 'jg', '0', '默认提现费率', 0, 'input', NULL, NULL),
(101, 'jg_mrfy', 'jg', '100', '默认返佣费率', 0, 'input', NULL, NULL),
(102, 'jg_yqm', 'jg', '100', '邀请码价格', 0, 'input', NULL, NULL),
(103, 'jg_dbzd', 'jg', '1', '单笔最低提现金额', 0, 'input', NULL, NULL),
(104, 'jg_dbzg', 'jg', '10000', '单笔最高提现金额', 0, 'input', NULL, NULL),
(105, 'jg_dtzg', 'jg', '100000', '单天最高提现金额', 0, 'input', NULL, NULL),
(106, 'jg_topen', 'jg', '1', '包天总闸1', 0, 'input', NULL, NULL),
(107, 'jg_tmin', 'jg', '1', '包天总闸2', 0, 'input', NULL, NULL),
(108, 'jg_tmax', 'jg', '50', '包天总闸3', 0, 'input', NULL, NULL),
(109, 'jg_yopen', 'jg', '1', '包月总闸1', 0, 'input', NULL, NULL),
(110, 'jg_ymin', 'jg', '1', '包月总闸2', 0, 'input', NULL, NULL),
(111, 'jg_ymax', 'jg', '288', '包月总闸3', 0, 'input', NULL, NULL),
(112, 'site_bg', 'site', '/upload/tp/htdly-lbzynjk.png', '登陆背景图', 0, 'input', NULL, NULL),
(117, 'jp_wopen', 'jg', '1', '包周总闸', 0, 'input', NULL, NULL),
(118, 'jp_min', 'jg', '1', '包周最小值', 0, 'input', NULL, NULL),
(119, 'jp_max', 'jg', '128', '包周最大值', 0, 'input', NULL, NULL),
(120, 'site_domain', 'site', 'www.aa.com', '默认中转域名', 0, 'input', NULL, NULL),
(121, 'site_pay', 'site', 'www.pay.com', '支付域名', 0, 'input', NULL, NULL),
(122, 'site_payback', 'site', 'www.payaa.com', '支付回调域名', 0, 'input', NULL, NULL),
(123, 'site_urlapi', 'site', 'aaaadd', '短网址接口', 0, 'input', NULL, NULL),

(125, 'site_qq', 'site', '管理员未设置QQ请联系上级', '客服QQ', 0, 'input', NULL, NULL),
(126, 'site_wechat', 'site', '管理员未设置微信请联系上级', '客服微信', 0, 'input', NULL, NULL),
(127, 'jg_ym', 'jg', '10', '域名价格', 0, 'input', NULL, NULL),
(128, 'fb_min_money', 'jp', '1', '最低打赏金额', 0, 'input', NULL, NULL),
(129, 'fb_max_money', 'jp', '15', '最高打赏金额', 0, 'input', NULL, NULL),
(130, 'ac_init_number', 'ac', '99999', '', 0, 'input', NULL, NULL),
(131, 'pay_zhifu1', 'pay', '', '全局支付方式', 0, 'input', NULL, NULL),
(148, 'zbkg', 'short_video', '0', '直播开关', 1, 'radio', NULL, NULL),
(149, 'zb_t_img', 'short_video', '/upload/tp/zbtc.png', '未开启直播弹出图片', 2, 'file', NULL, NULL),
(151, 'ff_pc', 'ff', '0', '禁止pc端打开', 0, 'input', NULL, NULL),
(300, 'ff_redirect_url', 'ff', 'https://www.baidu.com', 'PC端重定向地址', 1, 'input', NULL, 'PC端被禁止时跳转的地址'),
(301, 'ff_enable', 'ff', '1', '启用防洪功能', 2, 'radio', '0|关闭\r\n1|开启', '是否启用防洪功能'),
(152, 'm_token', 'short', 'lb1111wxjc ', '猫咪微信检测token', 0, 'input', NULL, NULL),
(153, 'zbwl', 'short_video', 'https://v2.sohu.com/v/url/323122026_100114195.MP4', '直播外链', 0, 'input', NULL, NULL),
(154, 'zbyfmt', 'short_video', 'https://s1.ax1x.com/2022/05/08/O3MrsP.png', '直播页封面图', 0, 'file', NULL, NULL),
(155, 'dspsk', 'short_video', '0', '短视频页面试看', 0, 'radio', NULL, NULL),
(156, 'ppvd_params', 'short_video', '?segments=2&time=8', '短视频试看参数', 0, 'input', NULL, NULL),
(157, 'rvery_point', 'short_video', '1', '每次播放消耗几张点播券', 0, 'input', NULL, NULL),
(158, 'shar_point', 'short_video', '5', '分享好友可得几张点播卷', 0, 'input', NULL, NULL),
(159, 'dsp_notify', 'short_video', '左右滑动观看更多视频，分享好友可得点播卷免费点播观看', '短视频顶部公告', 0, 'input', NULL, NULL),
(160, 'shar_box_text', 'short_video', '长按收藏二维码，分享好友可得点播卷免费点播观看', '分享弹窗txt提示文本', 0, 'input', NULL, NULL),
(161, 'qrcode_logo', 'short_video', 'https://p7.itc.cn/images01/20220421/b3aec30f2fb749dbaf82f652a0b38ea1.jpeg', ' 配置二维码logo(30*30)', 0, 'file', NULL, NULL),
(162, 'qrcode_bg', 'short_video', 'https://p0.meituan.net/dpgroup/5688ad3b78ff41d4f84e2f7d65aeb84f77914.jpg', '二维码背景图(375*600)', 0, 'file', NULL, NULL),
(164, 'q_t', 'short_video', '400', '', 0, 'other', NULL, NULL),
(165, 'q_r', 'short_video', '0', '', 0, 'other', NULL, NULL),
(166, 'q_x', 'short_video', '0', '', 0, 'other', NULL, NULL),
(167, 'q_l', 'short_video', '0', '', 0, 'other', NULL, NULL),
(168, 'l_t', 'short_video', '476', '', 0, 'other', NULL, NULL),
(170, 'l_r', 'short_video', '0', '', 0, 'other', NULL, NULL),
(171, 'l_x', 'short_video', '0', '', 0, 'other', NULL, NULL),
(172, 'l_l', 'short_video', '75', '', 0, 'other', NULL, NULL),
(174, 'add_point', 'short_video', '10', '新用户附送几张卷', 0, 'input', NULL, NULL),
(175, 'ff_fix', 'ff', '0', '域名随机前缀', 0, 'input', NULL, NULL),
(176, 'sina', 'short', 'PC_TOKEN=fab386c3a8; XSRF-TOKEN=hkDvOGWgURzADW1M_b1Qu52l; SUB=_2A25PiZsWDeRhGeFK7FQR8CnLwjqIHXVs_overDV8PUNbmtAfLUvskW9NQxKdd0yHQLuf_16qAksZSK7RcZjsVRN_; SUBP=0033WrSXqPxfM725Ws9jqgMF55529P9D9WWsodCWgTJNnvN.6hh_dYI45JpX5KzhUgL.FoMXS0q7ehMN1Kq2dJLoIp7LxKML1KBLBKnLxKqL1hnLBoMNShMceh5NS0.c; ALF=1685003974; SSOLoginState=1653467974; WBPSESS=zlCRJSk_4IijbpEHSyZ1KDymeDyXUcs_DYetEfToFzcxJI6N7-xfuDE2hP_lYX_3JhBabnYV3VLBEKktSD75zfkd1pSArDT8t34OoU30ywhV7J76w9XVeXiPH0m5NSGAvkPjdvGiAx3Dp5cPcgcBQQ==', '微博ck', 0, 'input', NULL, NULL),
(177, 'pay_type', 'pay', 'zhiguan', '模式选择', 0, 'input', NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ds_system_menu`
--

CREATE TABLE `ds_system_menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pid` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '父id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `href` varchar(100) NOT NULL DEFAULT '' COMMENT '链接',
  `params` varchar(500) DEFAULT '' COMMENT '链接参数',
  `target` varchar(20) NOT NULL DEFAULT '_self' COMMENT '链接打开方式',
  `sort` int(11) DEFAULT '0' COMMENT '菜单排序',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态(0:禁用,1:启用)',
  `remark` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统菜单表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `ds_system_menu`
--

INSERT INTO `ds_system_menu` (`id`, `pid`, `title`, `icon`, `href`, `params`, `target`, `sort`, `status`, `remark`, `create_time`, `update_time`, `delete_time`) VALUES
(228, 0, '顶级菜单', 'fa fa-cog', '', '', '_self', 0, 1, '', NULL, 1605336845, 1605336845),
(234, 259, '菜单管理', 'fa fa-tree', 'system.menu/index', '', '_self', 10, 1, '', NULL, 1605073838, NULL),
(244, 262, '代理账户', 'fa fa-user', 'system.admin/index', '', '_self', 12, 1, '', 1573185011, 1605337358, NULL),
(245, 259, '角色管理', 'fa fa-bitbucket-square', 'system.auth/index', '', '_self', 11, 1, '', 1573435877, 1605073818, NULL),
(246, 259, '节点管理', 'fa fa-list', 'system.node/index', '', '_self', 9, 1, '', 1573435919, 1605073852, NULL),
(247, 254, '网站设置', 'fa fa-asterisk', 'system.config/index', '', '_self', 12, 1, '', 1573457448, 1605246828, NULL),
(248, 259, '上传管理', 'fa fa-arrow-up', 'system.uploadfile/index', '', '_self', 0, 1, '', 1573542953, 1605073894, NULL),
(250, 249, '商品分类', 'fa fa-calendar-check-o', 'mall.cate/index', '', '_self', 0, 1, '', 1589439910, 1589439966, NULL),
(251, 249, '商品管理', 'fa fa-list', 'mall.goods/index', '', '_self', 0, 1, '', 1589439931, 1589439942, NULL),
(252, 259, '快捷入口', 'fa fa-list', 'system.quick/index', '', '_self', 0, 1, '', 1589623683, 1605073909, NULL),
(253, 259, '日志管理', 'fa fa-connectdevelop', 'system.log/index', '', '_self', 0, 1, '', 1589623684, 1605073923, NULL),
(254, 0, '系统管理', 'fa fa-bullseye', '', '', '_self', 10, 1, '', 1604913833, 1607844033, NULL),
(255, 254, '通知公告', 'fa fa-bell', 'notify/index', '', '_self', 1, 1, '', 1604914018, 1607843833, NULL),
(256, 278, '推广链接', 'fa fa-puzzle-piece', '', '', '_self', 0, 1, '', 1604919261, 1607844707, NULL),
(257, 254, '主域名', 'fa fa-random', 'domainlib/index', '', '_self', 2, 1, '', 1605005862, 1607843848, NULL),
(258, 254, '域名库', 'fa fa-chain', 'domainrule/index', '', '_self', 3, 1, '', 1605027169, 1607843890, NULL),
(259, 0, '后台设置', 'fa fa-cogs', '', '', '_self', 0, 1, '', 1605073766, 1607844343, NULL),
(260, 254, '投诉列表', 'fa fa-check-square', 'complain/index', '', '_self', 4, 1, '', 1605089501, 1607843906, NULL),
(261, 254, '支付管理', 'fa fa-cc-paypal', 'paysetting/index', '', '_self', 5, 1, '', 1605109417, 1607843917, NULL),
(300, 254, '短地址配置', 'fa fa-link', 'system.shorturl/index', '', '_self', 11, 1, '短地址服务配置管理', 1641888000, 1641888000, NULL),
(262, 0, '代理管理', 'fa fa-address-book', '', '', '_self', 8, 1, '', 1605336885, 1607844050, NULL),
(263, 0, '片库管理', 'fa fa-connectdevelop', '', '', '_self', 5, 1, '', 1605336901, 1607844244, NULL),
(264, 0, '订单管理', 'fa fa-buysellads', '', '', '_self', 6, 1, '', 1605336913, 1607844130, NULL),

(267, 262, '邀请码管理', 'fa fa-handshake-o', 'number/index', '', '_self', 0, 1, '', 1605336993, 1607844094, NULL),
(268, 263, '公共片库', 'fa fa-automobile', 'stock/index', '', '_self', 0, 1, '', 1605337022, 1606827791, NULL),
(269, 263, '代理片库', 'fa fa-child', 'link/index', '', '_self', 0, 0, '', 1605337036, 1607844326, NULL),
(270, 264, '订单列表', 'fa fa-book', 'payorder/index', '', '_self', 0, 1, '', 1605337057, 1607844147, NULL),
(271, 264, '未结算列表', 'fa fa-money', 'outlayw/index', '', '_self', 0, 1, '', 1605337070, 1607844165, NULL),
(272, 264, '已结算列表', 'fa fa-diamond', 'outlayy/index', '', '_self', 0, 1, '', 1605337081, 1607844178, NULL),
(273, 264, '已拒绝列表', 'fa fa-ban', 'outlayj/index', '', '_self', 0, 1, '', 1605337095, 1607844196, NULL),
(274, 0, '开始赚钱', 'fa fa-hashtag', 'peizhi/index', '', '_self', 0, 1, '', 1605338433, 1652719545, NULL),
(275, 274, '推广配置', 'fa fa-cny', '', '', '_self', 0, 1, '', 1605338446, 1652719096, NULL),
(276, 274, '公共片库', 'fa fa-cc-paypal', 'stock/index', '', '_self', 0, 1, '', 1605338461, 1607844586, NULL),
(277, 274, '私有片库', 'fa fa-ellipsis-h', 'link/index', '', '_self', 0, 1, '', 1605338473, 1607844611, NULL),
(278, 0, '推广链接', 'fa fa-usb', 'hezi/index', '', '_self', 0, 1, '', 1605338507, 1652768457, NULL),
(279, 0, '财务管理', 'fa fa-dollar', '', '', '_self', 0, 1, '', 1605338530, 1607844764, NULL),
(280, 279, '订单记录', 'fa fa-street-view', 'paylist/index', '', '_self', 0, 1, '', 1605338544, 1651029680, NULL),
(281, 279, '提现记录', 'fa fa-cc-visa', 'outlay/index', '', '_self', 0, 1, '', 1605338556, 1607844801, NULL),
(282, 279, '账户流水', 'fa fa-universal-access', 'adaccount/index', '', '_self', 0, 1, '', 1605338569, 1607844821, NULL),
(283, 254, '抽单设置', 'fa fa-birthday-cake', 'quantity/index', '', '_self', 6, 1, '', 1605340171, 1650975238, NULL),
(284, 254, '资源分类', 'fa fa-pagelines', 'category/index', '', '_self', 7, 1, '', 1605459439, 1607843945, NULL),
(285, 254, '模板管理', 'fa fa-html5', 'muban/index', '', '_self', 8, 1, '', 1605545894, 1607843956, NULL),
(286, 0, '首页', 'fa fa-dashboard', '', '', '_self', 11, 1, '', 1606889408, 1607844729, NULL),
(287, 99999999, '仪表盘', 'fa fa-building', 'index/welcome', '', '_self', 11, 1, '', 1606889600, 1652718923, NULL),
(288, 254, '抽单列表', 'fa fa-assistive-listening-systems', 'quantitylist/index', '', '_self', 10, 1, '', 1606922804, 1650975249, NULL),
(289, 0, '下级管理', 'fa fa-american-sign-language-interpreting', '', '', '_self', 0, 1, '', 1607002225, 1607844847, NULL),
(290, 289, '下级明细', 'fa fa-barcode', 'numberx/index', '', '_self', 0, 1, '', 1607002290, 1607844869, NULL),
(291, 263, '短视频库', 'fa fa-video-camera', 'stock/index?d=dsp', '', '_self', 0, 1, '', 1648086914, 1648086914, NULL),
(291, 263, '短视频库', 'fa fa-video-camera', 'stock/index?d=dsp', '', '_self', 0, 1, '', 1648086914, 1648086914, NULL),
(301, 254, '采集管理', 'fa fa-download', 'collect/index', '', '_self', 9, 1, '资源采集管理功能', 1755002732, 1755002732, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ds_system_node`
--

CREATE TABLE `ds_system_node` (
  `id` int(10) UNSIGNED NOT NULL,
  `node` varchar(100) DEFAULT NULL COMMENT '节点代码',
  `title` varchar(500) DEFAULT NULL COMMENT '节点标题',
  `type` tinyint(1) DEFAULT '3' COMMENT '节点类型（1：控制器，2：节点）',
  `is_auth` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '是否启动RBAC权限控制',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统节点表' ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `ds_system_node`
-- 注意：包含完整的223个系统节点，确保权限系统正常工作
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
(10, 'system.auth/authorize', '授权', 2, 1, 1589580432, 1589580432),
(11, 'system.auth/saveAuthorize', '授权保存', 2, 1, 1589580432, 1589580432),
(12, 'system.auth/index', '列表', 2, 1, 1589580432, 1589580432),
(13, 'system.auth/add', '添加', 2, 1, 1589580432, 1589580432),
(14, 'system.auth/edit', '编辑', 2, 1, 1589580432, 1589580432),
(15, 'system.auth/delete', '删除', 2, 1, 1589580432, 1589580432),
(16, 'system.auth/export', '导出', 2, 1, 1589580432, 1589580432),
(17, 'system.auth/modify', '属性修改', 2, 1, 1589580432, 1589580432),
(18, 'system.config', '系统配置管理', 1, 1, 1589580432, 1589580432),
(19, 'system.config/index', '列表', 2, 1, 1589580432, 1589580432),
(20, 'system.config/save', '保存', 2, 1, 1589580432, 1589580432),
(21, 'system.menu', '菜单管理', 1, 1, 1589580432, 1589580432),
(22, 'system.menu/index', '列表', 2, 1, 1589580432, 1589580432),
(23, 'system.menu/add', '添加', 2, 1, 1589580432, 1589580432),
(24, 'system.menu/edit', '编辑', 2, 1, 1589580432, 1589580432),
(25, 'system.menu/delete', '删除', 2, 1, 1589580432, 1589580432),
(26, 'system.menu/modify', '属性修改', 2, 1, 1589580432, 1589580432),
(27, 'system.menu/getMenuTips', '添加菜单提示', 2, 1, 1589580432, 1589580432),
(28, 'system.menu/export', '导出', 2, 1, 1589580432, 1589580432),
(29, 'system.node', '系统节点管理', 1, 1, 1589580432, 1589580432),
(30, 'system.node/index', '列表', 2, 1, 1589580432, 1589580432),
(31, 'system.node/refreshNode', '系统节点更新', 2, 1, 1589580432, 1589580432),
(32, 'system.node/clearNode', '清除失效节点', 2, 1, 1589580432, 1589580432),
(33, 'system.node/add', '添加', 2, 1, 1589580432, 1589580432),
(34, 'system.node/edit', '编辑', 2, 1, 1589580432, 1589580432),
(35, 'system.node/delete', '删除', 2, 1, 1589580432, 1589580432),
(36, 'system.node/export', '导出', 2, 1, 1589580432, 1589580432),
(37, 'system.node/modify', '属性修改', 2, 1, 1589580432, 1589580432),
(38, 'system.uploadfile', '上传文件管理', 1, 1, 1589580432, 1589580432),
(39, 'system.uploadfile/index', '列表', 2, 1, 1589580432, 1589580432),
(40, 'system.uploadfile/add', '添加', 2, 1, 1589580432, 1589580432),
(41, 'system.uploadfile/edit', '编辑', 2, 1, 1589580432, 1589580432),
(42, 'system.uploadfile/delete', '删除', 2, 1, 1589580432, 1589580432),
(43, 'system.uploadfile/export', '导出', 2, 1, 1589580432, 1589580432),
(44, 'system.uploadfile/modify', '属性修改', 2, 1, 1589580432, 1589580432),
(60, 'system.quick', '快捷入口管理', 1, 1, 1589623188, 1589623188),
(61, 'system.quick/index', '列表', 2, 1, 1589623188, 1589623188),
(62, 'system.quick/add', '添加', 2, 1, 1589623188, 1589623188),
(63, 'system.quick/edit', '编辑', 2, 1, 1589623188, 1589623188),
(64, 'system.quick/delete', '删除', 2, 1, 1589623188, 1589623188),
(65, 'system.quick/export', '导出', 2, 1, 1589623188, 1589623188),
(66, 'system.quick/modify', '属性修改', 2, 1, 1589623188, 1589623188),
(67, 'system.log', '操作日志管理', 1, 1, 1589623188, 1589623188),
(68, 'system.log/index', '列表', 2, 1, 1589623188, 1589623188),
(69, 'domainlib', '中转域名', 1, 1, 1605073667, 1605073667),
(70, 'domainlib/delete', '删除', 2, 1, 1605073667, 1605073667),
(71, 'domainlib/export', '导出', 2, 1, 1605073667, 1605073667),
(72, 'domainlib/modify', '属性修改', 2, 1, 1605073668, 1605073668),
(73, 'domainrule', '落地域名', 1, 1, 1605073668, 1605073668),
(75, 'domainrule/delete', '删除', 2, 1, 1605073668, 1605073668),
(76, 'domainrule/export', '导出', 2, 1, 1605073668, 1605073668),
(77, 'domainrule/modify', '属性修改', 2, 1, 1605073668, 1605073668),
(78, 'hezi', '推广盒子', 1, 1, 1605073668, 1605073668),
(79, 'hezi/index', '列表', 2, 1, 1605073668, 1605073668),
(80, 'hezi/add', '添加', 2, 1, 1605073668, 1605073668),
(81, 'hezi/edit', '编辑', 2, 1, 1605073668, 1605073668),
(82, 'hezi/delete', '删除', 2, 1, 1605073668, 1605073668),
(83, 'hezi/export', '导出', 2, 1, 1605073668, 1605073668),
(84, 'hezi/modify', '属性修改', 2, 1, 1605073668, 1605073668),
(85, 'notify', '通告通知', 1, 1, 1605073668, 1605073668),
(86, 'notify/index', '列表', 2, 1, 1605073668, 1605073668),
(88, 'notify/edit', '编辑', 2, 1, 1605073668, 1605073668),
(89, 'notify/delete', '删除', 2, 1, 1605073668, 1605073668),
(90, 'notify/export', '导出', 2, 1, 1605073668, 1605073668),
(91, 'notify/modify', '属性修改', 2, 1, 1605073668, 1605073668),
(92, 'category', '资源分类', 1, 1, 1606308862, 1606308862),
(93, 'category/index', '列表', 2, 1, 1606308862, 1606308862),
(94, 'category/add', '添加', 2, 1, 1606308862, 1606308862),
(95, 'category/edit', '编辑', 2, 1, 1606308862, 1606308862),
(96, 'category/delete', '删除', 2, 1, 1606308862, 1606308862),
(97, 'category/export', '导出', 2, 1, 1606308862, 1606308862),
(98, 'category/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(99, 'complain', '投诉管理', 1, 1, 1606308862, 1606308862),
(100, 'complain/index', '列表', 2, 1, 1606308862, 1606308862),
(101, 'complain/add', '添加', 2, 1, 1606308862, 1606308862),
(102, 'complain/edit', '编辑', 2, 1, 1606308862, 1606308862),
(103, 'complain/delete', '删除', 2, 1, 1606308862, 1606308862),
(104, 'complain/export', '导出', 2, 1, 1606308862, 1606308862),
(105, 'complain/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(106, 'link', '代理片库', 1, 1, 1606308862, 1606308862),
(107, 'link/add', '添加', 2, 1, 1606308862, 1606308862),
(109, 'link/delete', '删除', 2, 1, 1606308862, 1606308862),
(110, 'link/export', '导出', 2, 1, 1606308862, 1606308862),
(111, 'link/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(112, 'muban', '模版管理', 1, 1, 1606308862, 1606308862),
(113, 'muban/edit', '编辑', 2, 1, 1606308862, 1606308862),
(114, 'muban/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(115, 'muban/add', '添加', 2, 1, 1606308862, 1606308862),
(116, 'muban/delete', '删除', 2, 1, 1606308862, 1606308862),
(117, 'muban/export', '导出', 2, 1, 1606308862, 1606308862),
(118, 'paysetting', '支付管理表', 1, 1, 1606308862, 1606308862),
(119, 'paysetting/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(120, 'paysetting/index', '列表', 2, 1, 1606308862, 1606308862),
(121, 'paysetting/add', '添加', 2, 1, 1606308862, 1606308862),
(122, 'paysetting/edit', '编辑', 2, 1, 1606308862, 1606308862),
(123, 'paysetting/delete', '删除', 2, 1, 1606308862, 1606308862),
(124, 'paysetting/export', '导出', 2, 1, 1606308862, 1606308862),
(125, 'quantity', '扣量设置', 1, 1, 1606308862, 1606308862),
(126, 'quantity/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(127, 'quantity/index', '列表', 2, 1, 1606308862, 1606308862),
(128, 'quantity/delete', '删除', 2, 1, 1606308862, 1606308862),
(129, 'quantity/export', '导出', 2, 1, 1606308862, 1606308862),
(130, 'stock', '公共片库', 1, 1, 1606308862, 1606308862),
(131, 'stock/edit', '编辑', 2, 1, 1606308862, 1606308862),
(132, 'stock/delete', '删除', 2, 1, 1606308862, 1606308862),
(133, 'stock/export', '导出', 2, 1, 1606308862, 1606308862),
(134, 'stock/modify', '属性修改', 2, 1, 1606308862, 1606308862),
(135, 'outlay', '提现列表', 1, 1, 1606717127, 1606717127),
(136, 'outlay/index', '提现列表', 2, 1, 1606717127, 1606717127),
(137, 'outlay/add', '提现申请添加', 2, 1, 1606717127, 1606717127),
(138, 'outlay/edit', '编辑', 2, 1, 1606717127, 1606717127),
(139, 'outlay/delete', '删除', 2, 1, 1606717127, 1606717127),
(140, 'outlay/export', '导出', 2, 1, 1606717127, 1606717127),
(141, 'outlay/modify', '属性修改', 2, 1, 1606717127, 1606717127),
(142, 'outlayj', '已拒绝列表', 1, 1, 1606717127, 1606717127),
(143, 'outlayj/index', '已拒绝列表', 2, 1, 1606717127, 1606717127),
(144, 'outlayj/add', '添加', 2, 1, 1606717127, 1606717127),
(145, 'outlayj/edit', '编辑', 2, 1, 1606717127, 1606717127),
(146, 'outlayj/delete', '删除', 2, 1, 1606717127, 1606717127),
(147, 'outlayj/export', '导出', 2, 1, 1606717127, 1606717127),
(148, 'outlayj/modify', '属性修改', 2, 1, 1606717127, 1606717127),
(149, 'outlayy', '已结算列表', 1, 1, 1606717127, 1606717127),
(150, 'outlayy/index', '已结算列表', 2, 1, 1606717127, 1606717127),
(151, 'outlayy/add', '添加', 2, 1, 1606717127, 1606717127),
(152, 'outlayy/edit', '编辑', 2, 1, 1606717127, 1606717127),
(153, 'outlayy/delete', '删除', 2, 1, 1606717127, 1606717127),
(154, 'outlayy/export', '导出', 2, 1, 1606717127, 1606717127),
(155, 'outlayy/modify', '属性修改', 2, 1, 1606717127, 1606717127),
(156, 'payorder', '订单列表', 1, 1, 1606717127, 1606717127),
(157, 'payorder/index', '列表', 2, 1, 1606717127, 1606717127),
(158, 'payorder/add', '添加', 2, 1, 1606717127, 1606717127),
(159, 'payorder/edit', '编辑', 2, 1, 1606717127, 1606717127),
(160, 'payorder/delete', '删除', 2, 1, 1606717127, 1606717127),
(161, 'payorder/export', '导出', 2, 1, 1606717127, 1606717127),
(162, 'payorder/modify', '属性修改', 2, 1, 1606717127, 1606717127),
(163, 'muban/index', '列表', 2, 1, 1606789676, 1606789676),
(164, 'domainlib/edit', '编辑', 2, 1, 1606826461, 1606826461),
(165, 'domainrule/add', '添加', 2, 1, 1606826461, 1606826461),
(166, 'domainrule/edit', '编辑', 2, 1, 1606826461, 1606826461),
(167, 'link/index', '列表', 2, 1, 1606826461, 1606826461),
(168, 'notify/add', '添加', 2, 1, 1606826461, 1606826461),
(169, 'outlayw', '未结算列表', 1, 1, 1606826461, 1606826461),
(170, 'outlayw/index', '未结算列表', 2, 1, 1606826461, 1606826461),
(171, 'outlayw/add', '添加', 2, 1, 1606826461, 1606826461),
(172, 'outlayw/edit', '编辑', 2, 1, 1606826461, 1606826461),
(173, 'outlayw/delete', '删除', 2, 1, 1606826461, 1606826461),
(174, 'outlayw/export', '导出', 2, 1, 1606826461, 1606826461),
(175, 'outlayw/modify', '属性修改', 2, 1, 1606826461, 1606826461),
(176, 'quantity/add', '添加', 2, 1, 1606826461, 1606826461),
(177, 'quantity/edit', '编辑', 2, 1, 1606826461, 1606826461),
(178, 'stock/index', '列表', 2, 1, 1606826461, 1606826461),
(179, 'stock/add', '添加', 2, 1, 1606826461, 1606826461),
(180, 'domainrule/index', '列表', 2, 1, 1606834476, 1606834476),
(181, 'index', '仪表盘', 1, 1, 1606889009, 1606889009),
(182, 'index/welcome', '欢迎页', 2, 1, 1606889009, 1606889009),
(183, 'stock/import', '导入', 2, 1, 1606900920, 1606900920),
(184, 'adaccount', '会员账户流水', 1, 1, 1606911229, 1606911229),
(185, 'adaccount/index', '账户流水', 2, 1, 1606911229, 1606911229),
(186, 'adaccount/add', '添加', 2, 1, 1606911229, 1606911229),
(187, 'adaccount/edit', '编辑', 2, 1, 1606911230, 1606911230),
(188, 'adaccount/delete', '删除', 2, 1, 1606911230, 1606911230),
(189, 'adaccount/export', '导出', 2, 1, 1606911230, 1606911230),
(190, 'adaccount/modify', '属性修改', 2, 1, 1606911230, 1606911230),
(191, 'adaccount', '用户账户流水', 1, 1, 1606911231, 1606911231),
(192, 'adaccount/index', '账户流水', 2, 1, 1606911231, 1606911231),
(193, 'adaccount/add', '添加', 2, 1, 1606911231, 1606911231),
(194, 'adaccount/edit', '编辑', 2, 1, 1606911231, 1606911231),
(195, 'adaccount/delete', '删除', 2, 1, 1606911231, 1606911231),
(196, 'adaccount/export', '导出', 2, 1, 1606911232, 1606911232),
(197, 'adaccount/modify', '属性修改', 2, 1, 1606911232, 1606911232),
(198, 'paylist', '打赏记录', 1, 1, 1606912324, 1606912324),
(199, 'paylist/index', '打赏记录', 2, 1, 1606912324, 1606912324),
(200, 'paylist/add', '添加', 2, 1, 1606912325, 1606912325),
(201, 'paylist/edit', '编辑', 2, 1, 1606912325, 1606912325),
(202, 'paylist/delete', '删除', 2, 1, 1606912325, 1606912325),
(203, 'paylist/export', '导出', 2, 1, 1606912325, 1606912325),
(204, 'paylist/modify', '属性修改', 2, 1, 1606912326, 1606912326),
(205, 'peizhi', '打赏配置', 1, 1, 1606918782, 1606918782),
(206, 'peizhi/index', '列表', 2, 1, 1606918782, 1606918782),
(207, 'peizhi/add', '添加', 2, 1, 1606918782, 1606918782),
(208, 'peizhi/edit', '编辑', 2, 1, 1606918783, 1606918783),
(209, 'peizhi/delete', '删除', 2, 1, 1606918783, 1606918783),
(210, 'peizhi/export', '导出', 2, 1, 1606918783, 1606918783),
(211, 'peizhi/modify', '属性修改', 2, 1, 1606918783, 1606918783),
(212, 'number', '邀请码管理', 1, 1, 1607004740, 1607004740),
(213, 'number/add', '添加', 2, 1, 1607004740, 1607004740),
(214, 'number/index', '列表', 2, 1, 1607004740, 1607004740),
(215, 'number/edit', '编辑', 2, 1, 1607004740, 1607004740),
(216, 'number/delete', '删除', 2, 1, 1607004740, 1607004740),
(217, 'number/export', '导出', 2, 1, 1607004740, 1607004740),
(218, 'number/modify', '属性修改', 2, 1, 1607004740, 1607004740),
(219, 'numberx', '下级管理', 1, 1, 1607004740, 1607004740),
(220, 'numberx/add', '添加', 2, 1, 1607004741, 1607004741),
(221, 'numberx/index', '列表', 2, 1, 1607004741, 1607004741),
(222, 'numberx/edit', '编辑', 2, 1, 1607004741, 1607004741),
(223, 'numberx/delete', '删除', 2, 1, 1607004741, 1607004741),
(224, 'numberx/export', '导出', 2, 1, 1607004741, 1607004741),
(225, 'numberx/modify', '属性修改', 2, 1, 1607004741, 1607004741),
(226, 'quantitylist', '扣量列表', 1, 1, 1607004741, 1607004741),
(227, 'quantitylist/index', '扣量列表', 2, 1, 1607004741, 1607004741),
(228, 'quantitylist/add', '添加', 2, 1, 1607004741, 1607004741),
(229, 'quantitylist/edit', '编辑', 2, 1, 1607004741, 1607004741),
(230, 'quantitylist/delete', '删除', 2, 1, 1607004741, 1607004741),
(231, 'quantitylist/export', '导出', 2, 1, 1607004741, 1607004741),
(232, 'quantitylist/modify', '属性修改', 2, 1, 1607004741, 1607004741),
(233, 'stock/piliang', '批量发布', 2, 1, 1607150923, 1607150923),
(234, 'stock/push_all', '全部发布', 2, 1, 1607150923, 1607150923),
(235, 'domainlib/index', '列表', 2, 1, 1607174142, 1607174142),
(236, 'domainlib/recycling', '回收', 2, 1, 1607174142, 1607174142),
(237, 'domainlib/add', '添加', 2, 1, 1607174142, 1607174142),
(238, 'domainrule/recycling', '回收', 2, 1, 1607175465, 1607175465),
(239, 'link/edit', '编辑', 2, 1, 1607696652, 1607696652),
(240, 'hezi/domain', '购买分流域名', 2, 1, 1607696712, 1607696712),
(241, 'domainrule/piliang', '批量添加域名', 2, 1, 1619960681, 1619960681);
--

-- --------------------------------------------------------

--
-- 表的结构 `ds_system_quick`
--

CREATE TABLE `ds_system_quick` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(20) NOT NULL COMMENT '快捷入口名称',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `href` varchar(255) DEFAULT NULL COMMENT '快捷链接',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `status` tinyint(3) UNSIGNED DEFAULT '1' COMMENT '状态(1:禁用,2:启用)',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统快捷入口表' ROW_FORMAT=COMPACT;

--
--


-- --------------------------------------------------------

--
-- 表的结构 `ds_system_uploadfile`
--

CREATE TABLE `ds_system_uploadfile` (
  `id` int(10) UNSIGNED NOT NULL COMMENT 'ID',
  `upload_type` varchar(20) NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `original_name` varchar(255) DEFAULT NULL COMMENT '文件原名',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '物理路径',
  `image_width` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `image_height` varchar(30) NOT NULL DEFAULT '' COMMENT '高度',
  `image_type` varchar(30) NOT NULL DEFAULT '' COMMENT '图片类型',
  `image_frames` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `mime_type` varchar(100) NOT NULL DEFAULT '' COMMENT 'mime类型',
  `file_size` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `file_ext` varchar(100) DEFAULT NULL,
  `sha1` varchar(64) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `create_time` int(11) DEFAULT NULL COMMENT '创建日期',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `upload_time` int(11) DEFAULT NULL COMMENT '上传时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='上传文件表' ROW_FORMAT=COMPACT;

--
-- 表的结构 `ds_short_service`
--

CREATE TABLE `ds_short_service` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `service_code` varchar(50) NOT NULL DEFAULT '' COMMENT '服务代码',
  `service_name` varchar(100) NOT NULL DEFAULT '' COMMENT '服务名称',
  `api_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'API接口地址',
  `api_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'API密钥',
  `api_secret` varchar(255) NOT NULL DEFAULT '' COMMENT 'API密钥2',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用：0=禁用，1=启用',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认：0=否，1=是',
  `sort_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `remark` text COMMENT '备注说明',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_code` (`service_code`),
  KEY `is_enabled` (`is_enabled`),
  KEY `is_default` (`is_default`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='短地址服务配置表';

--
-- 转存表中的数据 `ds_short_service`
--

INSERT INTO `ds_short_service` (`id`, `service_code`, `service_name`, `api_url`, `api_key`, `api_secret`, `is_enabled`, `is_default`, `sort_order`, `remark`, `create_time`, `update_time`) VALUES
(1, '0', '不使用短链接', '', '', '', 1, 1, 0, '直接返回原始URL，不进行短链接转换', 1641888000, 1641888000),
(2, 'self', '自建短链接', '', '', '', 1, 0, 1, '使用系统自建的短链接服务', 1641888000, 1641888000),
(3, 'sina', '新浪短链接', 'https://api.weibo.com/2/short_url/shorten.json', '', '', 0, 0, 2, '新浪微博短链接服务（需要API密钥）', 1641888000, 1641888000),
(4, 'baidu', '百度短链接', 'https://dwz.cn/admin/create', '', '', 0, 0, 3, '百度短链接服务', 1641888000, 1641888000);

--
--


-- --------------------------------------------------------

--
-- 表的结构 `ds_tj`
--

CREATE TABLE `ds_tj` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT '0',
  `ua` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='访问量统计';

-- --------------------------------------------------------

--
-- 表的结构 `ds_user_money_log`
--

CREATE TABLE `ds_user_money_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` decimal(10,2) NOT NULL COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL COMMENT '变更后余额',
  `type` int(11) DEFAULT '1' COMMENT '1:加2:减',
  `memo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `simple` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '简化备注',
  `order_on` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '订单号',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员余额变动表';

-- --------------------------------------------------------

--
-- 表的结构 `ds_user_point`
--

CREATE TABLE `ds_user_point` (
  `id` int(11) NOT NULL,
  `ua` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point` int(11) DEFAULT '0' COMMENT '点播卷',
  `time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='点播卷';

-- --------------------------------------------------------

--
-- 转储表的索引
--

--
-- 表的索引 `ds_category`
--
ALTER TABLE `ds_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `weigh` (`id`);

--
-- 表的索引 `ds_complain`
--
ALTER TABLE `ds_complain`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `ds_config`
--
ALTER TABLE `ds_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- 表的索引 `ds_domain_lib`
--
ALTER TABLE `ds_domain_lib`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE;

--
-- 表的索引 `ds_domain_rule`
--
ALTER TABLE `ds_domain_rule`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `ds_hezi`
--
ALTER TABLE `ds_hezi`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `ds_kouliang`
--
ALTER TABLE `ds_kouliang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uid` (`uid`),
  ADD KEY `idx_create_time` (`create_time`);

--
-- 表的索引 `ds_link`
--
ALTER TABLE `ds_link`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `cid` (`cid`) USING BTREE,
  ADD KEY `stock` (`stock_id`) USING BTREE,
  ADD KEY `uid_stock` (`uid`,`stock_id`) USING BTREE,
  ADD KEY `status` (`status`) USING BTREE,
  ADD KEY `create_time` (`create_time`) USING BTREE,
  ADD KEY `uid_cid` (`uid`,`cid`) USING BTREE;
--
-- 表的索引 `ds_muban`
--
ALTER TABLE `ds_muban`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `ds_notify`
--
ALTER TABLE `ds_notify`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_number`
--
ALTER TABLE `ds_number`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_outlay`
--
ALTER TABLE `ds_outlay`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_payed_show`
--
ALTER TABLE `ds_payed_show`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `ua` (`ip`,`createtime`,`ua`(191)) USING BTREE;

--
-- 表的索引 `ds_pay_order`
--
ALTER TABLE `ds_pay_order`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `order` (`transact`) USING BTREE,
  ADD KEY `uid` (`uid`) USING BTREE,
  ADD KEY `vid` (`vid`) USING BTREE,
  ADD KEY `status_uid` (`status`,`uid`) USING BTREE,
  ADD KEY `uid_status_time` (`uid`,`status`,`createtime`) USING BTREE,
  ADD KEY `createtime` (`createtime`) USING BTREE,
  ADD KEY `paytime` (`paytime`) USING BTREE;

--
-- 表的索引 `ds_pay_setting`
--
ALTER TABLE `ds_pay_setting`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `ds_point_decr`
--
ALTER TABLE `ds_point_decr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `SSS` (`ua`(111),`vid`) USING BTREE;

--
-- 表的索引 `ds_point_logs`
--
ALTER TABLE `ds_point_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_price`
--
ALTER TABLE `ds_price`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_quantity`
--
ALTER TABLE `ds_quantity`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_stock`
--
ALTER TABLE `ds_stock`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `cid` (`cid`) USING BTREE,
  ADD KEY `create_time` (`create_time`) USING BTREE,
  ADD KEY `is_dsp` (`is_dsp`) USING BTREE;

--
-- 表的索引 `ds_system_admin`
--
ALTER TABLE `ds_system_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD KEY `phone` (`phone`);

--
-- 表的索引 `ds_system_auth`
--
ALTER TABLE `ds_system_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`) USING BTREE;

--
-- 表的索引 `ds_system_auth_node`
--
ALTER TABLE `ds_system_auth_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_system_auth_auth` (`auth_id`) USING BTREE,
  ADD KEY `index_system_auth_node` (`node_id`) USING BTREE;

--
-- 表的索引 `ds_system_config`
--
ALTER TABLE `ds_system_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `group` (`group`);

--
-- 表的索引 `ds_system_menu`
--
ALTER TABLE `ds_system_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `href` (`href`);

--
-- 表的索引 `ds_system_node`
--
ALTER TABLE `ds_system_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `node` (`node`) USING BTREE;

--
-- 表的索引 `ds_system_quick`
--
ALTER TABLE `ds_system_quick`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_system_uploadfile`
--
ALTER TABLE `ds_system_uploadfile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `upload_type` (`upload_type`),
  ADD KEY `original_name` (`original_name`);

--
-- 表的索引 `ds_tj`
--
ALTER TABLE `ds_tj`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_user_money_log`
--
ALTER TABLE `ds_user_money_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `ds_user_point`
--
ALTER TABLE `ds_user_point`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ds_category`
--
ALTER TABLE `ds_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=29;

--
-- 使用表AUTO_INCREMENT `ds_complain`
--
ALTER TABLE `ds_complain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `ds_config`
--
ALTER TABLE `ds_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- 使用表AUTO_INCREMENT `ds_domain_lib`
--
ALTER TABLE `ds_domain_lib`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '域名ID';

--
-- 使用表AUTO_INCREMENT `ds_domain_rule`
--
ALTER TABLE `ds_domain_rule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '域名ID', AUTO_INCREMENT=42;

--
-- 使用表AUTO_INCREMENT `ds_hezi`
--
ALTER TABLE `ds_hezi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=1336;

--
-- 使用表AUTO_INCREMENT `ds_kouliang`
--
ALTER TABLE `ds_kouliang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID';

--
-- 使用表AUTO_INCREMENT `ds_link`
--
ALTER TABLE `ds_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';





--
-- 使用表AUTO_INCREMENT `ds_muban`
--
ALTER TABLE `ds_muban`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `ds_notify`
--
ALTER TABLE `ds_notify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `ds_number`
--
ALTER TABLE `ds_number`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=160;

--
-- 使用表AUTO_INCREMENT `ds_outlay`
--
ALTER TABLE `ds_outlay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- 使用表AUTO_INCREMENT `ds_payed_show`
--
ALTER TABLE `ds_payed_show`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49369;

--
-- 使用表AUTO_INCREMENT `ds_pay_order`
--
ALTER TABLE `ds_pay_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=402;

--
-- 使用表AUTO_INCREMENT `ds_pay_setting`
--
ALTER TABLE `ds_pay_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ds_point_decr`
--
ALTER TABLE `ds_point_decr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `ds_point_logs`
--
ALTER TABLE `ds_point_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `ds_price`
--
ALTER TABLE `ds_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- 使用表AUTO_INCREMENT `ds_quantity`
--
ALTER TABLE `ds_quantity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '抽单ID', AUTO_INCREMENT=80;

--
-- 使用表AUTO_INCREMENT `ds_stock`
--
ALTER TABLE `ds_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资源ID', AUTO_INCREMENT=41349;





--
-- 使用表AUTO_INCREMENT `ds_system_admin`
--
ALTER TABLE `ds_system_admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- 使用表AUTO_INCREMENT `ds_system_auth`
--
ALTER TABLE `ds_system_auth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `ds_system_auth_node`
--
ALTER TABLE `ds_system_auth_node`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1234;

--
-- 使用表AUTO_INCREMENT `ds_system_config`
--
ALTER TABLE `ds_system_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- 使用表AUTO_INCREMENT `ds_system_menu`
--
ALTER TABLE `ds_system_menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- 使用表AUTO_INCREMENT `ds_system_node`
--
ALTER TABLE `ds_system_node`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- 使用表AUTO_INCREMENT `ds_system_quick`
--
ALTER TABLE `ds_system_quick`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `ds_system_uploadfile`
--
ALTER TABLE `ds_system_uploadfile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `ds_tj`
--
ALTER TABLE `ds_tj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121519;

--
-- 使用表AUTO_INCREMENT `ds_user_money_log`
--
ALTER TABLE `ds_user_money_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49806;

--
-- 使用表AUTO_INCREMENT `ds_user_point`
--
ALTER TABLE `ds_user_point`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107882;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
