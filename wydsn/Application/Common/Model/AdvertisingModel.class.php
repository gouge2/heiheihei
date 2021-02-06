<?php
/**
 * 广告文件管理类
 */
namespace Common\Model;
use Think\Model;

class AdvertisingModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('vid','require','文件标识不能为空！',self::EXISTS_VALIDATE),              //存在验证，必填
        array('short_name','1,255','文件名称不符合！',self::VALUE_VALIDATE,'length'),   //值不为空的时候验证，不超过255个字符
        array('create_time','require','记录时间不能为空！',self::EXISTS_VALIDATE),      //存在验证，必填
        array('media_url','1,255','文件链接不符合！',self::VALUE_VALIDATE,'length'),    //值不为空的时候验证，不超过255个字符
    );
    

    /**
     * 获取广告列表
     */
    public function getList($whe,$at_id, $platform = '')
    {
        $data   = [];
        $field  = 'id as short_id,is_status as review_status,avatar,advertiser_name,title,start_time,end_time,cover_url,media_url,channel_link,play_num,comment_num,praise_num,forward_num,click_num,preparation_interface';
        $list   = $this->field($field)->where($whe)->order()->select();
        $time = date('Y-m-d H:i:s');
        // 过期下架广告
        foreach ($list as $k => $v) {
            if ($time >= $list[$k]['end_time']) {
                $this->where(['id' => $list[$k]['short_id']])->save(['is_status'=>0]);
                unset($list[$k]);
            }
        }
        if ($list) {
            // 其他模型
            $AdvertisingPraise     = new \Common\Model\AdvertisingPraiseModel();

            // 其他表查询条件
            $uid_arr = $sid_arr = [];
            $ud_list = $up_list = $uc_list = $sg_list = $lv_list = [];

            // 循环组装其他表查询条件
            foreach ($list as $v) {
                $sid_arr[]      = $v['short_id'];
            }


            // 点赞列表
            $up_list = $at_id ? $AdvertisingPraise->where(['ad_id' => ['in', $sid_arr], 'user_id' => $at_id])->getField('ad_id', true) : [];
            // 组装其他信息
            foreach ($list as $val) {

                $temp['short_id']           = $val['short_id'];
                // 视频与封面判断
                $temp['cover_url']          = is_url($val['cover_url']) ? $val['cover_url'] : WEB_URL . $val['cover_url'];
                $temp['media_url']          = is_url($val['media_url']) ? $val['media_url'] : WEB_URL . $val['media_url'];

                // 点赞标识
                $temp['praise_iden']        = in_array($temp['short_id'], $up_list) ? 1 : 0;

                // 开始~结束时间转时间戳
                $temp['start_time']         = strtotime($val['start_time']);
                $temp['end_time']           = strtotime($val['end_time']);
                // 标题
                $temp['description']        = $val['title'];

                //头像
                $temp['avatar']             = is_url($val['avatar']) ? $val['avatar'] : WEB_URL . $val['avatar'];

                //广告主名称
                $temp['nickname']    = $val['advertiser_name'];

                // 渠道链接
                $temp['channel_link']       = $val['channel_link'];

                //预备接口
                $temp['preparation_interface'] = $val['preparation_interface'];

                // 类型转换
                $temp['praise_num']         = (int)$val['praise_num'];      //点赞量
                $temp['comment_num']        = (int)$val['comment_num'];     //评论量
                $temp['forward_num']        = (int)$val['forward_num'];     //分享量
                $temp['play_num']           = (int)$val['play_num'];        //播放量
                $temp['click_num']          = (int)$val['click_num'];       //点击量
                $data[]                     = $temp;
            }

        }
        return $data;
    }
}
?>