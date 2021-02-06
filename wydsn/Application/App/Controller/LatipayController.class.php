<?php

namespace App\Controller;

use App\Common\Controller\AuthController;
use stdClass;

class LatipayController extends AuthController
{
    protected $url = LATIPAY_URL;  //支付网关
    protected $prekey = LATIPAY_KEY;  //秘钥
    protected $walletid = LATIPAY_WALLET; //钱包id
    protected $userid = LATIPAY_UID; //用户id

    /**
     * latipay支付
     * @param string $order :保证金订单
     * @param string $total :保证金金额
     * @param string $payment :保证金支付方式
     * @param string $type :1 保证金，2 鹿角充值
     * @return array|bool|float|int|mixed|stdClass|string|null
     */
    public function pay($order = '', $total = '', $payment = '', $type = '')
    {
        $method = trim(I('post.method'));
        $orderid = trim(I('post.order_id'));
        $product_name = trim(I('post.product_name'));
        $this->verifyUserToken($token, $User, $res_token);

        if ($type) {
            $orderid = $order;
            $method = $payment;
        }

        if ($method && $orderid) {
            $OrderModel = new \Common\Model\OrderModel();
            if (!$type) {
                $ord = $OrderModel->where(['id' => $orderid, 'status' => 1])->field('allprice')->find();
            } else {
                $ord['allprice'] = $total;
            }

            if (empty($ord)) {
                $this->ajaxError('', '无效的订单');
            }
            if ($method == 'int_wx') {
                $methods = 'wechat';
            } elseif ($method == 'int_ali') {
                $methods = 'alipay';
            }
            if (!$type) {
                $orderid = 'zy_' . $orderid;
            }
            // 请求参数
            $data = [
                'user_id' => $this->userid, //用户id
                'wallet_id' => $this->walletid, //钱包id
                'payment_method' => $methods, //支付方式 wechat, alipay, onlineBank, or polipay
                'amount' => $this->exchangeRate($ord['allprice'], $type), //金额
                'return_url' => WEB_URL . '/app.php?c=Latipay&a=retunUrl', //同步跳转地址
                'callback_url' => WEB_URL . '/app.php?c=Latipay&a=latipayCallback', //异步通知地址
                'merchant_reference' => $orderid, //一个unique id识别商家的系统的顺序
                'ip' => '127.0.0.1', //用户ip
                'version' => '2.0', //接口版本
                'product_name' => $product_name ?: 'latipay', //商品名称
            ];

            // 微信二维码
            if ($data['payment_method'] == 'wechat') $data['present_qr'] = 1;

            // 排序
            ksort($data);

            // 加密
            $data['signature'] = hash_hmac('sha256', urldecode(http_build_query($data)) . $this->prekey, $this->prekey);

            // 发送支付请求
            $res = $this->sendJsonData($this->url, $data);
            $result = json_decode($res, true);

            // 结果解析
            if ($result['message'] == 'SUCCESS') {
                $order_num = substr($data['merchant_reference'], 3);//截取掉前3位
                $result['order_id'] = $order_num;
                if ($order) {
                    return $result;
                } else $this->ajaxSuccess($result);
            } else {
                $this->ajaxError($result['code'], $result['messageCN'], $result);
            }
        } else {
            $this->ajaxError();
        }
    }

    /**
     * 请求支付
     * @param $url
     * @param $data
     * @return mixed
     */
    protected function sendJsonData($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    // 信任任何证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);        // 表示不检查证书
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 回调
     */
    public function latipayCallback()
    {
        $data = $_POST;
        if ($data) {
            writeLog(json_encode($data), 'pay1331');
        }
        ksort($data);
        $signStr = $data['merchant_reference'] . $data['payment_method'] . $data['status'] . $data['currency'] . $data['amount'];
        $mySign = hash_hmac('sha256', $signStr, $this->prekey);
        if (strtoupper($data['signature']) == strtoupper($mySign) && $data['status'] == 'paid') {
            echo 'sent';
            $type = substr($data['merchant_reference'], 0, 2);//截取前2位
            //订单号
            $order_num = substr($data['merchant_reference'], 3);//截取掉前3位

            $pay_method = '';
            if ($data['payment_method'] == 'wechat') {
                $pay_method = 'int_wx';
            } elseif ($data['payment_method'] == 'alipay') {
                $pay_method = 'int_ali';
            }

            switch ($type) {
                case 'zy':   // 订单支付
                    $OrderModel = new \Common\Model\OrderModel();
                    $ord = $OrderModel->where(['id' => $order_num, 'status' => 1])->getField('id');
                    if ($ord) {
                        $OrderModel->where(['id' => $ord])->save(['status' => 2, 'pay_method' => $pay_method, 'pay_time' => date("Y-m-d H:i:s")]);
                    }
                    break;
                case 'll':  // 鹿角充值
                    $FillRecord = new \Common\Model\FillRecordModel();
                    $fr_one = $FillRecord->field('id,user_id,redeem')->where(['fill_num' => $order_num, 'is_status' => 'not'])->find();
                    if ($fr_one) {
                        $User = new \Common\Model\UserModel();
                        $User->where(['uid' => $fr_one['user_id']])->setInc('ll_balance', $fr_one['redeem']);
                        $FillRecord->where(['id' => $fr_one['id']])->save(['is_status' => 'succ', 'pay_method' => $pay_method, 'pay_time' => date("Y-m-d H:i:s")]);

                    }
                    break;
                case 'bp': //缴纳保证金
                    $boodlog = new \Common\Model\BoodLogModel();
                    $ord = $boodlog->where(['log_sn' => $order_num, 'pay_status' => 0])->getField('id');
                    if ($ord) {
                        $boodlog->where(['log_sn' => $order_num])->save(['pay_status' => 1, 'payment' => $pay_method, 'pay_time' => date("Y-m-d H:i:s")]);
                    }
                    break;
                case 'v1': //会员升级
                    $UserGroupRecharge = new \Common\Model\UserGroupRechargeModel();
                    $UserGroupRecharge->treatUpgrade($order_num, $pay_method);
                    break;
            }
        }
    }

    /**
     * 同步跳转
     */
    public function retunUrl()
    {
        $msg = '';
        switch ($_POST['status']) {
            case 'pending':
                $msg = '等待支付中';
                break;
            case 'paid':
                $msg = '支付成功';
                break;
            case 'failed':
                $msg = '支付失败';
                break;
        }
        $this->ajaxSuccess($_POST, $msg);
    }

    /**
     * 金额处理
     * @param $amount
     * @param $type
     * @return string
     */
    protected function exchangeRate($amount, $type)
    {
        $my_exchange = defined('MY_EXCHANGE') ? MY_EXCHANGE : "";
        $data = file_get_contents("http://www.baidu.com/s?wd=NZD%20CNY&rsv_spt=1");
        preg_match("/<div>1\D*=(\d*\.\d*)\D*<\/div>/", $data, $conver);
        $converteds = preg_replace("/[^0-9.]/", "", $conver[1]);
        $converted = $my_exchange ?: $converteds;
        if ($type) {
            return sprintf("%.2f", $amount / $converted);
        } else {
            return sprintf("%.2f", ($amount / 100) / $converted);
        }
    }

    /**
     * 订单支付状态查询
     */
    public function checkorder()
    {
        $orderid = trim(I('post.order_id'));
        $type = trim(I('post.type'));    // 1:订单支付，2：鹿角充值,3保证金支付,会员升级
        $this->verifyUserToken($token, $User, $res_token);
        if ($type && $orderid) {
            switch ($type) {
                case '1':
                    $OrderModel = new \Common\Model\OrderModel();
                    $status = $OrderModel->where(['id' => $orderid])->getField('status');
                    if ($status && in_array($status, [1, 2])) {
                        $this->ajaxSuccess(['payment_status' => $status]);
                    } else {
                        $this->ajaxError();
                    }
                    break;
                case '2':
                    $FillRecord = new \Common\Model\FillRecordModel();
                    $fr_one = $FillRecord->where(['fill_num' => $orderid])->field('is_status')->find();
                    if ($fr_one) {
                        $status = 1;
                        if ($fr_one['is_status'] == 'succ') {
                            $status = 2;
                        }
                        $this->ajaxSuccess(['payment_status' => $status]);
                    } else {
                        $this->ajaxError();
                    }
                    break;
                case '3':
                    $boodlog = new \Common\Model\BoodLogModel();
                    $ord = $boodlog->where(['log_sn' => $orderid, 'pay_status' => 0])->field('pay_status')->find();
                    if ($ord) {
                        $status = 1;
                        if ($ord['pay_status']) {
                            $status = 2;
                        }
                        $this->ajaxSuccess(['payment_status' => $status]);
                    } else {
                        $this->ajaxError();
                    }
                    break;
                case '4':
                    $UserGroupRecharge = new \Common\Model\UserGroupRechargeModel();
                    $res = $UserGroupRecharge->where("order_num='$orderid'")->field('is_pay')->find();
                    if ($res) {
                        $status = 1;
                        if ($res['is_pay'] == 'Y') {
                            $status = 2;
                        }
                        $this->ajaxSuccess(['payment_status' => $status]);
                    }
                    break;
            }
        } else {
            $this->ajaxError();
        }
    }
}