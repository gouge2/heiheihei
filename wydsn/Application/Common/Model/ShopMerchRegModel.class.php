<?php
namespace Common\Model;
use Think\Model;

class ShopMerchRegModel extends Model
{
    #表名
    protected $tableName='ewei_shop_merch_reg';
    /**
     * 获取单条数据记录
     */
    public function getOne($whe, $field = '*')
    {
        $res = null;

        if ($whe) {

            $res = $this->field($field)->where($whe)->find();
        }
        return $res;
    }
}
?>