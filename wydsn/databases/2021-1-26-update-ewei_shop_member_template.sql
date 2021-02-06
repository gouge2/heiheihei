ALTER TABLE `lailu_ewei_shop_member_printer_template`
ADD COLUMN `ordersn_code_type`  tinyint(1) NOT NULL DEFAULT 0 AFTER `productsn`,
ADD COLUMN `tel_code_type`  tinyint(1) NOT NULL DEFAULT 0 AFTER `ordersn_code_type`;
