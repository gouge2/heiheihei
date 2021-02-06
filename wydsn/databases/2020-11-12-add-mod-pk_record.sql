
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for lailu_live_pk_record
-- ----------------------------
DROP TABLE IF EXISTS `lailu_live_pk_record`;
CREATE TABLE `lailu_live_pk_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '标识',
  `user_id` int(11) NOT NULL COMMENT '打赏的用户ID',
  `other_uid` int(11) unsigned NOT NULL COMMENT '主播用户ID/或受礼用户ID',
  `money` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '主播获得鹿角金额',
  `other_money` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT 'pk方获得鹿角金额',
  `add_time` datetime NOT NULL COMMENT 'pk倒计时开始时间',
  `is_status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT 'pk状态 1：pk中 2：结束pk',
  `room_id` int(11) unsigned NOT NULL COMMENT '房间号',
  `other_room` int(11) unsigned NOT NULL COMMENT 'pk方房间号',
  `end_time` datetime DEFAULT NULL COMMENT 'pk倒计时结束时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='主播pk记录表';
