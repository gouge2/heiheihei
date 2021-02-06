ALTER TABLE `lailu_shopcart`
ADD COLUMN `shop_id`  int(10) NULL DEFAULT 0 COMMENT '商家ID  为0是平台直营的' AFTER `user_id`;

ALTER TABLE `lailu_order`
ADD COLUMN `shop_id`  int(10) NULL DEFAULT 0 COMMENT '商户ID' AFTER `host_id`;

CREATE TABLE `lailu_task_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL DEFAULT '0' COMMENT '订单ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

