<?php
/**
 * by 来鹿 www.lailu.shop
 * 购物车管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class ShopcartController extends AuthController
{
	/**
	 * 加入购物车
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @param int $goods_num:购买数量，非必填，默认1
	 * @param int $goods_sku:商品规格属性，json数组
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function add()
	{
		if (trim(I('post.token')) and trim(I('post.goods_id'))) {
			//判断用户身份
			$token     = trim(I('post.token'));
			$User      = new \Common\Model\UserModel();
			$res_token = $User->checkToken($token);

			if ($res_token['code'] != 0) {
				//用户身份不合法
				$res   		= $res_token;
			}else {
				$uid   		= $res_token['uid'];
				$goods_num  = I('post.goods_num') ? I('post.goods_num') : 1;

				//判断商品是否存在
				$goods_id 	= trim(I('post.goods_id'));
				$Goods    	= new \Common\Model\GoodsModel();
				$GoodsMsg 	= $Goods->getGoodsMsg($goods_id);

				if ($GoodsMsg) {
					//判断是否已加入购物车
					$where 	= "user_id='$uid' and goods_id='$goods_id'";

					if ($_POST['goods_sku']) {
						#两种规格处理
                        $goods_sku = $_POST['goods_sku'];
                        if($GoodsMsg['ren_good_id']>0)
                        {
                            $GoodsSku  = new \Common\Model\ShopGoodsOptionModel();
                            $skuMsg    = $GoodsSku->getGoodsOptionById($GoodsMsg['ren_good_id'],$goods_sku);
                        }else{
                            $goods_sku = str_replace("\\", '', $goods_sku);
                            //$goods_sku = json_encode($_POST['goods_sku'],JSON_UNESCAPED_UNICODE); // 处理数组
                            $GoodsSku  = new \Common\Model\GoodsSkuModel();
                            $skuMsg    = $GoodsSku->getSkuMsg($goods_sku,$goods_id);
                        }


						if ($skuMsg == '') {
							//商品规格配置不存在
							$res = array(
								'code' => $this->ERROR_CODE_GOODS['GOODS_SKU_NOT_EXIST'],
								'msg'  => $this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_SKU_NOT_EXIST']]
							);
							echo json_encode ($res,JSON_UNESCAPED_UNICODE);  exit();
						}
						if($skuMsg['stock'] <$goods_num)
                        {
                            $res=array(
                                'code'=>$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE'],
                                'msg'=>$this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['INVENTORY_SHORTAGE']]
                            );
                            echo json_encode ($res,JSON_UNESCAPED_UNICODE);
                            exit();
                        }

						$where .= " and goods_sku='$goods_sku'";
					}

					$Shopcart  = new \Common\Model\ShopcartModel();

					$res_exist = $Shopcart->where($where)->find();

					if ($res_exist) {

                        #购物车操作
                        $cart_set = $Shopcart->where('id='.$res_exist['id'])->setInc('goods_num',$goods_num);
                        // 购物车数量
                        $cart_num  = $Shopcart->shopcartNum($uid);
						//已加入过购物车，不作操作
						$res = array(
							'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
							'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
							'data' => ['cart_num' => $cart_num]
						);
					}else {
						//新增
						$data 		= array(
								'user_id'=>$uid,
								'goods_id'=>$goods_id,
								'goods_num'=>$goods_num,
								'goods_sku'=>$goods_sku
						);
						$res_add 	= $Shopcart->add($data);

						// 购物车数量
						$cart_num  = $Shopcart->shopcartNum($uid);

						if ($res_add !== false) {
							$res 	= array(
								'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
								'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
								'data' => ['cart_num' => $cart_num]
							);
						}else {
							//数据库错误
							$res 	= array(
								'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
								'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
							);
						}
					}
				} else {
					//该商品不存在
					$res = array(
						'code' => $this->ERROR_CODE_GOODS['GOODS_NOT_EXIST'],
						'msg'  => $this->ERROR_CODE_GOODS_ZH[$this->ERROR_CODE_GOODS['GOODS_NOT_EXIST']]
					);
				}
			}
		} else {
			//参数不正确，参数缺失
			$res = array(
				'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
				'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 获取用户购物车列表
	 * @param string $token:用户身份令牌
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:购物车列表
	 */
	public function getShopcartList()
	{
		if (trim(I('post.token'))) {
			//判断用户身份
			$token     = trim(I('post.token'));
			$User      = new \Common\Model\UserModel();
			$res_token = $User->checkToken($token);

			if ($res_token['code'] != 0) {
				//用户身份不合法
				$res   = $res_token;
			} else {
				$uid      = $res_token['uid'];
				//获取用户购物车列表
				$Shopcart = new \Common\Model\ShopcartModel();
				$list     = $Shopcart->getUserShopcart($uid);

				if ($list !== false) {
					$res  = array(
						'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
						'data' => ['list' => $list]
					);
				}else {
					//数据库错误
					$res  = array(
						'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
					);
				}
			}
		}else {
			//参数不正确，参数缺失
			$res 	= array(
				'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
				'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}

		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

	/**
	 * 从购物车删除
	 * @param string $token:用户身份令牌
	 * @param int $goods_id:商品ID
	 * @param int $goods_sku:商品规格属性，json数组
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
    public function del()
    {
        $token     	= trim(I('post.token'));
        $goods_id 	= I('post.goods_id');
        $goods_sku  = I('post.goods_sku');
        $goods_num  = I('post.goods_num');

        if ($token and $goods_id) {
            //判断用户身份
            $User      = new \Common\Model\UserModel();
            $res_token = $User->checkToken($token);

            if ($res_token['code'] != 0) {
                //用户身份不合法
                $res   = $res_token;
            } else {
                $uid      = $res_token['uid'];
                $where    = ['user_id' => $uid];

                $where['goods_id'] = is_array($goods_id) ? ['in', $goods_id] : $goods_id;
                $num = count($goods_sku);
                $a = 0;
                for ($i =0;$i<=$num;$i++) {
                    if (empty($goods_sku[$i])){
                        $a ++;
                    }
                }
                if ($num < $a) {
                    $goods_sku = '';
                }

                if ($goods_sku) {
                    $where['goods_sku'] = is_array($goods_sku) ? ['in', $goods_sku] : $goods_sku;
                }

                $Shopcart = new \Common\Model\ShopcartModel();
                $goodsnum = $Shopcart->where($where)->getField('goods_num');
                if ($goods_num && $goodsnum > $goods_num) {
                    $nums = array(
                        'goods_num' => ($goodsnum - $goods_num),
                    );
                    $Shopcart->where($where)->save($nums);
                } else {
                    $res_del  = $Shopcart->where($where)->delete();
                }

                // 购物车数量
                $cart_num  = $Shopcart->shopcartNum($uid);

                if ($res_del !== false) {
                    $res = array(
                        'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
                        'data' => ['cart_num' => $cart_num]
                    );
                } else {
                    //数据库错误
                    $res = array(
                        'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
                        'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                    );
                }
            }
        } else {
            //参数不正确，参数缺失
            $res = array(
                'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }

        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}
?>
