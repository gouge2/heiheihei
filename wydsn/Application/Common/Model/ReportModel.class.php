<?php
/**
 * 举报记录管理
 */
namespace Common\Model;
use Think\Model;

class ReportModel extends Model
{
	//验证规则
	protected $_validate =array(
		array('cat_id','require','分类标识不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('user_id','require','用户标识不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('cause','require','举报原因不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
		array('add_time','require','举报时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
	);
	
}