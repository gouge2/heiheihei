<?php
/**
 * by 翠花 http://www.lailu.shop
 * 拼多多商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Model\UserModel;

class PddController extends AuthController 
{
	/**
	 * 获取顶级拼多多商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级拼多多商品分类列表
	 */
	public function getTopCatList()
	{
		$PddCat=new \Common\Model\PddCatModel();
		$list=$PddCat->getParentList('Y');
		if($list!==false)
		{
			//成功
			$data=array(
					'list'=>$list
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
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品标准类目
	 * @param int $parent_cat_id:非必填，值=0时为顶点cat_id,通过树顶级节点获取cat树
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getPddGoodsCat()
	{
		if(trim(I('post.parent_cat_id')))
		{
			$parent_cat_id=trim(I('post.parent_cat_id'));
		}else {
			$parent_cat_id=0;
		}
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$res=$pdd->getGoodsCat($parent_cat_id);
		// dump($res);die();
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品标签列表
	 * @param int $parent_opt_id:非必填，值=0时为顶点opt_id,通过树顶级节点获取opt树
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 */
	public function getPddGoodsOpt()
	{
		if(trim(I('post.parent_opt_id')))
		{
			$parent_opt_id=trim(I('post.parent_opt_id'));
		}else {
			$parent_opt_id=0;
		}
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$res=$pdd->getGoodsOpt($parent_opt_id=0);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取推荐商品列表
	 * @param int $channel_type:非必填，0, "1.9包邮"；1, "今日爆款"； 2, "品牌清仓"； 4,"PC端专属商城"；5，“福利商城”；不传值为默认商城；
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getTopGoodsList()
	{
		if(trim(I('post.channel_type')))
		{
			$channel_type=trim(I('post.channel_type'));
		}else {
			$channel_type='';
		}
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$p_id_list='["'.$pdd->pid.'"]';
		$res=$pdd->promUrlGenerate($generate_short_url='true',$p_id_list,$generate_mobile=false,$multi_group='true',$custom_parameters='',$generate_weapp_webview=false,$we_app_web_view_short_url=true,$we_app_web_view_url=true,$channel_type);
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 运营频道商品查询-推荐商品
	 * @param string $token:用户身份令牌
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param string $channel_type:非必填，频道类型；0, "1.9包邮", 1, "今日爆款", 2, "品牌清仓", 3, "默认商城", 非必填 ,默认是1
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getGoodsRecommend()
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
		//频道类型
		if(trim(I('post.channel_type')))
		{
			$channel_type=trim(I('post.channel_type'));
		}else {
			$channel_type=1;
		}
		//用户账号
		if(trim(I('post.token')))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
				echo json_encode ($res,JSON_UNESCAPED_UNICODE);
				exit();
			}else {
				$uid=$res_token['uid'];
				$userMsg=$User->getUserMsg($uid);
				//会员组
				$group_id=$userMsg['group_id'];
			}
		}else {
			//普通会员组
			$group_id=1;
		}
		$UserGroup=new \Common\Model\UserGroupModel();
		$groupMsg=$UserGroup->getGroupMsg($group_id);
		$fee_user=$groupMsg['fee_user'];
		
		//获取商品列表
		Vendor('pdd.pdd','','.class.php');
		$pdd=new \pdd();
		$offset=($page-1)*$page_size;
		$res_pdd=$pdd->getGoodsRecommend($offset,$page_size,$channel_type);
		if($res_pdd['code']==0)
		{
			$num=count($res_pdd['data']['list']);
			for($i=0;$i<$num;$i++)
			{
				//根据会员组计算相应佣金
				//佣金
				$price=$res_pdd['data']['list'][$i]['min_group_price']-$res_pdd['data']['list'][$i]['coupon_discount'];
				$commission=($price*$res_pdd['data']['list'][$i]['promotion_rate']/1000)*$fee_user/100;
				//保留2位小数，四舍五不入
				$res_pdd['data']['list'][$i]['commission']=substr(sprintf("%.3f",$commission),0,-1);
			}
		}
		$res=$res_pdd;
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取商品列表
	 * @param string $token:用户身份令牌
	 * @param string $keyword:非必填，商品关键词，与opt_id字段选填一个或全部填写
	 * @param number $opt_id:非必填，商品标签类目ID，使用pdd.goods.opt.get获取
	 * @param number $page:非必填，默认值1，商品分页数
	 * @param number $page_size:非必填，默认10，每页商品数量
	 * @param string $sort_type:非必填，排序方式:0-综合排序（默认）;1-按佣金比率升序;2-按佣金比例降序;3-按价格升序;4-按价格降序;5-按销量升序;6-按销量降序;7-优惠券金额排序升序;8-优惠券金额排序降序;9-券后价升序排序;10-券后价降序排序;11-按照加入多多进宝时间升序;12-按照加入多多进宝时间降序;13-按佣金金额升序排序;14-按佣金金额降序排序;15-店铺描述评分升序;16-店铺描述评分降序;17-店铺物流评分升序;18-店铺物流评分降序;19-店铺服务评分升序;20-店铺服务评分降序;27-描述评分击败同类店铺百分比升序，28-描述评分击败同类店铺百分比降序，29-物流评分击败同类店铺百分比升序，30-物流评分击败同类店铺百分比降序，31-服务评分击败同类店铺百分比升序，32-服务评分击败同类店铺百分比降序
	 * @param string $with_coupon:非必填，是否只返回优惠券的商品，false返回所有商品，true只返回有优惠券的商品（默认）
	 * @param number $cat_id:非必填，商品类目ID，使用pdd.goods.cats.get接口获取
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getGoodsList()
	{
		$token     			= trim(I('post.token'));
		$keyword 			= trim(I('post.keyword'));
		$opt_id 			= trim(I('post.opt_id'));
		$sort_type 			= I('post.sort_type', 0);
		$cat_id 			= trim(I('post.cat_id'));
		$with_coupon 		= trim(I('post.with_coupon', 'true'));
		$page 				= I('post.page/d', self::$page);
		$page_size 			= I('post.page_size/d', self::$limit);

		$platform           = trim(I('post.platform'));       // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端


		// 获取用户佣金比例
		$User 				= new \Common\Model\UserModel();
		$fee_user 			= $User->getUserFeeRatio($token, $group_id);
		$uid        		= $User->getUserId($token);

        // 主播佣金
        $HostTreatModel 	= new \Common\Model\HostTreatModel();

		//获取商品列表
		Vendor('pdd.pdd','','.class.php');
		$pdd 				= new \pdd();
		$res_pdd 			= $pdd->searchGoods($keyword,$opt_id,$page,$page_size,$sort_type,$with_coupon,$range_list='',$cat_id,$goods_id_list='',$zs_duo_id='',$merchant_type='');

		if ($res_pdd['code'] == 0) {
            $hostDetail 	= $HostTreatModel->getHostCommission($res_pdd['data']['list'], 'pdd', $group_id, 'goods_id');
            $hostCommission = $hostDetail['commission'];
			$num 			= count($res_pdd['data']['list']);

			// 循环组装条件
			$pid_arr 			= [];
			foreach ($res_pdd['data']['list'] as $val) {
				$pid_arr[] 		= $val['goods_id'];
			}

			// 收藏列表
			$pc_arr  			= [];
			$PddCollect 		= new \Common\Model\PddCollectModel();
			if ($pid_arr) {
				$pc_arr         = $PddCollect->is_collect($pid_arr, $uid);
			}

			for ($i = 0; $i < $num; $i++) {	
				//根据会员组计算相应佣金
				$price 				= $res_pdd['data']['list'][$i]['min_group_price'] * 1 - $res_pdd['data']['list'][$i]['coupon_discount'] * 1;
				$commission 		= ($price*$res_pdd['data']['list'][$i]['promotion_rate']/1000)/100;
				$commission_user 	= $commission*$fee_user;

				//保留2位小数，四舍五不入
				$res_pdd['data']['list'][$i]['commission'] 		= substr(sprintf("%.3f",$commission_user),0,-1);
                $res_pdd['data']['list'][$i]['commission_host'] = substr(sprintf("%.3f",$commission * $hostCommission * 100),0,-1);
                // 如果已经添加到商品夹，走主播的分佣
                if ($hostCommission['userCommission'] && in_array($res_pdd['data']['list'][$i]['goods_id'], $hostCommission['goodsList'])) {
                    $res_pdd['data']['list'][$i]['commission'] 	= substr(sprintf("%.3f",$commission * $hostCommission['userCommission'] * 100),0,-1);
                }

				// 微信小程序
				if ($platform == 'applet') {
					$res_pdd['data']['list'][$i]['commission'] 		= sprintf("%.2f", ($commission_user / 100));
					$res_pdd['data']['list'][$i]['commission_host'] = sprintf("%.2f", ($commission * $hostCommission));
					$res_pdd['data']['list'][$i]['min_group_price'] = substr(sprintf("%.3f", $price / 100), 0, -1);
					$old_price      = substr(sprintf("%.3f", $res_pdd['data']['list'][$i]['min_normal_price'] / 100), 0, -1);
					$res_pdd['data']['list'][$i]['min_normal_price']= $old_price;
					$coupon_amount  = substr(sprintf("%.1f", $res_pdd['data']['list'][$i]['coupon_discount'] / 100), 0, -2);
					$res_pdd['data']['list'][$i]['coupon_discount'] = $coupon_amount;

                    // 如果已经添加到商品夹，走主播的分佣
                    if ($hostCommission['userCommission'] && in_array($res_pdd['data']['list'][$i]['goods_id'], $hostCommission['goodsList'])) {
                        $res_pdd['data']['list'][$i]['commission'] 	= sprintf("%.2f", ($commission * $hostCommission['userCommission']));
                    }
				}
                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'pdd','goods_id'=>$res_pdd['data']['list'][$i]['goods_id']]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                #新版佣金获取
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$commission);
                $res_pdd['data']['list'][$i]['commission'] = $userCommission['userHasCommission'];
                $res_pdd['data']['list'][$i]['commission_host'] = $userCommission['hostUserCommission'];

				// 商品收藏标识
				$res_pdd['data']['list'][$i]['is_collect'] 			= isset($pc_arr[$res_pdd['data']['list'][$i]['goods_id']]) ? 'Y' : 'N';
			}

			$this->ajaxSuccess($res_pdd ? $res_pdd['data'] : []);
		}

		$this->ajaxError($res_pdd['code'], $res_pdd['msg']);
	}
	
	/**
	 * 获取商品详情
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:拼多多商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goods_details:商品详情
	 */
	public function getGoodsDetail()
	{
		$token     			= trim(I('post.token'));
		$goods_id 			= trim(I('post.goods_id'));
		$platform           = trim(I('post.platform'));       // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端
		
		if ($goods_id) {
			$goods_id_list 	= "[$goods_id]";

			$PddCollect 	= new \Common\Model\PddCollectModel();
			// 获取用户佣金比例
			$User 			= new \Common\Model\UserModel();
			$UserGroup 		= new \Common\Model\UserGroupModel();
			$fee_high   	= $User->getUserFeeRatio('', $group_id, 'high');
			$fee_user 		= $User->getUserFeeRatio($token, $group_id);
			$uid        	= $User->getUserId($token);

            // 主播佣金
            $HostTreatModel = new \Common\Model\HostTreatModel();

            //最高等级佣金比例
            $groupVipList 	= $UserGroup->getGroupList();
            $groupVipMsg 	= end($groupVipList);

			//获取商品列表
			Vendor('pdd.pdd','','.class.php');
			$pdd 			= new \pdd();
			$res_pdd 		= $pdd->getGoodsDetail($goods_id_list);

			if ($res_pdd['code'] == 0) {
                $hostDetail 		= $HostTreatModel->getHostCommission(array($res_pdd['data']['goods_details']), 'pdd', $group_id, 'goods_id');
				$hostCommission 	= $hostDetail['commission'];
				
				//根据会员组计算相应佣金
				$price 				= $res_pdd['data']['goods_details']['min_group_price'] * 1 - $res_pdd['data']['goods_details']['coupon_discount'] * 1;
				$commission 	 	= $fx_commission =($price*$res_pdd['data']['goods_details']['promotion_rate']/1000) / 100;
				$commission_user 	= $commission * $fee_user;
				$commission_high 	= $commission * $fee_high;

				//保留2位小数，四舍五不入
				$res_pdd['data']['goods_details']['commission'] 		= substr(sprintf("%.3f",$commission_user),0,-1);
				$res_pdd['data']['goods_details']['commission_high'] 	= substr(sprintf("%.3f",$commission_high),0,-1);
				$res_pdd['data']['goods_details']['commission_host'] 	= substr(sprintf("%.3f",$commission * $hostCommission * 100),0,-1);

                // 如果已经添加到商品夹，走主播的分佣
                if ($hostCommission['userCommission'] && in_array($res_pdd['data']['goods_details']['goods_id'], $hostCommission['goodsList'])) {

                    $res_pdd['data']['goods_details']['commission'] 	= substr(sprintf("%.3f",$commission * $hostCommission['userCommission'] * 100),0,-1);
				}
				
				//VIP佣金
				$res_pdd['data']['commission_vip'] 	= ($price*$res_pdd['data']['goods_details']['promotion_rate']/1000)*$groupVipMsg['fee_user']/100;

				//保留2位小数，四舍五不入
				$res_pdd['data']['commission_vip'] 					= substr(sprintf("%.3f",$res_pdd['data']['commission_vip']),0,-1);
				$res_pdd['data']['goods_details']['commission_vip'] = 	$res_pdd['data']['commission_vip'];
				
				//生成推广链接
				$p_id								= $pdd->pid;
				$custom_parameters 					= $uid;
				$res_pdd_url 						= $pdd->goodsPromotionUrlGenerate($p_id,$goods_id_list,$generate_short_url='true',$multi_group='false',$custom_parameters,$pull_new='true',$generate_weapp_webview='true',$zs_duo_id='',$generate_we_app='true', $generate_coupon = 'true');
                $CheckRecord = $pdd->CheckRecord($uid);
                if ($CheckRecord['bind'] == 1) {
                    $res_pdd['data']['url_list'] = ($res_pdd_url['code'] == 0) ? $res_pdd_url['data']['url_list'] : [];
                } else {
                    $res_pdd['data']['url_list'] = [];
                }

                // 微信小程序
				if ($platform == 'applet') {
					$res_pdd['data']['goods_details']['commission'] 		= sprintf("%.2f", ($commission_user / 100));
					$res_pdd['data']['goods_details']['commission_high'] 	= sprintf("%.2f", ($commission_high / 100));
					$res_pdd['data']['goods_details']['commission_host'] 	= sprintf("%.2f", ($commission * $hostCommission));
					$res_pdd['data']['goods_details']['min_group_price'] 	= substr(sprintf("%.3f", $price / 100), 0, -1);
					$old_price      = substr(sprintf("%.3f", $res_pdd['data']['goods_details']['min_normal_price'] / 100), 0, -1);
					$res_pdd['data']['goods_details']['min_normal_price'] 	= $old_price;
					$coupon_amount  = substr(sprintf("%.1f", $res_pdd['data']['goods_details']['coupon_discount'] / 100), 0, -2);
					$res_pdd['data']['goods_details']['coupon_discount'] 	= $coupon_amount;

                    // 如果已经添加到商品夹，走主播的分佣
                    if ($hostCommission['userCommission'] && in_array($res_pdd['data']['goods_details']['goods_id'], $hostCommission['goodsList'])) {
                        $res_pdd['data']['goods_details']['commission']=sprintf("%.2f", ($commission * $hostCommission['userCommission']));
					}
				}
                #新版佣金获取
                #效验是否存在商品列表
                $shortLiveModel = new \Common\Model\ShortLiveGoodsModel();
                $goodsItem = $shortLiveModel->getOne(['from'=>'pdd','goods_id'=>$res_pdd['data']['goods_details']['goods_id']]);
                $is_has = 0;
                if($goodsItem)
                {
                    $is_has = 1;
                }
                $userCommission = $HostTreatModel->getCommissionByUser($uid,$is_has,$fx_commission,$groupVipMsg['id']);
                $res_pdd['data']['goods_details']['commission'] = $userCommission['userHasCommission'];
                $res_pdd['data']['goods_details']['commission_host'] = $userCommission['hostUserCommission'];
                $res_pdd['data']['goods_details']['commission_vip'] = $res_pdd['data']['goods_details']['commission_high']  = $userCommission['vipHasCommission'];

				// 收藏标识
				$res_pdd['data']['goods_details']['is_collect']		= $PddCollect->is_collect($goods_id, $uid);

				$this->ajaxSuccess($res_pdd ? $res_pdd['data'] : []);
			}

			$this->ajaxError($res_pdd['code'], $res_pdd['msg']);
		}

		$this->ajaxError();
	}

    /**
     * 授权备案
     */
    public function GenerateAuthLink()
    {
        $token = trim(I('post.token'));
        $platform = trim(I('post.platform'));
        $platform = !empty($platform) ? $platform : 'applet';
        if (empty($token) || !in_array($platform,['applet','app'])) {
            $this->ajaxError();
        }
        $User = new UserModel();
        $uid = $User->getUserId($token);

        Vendor('pdd.pdd','','.class.php');
        $pdd = new \pdd();

        $data = array(
            'url' => '',
            'mobile_url' => ''
        );
        // 检查是否备案
        $checkGenerate = $pdd->CheckRecord($uid);
        if ($checkGenerate['bind'] == 0) {
            $res = $pdd->GenerateAuthLinkApplets($uid);
            $res = $res['rp_promotion_url_generate_response']['url_list'][0];
            if ($platform == 'applet') {
                $data['url'] = $res['we_app_info']['page_path'];
            } else {
                $data['url'] = $res['url'];
                $data['mobile_url'] = $res['mobile_url'];
            }
        }
        $this->ajaxSuccess($data);
    }
}
?>