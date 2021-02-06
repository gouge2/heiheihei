<?php
namespace Common\Model;
use Think\model;
class ShopGoodsSpecModel extends model{

    protected $tableName = "ewei_shop_goods_spec";


    /**
     * 获取规格属性
     * @param $id   人人商品ID
     */
    public function getSpec($id)
    {
        //规格specs
        return $this->where('goodsid='.$id)->field(['title','id'])->select();
    }
}