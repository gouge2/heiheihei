<?php
/**
 * by 来鹿 www.lailu.shop
 * 拼多多分类管理类
 */
namespace Common\Model;
use Think\Model;

class Commission extends Model
{
    /**
     * 分佣算法
     * 一、礼包体系（不考虑自购）
     *     计算规则如下：
     *          1、可用分佣金额 =礼包实际利润金额;
     *          2、直推：可用分佣金额 * Z（获佣会员所属分组比一级提成）
     *          3、间推：可用分佣金额 * Z（获佣会员所属分组比二级提成）
     *
     **** 统一计算   实际分成佣金 - X（佣金*20%） - Y（佣金*10%） ***
     *
     * 二、导购体系（考虑自购）  ** 当自购用户属于 VIP（一、二级）时，只发放自购佣金 ；当满足双次VIP佣金发放时，退出 **
     *     计算规则如下：
     *          1、自购佣金：实际分成佣金 * Z（获佣会员所属分组比）
     *          2、直推佣金：实际分成佣金 * Z（获佣会员所属分组比）
     *          3、间推佣金：实际分成佣金 * Z（获佣会员所属分组比）
     *          4、团队一级：实际分成佣金 * X（VIP会员且按照所属VIP等级比例）
     *          5、团队二级：实际分成佣金 * Y（VIP会员且按照所属VIP等级比例）
     * 三、直播体系（考虑自购）
     *      *** 1、会员点击锁佣30分钟，超时释放；2、基于选品商品分佣获取实际分佣金额（三方佣金 * L（平台直播带货佣金比）：P）；***
     *     计算规则如下：
     *          ———— 带货机制 ————
     *          1、购买佣金：（P -(X+Y)）* M（直播佣金比）
     *          2、直推佣金：（P -(X+Y)）* N（直推佣金比）
     *          3、间推佣金：（P -(X+Y)）* O（间推佣金比）
     *          ———— 导购机制 ————
     *          4、实际分成佣金 = （P -(X+Y)）* K（购买体系佣金比）
     */

    /**
     * 导购分销
     * @param $id 订单ID
     */
    public function dgCommission($id)
    {

    }
    /**
     * 获取上级用户
     * @param $u_id   当前的用户ID
     */
    private function getUserFather($u_id)
    {
        $userModel = new \Common\Model\UserModel();
        $user = $userModel->getUserMsg($u_id);
        return $user['referrer_id'];
    }

}