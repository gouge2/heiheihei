<?php

//20200612
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Set_EweiShopV2Page extends PluginWebPage {


    function main()
    {
        global $_W, $_GPC;


        if ($_W['ispost']) {
            $data = is_array($_GPC['data']) ? $_GPC['data'] : array();

            $data['applycontent'] = m('common')->html_images($data['applycontent']);
            $data['regbg'] = save_media($data['regbg']);
            $data['regpic'] = save_media($data['regpic']);
            $data['reglogo'] = save_media($data['reglogo']);

            $data['applycashweixin'] = intval($data['applycashweixin']);
            $data['applycashalipay'] = intval($data['applycashalipay']);
            $data['applycashcard'] = intval($data['applycashcard']);

            m('common')->updatePluginset(array('merch'=>$data));
            //模板缓存
            m('cache')->set('template_' . $this->pluginname, $data['style']);
            plog('merch.set.edit', '修改基本设置');
            $host = $data['host'];
            if (empty($host))
            {
                pdo_delete('ewei_shop_domain_bindings',array('uniacid'=>$_W['uniacid'],'plugin'=>'merch'));
            }
            $binding = array(
                'uniacid'=>$_W['uniacid'],
                'domain'=>$host,
                'plugin'=>'merch'
            );
            pdo_insert('ewei_shop_domain_bindings',$binding);
            show_json(1,array('url'=>webUrl('merch/set', array('tab'=>str_replace("#tab_","",$_GPC['tab'])))));
        }
        $url = $_W['siteroot']."web/merchant.php?i=".$_W['uniacid'];
        $qrcode = m('qrcode')->createQrcode($url);

        $styles = array();
        $dir = IA_ROOT . "/addons/shop/plugin/" . $this->pluginname . "/template/mobile/";
        if ($handle = opendir($dir)) {
            while (($file = readdir($handle)) !== false) {
                if ($file != ".." && $file != ".") {
                    if (is_dir($dir . "/" . $file)) {
                        $styles[] = $file;
                    }
                }
            }
            closedir($handle);
        }
        $form_list = false;
        if(p('diyform')){
            $form_list = p('diyform')->getDiyformList();
        }

        $data = m('common')->getPluginset('merch');
        include $this->template();
    }

}