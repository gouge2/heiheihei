<?php
/**
 * by 翠花 http://livedd.com
 * 购物车管理类
 */
namespace Common\Model;
use Think\Model;

class ShopcartModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','购买用户不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','购买用户不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('goods_id','require','购买商品不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_id','is_positive_int','购买商品不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('goods_num','require','商品数量不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_num','is_positive_int','商品数量必须为正整数',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
	);
	
	/**
	 * 获取用户购物车列表
	 * @param int $uid:用户ID
	 * @return array|boolean
	 */
	public function getUserShopcart($uid)
	{
		$sql="select c.*,g.* from __PREFIX__shopcart c,__PREFIX__goods g where c.user_id='$uid' and c.goods_id=g.goods_id";
		$list=M()->query($sql);

		$lists = self::getShopMerch($uid);
		array_push($lists,['shop_id'=>-1]);
		$datas = [];
		$data = [];
        $key = 0;
		if($list!==false)
		{
			$num=count($list);
			$GoodsSku=new \Common\Model\GoodsSkuModel();
			$GoodsAttribute=new \Common\Model\GoodsAttributeModel();
			$shopOptionModel = new \Common\Model\ShopGoodsOptionModel();
            $shopModel = new \Common\Model\ShopMerchUserModel();
            $logo = $shopModel->getLogo();
			for ($s=0;$s<count($lists);$s++)
            {
                if($lists[$s]['shop_id']>0)
                {
                    $shop = $shopModel->getOne(['id'=>$lists[$s]['shop_id']]);
                    $datas[$s]['shop_name']=$shop['merchname'];
                    $datas[$s]['logo'] = $logo[3]['logo'];
                    $datas[$s]['shop_id'] = $lists[$s]['shop_id'];
                    $datas[$s]['text'] = '';
                    $datas[$s]['is_gift_goods'] = 'N';
                }else if($lists[$s]['shop_id']==0)
                {
                    $datas[$s]['shop_name'] = '平台直营';
                    $datas[$s]['logo'] = $logo[1]['logo'];
                    $datas[$s]['shop_id'] = 0;
                    $datas[$s]['text'] = '';
                    $datas[$s]['is_gift_goods'] = 'N';
                }else{
                    $datas[$s]['shop_name'] = '礼包商品';
                    $datas[$s]['logo'] = $logo[2]['logo'];;
                    $datas[$s]['shop_id'] = 0;
                    $datas[$s]['is_gift_goods'] = 'Y';
                    $datas[$s]['text'] = '礼包商品不允许退款';
                }
                $datas[$s]['goods_list']=[];
                for($i=0;$i<$num;$i++)
                {
                    if ($list[$i]['img']) {
                        $list[$i]['img'] = (is_url($list[$i]['img']) ? $list[$i]['img'] : WEB_URL . $list[$i]['img']);
                    }
                    if ($list[$i]['tmp_img']) {
                        $list[$i]['tmp_img'] = (is_url($list[$i]['tmp_img']) ? $list[$i]['tmp_img'] : WEB_URL . $list[$i]['tmp_img']);
                    }
                    #拆分购物车
                    $key1 = 0;
                    #基于商家进行拆分购物车模块
                    if($list[$i]['shop_id']==0 && $list[$i]['is_gift_goods'] == 'N' && $lists[$s]['shop_id'] == 0)
                    {
                        $list[$i]['price']=$list[$i]['price']/100;
                        if($list[$i]['goods_sku']) {
                            $goods_sku = $list[$i]['goods_sku'];
                            $skuMsg = $GoodsSku->getSkuMsg($goods_sku);
                            //价格
                            $list[$i]['price'] = $skuMsg['price'];
                            //图片
                            if ($skuMsg['img']) {
                                $list[$i]['img'] = (is_url($skuMsg['img']) ? $skuMsg['img'] : WEB_URL . $skuMsg['img']);
                            }
                            $sku_arr = json_decode($list[$i]['goods_sku'], true);
                            $num2 = count($sku_arr);
                            $sku_str = '';
                            for ($j = 0; $j < $num2; $j++) {
                                //属性ID
                                $GoodsAttributeMsg = $GoodsAttribute->getMsg($sku_arr[$j]['attribute_id']);
                                $sku_arr[$j]['attribute_name'] = $GoodsAttributeMsg['goods_attribute_name'];
                                $sku_str .= $GoodsAttributeMsg['goods_attribute_name'] . '：' . $sku_arr[$j]['value'] . '&nbsp;&nbsp;';
                            }
                            $list[$i]['sku_arr'] = $sku_arr;
                            $list[$i]['sku_str'] = substr($sku_str, 0, -1);
                        }
                        $list[$i]['sku_arr']='';
                        $list[$i]['sku_str']='';
                        $datas[$s]['goods_list'][]=$list[$i];
                    }
                    else if($list[$i]['is_gift_goods'] == 'Y' && $list[$i]['shop_id']==0 && $lists[$s]['shop_id'] == -1){
                        $list[$i]['price']=$list[$i]['price']/100;
                        if($list[$i]['goods_sku']) {
                            $goods_sku = $list[$i]['goods_sku'];
                            $skuMsg = $GoodsSku->getSkuMsg($goods_sku);
                            //价格
                            $list[$i]['price'] = $skuMsg['price'];
                            //图片
                            if ($skuMsg['img']) {
                                $list[$i]['img'] = (is_url($skuMsg['img']) ? $skuMsg['img'] : WEB_URL . $skuMsg['img']);
                            }
                            $sku_arr = json_decode($list[$i]['goods_sku'], true);
                            $num2 = count($sku_arr);
                            $sku_str = '';
                            for ($j = 0; $j < $num2; $j++) {
                                //属性ID
                                $GoodsAttributeMsg = $GoodsAttribute->getMsg($sku_arr[$j]['attribute_id']);
                                $sku_arr[$j]['attribute_name'] = $GoodsAttributeMsg['goods_attribute_name'];
                                $sku_str .= $GoodsAttributeMsg['goods_attribute_name'] . '：' . $sku_arr[$j]['value'] . '&nbsp;&nbsp;';
                            }
                            $list[$i]['sku_arr'] = $sku_arr;
                            $list[$i]['sku_str'] = substr($sku_str, 0, -1);
                        }
                        $list[$i]['sku_arr']='';
                        $list[$i]['sku_str']='';
                        $datas[$s]['goods_list'][]=$list[$i];
                    }
                    else{
                        if($lists[$s]['shop_id'] == $list[$i]['shop_id'] && $list[$i]['shop_id'] >0)
                        {
                            $list[$i]['price']=$list[$i]['price']/100;
                            $list[$i]['sku_arr']='';
                            $list[$i]['sku_str']='';
                            if($list[$i]['goods_sku'])
                            {
                                $goods_option = $shopOptionModel->getGoodsOptionById($list[$i]['ren_good_id'],$list[$i]['goods_sku']);
                                $list[$i]['sku_str']=empty($goods_option)?'':$goods_option['title'];
                                $list[$i]['price']=!empty($goods_option)?$goods_option['marketprice']:$list[$i]['price'];
                            }
                            $datas[$s]['goods_list'][]=$list[$i];
                        }
                    }
                }
               if(!empty($datas[$s]['goods_list']))
               {

                   $data[$key]['shop_name'] = $datas[$s]['shop_name'];
                   $data[$key]['shop_id'] = $datas[$s]['shop_id'];
                   $data[$key]['logo'] = $datas[$s]['logo'];
                   $data[$key]['text'] = $datas[$s]['text'];
                   $data[$key]['is_gift_goods'] = $datas[$s]['is_gift_goods'];
                   $data[$key]['goods_list'] = $datas[$s]['goods_list'];
                   $key ++;
               }
            }
			return $data;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取购物车数量
	 * @param int $uid:用户ID
	 * @return number|boolean
	 */
	public function shopcartNum($uid)
	{
		$num = $this->where("user_id='$uid' and goods_num > 0")->count();

		return ($num!==false) ? (int)$num : 0;
	}

    /**
     * group商家
     */
    public function getShopMerch($uid)
    {
        return $this->alias('c')->join('__GOODS__ g ON c.goods_id= g.goods_id', 'LEFT')->where('c.user_id='.$uid.' AND NOT ISNULL(g.shop_id)')->field('shop_id')->group('shop_id')->select();
    }
}
?>