SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lailu_country_code
-- ----------------------------
DROP TABLE IF EXISTS `lailu_country_code`;
CREATE TABLE `lailu_country_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` char(20) NOT NULL COMMENT '国家',
  `code` varchar(6) NOT NULL COMMENT '编号',
  `type` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示 1：显示 2：隐藏',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='国家编码表';