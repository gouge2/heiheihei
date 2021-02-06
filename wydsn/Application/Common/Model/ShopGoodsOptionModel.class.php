<?php
namespace Common\Model;
use Think\model;
class ShopGoodsOptionModel extends model{

    protected $tableName = "ewei_shop_goods_option";


    /**
     * 获取商品的属性值
     * @param $id int 商品ID
     * @param $spec_id   int   规格ID
     */
    public function getGoodsOptionBySpecId($id)
    {
        return $this->where('goodsid='.$id)->field(['stock','marketprice','productprice','title','specs'])->select();
    }

    /**
     * 根据商品ID及属性值获取商品属性
     * @param $id
     * @param $value
     * @return array|false|mixed|string|null
     */
    public function getGoodsOptionById($id,$value)
    {
        return $this->where("goodsid={$id} and specs='{$value}'")->field(['id','stock','marketprice','productprice','title','specs'])->find();
    }

    public function getGoodsInfo($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }


    /**
     * #增减库存
     * @param $goods_id  商品ID
     * @param $num       同步的数量  为负减库存  为正加库存
     */
    public function synStock($goods_id,$num,$goods_sku)
    {
        $goods = $this->getGoodsInfo(['id'=>$goods_id,'specs'=>$goods_sku]);

        if($goods)
        {
            $total =$goods['stock']+$num;
            $this->where(['id'=>$goods_id,'specs'=>$goods_sku])->save(['stock'=>$total]);
            return true;
        }else{
            return false;
        }
    }
}