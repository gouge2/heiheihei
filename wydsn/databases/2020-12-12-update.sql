ALTER TABLE `lailu_jingdong_cat`
ADD COLUMN `keyword` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '关键词' AFTER `name`,
ADD COLUMN `is_api` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Y' COMMENT '商品获取方式  Y：调用接口   N：关键词' AFTER `jingdong_id`;

ALTER TABLE `lailu_pdd_cat`
ADD COLUMN `keyword` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '关键词' AFTER `name`,
ADD COLUMN `is_api` char(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Y' COMMENT '商品获取方式  Y：调用接口   N：关键词' AFTER `pdd_id`;
CREATE TABLE `lailu_ewei_shop_verifyorder_log` (
  `id` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `orderid` int(11) DEFAULT NULL,
  `salerid` int(11) DEFAULT NULL,
  `storeid` int(11) DEFAULT NULL,
  `verifytime` int(11) DEFAULT NULL,
  `verifyinfo` longtext,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `orderid` (`orderid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

