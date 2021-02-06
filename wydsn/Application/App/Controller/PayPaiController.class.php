<?php

namespace App\Controller;

use App\Common\Controller\AuthController;

class PayPaiController extends AuthController
{
    private $getTokenUrl = PAYPAL_GETTOKEN;            // 获取token地址
    private $createOrderUrl = PAYPAL_CREATEORDERURL;      // 创建订单地址
    private $clientId = PAYPAL_CLIENTID;
    private $clientSecret = PAYPAL_CLIENTSECRET;
    private $checkOrderUrl = PAYPAL_CHECKORDERURL;

    /**
     * paypai支付
     * @param string $order :保证金订单
     * @param string $total :保证金金额
     * @param string $payment :保证金支付方式
     * @param string $type :1 保证金，2 鹿角充值
     * @return mixed
     */
    public function pay($order = '', $total = '', $payment = '', $type = '')
    {
        $method = trim(I('post.method'));
        $orderid = trim(I('post.order_id')); //订单号
        $this->verifyUserToken($token, $User, $res_token);
        if ($type) {
            $orderid = $order;
            $method = $payment;
        }
//        if ($method && $orderid) {
//
//            $OrderModel = new \Common\Model\OrderModel();
//            if (!$type) {
//                $ord = $OrderModel->where(['id' => $orderid, 'status' => 1])->field('allprice')->find();
//            } else {
//                $ord['allprice'] = $total;
//            }
//            if (empty($ord)) {
//                $this->ajaxError('', '无效的订单');
//            }
//            if (!$type) {
//                $orderid = 'zy_'.$orderid;
//            }
//
//            // 获取token
//            $postfilds = "grant_type=client_credentials";
//            $header = array(
//                "Content-Type: application/x-www-form-urlencoded",
//                "Accept-Language: en_US"
//            );
//            $userpw = $this->clientId . ":" . $this->clientSecret;
//
//            $resp = $this->httpPost($this->getTokenUrl, $postfilds, $header, $userpw);
//
//            $result = json_decode($resp, true);
//
//            // 创建订单
//            if ($result['access_token']) {
//
//                $postfildsa = [
//                    'intent' => 'CAPTURE',
//                    'purchase_units' => [
//                        '0' => [
//                            'amount' => [
//                                'currency_code' => 'USD',
//                                'value' => $ord['allprice'] / 100,
//                            ]
//                        ]
//                    ],
//                    'application_context' => [
//                        'cancel_url' => WEB_URL . '/app.php?c=PayPai&a=ppcallback',
//                        'return_url' => WEB_URL . '/app.php?c=PayPai&a=ppcallback&ord=' . $orderid,
//                    ]
//                ];
//
//                $jsonData = json_encode($postfildsa);
//
//                $header = array(
//                    'Authorization: Bearer ' . $result['access_token'],
//                    'Content-Type: application/json'
//                );
//
//                $res = $this->httpPost($this->createOrderUrl, $jsonData, $header, false);
//                $results = json_decode($res, true);
//                if ($results['status'] == 'CREATED') {
//                    foreach ($results['links'] as $k => $v) {
//                        if ($v['rel'] != 'approve') unset($results['links'][$k]);
//                    }
//                    $results['payUrl'] = $results['links'][1]['href'];
//                    unset($results['links']);
//                    if ($type) {
//                        $order_num = substr($order, 3);//截取掉前3位
//                        $results['order_id'] = $order_num;
//                        return $results;
//                    } else {
//                        $this->ajaxSuccess($results);
//                    }
//                } else {
//                    $this->ajaxError($results['name'], $results['message']);
//                }
//            } else {
//                $this->ajaxError($result['error'], $result['error_description']);
//            }
//        }
        if ($method && $orderid) {
            $OrderModel = new \Common\Model\OrderModel();
            if (!$type) {
                $ord = $OrderModel->where(['id' => $orderid, 'status' => 1])->field('allprice')->find();
                $ord['allprice'] = $ord['allprice']/100;
            } else {
                $ord['allprice'] = $total;
            }
            if (empty($ord)) {
                $this->ajaxError('', '无效的订单');
            }
            if (!$type) {
                $orderid = 'zy_' . $orderid;
            }
            $data = [
                'cmd' => '_xclick',
                'business' => 'sb-pkwx03396455@business.example.com',
                'item_name' => 'paypal',
                'item_number' => $orderid,
                'amount' => $ord['allprice'],
                'currency_code' => 'USD',
                'return' => WEB_URL . '/app.php?c=PayPai&a=ppcallback',
                'notify_url' => WEB_URL . '/app.php?c=PayPai&a=ppcallback',
                'cancel_return' => WEB_URL . '/app.php?c=PayPai&a=ppcallback',
                'invoice' => $orderid,
            ];
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            $res = $this->sendJsonData($url, $data);
            if (strpos($res, 'token') !== false) {
                $results['payUrl'] = strstr($res, 'http');
                if ($type) {
                    $order_num = substr($order, 3);//截取掉前3位
                    $results['order_id'] = $order_num;
                    return $results;
                } else {
                    $this->ajaxSuccess($results);
                }
            } else {
                $this->ajaxError('', '请求失败', []);
            }
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 发送http请求
     * @param $url
     * @param $postfilds
     * @param $header
     * @param false $userpw
     * @return mixed
     */
    private function httpPost($url, $postfilds, $header, $userpw = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if ($userpw) {
            curl_setopt($ch, CURLOPT_USERPWD, $userpw);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfilds);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 回调
     */
    public function ppcallback()
    {
        $data = $_POST;
        if ($data) {
            writeLog(json_encode($data), 'pay1334');
        } else {
            $content = file_get_contents("php://input");
            writeLog($content, 'pay1333');
            $verify = $this->verifyNotify($content);
            if ($verify) {
                header("HTTP/1.1 200 OK");
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        }




//        //ipn验证
//        $data = $_POST;
//        $data['cmd'] = '_notify-validate';
//        $url = config('paypal.gateway');//支付异步验证地址
//        $res = https_request($url,$data);
//        //记录支付ipn验证回调信息
//        log_result($res,'paypal');
//
//        if (!empty($res)) {
//            if (strcmp($res, "VERIFIED") == 0) {
//
//                if ($_POST['payment_status'] == 'Completed' || $_POST['payment_status'] == 'Pending') {
//                    //付款完成，这里修改订单状态
//                    $order_res = $this->order_pay($_POST);
//                    if(!$order_res){
//                        log_result('update order result fail','paypal');
//                    }
//                    return 'success';
//                }
//            } elseif (strcmp($res, "INVALID") == 0) {
//                //未通过认证，有可能是编码错误或非法的 POST 信息
//                return 'fail';
//            }
//        } else {
//            //未通过认证，有可能是编码错误或非法的 POST 信息
//
//            return 'fail';
//
//        }
//        return 'fail';
    }

    /**
     * 验证订单
     * @param $post
     * @return bool
     */
    private function verifyNotify($post)
    {
        if (empty($post)) {
            return false;
        }

        $post2 = json_decode($post, true);
        if ($post2['resource']['state'] != 'completed') {
            return false;
        }
        $order_sn = $post2['resource']['invoice_number']; //收取订单号
        $payment_id = $post2['resource']['parent_payment'];
        $pay_order = $post2['resource']['parent_payment'];
        $money = $post2['resource']['amount']['total'];

        // 查询订单信息获取,token
        $postfilds = "grant_type=client_credentials";
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Accept-Language: en_US"
        );
        $userpw = $this->clientId . ":" . $this->clientSecret;

        $tokens = $this->httpPost($this->getTokenUrl, $postfilds, $header, $userpw);

        $token = $tokens['access_token'];

        //查询是否有订单
        $order = $this->get_curlOrder($pay_order, $token);
        $state = $order['transactions'][0]['related_resources'][0]['sale']['state'];
        if ($state == 'completed') {
            $OrderModel = new \Common\Model\OrderModel();
            $ord = $OrderModel->where(['id' => $post2['ord'], 'status' => 1])->getField('id');
            if ($ord) {
                $OrderModel->where(['id' => $ord])->save(['status' => 2]);
            }
            return true;
        } else {
            return false;
        }
    }

    // 根据token查询订单状态
    public function get_curlOrder($orderid, $token)
    {
        if (empty ($orderid) || empty ($token)) {
            return false;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '0');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '0');

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->checkOrderUrl . '/' . $orderid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$token}",
                "Content-Type:application/json"
            )
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}