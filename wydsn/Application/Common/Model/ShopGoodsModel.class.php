<?php
namespace Common\Model;
use Think\Model;
class ShopGoodsModel extends Model{
    protected $tableName = 'ewei_shop_goods';

    public function getGoodsInfo($where,$field='*')
    {
        return $this->where($where)->field($field)->find();
    }

    public function getList($where,$field='*')
    {
        return $this->where($where)->field($field)->select();
    }

    /**
     * #增减库存
     * @param $goods_id  商品ID
     * @param $num       同步的数量  为负减库存  为正加库存
     */
    public function synStock($goods_id,$num)
    {
        $goods = $this->getGoodsInfo(['id'=>$goods_id]);

        if($goods)
        {
            $total =$goods['total']+$num;
            $this->where(['id'=>$goods_id])->save(['total'=>$total]);
            return true;
        }else{
            return false;
        }
    }

    /**
     * #增减销量
     * @param $goods_id  商品ID
     * @param $num       同步的数量  为负减销量 为正加销量
     */
    public function synSales($goods_id,$num)
    {
        $goods = $this->getGoodsInfo(['id'=>$goods_id]);
        if($goods)
        {
            $sales =$goods['sales']+$num;
            $this->where(['id'=>$goods_id])->save(['sales'=>$sales]);
            return true;
        }else{
            return false;
        }
    }
}