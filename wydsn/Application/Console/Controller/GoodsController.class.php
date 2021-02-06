<?php
/**
 * 商品管理类
 */
namespace Console\Controller;

use Think\Controller;

class GoodsController extends Controller 
{

	/**
	 * 短视频或直播绑定商品失效的 查询并还原
	 */
	public function loseRecycle()
	{
		$ShortLiveGoods 	= new \Common\Model\ShortLiveGoodsModel();

		$whe 				= ['is_lose' => 0];

		// 查询有数据的列表
		$goods				= $ShortLiveGoods->getGoodsData('ad_fake', $whe, 0, 0, 0);

		$slg_id				= [];

		if ($goods) {
			foreach ($goods as $key => $val) {
				if ($val['price'] && $val['old_price'] ) {
					$slg_id[] = $key;
				}
			}
		}
		
		// 修改成有效的商品
		if ($slg_id) {
			$ShortLiveGoods->where(['id' => ['in', $slg_id]])->save(['is_lose' => 1]);
		}
	}

}