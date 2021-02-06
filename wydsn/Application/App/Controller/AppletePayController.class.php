<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/11/13
 * Time: 16:15
 */

namespace App\Controller;


use App\Common\Controller\AuthController;
use Common\Model\UserBalanceRecordModel;
use Common\Model\UserDrawApplyModel;
use Common\Model\UserModel;
use Common\Model\UserPayModel;
class AppletePayController extends AuthController
{

    /**
     * 小程序支付宝接口
     */
    public function AppletsDraw()
    {
        // 验证提现时间，每个月规定的时间段才可以提现
        $today = date('d');
        if ($today < DRAW_START_DATE && $today > DRAW_END_DATE) {
            $this->ajaxReturn([
                'code' => 1,
                'msg' => '每个月的' . DRAW_START_DATE . '号-' . DRAW_END_DATE . '号可以提现'
            ]);
        }

        if (!I('post.token') || !I('post.account') || !I('post.truename') || !I('post.pay_type') || !I('post.money')) {
            $this->ajaxReturn([
                'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            ]);
        }
        // 1.验证用户信息
        $token = I('post.token');
        $User = new UserModel();
        $resToken = $User->checkToken($token);
        if ($resToken['code'] !== 0) {
            $this->ajaxReturn($resToken);
        }
        $uid = $resToken['uid'];

        // 4.最低5元提现，超过300，人工审核。
        $money = floatval(I('post.money'));
//        $money = 0.2;   // 暂时测试使用
        $money = floor($money*100)/100;

        if ($money < DRAW_LIMIT_MONEY) {
            $this->ajaxReturn([
                'code' => $this->ERROR_CODE_USER['WITHDRAWAL_AMOUNT_MUST_BE_A_MULTIPLE_OF_10'],
                'msg' => '提现金额必须大' . DRAW_LIMIT_MONEY . '元'
            ]);
        }
        // 检测余额是否足够
        $UserInfo = $User->getUserMsg($uid);
        if ($UserInfo['balance'] < $money) {
            $this->ajaxReturn([
                'code'=>$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT'],
                'msg'=>$this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT']]
            ]);
        }

        // 判断今天是否已提现,每天只准提现一次
        // 暂时去掉
        $UserDrawApply = new UserDrawApplyModel();
//        $where = "TO_DAYS(apply_time) = TO_DAYS(NOW()) and user_id='$uid'";
//        $res_exist = $UserDrawApply->where($where)->find();
//        if ($res_exist) {
//            $this->ajaxReturn([
//                'code' => $this->ERROR_CODE_USER['WITHDRAWAL_ONLY_ONCE_A_DAY'],
//                'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['WITHDRAWAL_ONLY_ONCE_A_DAY']]
//            ]);
//        }


        // 手续费


        $draw_fee = floor($money*DRAW_FEE)/100;

        // 实际提现金额
        $real_money = $money - $draw_fee;
        $data = array(
            'user_id' => $uid,
            'money' => $money,
            'account_type' => I('post.pay_type'),
            'account' => I('post.account'),
            'truename' => I('post.truename'),
            'draw_fee' => $draw_fee,
            'real_money' => $real_money,
            'apply_time' => date('Y-m-d H:i:s'),

        );
        //判断提现方式为3自动提现，并且提现金额不超过限制的方可自动转账，其他都需要后台审核

        if (DRAW_METHOD == '3' || $money <= DRAW_AUTO_MONEY) {
            $data['is_check'] = 'Y';
            $data['check_result'] = 'Y';
            $data['check_time'] = date('Y-m-d H:i:s');

            //开启事务
            $UserDrawApply->startTrans();
            $res_add = $UserDrawApply->add($data);
            //修改用户余额
            $res_balance = $User->where("uid='$uid'")->setDec('balance', $money);
            //记录用户余额变动记录
            $UserBalanceRecord = new UserBalanceRecordModel();
            $all_money = $UserInfo['balance'] - $money;
            //保留2位小数，四舍五入
            $all_money = round($all_money, 2);
            $res_record = $UserBalanceRecord->addLog($uid, $money, $all_money, 'draw', '2');

            if (!$res_add || !$res_balance || !$res_record) {
                //回滚
                $UserDrawApply->rollback();
                $this->ajaxReturn([
                    'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
                    'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                ]);
            }

            // 转到支付宝,获取支付宝请求参数
            Vendor('pay.alipayApp', '', '.class.php');
            $alipayApp = new \alipayApp();
            $out_biz_no = time() . '_' . $res_add;
            $payee_account = trim(I('post.account'));
            $amount = $real_money;
            $payer_show_name = APP_NAME;//付款方姓名
            $payee_real_name = trim(I('post.truename'));//收款方真实姓名
            $res_ali = $alipayApp->fundTransToaccountTransfer($out_biz_no, $payee_account, $amount, $payer_show_name, $payee_real_name);
            if ($res_ali['code'] !== 0) {
                //回滚
                $UserDrawApply->rollback();
                //转账失败
                $error_msg = '支付宝转账失败：' . $res_ali['msg'] . ',账号：' . $payee_account . '，姓名：' . $payee_real_name . json_encode($res_ali);
                writeLog($error_msg);
                $str_index = strpos($res_ali['msg'],"原因");
                $ret_msg = mb_substr($res_ali['msg'],$str_index-11);
                $this->ajaxReturn([
                    'code' => $res_ali['code'],
                    'msg' => $ret_msg
                ]);
            }

            // 提交事务
            $UserDrawApply->commit();
            $this->ajaxReturn([
                'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                'msg' => '提现成功！'
            ]);

        } else {
            $data['is_check'] = 'N';
            $res=$UserDrawApply->add($data);
            if (!$res) {
                $ret = array(
                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                );
            } else {
                $ret = array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'提现申请成功，请等待管理员审核！'
                );
            }

            $this->ajaxReturn($ret);
        }
    }

    /**
     *  添加修改支付宝账号
     */
    public function addAlipayAccount()
    {
        if (!I('post.token') || !I('post.pay_type') || !I('post.account') || !I('post.truename')) {
            $this->ajaxReturn([
                'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            ]);
        }

        $preg_phone='/^1[345789]\d{9}$/';
        if(!preg_match($preg_phone,I('post.account'))){
            $this->ajaxReturn([
                'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg' => '手机号码格式不对'
            ]);
        }

        $User = new UserModel();
        $resToken = $User->checkToken(I('post.token'));
        if ($resToken['code'] !== 0) {
            $this->ajaxReturn($resToken);
        }
        $uid = $resToken['uid'];

        $UserPay = new UserPayModel();
        $res = $UserPay->addPay($uid,I('post.pay_type'),I('post.account'),I('post.truename'));
        if ($res) {
            $ret = array(
                'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                'msg' => '添加成功'
            );
        } else {
            $ret = array(
                'code' => 1,
                'msg' => '账号已存在'
            );
        }

        $this->ajaxReturn($ret);
    }

    /**
     *  获取支付宝账号信息
     */
    public function getPay()
    {
        if (!I('post.token') || !I('post.pay_type')) {
            $this->ajaxReturn([
                'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            ]);
        }

        $User = new UserModel();
        $resToken = $User->checkToken(I('post.token'));
        if ($resToken['code'] !== 0) {
            $this->ajaxReturn($resToken);
        }
        $uid = $resToken['uid'];

        $UserPay = new UserPayModel();
        $res = $UserPay->getPay($uid,I('post.pay_type'));
        if ($res) {
            $ret = $res;
        } else {
            $ret = array(
                'code' => 1,
                'msg' => '无账号'
            );
        }
        $this->ajaxSuccess($ret);
    }
}