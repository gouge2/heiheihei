<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 会员管理
 * 会员余额变动记录管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
use Common\Model\SettingModel;

class UserBalanceRecordController extends AuthController
{
	public function index($user_id='')
	{
		$where='1';
		if($user_id) {
			$where.=" and user_id='$user_id'";
		}
        $User=new \Common\Model\UserModel();
		//用户手机号码
		if(trim(I('get.phone'))){
		    $phone=trim(I('get.phone'));
		    $res_u=$User->where("phone='$phone'")->find();
		    if($res_u['uid']){
		        $user_id=$res_u['uid'];
		        $where.=" and user_id='$user_id'";
		    }else {
		        layout(false);
		        $this->error('查询用户不存在！');
		    }
		}

		$UserBalanceRecord=new \Common\Model\UserBalanceRecordModel();
		$count=$UserBalanceRecord->where($where)->count();
		$per = 15;
		if($_GET['p'])
		{
			$p=$_GET['p'];
		}else {
			$p=1;
		}
		$Page=new \Common\Model\PageModel();
		$show= $Page->show($count,$per);// 分页显示输出
		$this->assign('page',$show);

		$list = $UserBalanceRecord->where($where)->page($p.','.$per)->order('id desc')->select();
        $userMsg = $User->getUserMsg($user_id);
        foreach ($list as $k => $v) {
            $list[$k]['all_money'] = $userMsg['balance']*100;
        }
		$this->assign('list',$list);

		$this->display();
	}

    public function receiveBonus()
    {
        if (IS_POST) {
            layout(false);

            $model_setting = new SettingModel();
            $file = "./Public/inc/config.php";

            $parm = I('post.');
            if ($parm['start_time'] > $parm['end_time']) {
                $this->error('活动范围错误，请重新选择');
                return;
            }
            // 保存
            foreach ($parm as $key => $val) {
                $model_setting->set(strtoupper($key), $parm[$key], $file);
            }

            $this->cacheSetting($file);
            $this->success('更新成功！');
        } else {
            $start_time = $end_time = date ( 'Y-m-d');
            $list['start_time'] = defined('START_TIME') ? START_TIME : $start_time;
            $list['end_time'] = defined('END_TIME') ? END_TIME : $end_time;
            $list['rated_amount'] = defined('RATED_AMOUNT') ? RATED_AMOUNT : '0.88';
            $list['random_amount1'] = defined( 'RANDOM_AMOUNT1') ? RANDOM_AMOUNT1 : '0.88';
            $list['random_amount2'] = defined( 'RANDOM_AMOUNT2') ? RANDOM_AMOUNT2 : '1.88';
            $list['random_amount3'] = defined( 'RANDOM_AMOUNT3') ? RANDOM_AMOUNT3 : '2.18';
            $list['random_amount4'] = defined( 'RANDOM_AMOUNT4') ? RANDOM_AMOUNT4 : '2.28';
            $list['random_amount5'] = defined( 'RANDOM_AMOUNT5') ? RANDOM_AMOUNT5 : '2.68';
            $list['random_amount6'] = defined( 'RANDOM_AMOUNT6') ? RANDOM_AMOUNT6 : '2.88';

            $this->assign('list',$list);
            $this->display();
        }
    }
}
