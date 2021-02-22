ALTER TABLE `lailu_live_room`
ADD COLUMN `heartbeat_time`  datetime NULL DEFAULT NULL COMMENT '直播心跳监听' AFTER `sort`;