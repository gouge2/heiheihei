<?php
/**
 * by 翠花 http://www.lailu.shop
 * 多商户
 */

namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class MultiMerchantController extends AuthController
{
    public function index()
    {
        $Multi = new \Common\Model\MultiMerchantModel();
        $list = $Multi->getBannerCatList();
        if (!$list) {
            // 如果没有则新增初始化配置
            $data = [
                "id" =>  "1", "type" =>  "0", "settle_in" =>  "1", "authority" =>  "0", "verified" =>  "1", "margin" =>  "1", "total_amount" =>  "0", "description" => NULL, "introduction" => NULL, "payment" =>  "0",
            ];
            $Multi->add($data);
            $list = $Multi->getBannerCatList();
        }
        foreach ($list as $k =>$v) {
            $list[$k]['latipay_type'] = defined('LATIPAY_TYPE') ? LATIPAY_TYPE : '';
            $list[$k]['twitter_type'] = defined('TWITTER_TYPE') ? TWITTER_TYPE : '';
            $list[$k]['facebook_type'] = defined('FACEBOOK_TYPE') ? FACEBOOK_TYPE : '';
            $list[$k]['paypal_type'] = defined('PAYPAL_TYPE') ? PAYPAL_TYPE : '';
            $list[$k]['multi_language'] = defined('MULTI_LANGUAGE') ? MULTI_LANGUAGE : '';
        }
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 修改多商户 开启、关闭
     */
    public function catShow()
    {
        $sw = I('post.sw/d');
        $cid = I('post.cid/d');

        if ($sw && $cid) {
            $Multi = new \Common\Model\MultiMerchantModel();
            $Gooods = new \Common\Model\GoodsModel();
            $ShopGoodsModel = new \Common\Model\ShopGoodsModel();
            $Multi->where(['id' => $cid])->save(['type' => ($sw == 1 ? 1 : 0)]);

            // 商品下架
            if ($sw == 2) {
                $whe['shop_id'] = array("GT", 0);
                $Gooods->where($whe)->save(['is_show'=>'N']);
                $ShopGoodsModel->where('1')->save(['status'=>0]);
            }
        }
    }

    // 多商户设置
    public function setUp()
    {
        if (I('post.')) {
            $data = [
                'settle_in' => $_POST['settle_in'],
                'verified' => $_POST['verified'],
                'margin' => $_POST['margin'],
                'total_amount' => $_POST['total_amount'],
                'description' => $_POST['description'],
                'introduction' => $_POST['introduction'],
            ];
            $data['authority'] = 0;
            if (I('post.authority')) {
                $pay_data = array_keys(I('post.authority'));
                $data['authority'] = implode(",", $pay_data);
            }
            $data['payment'] = 0;
            if (I('post.payment')) {
                $pay_data = array_keys(I('post.payment'));
                $data['payment'] = implode(",", $pay_data);
            }
            $Multi = new \Common\Model\MultiMerchantModel();
            $Multi->mod($data);
        }
        $Multi = new \Common\Model\MultiMerchantModel();
        $list = $Multi->find();
        $list['latipay_type'] = defined('LATIPAY_TYPE') ? LATIPAY_TYPE : '';
        $list['paypal_type'] = defined('LATIPAY_TYPE') ? LATIPAY_TYPE : '';
        $list['multi_language'] = defined('MULTI_LANGUAGE') ? MULTI_LANGUAGE : '';
        $this->assign('msg', $list);
        $this->display();
    }
}