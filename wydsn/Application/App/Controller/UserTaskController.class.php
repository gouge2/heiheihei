<?php
/**
 * by 翠花 http://http://livedd.com
 * 样式管理接口
 */

namespace App\Controller;

use App\Common\Controller\AuthController;

class UserTaskController extends AuthController
{

    public function index()
    {
        $taskCenterUrl = 'https://c.buuyee.com/api/external';
        $channel = TASK_NAME;  // 渠道
        $channelKey = TASK_PWD;   // 渠道秘钥
        $time = time();

        if (trim(I('get.token'))) {
            //判断用户身份
            $token = trim(I('get.token'));
            $User = new \Common\Model\UserModel();
            $res_token = $User->checkToken($token);
            if ($res_token['code'] != 0) {
                //用户身份不合法
                $res = $res_token;
            } else {

                //获取用户账号信息
                $uid = $res_token['uid'];
                $UserMsg = $User->getUserMsg($uid);
                $phone = $UserMsg['phone']; // 手机号码

                // 跳转
                $signature = md5($phone . $channel . $channelKey);
                $url = "{$taskCenterUrl}?phone={$phone}&channel={$channel}&time={$time}&signature={$signature}";
                header("Location: $url");
            }
        } else {
            //参数不正确，参数缺失
            $res = array(
                'code' => $this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}
