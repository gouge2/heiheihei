ALTER TABLE `lailu_order_detail`
ADD COLUMN `fx_profit_money`  decimal(10,2) NULL DEFAULT 0 COMMENT '分銷佣金' AFTER `allprice`;

CREATE TABLE `lailu_bood_change` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提取金额',
  `status` tinyint(1) DEFAULT '0' COMMENT '默认0待审核 1已审核 2已打款',
  `account_mobile` char(30) NOT NULL COMMENT '打款账户',
  `account_name` varchar(100) NOT NULL COMMENT '账户名称',
  `create_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '打款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

