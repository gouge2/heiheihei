alter table lailu_bood_log modify payment enum('banlance','alipay','wxpay','int_wx','int_ali','paypal') NOT NULL DEFAULT 'alipay';
