<?php
namespace App\Common\Controller;
use Think\Controller;

//权限认证
class AuthController extends Controller 
{
	public static $limit   	     		= 10;      		// 每页显示条数
	public static $page    		 		= 1;       		// 当前页
	
	public $ERROR_CODE_COMMON    = array(); // 公共返回码
	public $ERROR_CODE_COMMON_ZH = array(); // 公共返回码中文描述
	public $ERROR_CODE_SMS       = array(); // 手机短信返回码
	public $ERROR_CODE_SMS_ZH    = array(); // 手机短信返回码中文描述
	public $ERROR_CODE_EMAIL     = array(); // 邮件返回码
	public $ERROR_CODE_EMAIL_ZH  = array(); // 邮件返回码中文描述
	public $ERROR_CODE_USER      = array(); // 用户管理返回码
	public $ERROR_CODE_USER_ZH   = array(); // 用户管理返回码中文描述
	public $ERROR_CODE_GOODS     = array(); // 商品管理返回码
	public $ERROR_CODE_GOODS_ZH  = array(); // 商品管理返回码中文描述
	public $ERROR_CODE_SHORT     = array(); // 短视频管理返回码
	public $ERROR_CODE_SHORT_ZH  = array(); // 短视频管理返回码中文描述
	public $ERROR_CODE_LIVE      = array(); // 直播管理返回码
	public $ERROR_CODE_LIVE_ZH   = array(); // 直播管理返回码中文描述
	
	protected function _initialize()
	{
		// 返回码配置
		$this->ERROR_CODE_COMMON    = json_decode(error_code_common,true);
		$this->ERROR_CODE_COMMON_ZH = json_decode(error_code_common_zh,true);
		$this->ERROR_CODE_SMS       = json_decode(error_code_sms,true);
		$this->ERROR_CODE_SMS_ZH    = json_decode(error_code_sms_zh,true);
		$this->ERROR_CODE_EMAIL     = json_decode(error_code_email,true);
		$this->ERROR_CODE_EMAIL_ZH  = json_decode(error_code_email_zh,true);
		$this->ERROR_CODE_USER      = json_decode(error_code_user,true);
		$this->ERROR_CODE_USER_ZH   = json_decode(error_code_user_zh,true);
		$this->ERROR_CODE_GOODS     = json_decode(error_code_goods,true);
		$this->ERROR_CODE_GOODS_ZH  = json_decode(error_code_goods_zh,true);
		$this->ERROR_CODE_SHORT     = json_decode(error_code_short,true);
		$this->ERROR_CODE_SHORT_ZH  = json_decode(error_code_short_zh,true);
		$this->ERROR_CODE_LIVE      = json_decode(error_code_live,true);
		$this->ERROR_CODE_LIVE_ZH   = json_decode(error_code_live_zh,true);
	}

    /**
     * 返回成功信息
     * @param array $data
     */
	protected function ajaxSuccess($data = null, $msg = '')
	{
        $res = array(
            'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'  => ($msg ? $msg : $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']]),
            'data' => $data
		);
		
        echo json_encode($res,JSON_UNESCAPED_UNICODE); exit();
    }

    /**
     * 返回错误信息
     * @param $code
     * @param string $msg
     * @param array $data
     */
	protected function ajaxError($code = '', $msg = '', $data = null)
	{
		$res   = [];

		// 默认 - 参数不正确，参数缺失
		if (!is_array($code)) {
			$res['code'] 	= $code ? $code : $this->ERROR_CODE_COMMON['PARAMETER_ERROR'];
			$res['msg'] 	= $msg ? $msg : $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']];

		// 拆分获取值
		} else {
			foreach ($code as $key => $val) {
				$str        = $key .'_ZH';
				$res['code']= $this->$key[$val];
				$res['msg'] = $this->$str[$res['code']];
			}
		}

		$res['data'] 		= $data;
		
        echo json_encode($res, JSON_UNESCAPED_UNICODE); exit();
	}
	
	/**
     * 验证登录用户的token
     */
	public function verifyUserToken(&$token, &$User, &$res_token)
	{
		// 获取参数
		$token     = trim(I('post.token'));
		
		$User      = new \Common\Model\UserModel();

        if ($token) {
            // 判断用户身份
            $res_token = $User->checkToken($token);
            
            if ($res_token['code'] != 0) {
                // 用户身份不合法
                $this->ajaxError($res_token['code'], $res_token['msg']);
            }
		} else {
			// token不能为空
			$this->ajaxError(['ERROR_CODE_USER' => 'LACK_TOKEN']);
		}
	}
}