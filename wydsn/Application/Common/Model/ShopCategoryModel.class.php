<?php
namespace Common\Model;
use Think\Model;

class ShopCategoryModel extends Model
{
    protected $tableName = "ewei_shop_category";


    /**
     * 获取商品分类信息
     * @param int $cat_id:商品分类ID
     * @return array|false
     */
    public function getCatMsg($cat_id)
    {
        $msg=$this->where("id='$cat_id'")->find();
        if($msg!==false)
        {
            return $msg;
        }else {
            return false;
        }
    }
}