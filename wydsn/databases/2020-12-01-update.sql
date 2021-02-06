CREATE TABLE `lailu_bood` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '所属用户ID',
  `bood` int(10) NOT NULL DEFAULT '0' COMMENT '保证金',
  `status` tinyint(1) NOT NULL COMMENT '状态值 0 冻结  1可用',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `lailu_bood_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_sn` varchar(255) NOT NULL COMMENT '编号',
  `user_id` int(10) NOT NULL,
  `bood_money` decimal(10,2) NOT NULL,
  `payment` enum('banlance','alipay','wxpay') NOT NULL DEFAULT 'alipay',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `pay_status` tinyint(1) DEFAULT '0' COMMENT '0待支付 1已支付',
  `create_time` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

