<?php
/**
 * 直播礼物管理类
 */
namespace Common\Model;
use Think\Model;

class GiftModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('gift_name','require','礼物名称不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
        array('gift_price','require','礼物标价不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );

    
    /**
     * 获取礼物的全部列表
     */
    public function getAllList($sort = ' sort desc,gift_id asc ')
    {
        $data = [];
        $whe  = ['is_show' => 1, 'is_status' => 1];

        $list = $this->field('gift_id,gift_name,gift_join,gift_price,gift_cover,gift_url,gift_luxury')->where($whe)->order($sort)->select();

        if ($list) {
            foreach ($list as $val) {
                $temp               = $val;
                $gift_num           = [];

                // 礼物数量选项
                if ($temp['gift_join']) {
                    $join_arr = explode(',', $temp['gift_join']);
                    foreach ($join_arr as $v) {
                        $gift_num[] = (int)$v;
                    }
                } else {
                    $gift_num       = [1];
                }
                $temp['gift_num']   = $gift_num ? $gift_num : [1];

                // 封面图处理
                $temp['gift_cover'] = $val['gift_cover'] ? (is_url($val['gift_cover']) ? $val['gift_cover'] :  WEB_URL . $val['gift_cover']) : '';

                // 效果图处理
                $temp['gift_url']   = $val['gift_url'] ? (is_url($val['gift_url']) ? $val['gift_url'] :  WEB_URL . $val['gift_url']) : '';

                unset($temp['gift_join']);

                $data[]             = $temp;
            }
        }

        return $data;
    }
}
?>