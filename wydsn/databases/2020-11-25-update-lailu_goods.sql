ALTER TABLE `lailu_goods`
ADD COLUMN `is_fx_goods`  char(1) DEFAULT 'N' COMMENT '是否开启分销 Y是N否' AFTER `is_gift_goods`;

ALTER TABLE `lailu_goods`
ADD COLUMN `fx_profit_money`  int NULL DEFAULT 0 COMMENT '商品初始分销金额' AFTER `profit_money`;

