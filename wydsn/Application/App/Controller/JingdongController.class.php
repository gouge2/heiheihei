<?php
/**
 * by 翠花 http://http://livedd.com
 * 京东商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Controller\JingtuituiController;
use Common\Model\HostTreatModel;
use Common\Model\JingdongCollectModel;
use Common\Model\UserModel;

class JingdongController extends AuthController
{
	/**
	 * 获取顶级京东商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getCatList()
	{
		Vendor('JingDong.JingDong','','.class.php');
		$JindDong=new \JindDong();
		$res=$JindDong->searchGoodsCategory($parent_id=0,$grade=0);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取爆款商品列表
	 * @param string $token:用户身份令牌
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param string $cid3:三级类目
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getTopGoodsList()
	{
		//第几页
		if(trim(I('post.page')))
		{
			$page=trim(I('post.page'));
		}else {
			$page=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		Vendor('JingDong.JingDong','','.class.php');
		$JindDong=new \JindDong();
		$from=($page-1)*$page_size;
		$res=$JindDong->queryExplosiveGoods($from,$page_size,$cid3='');
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 关键词查询选品
	 * @param string $token:用户身份令牌
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param number $cat1Id:一级类目
	 * @param number $cat2Id:二级类目
	 * @param number $cat3Id:三级类目
	 * @param string $keyword:关键词
	 * @param string $sort_name:排序字段[pcPrice pc价],[pcCommission pc佣金],[pcCommissionShare pc佣金比例],[inOrderCount30Days 30天引入订单量],[inOrderComm30Days 30天支出佣金]
	 * @param string $sort:	asc,desc升降序,默认降序
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getGoodsList()
	{
		//第几页
		if(trim(I('post.page')))
		{
			$page=trim(I('post.page'));
		}else {
			$page=1;
		}
		//页大小
		if(trim(I('post.page_size')))
		{
			$page_size=trim(I('post.page_size'));
		}else {
			$page_size=10;
		}
		if(trim(I('post.cat1Id')))
		{
			$cat1Id=trim(I('post.cat1Id'));
		}
		if(trim(I('post.cat2Id')))
		{
			$cat2Id=trim(I('post.cat2Id'));
		}
		if(trim(I('post.cat3Id')))
		{
			$cat3Id=trim(I('post.cat3Id'));
		}
		//关键字
		if(trim(I('post.keyword')))
		{
			$keyword=trim(I('post.keyword'));
		}
		//排序规则
		if(trim(I('post.sort_name')))
		{
			$sort_name=trim(I('post.sort_name'));
		}
		if(trim(I('post.sort')))
		{
			$sort=trim(I('post.sort'));
		}else {
			$sort='desc';
		}
		Vendor('JingDong.JingDong','','.class.php');
		$JindDong=new \JindDong();
		$res=$JindDong->searchGoods($cat1Id,$cat2Id,$cat3Id,$keyword,$page,$page_size,$sort_name,$sort);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}


	/**
	 * 京东商品新的列表
	 */
	public function getNewList($types = '')
	{
		$token     		= trim(I('post.token'));
		$rank     		= trim(I('post.rank'));
		$so     		= trim(I('post.so'));
		$brand_id     	= trim(I('post.brand_id'));
		$price_start    = trim(I('post.price_start'));
		$price_end    	= trim(I('post.price_end'));
		$goods_new_type = trim(I('post.goods_new_type'));
        $type      		= I('post.type/d');

        if (empty($types)) {
            $limit = I('post.limit/d');
            $page = I('post.page/d');
        } else {
            $limit = I('post.back/d');
            $page = I('post.min_id/d');
        }

		if (IS_POST) {
			$Jingtuitui = new JingtuituiController();
			$whe        = [];

			if ($rank) {
				$whe['rank'] 		= $rank;
			}

			if ($limit) {
				$whe['limit'] 		= $limit;
			}

			if ($page) {
				$whe['page'] 		= $page;
			}

			if ($so) {
				$whe['so'] 			= $so;
			}

			if ($brand_id) {
				$whe['brand_id'] 	= $brand_id;
			}

			if ($price_start) {
				$whe['price_start'] = $price_start;
			}

			if ($price_end) {
				$whe['price_end'] 	= $price_end;
			}

			if ($goods_new_type) {
				$whe['goods_new_type'] = $goods_new_type;
			}

            if (empty($rank)) {
                if ($goods_new_type) {
                    $whe['cid1'] = $goods_new_type;
                }
                if ($so) {
                    $whe['keyword'] = $so;
                }
                if ($limit) {
                    $whe['pageSize'] = $limit;
                }
                if ($page) {
                    $whe['pageIndex'] = $page;
                }
                if ($price_start) {
                    $whe['pingouPriceStart'] = $price_start;
                }
                if ($price_end){
                    $whe['pingouPriceEnd'] = $price_end;
                }
                $res = $Jingtuitui::getGoodsQuery($whe);
                foreach ($res as $key => $value) {
                    $list[$key]['goods_id'] = $value['skuId'];
                    $list[$key]['goods_link'] = $value['materialUrl'];
                    $list[$key]['goods_img'] = $value['imageInfo']['imageList'][0]['url'];
                    $list[$key]['goods_name'] = $value['skuName'];
                    $list[$key]['goods_content'] = $value['skuName'];
                    $list[$key]['goods_price'] = $value['priceInfo']['lowestPrice'];
                    $list[$key]['coupon_price'] = $value['priceInfo']['lowestCouponPrice'];
                    $list[$key]['inOrderCount30Days'] = $value['inOrderCount30Days'];
                    $list[$key]['discount_price'] = $value['couponInfo']['couponList'][0]['discount'] ?? 0;
                    $list[$key]['commission'] = $value['commissionInfo']['commissionShare'];
                }

            } else {
                $list = $Jingtuitui::getGoodsList($whe);
            }

            $data = [];

			if ($list) {
				// 获取用户佣金比例
				$User 				= new UserModel();
				$fee_user 			= $User->getUserFeeRatio($token, $group_id);
				$uid        		= $User->getUserId($token);

				// 主播佣金
                $HostTreatModel 	= new HostTreatModel();
                $hostDetail 		= $HostTreatModel->getHostCommission($list, 'jd', $group_id, 'goods_id');
				$hostCommission 	= $hostDetail['commission'];
				
				// 循环组装条件
				$gid_arr 			= [];
				foreach ($list as $val) {
					$gid_arr[] 		= $val['goods_id'];
				}

				// 收藏列表
				$gc_arr  			= [];
				$JingdongCollect 	= new JingdongCollectModel();
				if ($gid_arr) {
					$gc_arr         = $JingdongCollect->is_collect($gid_arr, $uid);
				}
				
				foreach ($list as $val) {
					$temp = [
						'goods_id' 		=> $val['goods_id'],
						'goods_url' 	=> $val['goods_link'],
						'img' 			=> $val['goods_img'],
						'goods_name' 	=> $val['goods_name'],
						'goods_content' => $val['goods_content'],
						'price' 		=> substr(sprintf("%.3f", $val['coupon_price']), 0, -1),
						'old_price' 	=> substr(sprintf("%.3f", $val['goods_price']), 0, -1),
						'sales_volume' 	=> $val['inOrderCount30Days'],
						'coupon_amount' => (string)$val['discount_price'],
					];

					// 佣金基数
					$commission_base 		= $val['coupon_price'] * $val['commission'] / 100;
					$temp['commission'] 	= sprintf("%.2f", ($commission_base *  $fee_user / 100));
					$temp['commission_host']= sprintf("%.2f", ($commission_base * $hostCommission));

                    $is_has=0;

                    #效验是否存在商品列表
                    $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                    $goodsItem = $shortLiveModel->getOne(['from'=>'jd','goods_id'=>$val['goods_id']]);
                    $is_has = 0;
                    if($goodsItem)
                    {
                        $is_has = 1;
                    }
                    // 如果已经添加到商品夹，走主播的分佣
                    if ($hostCommission['userCommission'] && in_array($val['goods_id'], $hostCommission['goodsList'])) {
                        $temp['commission'] = sprintf("%.2f", ($commission_base *  $hostCommission['userCommission']));
                    }
                    #新版佣金获取
                    $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base);
                    $temp['commission'] = $userCommission['userHasCommission'];
                    $temp['commission_host'] = $userCommission['hostUserCommission'];
					// 商品收藏标识
					$temp['is_collect'] 	= isset($gc_arr[$val['goods_id']]) ? 'Y' : 'N';

					$data[] 				= $temp;
				}
			}
            if (empty($types)) {
                $this->ajaxSuccess(['list' => $data]);
            } else {
                return $data;
            }

        }

		$this->ajaxError();
	}

	/**
	 * 京东商品详情
	 */
	public function getGoodsDetail()
	{
		$token     					= trim(I('post.token'));
		$goods_id      				= trim(I('post.goods_id'));

		if ($goods_id) {
			$Jingtuitui = new JingtuituiController();
			$det       				= $Jingtuitui::getGoodsDet($goods_id);
			$ware       			= $Jingtuitui::getGoodsInfo($goods_id, true);
			$detail       			= [];
            $User 		= new UserModel();
			$uid = $User->getUserId($token);
			if ($det) {
				$JingdongCollect 	= new JingdongCollectModel();
				// 获取用户佣金比例
				$fee_high   = $User->getUserFeeRatio('', $group_id, 'high');
				$fee_user 	= $User->getUserFeeRatio($token, $group_id);
                #最高等级佣金比例
                $UserGroup=new \Common\Model\UserGroupModel();
                $groupVipList=$UserGroup->getGroupList();
                $groupVipMsg=end($groupVipList);
				
                // 主播佣金
                $HostTreatModel = new HostTreatModel();
                $hostDetail = $HostTreatModel->getHostCommission(array($det), 'jd', $group_id, 'goods_id');
                $hostCommission = $hostDetail['commission'];

				$detail['slideshow'] 		= []; // 商品轮播
				if (isset($det['imageInfo']['imageList'])) {
					foreach ($det['imageInfo']['imageList'] as $key => $val) {
						if (isset($val['url'])) {
							$detail['slideshow'][] = $val['url'];
						}
					}
				}

				$detail['old_price']  		= isset($det['priceInfo']['lowestPrice']) ? substr(sprintf("%.3f", $det['priceInfo']['lowestPrice']), 0, -1) : 0; // 原价
				$detail['price']  			= isset($det['priceInfo']['lowestCouponPrice']) ? substr(sprintf("%.3f", $det['priceInfo']['lowestCouponPrice']), 0, -1) : 0; // 券后价
				if ($detail['price'] == 0 && $detail['old_price'] > 0) {
					$detail['price'] = $detail['old_price'];
				}

				$detail['sales_volume']  	= $det['inOrderCount30Days']; //销量
				$detail['goods_name']  		= $det['goodsName']; //商品名称
				$detail['coupon_amount']  	= isset($det['couponInfo']['couponList'][0]) ? $det['couponInfo']['couponList'][0]['discount'] : 0; //优惠劵
				$detail['coupon_start']   	=  isset($det['couponInfo']['couponList'][0]) ? $det['couponInfo']['couponList'][0]['getEndTime'] : 0;//优惠卷开始时间,
				$detail['coupon_end']     	=  isset($det['couponInfo']['couponList'][0]) ? $det['couponInfo']['couponList'][0]['getStartTime'] : 0;//优惠卷结束时间
				$detail['coupon_link']    	=  isset($det['couponInfo']['couponList'][0]) ? $det['couponInfo']['couponList'][0]['link'] : 0;//优惠劵链接

				// 商品图文详情
				$match = [];
				if ($ware) {
					preg_match_all('/<img.*?data-lazyload="(.*?)"[^>]*>/i', $ware, $match);
				}
				$detail['ware_style']       = isset($match[1]) ? $match[1] : [];

				// 佣金基数
				$commission_base 			= $det['priceInfo']['lowestCouponPrice'] * $det['commissionInfo']['commissionShare'] / 100;
				$detail['commission'] 		= substr(sprintf("%.3f", ($commission_base *  $fee_user / 100)), 0, -1);
				$detail['commission_high'] 	= substr(sprintf("%.3f", ($commission_base *  $fee_high / 100)), 0, -1);
                $detail['commission_host'] 	= substr(sprintf("%.3f", ($commission_base * $hostCommission)), 0, -1);

                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'jd','goods_id'=>$goods_id]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                // 如果已经添加到商品夹，走主播的分佣
                if ($hostCommission['userCommission'] && in_array($val['goods_id'], $hostCommission['goodsList'])) {
                    $detail['commission'] 	= substr(sprintf("%.3f", ($commission_base *  $hostCommission['userCommission'])), 0, -1);
                }
                #新版佣金获取
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission_base,$groupVipMsg['id']);
                $detail['commission'] = $userCommission['userHasCommission'];
                $detail['commission_host'] = $userCommission['hostUserCommission'];
                $detail['commission_vip'] = $detail['commission_high'] = $userCommission['vipHasCommission'];

				// 收藏标识
				$detail['is_collect']		= $JingdongCollect->is_collect($goods_id, $uid);
            }

			$pid = $User->getJdPid($token);
			if(!$pid){
                $pid = $Jingtuitui->create_pid($uid);
            }
            $detail['coupon_link'] = $Jingtuitui::getGoodsCouponLink($goods_id, $detail['coupon_link'], $pid);

			$this->ajaxSuccess(['detail' => $detail]);
		}

		$this->ajaxError();
	}

    /**
     * 智能转链
     */
    public function jdSmartChain() {
        $content = trim(I('post.content'));
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->smartChain($content);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 精选好货
     */
    public function jdFeaturedGoods() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->featuredGoods($data);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 品牌库
     */
    public function jdBrandLibrary() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->brandLibrary($data);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 京东配送商品
     */
    public function jdDeliveryGoods() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->jingdongDeliveryGoods($data);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 京东配送商品
     */
    public function jdNineYuanNineSpecial() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->nineYuanNineSpecial($data);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 京东自营
     */
    public function jdSelfOperated() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->jingdongSelfOperated($data);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 京东秒杀
     */
    public function jdSpike() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $h = trim(I('post.h'));
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->jingdongSpike($data, $h);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 商品奖励
     */
    public function jdRewardGoods() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $type = trim(I('post.goods_new_type'));
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->rewardGoods($data, $type);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 商品更新
     */
    public function jdProductUpdate() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->productUpdate($data);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 超级分类
     */
    public function jdSuperClassification() {
        $cid = trim(I('post.cid'));
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->superClassification($cid);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']['data']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

    /**
     * 各大榜单
     */
    public function jdMajorLists() {
        $data['page'] = I('post.page', self::$page);
        $data['limit'] = I('post.limit', self::$limit);
        $type = trim(I('post.goods_new_type'));
        $Jingtuitui = new JingtuituiController();
        $res = $Jingtuitui->majorLists($data, $type);
        if ($res['return'] == 0) {
            $this->ajaxSuccess($res['result']);
        } else {
            $this->ajaxError($res['return'], $res['result']);
        }
    }

}