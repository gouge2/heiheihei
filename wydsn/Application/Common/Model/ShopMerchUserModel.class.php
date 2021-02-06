<?php
namespace Common\Model;
use Think\Model;

class ShopMerchUserModel extends Model
{
    #表名 
    protected $tableName='ewei_shop_merch_user';
    /**
     * 获取单条数据记录
     */
    public function getOne($whe, $field = '*')
    {
        $res = null;

        if ($whe) {

            $res = $this->field($field)->where($whe)->find();
            if(!empty($res))
            {
                $res['logo'] = WEB_URL.'/Public/Upload/Goods/images/'.$res['logo'];
            }
        }
        return $res;
    }

    public function getLogo()
    {
        return [
            '1'=>['logo'=>WEB_URL.'/ic_self_employed.png'],
            '2'=>['logo'=>WEB_URL.'/ic_self_member.png'],
            '3'=>['logo'=>WEB_URL.'/ic_self_shop.png'],
        ];
    }
}
?>