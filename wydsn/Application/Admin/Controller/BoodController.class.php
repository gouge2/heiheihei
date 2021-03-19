<?php
/**
 * by 翠花 http://www.lailu.shop
 * 提现管理
 */

namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class BoodController extends AuthController
{
    public function index()
    {
        $page = I('get.p', self::$page);
        //获取银行列表
        $Bood = new \Common\Model\BoodModel();
        $Merch = new \Common\Model\ShopMerchUserModel();
        $User = new \Common\Model\UserDetailModel();
        $Page = new \Common\Model\PageModel();
        $boodLogModel = new \Common\Model\BoodLogModel();
        $whe['pay_status'] = array('in', [0,1]);
        $whe['type'] = 'exchange';
        $count = $boodLogModel->where(['pay_status' => 0,'type'=>'exchange'])->count();
        $show = $Page->show($count, self::$limit);    // 分页显示输出
        $data = $Bood->getBoodList($whe,$page, self::$limit);
        foreach ($data as $k => $v) {
            $list[] = $v;
            $list[$k]['meid'] = $Merch->where(['openid' => 'lailu' . $v['user_id']])->getField('id') ?: '';
            $list[$k]['name'] = $User->where(['user_id' => $v['user_id']])->getField('nickname');
            unset($list[$k]['user_id']);
        }

        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->display();
    }


    //修改显示状态
    public function mod($id, $status)
    {
        $boodLogModel = new \Common\Model\BoodLogModel();
        $res = $boodLogModel->where("id='$id'")->save(['pay_status' => $status,'pay_time'=>date("Y-m-d H:i:s")]);
        if ($res === false) {
            echo '0';
        } else {
            echo '1';
        }
    }
}

?>