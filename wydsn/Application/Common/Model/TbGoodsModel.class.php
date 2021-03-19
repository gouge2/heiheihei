<?php
/**
 * by 翠花 http://www.lailu.shop
 * 淘宝商品管理
 */
namespace Common\Model;
use Think\Model;

class TbGoodsModel extends Model
{
	/**
	 * 获取商品详情
	 * @param int $id:ID
	 * @return array|boolean
	 */
	public function getGoodsMsg($id)
	{
		$msg=$this->where("id='$id'")->find();
		if($msg)
		{
			return $msg;
		}else {
			return false;
		}
	}

    /**
     * 获取淘宝客商品详情
     * @param $goods_id
     * @param $pid
     * @param $relationId
     */
	public function getTbkGoodsMsg($goods_id,$pid,$relationId)
    {
        //获取商品详情
        $num_iid=trim(I('post.num_iid'));
        Vendor('tbk.tbk','','.class.php');
        $tbk=new \tbk();
        $ip='';
        $res_tbk=$tbk->getItemDetail($num_iid,'2',$ip,$pid,$relationId);
        return $res_tbk['data']['commission'];
    }
}
?>