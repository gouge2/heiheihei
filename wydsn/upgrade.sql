CREATE TABLE if not exists `lailu_team_rewards_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '团队分红用户ID',
  `buy_id` int(10) unsigned NOT NULL COMMENT '购买商品用户ID',
  `order_id` varchar(50) NOT NULL COMMENT '订单ID',
  `buy_method` varchar(10) DEFAULT NULL COMMENT '购买渠道 tb淘宝 jd京东 pdd拼多多 vip唯品会',
  `rewards_level` INT(1) NOT NULL COMMENT '团队分红级别' ,
  `create_time` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单团队分红记录表';
ALTER TABLE `lailu_user` ADD `is_buy_free_goods` CHAR(1) DEFAULT 'N' COMMENT '是否购买0元购商品 Y是 N否' AFTER `is_buy_free`;
ALTER TABLE `lailu_user_group` ADD `commission` FLOAT(12,2) UNSIGNED DEFAULT '0.00' COMMENT '等级必要佣金' AFTER `exp`;
ALTER TABLE `lailu_banner_cat` ADD `is_delete` CHAR(1) NOT NULL DEFAULT 'Y' COMMENT '是否可删除 Y可删 N不可删' AFTER `title`;
ALTER TABLE `lailu_user` ADD `jd_pid` varchar(50) NOT NULL DEFAULT '' COMMENT 'jd_pid';
ALTER TABLE `lailu_user_draw_apply` ADD `draw_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费';
ALTER TABLE `lailu_user_draw_apply` ADD `real_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '到账金额';


CREATE TABLE IF NOT EXISTS `lailu_bk_cat` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '分类名称',
  `is_delete` char(1) NOT NULL DEFAULT 'Y' COMMENT '是否可删除 Y可删 N不可删',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='宫格版块分类表';

INSERT INTO `lailu_bk_cat` (`id`, `title`, `is_delete`, `createtime`) VALUES
(1, '新人专区', 'N', '2020-06-03 15:29:13'),
(2, '黑六宫格', 'N', '2020-06-03 15:29:13'),
(3, '白五宫格', 'N', '2020-06-03 15:29:24');

CREATE TABLE IF NOT EXISTS `lailu_bk` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT 'ID',
  `cat_id` smallint(5) UNSIGNED NOT NULL COMMENT '分类ID',
  `title` varchar(200) NOT NULL COMMENT '链接名称',
  `img` varchar(100) DEFAULT NULL COMMENT '图片',
  `color` char(7) DEFAULT NULL COMMENT '宫格图颜色',
  `href` varchar(255) DEFAULT NULL COMMENT '链接地址',
  `sort` int(10) UNSIGNED DEFAULT 0 COMMENT '排序',
  `is_show` char(1) DEFAULT 'Y' COMMENT '是否显示 Y显示 N不显示',
  `createtime` datetime NOT NULL COMMENT '创建时间',
  `type` varchar(2) DEFAULT '1' COMMENT '类型',
  `type_value` varchar(30) DEFAULT NULL COMMENT '类型值',
  `agent_id` int(10) UNSIGNED DEFAULT 0 COMMENT '代理商ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='宫格版块表';


INSERT INTO `lailu_bk` (`id`, `cat_id`, `title`, `img`, `color`, `href`, `sort`, `is_show`, `createtime`, `type`, `type_value`, `agent_id`) VALUES
(1, 1, '新人专区背景-1086x426-png', '/Public/bannerfix/5ed8934570541133.png', '', '', 0, 'Y', '2020-06-04 17:47:00', '12', '', 0),
(2, 1, '新手教程-513x267-png', '/Public/bannerfix/5ed89365e2877188.png', '', '', 0, 'Y', '2020-06-04 17:53:35', '13', '', 0),
(3, 1, '新人0元购-513x267-png', '/Public/bannerfix/5ed893568a33d689.png', '', '', 0, 'Y', '2020-06-04 17:54:19', '11', '', 0),
(4, 2, '分享淘口令-519x231-png', '/Public/bannerfix/5ed893815dfa1461.png', '', '', 0, 'Y', '2020-06-04 17:54:37', '14', '', 0),
(5, 2, '限量1元秒杀-519x231-png', '/Public/bannerfix/5ed89390b284d824.png', '', '', 0, 'Y', '2020-06-04 17:54:53', '15', '', 0),
(6, 2, '聚划算榜单-519x231-png', '/Public/bannerfix/bk21591265811126.png', '', '', 0, 'Y', '2020-06-04 18:16:51', '16', '', 0),
(7, 2, '超级券-519x231-png', '/Public/bannerfix/5ed893aa70ccb215.png', '', '', 0, 'Y', '2020-06-04 17:55:18', '17', '', 0),
(8, 2, '达人说-519x231-png', '/Public/bannerfix/5ed893b8deb95780.png', '', '', 0, 'Y', '2020-06-04 17:55:28', '18', '', 0),
(9, 2, '必买清单-519x231-png', '/Public/bannerfix/5ed893c768bce330.png', '', '', 0, 'Y', '2020-06-04 17:55:41', '19', '', 0),
(10, 3, '9.9元购-527x302-png', '/Public/bannerfix/5ed893d5ad99f377.png', '', '', 0, 'Y', '2020-06-05 18:10:28', '20', '', 0),
(11, 3, '限时秒杀-527x302-png', '/Public/bannerfix/5ed893e2ae345279.png', '', '', 0, 'Y', '2020-06-04 17:56:09', '21', '', 0),
(12, 3, '拼多多-345x513-png', '/Public/bannerfix/5ed893f311e8f206.png', '', '', 0, 'Y', '2020-06-04 17:56:18', '22', '', 0),
(13, 3, '今日爆款-345x513-png', '/Public/bannerfix/5ed894072499c859.png', '', '', 0, 'Y', '2020-06-04 17:56:30', '23', '', 0),
(14, 3, '京东大促-345x513-png', '/Public/bannerfix/5ed8941352c5a470.png', '', '', 0, 'Y', '2020-06-04 17:56:38', '24', '', 0);

ALTER TABLE `lailu_user` ADD `agent_switch` CHAR(1) NULL DEFAULT 'N' COMMENT '是否开启代理商配置 Y是 N否 ' AFTER `is_agent`;
ALTER TABLE `lailu_user_group` ADD `fee_user_virtual` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收益虚拟比例-用户' AFTER `fee_user`;
ALTER TABLE `lailu_user_group` ADD `referrer_rate_virtual` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '直推虚拟佣金比例' AFTER `referrer_rate`;
ALTER TABLE `lailu_user_group` ADD `referrer_rate2_virtual` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '间推虚拟佣金比例' AFTER `referrer_rate2`;

