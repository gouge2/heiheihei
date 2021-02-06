<?php
namespace Common\Model;
use Think\model;
class ShopGoodsSpecItemModel extends model{

    protected $tableName = "ewei_shop_goods_spec_item";


    /**
     * 获取商品规格值
     * @param $id
     */
    public function getGoodsSpecItem($id)
    {
        return $this->where('specid='.$id)->field(['title','id','specid'])->select();
    }
}