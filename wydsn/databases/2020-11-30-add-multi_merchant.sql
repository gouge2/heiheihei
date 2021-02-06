SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lailu_multi_merchant
-- ----------------------------
DROP TABLE IF EXISTS `lailu_multi_merchant`;
CREATE TABLE `lailu_multi_merchant` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '多商户状态 0：关闭 1：开启',
  `settle_in` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '入驻开关 1：关闭 2：开启',
  `authority` text NOT NULL COMMENT '开店权限 0：无权限 1：会员 2：铂金主播 3：钻石主播 4：至尊主播',
  `verified` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启实名认证 1：关闭 2：开启',
  `margin` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '缴纳保证金 1：关闭 2：开启',
  `total_amount` decimal(10,0) unsigned NOT NULL DEFAULT '0' COMMENT '输入金额',
  `payment` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式 0: 未开通 1：支付宝',
  `description` text COMMENT '保证金说明',
  `introduction` text COMMENT '店铺入驻介绍',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of lailu_multi_merchant
-- ----------------------------
INSERT INTO `lailu_multi_merchant` VALUES ('1', '0', '1', '0', '1', '1', '0', '0', '', '');