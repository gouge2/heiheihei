ALTER TABLE `lailu_goods`
ADD COLUMN `profit_money`  int NULL DEFAULT 0 COMMENT '礼包初始佣金金额' AFTER `group_id`;

