<?php
/**
 * 云直播播-服务器通知
*/
namespace App\Controller;

use Think\Controller;

class LiveNotifyController extends Controller
{
		
	protected static $sgin_key  = TENCENT_LIVE_CALL_KEY;


	/**
     * 服务器通知处理
     */
	public function dispose()
	{
		// 获取返回数据
		$param 		= [];
		$raw_xml 	= file_get_contents("php://input");
		
		// 数据还原
		if ($raw_xml) {
			//写日志
			// if (APP_DEBUG === true) {
				writeLog($raw_xml, 'test140');
			// }

			$param 		= json_decode($raw_xml, true);
		}

		// 判断回调方法
		if ($param && isset($param['event_type']) && isset($param['sign']) && isset($param['t'])) {
			$sign = md5(self::$sgin_key . $param['t']);

			if ($sign == $param['sign']) {			// 验证签名
				$LiveRoom = new \Common\Model\LiveRoomModel();
				$LiveRoom->processFlow($param);
			}
		}	
	}
}
?>