<?php


namespace App\Controller;


use App\Common\Controller\AuthController;
use Common\Controller\JingtuituiController;
use Common\Model\AdvertHeadModel;
use Common\Model\GoodsCollectModel;
use Common\Model\HaodankuModel;
use Common\Model\HostTreatModel;
use Common\Model\JingdongCollectModel;
use Common\Model\PddCollectModel;
use Common\Model\TbGoodsCollectModel;
use Common\Model\UserModel;

class AdvertHeadController extends AuthController
{

    public $tb_min_id;
    /**
     * $back 获取数据条数
     * $min_id  分页
     * $tb_p  淘宝分页，用于实现类似分页抓取效果
     * $hour_type 时间
     * $client  客户端 app 和 小程序
     * $modular  活动模块 1,2,3
     */
    public function getGoodrecommendation()
    {
        $back = 10;
        $min_id = 1;
        $hour_type = '';
        $client = 'app';
        $modular = 1;
        if (I('post.back')) {
            $back = I('post.back');
        }
        if (I('post.min_id')) {
            $min_id = I('post.min_id');
        }
        if (I('post.hour_type')) {
            $hour_type = I('post.hour_type');
        }
        if (I('post.client')) {
            $client = I('post.client');
            if (!in_array($client,['app','applets'])) {
                $this->ajaxError('1','参数错误');
            }
        }
        if (I('post.modular')) {
            $modular = I('post.modular');
        }
        if (I('post.token')) {
            $token = I('post.token');
            $User = new UserModel();
            $uid = $User->getUserId($token);
        }

        $retList = array();
        $AdvertModel = new AdvertHeadModel();
        $list = $AdvertModel->where(['advert_modular'=>$modular,'advert_switch'=>1,'advert_client'=>$client])->find();
        if ($list['advert_coupon'] == 3) $list['advert_coupon'] = '';
        // 获取用户收藏
        $TbGoods = new TbGoodsCollectModel();
        if ($uid) {
            $collerlist = [];
            $GoodsList = $TbGoods->getCollectList($uid);
            foreach ($GoodsList as $key => $value) {
                $collerlist[] = $value['goods_id'];
            }
        }
         // 主播佣金
        $HostTreatModel = new HostTreatModel();

        if (empty($list)) {
            $retList['advert_title'] = '';
            $retList['advert_img'] = '';
            $retList['advert_client'] = '';
            $retList['advert_source'] = '';
            if ($modular == 1) {
                if ($client == 'app') {
                    $retList['advert_client'] = $client;
                    $retList['advert_source'] = 'tb';
                    $Tbk = new TbkController();
                    $res = $Tbk->getHotGoodsList(1);
                    foreach ($res as $key => $value) {
                        $temp['goods_id'] = $value['goods_id'];  // 商品ID
                        $temp['source'] = 'tb';                           // 来源
                        $temp['goods_name'] = $value['goods_name']; // 标题
                        $temp['goods_content'] = $value['description']; // 短标题
                        $temp['sales_volume'] = $value['volume']; // 月销量
                        $temp['img'] = $value['pict_url'];  // 主图
                        $temp['old_price'] = $value['zk_final_price']; // 在售价
                        $temp['price'] = substr(sprintf("%.3f", ($value['zk_final_price']-$value['coupon_amount'])), 0, -1);  // 券后价
                        $temp['commission_ratio'] = $value['commission_rate'];   // 佣金比率
                        $temp['commission'] = sprintf("%.2f", ($temp['price']*$value['commission_rate']/100));   // 佣金
                        $temp['coupon_amount'] = $value['coupon_amount'];   // 优惠券金额
                        $temp['couponurl'] = '';   // 优惠券链接
                        $temp['is_collect'] = $value['is_collect'];
                        $retList['list'][] = $temp;
                    }
                } elseif($client == 'applets') {
                    $retList['advert_client'] = $client;
                    $retList['advert_source'] = 'jd';
                    $Jingdong = new JingdongController();
                    $res = $Jingdong->getNewList(1);
                    $retList['list'] = $res;
                }
                $this->ajaxSuccess($retList);
            } elseif ($modular == 2) {
                $retList['advert_client'] = $client;
                $retList['advert_source'] = 'tb';
                $Haodanku = new HaodankuModel();
                $res = $Haodanku->getFastBuyList($min_id,$hour_type);
                foreach ($res['data'] as $key => $value) {
                    $temp['goods_id'] = $value['itemid'];  // 商品ID
                    $temp['source'] = 'tb';                           // 来源
                    $temp['goods_name'] = $value['itemtitle']; // 标题
                    $temp['goods_content'] = $value['itemshorttitle']; // 短标题
                    $temp['sales_volume'] = $value['itemsale']; // 月销量
                    $temp['img'] = $value['itempic'].'_310x310.jpg';  // 主图
                    $temp['old_price'] = $value['itemprice']; // 在售价
                    $temp['price'] = $value['itemendprice'];  // 券后价
                    $temp['commission_ratio'] = $value['tkrates'];   // 佣金比率
                    $temp['commission'] = $value['tkmoney'];   // 佣金
                    $temp['coupon_amount'] = $value['couponmoney'];   // 优惠券金额
                    $temp['couponurl'] = $value['couponurl'];   // 优惠券链接


                    #效验是否存在商品列表
                    $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                    $goodsItem = $shortLiveModel->getOne(['from'=>'tb','goods_id'=>$value['itemid']]);
                    $is_has = 0;
                    if($goodsItem)
                    {
                        $is_has = 1;
                    }
                    // 佣金基数
                    $commission_base = $temp['price'] * $temp['commission_ratio']/ 100;
                    #新版佣金获取
                    $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                    $temp['commission'] = $userCommission['userHasCommission'];
                    $temp['commission_host'] = $userCommission['hostUserCommission'];

                    if (in_array($value['itemid'],$collerlist)) {
                        $temp['is_collect'] = 'Y';
                    } else {
                        $temp['is_collect'] = 'N';
                    }
                    $retList['list'][] = $temp;
                }
                $this->ajaxSuccess($retList);
            } else {
                $retList['advert_client'] = $client;
                $retList['advert_source'] = 'tb';
                $Haodanku = new HaodankuModel();
                $res = $Haodanku->getGoodsList(1,0,$back,$min_id);
                $retList['min_id'] = $res['min_id'];
                foreach ($res['data'] as $key => $value) {
                    $temp['goods_id'] = $value['itemid'];  // 商品ID
                    $temp['source'] = 'tb';                           // 来源
                    $temp['goods_name'] = $value['itemtitle']; // 标题
                    $temp['goods_content'] = $value['itemshorttitle']; // 短标题
                    $temp['sales_volume'] = $value['itemsale']; // 月销量
                    $temp['img'] = $value['itempic'].'_310x310.jpg';  // 主图
                    $temp['old_price'] = $value['itemprice']; // 在售价
                    $temp['price'] = $value['itemendprice'];  // 券后价
                    $temp['commission_ratio'] = $value['tkrates'];   // 佣金比率
                    $temp['commission'] = $value['tkmoney'];   // 佣金
                    $temp['coupon_amount'] = $value['couponmoney'];   // 优惠券金额
                    $temp['couponurl'] = $value['couponurl'];   // 优惠券链接

                    #效验是否存在商品列表
                    $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                    $goodsItem = $shortLiveModel->getOne(['from'=>'tb','goods_id'=>$temp['goods_id']]);
                    $is_has = 0;
                    if($goodsItem)
                    {
                        $is_has = 1;
                    }
                    // 佣金基数
                    $commission_base = $temp['price'] * $temp['commission_ratio']/ 100;
                    #新版佣金获取
                    $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                    $temp['commission'] = $userCommission['userHasCommission'];
                    $temp['commission_host'] = $userCommission['hostUserCommission'];

                    if (in_array($value['itemid'],$collerlist)) {
                        $temp['is_collect'] = 'Y';
                    } else {
                        $temp['is_collect'] = 'N';
                    }
                    $retList['list'][] = $temp;
                }
                $this->ajaxSuccess($retList);
            }
        }

        $retList['advert_title'] = $list['advert_title'];
        $retList['advert_client'] = $list['advert_client'];
        $retList['advert_source'] = $list['advert_source'];
        if (!empty($list['advert_img'])) {
            $retList['advert_img'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$list['advert_img'];
        }

        if ($list['advert_source'] == 'tb') {
            $TbGoods = new TbGoodsCollectModel();
            if ($uid) {
                $collerlist = [];
                $GoodsList = $TbGoods->getCollectList($uid);
                foreach ($GoodsList as $key => $value) {
                    $collerlist[] = $value['goods_id'];
                }
            }
            $parm = array(
                'advert_cat_id' =>$list['advert_cat_id'],
                'category' => $list['advert_catgray'],
                'price_min' => $list['advert_price_min'],
                'price_max' => $list['advert_price_max'],
                'coupon' => $list['advert_coupon'],
                'advert_cat' => $list['advert_cat'],
                'advert_word' => $list['advert_word'],
                'amount_min' => $list['advert_amount_min'],
                'hour_type' => $hour_type
            );
            $rst = $this->getTbinfo($list['diy_id'],$back,$min_id,$parm);
            foreach ($rst as $key => $value) {

                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'tb','goods_id'=>$value['goods_id']]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                // 佣金基数
                $commission_base = $value['price'] * $value['commission_ratio']/ 100;
                #新版佣金获取
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                $rst[$key]['commission'] = $userCommission['userHasCommission'];
                $rst[$key]['commission_host'] = $userCommission['hostUserCommission'];

                if (in_array($value['goods_id'],$collerlist)) {
                    $rst[$key]['is_collect'] = 'Y';
                } else {
                    $rst[$key]['is_collect'] = 'N';
                }
            }
            if (!empty($this->tb_min_id)) {
                $retList['min_id'] = $this->tb_min_id;
            }
        } elseif ($list['advert_source'] == 'jd') {
            $JingdongCollect = new JingdongCollectModel();
            if ($uid) {
                $collerlist = [];
                $GoodsList = $JingdongCollect->getCollectList($uid);
                foreach ($GoodsList as $key => $value) {
                    $collerlist[] = $value['goods_id'];
                }
            }
            $parm = array(
                'advert_cat_id' => $list['advert_cat_id'],
                'advert_cat' => $list['advert_cat'],
                'category' => $list['advert_catgray'],
                'advert_word' => $list['advert_word'],
                'commissionShareStart' => $list['advert_amount_min'],
                'commissionShareEnd' => $list['advert_amount_max'],
                'isCoupon' => $list['advert_coupon'],

            );
            $rst = $this->getJdinfo($list['diy_id'],$back,$min_id,$parm);
            foreach ($rst as $key => $value) {

                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'jd','goods_id'=>$value['goods_id']]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                // 佣金基数
                $commission_base = $value['price'] * $value['commission_ratio']/ 100;
                #新版佣金获取
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                $rst[$key]['commission'] = $userCommission['userHasCommission'];
                $rst[$key]['commission_host'] = $userCommission['hostUserCommission'];

                if (in_array($value['goods_id'],$collerlist)) {
                    $rst[$key]['is_collect'] = 'Y';
                } else {
                    $rst[$key]['is_collect'] = 'N';
                }
            }
        } elseif ($list['advert_source'] == 'pdd') {
            $PddCollect = new PddCollectModel();
            if ($uid) {
                $collerlist = [];
                $GoodsList = $PddCollect->getCollectList($uid);
                foreach ($GoodsList as $key => $value) {
                    $collerlist[] = $value['goods_id'];
                }
            }
            $parm = array(
                'advert_cat_id' => $list['advert_cat_id'],
                'category' => $list['advert_catgray'],
                'advert_word' => $list['advert_word']
            );
            $rst = $this->getPddinfo($list['diy_id'],$back,$min_id,$parm);
            foreach ($rst as $key => $value) {

                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'pdd','goods_id'=>$value['goods_id']]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                // 佣金基数
                $commission_base = $value['price'] * $value['commission_ratio']/ 100;
                #新版佣金获取
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                $rst[$key]['commission'] = $userCommission['userHasCommission'];
                $rst[$key]['commission_host'] = $userCommission['hostUserCommission'];

                if (in_array($value['goods_id'],$collerlist)) {
                    $rst[$key]['is_collect'] = 'Y';
                } else {
                    $rst[$key]['is_collect'] = 'N';
                }
            }
        } elseif ($list['advert_source'] == 'self') {
            $GoodsCollect = new GoodsCollectModel();
            if ($uid) {
                $collerlist = [];
                $GoodsList = $GoodsCollect->getCollectList($uid);
                foreach ($GoodsList as $key => $value) {
                    $collerlist[] = $value['goods_id'];
                }
            }
            $parm = array(
                'advert_cat_id' => $list['advert_cat_id'],
                'category' => $list['advert_catgray'],
            );
            $rst = $this->getSelfinfo($list['diy_id'],$back,$min_id,$parm);
            foreach ($rst as $key => $value) {

                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'self','goods_id'=>$value['goods_id']]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                // 佣金基数
                $commission_base = $value['price'] * $value['commission_ratio']/ 100;
                #新版佣金获取
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                $rst[$key]['commission'] = $userCommission['userHasCommission'];
                $rst[$key]['commission_host'] = $userCommission['hostUserCommission'];

                if (in_array($value['itemid'],$collerlist)) {
                    $rst[$key]['is_collect'] = 'Y';
                } else {
                    $rst[$key]['is_collect'] = 'N';
                }
            }
        }
        $retList['list'] = $rst;

        $this->ajaxSuccess($retList);

    }

    /**
     * 淘宝
     * @param $diy_id   自定义
     * @param $back     每页返回条数
     * @param $min_id   分页
     * @param string $advert_cat_id   商品ID  ‘12313，1231’
     * @param string $category        商品类目
     * @param string $price_min       最小金额
     * @param string $price_max       最大金额
     * @param string $coupon          是否有券
     * @param string $advert_cat      活动类型
     * @param string $advert_word     关键字
     * @param string $amount_min      佣金
     * @return array
     */
    public function getTbinfo($diy_id,$back,$min_id,$parm = array())
    {
        $retData = [];
        $Resourc = [];
        $HaodanKu = new HaodankuModel();
        if ($diy_id == 1) {
            if (!empty($parm['advert_cat_id'])) {
                $cat_id = explode('，',$parm['advert_cat_id']);
                $start = ($min_id - 1) * $back;//偏移量，当前页-1乘以每页显示条数
                $cat_id = array_slice($cat_id, $start, $back);
                for ($i = 0;$i < count($cat_id); $i++) {
                    $resour = $HaodanKu->getItemDetailLocal($cat_id[$i]);
                    if ($resour['code'] == 1) {
                        $Resourc[] = $resour['data'];
                    }
                }
            }
        } elseif ($diy_id == 2) {
            if (!empty($parm['category'])) {
                $resour = $HaodanKu->getGoodsList(1,$parm['category'],$back,$min_id,0,$parm['price_min'],$parm['price_max'],'','','','','','','',$parm['coupon']);
                $this->tb_min_id = $resour['min_id'];
                if ($resour['code'] == 1) {
                    $Resourc = $resour['data'];
                }
            }
        } elseif ($diy_id == 3) {
            if (!empty($parm['advert_cat'])) {
                $resour = $HaodanKu->getFastBuyList($min_id,$parm['hour_type']);
                if ($resour['code'] == 1) {
                    $Resourc = $resour['data'];
                }
            }
        } elseif ($diy_id == 4) {
            if (!empty($parm['advert_word'])) {
                $resour = $HaodanKu->supersearch($parm['advert_word'],$back,$min_id,1,0,0,$parm['coupon'],$parm['amount_min'],$parm['price_min']);
                if ($resour['code'] == 1) {
                    $Resourc = $resour['data'];
                }
            }
        }

        if (!empty($Resourc)) {
            foreach ($Resourc as $key => $value) {
                $temp['goods_id'] = $value['itemid'];  // 商品ID
                $temp['source'] = 'tb';                           // 来源
                $temp['goods_name'] = $value['itemtitle']; // 标题
                $temp['goods_content'] = $value['itemshorttitle']; // 短标题
                $temp['sales_volume'] = $value['itemsale']; // 月销量
                $temp['img'] = $value['itempic'].'_310x310.jpg';  // 主图
                $temp['old_price'] = sprintf('%.2f',round($value['itemprice'],2)); // 在售价
                $temp['price'] = $value['itemendprice'];  // 券后价
                $temp['commission_ratio'] = $value['tkrates'];   // 佣金比率
                $temp['commission'] = $value['tkmoney'];   // 佣金
                $temp['coupon_amount'] = $value['couponmoney'];   // 优惠券金额
                $temp['couponurl'] = $value['couponurl'];   // 优惠券链接
                $retData[] = $temp;
            }
        }
        return $retData;
    }

    /**
     * 京东
     * @param $diy_id  自定义类型
     * @param $back    每页返回条数
     * @param $min_id  分页
     * @param array $parm   参数
     * @return array
     */
    public function getJdinfo($diy_id,$back,$min_id,$parm = array())
    {
        $retData = [];
        $Resourc = [];
        $Jingtt = new JingtuituiController();
        if ($diy_id == 1) {
            if (!empty($parm['advert_cat_id'])) {
                $cat_id = explode('，',$parm['advert_cat_id']);
                $start = ($min_id - 1) * $back;//偏移量，当前页-1乘以每页显示条数
                $cat_id = array_slice($cat_id, $start, $back);
                for ($i = 0;$i < count($cat_id); $i++) {
                    $resour = $Jingtt->getGoodsQuery(['skuIds' =>$cat_id[$i]]);
                    if (!empty($resour)) {
                        $Resourc[] = $resour[0];
                    }
                }
            }
        } elseif ($diy_id == 2) {
            if (!empty($parm['category'])) {
                $data['cid2'] = $parm['category'];
                $data['pageSize'] = $back;
                $data['pageIndex'] = $min_id;
                if (!empty($parm['commissionShareStart'])) {
                    $data['commissionShareStart'] = $parm['commissionShareStart'];
                }
                if (!empty($parm['commissionShareEnd'])) {
                    $data['commissionShareEnd'] = $parm['commissionShareEnd'];
                }
                if (!empty($parm['isCoupon'])) {
                    $data['isCoupon'] = $parm['isCoupon'];
                }
                if (!empty($parm['commissionShareEnd'])) {
                    $data['commissionShareEnd'] = $parm['commissionShareEnd'];
                }

                $resour = $Jingtt::getGoodsQuery($data);
                if (!empty($resour)) {
                    $Resourc = $resour;
                }
            }
        } elseif ($diy_id == 3) {
            if (!empty($parm['advert_cat'])) {
                $data['limit'] = $back;
                $data['page'] = $min_id;
                if ($parm['advert_cat'] == 2) {
                    $resour = $Jingtt->nineYuanNineSpecial($data);
                    if ($resour['return'] == 0) {
                        foreach ($resour['result']['data'] as $key => $value ) {
                            $temp['goods_id'] = $value['goods_id'];
                            $temp['source'] = 'jd';
                            $temp['goods_name'] = $value['goods_name'];
                            $temp['goods_content'] = $value['shop_name'];
                            $temp['sales_volume'] = $value['inOrderCount30Days'];
                            $temp['img'] = $value['goods_img'];
                            $temp['old_price'] = $value['goods_price'];
                            $temp['price'] = $value['coupon_price'];
                            $temp['commission_ratio'] = $value['commission'];
                            $temp['commission'] = $value['coupon_price']*$value['commission']/100;
                            $temp['coupon_amount'] = $value['discount_price'];
                            $temp['couponurl'] = $value['discount_link'];
                            $retData[] = $temp;
                        }
                    }
                } else {
                    $resour = $Jingtt->featuredGoods($data);
                    if ($resour['return'] == 0) {
                        foreach ($resour['result']['data'] as $key => $value ) {
                            $temp['goods_id'] = $value['goods_id'];
                            $temp['source'] = 'jd';
                            $temp['goods_name'] = $value['goods_name'];
                            $temp['goods_content'] = $value['shop_name'];
                            $temp['sales_volume'] = $value['inOrderCount30Days'];
                            $temp['img'] = $value['goods_img'];
                            $temp['old_price'] = $value['goods_price'];
                            $temp['price'] = $value['coupon_price'];
                            $temp['commission_ratio'] = $value['commission'];
                            $temp['commission'] = $value['coupon_price']*$value['commission']/100;
                            $temp['coupon_amount'] = $value['discount_price'];
                            $temp['couponurl'] = $value['discount_link'];
                            $retData[] = $temp;
                        }
                    }
                }
            }
        } elseif ($diy_id == 4) {
            if (!empty($parm['advert_word'])) {
                $data['pageSize'] = $back;
                $data['pageIndex'] = $min_id;
                $data['keyword'] = $parm['advert_word'];
                if (!empty($parm['commissionShareStart'])) {
                    $data['commissionShareStart'] = $parm['commissionShareStart'];
                }
                if (!empty($parm['commissionShareEnd'])) {
                    $data['commissionShareEnd'] = $parm['commissionShareEnd'];
                }
                if (!empty($parm['isCoupon'])) {
                    $data['isCoupon'] = $parm['isCoupon'];
                }
                if (!empty($parm['commissionShareEnd'])) {
                    $data['commissionShareEnd'] = $parm['commissionShareEnd'];
                }
                $resour = $Jingtt::getGoodsQuery($data);
                if (!empty($resour)) {
                    $Resourc = $resour;
                }
            }
        }
        if (!empty($Resourc)) {
            foreach ($Resourc as $key => $value) {
                $images = preg_replace('/jfs/','s300x300_jfs',$value['imageInfo']['imageList'][0]);
                $temp['goods_id'] = $value['skuId'];  // 商品ID
                $temp['source'] = 'jd';                           // 来源
                $temp['goods_name'] = $value['skuName']; // 标题
                $temp['goods_content'] = $value['categoryInfo']['cid3Name']; // 短标题
                $temp['sales_volume'] = $value['inOrderCount30Days']; // 月销量
                $temp['img'] = $images['url'];  // 主图
                $temp['old_price'] = $value['priceInfo']['price']; // 在售价
                $temp['price'] = $value['priceInfo']['lowestCouponPrice'];  // 券后价
                $temp['commission_ratio'] = $value['commissionInfo']['commissionShare'];   // 佣金比率
                if (isset($value['priceInfo']['lowestCouponPrice'])) {
                    $temp['commission'] = $value['commissionInfo']['couponCommission'];   // 佣金
                } else {
                    $temp['commission'] = $value['commissionInfo']['commission'];   // 佣金
                }
                $temp['coupon_amount'] = $value['couponInfo']['couponList'][0]['discount'];   // 优惠券金额
                $temp['couponurl'] = $value['couponInfo']['couponList'][0]['link'];   // 优惠券链接
                $retData[] = $temp;
            }
        }

        return $retData;
    }

    /**
     * 拼多多
     * @param $diy_id
     * @param $back
     * @param $min_id
     * @param array $parm
     * @return array
     */
    public function getPddinfo($diy_id,$back,$min_id,$parm = array())
    {
        $retData = [];
        $Resourc = [];
        Vendor('pdd.pdd', '', '.class.php');
        $pdd = new \pdd();
        if ($diy_id == 1) {
            if (!empty($parm['advert_cat_id'])) {
                $cat_id = explode('，',$parm['advert_cat_id']);
                $start = ($min_id - 1) * $back;//偏移量，当前页-1乘以每页显示条数
                $cat_id = array_slice($cat_id, $start, $back);
                for ($i = 0;$i < count($cat_id); $i++) {
                    $advert_cat_id = "[$cat_id[$i]]";
                    $resour = $pdd->getGoodsDetail($advert_cat_id);
                    if ($resour['code'] == 0) {
                        $Resourc[] = $resour['data']['goods_details'];
                    }
                }

            }
        } elseif ($diy_id == 2) {
            if (!empty($parm['category'])) {
                $resour = $pdd->searchGoods('','',$min_id,$back,0,true,'',$parm['category']);
                if ($resour['code'] == 0) {
                    $Resourc = $resour['data']['list'];
                }
            }
        } elseif ($diy_id == 3) {
           // 暂时没有活动
        } elseif ($diy_id == 4) {
            if (!empty($parm['advert_word'])) {
                $resour = $pdd->searchGoods($parm['advert_word'],'',$min_id,$back,0,true);
                if ($resour['code'] == 0) {
                    $Resourc = $resour['data']['list'];
                }
            }
        }
        if (!empty($Resourc)) {
            foreach ($Resourc as $key => $value) {
                $temp['goods_id'] = $value['goods_id'];  // 商品ID
                $temp['source'] = 'pdd';               // 来源
                $temp['goods_name'] = $value['goods_name']; // 标题
                $temp['goods_content'] = $value['category_name']; // 短标题
                $temp['sales_volume'] = $value['sales_tip']; // 月销量
                $temp['img'] = $value['goods_thumbnail_url'];  // 主图
                $temp['old_price'] = $value['min_normal_price']/100; // 在售价
                $temp['price'] = substr(sprintf("%.3f", ($value['min_group_price']-$value['coupon_discount'])/100), 0, -1);  // 券后价
                $temp['commission_ratio'] =sprintf("%.2f", $value['promotion_rate']/1000*100);   // 佣金比率
                $temp['commission'] = $temp['couponprice']*$temp['commission_ratio']/100;   // 佣金
                $temp['coupon_amount'] = $value['coupon_discount']/100;   // 优惠券金额
                $temp['couponurl'] ='';   // 优惠券链接
                $retData[] = $temp;
            }
        }

        return $retData;
    }

    /**
     * 自营
     * @param $diy_id
     * @param $back
     * @param $min_id
     * @param array $parm
     * @return array
     */
    public function getSelfinfo($diy_id,$back,$min_id,$parm = array())
    {
        $retData = [];
        $Resourc = [];
        $Goods = new \Common\Model\GoodsModel();
        if ($diy_id == 1) {
            if (!empty($parm['advert_cat_id'])) {
                $cat_id = explode('，',$parm['advert_cat_id']);
                $start = ($min_id - 1) * $back;//偏移量，当前页-1乘以每页显示条数
                $cat_id = array_slice($cat_id, $start, $back);
                for ($i = 0;$i < count($cat_id); $i++) {
                    $resour = $Goods->getGoodsDetail($cat_id[$i]);
                    if (!empty($resour)) {
                        $Resourc[] = $resour;
                    }
                }
            }
        } elseif ($diy_id == 2) {
            if (!empty($parm['category'])) {
                $resour = $Goods->getListByLimit($parm['category'],'desc',$min_id,$back);
                if (!empty($resour)) {
                    $Resourc = $resour;
                }

            }
        }
        if (!empty($Resourc)) {
            $preg = "/^http(s)?:\\/\\/.+/";
            foreach ($Resourc as $key => $value) {
                if(preg_match($preg,$value['tmp_img'])){
                    $images = $value['tmp_img'];
                } else {
                    $images = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$value['tmp_img'];
                }
                $temp['goods_id'] = $value['goods_id'];  // 商品ID
                $temp['source'] = 'self';               // 来源
                $temp['goods_name'] = $value['goods_name']; // 标题
                $temp['goods_content'] = ''; // 短标题
                $temp['sales_volume'] = $value['virtual_volume']+$value['sales_volume']; // 月销量
                $temp['img'] = $images;  // 主图
                $temp['price'] = $value['price']; // 在售价
                $temp['old_price'] = $value['old_price'];  // 券后价
                $temp['commission_ratio'] = '';   // 佣金比率
                $temp['commission'] = '';   // 佣金
                $temp['coupon_amount'] = '';   // 优惠券金额
                $temp['couponurl'] ='';   // 优惠券链接
                $retData[] = $temp;
            }
        }

        return $retData;
    }

}
