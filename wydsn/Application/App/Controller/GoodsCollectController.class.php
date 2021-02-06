<?php
/**
 * 自营商品收藏管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class GoodsCollectController extends AuthController
{
    /**
     * 收藏商品/取消收藏
     */
    public function handleCollect()
    {
        // 获取参数
        $goods_id  = trim(I('post.goods_id'));
        $type      = trim(I('post.type'));         // 操作类型  collect：收藏  cancel:取消收藏

        if ($goods_id && $type && in_array($type, ['collect', 'cancel'])) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            // 获取用户标识
            $uid            = $res_token['uid'];

            // 判断商品是否存在
            $Goods          = new \Common\Model\GoodsModel();
            $g_cou          = $Goods->where(['goods_id' => $goods_id])->getField('goods_id');

            if ($g_cou) {
                // 判断是否已收藏该商品
                $GoodsCollect   = new \Common\Model\GoodsCollectModel();
                $res_c          = $GoodsCollect->where("goods_id='$goods_id' and user_id='$uid'")->getField('id');

                // 收藏商品
                if ($type == 'collect') {
                    if ($res_c) {
                        // 已收藏该商品，请勿重复收藏
                        $this->ajaxError(['ERROR_CODE_GOODS' => 'GOODS_ALREADY_COLLECTED']);
                    } else {
                        $data       = array(
                            'goods_id'     => $goods_id,
                            'user_id'      => $uid,
                            'collect_time' => date('Y-m-d H:i:s')
                        );
                        $res_add           = $GoodsCollect->add($data);     // 添加收藏

                        if ($res_add !== false) {
                            $this->ajaxSuccess();
                        } else {
                            // 数据库错误
                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }
                    }

                // 取消收藏
                } elseif ($type == 'cancel') {
                    if ($res_c) {
                        $res_del        = $GoodsCollect->where("goods_id='$goods_id' and user_id='$uid'")->delete();

                        if ($res_del) {
                            $this->ajaxSuccess();
                        } else {
                            // 数据库错误
                            $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
                        }
                    } else {
                        // 您尚未收藏该商品
                        $this->ajaxError(['ERROR_CODE_GOODS' => 'GOODS_NOT_COLLECT']);
                    }
                }
            } else {
                $this->ajaxError(['ERROR_CODE_GOODS' => 'GOODS_NOT_EXIST']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 获取用户收藏商品列表
     */
    public function getCollectList()
    {
        // 获取参数
        $limit     = I('post.limit/d', self::$limit);
        $page      = I('post.page/d', self::$page);

        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        $uid            = $res_token['uid'];
        $GoodsCollect   = new \Common\Model\GoodsCollectModel();
        $goods_list     = $GoodsCollect->where("user_id='$uid'")->page($page, $limit)->order('collect_time desc')->getField('goods_id', true);

        if ($goods_list) {
            $Goods  = new \Common\Model\GoodsModel();
            $list   = $Goods->where(['goods_id' => ['in', $goods_list]])
                ->field('goods_id,cat_id,goods_name,goods_code,img,description,brand_id,clicknum,old_price,price,inventory,give_point,sales_volume,virtual_volume,createtime,is_fx_goods,fx_profit_money')
                ->order('goods_id desc')->select();

            if ($list) {
                //查询用户会员组
                $userMsg   = $User->getUserMsg($uid);
                $UserGroup = new \Common\Model\UserGroupModel();
                $groupMsg  = $UserGroup->getGroupMsg($userMsg['group_id']);
                $fee_user  = $groupMsg['fee_user'];
                $num       = count($list);
                $ShortLiveGoodsModel = new \Common\Model\ShortLiveGoodsModel();
                $hostTreatModel = new \Common\Model\HostTreatModel();
                $userGroup = new \Common\Model\UserGroupModel();
                $groupList = $userGroup->getGroupList();
                $groupVipMsg 	= end($groupList);
                for ($i = 0; $i < $num; $i++) {
                    // 价格  四舍五不入
                    $list[$i]['price']        = $list[$i]['price']/100;
                    $list[$i]['price']        = substr(sprintf("%.3f",$list[$i]['price']),0,-1);
                    $list[$i]['old_price']    = substr(sprintf("%.3f",$list[$i]['old_price']),0,-1);
                    // 销量
                    $list[$i]['sales_volume'] = $list[$i]['sales_volume']+$list[$i]['virtual_volume'];
                    unset($list[$i]['virtual_volume']);
                    // 主图
                    $list[$i]['img']          = is_url($list[$i]['img']) ? $list[$i]['img'] : WEB_URL . $list[$i]['img'];;

                    // 佣金  保留2位小数，四舍五不入
                    $list[$i]['commission']   = 0;
                    $list[$i]['commission']   = substr(sprintf("%.3f",$list[$i]['commission']),0,-1);
                    // 优惠券价格
                    $list[$i]['coupon_amount']= substr(sprintf("%.3f",0),0,-1);

                    // 类型转化
                    $list[$i]['inventory']    = (int)$list[$i]['inventory'];
                    $list[$i]['clicknum']     = (int)$list[$i]['clicknum'];

                    // 带货赚
                    $list[$i]['commission_host'] = 0;
                    if(IS_DISTRIBUTION=='Y' && $list[$i]['is_fx_goods']=='Y' )
                    {
                        #判定是否存在入库商品列表
                        $item = $ShortLiveGoodsModel->getOne(['from'=>'self','goods_id'=>$list[$i]['goods_id']]);
                        if($item)
                        {
                            $is_has = 1;
                        }
                        $userCommission = $hostTreatModel->getCommissionByUser($uid,$is_has,$list[$i]['fx_profit_money']/100,$groupVipMsg['id']);
                        if ($userMsg['is_host'] == 'Y') $list[$i]['commission_host']	 	= $userCommission['hostUserCommission'];
                        $list[$i]['commission'] 	= $userCommission['userHasCommission'];
                    }
                }

                $this->ajaxSuccess(['list' => $list]);

            } else {
                // 商品不存在
                $this->ajaxError(['ERROR_CODE_GOODS' => 'GOODS_NOT_EXIST']);
            }
        } else {
            // 没有收藏的商品
            $this->ajaxSuccess(['list' => []]);
        }
    }

    /**
     * 用户是否收藏商品
     */
    public function is_collect()
    {
        // 获取参数
        $goods_id  = trim(I('post.goods_id'));

        if ($goods_id) {
            // 验证登录的token
            $this->verifyUserToken($token, $User, $res_token);

            $uid          = $res_token['uid'];

            // 判断是否收藏
            $GoodsCollect = new \Common\Model\GoodsCollectModel();
            $res_c        = $GoodsCollect->where("goods_id='$goods_id' and user_id='$uid'")->getField('id');

            $is_collect   = $res_c ? 'Y' : 'N';

            $this->ajaxSuccess(['is_collect' => $is_collect]);
        }

        $this->ajaxError();
    }
}
?>
