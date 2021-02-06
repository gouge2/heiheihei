<?php
/**
 * 举报分类管理
 */
namespace Common\Model;
use Think\Model;

class ReportCatModel extends Model
{
	//验证规则
	protected $_validate =array(
		array('name','require','分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
	);
	
	/**
	 * 获取全部分类列表
	 */
	public function getAllList($field = 'id,name', $sort = 'sort desc, id asc')
	{
		$whe = ['is_show' => 1];
		$res = $this->field($field)->where($whe)->order($sort)->select();
		
		return $res;
	}
}