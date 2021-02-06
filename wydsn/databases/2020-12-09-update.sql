ALTER TABLE `lailu_ewei_shop_goods`
ADD COLUMN `is_fx_goods`  char(1) NULL DEFAULT 'N' COMMENT '是否开始分销  N否Y是' AFTER `import_id`,
ADD COLUMN `fx_profit_money`  decimal(10,2) NULL DEFAULT 0 COMMENT '初始化分销佣金' AFTER `is_fx_goods`;


ALTER TABLE `lailu_ewei_shop_goods`
ADD COLUMN `isdiscount_time_start`  char(1) NULL DEFAULT 'N' COMMENT '是否开始分销  N否Y是' AFTER `import_id`;

