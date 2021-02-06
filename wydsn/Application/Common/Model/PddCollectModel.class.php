<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 拼多多收藏商品管理
 */
namespace Common\Model;
use Think\Model;

class PddCollectModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('goods_id','require','请选择需要收藏的商品！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('goods_id','1,30','拼多多商品ID不超过30个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过30个字符
			array('user_id','require','用户账号错误！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','用户账号错误！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('collect_time','require','收藏时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('collect_time','is_datetime','收藏时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
	);
	
	/**
	 * 判断用户是否收藏该商品
	 */
	public function is_collect($goods_id,$uid)
	{
		$whe['user_id'] 		= $uid;

		if (is_array($goods_id)) {
			$data   			= [];
			if ($uid) {
				$whe['goods_id']= ['in', $goods_id];
				$data 			= $this->where($whe)->getField('goods_id,user_id');
			}
		} else {
			$whe['goods_id'] 	= $goods_id;
			$res_exist 			= $this->where($whe)->find();
			$data      			= $res_exist ? 'Y' : 'N';
		}

		return $data;
	}
	
	/**
	 * 获取收藏商品列表
	 * @param int $uid:用户ID
	 * @return array|boolean
	 */
	public function getCollectList($uid)
	{
		$list=$this->where("user_id='$uid'")->select();
		if($list!==false)
		{
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取客户收藏商品数量
	 * @param int $uid:用户ID
	 * @return number|boolean
	 */
	public function calculateNum($uid)
	{
		$num=$this->where("user_id='$uid'")->count();
		if($num!==false)
		{
			return $num;
		}else {
			return false;
		}
	}
}
?>