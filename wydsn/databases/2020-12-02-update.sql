ALTER TABLE `lailu_goods`
ADD COLUMN `shop_id`  int(10) NULL DEFAULT 0 COMMENT '商户ID' AFTER `cat_id`;

