/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : lailu

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-12-08 07:16:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lailu_ico_setting
-- ----------------------------
DROP TABLE IF EXISTS `lailu_ico_setting`;
CREATE TABLE `lailu_ico_setting` (
  `ico_id` int(11) NOT NULL AUTO_INCREMENT,
  `ico_name` varchar(30) DEFAULT NULL COMMENT 'ico名称',
  `ico_image` varchar(200) DEFAULT NULL COMMENT '图标',
  `ico_url` varchar(100) DEFAULT NULL COMMENT '地址',
  `sort` tinyint(3) DEFAULT '0' COMMENT '排序id',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示  1：是  0：否',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '是否删除  1：是  0：否',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`ico_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='ico配置表';

INSERT INTO `lailu_ico_setting` VALUES (1, '自营商城', '/Public/Upload/ico/5fd313370a6b7227.png', 'mall/proprietary_mall/proprietary_mall', 100, 1, 0, '2020-12-11 11:39:27');
INSERT INTO `lailu_ico_setting` VALUES (2, '拼多多', '/Public/Upload/ico/5fd2ea089cd57308.png', 'mall/pinddList/pinddList?type=pdd', 99, 1, 0, '2020-12-11 11:39:56');
INSERT INTO `lailu_ico_setting` VALUES (3, '京东', '/Public/Upload/ico/5fd2ea2595df4850.png', 'mall/pinddList/pinddList?type=jd', 98, 1, 0, '2020-12-11 11:40:37');
INSERT INTO `lailu_ico_setting` VALUES (4, '每日签到', '/Public/Upload/ico/5fd2ea6139846785.png', 'mall/sign_in/sign_in', 97, 1, 0, '2020-12-11 11:41:23');