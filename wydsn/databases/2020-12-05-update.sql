ALTER TABLE `lailu_bood_log`
ADD COLUMN `account_mobile`  varchar(255) NULL DEFAULT NULL COMMENT '打款账户' AFTER `payment`,
ADD COLUMN `account_name`  varchar(255) NULL DEFAULT NULL COMMENT '打款名称' AFTER `account_mobile`;

ALTER TABLE `lailu_order`
ADD COLUMN `shop_id`  int(10) NULL DEFAULT '0' COMMENT '商户ID' AFTER `host_id`;

ALTER TABLE `lailu_order`
ADD COLUMN `is_delete`  tinyint(1) NULL DEFAULT 0 COMMENT '是否删除 0否1是' AFTER `is_delay`;

ALTER TABLE `lailu_order`
ADD COLUMN `main_order_id`  int(10) NULL DEFAULT 0 COMMENT '主订单ID' AFTER `is_delete`;

ALTER TABLE `lailu_order`
ADD COLUMN `ren_order_id`  int(10) NULL DEFAULT 0 COMMENT '商户订单ID' AFTER `main_order_id`;

