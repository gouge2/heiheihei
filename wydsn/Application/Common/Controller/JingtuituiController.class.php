<?php
/**
 * 京推推接口管理类
 * 
 */
namespace Common\Controller;
use Think\Controller;


class JingtuituiController extends Controller
{
	protected static $appid  	= JINGTUITUI_APPID;
	protected static $appkey 	= JINGTUITUI_APPKEY;

	public static $url    		= 'http://japi.jingtuitui.com/api/';
	public static $limit  		= 10;
	public static $page    		= 1;


	/**
	 * 获取京东单个商品信息(内容没有)
	 */
	public static function getGoodsInfo($gid, $ware = false)
	{
		$list   = [];
		$method = $ware ? self::$url .'get_ware_style' : self::$url .'get_goods_info';
		$param 	= ['gid' => $gid, 'appid' => self::$appid, 'appkey' => self::$appkey];

		$res 	= json_decode(https_request($method, $param), true);         // curl获取商品信息

		if ($res && $res['return'] == 0 && $res['result']) {
			$list = $res['result'];
		}

		return $list;
	}

	/**
	 * 获取京东商品列表信息
	 */
	public static function getGoodsList($whe)
	{	
		$list   = [];
		$method = self::$url .'get_goods_list';

		$param 	= array_merge([
			'appid' 	=> self::$appid, 
			'appkey' 	=> self::$appkey,
			'page' 		=> self::$page,
			'num' 		=> self::$limit,
		], $whe);

		// 显示条数 最低10 最高100
		if (isset($whe['limit'])) {
			unset($param['limit']);
			$param['num'] 	= $whe['limit'];
		}

		// 显示页数 最高50页
		if (isset($whe['page'])) {
			$param['page'] 	= $whe['page'];
		}

		// 有goods_id只拿一条数据
		if (isset($whe['goods_id'])) {
			unset($param['goods_id']);
			$param['so'] 	= $whe['goods_id'];
		}

		$res 	= json_decode(https_request($method, $param), true);         // curl获取商品信息

		if ($res && $res['return'] == 0 && isset($res['result']['data']) && is_array($res['result']['data'])) {
			if (isset($whe['goods_id'])) {
				foreach ($res['result']['data'] as $key => $val) {
					if ($whe['goods_id'] == $val['goods_id']) {
						$list = $val;
						break;
					}
				}
			} else {
				$list = $res['result']['data'];
			}
		}

		return $list;
	}

	/**
	 * 联盟查询京东商品信息
	 */
	public static function getGoodsQuery($whe)
	{	
		$list   = [];
		$method = self::$url .'jd_goods_query';

		$param 	= array_merge([
			'appid' 	=> self::$appid, 
			'appkey' 	=> self::$appkey,
			'pageSize' 	=> self::$limit,
		], $whe);

		// 显示条数 默认20 最高30
		if (isset($whe['limit'])) {
			unset($param['limit']);
			$param['page'] 	= $whe['limit'];
		}

		// 有goods_id只拿一条数据
		if (isset($whe['goods_id'])) {
			unset($param['goods_id']);
		}

		$res 	= json_decode(https_request($method, $param), true);         // curl获取商品信息


		if ($res && $res['return'] == 0 && isset($res['result']['goods']) && is_array($res['result']['goods'])) {
			$list = $res['result']['goods'];
		}

		return $list;
	}

	/**
	 * 查询京东商品详情信息
	 */
	public static function getGoodsDet($gid)
	{	
		$list 	= [];

		if ($gid) {
			// 基础商品查询
			$goods 	= self::getGoodsInfo($gid);

			if ($goods && isset($goods['goodsName']) && isset($goods['shopId'])) {
				// 联盟查询
				$res = self::getGoodsQuery(['keyword' => $goods['goodsName'], 'shopid' => $goods['shopId']]);
				
				if ($res) {
					foreach ($res as $key => $val) {
						if ($gid == $val['skuId']) {
							$list = array_merge($goods, $val);
						}
					}
				}
			}
		}

		return $list;
	}

    /**
     * 获取京东转链
     * @param $gid
     * @param $coupon_link
     * @param int $position_id
     * @return array|mixed
     */
	public static function getGoodsCouponLink($gid, $coupon_link, $position_id=0) 
	{
        $link   = [];
        $method = self::$url .'get_goods_link';
        $param 	= [
            'gid'        => $gid,
            'appid'      => self::$appid,
            'appkey'     => self::$appkey,
            'unionid'    => JD_UNIONID,
            'coupon_url' => $coupon_link
		];
		
        if ($position_id) {
            $param['positionid'] = $position_id;
        }

        $res 	= json_decode(https_request($method, $param), true);         // curl获取商品信息

        if ($res && $res['return'] == 0 && $res['result']) {
            $link = $res['result']['link'];
        }

        return $link;
    }

    public function create_pid($uid){
        $keyurl = "http://api.josapi.net/createpid?unionId=".JD_UNIONID."&key=".JD_AUTH_KEY."&type=4&spaceNameList=pid_for_{$uid}";
        $pidinfo = file_get_contents($keyurl);
        $result = json_decode($pidinfo,true);
        $jd_pid = "";
        writeLog("京东推广位id：".$pidinfo);
        if($result['error']=="0")
        {
            $jd_idx = "pid_for_{$uid}";
            $jd_pid = $result['data']['resultList'][$jd_idx];
            $User=new \Common\Model\UserModel();
            $data=array(
                'jd_pid'=>$jd_pid
            );
            $User->where("uid=".$uid)->save($data);
        }
        return $jd_pid;
    }

	/**
	 * 处理成简洁的数据列表
	 */
	public static function jdConciseList($jd_gid, $fee_user, $group_id, $res_tag = false)
	{ 	
		$s_jd    	= S('jd_data');    		
		$jd_data    = $s_jd ? $s_jd : [];    // 读取缓存有没有相应的数据
		$new_data   = [];
		$jd_sw      = false;

        // 主播佣金
        $HostTreatModel = new \Common\Model\HostTreatModel();
        $hostDetail = $HostTreatModel->getHostCommission($jd_gid, 'jd', $group_id, '');
        $hostCommission = $hostDetail['commission'];
		
		if ($jd_gid) {
			foreach ($jd_gid as $key => $val) {
				if (!isset($jd_data[$val])) {      // 未缓存京东商品信息  去获取
					$res       	= self::getGoodsDet($val);

					// 佣金基数
					$commission    = $res['priceInfo']['lowestCouponPrice'] * $res['commissionInfo']['commissionShare'] / 100;
					$coupon_amount = isset($res['couponInfo']['couponList'][0]) ? $res['couponInfo']['couponList'][0]['discount'] : 0;

					if ($res) {
						// 价格处理
						$price 	   = substr(sprintf("%.3f", $res['priceInfo']['lowestCouponPrice']), 0, -1);
						$old_price = substr(sprintf("%.3f", $res['priceInfo']['lowestPrice']), 0, -1);
						if ($price == 0 && $old_price > 0) {
							$price = $old_price;
						}

						$jd_data[$val] = $new_data[] = [
							'goods_id'      	=> $val,
							'goods_url'     	=> $res['materialUrl'],
							'goods_name'    	=> $res['goodsName'],
							'img'           	=> $res['imgUrl'],
							'price'         	=> $price,
							'old_price'     	=> $old_price,
							'sales_volume'  	=> (string)$res['inOrderCount30Days'],
							'from'          	=> 'jd',
							'coupon_amount' 	=> (string)$coupon_amount,
                            'commission'   		=> substr(sprintf("%.3f", ($commission *  $fee_user / 100)), 0, -1),
                            'commission_base'   => $commission,
                            'commission_host'   => substr(sprintf("%.3f", ($commission *  $hostCommission)), 0, -1),
						];

                        // 如果已经添加到商品夹，走主播的分佣
                        if ($hostCommission['userCommission'] && in_array($val, $hostCommission['goodsList'])) {
                            $jd_data[$val]['commission'] = substr(sprintf("%.3f", ($commission *  $hostCommission['userCommission'])), 0, -1);
                        }

						$jd_sw = true;
					}
				} else {
					// 佣金换算
					$jd_data[$val]['commission'] = substr(sprintf("%.3f", ($jd_data[$val]['commission_base'] *  $fee_user / 100)), 0, -1);
					$jd_data[$val]['commission_host'] = substr(sprintf("%.3f", ($jd_data[$val]['commission_base'] *  $hostCommission)), 0, -1);

                    // 如果已经添加到商品夹，走主播的分佣
                    if ($hostCommission['userCommission'] && in_array($val, $hostCommission['goodsList'])) {
                        $jd_data[$val]['commission'] = substr(sprintf("%.3f", ($jd_data[$val]['commission_base'] *  $hostCommission['userCommission'])), 0, -1);
                    }

					if ($res_tag) {
						$new_data[] = $jd_data[$val];
					}
				}
			}
		}

		if ($jd_sw) {
			S('jd_data', $jd_data, 86400);  // 保存缓存1天  避免重复调用接口
		}

		return $res_tag ? $new_data : $jd_data;
	}

    /**
     * 超级分类
     * @param $cid 京推推商品一级类目： 0全部；1居家日用；2食品；3生鲜；4图书；5美妆个护；6母婴；7数码家电；8内衣；9配饰；10女装；11男装；12鞋品；13家装家纺；14文娱车品；15箱包；16户外运动（支持多类目筛选，如1,2获取类目为女装、男装的商品，逗号仅限英文逗号）
     * @return mixed
     */
	public function superClassification($cid) {
        $method = self::$url .'get_super_category';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'version' => 'v1',
        ];
        if ($cid) {
            $param['cid'] = $cid;
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 各大榜单
     * @param $whe 分页参数
     * @param $type 商品类型
     * @return mixed
     */
    public function majorLists($whe, $type) {
        $method = self::$url .'today_top';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'page' 		=> self::$page,
            'num' 		=> self::$limit,
        ];
        if ($type) {
            $param['goods_new_type'] = $type;
        }
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 智能转链
     * @param $content 文案
     * @return mixed
     */
    public function smartChain($content) {
        $method = self::$url .'universal';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'version' => 'v1',
            'unionid'=> JD_UNIONID
        ];

        if ($content) {
            $param['content'] = $content;
        }
        return json_decode(https_request($method, $param), true);
    }


    /**
     * 精选好货
     * @param $whe 分页参数
     * @return mixed
     */
    public function featuredGoods($whe) {
        $method = self::$url .'get_goods_list_sift';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'page' 		=> self::$page,
            'num' 		=> self::$limit,
        ];
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 品牌库
     * @param $whe 分页参数
     * @return mixed
     */
    public function brandLibrary($whe) {
        $method = self::$url .'get_brand_list';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'version' => 'v1',
            'page' => self::$page,
            'num' => self::$limit,
        ];
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 京东配送商品
     * @param $whe 分页参数
     * @return mixed
     */
    public function jingdongDeliveryGoods($whe) {
        $method = self::$url .'get_goods_list_collage';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'page' => self::$page,
            'num' => self::$limit,
        ];
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 9块9专场
     * @param $whe 分页参数
     * @return mixed
     */
    public function nineYuanNineSpecial($whe) {
        $method = self::$url .'get_price_9_9';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'page' => self::$page,
            'num' => self::$limit,
        ];
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     *  京东自营
     * @param $whe 分页参数
     * @return mixed
     */
    public function jingdongSelfOperated($whe) {
        $method = self::$url .'jd_self_operated';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'page' => self::$page,
            'num' => self::$limit,
        ];
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     *  京东秒杀
     * @param $whe 分页参数
     * @param $h 小时
     * @return mixed
     */
    public function jingdongSpike($whe,$h) {
        $method = self::$url .'sekill';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'version' => 'v3.0',
            'page' => self::$page,
            'num' => self::$limit,
            'h' => $h
        ];
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 奖励商品
     * @param $whe 分页参数
     * @param $type 商品类型
     * @return mixed
     */
    public function rewardGoods($whe, $type) {
        $method = self::$url .'subsidy_goods';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'version' => 'v3.0',
            'page' => self::$page,
            'num' => self::$limit,
        ];
        if ($type) {
            $param['goods_new_type'] = $type;
        }
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }

    /**
     * 商品更新
     * @param $whe 分页参数
     * @return mixed
     */
    public function productUpdate($whe) {
        $method = self::$url .'get_goods_update';
        $param 	= [
            'appid' => self::$appid,
            'appkey' => self::$appkey,
            'page' => self::$page,
            'num' => self::$limit,
        ];
        if ($type) {
            $param['goods_new_type'] = $type;
        }
        // 显示条数 最低10 最高100
        if (isset($whe['limit'])) {
            unset($param['limit']);
            $param['num'] 	= $whe['limit'];
        }

        // 显示页数 最高50页
        if (isset($whe['page'])) {
            $param['page'] 	= $whe['page'];
        }
        return json_decode(https_request($method, $param), true);
    }
}
?>