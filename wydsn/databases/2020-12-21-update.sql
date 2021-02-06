CREATE TABLE `lailu_user_porify_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  `proify_money` decimal(10,2) DEFAULT '0.00' COMMENT '自购佣金',
  `host_id` int(10) DEFAULT '0' COMMENT '主播ID',
  `host_proify_money` decimal(10,2) DEFAULT '0.00' COMMENT '主播佣金',
  `host1_id` int(10) DEFAULT '0' COMMENT '一级经纪人ID',
  `host1_proify_money` decimal(10,2) DEFAULT '0.00' COMMENT '一级经纪人佣金',
  `host2_id` int(10) DEFAULT '0' COMMENT '2级经纪人ID',
  `host2_proify_money` decimal(10,0) DEFAULT '0' COMMENT '2级经纪人佣金',
  `team_id` int(10) DEFAULT '0' COMMENT '团队ID',
  `team_proify_money` decimal(10,2) DEFAULT '0.00' COMMENT '团队佣金',
  `team2_id` int(10) DEFAULT '0' COMMENT '团队2级ID',
  `team2_proify_money` decimal(10,2) DEFAULT NULL COMMENT '团队2级佣金',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

