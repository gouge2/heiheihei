/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : lailu

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-11-23 05:11:53
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lailu_advert_head
-- ----------------------------
DROP TABLE IF EXISTS `lailu_advert_head`;
CREATE TABLE `lailu_advert_head` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `advert_title` varchar(100) NOT NULL COMMENT '活动名称',
  `advert_img` varchar(200) DEFAULT NULL COMMENT '背景图片',
  `advert_client` varchar(10) NOT NULL COMMENT '客户端',
  `advert_source` varchar(10) NOT NULL COMMENT '数据来源',
  `diy_id` tinyint(2) NOT NULL COMMENT '自定义（1：商品ID，2：分类，3：活动）',
  `advert_catgray` varchar(100) DEFAULT NULL COMMENT '来源分类',
  `advert_cat` int(11) DEFAULT NULL COMMENT '活动类型',
  `advert_cat_id` varchar(200) DEFAULT NULL COMMENT '商品ID',
  `advert_word` varchar(300) DEFAULT NULL COMMENT '关键词',
  `advert_amount_min` varchar(20) DEFAULT NULL COMMENT '最小佣金',
  `advert_amount_max` varchar(20) DEFAULT NULL COMMENT '最大佣金',
  `advert_price_min` varchar(20) DEFAULT NULL COMMENT '最小价格',
  `advert_price_max` varchar(20) DEFAULT NULL COMMENT '最大价格',
  `advert_coupon` tinyint(1) DEFAULT NULL COMMENT '是否有券（1：有，2：没有）',
  `advert_switch` tinyint(1) NOT NULL COMMENT '活动开关（1：开，2：关）',
  `add_time` datetime DEFAULT NULL COMMENT '添加时间',
  `update_time` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='首页活动表';
