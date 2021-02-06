SET FOREIGN_KEY_CHECKS=0;

ALTER TABLE `lailu_ewei_message_mass_task` MODIFY COLUMN `resdesc2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `customertype`;

ALTER TABLE `lailu_ewei_open_farm_advertisement` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_open_farm_advertisement` MODIFY COLUMN `uniacid`  int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `id`;

ALTER TABLE `lailu_ewei_shop_abonus_bill` ADD COLUMN `ceshi`  int(11) NULL DEFAULT NULL AFTER `confirmtime`;

ALTER TABLE `lailu_ewei_shop_abonus_bill` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_abonus_billp` MODIFY COLUMN `paytype`  tinyint(4) NULL DEFAULT 0 AFTER `payno`;

ALTER TABLE `lailu_ewei_shop_abonus_billp` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `charge`;

ALTER TABLE `lailu_ewei_shop_area_config` MODIFY COLUMN `new_area`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_area_config` MODIFY COLUMN `address_street`  tinyint(4) NOT NULL DEFAULT 0 AFTER `new_area`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `page_set_option_nocopy`  int(11) NOT NULL DEFAULT 0 AFTER `article_rule_money`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `page_set_option_noshare_tl`  int(11) NOT NULL DEFAULT 0 AFTER `page_set_option_nocopy`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `page_set_option_noshare_msg`  int(11) NOT NULL DEFAULT 0 AFTER `page_set_option_noshare_tl`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `article_report`  int(11) NOT NULL DEFAULT 0 AFTER `article_keyword2`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `product_advs_type`  int(11) NOT NULL DEFAULT 0 AFTER `article_report`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `article_state`  int(11) NOT NULL DEFAULT 0 AFTER `product_advs`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `article_hasendtime`  tinyint(4) NULL DEFAULT 0 AFTER `article_endtime`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `article_virtualadd`  tinyint(4) NULL DEFAULT 0 AFTER `article_advance`;

ALTER TABLE `lailu_ewei_shop_article` MODIFY COLUMN `article_visit`  tinyint(4) NULL DEFAULT 0 AFTER `article_virtualadd`;

ALTER TABLE `lailu_ewei_shop_article_comment` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_author_bill` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_author_billp` MODIFY COLUMN `paytype`  tinyint(4) NULL DEFAULT 0 AFTER `payno`;

ALTER TABLE `lailu_ewei_shop_author_billp` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `chargemoney`;

ALTER TABLE `lailu_ewei_shop_bargain_actor` MODIFY COLUMN `bargain_times`  int(11) NOT NULL AFTER `update_time`;

ALTER TABLE `lailu_ewei_shop_bargain_actor` MODIFY COLUMN `status`  tinyint(4) NOT NULL AFTER `bargain_price`;

ALTER TABLE `lailu_ewei_shop_bargain_goods` MODIFY COLUMN `status`  tinyint(4) NOT NULL AFTER `end_time`;

ALTER TABLE `lailu_ewei_shop_bargain_goods` MODIFY COLUMN `type`  tinyint(4) NOT NULL AFTER `status`;

ALTER TABLE `lailu_ewei_shop_bargain_goods` MODIFY COLUMN `myself`  tinyint(4) NULL DEFAULT 0 AFTER `initiate`;

ALTER TABLE `lailu_ewei_shop_cashier_clearing` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `clearno`;

ALTER TABLE `lailu_ewei_shop_cashier_clearing` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `paytime`;

ALTER TABLE `lailu_ewei_shop_cashier_pay_log` MODIFY COLUMN `paytype`  tinyint(4) NULL DEFAULT NULL AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_cashier_randommoney_log` MODIFY COLUMN `clientip`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `cashierid`;

ALTER TABLE `lailu_ewei_shop_cashier_user` MODIFY COLUMN `setmeal`  tinyint(4) NULL DEFAULT 0 AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_category` MODIFY COLUMN `isrecommand`  int(11) NULL DEFAULT 0 AFTER `parentid`;

ALTER TABLE `lailu_ewei_shop_category` MODIFY COLUMN `ishome`  tinyint(4) NULL DEFAULT 0 AFTER `enabled`;

ALTER TABLE `lailu_ewei_shop_category` MODIFY COLUMN `level`  tinyint(4) NULL DEFAULT NULL AFTER `ishome`;

CREATE TABLE `lailu_ewei_shop_category_copy_1584458045` (
`id`  int(11) NOT NULL DEFAULT 0 ,
`uniacid`  int(11) NULL DEFAULT 0 ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`parentid`  int(11) NULL DEFAULT 0 ,
`isrecommand`  int(11) NULL DEFAULT 0 ,
`description`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`displayorder`  tinyint(3) UNSIGNED NULL DEFAULT 0 ,
`enabled`  tinyint(1) NULL DEFAULT 1 ,
`ishome`  tinyint(4) NULL DEFAULT 0 ,
`level`  tinyint(4) NULL DEFAULT NULL ,
`advimg`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`advurl`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT ''
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_commission_apply` ADD COLUMN `bankopen`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `senddata`;

ALTER TABLE `lailu_ewei_shop_commission_apply` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `mid`;

ALTER TABLE `lailu_ewei_shop_commission_apply` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `content`;

ALTER TABLE `lailu_ewei_shop_commission_level` ADD COLUMN `withdraw`  decimal(10,2) NULL DEFAULT NULL AFTER `level`;

ALTER TABLE `lailu_ewei_shop_commission_level` ADD COLUMN `repurchase`  decimal(10,2) NULL DEFAULT NULL AFTER `withdraw`;

ALTER TABLE `lailu_ewei_shop_commission_level` MODIFY COLUMN `downcount`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `ordercount`;

ALTER TABLE `lailu_ewei_shop_commission_level` MODIFY COLUMN `goodsids_text`  varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `goodsids`;

ALTER TABLE `lailu_ewei_shop_commission_level` MODIFY COLUMN `level`  int(11) NULL DEFAULT NULL AFTER `goodsids_text`;

ALTER TABLE `lailu_ewei_shop_commission_log` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `deductionmoney`;

ALTER TABLE `lailu_ewei_shop_commission_relation` MODIFY COLUMN `level`  tinyint(3) UNSIGNED NOT NULL AFTER `pid`;

ALTER TABLE `lailu_ewei_shop_commission_repurchase` MODIFY COLUMN `year`  int(11) NULL DEFAULT 0 AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_commission_repurchase` MODIFY COLUMN `month`  tinyint(4) NULL DEFAULT 0 AFTER `year`;

ALTER TABLE `lailu_ewei_shop_commission_shop` MODIFY COLUMN `selectgoods`  tinyint(4) NULL DEFAULT 0 AFTER `desc`;

ALTER TABLE `lailu_ewei_shop_commission_shop` MODIFY COLUMN `selectcategory`  tinyint(4) NULL DEFAULT 0 AFTER `selectgoods`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `gettype`  tinyint(4) NULL DEFAULT 0 AFTER `couponname`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `usetype`  tinyint(4) NULL DEFAULT 0 AFTER `getmax`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `returntype`  tinyint(4) NULL DEFAULT 0 AFTER `usetype`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `timelimit`  tinyint(4) NULL DEFAULT 0 AFTER `enough`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `coupontype`  tinyint(4) NULL DEFAULT 0 AFTER `timelimit`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `backtype`  tinyint(4) NULL DEFAULT 0 AFTER `deduct`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `backwhen`  tinyint(4) NULL DEFAULT 0 AFTER `backredpack`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `total`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `usecredit2`  tinyint(4) NULL DEFAULT 0 AFTER `credit`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `descnoset`  tinyint(4) NULL DEFAULT 0 AFTER `remark`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `pwdstatus`  tinyint(4) NULL DEFAULT 0 AFTER `pwdask`;

ALTER TABLE `lailu_ewei_shop_coupon` MODIFY COLUMN `pwdopen`  tinyint(4) NULL DEFAULT 0 AFTER `pwdwords`;

ALTER TABLE `lailu_ewei_shop_coupon_data` MODIFY COLUMN `gettype`  tinyint(4) NULL DEFAULT 0 AFTER `couponid`;

ALTER TABLE `lailu_ewei_shop_coupon_data` MODIFY COLUMN `back`  tinyint(4) NULL DEFAULT 0 AFTER `ordersn`;

ALTER TABLE `lailu_ewei_shop_coupon_guess` MODIFY COLUMN `ok`  tinyint(4) NULL DEFAULT 0 AFTER `pwdkey`;

ALTER TABLE `lailu_ewei_shop_coupon_log` MODIFY COLUMN `paystatus`  tinyint(4) NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_coupon_log` MODIFY COLUMN `creditstatus`  tinyint(4) NULL DEFAULT 0 AFTER `paystatus`;

ALTER TABLE `lailu_ewei_shop_coupon_log` MODIFY COLUMN `paytype`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_coupon_log` MODIFY COLUMN `getfrom`  tinyint(4) NULL DEFAULT 0 AFTER `paytype`;

CREATE TABLE `lailu_ewei_shop_coupon_record` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`coupon_id`  int(11) NOT NULL ,
`openid`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`mid`  int(11) NOT NULL ,
`record_id`  int(11) NOT NULL ,
`add_time`  int(11) NOT NULL ,
PRIMARY KEY (`id`),
INDEX `add_time` (`add_time`) USING BTREE ,
INDEX `coupon_id` (`coupon_id`) USING BTREE ,
INDEX `coupon_id_2` (`coupon_id`) USING BTREE ,
INDEX `openid` (`openid`) USING BTREE ,
INDEX `openid_2` (`openid`) USING BTREE ,
INDEX `record_id` (`record_id`) USING BTREE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_creditshop_category` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `advurl`;

ALTER TABLE `lailu_ewei_shop_creditshop_comment` MODIFY COLUMN `level`  tinyint(4) NOT NULL DEFAULT 0 AFTER `headimg`;

ALTER TABLE `lailu_ewei_shop_creditshop_comment` MODIFY COLUMN `istop`  tinyint(4) NOT NULL DEFAULT 0 AFTER `append_reply_time`;

ALTER TABLE `lailu_ewei_shop_creditshop_comment` MODIFY COLUMN `checked`  tinyint(4) NOT NULL DEFAULT 0 AFTER `istop`;

ALTER TABLE `lailu_ewei_shop_creditshop_comment` MODIFY COLUMN `append_checked`  tinyint(4) NOT NULL DEFAULT 0 AFTER `checked`;

ALTER TABLE `lailu_ewei_shop_creditshop_comment` MODIFY COLUMN `virtual`  tinyint(4) NOT NULL DEFAULT 0 AFTER `append_checked`;

ALTER TABLE `lailu_ewei_shop_creditshop_comment` MODIFY COLUMN `deleted`  tinyint(4) NOT NULL DEFAULT 0 AFTER `virtual`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `hascommission`  tinyint(4) NULL DEFAULT NULL AFTER `maxpacketmoney`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `nocommission`  tinyint(4) NULL DEFAULT NULL AFTER `hascommission`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `commission`  decimal(10,2) NULL DEFAULT NULL AFTER `nocommission`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `commission1_rate`  decimal(10,2) NULL DEFAULT NULL AFTER `commission`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `marketprice`  decimal(10,2) NULL DEFAULT NULL AFTER `commission1_rate`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `commission1_pay`  decimal(10,2) NULL DEFAULT NULL AFTER `marketprice`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` ADD COLUMN `maxprice`  mediumint(9) NULL DEFAULT NULL AFTER `commission1_pay`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `price`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `vip`  tinyint(4) NULL DEFAULT 0 AFTER `buygroups`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `istop`  tinyint(4) NULL DEFAULT 0 AFTER `vip`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `istop`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `istime`  tinyint(4) NULL DEFAULT 0 AFTER `isrecommand`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `followneed`  tinyint(4) NULL DEFAULT 0 AFTER `share_desc`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `isendtime`  tinyint(4) NULL DEFAULT 0 AFTER `goodsdetail`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `usecredit2`  tinyint(4) NULL DEFAULT 0 AFTER `isendtime`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `noticetype`  tinyint(4) NULL DEFAULT 0 AFTER `noticeopenid`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `noticetype`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `goodstype`  tinyint(4) NULL DEFAULT 0 AFTER `isverify`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `dispatchtype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `maxmoney`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `verifytype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `dispatchid`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `showtotal`  tinyint(4) NOT NULL AFTER `weight`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `totalcnf`  tinyint(4) NOT NULL DEFAULT 0 AFTER `showtotal`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `hasoption`  tinyint(4) NOT NULL DEFAULT 0 AFTER `usetime`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `noticedetailshow`  tinyint(4) NOT NULL DEFAULT 0 AFTER `hasoption`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `detailshow`  tinyint(4) NOT NULL DEFAULT 0 AFTER `noticedetailshow`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `packettype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `packetlimit`;

ALTER TABLE `lailu_ewei_shop_creditshop_goods` MODIFY COLUMN `maxpacketmoney`  decimal(10,2) NULL DEFAULT NULL AFTER `packetsurplus`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `paystatus`  tinyint(4) NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `paytype`  tinyint(4) NULL DEFAULT '-1' AFTER `paystatus`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `dispatchstatus`  tinyint(4) NULL DEFAULT 0 AFTER `paytype`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `creditpay`  tinyint(4) NULL DEFAULT 0 AFTER `dispatchstatus`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `dupdate1`  tinyint(4) NULL DEFAULT 0 AFTER `couponid`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `iscomment`  tinyint(4) NOT NULL DEFAULT 0 AFTER `time_finish`;

ALTER TABLE `lailu_ewei_shop_creditshop_log` MODIFY COLUMN `merchapply`  tinyint(4) NULL DEFAULT NULL AFTER `goods_num`;

ALTER TABLE `lailu_ewei_shop_creditshop_option` MODIFY COLUMN `goodsid`  int(11) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_creditshop_option` MODIFY COLUMN `credit`  int(11) NOT NULL DEFAULT 0 AFTER `thumb`;

ALTER TABLE `lailu_ewei_shop_creditshop_spec` MODIFY COLUMN `displaytype`  tinyint(4) NULL DEFAULT 0 AFTER `description`;

ALTER TABLE `lailu_ewei_shop_creditshop_verify` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `verifier`;

ALTER TABLE `lailu_ewei_shop_customer_guestbook` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `remark`;

ALTER TABLE `lailu_ewei_shop_customer_guestbook` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_cycelbuy_periods` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `receipttime`;

ALTER TABLE `lailu_ewei_shop_cycelbuy_periods` MODIFY COLUMN `dispatchtype`  tinyint(4) NULL DEFAULT NULL AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_designer` MODIFY COLUMN `pagetype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `pagename`;

ALTER TABLE `lailu_ewei_shop_designer` MODIFY COLUMN `setdefault`  tinyint(4) NOT NULL DEFAULT 0 AFTER `savetime`;

CREATE INDEX `idx_keyword` ON `lailu_ewei_shop_designer`(`keyword`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_designer_menu` MODIFY COLUMN `isdefault`  tinyint(4) NULL DEFAULT 0 AFTER `menuname`;

ALTER TABLE `lailu_ewei_shop_dispatch` MODIFY COLUMN `isdispatcharea`  tinyint(4) NOT NULL DEFAULT 0 AFTER `nodispatchareas_code`;

ALTER TABLE `lailu_ewei_shop_dividend_apply` ADD COLUMN `bankopen`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `senddata`;

ALTER TABLE `lailu_ewei_shop_dividend_apply` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `mid`;

ALTER TABLE `lailu_ewei_shop_dividend_apply` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `content`;

ALTER TABLE `lailu_ewei_shop_dividend_log` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `deductionmoney`;

ALTER TABLE `lailu_ewei_shop_dividend_log` MODIFY COLUMN `type1`  tinyint(4) NOT NULL DEFAULT 0 AFTER `type`;

ALTER TABLE `lailu_ewei_shop_diyform_data` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_diypage_plu` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `type`;

ALTER TABLE `lailu_ewei_shop_diypage_template` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_diypage_template` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `cate`;

ALTER TABLE `lailu_ewei_shop_exchange_cart` MODIFY COLUMN `total`  int(11) NULL DEFAULT 1 AFTER `goodsid`;

ALTER TABLE `lailu_ewei_shop_exchange_code` MODIFY COLUMN `status`  int(11) NOT NULL DEFAULT 1 AFTER `endtime`;

ALTER TABLE `lailu_ewei_shop_exchange_group` MODIFY COLUMN `type`  int(11) NOT NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_exchange_group` MODIFY COLUMN `mode`  int(11) NOT NULL DEFAULT 0 AFTER `endtime`;

ALTER TABLE `lailu_ewei_shop_exchange_group` MODIFY COLUMN `status`  int(11) NOT NULL DEFAULT 0 AFTER `mode`;

ALTER TABLE `lailu_ewei_shop_exchange_group` MODIFY COLUMN `max`  int(11) NOT NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_exchange_setting` MODIFY COLUMN `no_qrimg`  tinyint(4) NOT NULL DEFAULT 1 AFTER `alllimit`;

ALTER TABLE `lailu_ewei_shop_exchange_setting` DROP PRIMARY KEY;

ALTER TABLE `lailu_ewei_shop_exchange_setting` ADD PRIMARY KEY (`id`);

ALTER TABLE `lailu_ewei_shop_exhelper_esheet` MODIFY COLUMN `datas`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `code`;

ALTER TABLE `lailu_ewei_shop_exhelper_esheet_temp` MODIFY COLUMN `paytype`  tinyint(4) NOT NULL DEFAULT 1 AFTER `sendsite`;

ALTER TABLE `lailu_ewei_shop_exhelper_esheet_temp` MODIFY COLUMN `isnotice`  tinyint(4) NOT NULL DEFAULT 0 AFTER `templatesize`;

ALTER TABLE `lailu_ewei_shop_exhelper_esheet_temp` MODIFY COLUMN `issend`  tinyint(4) NOT NULL DEFAULT 1 AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_exhelper_esheet_temp` MODIFY COLUMN `isdefault`  tinyint(4) NOT NULL DEFAULT 0 AFTER `issend`;

ALTER TABLE `lailu_ewei_shop_exhelper_express` MODIFY COLUMN `type`  int(11) NOT NULL DEFAULT 1 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_exhelper_express` MODIFY COLUMN `isdefault`  tinyint(4) NULL DEFAULT 0 AFTER `bg`;

ALTER TABLE `lailu_ewei_shop_exhelper_senduser` MODIFY COLUMN `isdefault`  tinyint(4) NULL DEFAULT 0 AFTER `sendercity`;

ALTER TABLE `lailu_ewei_shop_exhelper_sys` MODIFY COLUMN `is_cloud`  int(11) NOT NULL DEFAULT 0 AFTER `port_cloud`;

ALTER TABLE `lailu_ewei_shop_express` ENGINE=MyISAM,
ROW_FORMAT=Dynamic;

ALTER TABLE `lailu_ewei_shop_form` MODIFY COLUMN `isrequire`  tinyint(4) NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_friendcoupon` MODIFY COLUMN `people_count`  int(11) NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_friendcoupon` MODIFY COLUMN `allocate`  tinyint(3) UNSIGNED NULL DEFAULT 0 AFTER `duration`;

ALTER TABLE `lailu_ewei_shop_friendcoupon` MODIFY COLUMN `limitgoodtype`  tinyint(3) UNSIGNED NULL DEFAULT 0 AFTER `limitgoodcateids`;

ALTER TABLE `lailu_ewei_shop_friendcoupon` MODIFY COLUMN `deleted`  tinyint(3) UNSIGNED NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_friendcoupon` MODIFY COLUMN `stop_time`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `deleted`;

ALTER TABLE `lailu_ewei_shop_friendcoupon_data` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_friendcoupon_data` MODIFY COLUMN `is_send`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `deadline`;

ALTER TABLE `lailu_ewei_shop_fullback_goods` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_fullback_goods` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `fullbackratio`;

ALTER TABLE `lailu_ewei_shop_fullback_goods` MODIFY COLUMN `hasoption`  tinyint(4) NOT NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_fullback_goods` MODIFY COLUMN `refund`  tinyint(4) NOT NULL DEFAULT 0 AFTER `startday`;

ALTER TABLE `lailu_ewei_shop_fullback_log` MODIFY COLUMN `day`  int(11) NOT NULL AFTER `priceevery`;

ALTER TABLE `lailu_ewei_shop_fullback_log` MODIFY COLUMN `fullbackday`  int(11) NOT NULL AFTER `day`;

ALTER TABLE `lailu_ewei_shop_fullback_log` MODIFY COLUMN `createtime`  int(11) NOT NULL AFTER `fullbackday`;

ALTER TABLE `lailu_ewei_shop_fullback_log` MODIFY COLUMN `fullbacktime`  int(11) NOT NULL AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_fullback_log` MODIFY COLUMN `isfullback`  tinyint(4) NOT NULL DEFAULT 0 AFTER `fullbacktime`;

ALTER TABLE `lailu_ewei_shop_fullback_log_map` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_gift` MODIFY COLUMN `activity`  tinyint(4) NOT NULL DEFAULT 1 AFTER `thumb`;

ALTER TABLE `lailu_ewei_shop_gift` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `endtime`;

ALTER TABLE `lailu_ewei_shop_globonus_bill` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_globonus_billp` MODIFY COLUMN `paytype`  tinyint(4) NULL DEFAULT 0 AFTER `payno`;

ALTER TABLE `lailu_ewei_shop_globonus_billp` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `chargemoney`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `isdiscount_time_start`  int(11) NULL DEFAULT NULL AFTER `import_id`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `456wd_id`  int(11) NOT NULL AFTER `isdiscount_time_start`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `wd_new`  int(11) NOT NULL AFTER `456wd_id`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `zq_Id`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `wd_new`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `zq_Id02`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `zq_Id`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `zq_Source`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `zq_Id02`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate42392`  tinyint(4) NULL DEFAULT NULL AFTER `zq_Source`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate42392`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `minpriceupdated`  tinyint(1) NULL DEFAULT NULL AFTER `saleupdate`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate40170`  tinyint(4) NULL DEFAULT NULL AFTER `minpriceupdated`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate35843`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate40170`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate33219`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate35843`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate32484`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate33219`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate36586`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate32484`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate53481`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate36586`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `saleupdate30424`  tinyint(4) NULL DEFAULT NULL AFTER `saleupdate53481`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `video_type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `saleupdate30424`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `video_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `video_type`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `end_video_url`  tinytext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `video_url`;

ALTER TABLE `lailu_ewei_shop_goods` ADD COLUMN `video_cut`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `end_video_url`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `total`  int(11) NULL DEFAULT 0 AFTER `originalprice`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `viewcount`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `hascommission`  tinyint(4) NULL DEFAULT 0 AFTER `deleted`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `cash`  tinyint(4) NULL DEFAULT 0 AFTER `share_icon`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isnodiscount`  tinyint(4) NULL DEFAULT 0 AFTER `commission_thumb`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `buygroups`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `needfollow`  tinyint(4) NULL DEFAULT 0 AFTER `noticetype`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `nocommission`  tinyint(4) NULL DEFAULT 0 AFTER `discounts`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `hidecommission`  tinyint(4) NULL DEFAULT 0 AFTER `nocommission`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `saleupdate37975`  tinyint(4) NULL DEFAULT 0 AFTER `manydeduct`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `invoice`  tinyint(4) NULL DEFAULT 0 AFTER `minbuy`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `repair`  tinyint(4) NULL DEFAULT 0 AFTER `invoice`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `seven`  tinyint(4) NULL DEFAULT 0 AFTER `repair`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `saleupdate51117`  tinyint(4) NULL DEFAULT 0 AFTER `buycontent`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `quality`  tinyint(4) NULL DEFAULT 0 AFTER `diysave`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `groupstype`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `quality`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `showtotal`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `groupstype`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `checked`  tinyint(4) NULL DEFAULT 0 AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `thumb_first`  tinyint(4) NULL DEFAULT 0 AFTER `checked`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `cannotrefund`  tinyint(4) NULL DEFAULT 0 AFTER `autoreceive`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isendtime`  tinyint(4) NOT NULL DEFAULT 0 AFTER `cashier`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `ispresell`  tinyint(4) NOT NULL DEFAULT 0 AFTER `exchange_postage`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `presellover`  tinyint(4) NOT NULL DEFAULT 0 AFTER `presellprice`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `presellstart`  tinyint(4) NOT NULL DEFAULT 0 AFTER `presellovertime`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `presellend`  tinyint(4) NOT NULL DEFAULT 0 AFTER `preselltimestart`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `presellsendtype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `preselltimeend`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `unite_total`  tinyint(4) NOT NULL DEFAULT 0 AFTER `edareas_code`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isfullback`  tinyint(4) NOT NULL DEFAULT 0 AFTER `intervalprice`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isstatustime`  tinyint(4) NOT NULL DEFAULT 0 AFTER `isfullback`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `statustimestart`  int(11) NOT NULL DEFAULT 0 AFTER `isstatustime`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `statustimeend`  int(11) NOT NULL DEFAULT 0 AFTER `statustimestart`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `showsales`  tinyint(4) NOT NULL DEFAULT 1 AFTER `nosearch`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isstoreprice`  tinyint(4) NOT NULL DEFAULT 0 AFTER `tempid`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `newgoods`  tinyint(4) NOT NULL DEFAULT 0 AFTER `beforehours`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `verifygoodstype`  tinyint(1) NULL DEFAULT NULL AFTER `officthumb`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `isforceverifystore`  tinyint(4) NULL DEFAULT NULL AFTER `verifygoodstype`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `membercardpoint`  int(11) NULL DEFAULT NULL AFTER `manydeduct2`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `refund`  tinyint(4) NULL DEFAULT NULL AFTER `membercardpoint`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `returngoods`  tinyint(4) NULL DEFAULT NULL AFTER `refund`;

ALTER TABLE `lailu_ewei_shop_goods` MODIFY COLUMN `exchange`  tinyint(4) NULL DEFAULT NULL AFTER `returngoods`;

CREATE INDEX `zq_Id` ON `lailu_ewei_shop_goods`(`zq_Id`) USING BTREE ;

CREATE INDEX `zq_Id02` ON `lailu_ewei_shop_goods`(`zq_Id02`) USING BTREE ;

CREATE INDEX `zq_Source` ON `lailu_ewei_shop_goods`(`zq_Source`) USING BTREE ;

CREATE INDEX `idx_tcate` ON `lailu_ewei_shop_goods`(`tcate`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_goodscode_good` MODIFY COLUMN `status`  tinyint(4) NOT NULL AFTER `qrcode`;

ALTER TABLE `lailu_ewei_shop_goods_comment` MODIFY COLUMN `goodsid`  int(11) NULL DEFAULT 0 AFTER `uniacid`;

CREATE TABLE `lailu_ewei_shop_goods_copy_1584458045` (
`id`  int(11) NOT NULL DEFAULT 0 ,
`uniacid`  int(11) NULL DEFAULT 0 ,
`pcate`  int(11) NULL DEFAULT 0 ,
`ccate`  int(11) NULL DEFAULT 0 ,
`tcate`  int(11) NULL DEFAULT 0 ,
`type`  tinyint(1) NULL DEFAULT 1 ,
`status`  tinyint(1) NULL DEFAULT 1 ,
`displayorder`  int(11) NULL DEFAULT 0 ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`unit`  varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`description`  varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`content`  longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`goodssn`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`productsn`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`productprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`marketprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`costprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`originalprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`total`  int(11) NULL DEFAULT 0 ,
`totalcnf`  int(11) NULL DEFAULT 0 ,
`sales`  int(11) NULL DEFAULT 0 ,
`salesreal`  int(11) NULL DEFAULT 0 ,
`spec`  varchar(5000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`createtime`  int(11) NULL DEFAULT 0 ,
`weight`  decimal(10,2) NULL DEFAULT 0.00 ,
`credit`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`maxbuy`  int(11) NULL DEFAULT 0 ,
`usermaxbuy`  int(11) NULL DEFAULT 0 ,
`hasoption`  int(11) NULL DEFAULT 0 ,
`dispatch`  int(11) NULL DEFAULT 0 ,
`thumb_url`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`isnew`  tinyint(1) NULL DEFAULT 0 ,
`ishot`  tinyint(1) NULL DEFAULT 0 ,
`isdiscount`  tinyint(1) NULL DEFAULT 0 ,
`isdiscount_title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`isdiscount_time`  int(11) NULL DEFAULT 0 ,
`isdiscount_discounts`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`isrecommand`  tinyint(1) NULL DEFAULT 0 ,
`issendfree`  tinyint(1) NULL DEFAULT 0 ,
`istime`  tinyint(1) NULL DEFAULT 0 ,
`iscomment`  tinyint(1) NULL DEFAULT 0 ,
`timestart`  int(11) NULL DEFAULT 0 ,
`timeend`  int(11) NULL DEFAULT 0 ,
`viewcount`  int(11) NULL DEFAULT 0 ,
`deleted`  tinyint(4) NULL DEFAULT 0 ,
`hascommission`  tinyint(4) NULL DEFAULT 0 ,
`commission1_rate`  decimal(10,2) NULL DEFAULT 0.00 ,
`commission1_pay`  decimal(10,2) NULL DEFAULT 0.00 ,
`commission2_rate`  decimal(10,2) NULL DEFAULT 0.00 ,
`commission2_pay`  decimal(10,2) NULL DEFAULT 0.00 ,
`commission3_rate`  decimal(10,2) NULL DEFAULT 0.00 ,
`commission3_pay`  decimal(10,2) NULL DEFAULT 0.00 ,
`commission`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`score`  decimal(10,2) NULL DEFAULT 0.00 ,
`catch_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`catch_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`catch_source`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`updatetime`  int(11) NULL DEFAULT 0 ,
`share_title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`share_icon`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`cash`  tinyint(4) NULL DEFAULT 0 ,
`commission_thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`isnodiscount`  tinyint(4) NULL DEFAULT 0 ,
`showlevels`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`buylevels`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`showgroups`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`buygroups`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`isverify`  tinyint(4) NULL DEFAULT 0 ,
`storeids`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`noticeopenid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`noticetype`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`needfollow`  tinyint(4) NULL DEFAULT 0 ,
`followurl`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`followtip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`deduct`  decimal(10,2) NULL DEFAULT 0.00 ,
`shorttitle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`virtual`  int(11) NULL DEFAULT 0 ,
`ccates`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`discounts`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`nocommission`  tinyint(4) NULL DEFAULT 0 ,
`hidecommission`  tinyint(4) NULL DEFAULT 0 ,
`pcates`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`tcates`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`detail_logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`detail_shopname`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`detail_totaltitle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`detail_btntext1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`detail_btnurl1`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`detail_btntext2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`detail_btnurl2`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`cates`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`artid`  int(11) NULL DEFAULT 0 ,
`deduct2`  decimal(10,2) NULL DEFAULT 0.00 ,
`ednum`  int(11) NULL DEFAULT 0 ,
`edareas`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`edmoney`  decimal(10,2) NULL DEFAULT 0.00 ,
`diyformtype`  tinyint(1) NULL DEFAULT 0 ,
`diyformid`  int(11) NULL DEFAULT 0 ,
`diymode`  tinyint(1) NULL DEFAULT 0 ,
`dispatchtype`  tinyint(1) NULL DEFAULT 0 ,
`dispatchid`  int(11) NULL DEFAULT 0 ,
`dispatchprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`manydeduct`  tinyint(1) NULL DEFAULT 0 ,
`saleupdate37975`  tinyint(4) NULL DEFAULT 0 ,
`shopid`  int(11) NULL DEFAULT 0 ,
`allcates`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`minbuy`  int(11) NULL DEFAULT 0 ,
`invoice`  tinyint(4) NULL DEFAULT 0 ,
`repair`  tinyint(4) NULL DEFAULT 0 ,
`seven`  tinyint(4) NULL DEFAULT 0 ,
`money`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`minprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`maxprice`  decimal(10,2) NULL DEFAULT 0.00 ,
`province`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`city`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`buyshow`  tinyint(1) NULL DEFAULT 0 ,
`buycontent`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`saleupdate51117`  tinyint(4) NULL DEFAULT 0 ,
`virtualsend`  tinyint(1) NULL DEFAULT 0 ,
`virtualsendcontent`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`verifytype`  tinyint(1) NULL DEFAULT 0 ,
`diyfields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`diysaveid`  int(11) NULL DEFAULT 0 ,
`diysave`  tinyint(1) NULL DEFAULT 0 ,
`quality`  tinyint(4) NULL DEFAULT 0 ,
`groupstype`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`showtotal`  tinyint(3) UNSIGNED NOT NULL DEFAULT 0 ,
`subtitle`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`sharebtn`  tinyint(1) NOT NULL DEFAULT 0 ,
`merchid`  int(11) NULL DEFAULT 0 ,
`checked`  tinyint(4) NULL DEFAULT 0 ,
`thumb_first`  tinyint(4) NULL DEFAULT 0 ,
`merchsale`  tinyint(1) NULL DEFAULT 0 ,
`keywords`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`labelname`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`autoreceive`  int(11) NULL DEFAULT 0 ,
`cannotrefund`  tinyint(4) NULL DEFAULT 0 ,
`bargain`  int(11) NULL DEFAULT 0 ,
`buyagain`  decimal(10,2) NULL DEFAULT 0.00 ,
`buyagain_islong`  tinyint(1) NULL DEFAULT 0 ,
`buyagain_condition`  tinyint(1) NULL DEFAULT 0 ,
`buyagain_sale`  tinyint(1) NULL DEFAULT 0 ,
`buyagain_commission`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`buyagain_price`  decimal(10,2) NULL DEFAULT 0.00 ,
`diypage`  int(11) NULL DEFAULT NULL ,
`cashier`  tinyint(1) NULL DEFAULT 0 ,
`isendtime`  tinyint(4) NOT NULL DEFAULT 0 ,
`usetime`  int(11) NOT NULL DEFAULT 0 ,
`endtime`  int(11) NOT NULL DEFAULT 0 ,
`merchdisplayorder`  int(11) NOT NULL DEFAULT 0 ,
`exchange_stock`  int(11) NULL DEFAULT 0 ,
`exchange_postage`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`ispresell`  tinyint(4) NOT NULL DEFAULT 0 ,
`presellprice`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`presellover`  tinyint(4) NOT NULL DEFAULT 0 ,
`presellovertime`  int(11) NOT NULL ,
`presellstart`  tinyint(4) NOT NULL DEFAULT 0 ,
`preselltimestart`  int(11) NOT NULL DEFAULT 0 ,
`presellend`  tinyint(4) NOT NULL DEFAULT 0 ,
`preselltimeend`  int(11) NOT NULL DEFAULT 0 ,
`presellsendtype`  tinyint(4) NOT NULL DEFAULT 0 ,
`presellsendstatrttime`  int(11) NOT NULL DEFAULT 0 ,
`presellsendtime`  int(11) NOT NULL DEFAULT 0 ,
`edareas_code`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`unite_total`  tinyint(4) NOT NULL DEFAULT 0 ,
`threen`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`intervalfloor`  tinyint(1) NULL DEFAULT 0 ,
`intervalprice`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`isfullback`  tinyint(4) NOT NULL DEFAULT 0 ,
`isstatustime`  tinyint(4) NOT NULL DEFAULT 0 ,
`statustimestart`  int(11) NOT NULL DEFAULT 0 ,
`statustimeend`  int(11) NOT NULL DEFAULT 0 ,
`nosearch`  tinyint(1) NOT NULL DEFAULT 0 ,
`showsales`  tinyint(4) NOT NULL DEFAULT 1 ,
`islive`  int(11) NOT NULL DEFAULT 0 ,
`liveprice`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`opencard`  tinyint(1) NULL DEFAULT 0 ,
`cardid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`verifygoodstype`  tinyint(1) NOT NULL DEFAULT 0 ,
`verifygoodsnum`  int(11) NULL DEFAULT 1 ,
`verifygoodsdays`  int(11) NULL DEFAULT 1 ,
`verifygoodslimittype`  tinyint(1) NULL DEFAULT 0 ,
`verifygoodslimitdate`  int(11) NULL DEFAULT 0 ,
`minliveprice`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`maxliveprice`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`dowpayment`  decimal(10,2) NOT NULL DEFAULT 0.00 ,
`tempid`  int(11) NOT NULL DEFAULT 0 ,
`isstoreprice`  tinyint(4) NOT NULL DEFAULT 0 ,
`beforehours`  int(11) NOT NULL DEFAULT 0 ,
`newgoods`  tinyint(4) NOT NULL DEFAULT 0 ,
`video`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`officthumb`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' ,
`isforceverifystore`  tinyint(1) NOT NULL DEFAULT 0 ,
`catesinit3`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`showtotaladd`  tinyint(1) NULL DEFAULT 0 ,
`manydeduct2`  tinyint(1) NULL DEFAULT 0 ,
`refund`  tinyint(4) NOT NULL DEFAULT 0 ,
`returngoods`  tinyint(4) NOT NULL DEFAULT 0 ,
`exchange`  tinyint(4) NOT NULL DEFAULT 0 ,
`membercardpoint`  int(11) NOT NULL DEFAULT 0 ,
`isdiscount_time_start`  int(11) NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_goods_group` MODIFY COLUMN `goodsids`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `name`;

ALTER TABLE `lailu_ewei_shop_goods_label` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `labelname`;

ALTER TABLE `lailu_ewei_shop_goods_labelstyle` MODIFY COLUMN `style`  int(11) NOT NULL AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_goods_option` MODIFY COLUMN `goodsid`  int(11) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_goods_option` MODIFY COLUMN `thumb`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `title`;

ALTER TABLE `lailu_ewei_shop_goods_option` MODIFY COLUMN `day`  int(11) NOT NULL AFTER `presellprice`;

ALTER TABLE `lailu_ewei_shop_goods_option` MODIFY COLUMN `isfullback`  tinyint(4) NOT NULL AFTER `fullbackratio`;

ALTER TABLE `lailu_ewei_shop_goods_option` MODIFY COLUMN `cycelbuy_periodic`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `liveprice`;

ALTER TABLE `lailu_ewei_shop_goods_param` MODIFY COLUMN `goodsid`  int(11) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_goods_spec` MODIFY COLUMN `displaytype`  tinyint(4) NULL DEFAULT 0 AFTER `description`;

ALTER TABLE `lailu_ewei_shop_goods_spec` MODIFY COLUMN `iscycelbuy`  tinyint(1) NULL DEFAULT NULL AFTER `propId`;

ALTER TABLE `lailu_ewei_shop_goods_spec_item` MODIFY COLUMN `cycelbuy_periodic`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `virtual`;

ALTER TABLE `lailu_ewei_shop_groups_category` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `advurl`;

ALTER TABLE `lailu_ewei_shop_groups_goods` ADD COLUMN `ishot`  tinyint(4) NOT NULL AFTER `is_ladder`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `displayorder`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `id`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `showstock`  tinyint(4) NOT NULL AFTER `category`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `single`  tinyint(4) NOT NULL DEFAULT 0 AFTER `purchaselimit`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `dispatchtype`  tinyint(4) NOT NULL AFTER `units`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `endtime`  int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `freight`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `groupnum`  int(11) NOT NULL DEFAULT 0 AFTER `endtime`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `sales`  int(11) NOT NULL DEFAULT 0 AFTER `groupnum`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `createtime`  int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `content`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `isindex`  tinyint(4) NOT NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `deleted`  tinyint(4) NOT NULL DEFAULT 0 AFTER `isindex`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `followneed`  tinyint(4) NOT NULL DEFAULT 0 AFTER `goodsid`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `rights`  tinyint(4) NOT NULL DEFAULT 1 AFTER `thumb_url`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `discount`  tinyint(4) NULL DEFAULT 0 AFTER `gid`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `headstype`  tinyint(4) NULL DEFAULT NULL AFTER `discount`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `isdiscount`  tinyint(4) NULL DEFAULT 0 AFTER `headsdiscount`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `isdiscount`;

ALTER TABLE `lailu_ewei_shop_groups_goods` MODIFY COLUMN `verifytype`  tinyint(4) NULL DEFAULT 0 AFTER `isverify`;

CREATE INDEX `idx_istop` ON `lailu_ewei_shop_groups_goods`(`isindex`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_groups_goods_option` MODIFY COLUMN `groups_goods_id`  int(11) NULL DEFAULT 0 AFTER `goodsid`;

ALTER TABLE `lailu_ewei_shop_groups_goods_option` MODIFY COLUMN `stock`  int(11) NULL DEFAULT NULL AFTER `specs`;

ALTER TABLE `lailu_ewei_shop_groups_order` ADD COLUMN `ischecked`  tinyint(1) NOT NULL AFTER `diyformfields`;

ALTER TABLE `lailu_ewei_shop_groups_order` ADD COLUMN `delete`  int(11) NOT NULL AFTER `ischecked`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `status`  int(11) NOT NULL AFTER `freight`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `is_team`  int(11) NOT NULL AFTER `teamid`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `endtime`  int(11) NOT NULL AFTER `canceltime`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `refundstate`  tinyint(4) NOT NULL DEFAULT 0 AFTER `refundid`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `sendtime`  int(11) NULL DEFAULT 0 AFTER `expresssn`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `success`  int(11) NOT NULL DEFAULT 0 AFTER `message`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `deleted`  int(11) NOT NULL DEFAULT 0 AFTER `success`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `mobile`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `verifytype`  tinyint(4) NULL DEFAULT 0 AFTER `isverify`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `apppay`  tinyint(4) NOT NULL DEFAULT 0 AFTER `printstate2`;

ALTER TABLE `lailu_ewei_shop_groups_order` MODIFY COLUMN `ladder_id`  tinyint(1) NULL DEFAULT NULL AFTER `source`;

CREATE INDEX `groups_order_id` ON `lailu_ewei_shop_groups_order_goods`(`groups_order_id`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_groups_order_refund` MODIFY COLUMN `refundstatus`  tinyint(4) NOT NULL DEFAULT 0 AFTER `refundno`;

ALTER TABLE `lailu_ewei_shop_groups_order_refund` MODIFY COLUMN `rtype`  int(11) NOT NULL DEFAULT 0 AFTER `refundtype`;

ALTER TABLE `lailu_ewei_shop_groups_paylog` MODIFY COLUMN `plid`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_groups_paylog` MODIFY COLUMN `credit`  int(11) NOT NULL DEFAULT 0 AFTER `tid`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `groups`  int(11) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `description`  int(11) NOT NULL DEFAULT 0 AFTER `groups_description`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `creditdeduct`  tinyint(4) NOT NULL DEFAULT 0 AFTER `description`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `groupsdeduct`  tinyint(4) NOT NULL DEFAULT 0 AFTER `creditdeduct`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `discount`  tinyint(4) NULL DEFAULT 0 AFTER `receive`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `headstype`  tinyint(4) NULL DEFAULT 0 AFTER `discount`;

ALTER TABLE `lailu_ewei_shop_groups_set` MODIFY COLUMN `followbar`  tinyint(4) NOT NULL DEFAULT 0 AFTER `headsdiscount`;

ALTER TABLE `lailu_ewei_shop_groups_verify` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `verifier`;

ALTER TABLE `lailu_ewei_shop_invitation` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_invitation` MODIFY COLUMN `qrcode`  tinyint(4) NOT NULL DEFAULT 0 AFTER `follow`;

ALTER TABLE `lailu_ewei_shop_invitation` MODIFY COLUMN `status`  tinyint(4) NOT NULL AFTER `qrcode`;

ALTER TABLE `lailu_ewei_shop_invitation_log` MODIFY COLUMN `scan_time`  int(11) NOT NULL DEFAULT 0 AFTER `invitation_openid`;

ALTER TABLE `lailu_ewei_shop_invitation_log` MODIFY COLUMN `follow`  tinyint(4) NOT NULL DEFAULT 0 AFTER `scan_time`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `livetype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `screen`  tinyint(4) NOT NULL DEFAULT 0 AFTER `liveidentity`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `goodsid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `screen`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `hot`  tinyint(4) NOT NULL DEFAULT 0 AFTER `thumb`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `recommend`  tinyint(4) NOT NULL DEFAULT 0 AFTER `hot`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `living`  tinyint(4) NOT NULL DEFAULT 0 AFTER `recommend`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `living`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `livetime`  int(11) NOT NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `createtime`  int(11) NOT NULL DEFAULT 0 AFTER `lastlivetime`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `subscribenotice`  tinyint(4) NOT NULL DEFAULT 0 AFTER `subscribe`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `covertype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `video`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `iscoupon`  tinyint(4) NOT NULL DEFAULT 0 AFTER `cover`;

ALTER TABLE `lailu_ewei_shop_live` MODIFY COLUMN `jurisdictionurl_show`  tinyint(4) NOT NULL DEFAULT 0 AFTER `jurisdiction_url`;

ALTER TABLE `lailu_ewei_shop_live_category` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `advurl`;

ALTER TABLE `lailu_ewei_shop_live_favorite` MODIFY COLUMN `deleted`  tinyint(4) NOT NULL DEFAULT 0 AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_live_setting` MODIFY COLUMN `ismember`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_lottery_join` MODIFY COLUMN `lottery_num`  int(11) NULL DEFAULT 0 AFTER `lottery_id`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `456wd_id`  int(11) NOT NULL AFTER `jpush`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `wd_new`  int(11) NOT NULL AFTER `456wd_id`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `is_reset`  tinyint(1) NOT NULL AFTER `wd_new`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `old_openid`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `is_reset`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `expiretime`  int(11) NULL DEFAULT NULL AFTER `old_openid`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `commission`  decimal(10,2) NULL DEFAULT NULL AFTER `expiretime`;

ALTER TABLE `lailu_ewei_shop_member` ADD COLUMN `commission_pay`  decimal(10,2) NULL DEFAULT NULL AFTER `commission`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `createtime`  int(11) NULL DEFAULT 0 AFTER `content`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `agenttime`  int(11) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `gender`  tinyint(4) NULL DEFAULT 0 AFTER `birthday`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `agentselectgoods`  tinyint(4) NULL DEFAULT 0 AFTER `inviter`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `fixagentid`  tinyint(4) NULL DEFAULT 0 AFTER `username`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `ispartner`  tinyint(4) NULL DEFAULT 0 AFTER `endtime2`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `partnerstatus`  tinyint(4) NULL DEFAULT 0 AFTER `partnertime`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `partnerblack`  tinyint(4) NULL DEFAULT 0 AFTER `partnerstatus`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `partnernotupgrade`  tinyint(4) NULL DEFAULT 0 AFTER `partnerlevel`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `isaagent`  tinyint(4) NULL DEFAULT 0 AFTER `diyglobonusfields`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `aagentstatus`  tinyint(4) NULL DEFAULT 0 AFTER `aagenttime`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `aagentblack`  tinyint(4) NULL DEFAULT 0 AFTER `aagentstatus`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `aagentnotupgrade`  tinyint(4) NULL DEFAULT 0 AFTER `aagentblack`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `aagenttype`  tinyint(4) NULL DEFAULT 0 AFTER `aagentnotupgrade`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `mobileverify`  tinyint(4) NULL DEFAULT 0 AFTER `salt`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `mobileuser`  tinyint(4) NULL DEFAULT 0 AFTER `mobileverify`;

ALTER TABLE `lailu_ewei_shop_member` MODIFY COLUMN `diymaxcredit`  tinyint(4) NULL DEFAULT 0 AFTER `openid_wx`;

ALTER TABLE `lailu_ewei_shop_member_card` ADD COLUMN `paytype`  int(11) NULL DEFAULT NULL AFTER `goodsids`;

ALTER TABLE `lailu_ewei_shop_member_card` ADD COLUMN `paytime`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `paytype`;

ALTER TABLE `lailu_ewei_shop_member_card` ADD COLUMN `finishtime`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `paytime`;

ALTER TABLE `lailu_ewei_shop_member_card` MODIFY COLUMN `sort_order`  int(11) NULL DEFAULT 0 AFTER `card_style`;

ALTER TABLE `lailu_ewei_shop_member_card` MODIFY COLUMN `shipping`  tinyint(4) NULL DEFAULT NULL AFTER `sort_order`;

ALTER TABLE `lailu_ewei_shop_member_card` MODIFY COLUMN `stock`  int(11) NULL DEFAULT NULL AFTER `price`;

ALTER TABLE `lailu_ewei_shop_member_card` MODIFY COLUMN `isdelete`  tinyint(4) NOT NULL DEFAULT 0 AFTER `del_time`;

ALTER TABLE `lailu_ewei_shop_member_card` MODIFY COLUMN `cardmodel`  int(11) NULL DEFAULT 1 AFTER `update_time`;

ALTER TABLE `lailu_ewei_shop_member_card_history` MODIFY COLUMN `pay_type`  int(11) NULL DEFAULT NULL AFTER `del_time`;

ALTER TABLE `lailu_ewei_shop_member_card_order` MODIFY COLUMN `apppay`  tinyint(4) NOT NULL DEFAULT 0 AFTER `paytype`;

ALTER TABLE `lailu_ewei_shop_member_cart` MODIFY COLUMN `isnewstore`  tinyint(4) NOT NULL DEFAULT 0 AFTER `selectedadd`;

ALTER TABLE `lailu_ewei_shop_member_credit_record` MODIFY COLUMN `uid`  int(10) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `lailu_ewei_shop_member_credit_record` MODIFY COLUMN `presentcredit`  decimal(10,2) NULL DEFAULT NULL AFTER `module`;

ALTER TABLE `lailu_ewei_shop_member_favorite` ADD COLUMN `thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `type`;

ALTER TABLE `lailu_ewei_shop_member_favorite` ADD COLUMN `title`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `thumb`;

ALTER TABLE `lailu_ewei_shop_member_favorite` ADD COLUMN `marketprice`  int(11) NULL DEFAULT NULL AFTER `title`;

ALTER TABLE `lailu_ewei_shop_member_favorite` ADD COLUMN `productprice`  int(11) NULL DEFAULT NULL AFTER `marketprice`;

ALTER TABLE `lailu_ewei_shop_member_favorite` MODIFY COLUMN `goodsid`  int(11) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_member_group` MODIFY COLUMN `description`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `groupname`;

ALTER TABLE `lailu_ewei_shop_member_history` MODIFY COLUMN `goodsid`  int(11) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_member_level` ADD COLUMN `456wd_id`  int(11) NOT NULL AFTER `goodsids`;

ALTER TABLE `lailu_ewei_shop_member_level` ADD COLUMN `wd_new`  int(11) NOT NULL AFTER `456wd_id`;

ALTER TABLE `lailu_ewei_shop_member_level` ADD COLUMN `timelimit`  int(11) NULL DEFAULT NULL AFTER `wd_new`;

ALTER TABLE `lailu_ewei_shop_member_level` MODIFY COLUMN `ordercount`  int(11) NULL DEFAULT 0 AFTER `ordermoney`;

ALTER TABLE `lailu_ewei_shop_member_level` MODIFY COLUMN `enabled`  tinyint(4) NULL DEFAULT 0 AFTER `discount`;

ALTER TABLE `lailu_ewei_shop_member_log` ADD COLUMN `paytype`  tinyint(1) NOT NULL AFTER `senddata`;

ALTER TABLE `lailu_ewei_shop_member_log` ADD COLUMN `ischecked`  tinyint(1) NOT NULL AFTER `paytype`;

ALTER TABLE `lailu_ewei_shop_member_log` ADD COLUMN `bankopen`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `ischecked`;

ALTER TABLE `lailu_ewei_shop_member_log` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT NULL AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_member_log` MODIFY COLUMN `isborrow`  tinyint(4) NULL DEFAULT 0 AFTER `deductionmoney`;

ALTER TABLE `lailu_ewei_shop_member_log` MODIFY COLUMN `apppay`  tinyint(4) NOT NULL DEFAULT 0 AFTER `remark`;

ALTER TABLE `lailu_ewei_shop_member_log` MODIFY COLUMN `applytype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `realname`;

ALTER TABLE `lailu_ewei_shop_member_message_template` MODIFY COLUMN `send_desc`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `messagetype`;

ALTER TABLE `lailu_ewei_shop_member_printer` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_member_printer_template` ADD COLUMN `tel_code_type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `productsn`;

ALTER TABLE `lailu_ewei_shop_member_printer_template` ADD COLUMN `ordersn_code_type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `tel_code_type`;

ALTER TABLE `lailu_ewei_shop_member_printer_template` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_member_wxapp_message_template_default` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_member_wxapp_message_template_type` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_merch_account` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `salt`;

ALTER TABLE `lailu_ewei_shop_merch_account` MODIFY COLUMN `isfounder`  tinyint(4) NULL DEFAULT 0 AFTER `perms`;

ALTER TABLE `lailu_ewei_shop_merch_bill` ADD COLUMN `bankopen`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `isbillcredit`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `orderids`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `remark`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `applytype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `applyrealname`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `handpay`  tinyint(4) NOT NULL DEFAULT 0 AFTER `applytype`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `creditstatus`  tinyint(4) NOT NULL DEFAULT 0 AFTER `handpay`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `creditrate`  int(11) NOT NULL DEFAULT 1 AFTER `creditstatus`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `creditnum`  int(11) NOT NULL DEFAULT 0 AFTER `creditrate`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `passcreditnum`  int(11) NOT NULL DEFAULT 0 AFTER `creditmoney`;

ALTER TABLE `lailu_ewei_shop_merch_bill` MODIFY COLUMN `isbillcredit`  int(11) NOT NULL DEFAULT 0 AFTER `passcreditmoney`;

ALTER TABLE `lailu_ewei_shop_merch_commission_orderprice` MODIFY COLUMN `order_id`  int(10) UNSIGNED NOT NULL FIRST ;

ALTER TABLE `lailu_ewei_shop_merch_group` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_merch_nav` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_merch_notice` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `detail`;

ALTER TABLE `lailu_ewei_shop_merch_perm_role` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `rolename`;

ALTER TABLE `lailu_ewei_shop_merch_perm_role` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `perms`;

ALTER TABLE `lailu_ewei_shop_merch_reg` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `mobile`;

ALTER TABLE `lailu_ewei_shop_merch_saler` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_merch_store` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `lng`;

ALTER TABLE `lailu_ewei_shop_merch_user` ADD COLUMN `	iscredit`  tinyint(4) NOT NULL AFTER `can_edit`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `mobile`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `sets`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `accountid`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `iscredit`  tinyint(4) NOT NULL DEFAULT 1 AFTER `maxgoods`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `creditrate`  int(11) NOT NULL DEFAULT 1 AFTER `iscredit`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `iscreditmoney`  int(11) NOT NULL DEFAULT 1 AFTER `creditrate`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `can_import`  tinyint(4) NOT NULL DEFAULT 0 AFTER `iscreditmoney`;

ALTER TABLE `lailu_ewei_shop_merch_user` MODIFY COLUMN `can_edit`  tinyint(4) NOT NULL DEFAULT 0 AFTER `can_import`;

ALTER TABLE `lailu_ewei_shop_multi_shop` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `jointime`;

ALTER TABLE `lailu_ewei_shop_nav` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_notice` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `detail`;

ALTER TABLE `lailu_ewei_shop_notice` MODIFY COLUMN `iswxapp`  tinyint(4) NOT NULL DEFAULT 0 AFTER `shopid`;

ALTER TABLE `lailu_ewei_shop_open_plugin` ADD COLUMN `uniacid`  int(11) NULL DEFAULT NULL AFTER `domain`;

ALTER TABLE `lailu_ewei_shop_open_plugin` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_open_plugin` MODIFY COLUMN `status`  int(11) NOT NULL DEFAULT 1 AFTER `key`;

ALTER TABLE `lailu_ewei_shop_order` ADD COLUMN `ischecked`  tinyint(1) NOT NULL AFTER `dividend_content`;

ALTER TABLE `lailu_ewei_shop_order` ADD COLUMN `wxapp_allow_subscribe`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `ischecked`;

ALTER TABLE `lailu_ewei_shop_order` ADD COLUMN `authorid`  int(11) NULL DEFAULT NULL AFTER `wxapp_allow_subscribe`;

ALTER TABLE `lailu_ewei_shop_order` ADD COLUMN `isauthor`  tinyint(1) NULL DEFAULT NULL AFTER `authorid`;

ALTER TABLE `lailu_ewei_shop_order` ADD COLUMN `ces`  int(11) NULL DEFAULT NULL AFTER `isauthor`;

ALTER TABLE `lailu_ewei_shop_order` ADD COLUMN `willcloseverifymessage`  int(11) NOT NULL DEFAULT 0 AFTER `ces`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `discountprice`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `dispatchid`  int(11) NULL DEFAULT 0 AFTER `dispatchprice`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `createtime`  int(11) NULL DEFAULT NULL AFTER `dispatchid`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `dispatchtype`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `iscomment`  tinyint(4) NULL DEFAULT 0 AFTER `refundid`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `creditadd`  tinyint(4) NULL DEFAULT 0 AFTER `iscomment`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `creditadd`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `userdeleted`  tinyint(4) NULL DEFAULT 0 AFTER `deleted`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `cash`  tinyint(4) NULL DEFAULT 0 AFTER `fetchtime`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `isverify`  tinyint(4) NULL DEFAULT 0 AFTER `refundtime`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `verified`  tinyint(4) NULL DEFAULT 0 AFTER `isverify`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `deductcredit`  int(11) NULL DEFAULT 0 AFTER `deductprice`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `sysdeleted`  tinyint(4) NULL DEFAULT 0 AFTER `address`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `isvirtual`  tinyint(4) NULL DEFAULT 0 AFTER `olddispatchprice`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `refundstate`  tinyint(4) NULL DEFAULT 0 AFTER `address_send`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `ismr`  int(11) NOT NULL DEFAULT 0 AFTER `remarksend`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `isglobonus`  tinyint(4) NULL DEFAULT 0 AFTER `couponmerchid`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `isabonus`  tinyint(4) NULL DEFAULT 0 AFTER `merchapply`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `isborrow`  tinyint(4) NULL DEFAULT 0 AFTER `isabonus`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `apppay`  tinyint(4) NOT NULL DEFAULT 0 AFTER `merchisdiscountprice`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `ispackage`  tinyint(4) NULL DEFAULT 0 AFTER `buyagainprice`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `sendtype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `willcancelmessage`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `istrade`  tinyint(4) NOT NULL DEFAULT 0 AFTER `quickid`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `isnewstore`  tinyint(4) NOT NULL DEFAULT 0 AFTER `istrade`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `is_cashier`  tinyint(4) NOT NULL DEFAULT 0 AFTER `city_express_state`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `iscycelbuy`  tinyint(4) NULL DEFAULT 0 AFTER `commissionmoney`;

ALTER TABLE `lailu_ewei_shop_order` MODIFY COLUMN `dividend_status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `dividend_deletetime`;

ALTER TABLE `lailu_ewei_shop_order_comment` MODIFY COLUMN `level`  tinyint(4) NULL DEFAULT 0 AFTER `headimgurl`;

ALTER TABLE `lailu_ewei_shop_order_comment` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_order_comment` MODIFY COLUMN `istop`  tinyint(4) NULL DEFAULT 0 AFTER `append_reply_images`;

ALTER TABLE `lailu_ewei_shop_order_comment` MODIFY COLUMN `checked`  tinyint(4) NOT NULL DEFAULT 0 AFTER `istop`;

ALTER TABLE `lailu_ewei_shop_order_comment` MODIFY COLUMN `replychecked`  tinyint(4) NOT NULL DEFAULT 0 AFTER `checked`;

ALTER TABLE `lailu_ewei_shop_order_goods` ADD COLUMN `is_make`  tinyint(1) NULL DEFAULT NULL AFTER `fullbackid`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `optionid`  int(11) NULL DEFAULT 0 AFTER `total`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `checktime1`  int(11) NULL DEFAULT 0 AFTER `applytime1`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `status1`  tinyint(4) NULL DEFAULT 0 AFTER `deletetime1`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `checktime2`  int(11) NULL DEFAULT 0 AFTER `applytime2`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `status2`  tinyint(4) NULL DEFAULT 0 AFTER `deletetime2`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `checktime3`  int(11) NULL DEFAULT 0 AFTER `applytime3`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `status3`  tinyint(4) NULL DEFAULT 0 AFTER `deletetime3`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `nocommission`  tinyint(4) NULL DEFAULT 0 AFTER `productsn`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `rstate`  tinyint(4) NULL DEFAULT 0 AFTER `diyformid`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `merchsale`  tinyint(4) NOT NULL DEFAULT 0 AFTER `parentorderid`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `seckill`  tinyint(4) NULL DEFAULT 0 AFTER `canbuyagain`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `sendtype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `seckill_timeid`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `prohibitrefund`  tinyint(4) NOT NULL DEFAULT 0 AFTER `remarksend`;

ALTER TABLE `lailu_ewei_shop_order_goods` MODIFY COLUMN `single_refundstate`  tinyint(4) NOT NULL DEFAULT 0 AFTER `single_refundid`;

ALTER TABLE `lailu_ewei_shop_order_peerpay_payinfo` MODIFY COLUMN `paytype`  tinyint(1) NULL DEFAULT NULL AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_order_print` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `id`;

ALTER TABLE `lailu_ewei_shop_order_print` MODIFY COLUMN `sid`  tinyint(4) NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_order_print` MODIFY COLUMN `foid`  tinyint(4) NULL DEFAULT 0 AFTER `sid`;

CREATE TABLE `lailu_ewei_shop_order_query` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NULL DEFAULT NULL ,
`out_trade_no`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`transaction_id`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`total_fee`  decimal(10,2) NULL DEFAULT NULL ,
`time_end`  int(11) NULL DEFAULT NULL ,
`status`  tinyint(4) NOT NULL ,
`create_time`  int(11) NULL DEFAULT NULL ,
`apply_time`  int(11) NOT NULL ,
`check_time`  int(11) NULL DEFAULT NULL ,
`withdraw_time`  int(11) NOT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_openid` (`out_trade_no`) USING BTREE ,
INDEX `idx_shareid` (`transaction_id`) USING BTREE ,
INDEX `idx_uniacid` (`uniacid`) USING BTREE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_order_refund` ADD COLUMN `ordergoodsids`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_order_refund` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_order_refund` MODIFY COLUMN `refundtype`  tinyint(4) NULL DEFAULT 0 AFTER `reply`;

ALTER TABLE `lailu_ewei_shop_order_refund` MODIFY COLUMN `rtype`  tinyint(4) NULL DEFAULT 0 AFTER `imgs`;

ALTER TABLE `lailu_ewei_shop_order_single_refund` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_order_single_refund` MODIFY COLUMN `refundtype`  tinyint(4) NULL DEFAULT 0 AFTER `reply`;

ALTER TABLE `lailu_ewei_shop_order_single_refund` MODIFY COLUMN `rtype`  tinyint(4) NULL DEFAULT 0 AFTER `imgs`;

ALTER TABLE `lailu_ewei_shop_order_single_refund` MODIFY COLUMN `tradetype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_order_single_refund` MODIFY COLUMN `issuporder`  tinyint(4) NULL DEFAULT 0 AFTER `tradetype`;

ALTER TABLE `lailu_ewei_shop_order_single_refund` MODIFY COLUMN `suptype`  tinyint(4) NULL DEFAULT 0 AFTER `issuporder`;

CREATE TABLE `lailu_ewei_shop_order_withdraw_apply` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`apply_ids`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`create_time`  int(11) NOT NULL ,
`uniacid`  int(11) NOT NULL ,
`status`  tinyint(1) NOT NULL ,
`openid`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`id_number`  varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`real_name`  varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`total_money`  decimal(10,2) NOT NULL ,
`check_money`  decimal(10,2) NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_package` MODIFY COLUMN `cash`  tinyint(4) NOT NULL DEFAULT 0 AFTER `goodsid`;

ALTER TABLE `lailu_ewei_shop_package` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `share_desc`;

ALTER TABLE `lailu_ewei_shop_package` MODIFY COLUMN `deleted`  tinyint(4) NOT NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_package` MODIFY COLUMN `dispatchtype`  tinyint(4) NULL DEFAULT NULL AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_package_goods` MODIFY COLUMN `hasoption`  tinyint(4) NOT NULL DEFAULT 0 AFTER `productsn`;

ALTER TABLE `lailu_ewei_shop_payment` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_payment` MODIFY COLUMN `paytype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_payment` MODIFY COLUMN `alitype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `paytype`;

ALTER TABLE `lailu_ewei_shop_pc_adv` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_pc_adv` MODIFY COLUMN `uniacid`  int(10) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `lailu_ewei_shop_pc_adv` MODIFY COLUMN `width`  int(10) UNSIGNED NOT NULL AFTER `link`;

ALTER TABLE `lailu_ewei_shop_pc_adv` MODIFY COLUMN `height`  int(10) UNSIGNED NOT NULL AFTER `width`;

ALTER TABLE `lailu_ewei_shop_pc_browse_history` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_pc_goods` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_pc_goods` MODIFY COLUMN `sort`  int(11) NOT NULL AFTER `bottom_url`;

ALTER TABLE `lailu_ewei_shop_pc_goods` MODIFY COLUMN `status`  int(11) NOT NULL DEFAULT 0 AFTER `sort`;

ALTER TABLE `lailu_ewei_shop_pc_link` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_pc_link` MODIFY COLUMN `uniacid`  int(10) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `lailu_ewei_shop_pc_link` MODIFY COLUMN `displayorder`  int(10) UNSIGNED NOT NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_pc_menu` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_pc_menu` MODIFY COLUMN `uniacid`  int(10) UNSIGNED NOT NULL AFTER `id`;

ALTER TABLE `lailu_ewei_shop_pc_menu` MODIFY COLUMN `type`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_pc_menu` MODIFY COLUMN `displayorder`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `type`;

ALTER TABLE `lailu_ewei_shop_pc_menu` MODIFY COLUMN `createtime`  int(10) UNSIGNED NULL DEFAULT NULL AFTER `enabled`;

ALTER TABLE `lailu_ewei_shop_pc_menu` MODIFY COLUMN `create_time`  int(10) UNSIGNED NULL DEFAULT NULL AFTER `status`;

ALTER TABLE `lailu_ewei_shop_pc_slide` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_pc_slide` MODIFY COLUMN `uniacid`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `id`;

ALTER TABLE `lailu_ewei_shop_pc_slide` MODIFY COLUMN `type`  int(10) UNSIGNED NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_pc_template` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

CREATE INDEX `idx_type` ON `lailu_ewei_shop_perm_log`(`type`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_perm_plugin` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `uid`;

CREATE INDEX `idx_acid` ON `lailu_ewei_shop_perm_plugin`(`acid`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_perm_role` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `rolename`;

ALTER TABLE `lailu_ewei_shop_perm_role` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `perms2`;

ALTER TABLE `lailu_ewei_shop_perm_user` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `perms2`;

ALTER TABLE `lailu_ewei_shop_plugin` MODIFY COLUMN `iscom`  tinyint(4) NULL DEFAULT 0 AFTER `desc`;

ALTER TABLE `lailu_ewei_shop_plugin` MODIFY COLUMN `deprecated`  tinyint(4) NULL DEFAULT 0 AFTER `iscom`;

ALTER TABLE `lailu_ewei_shop_plugin` MODIFY COLUMN `isv2`  tinyint(4) NULL DEFAULT 0 AFTER `deprecated`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `isdefault`  tinyint(4) NULL DEFAULT 0 AFTER `follows`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `resptype`  tinyint(4) NULL DEFAULT 0 AFTER `isdefault`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `beagent`  tinyint(4) NULL DEFAULT 0 AFTER `subtext`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `bedown`  tinyint(4) NULL DEFAULT 0 AFTER `beagent`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `isopen`  tinyint(4) NULL DEFAULT 0 AFTER `bedown`;

ALTER TABLE `lailu_ewei_shop_poster` MODIFY COLUMN `ismembergroup`  tinyint(4) NULL DEFAULT 0 AFTER `reward_totle`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `isdefault`  tinyint(4) NULL DEFAULT 0 AFTER `keyword2`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `resptype`  tinyint(4) NULL DEFAULT 0 AFTER `isdefault`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `beagent`  tinyint(4) NULL DEFAULT 0 AFTER `subtext`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `bedown`  tinyint(4) NULL DEFAULT 0 AFTER `beagent`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `isopen`  tinyint(4) NULL DEFAULT 0 AFTER `bedown`;

ALTER TABLE `lailu_ewei_shop_postera` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `timeend`;

CREATE INDEX `idx_from_openid` ON `lailu_ewei_shop_postera_log`(`from_openid`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_postera_qr` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `posterid`;

CREATE INDEX `idx_from_openid` ON `lailu_ewei_shop_poster_log`(`from_openid`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_poster_qr` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `openid`;

CREATE INDEX `idx_openid` ON `lailu_ewei_shop_poster_qr`(`openid`) USING BTREE ;

CREATE INDEX `idx_openid` ON `lailu_ewei_shop_poster_scan`(`openid`) USING BTREE ;

ALTER TABLE `lailu_ewei_shop_print` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `id`;

ALTER TABLE `lailu_ewei_shop_print` MODIFY COLUMN `print_nums`  tinyint(4) NULL DEFAULT 0 AFTER `key`;

ALTER TABLE `lailu_ewei_shop_print` MODIFY COLUMN `sid`  tinyint(4) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_print` MODIFY COLUMN `print_type`  tinyint(4) NULL DEFAULT 0 AFTER `sid`;

ALTER TABLE `lailu_ewei_shop_qa_category` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `enabled`;

ALTER TABLE `lailu_ewei_shop_qa_question` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `content`;

ALTER TABLE `lailu_ewei_shop_qa_question` MODIFY COLUMN `isrecommand`  tinyint(4) NOT NULL DEFAULT 0 AFTER `status`;

CREATE TABLE `lailu_ewei_shop_qa_question_copy_1584458045` (
`id`  int(11) NOT NULL DEFAULT 0 ,
`uniacid`  int(11) NOT NULL DEFAULT 0 ,
`cate`  int(11) NOT NULL DEFAULT 0 ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`keywords`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`content`  mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`status`  tinyint(4) NOT NULL DEFAULT 0 ,
`isrecommand`  tinyint(4) NOT NULL DEFAULT 0 ,
`displayorder`  int(11) NOT NULL DEFAULT 0 ,
`createtime`  int(11) NOT NULL DEFAULT 0 ,
`lastedittime`  int(11) NOT NULL DEFAULT 0
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_qa_set` MODIFY COLUMN `showmember`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_qa_set` MODIFY COLUMN `showtype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `showmember`;

ALTER TABLE `lailu_ewei_shop_qa_set` MODIFY COLUMN `share`  tinyint(4) NOT NULL DEFAULT 0 AFTER `enter_desc`;

ALTER TABLE `lailu_ewei_shop_queue` MODIFY COLUMN `priority`  int(10) UNSIGNED NOT NULL DEFAULT 1024 AFTER `delay`;

ALTER TABLE `lailu_ewei_shop_quick` MODIFY COLUMN `cart`  tinyint(4) NOT NULL DEFAULT 0 AFTER `datas`;

ALTER TABLE `lailu_ewei_shop_quick` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `enter_icon`;

ALTER TABLE `lailu_ewei_shop_quick` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `merchid`;

ALTER TABLE `lailu_ewei_shop_saler` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_saler` MODIFY COLUMN `isfounder`  tinyint(4) NULL DEFAULT 0 AFTER `lastip`;

ALTER TABLE `lailu_ewei_shop_sale_coupon` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `name`;

ALTER TABLE `lailu_ewei_shop_sale_coupon_data` MODIFY COLUMN `gettype`  tinyint(4) NULL DEFAULT 0 AFTER `gettime`;

ALTER TABLE `lailu_ewei_shop_seckill_task` MODIFY COLUMN `enabled`  tinyint(4) NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_seckill_task` MODIFY COLUMN `oldshow`  tinyint(4) NULL DEFAULT 0 AFTER `closesec`;

ALTER TABLE `lailu_ewei_shop_seckill_task` MODIFY COLUMN `overtimes`  tinyint(4) NULL DEFAULT NULL AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_seckill_task_room` MODIFY COLUMN `enabled`  tinyint(4) NULL DEFAULT 0 AFTER `title`;

ALTER TABLE `lailu_ewei_shop_seckill_task_room` MODIFY COLUMN `oldshow`  tinyint(4) NULL DEFAULT 0 AFTER `share_icon`;

ALTER TABLE `lailu_ewei_shop_seckill_task_time` MODIFY COLUMN `time`  tinyint(4) NULL DEFAULT 0 AFTER `taskid`;

ALTER TABLE `lailu_ewei_shop_sendticket_draw` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_sign_records` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `log`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `iscenter`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `iscreditshop`  tinyint(4) NOT NULL DEFAULT 0 AFTER `iscenter`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `isopen`  tinyint(4) NOT NULL DEFAULT 0 AFTER `desc`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `signold`  tinyint(4) NOT NULL DEFAULT 0 AFTER `isopen`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `signold_type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `signold_price`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `cycle`  tinyint(4) NOT NULL DEFAULT 0 AFTER `maincolor`;

ALTER TABLE `lailu_ewei_shop_sign_set` MODIFY COLUMN `share`  tinyint(4) NOT NULL DEFAULT 0 AFTER `sign_rule`;

ALTER TABLE `lailu_ewei_shop_sms` MODIFY COLUMN `template`  tinyint(4) NOT NULL DEFAULT 0 AFTER `type`;

ALTER TABLE `lailu_ewei_shop_sms` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `data`;

ALTER TABLE `lailu_ewei_shop_sms_set` MODIFY COLUMN `juhe`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_sms_set` MODIFY COLUMN `dayu`  tinyint(4) NOT NULL DEFAULT 0 AFTER `juhe_key`;

ALTER TABLE `lailu_ewei_shop_sms_set` MODIFY COLUMN `emay`  tinyint(4) NOT NULL DEFAULT 0 AFTER `dayu_secret`;

ALTER TABLE `lailu_ewei_shop_sms_set` MODIFY COLUMN `aliyun`  tinyint(4) NOT NULL DEFAULT 0 AFTER `emay_warn_time`;

ALTER TABLE `lailu_ewei_shop_sms_set` MODIFY COLUMN `aliyun_new`  tinyint(4) NOT NULL DEFAULT 0 AFTER `aliyun_appcode`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `notagent`  tinyint(4) NULL DEFAULT 0 AFTER `bestboardcredit`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `notagentpost`  tinyint(4) NULL DEFAULT 0 AFTER `notagent`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `topboardcredit`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `noimage`  tinyint(4) NULL DEFAULT 0 AFTER `status`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `novoice`  tinyint(4) NULL DEFAULT 0 AFTER `noimage`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `needfollow`  tinyint(4) NULL DEFAULT 0 AFTER `novoice`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `needpostfollow`  tinyint(4) NULL DEFAULT 0 AFTER `needfollow`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `keyword`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `needcheck`  tinyint(4) NULL DEFAULT 0 AFTER `banner`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `needcheckmanager`  tinyint(4) NULL DEFAULT 0 AFTER `needcheck`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `notpartner`  tinyint(4) NULL DEFAULT 0 AFTER `postpartnerlevels`;

ALTER TABLE `lailu_ewei_shop_sns_board` MODIFY COLUMN `notpartnerpost`  tinyint(4) NULL DEFAULT 0 AFTER `notpartner`;

ALTER TABLE `lailu_ewei_shop_sns_category` MODIFY COLUMN `isrecommand`  tinyint(4) NULL DEFAULT 0 AFTER `advurl`;

ALTER TABLE `lailu_ewei_shop_sns_complain` MODIFY COLUMN `type`  tinyint(4) NOT NULL AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_sns_complain` MODIFY COLUMN `complaint_type`  int(11) NOT NULL DEFAULT 0 AFTER `complainant`;

ALTER TABLE `lailu_ewei_shop_sns_complain` MODIFY COLUMN `checked`  tinyint(4) NOT NULL DEFAULT 0 AFTER `checkedtime`;

ALTER TABLE `lailu_ewei_shop_sns_complain` MODIFY COLUMN `deleted`  tinyint(4) NOT NULL DEFAULT 0 AFTER `checked_note`;

ALTER TABLE `lailu_ewei_shop_sns_complaincate` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `name`;

ALTER TABLE `lailu_ewei_shop_sns_level` MODIFY COLUMN `enabled`  tinyint(4) NULL DEFAULT 0 AFTER `credit`;

ALTER TABLE `lailu_ewei_shop_sns_manage` MODIFY COLUMN `enabled`  tinyint(4) NULL DEFAULT 0 AFTER `openid`;

ALTER TABLE `lailu_ewei_shop_sns_member` MODIFY COLUMN `isblack`  tinyint(4) NULL DEFAULT 0 AFTER `sign`;

ALTER TABLE `lailu_ewei_shop_sns_member` MODIFY COLUMN `notupgrade`  tinyint(4) NULL DEFAULT 0 AFTER `isblack`;

ALTER TABLE `lailu_ewei_shop_sns_post` MODIFY COLUMN `isboardbest`  tinyint(4) NULL DEFAULT 0 AFTER `isbest`;

ALTER TABLE `lailu_ewei_shop_sns_post` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT 0 AFTER `isboardbest`;

ALTER TABLE `lailu_ewei_shop_sns_post` MODIFY COLUMN `checked`  tinyint(4) NULL DEFAULT NULL AFTER `deletedtime`;

ALTER TABLE `lailu_ewei_shop_sns_post` MODIFY COLUMN `isadmin`  tinyint(4) NOT NULL DEFAULT 0 AFTER `checktime`;

ALTER TABLE `lailu_ewei_shop_store` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `lng`;

ALTER TABLE `lailu_ewei_shop_store` MODIFY COLUMN `opensend`  tinyint(4) NOT NULL DEFAULT 0 AFTER `citycode`;

ALTER TABLE `lailu_ewei_shop_store` MODIFY COLUMN `diypage_ispage`  tinyint(4) NOT NULL DEFAULT 0 AFTER `diypage`;

ALTER TABLE `lailu_ewei_shop_supplier_apply` MODIFY COLUMN `status`  tinyint(4) NOT NULL COMMENT '0为申请状态1为完成状态' AFTER `apply_time`;

ALTER TABLE `lailu_ewei_shop_sysset` MODIFY COLUMN `sec`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `plugins`;

ALTER TABLE `lailu_ewei_shop_system_adv` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `module`;

ALTER TABLE `lailu_ewei_shop_system_article` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `cate`;

ALTER TABLE `lailu_ewei_shop_system_banner` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_system_case` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_system_company_article` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `cate`;

ALTER TABLE `lailu_ewei_shop_system_copyright` MODIFY COLUMN `ismanage`  tinyint(4) NULL DEFAULT 0 AFTER `bgcolor`;

ALTER TABLE `lailu_ewei_shop_system_copyright_notice` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_system_link` MODIFY COLUMN `status`  tinyint(4) NULL DEFAULT NULL AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_log` MODIFY COLUMN `month`  int(11) NOT NULL DEFAULT 0 AFTER `type`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_log` MODIFY COLUMN `permendtime`  int(11) NOT NULL DEFAULT 0 AFTER `month`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_log` MODIFY COLUMN `permlasttime`  int(11) NOT NULL DEFAULT 0 AFTER `permendtime`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_log` MODIFY COLUMN `isperm`  tinyint(4) NOT NULL DEFAULT 0 AFTER `permlasttime`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_log` MODIFY COLUMN `createtime`  int(11) NOT NULL DEFAULT 0 AFTER `isperm`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_log` MODIFY COLUMN `deleted`  tinyint(4) NULL DEFAULT NULL AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_order` MODIFY COLUMN `createtime`  int(11) NOT NULL DEFAULT 0 AFTER `month`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_order` MODIFY COLUMN `paystatus`  tinyint(4) NOT NULL DEFAULT 0 AFTER `createtime`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_order` MODIFY COLUMN `paytime`  int(11) NOT NULL DEFAULT 0 AFTER `paystatus`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_order` MODIFY COLUMN `paytype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `paytime`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_package` MODIFY COLUMN `state`  tinyint(4) NOT NULL DEFAULT 0 AFTER `data`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_package` MODIFY COLUMN `rec`  tinyint(4) NOT NULL DEFAULT 0 AFTER `state`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_plugin` MODIFY COLUMN `state`  tinyint(4) NOT NULL DEFAULT 0 AFTER `data`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_plugin` MODIFY COLUMN `createtime`  int(11) NOT NULL DEFAULT 0 AFTER `sales`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_plugin` MODIFY COLUMN `plugintype`  tinyint(4) NOT NULL DEFAULT 0 AFTER `displayorder`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_setting` MODIFY COLUMN `weixin`  tinyint(4) NOT NULL DEFAULT 0 AFTER `servertime`;

ALTER TABLE `lailu_ewei_shop_system_plugingrant_setting` MODIFY COLUMN `alipay`  tinyint(4) NOT NULL AFTER `apikey`;

ALTER TABLE `lailu_ewei_shop_system_setting` MODIFY COLUMN `uniacid`  int(11) NULL DEFAULT NULL AFTER `id`;

ALTER TABLE `lailu_ewei_shop_system_setting` MODIFY COLUMN `contact`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `casebanner`;

ALTER TABLE `lailu_ewei_shop_system_site` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_task_qr` MODIFY COLUMN `poster_version`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `ticket`;

ALTER TABLE `lailu_ewei_shop_task_set` MODIFY COLUMN `top_notice`  tinyint(1) NULL DEFAULT NULL AFTER `bg_img`;

CREATE TABLE `lailu_ewei_shop_touch_loop_list` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL ,
`goods_id`  int(11) NOT NULL ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`thumbs`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`sort`  int(11) NULL DEFAULT NULL ,
`credted_at`  int(11) NULL DEFAULT NULL ,
`status`  tinyint(4) NULL DEFAULT NULL ,
`isrecommand`  tinyint(4) NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
INDEX `index_uniacid` (`uniacid`) USING BTREE ,
INDEX `index_status` (`status`) USING BTREE ,
INDEX `index_goods_id` (`goods_id`) USING BTREE ,
INDEX `index_isrecommand` (`isrecommand`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_shop_universalform_category` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NULL DEFAULT NULL ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`merch`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_shop_universalform_data` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL ,
`typeid`  int(11) NOT NULL ,
`cid`  int(11) NULL DEFAULT NULL ,
`fields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`universalformfields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`openid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`type`  tinyint(4) NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_typeid` (`typeid`) USING BTREE ,
INDEX `idx_cid` (`cid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_shop_universalform_temp` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL ,
`typeid`  int(11) NULL DEFAULT NULL ,
`cid`  int(11) NOT NULL ,
`universalformfields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`fields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`openid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`type`  tinyint(1) NULL DEFAULT NULL ,
`universalformid`  int(11) NULL DEFAULT NULL ,
`universalformdata`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`carrier_realname`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`carrier_mobile`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_cid` (`cid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_shop_universalform_type` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL ,
`cate`  int(11) NULL DEFAULT NULL ,
`title`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`adpic`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`adurl`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`fields`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`usedata`  int(11) NOT NULL ,
`alldata`  int(11) NOT NULL ,
`status`  tinyint(1) NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_cate` (`cate`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

ALTER TABLE `lailu_ewei_shop_upwxapp_log` ADD COLUMN `is_live`  tinyint(1) NULL DEFAULT NULL AFTER `is_goods`;

ALTER TABLE `lailu_ewei_shop_upwxapp_log` ADD COLUMN `appid`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `is_live`;

ALTER TABLE `lailu_ewei_shop_upwxapp_log` ADD COLUMN `is_recharge`  tinyint(1) NULL DEFAULT NULL AFTER `appid`;

ALTER TABLE `lailu_ewei_shop_upwxapp_log` MODIFY COLUMN `type`  tinyint(4) NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_upwxapp_log` MODIFY COLUMN `is_goods`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `version_time`;

CREATE INDEX `uniacid` ON `lailu_ewei_shop_verifygoods_log`(`uniacid`) USING BTREE ;

CREATE INDEX `verifygoodsid` ON `lailu_ewei_shop_verifygoods_log`(`verifygoodsid`) USING BTREE ;

CREATE TABLE `lailu_ewei_shop_verifyorder_log` (
`id`  int(11) NOT NULL ,
`uniacid`  int(11) NULL DEFAULT NULL ,
`orderid`  int(11) NULL DEFAULT NULL ,
`salerid`  int(11) NULL DEFAULT NULL ,
`storeid`  int(11) NULL DEFAULT NULL ,
`verifytime`  int(11) NULL DEFAULT NULL ,
`verifyinfo`  longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`id`),
INDEX `uniacid` (`uniacid`) USING BTREE ,
INDEX `orderid` (`orderid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

ALTER TABLE `lailu_ewei_shop_version` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uid`;

ALTER TABLE `lailu_ewei_shop_version` MODIFY COLUMN `version`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_virtual_send_log` MODIFY COLUMN `id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST ;

ALTER TABLE `lailu_ewei_shop_wxapp_page` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_wxapp_poster` MODIFY COLUMN `type`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

ALTER TABLE `lailu_ewei_shop_wxapp_poster` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `lastedittime`;

ALTER TABLE `lailu_ewei_shop_wxapp_startadv` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `uniacid`;

CREATE TABLE `lailu_ewei_shop_wxapp_subscribe` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL ,
`type`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`templateid`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`createtime`  varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `lailu_ewei_shop_wxapp_tmessage` MODIFY COLUMN `status`  tinyint(4) NOT NULL DEFAULT 0 AFTER `emphasis_keyword`;

ALTER TABLE `lailu_ewei_shop_wxcard` MODIFY COLUMN `limitgoodcatetype`  tinyint(3) UNSIGNED NULL DEFAULT 0 AFTER `limitgoodtype`;

ALTER TABLE `lailu_ewei_shop_wxcard` MODIFY COLUMN `limitdiscounttype`  tinyint(3) UNSIGNED NULL DEFAULT 0 AFTER `limitgoodids`;

ALTER TABLE `lailu_ewei_shop_wxcard` MODIFY COLUMN `gettype`  tinyint(4) NULL DEFAULT NULL AFTER `merchid`;

CREATE TABLE `lailu_ewei_shop_wxlive` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NOT NULL ,
`room_id`  int(11) NOT NULL ,
`name`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`cover_img`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`live_status`  tinyint(4) NOT NULL ,
`local_live_status`  tinyint(1) NOT NULL ,
`start_time`  int(11) NOT NULL ,
`end_time`  int(11) NOT NULL ,
`anchor_name`  varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`anchor_img`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`goods_json`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
`is_top`  tinyint(1) NOT NULL ,
`is_recommend`  tinyint(1) NOT NULL ,
`status`  tinyint(4) NOT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_start_time` (`start_time`) USING BTREE ,
INDEX `idx_end_time` (`end_time`) USING BTREE ,
INDEX `idx_is_top` (`is_top`) USING BTREE ,
INDEX `idx_is_recommend` (`is_recommend`) USING BTREE ,
INDEX `idx_local_live_status` (`local_live_status`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

CREATE TABLE `lailu_ewei_shop_wxlive_back` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(11) NULL DEFAULT NULL ,
`live_id`  int(11) NOT NULL ,
`room_id`  int(11) NOT NULL ,
`expire_time`  int(11) NULL DEFAULT NULL ,
`create_time`  int(11) NULL DEFAULT NULL ,
`media_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`show_times`  int(11) NULL DEFAULT NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_roomid` (`room_id`) USING BTREE ,
INDEX `idx_liveid` (`live_id`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_about` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`company_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '企业名称' ,
`site_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址' ,
`slogan`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公司标语' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '展示图' ,
`qrcode`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二维码' ,
`description`  varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '简介' ,
`mobile`  varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机' ,
`telephone`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '电话' ,
`work_time`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '工作时间' ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公司地址' ,
`email`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱' ,
`qq`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'qq号' ,
`latitude`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '维度' ,
`longitude`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '经度' ,
`bmap_apikey`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '百度底提apikey' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '发布时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_banner` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '广告名称' ,
`description`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '广告描述' ,
`bgimage`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '首页广告背景图' ,
`inbgimage`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '内容页广告背景图' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT 'banner图' ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '广告链接' ,
`status`  tinyint(1) NULL DEFAULT 1 COMMENT '状态(0隐藏 1显示)' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '添加时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更改时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_comments` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`title`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '评语' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '展示图' ,
`author`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '发布者' ,
`status`  tinyint(1) NULL DEFAULT 1 COMMENT '状态(0隐藏 1显示)' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '添加时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更改时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_friendlink` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`site_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站点名称' ,
`site_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址' ,
`status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(0隐藏 1正常)' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '发布时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_news` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`cate_pid`  int(11) NOT NULL DEFAULT 0 COMMENT '父级分类ID' ,
`cate_id`  int(11) NOT NULL DEFAULT 0 COMMENT '分类ID' ,
`cate_pname`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '父级分类名称' ,
`cate_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称' ,
`title`  varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文章标题' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '缩略图' ,
`description`  varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '文章摘要' ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容' ,
`browse_count`  int(11) NOT NULL DEFAULT 0 COMMENT '浏览量' ,
`status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(0隐藏 1正常)' ,
`is_link`  tinyint(1) NULL DEFAULT 0 COMMENT '是否是链接' ,
`link_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '发布时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_cateid` (`cate_id`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_news_category` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`pid`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级分类ID' ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类标题' ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '分类链接' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '图标' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`status`  tinyint(1) NULL DEFAULT 1 COMMENT '状态(0隐藏 1显示)' ,
`cate_level`  tinyint(1) NULL DEFAULT 1 COMMENT '分类级别(1级 2级)' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '添加时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更改时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE ,
INDEX `idx_level` (`cate_level`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_partner` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '名称' ,
`logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'logo图' ,
`url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '链接' ,
`status`  tinyint(1) NULL DEFAULT 1 COMMENT '状态(0隐藏 1显示)' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '添加时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更改时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_product` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`cate_id`  int(11) NOT NULL DEFAULT 0 COMMENT '分类ID' ,
`cate_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称' ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '产品标题' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '缩略图' ,
`description`  varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '产品摘要' ,
`content`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容' ,
`browse_count`  int(11) NOT NULL DEFAULT 0 COMMENT '浏览量' ,
`status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(0隐藏 1正常)' ,
`is_link`  tinyint(1) NULL DEFAULT 0 COMMENT '是否是链接' ,
`link_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '链接地址' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '发布时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_cateid` (`cate_id`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_product_category` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`pid`  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级分类ID' ,
`name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '分类名称' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '缩略图' ,
`description`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分类描述' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`status`  tinyint(1) NULL DEFAULT 1 COMMENT '状态(0隐藏 1显示)' ,
`cate_level`  tinyint(1) NULL DEFAULT 1 COMMENT '分类级别(1级 2级)' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '添加时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更改时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_setting` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`setvalue`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_site` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`site_domain`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站点域名' ,
`site_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站点名称' ,
`site_logo`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '站点logo' ,
`copyrigth`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '版权申明' ,
`record_num`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '网站备案号' ,
`company_name`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '企业名称' ,
`site_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '企业网址' ,
`slogan`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公司标语' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '展示图' ,
`qrcode`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二维码' ,
`description`  varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '简介' ,
`mobile`  varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机' ,
`telephone`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '电话' ,
`work_time`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '工作时间' ,
`address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '公司地址' ,
`email`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '邮箱' ,
`qq`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'qq号' ,
`latitude`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '维度' ,
`longitude`  varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '经度' ,
`bmap_apikey`  varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '百度底提apikey' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '发布时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' ,
`status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_domain` (`site_domain`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

CREATE TABLE `lailu_ewei_webappfox_work` (
`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`uniacid`  int(10) UNSIGNED NOT NULL ,
`title`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '业务名称' ,
`bgcolor`  varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '模块展示颜色' ,
`icon`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '缩略图标' ,
`thumb`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '展示图' ,
`description`  varchar(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '业务介绍' ,
`status`  tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态(0隐藏 1正常)' ,
`display_order`  int(11) NULL DEFAULT 0 COMMENT '排序' ,
`add_time`  int(11) NOT NULL DEFAULT 0 COMMENT '发布时间' ,
`update_time`  int(11) NOT NULL DEFAULT 0 COMMENT '更新时间' ,
PRIMARY KEY (`id`),
INDEX `idx_uniacid` (`uniacid`) USING BTREE ,
INDEX `idx_status` (`status`) USING BTREE
)
ENGINE=MyISAM
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
CHECKSUM=0
ROW_FORMAT=Dynamic
DELAY_KEY_WRITE=0
;

DROP TABLE `lailu_account`;

DROP TABLE `lailu_account_aliapp`;

DROP TABLE `lailu_account_baiduapp`;

DROP TABLE `lailu_account_phoneapp`;

DROP TABLE `lailu_account_toutiaoapp`;

DROP TABLE `lailu_account_webapp`;

DROP TABLE `lailu_account_wechats`;

DROP TABLE `lailu_account_wxapp`;

DROP TABLE `lailu_account_xzapp`;

DROP TABLE `lailu_activity_clerks`;

DROP TABLE `lailu_activity_clerk_menu`;

DROP TABLE `lailu_article_category`;

DROP TABLE `lailu_article_comment`;

DROP TABLE `lailu_article_news`;

DROP TABLE `lailu_article_notice`;

DROP TABLE `lailu_article_unread_notice`;

DROP TABLE `lailu_attachment_group`;

DROP TABLE `lailu_basic_reply`;

DROP TABLE `lailu_business`;

DROP TABLE `lailu_core_attachment`;

DROP TABLE `lailu_core_cache`;

DROP TABLE `lailu_core_cron`;

DROP TABLE `lailu_core_cron_record`;

DROP TABLE `lailu_core_job`;

DROP TABLE `lailu_core_menu1`;

DROP TABLE `lailu_core_menu_shortcut`;

DROP TABLE `lailu_core_paylog`;

DROP TABLE `lailu_core_performance`;

DROP TABLE `lailu_core_queue`;

DROP TABLE `lailu_core_refundlog`;

DROP TABLE `lailu_core_resource`;

DROP TABLE `lailu_core_sendsms_log`;

DROP TABLE `lailu_core_sessions`;

DROP TABLE `lailu_core_settings`;

DROP TABLE `lailu_coupon_location`;

DROP TABLE `lailu_cover_reply`;

DROP TABLE `lailu_custom_reply`;

DROP TABLE `lailu_images_reply`;

DROP TABLE `lailu_mc_cash_record`;

DROP TABLE `lailu_mc_chats_record`;

DROP TABLE `lailu_mc_credits_recharge`;

DROP TABLE `lailu_mc_credits_record`;

DROP TABLE `lailu_mc_fans_groups`;

DROP TABLE `lailu_mc_fans_tag`;

DROP TABLE `lailu_mc_fans_tag_mapping`;

DROP TABLE `lailu_mc_groups`;

DROP TABLE `lailu_mc_handsel`;

DROP TABLE `lailu_mc_mapping_fans`;

DROP TABLE `lailu_mc_mass_record`;

DROP TABLE `lailu_mc_members`;

DROP TABLE `lailu_mc_member_address`;

DROP TABLE `lailu_mc_member_fields`;

DROP TABLE `lailu_mc_member_property`;

DROP TABLE `lailu_mc_oauth_fans`;

DROP TABLE `lailu_menu_event`;

DROP TABLE `lailu_message_notice_log`;

DROP TABLE `lailu_mobilenumber`;

DROP TABLE `lailu_modules`;

DROP TABLE `lailu_modules_bindings`;

DROP TABLE `lailu_modules_cloud`;

DROP TABLE `lailu_modules_ignore`;

DROP TABLE `lailu_modules_plugin`;

DROP TABLE `lailu_modules_plugin_rank`;

DROP TABLE `lailu_modules_rank`;

DROP TABLE `lailu_modules_recycle`;

DROP TABLE `lailu_music_reply`;

DROP TABLE `lailu_news_reply`;

DROP TABLE `lailu_phoneapp_versions`;

DROP TABLE `lailu_profile_fields`;

DROP TABLE `lailu_qrcode`;

DROP TABLE `lailu_qrcode_stat`;

DROP TABLE `lailu_rule`;

DROP TABLE `lailu_rule_keyword`;

DROP TABLE `lailu_site_article`;

DROP TABLE `lailu_site_article_comment`;

DROP TABLE `lailu_site_category`;

DROP TABLE `lailu_site_multi`;

DROP TABLE `lailu_site_nav`;

DROP TABLE `lailu_site_page`;

DROP TABLE `lailu_site_slide`;

DROP TABLE `lailu_site_store_cash_log`;

DROP TABLE `lailu_site_store_cash_order`;

DROP TABLE `lailu_site_store_create_account`;

DROP TABLE `lailu_site_store_goods`;

DROP TABLE `lailu_site_store_goods_cloud`;

DROP TABLE `lailu_site_store_order`;

DROP TABLE `lailu_site_styles`;

DROP TABLE `lailu_site_styles_vars`;

DROP TABLE `lailu_site_templates`;

DROP TABLE `lailu_stat_fans`;

DROP TABLE `lailu_stat_keyword`;

DROP TABLE `lailu_stat_msg_history`;

DROP TABLE `lailu_stat_rule`;

DROP TABLE `lailu_stat_visit`;

DROP TABLE `lailu_stat_visit_ip`;

DROP TABLE `lailu_system_stat_visit`;

DROP TABLE `lailu_system_welcome_binddomain`;

DROP TABLE `lailu_uni_account`;

DROP TABLE `lailu_uni_account_extra_modules`;

DROP TABLE `lailu_uni_account_group`;

DROP TABLE `lailu_uni_account_menus`;

DROP TABLE `lailu_uni_account_modules`;

DROP TABLE `lailu_uni_account_modules_shortcut`;

DROP TABLE `lailu_uni_account_users`;

DROP TABLE `lailu_uni_group`;

DROP TABLE `lailu_uni_link_uniacid`;

DROP TABLE `lailu_uni_modules`;

DROP TABLE `lailu_uni_settings`;

DROP TABLE `lailu_uni_verifycode`;

DROP TABLE `lailu_userapi_cache`;

DROP TABLE `lailu_userapi_reply`;

DROP TABLE `lailu_users`;

DROP TABLE `lailu_users_bind`;

DROP TABLE `lailu_users_create_group`;

DROP TABLE `lailu_users_extra_group`;

DROP TABLE `lailu_users_extra_limit`;

DROP TABLE `lailu_users_extra_modules`;

DROP TABLE `lailu_users_extra_templates`;

DROP TABLE `lailu_users_failed_login`;

DROP TABLE `lailu_users_founder_group`;

DROP TABLE `lailu_users_founder_own_create_groups`;

DROP TABLE `lailu_users_founder_own_uni_groups`;

DROP TABLE `lailu_users_founder_own_users`;

DROP TABLE `lailu_users_founder_own_users_groups`;

DROP TABLE `lailu_users_group`;

DROP TABLE `lailu_users_invitation`;

DROP TABLE `lailu_users_lastuse`;

DROP TABLE `lailu_users_login_logs`;

DROP TABLE `lailu_users_operate_history`;

DROP TABLE `lailu_users_operate_star`;

DROP TABLE `lailu_users_permission`;

DROP TABLE `lailu_users_profile`;

DROP TABLE `lailu_video_reply`;

DROP TABLE `lailu_voice_reply`;

DROP TABLE `lailu_wechat_attachment`;

DROP TABLE `lailu_wechat_news`;

DROP TABLE `lailu_wxapp_general_analysis`;

DROP TABLE `lailu_wxapp_versions`;

DROP TABLE `lailu_wxcard_reply`;

SET FOREIGN_KEY_CHECKS=1;

