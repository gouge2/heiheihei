<?php
/**
 * 苹果用户
*/
namespace App\Controller;

use App\Common\Controller\AuthController;
use Org\appleSignIn\ASDecoder;

class AppleUserController extends AuthController
{
    /**
     * apple授权与登录
     */
    public function authLogin()
    {
        $user_iden              = trim(I('post.userIdentifier'));  	    // 授权的用户唯一标识
		$email                  = trim(I('post.email'));   			    // 邮箱
		$full_name              = trim(I('post.fullName'));			    // 用户信息
		// $code                = trim(I('post.authorizationCode'));    // 授权code 并没有用到
		$iden_token             = trim(I('post.identityToken'));        // 授权用户的JWT凭证

        if ($user_iden && $iden_token) {
            $appleSignInPayload = ASDecoder::getAppleSignInPayload($iden_token);
            $isValid 			= $appleSignInPayload->verifyUser($user_iden);

            // 当 $isValid 为 true 时验证通过，后续逻辑根据需求编写
            if ($isValid) {
                $User           = new \Common\Model\UserModel();

                // 查询用户是否存在
                $one            = $User->field('uid,phone,referrer_id,login_num,group_id')->where(['apple_iden' => $user_iden])->find();

                // 登录处理
                if ($one) {
                    $this->loginDispose($one);

                // 注册处理
                } else {
                    $this->regDispose($user_iden, $email, $full_name); 
                }

            } else {
                $this->ajaxError(['ERROR_CODE_USER' => 'VERIFY_APPLE_FAIL']);
            }
        }

        $this->ajaxError();
    }

    /**
     *  绑定手机号和邀请码
     */
    public function bindMsg()
    {
        // 获取参数
        $tem_token      = trim(I('post.tem_token'));
        $phone          = trim(I('post.phone'));
        $auth_code      = trim(I('post.auth_code'));
        $code           = trim(I('post.code'));
        $zone           = trim(I('post.zone'));

        if ($tem_token && $phone && $auth_code && $code) {
            // 短信验证码验证
            Vendor('mob.mob','','.class.php');
            $mob        = new \mob();
            $mob_code   = $mob->checkSmsCode($phone, $code, $zone);

            if ($mob_code['code'] != 200) {
                $this->ajaxError($mob_code['code'], $mob_code['msg']);
            }

            
            $User       = new \Common\Model\UserModel();

            // 注册的用户时候存在
            $one        = $User->field('uid,phone,referrer_id,apple_iden,login_num,group_id')->where(['tem_token' => $tem_token])->find();
            if (!$one) {    
                $this->ajaxError(['ERROR_CODE_USER' => 'USER_NOT_EXIST']); 
            }

            // 邀请码用户
            $ruid       = $User->where(['auth_code' => $auth_code])->getField('uid');
            if (!$ruid) {    
                $this->ajaxError(['ERROR_CODE_USER' => 'REFERRER_NOT_EXISTS']); 
            }

            // 手机号用户
            $puser      = $User->field('uid,phone,referrer_id,login_num,group_id')->where(['phone' => $phone])->find();

            $update     = [];
            $up_id      = $del_id = $group_id = 0;      // 修改或者删除的用户id

            if ($puser) {
                $up_id                      = $puser['uid'];
                $group_id                   = $puser['group_id'];
                $del_id                     = $one['uid'];
                $update['apple_iden']       = $one['apple_iden'];
                $update['login_num']        = $puser['login_num'] * 1 + 1;

                if (empty($puser['phone'])) {
                    $update['phone']        = $phone;
                }

                if (empty($puser['referrer_id'])) {
                    $update['referrer_id']  = $ruid;
                }

            } else {
                $up_id                      = $one['uid'];
                $group_id                   = $one['group_id'];
                $update['tem_token']        = '';                   // 清空临时时放入的token
                $update['username']         = $phone;
                $update['login_num']        = $one['login_num'] * 1 + 1;

                if (empty($one['phone'])) {
                    $update['phone']        = $phone;
                }

                if (empty($one['referrer_id'])) {
                    $update['referrer_id']  = $ruid;
                }
            } 

            $date                       = date('Y-m-d H:i:s');

            // 登录信息
            $token                      = $User->getAccessToken($up_id);
            $update['token']            = $token;
            $update['token_createtime'] = $date;
            $update['last_login_ip']    = getIp();
            $update['last_login_time']  = $date;

            // 修改
            $User->where(['uid' => $up_id])->save($update);

            // 修改用户团队路径
            $path   = $User->getPath($up_id);
            $User->where(['uid' => $up_id])->save(['path' => $path]);
            
            // 删除的用户
            if ($del_id) {
                $User->where(['uid' => $del_id])->delete();
            }

            // 成功信息
            $login = [
                'uid'      => $up_id,
                'group_id' => $group_id,
                'token'    => $token
            ];

            $this->ajaxSuccess(['user' => $login]);
        }

        $this->ajaxError();
    }

    /**
     * 登录处理
     */
    protected function loginDispose($one)
    {
        if ($one) {
            $User                       = new \Common\Model\UserModel();

            $tem_token                  = $User->getAccessToken($one['uid']);

            if (empty($one['phone']) || empty($one['referrer_id'])) {
                $User->where(['uid' => $one['uid']])->save(['tem_token' => $tem_token]);

                $this->ajaxError(401401, '请绑定手机号和推荐邀请码', ['user' => ['tem_token' => $tem_token]]);
            }

            $date                       = date('Y-m-d H:i:s');

            // 登录信息
            $update                     = [];
            $update['token']            = $tem_token;
            $update['token_createtime'] = $date;
            $update['last_login_ip']    = getIp();
            $update['last_login_time']  = $date;
            $update['login_num']        = $one['login_num'] * 1 + 1;

            // 修改
            $User->where(['uid' => $one['uid']])->save($update);

            // 成功信息
            $login = [
                'uid'      => $one['uid'],
                'group_id' => $one['group_id'],
                'token'    => $tem_token
            ];

            $this->ajaxSuccess(['user' => $login]);
        }

        $this->ajaxError();
    }

    /**
     * 注册处理
     */
    protected function regDispose($apple_iden, $email, $full_name)
    {
        if ($apple_iden) {
            $User           = new \Common\Model\UserModel();
            $UserDetail     = new \Common\Model\UserDetailModel();
            $UserAuthCode   = new \Common\Model\UserAuthCodeModel();

            // 拿一个邀请码
            $code           = $UserAuthCode->where(['is_used'=>'N'])->find();

            $tem_token      = $User->getAccessToken(mt_rand(100, 999));     // 临时token 用于下一个接口绑定手机号

            $info           = [
                'group_id'      => 1,
                'apple_iden'    => $apple_iden,
                'register_time' => date("Y-m-d H:i:s"),
                'register_ip'   => getIP(),
                'auth_code_id'  => $code['id'],
                'auth_code'     => $code['auth_code'],
                'tem_token'     => $tem_token,          // 临时token
            ];

            if ($email) {
                $info['email']  = $email;
            }

            $User->startTrans();   // 启用事务 
            try {
                // 新增用户
                $uid                    = $User->add($info);

                // 新增用户详情
                $detail                 = ['user_id' => $uid, 'sex' => '', 'signature' => ''];
                if ($full_name) {
                    $detail['nickname'] = $full_name;
                }
                $UserDetail->add($detail);

                // 修改邀请码使用
                $UserAuthCode->where(['id' => $code['id']])->save(['is_used' => 'Y', 'user_id' => $uid]);

                // 事务提交
                $User->commit(); 

            } catch(\Exception $e) {
                // 事务回滚
                $User->rollback();

                $this->ajaxError(['ERROR_CODE_COMMON' => 'DB_ERROR']);
            }

            $this->ajaxError(401401, '请绑定手机号和推荐邀请码', ['user' => ['tem_token' => $tem_token]]);
        }

        $this->ajaxError(['ERROR_CODE_USER' => 'NOT_APPLE_IDEN']);
    }
}
?>