/*
 Navicat MySQL Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50726
 Source Host           : localhost:3306
 Source Schema         : tao.lailu.live

 Target Server Type    : MySQL
 Target Server Version : 50726
 File Encoding         : 65001

 Date: 13/11/2020 17:16:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for lailu_user_pay
-- ----------------------------
DROP TABLE IF EXISTS `lailu_user_pay`;
CREATE TABLE `lailu_user_pay`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `pay_type` tinyint(1) NOT NULL COMMENT '支付类型（1：支付宝，2：微信）',
  `pay_number` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '支付号码',
  `pay_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付名称',
  `update_time` datetime(0) NULL DEFAULT NULL COMMENT '更新时间',
  `add_time` datetime(0) NULL DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = 'app支付账号' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
