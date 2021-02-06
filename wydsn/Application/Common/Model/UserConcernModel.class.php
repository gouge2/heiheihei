<?php
/**
 * 用户关注关系管理类
 */
namespace Common\Model;
use Think\Model;

class UserConcernModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('by_id','require','被关注人标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
        array('user_id','require','关注人标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
        array('add_time','require','关注时间不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );
    

    /**
     * 获取关注列表及用户信息 
     */
    public function getListUser($where, $limit, $page)
    {
        $data = [];

        // 获取列表
        $list = $this->where($where)->page($page, $limit)->select();

        if ($list) {
            $UserDetail = new \Common\Model\UserDetailModel();

            // 其他表查询条件
            $bid_arr = [];
            $ud_list = [];

            // 循环组装其他表查询条件
            foreach ($list as $k => $v) {
                if ($v['by_id'] && in_array($v['by_id'], $bid_arr)) {
                    $bid_arr[] = $v['by_id'];
                }
            }

            // 用户列表
            if ($bid_arr) {
                $ud_list = $UserDetail->where(['user_id' => ['in', $bid_arr]])->getField('user_id,nickname,avatar');
            }

            foreach ($list as $key => $val) {
                // 用户信息
                $temp   = isset($ud_list[$val['by_id']]) ? $ud_list[$val['by_id']] : ['user_id' => '', 'nickname' => '', 'avatar' => ''];

                // 判断头像是否为第三方应用头像
                if ($temp['avatar'] && !is_url($temp['avatar'])) {
                    $temp['avatar'] = WEB_URL . $temp['avatar'];
                }
                
                $data[] = $temp;
            }
        }

        return $data;
    }

}
?>