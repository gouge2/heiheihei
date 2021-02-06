<?php


namespace Common\Model;

use Think\Model;

/**
 * 首页活动配置
 * Class AdvertHeadModel
 * @package Common\Model
 */
class AdvertHeadModel extends Model
{
    public function getAdvertList()
    {
        $ret = array();
        $resources = $this->select();
        $diy_list = array_combine([1,2,3,4],['商品ID','分类','活动类型','自定义']);
        $advert_cat_List = array_combine([1,2,3],['快抢商品（淘宝）','9块9专场（京东）','精选好货（京东）']);
        $advert_source_List = array_combine(['tb','jd','pdd','self'],['淘宝','京东','拼多多','自营']);
        if (!empty($resources)) {
            foreach ($resources as $key => $value) {
                $resources[$key]['advert_amount'] = $value['advert_amount_min'] . ' ~ ' . $value['advert_amount_max'];
                $resources[$key]['advert_price'] = $value['advert_price_min'] . ' ~ ' . $value['advert_price_max'];
                $resources[$key]['advert_switch_name'] = $value['advert_switch'] == 1 ? '开' : '关';
                if ($value['advert_coupon'] == 1) {
                    $advert_coupon_name = '有';
                } elseif ($value['advert_coupon'] == 2) {
                    $advert_coupon_name = '无';
                } else {
                    $advert_coupon_name = '全部';
                }
                $resources[$key]['advert_coupon_name'] = $advert_coupon_name;
                $resources[$key]['advert_source_name'] = $advert_source_List[$value['advert_source']];
                $resources[$key]['diy_id'] = $diy_list[$value['diy_id']];
                $resources[$key]['advert_cat'] = $advert_cat_List[$value['advert_cat']];
                if ($value['advert_modular'] == 1) {
                    $resources[$key]['advert_modular_name'] = '好物推荐';
                } elseif ($value['advert_modular'] == 2) {
                    $resources[$key]['advert_modular_name'] = '限时秒杀';
                } elseif ($value['advert_modular'] == 3) {
                    $resources[$key]['advert_modular_name'] = '为你推荐';
                }
            }
            $ret = $resources;
        }
        return $ret;
    }
}