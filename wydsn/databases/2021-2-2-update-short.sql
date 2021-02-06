ALTER TABLE `lailu_short`
ADD COLUMN `lan_people`  mediumint(9) UNSIGNED NULL DEFAULT 0 COMMENT '虚拟人数' AFTER `sort`,
ADD COLUMN `lan_heat`  mediumint(9) UNSIGNED NULL DEFAULT 0 COMMENT '虚拟热度' AFTER `lan_people`;