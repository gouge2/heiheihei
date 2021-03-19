<?php
/**
 * by 翠花 www.lailu.shop
 * 京东收藏商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class JingdongCollectController extends AuthController
{
	/**
	 * 收藏商品
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:京东商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function collect()
	{
		if(I('post.token') and I('post.goods_id'))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断商品是否存在
				$goods_id=trim(I('post.goods_id'));
				//判断是否已收藏该商品
				$JingdongCollect=new \Common\Model\JingdongCollectModel();
				$res_c=$JingdongCollect->where("goods_id='$goods_id' and user_id='$uid'")->find();
				if($res_c)
				{
					//已收藏该商品，请勿重复收藏
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['GOODS_ALREADY_COLLECTED'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_ALREADY_COLLECTED']]
					);
				}else {
					$data=array(
							'goods_id'=>$goods_id,
							'user_id'=>$uid,
							'collect_time'=>date('Y-m-d H:i:s')
					);
					$res_add=$JingdongCollect->add($data);
					if($res_add!==false)
					{
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功',
						);
					}else {
						//数据库错误
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
						);
					}
				}
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
	 * 取消收藏
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function cancelCollect()
	{
		if(I('post.token') and I('post.goods_id'))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				$goods_id=trim(I('post.goods_id'));
				$JingdongCollect=new \Common\Model\JingdongCollectModel();
				$res_del=$JingdongCollect->where("goods_id='$goods_id' and user_id='$uid'")->delete();
				if($res_del)
				{
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'=>'成功',
					);
				}else {
					//您尚未收藏该商品
					$res=array(
							'code'=>$this->ERROR_CODE_GOODS['GOODS_NOT_COLLECT'],
							'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_NOT_COLLECT']]
					);
				}
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
	 * 获取用户收藏商品列表
	 * @param string $token:用户身份令牌
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goods_allid:收藏商品ID集合
	 */
	public function getCollectList()
	{
		if(I('post.token'))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				if(trim(I('post.p'))) {
				    $p 		= trim(I('post.p'));
				}else {
				    $p 		= self::$page;
				}
				if(trim(I('post.per'))) {
				    $per 	= trim(I('post.per'));
				}else {
				    $per 	= self::$limit;
				}
				$JingdongCollect=new \Common\Model\JingdongCollectModel();
				$goodslist=$JingdongCollect->where("user_id='$uid'")->order('id desc')->page($p,$per)->select();
				if($goodslist!==false)
				{
					$goods_allid='';
					foreach ($goodslist as $l)
					{
						$goods_allid.=$l['goods_id'].',';
					}
					if($goods_allid)
					{
						$goods_allid=substr($goods_allid, 0,-1);
						$data=array(
								'goods_allid'=>$goods_allid
						);
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功',
								'data'=>$data
						);
					}else {
						//没有收藏的商品
						$data=array(
								'goods_allid'=>''
						);
						$res=array(
								'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'=>'成功',
								'data'=>$data
						);
					}
				}else {
					//数据库错误
					$res=array(
							'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
							'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
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
	 * 用户是否收藏商品
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->is_collect:是否收藏 Y已收藏 N未收藏
	 */
	public function is_collect()
	{
		if(I('post.token') and I('post.goods_id'))
		{
			//判断用户身份
			$token=trim(I('post.token'));
			$User=new \Common\Model\UserModel();
			$res_token=$User->checkToken($token);
			if($res_token['code']!=0)
			{
				//用户身份不合法
				$res=$res_token;
			}else {
				$uid=$res_token['uid'];
				//判断是否收藏
				$goods_id=trim(I('post.goods_id'));
				$JingdongCollect=new \Common\Model\JingdongCollectModel();
				$res_exist=$JingdongCollect->where("goods_id='$goods_id' and user_id='$uid'")->find();
				if($res_exist)
				{
					$is_collect='Y';
				}else {
					$is_collect='N';
				}
				$data=array(
						'is_collect'=>$is_collect
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
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
	 * 获取用户收藏商品新的列表
	 */
	public function getNewCollectList()
	{
		// 参数不正确，参数缺失
        $res       = [      
            'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
            'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
		];
		
		$token             	= trim(I('post.token'));
		$limit              = I('post.limit/d', self::$limit);
        $page               = I('post.page/d', self::$page);
	
		if ($token) {
			// 判断用户身份
			$User 			= new \Common\Model\UserModel();
			$res_token 		= $User->checkToken($token);

			if ($res_token['code'] != 0) {
				//用户身份不合法
				$res 		= $res_token;
			} else {
				$uid 		= $res_token['uid'];

				$jdCollect 	= new \Common\Model\JingdongCollectModel();
				$jc_list 	= $jdCollect->where("user_id='$uid'")->order('id desc')->page($page, $limit)->select();

				if ($jc_list !== false) {
					// 京推推类库
					$Jingtuitui 	= new \Common\Controller\JingtuituiController();
            		$jd_gid     	= [];

					// 循环拿商品ID组
					foreach ($jc_list as $v) {
						$jd_gid[]  = $v['goods_id'];
					}

					// 查询用户会员组
					$UserGroup  	= new \Common\Model\UserGroupModel();
					$user_msg   	= $User->getUserMsg($uid);
					$group_msg  	= $user_msg ? $UserGroup->getGroupMsg($user_msg['group_id']) : [];
					$fee_user   	= $group_msg ? $group_msg['fee_user'] : 0;

					$list 			= $Jingtuitui::jdConciseList($jd_gid, $fee_user, $user_msg['group_id'], true);

					$res['data']['list'] = $list;
					$res['code']    = $this->ERROR_CODE_COMMON['SUCCESS'];
                    $res['msg']     = $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']];

				} else {
					// 数据库错误
					$res['code'] 	= $this->ERROR_CODE_COMMON['DB_ERROR'];
					$res['msg'] 	= $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']];
				}
			}
		}

		echo json_encode ($res, JSON_UNESCAPED_UNICODE);
	}
}
?>