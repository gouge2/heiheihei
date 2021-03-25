<?php
/**
 * by 翠花 http://http://livedd.com
 * 微信用户
*/
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Model\UserModel;
use GuzzleHttp\Client;
use WXBizDataCrypt;

class WxUserController extends AuthController
{
    /**
     * 登录
     */
	public function login(){
        $code       = I('get.code');

	    if (!$code) {
	        $this->ajaxError($this->ERROR_CODE_COMMON['PARAMETER_ERROR']);
        }

	    $appid      = APPLET_APPID;
	    $appsecret  = APPLET_APPSECRET;
	    $url        = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appsecret}&js_code={$code}&grant_type=authorization_code";
	    Vendor('autoload');
	    $client = new Client();
        $response = $client->get($url);
        $body = $response->getBody();
        $data = json_decode($body, true);
        if(empty($data) || !isset($data['openid'])){
            $this->ajaxError(1, '登录失败');
        }

        $openid = $data['openid'];
        $user = M('user')->where(['openid'=>$openid])->find();
        if(empty($user)){
            $this->ajaxError(86, '请注册', $data);
        }

        $result = $this->checkUser($user);
        $result = array_merge($result, $data);
        $this->ajaxSuccess($result);
	}

    /**
     * 注册
     */
	public function register(){
	    $userData  = I('post.userData');
	    $openid    = I('post.openid');
	    $auth_code = I('post.auth_code');

        if(!$openid){
            $this->ajaxError(1, 'openid缺失');
        }
        if(empty($userData)){
            $this->ajaxError(1, 'userData缺失');
        }

        $User   = new \Common\Model\UserModel();

        $user   = $User->where(['openid'=>$openid])->find();

        if(empty($user)){
            $info = [];
            if($auth_code){
                $refer_user = $User->where(['auth_code'=>$auth_code])->find();
                if(empty($refer_user)){
                    $this->ajaxError(1, '邀请人错误');
                }
                $info['referrer_id']   = $refer_user['uid'];
            }

            $code = M('user_auth_code')->where(['is_used'=>'N'])->find();
            if(empty($code)){
                $this->ajaxError(1, '不能分配邀请码');
            }

            $info['group_id'] = 1;
            $info['openid']   = $openid;
            $info['register_time'] = date("Y-m-d H:i:s", time());
            $info['register_ip']   = getIP();
            $info['auth_code_id']  = $code['id'];
            $info['auth_code'] = $code['auth_code'];
            $uid = $User->add($info);
            if(!$uid){
                $this->ajaxError(1, '注册失败');
            }

            M('user_auth_code')->where(['id'=>$code['id']])->save(['is_used'=>'Y', 'user_id'=>$uid]);
            $info['uid'] = $uid;

            $user_detail = M('user_detail')->where(['user_id'=>$uid])->find();
            $detail = [];
            $detail['user_id']  = $uid;
            $detail['nickname'] = $userData['nickName'];
            $detail['avatar']   = $userData['avatarUrl'];
            $detail['avatar']   = str_replace("http://", "https://", $detail['avatar']);    //转化为https
            $detail['sex']      = $userData['gender'];
            if(empty($user_detail)){
                M('user_detail')->add($detail);
            }else{
                M('user_detail')->where(['user_id'=>$uid])->save($detail);
            }

            // 修改用户团队路径
            $path   = $User->getPath($uid);
            $User->where(['uid' => $uid])->save(['path' => $path]);

            $info['phone'] = '';
            $result = $this->checkUser($info);
            $this->ajaxSuccess($result);
        }

        $result = $this->checkUser($user);
        $this->ajaxSuccess($result);
    }

    /**
     * 检查手机号码是否存在
     */
    public function checkPhone(){
        $phoneData = I('post.phoneData');
        if(empty($phoneData)){
            $this->ajaxError(1, 'phoneData缺失');
        }
        vendor('Weixin.wxBizDataCrypt');
        $config = C('wx_mini_program');
        $appid = $config['appid'];
        $pc = new WXBizDataCrypt($appid, $phoneData['sessionKey']);
        $errCode = $pc->decryptData($phoneData['encryptedData'], $phoneData['iv'], $phone);
        if($errCode){
            $this->ajaxError(1, '获取手机号码失败');
        }
        $phone = json_decode($phone, true);
        if(empty($phone) || !isset($phone['phoneNumber'])){
            $this->ajaxError(1, '获取手机号码失败');
        }

        $user = M('user')->where(['phone' => $phone['phoneNumber']])->find();
        $result = [];
        $result['phone'] = $phone['phoneNumber'];
        $result['sessionKey'] = $phoneData['sessionKey'];

        if ($user) {
            $result['username'] = $user['uid'];
        }

        $this->ajaxSuccess($result);
    }

    /**
     * 绑定手机号码
     */
    public function bindPhone(){
        $openid     = I('post.openid');
        $phone      = I('post.phone');
        if(!$openid || !$phone){
            $this->ajaxError(1, '参数错误');
        }
        $user = M('user')->where(['openid' => $openid])->find();
        if(empty($user)){
            $this->ajaxError(1, '用户不存在');
        }

        $phone_user = M('user')->where(['phone' => $phone])->find();

        if (empty($phone_user)) {
            $info = [];
            $user['phone']    = $info['phone']      = $phone;
            $user['username'] = $info['username']   = $phone;

            if (!$user['referrer_id']) {
                $auth_code = I('post.auth_code');
                if(!$auth_code && INVITE_CODE == 1){
                    $this->ajaxError(1, '没有邀请人');
                }elseif($auth_code){
                    $refer_user = M('user')->where(['auth_code'=>$auth_code])->find();
                    if(empty($refer_user)){
                        $this->ajaxError(1, '邀请人错误');
                    }
                    $user['referrer_id'] = $info['referrer_id']   = $refer_user['uid'];
                }
            }
            M('user')->where(['uid'=>$user['uid']])->save($info);
            $uid    = $user['uid'];
        }else{
            $info = [];
            $info['openid'] = $openid;
            M('user')->where(['uid'=>$phone_user['uid']])->save($info);
            if ($user['uid'] != $phone_user['uid']) {
                M('user')->where(['uid'=>$user['uid']])->delete();
                M('user_detail')->where(['user_id' => $user['uid']])->delete();
                M('user_auth_code')->where(['user_id'=>$user['uid']])->delete();
            }
            $user = $phone_user;
            $uid  = $phone_user['uid'];
        }

        $User   = new \Common\Model\UserModel();

        // 修改用户团队路径
        $path   = $User->getPath($uid);
        $User->where(['uid' => $uid])->save(['path' => $path]);

        $result = $this->checkUser($user);
        return $this->ajaxSuccess($result);
    }

    public function spbill(){

    }

    public function test() {
	    $WxServicePushModel = new \Common\Model\WxServicePushModel();

	    $data = $WxServicePushModel->push('draw', array('100', '5%', '100', '测试推送'), 'o7Ad55CahJ0Sd575vi7g-cF4gplA');
    }

    /**
     * 绑定邀请人上级关系
     */
    public function bindUser() {
        $token = I('post.token');
        $auth_code = I('post.auth_code');
        if(!$token || !$auth_code){
            $this->ajaxError(1, '参数错误');
        }
        $User = new UserModel();
        $UserAuthCode = new \Common\Model\UserAuthCodeModel();

        // 验证token
        $token_info = $User->checkToken($token);
        if ($token_info['code'] !== 0) {
            $this->ajaxReturn($token_info);
        }
        $uid = $token_info['uid'];
        $UserCode = $UserAuthCode->field(['auth_code'])->where("user_id='$uid'")->find();
        if ($UserCode) {
            $this->ajaxSuccess($UserCode,'用户已绑定邀请码');
        }

        // 验证邀请码
        $refer_user = $User->where(['auth_code'=>$auth_code])->find();
        if(empty($refer_user)){
            $this->ajaxError(1, '邀请码不存在');
        }
        $userInfo['referrer_id'] = $refer_user['uid'];

        // 绑定邀请码
        // 查询第一个未使用的邀请码
        $codeMsg = $UserAuthCode->where("is_used='N'")->order('id asc')->find();
        $userInfo['auth_code'] = $codeMsg['auth_code'];

        // 绑定用户邀请人和邀请码
        $resUser = $User->where(['uid' => $uid,'auth_code' => ''])->save($userInfo);
        if (!$resUser) {
            $this->ajaxError(2,'绑定失败');
        }

        // 更新邀请码状态
        $resCode = $UserAuthCode->where(['id' => $codeMsg['id']])->save([
            'is_used' => 'Y',
            'user_id' => $uid
        ]);
        if (!$resCode) {
            $this->ajaxError(3,'邀请码更新失败');
        }

        return $this->ajaxSuccess([],'绑定成功');
    }

    /**
     * 检查用户
     * @param $user
     * @return array
     */
    private function checkUser($user){
        if(!$user['path']){
            $User = new UserModel();
            $path=$User->getPath($user['uid']);
            M('user')->where(['uid' => $user['uid']])->save(['path'=>$path]);
        }

        //用户信息不全
        if(!$user['phone']){
            $data = [];
            $data['phone'] = $user['phone'];
            $data['refer_code'] = '';
            if($user['referrer_id']){
                $refer = M('user')->where(['uid'=>$user['referrer_id']])->find();
                if(!empty($refer)){
                    $data['refer_code'] = $refer['auth_code'];
                }
            }
            $data['need_invite_code'] = INVITE_CODE;
            return $data;
        }

        //token已经存在不刷新
        $token = [];
        if($user['token']){
            $token['token'] = $user['token'];
        }else{
            $user_model = new UserModel();
            $token['token'] = $user_model->getAccessToken($user['uid']);
            M('user')->where(['uid' => $user['uid']])->save($token);
        }
        return $token;
    }
}
?>