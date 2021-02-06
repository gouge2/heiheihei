ALTER TABLE `lailu_bbs_article`
ADD COLUMN `source` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '商品来源' AFTER `share_num`;