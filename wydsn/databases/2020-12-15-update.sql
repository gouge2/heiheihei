CREATE TABLE `lailu_core_cache` (
  `key` varchar(100) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `lailu_order_detail`
ADD COLUMN `freight`  int(10) NULL DEFAULT 0 COMMENT '运费' AFTER `sku`;

ALTER TABLE `lailu_order`
ADD COLUMN `freight`  int(10) NULL DEFAULT 0 COMMENT '运费' AFTER `drawback_refuse_reason`;

