<?php
/**
 * by 翠花 http://http://livedd.com
 * 纽元通
 */

namespace Admin\Controller;

use Admin\Common\Controller\AuthController;
use Common\Model\SettingModel;

class LatipayConfigController extends AuthController
{
    public function index()
    {
        $msg['latipay_type'] = defined('LATIPAY_TYPE') ? LATIPAY_TYPE : '';
        $msg['twitter_type'] = defined('TWITTER_TYPE') ? TWITTER_TYPE : '';
        $msg['facebook_type'] = defined('FACEBOOK_TYPE') ? FACEBOOK_TYPE : '';
        $msg['paypal_type'] = defined('PAYPAL_TYPE') ? PAYPAL_TYPE : '';
        $msg['multi_language'] = defined('MULTI_LANGUAGE') ? MULTI_LANGUAGE : '';
        $this->assign('list', $msg);
        $this->display();
    }

    /**
     * 修改纽元通 开启、关闭
     */
    public function catShow()
    {
        $sw = I('post.sw/d');

        layout(false);

        $model_setting = new SettingModel();
        $file = "./Public/inc/account.config.php";

        // 保存
        $key = 0;
        if ($sw == 1) $key = 1;

        $type = '';
        switch ($_POST['std']) {
            case 'st1':
                $type = 'LATIPAY_TYPE';
                break;
            case 'st2':
                $type = 'TWITTER_TYPE';
                break;
            case 'st3':
                $type = 'FACEBOOK_TYPE';
                break;
            case 'st4':
                $type = 'PAYPAL_TYPE';
                break;
            case 'st5':
                $type ='MULTI_LANGUAGE';
                break;
        }
        $model_setting->set($type, $key, $file);

        $this->cacheSetting($file);
    }

    // 纽元通设置
    public function setUp()
    {
        if ($_POST) {
            layout(false);

            $model_setting = new SettingModel();
            $file = "./Public/inc/account.config.php";

            // 提交的key
            switch ($_POST['id']) {
                case '1':
                    $post_key = ['latipay_uid', 'latipay_wallet', 'latipay_key', 'latipay_url', 'my_exchange'];
                    break;
                case '2':
                    $post_key = ['twitter_appkey','twitter_secretkey'];
                    break;
                case '3':
                    $post_key = ['paypal_clientid','paypal_clientsecret','paypal_gettoken','paypal_createorderurl','paypal_checkorderurl'];
                    break;
            }


            // 保存
            foreach ($post_key as $val) {
                $model_setting->set(strtoupper($val), I('post.' . $val), $file);
            }
            $this->cacheSetting($file);
            $this->success('编辑成功');

        } else {
            switch ($_GET['id']) {
                case '1':
                    $msg['latipay_uid'] = defined('LATIPAY_UID') ? LATIPAY_UID : '';
                    $msg['latipay_wallet'] = defined('LATIPAY_WALLET') ? LATIPAY_WALLET : '';
                    $msg['latipay_key'] = defined('LATIPAY_KEY') ? LATIPAY_KEY : '';
                    $msg['latipay_url'] = defined('LATIPAY_URL') ? LATIPAY_URL : '';
                    $msg['exchange'] = $this->exchange() ?: '汇率异常,请检查接口';
                    $msg['my_exchange'] = defined('MY_EXCHANGE') ? MY_EXCHANGE : "";
                    $this->assign('msg', $msg);
                    $this->display();
                    break;
                case '2':
                    $msg['twitter_appkey'] = defined('TWITTER_APPKEY') ? TWITTER_APPKEY : '';
                    $msg['twitter_secretkey'] = defined('TWITTER_SECRETKEY') ? TWITTER_SECRETKEY : '';
                    $this->assign('msg', $msg);
                    $this->display('twitter');
                    break;
                case '3':
                    $msg['paypal_clientid'] = defined('PAYPAL_CLIENTID') ? PAYPAL_CLIENTID : '';
                    $msg['paypal_clientsecret'] = defined('PAYPAL_CLIENTSECRET') ? PAYPAL_CLIENTSECRET : '';
                    $msg['paypal_gettoken'] = defined('PAYPAL_GETTOKEN') ? PAYPAL_GETTOKEN : '';
                    $msg['paypal_createorderurl'] = defined('PAYPAL_CREATEORDERURL') ? PAYPAL_CREATEORDERURL : '';
                    $msg['paypal_checkorderurl'] = defined('PAYPAL_CHECKORDERURL') ? PAYPAL_CHECKORDERURL : '';
                    $this->assign('msg', $msg);
                    $this->display('paypal');
                    break;
            }

        }
    }

    /**
     * 百度汇率实时查询
     * @return string|string[]|null
     */
    protected function exchange()
    {
        $data = file_get_contents("http://www.baidu.com/s?wd=NZD%20CNY&rsv_spt=1");
        preg_match("/<div>1\D*=(\d*\.\d*)\D*<\/div>/", $data, $converted);
        $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
        return $converted;
    }

}