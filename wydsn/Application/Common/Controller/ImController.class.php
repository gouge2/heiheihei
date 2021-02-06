<?php
/**
 * IM发群消息管理类
 * 
 */
namespace Common\Controller;
use Think\Controller;


class ImController extends Controller
{
	protected static $sdkappid  		= TENCENT_IM_SDKAPPID;
	protected static $key       		= TENCENT_IM_KEY;
	protected static $admin     		= TENCENT_IM_ADMIN;   // 应用程序管理员账号

	// 腾讯直播推拉流信息
	protected static $live_domain 		= ['push'=> TENCENT_LIVE_PUSH_DOMAIN, 'pull' => TENCENT_LIVE_PULL_DOMAIN];
	protected static $live_key         	= TENCENT_LIVE_KEY;
	protected static $live_aging_time  	= 86400;			// 有效时间

	public static $http 				= "https://console.tim.qq.com/";


	/**
	 * 初始化方法
	 */
	public function _initialize()
	{
		import("Org.Util.TLSSigAPIv2");  // 引入相关的类

		// 转化域名
		self::$live_domain['push']    	= explode('，', self::$live_domain['push']);
		self::$live_domain['pull']    	= explode('，', self::$live_domain['pull']);
	}

	/**
	 * 管理员发送IM消息时URL公共部分
	 */
	protected static function _comAdminUrlStr( $rand_tag = false, $type = 'json')
	{
		$com_data = [
			'usersig' 		=> self::getUserSig(self::$admin),
			'identifier' 	=> self::$admin,
			'sdkappid' 		=> self::$sdkappid,
			'contenttype' 	=> $type
		];

		// 随机数
		if ($rand_tag) {
			$com_data['random'] = mt_rand(100000, 9999999);
		}

		return '?'. urldecode(http_build_query($com_data));
	}

	/**
	 * 房间公共发送数据体与发送
	 */
	protected static function _comGroupDataSend($method, $room_id, $cmd, $data) 
	{
		$url 		= self::$http . $method . self::_comAdminUrlStr();		// 拼接链接

		// 发送数据体
		$post_data  = [
			"GroupId" 	=> (string)$room_id,
			"Random" 	=> mt_rand(100000, 9999999),
			"MsgBody" 	=> [
				[
					"MsgType" 		=> "TIMCustomElem",
					"MsgContent" 	=> [
						"Data" 		=> json_encode([
							"cmd" 		=> "CustomCmdMsg",
							"data" 		=> [
								"cmd" 			=> $cmd,
								"msg" 			=> json_encode($data),
								"userAvatar" 	=> "",
								"userName" 		=> ""
							]
						]),
						"Desc" 		=> "",
						"Ext" 		=> "",
						"Sound" 	=> ""
					]
				]
			]
		];

		return self::curlPost($url, $post_data);
	}

	/**
	 * 单发用户公共发送数据体与发送
	 */
	protected static function _comUserDataSend($method, $u_name, $cmd, $data)
	{
		$url 		= self::$http . $method . self::_comAdminUrlStr();		// 拼接链接

		// 发送数据体
		$post_data  = [
			"SyncOtherMachine" 	=> 2, 							// 消息不同步至发送方
			"To_Account" 		=> $u_name,
			"MsgLifeTime" 		=> 5, 							// 消息保存5秒
			"MsgTimeStamp" 		=> $_SERVER['REQUEST_TIME'],	// 发送时间
			"MsgRandom" 		=> mt_rand(100000, 9999999),
			"MsgBody" 			=> [
				[
					"MsgType" 		=> "TIMCustomElem",
					"MsgContent" 	=> [
						"Data" 		=> json_encode([
							"cmd" 		=> "CustomCmdMsg",
							"data" 		=> [
								"cmd" 			=> $cmd,
								"msg" 			=> json_encode($data),
								"userAvatar" 	=> "",
								"userName" 		=> ""
							]
						]),
						"Desc" 		=> "",
						"Ext" 		=> "",
						"Sound" 	=> ""
					]
				]
			]
		];

		return self::curlPost($url, $post_data);
	}

	/**
	 * 公共直播房间群操作
	 */
	protected static function _comLiveGroupHandle($room_id, $method, $gid = 0, $type = 'destroy')
	{
		$url 		= self::$http . $method . self::_comAdminUrlStr(true);		// 拼接链接

		$data       = $type == 'destroy' ? [
			"GroupId" 		=> (string)$room_id,
		] : [
			"Owner_Account" => (string)$gid,
			"Type" 			=> 'AVChatRoom',
			"GroupId" 		=> (string)$room_id,
			"Name" 			=> (string)$room_id,
		];

		return self::curlPost($url, $data);
	}

	/**
	 * 公共IM账号导入操作
	 */
	protected static function _comImImport($uid)
	{
		// 多账号导入
		if (is_array($uid)) {	
			$method     = 'v4/im_open_login_svc/multiaccount_import';
			$uarr       = [];

			foreach ($uid as  $val) {
				$uarr[] = (string)$val;
			}

			$data       = ["Accounts" => $uarr];

		// 单账号导入
		} else {
			$method     = 'v4/im_open_login_svc/account_import';
			$data       = [
				"Identifier" 	=> (string)$uid,
				"Nick" 		 	=> "",				// 昵称
				"FaceUrl" 		=> ""				// 头像
			];
		}
		
		$url 		= self::$http . $method . self::_comAdminUrlStr(true);		// 拼接链接

		return self::curlPost($url, $data);
	}

	/**
	 * curl发送数据
	 */
	protected static function curlPost($url, $post_data)
	{	
		$ch 	= curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);                 	// post数据
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));   // post的变量

		$output = curl_exec($ch);
		curl_close($ch);

		return json_decode($output, true);
	}

	/**
	 * 获取IM UserSig
	 */
	public static function getUserSig($uid) 
	{
		$obj 	= new \TLSSigAPIv2(self::$sdkappid, self::$key);
		return $obj->genSig($uid);
	}

	/**
	 * 获取IM后台管理账号
	 */
	public static function getImAdminAcc()
	{
		return self::$admin;
	}

	/**
	 * 获取IM sdkappid 账号
	 */
	public static function getImSdkAppid()
	{
		return self::$sdkappid;
	}

	/**
	 * 获取腾讯云直播域名
	 */
	public static function getLiveDomain()
	{
		return self::$live_domain;
	}

	/**
	 * 获取腾讯云直播签名
	 */
	public static function getLiveKey()
	{
		return self::$live_key;
	}

	/**
	 * 获取腾讯云直播流有效时间
	 */
	public static function getLiveAgingTime()
	{
		return self::$live_aging_time;
	}

	/**
	 * 在群组中发送普通消息
	 */
	public static function sendGroupMsg($room_id, $cmd, $list)
	{
		$method = 'v4/group_open_http_svc/send_group_msg';
		return self::_comGroupDataSend($method, $room_id, $cmd, $list);
	}

	/**
	 * 单独给用户发送普通消息
	 */
	public static function sendUserMsg($u_name, $cmd, $list)
	{
		$method = 'v4/openim/sendmsg';
		return self::_comUserDataSend($method, $u_name, $cmd, $list);
	}

	/**
	 * 创建直播房间群
	 */
	public static function createLiveGroup($room_id, $gid)
	{
		$method = 'v4/group_open_http_svc/create_group';
		$res    = self::_comLiveGroupHandle($room_id, $method, $gid, 'create');
		return (isset($res['ErrorCode']) && ($res['ErrorCode'] == 0 || $res['ErrorCode'] == 10021)) ? true : false;
	}

	/**
	 * 解散直播房间群
	 */
	public static function destroyLiveGroup($room_id)
	{
		$method = 'v4/group_open_http_svc/destroy_group';
		return self::_comLiveGroupHandle($room_id, $method);
	}

	/**
	 * 添加Im-userId
	 */
	public static function userIdAdd($uid)
	{
		return self::_comImImport($uid);
	}
	
}
?>