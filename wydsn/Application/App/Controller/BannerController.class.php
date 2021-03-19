<?php
/**
 * by 翠花 www.lailu.shop
 *  Banner/广告管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BannerController extends AuthController
{
	/**
	 * 获取Banner/广告图列表
	 * @param int $cat_id:Banner/广告分类ID
	 * @param int $agent_id:代理商ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:Banner/广告图列表
	 */
    public function getBannerList()
    {
        if(!trim(I('post.cat_id'))){
            //参数不正确，参数缺失
            $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_ERROR'],$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]);
        }

        $cat_id = trim(I('post.cat_id'));
        $agent_id = 0;
        if (trim(I('post.agent_id'))) {
            $agent_id = trim(I('post.agent_id'));
        }

        $index = $cat_id.'_'. $agent_id;
        $BannerList = S($index);

        if (empty($BannerList)) {
            $Banner = new \Common\Model\BannerModel();
            $list = $Banner->getBannerList($cat_id, 'Y', $agent_id);
            if (empty($list)) {
                //数据库错误
                $this->ajaxSuccess(['list' => []]);
//                $this->ajaxError(
//                    $this->ERROR_CODE_COMMON['DB_ERROR'],
//                    $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
//                );
            }

            $list = json_encode($list);

            S($index,$list,1200);
            $BannerList = $list;
        }
        $list = ["list" => json_decode($BannerList,true)];

        $this->ajaxSuccess($list);
    }
	
	/**
	 * 获取Banner/广告图信息
	 * @param int $id:Banner/广告ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->bannerMsg:Banner/广告图信息
	 */
	public function getBannerMsg()
	{
		if(trim(I('post.id')))
		{
			$id=trim(I('post.id'));
			$Banner=new \Common\Model\BannerModel();
			$bannerMsg=$Banner->getBannerMsg($id);
			if($bannerMsg!==false)
			{
				$data=array(
						'bannerMsg'=>$bannerMsg
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}else {
				//数据库错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取广告位详细信息
	 */
	public function getAppletDet()
	{
		$token     	= trim(I('post.token'));
		$banner_id 	= I('post.banner_id/d');


		// 返回的数据
		$data 		= [
			'pattern'	=> 'goods',				// goods商品模式  activity活动模式
			'title'		=> '饮料类目',			// 广告标题
			'main_img' 	=> "https://img14.360buyimg.com/pop/jfs/t1/122241/2/10169/209164/5f3f2b5aE472e98e9/b2287e3e249d2883.jpg",		
		];

		$g_det 		= [							// 商品模式 默认信息
			'bg_color'			=> '#87C0FE',
			'name_color'		=> '#333333',
			'price_color'		=> '#EE1B4A',
			'button_color'		=> '#EE1B4A',
			'button_font_color'	=> '#FFFFFF',
			'goods_list'		=> [
				[
					"goods_id"      => "10022090282269",
					"goods_url"     => "http://item.jd.com/10022090282269.html?rid=10109",
					"goods_name"    => "【超值抢购】海南正宗椰奶果味饮料椰子汁245ml*15瓶",
					"img"           => "http://img.jingtuitui.com/2271d202010141708061637.jpg",
					"price"         => "28.50",
					"old_price"     => "33.50",
					"sales_volume"  => "16",
					"commission"    => "5.19",
					"coupon_amount" => "5",
					"from"          => "jd",
				]
			]
		];

		$a_det 			= [							// 活动模式 默认信息
			'introduce' 		=> '<p>
										【介绍】包个白处今即自达持目教治省水克意县阶再称上花增的严基文七华清型者民系体活都水口&nbsp;
									</p>
									<p>
										1.求段米行线南置可亲什场各那图争叫商何级把更表好提与。一例分下员低以。
									</p>
									<p>
										&nbsp;2.求段米行线南置可亲什场各那图争叫商何级把更表好提与。一例分下员低以。
									</p>
									<p>
										<br/>
									</p>',
			'copywriter' 		=> '<p>
										<span>【超级品牌日】包个白处今即自达持目教治省水克意县阶再称上花增的严基文七华清型者民系体活都水口&nbsp;</span>
									</p>
									<p>
										<span>1.求段米行线南置可亲什场各那图争叫商何级把更表好提与。一例分下员低以。&nbsp;</span>
									</p>
									<p>
										<span>2.求段米行线南置可亲什场各那图争叫商何级把更表好提与。一例分下员低以。</span>
									</p>
									<p>
										<br/>
									</p>',
			'share_img' 		=> "http://img30.360buyimg.com/sku/jfs/t1/137248/32/1279/707061/5ed76a00E000062da/c82b0d51f1353141.jpg",
		];

		$data['g_det']  = $g_det;	
		$data['a_det']  = $a_det;	


		//// 有广告位ID处理
		if ($banner_id) {
			$User      			= new \Common\Model\UserModel();
			$ShortLiveGoods 	= new \Common\Model\ShortLiveGoodsModel();
			$Banner 			= new \Common\Model\BannerModel();

			$Jingtuitui 		= new \Common\Controller\JingtuituiController();

			// 获取广告位信息
			$msg				= $Banner->getBannerMsg($banner_id);

			if ($msg && $msg['text']) {
				$temp 							= json_decode($msg['text'], true);
				unset($msg['text']);
				$msg  							= array_merge($msg, $temp);

				// 处理数据
				$data['pattern']				= $msg['pattern'];
				$data['title']					= $msg['title'];
				$data['main_img']				= $msg['img'] ? WEB_URL . $msg['img'] : '';

				// 模式数据
				if ($msg['pattern'] == 'goods') {
					$data['a_det'] 				= [];
					$data['g_det'] 				= $msg['g_det'];
					unset($data['g_det'] ['goods_arr']);
					unset($data['g_det'] ['from']);
					$goods_arr 					= explode('，', $msg['g_det']['goods_arr']);

					if ($goods_arr) {
						$list   				= [];
						$gls  					= ['id' => 0, 'from' => $msg['g_det'] ['from'], 'short_id' => 0, 'site_id' => 0, 'user_id' => 0, 'is_explain' => 'not'];
						foreach ($goods_arr as $val) {
							$list[]				= array_merge(['goods_id' => $val], $gls);
						}

						// 获取商品数据列表
						$goods_list  			= $ShortLiveGoods->getGoodsData('package', [], 0, 10, 1, '', 'id desc', $list);

					} else {
						$goods_list  			= [];
					}

					$data['g_det']['goods_list']= $goods_list;

				} else {
					$data['g_det'] 					= [];
					$data['a_det']['introduce'] 	= htmlspecialchars_decode(html_entity_decode($msg['a_det']['introduce']));
					$data['a_det']['copywriter'] 	= htmlspecialchars_decode(html_entity_decode($msg['a_det']['copywriter']));
					$data['a_det']['share_img']  	= $msg['a_det']['share_img'] ? WEB_URL . $msg['a_det']['share_img'] : '';
					$data['a_det']['active_link'] 	= $msg['a_det']['active_link'];

					// 转化链接
					if ($token && $msg['a_det']['link_arr']) {
						$uid    			= $User->getUserId($token);        // 获取用户标识
						
						// 京东
						$pid 				= $User->getJdPid($token);
						$pid 				= $pid ? $pid : $Jingtuitui->create_pid($uid);
						
						// 拼多多
						Vendor('pdd.pdd','','.class.php');
						$pdd 				= new \pdd();

						$link_arr 			= explode('，', $msg['a_det']['link_arr']);

						foreach ($link_arr as $val) {
							$str 			= '';
							$str  			= $Jingtuitui::getGoodsCouponLink($val, '', $pid); 	// 京东转链

							if (!$str) {
								$res  		= $pdd->getUnitUrl($val, $uid);						// 拼多多转链
								$str       = ($res && isset($res['goods_zs_unit_generate_response']['short_url'])) ? $res['goods_zs_unit_generate_response']['short_url'] : '';
							}

							// 替换转化的链接
							if ($str) {
								// 替换
								$data['a_det']['copywriter'] = str_ireplace($val, $str, $data['a_det']['copywriter']);
							}
						}
					}


				}

				$this->ajaxSuccess(['detail' => $data]);
			}

			$this->ajaxError();
		}

		$this->ajaxSuccess(['detail' => $data]);
	}
}
?>