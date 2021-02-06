<?php
/**
 * 云点播短视频-服务器通知
*/
namespace App\Controller;

use Think\Controller;

class ShortNotifyController extends Controller
{
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
				writeLog($raw_xml, 'test130');
			// }

			$param 		= json_decode($raw_xml, true);
		}

		// 判断回调方法
		if ($param && isset($param['EventType'])) {
			$Short = new \Common\Model\ShortModel();

			// 上传完成回调
			if ($param['EventType'] == 'NewFileUpload' && isset($param['FileUploadEvent'])) {  
				$info  			= $param['FileUploadEvent']['MediaBasicInfo'];
				$data  			= $param['FileUploadEvent']['MetaData'];
				$info['Vid']    = $param['FileUploadEvent']['FileId'];
				$Short->callbacksAdd($info, $data);					// 添加记录

			// 删除回调	
			} elseif ($param['EventType'] == 'FileDeleted' && isset($param['FileDeleteEvent']['FileIdSet'])) {
				$data  = $param['FileDeleteEvent']['FileIdSet'];
				$Short->callbacksDel($data);						// 删除记录

			// 任务流变更回调
			} elseif ($param['EventType'] == 'ProcedureStateChanged' && isset($param['ProcedureStateChangeEvent']['MetaData'])) {
				$data  = $param['ProcedureStateChangeEvent'];
				$Short->callbacksMod($data);						// 修改记录
			}


		}
		
	}
}
?>