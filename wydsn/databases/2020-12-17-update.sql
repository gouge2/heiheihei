ALTER TABLE `lailu_order_detail`
ADD COLUMN `goods_sku_name`  varchar(255) NULL COMMENT '订单商品规格信息' AFTER `freight`,
ADD COLUMN `goods_thumb`  varchar(255) NULL COMMENT '订单商品缩略图' AFTER `goods_sku_name`;

ALTER TABLE `lailu_order_detail`
ADD COLUMN `ren_good_id`  int(10) NULL DEFAULT 0 COMMENT '商户端商品ID' AFTER `goods_thumb`;


ALTER TABLE `lailu_ewei_shop_merch_reg`
ADD COLUMN `address`  varchar(255) NULL COMMENT '地址' AFTER `cus_serve`;

