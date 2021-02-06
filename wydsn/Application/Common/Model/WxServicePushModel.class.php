<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 微信服务推送
 */
namespace Common\Model;
use Think\Model;
use GuzzleHttp\Client;

class WxServicePushModel extends Model
{
    // 获取模板数据
    public function getType($type) {
        $params = array(
            'draw' => array(
                'template_id' => 'vu2395mSt8kD1g_YeR4TdfTBzKav1jgwgb_8UaGCbSE',
                'page' => 'info/balance_subsidiary/balance_subsidiary?type=2',
                'data' => array(
                    'amount1',
                    'character_string2',
                    'amount3',
                    'thing5',
                )
            ),
            'authentication' => array(
                'template_id' => '354Xkk-9wR5KCehpIjwvFvIaZ5lBHHreFVlkVJCZpVs',
                'page' => 'info/authentication_status/authentication_status',
                'data' => array(
                    'thing2',
                    'phrase1',
                    'date4',
                    'thing12',
                )
            ),
        );

        return $params[$type];
    }

    // 获取微信token
    public function getToken() {
        // 查询库里微信token是否存在
        $data = $this->where(['id' => 1])->find();
        $token = '';

        if (empty($data) || strtotime($data['update_time']) + 700 < time()) {
            $config = C('wx_mini_program');
            $appid = $config['appid'];
            $appsecret = $config['appsecret'];

            $url = "https://api.weixin.qq.com/cgi-bin/token?appid={$appid}&secret={$appsecret}&grant_type=client_credential";
            Vendor('autoload');
            $client = new Client();
            $count = 0;
            while ($count < 5) {
                $response = $client->get($url);
                $body = $response->getBody();
                $data = json_decode($body, true);
                $token = $data['access_token'];
                if (!empty($token)) {
                    $this->where(['id' => 1])->save(['token' => $token, 'update_time' => date('Y-m-d H:i:s')]);
                    break;
                }
                $count++;
            }
        } else {
            $token = $data['token'];
        }

        return $token;
    }

    // 推送信息
    public function push($type, $value, $openid, $is_dev = true) {
        $token = $this->getToken();
        $key = $this->getType($type);

        $url = "https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token={$token}";

        $d = array();
        foreach ($key['data'] as $k => $v) {
            $d[$v] = array(
                'value' => $value[$k]
            );
        }

        $param = array(
            'touser' => $openid,
            'template_id' => $key['template_id'],
            'page' => $key['page'],
            'data' => $d,
        );

        if ($is_dev)
            $param['miniprogram_state'] = 'developer';

        Vendor('autoload');
        $client = new Client();
        $options['body'] = \GuzzleHttp\json_encode($param);
        /*echo "<pre>";
        print_r($options);
        exit;*/
        $response = $client->post($url, $options);
        $body = $response->getBody();
        $data = json_decode($body, true);

        return 1;

    }
}