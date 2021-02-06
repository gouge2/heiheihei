ALTER TABLE `lailu_goods`
MODIFY COLUMN `price`  float(10,0) UNSIGNED NULL DEFAULT 0.00 COMMENT '实际价格 单位分' AFTER `old_price`;
