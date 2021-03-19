<?php
/**
 * by 翠花 www.lailu.shop
 * 支付宝异步通知
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BoodController extends AuthController
{

    #保证金管理
    public function getInfo()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        $boodModel = new \Common\Model\BoodModel();
        $multiModel = new \Common\Model\MultiMerchantModel();
        $shopModel = new \Common\Model\ShopMerchUserModel();

        $bood = $boodModel->getOne(['user_id'=>$res_token['uid']]);
        $msg['is_sub'] = empty($bood)?0:1;
        $msg['bood_money'] = empty($bood)?0:$bood['bood']+$bood['bood_change'];
        $multi = $multiModel->where('id=1')->find();
        $msg['description'] = empty($multi)?'':$multi['description'];
        $msg['bood'] = empty($multi)?0:$multi['total_amount'];
        #门店信息获取
        $shop = $shopModel->getOne(['openid'=>'lailu_'.$res_token['uid']]);
        $msg['is_exchange'] = (!empty($shop) && $shop['accounttime']>time() && $bood['bood_change']>0)?1:0;

        $this->ajaxSuccess($msg);
    }

    #保证金记录
    public function getBoodList()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $type= !trim(I('post.type'))?'add':trim(I('post.type'));

        $boodLogModel = new \Common\Model\BoodLogModel();
        //分页
        $p 				= trim(I('post.p')) ? trim(I('post.p')) : 1;
        $per 			= trim(I('post.per')) ? trim(I('post.per')) : 10;
        $where ="user_id=".$res_token['uid']." and type='{$type}'";
        if($type =='add')
        {
            $where.=' and pay_status=1';
        }
        $list = $boodLogModel->where($where)->page($p,$per)->field(['bood_money','pay_time','bood_money','type'])->select();
        if($list !== false)
        {
            $num = count($list);
            for ($i=0;$i<$num;$i++)
            {
                $text = $list[$i]['type']=='add'?'充值':'提现';
                $list[$i]['content'] = '保证金'.$text;
            }
            $res = [
                'code' 	=> $this->ERROR_CODE_COMMON['SUCCESS'],
                'msg'	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
                'data' 	=> ['list' => $list]
            ];
        }else{
            //数据库错误
            $res = [
                'code' 	=> $this->ERROR_CODE_COMMON['DB_ERROR'],
                'msg' 	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
            ];
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
        exit;
    }

    #保证金提取--信息
    public function boodInfo()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);
        $boodModel = new \Common\Model\BoodModel();
        $bood = $boodModel->getOne(['user_id'=>$res_token['uid']]);

        $msg['bood_money'] = $bood['bood_change'];
        $this->ajaxSuccess($msg);
    }

    #保证金提取--提交
    public function exchangeBood()
    {
        // 验证登录的token
        $this->verifyUserToken($token, $User, $res_token);

        $boodModel = new \Common\Model\BoodModel();
        $bood = $boodModel->getOne(['user_id'=>$res_token['uid']]);
        if($bood)
        {
            $money= trim(I('post.money'));
            $account_mobile= trim(I('post.account_mobile'));
            $account_name = trim(I('post.account_name'));
            if(!empty($account_mobile) && !empty($account_name)) {
                if ($money) {
                    if ($bood['bood_change'] >= $money) {
                        #做提现处理
                        $boodChangeModel = new \Common\Model\BoodLogModel();
                        $orderModel = new \Common\Model\OrderModel();
                        $data['user_id'] = $res_token['uid'];
                        $data['log_sn'] = $orderModel->generateOrderNum();
                        $data['bood_money'] = $money;
                        $data['pay_status'] = 0;
                        $data['payment'] = 'alipay';
                        $data['type'] = 'exchange';
                        $data['account_mobile'] = $account_mobile;
                        $data['account_name'] = $account_name;
                        $data['create_time'] = date('Y-m-d H:i:s');
                        $res = $boodChangeModel->add($data);
                        if($res !== false)
                        {
                            $log_id = $boodChangeModel->getLastInsID();
                            #扣除保证金
                            $res_set = $boodModel->where('user_id='.$res_token['uid'])->setDec('bood_change',$money);
                            if($res_set !== false)
                            {
                                $res = [
                                    'code' 	=> $this->ERROR_CODE_COMMON['SUCCESS'],
                                    'msg'	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
                                    'data' 	=> ['id' => $log_id]
                                ];
                            }else{
                                $res = [
                                    'code' 	=> $this->ERROR_CODE_COMMON['DB_ERROR'],
                                    'msg'	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],

                                ];
                            }
                        }else{
                            $res = [
                                'code' 	=> $this->ERROR_CODE_COMMON['DB_ERROR'],
                                'msg'	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']],

                            ];
                        }
                    } else {
                        //余额不足
                        $res = [
                            'code' => $this->ERROR_CODE_USER['BALANCE_INSUFFICIENT'],
                            'msg' => $this->ERROR_CODE_USER_ZH[$this->ERROR_CODE_USER['BALANCE_INSUFFICIENT']]
                        ];
                    }
                } else {
                    //余额不足
                    $res = [
                        'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                        'msg' => '请输入提现金额'
                    ];
                }
            }else{
                //余额不足
                $res = [
                    'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                    'msg' => '请选择打款账户'
                ];
            }
        }else{
            //余额不足
            $res = [
                'code' 	=> $this->ERROR_CODE_COMMON['BALANCE_INSUFFICIENT'],
                'msg' 	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['BALANCE_INSUFFICIENT']]
            ];
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
        exit;
    }
}