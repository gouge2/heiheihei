<?php
/**
 * by 翠花 www.lailu.shop
 * 京东商品分类管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class JingdongCatController extends AuthController 
{
	/**
	 * 获取顶级京东商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级京东商品分类列表
	 */
	public function getTopCatList()
	{
		$JingdongCat 	= new \Common\Model\JingdongCatModel();
		$list 			= $JingdongCat->getParentList('Y');

		if ($list !== false) {
			$cat_list 	= [
				['cat_id' => '1', 'cat_name' => '居家日用'],
				['cat_id' => '2', 'cat_name' => '食品'],
				['cat_id' => '3', 'cat_name' => '生鲜'],
				['cat_id' => '4', 'cat_name' => '图书'],
				['cat_id' => '5', 'cat_name' => '美妆个护'],
				['cat_id' => '6', 'cat_name' => '母婴'],
				['cat_id' => '7', 'cat_name' => '数码家电'],
				['cat_id' => '8', 'cat_name' => '内衣'],
				['cat_id' => '9', 'cat_name' => '配饰'],
				['cat_id' => '10', 'cat_name' => '女装'],
				['cat_id' => '11', 'cat_name' => '男装'],
				['cat_id' => '12', 'cat_name' => '鞋品'],
				['cat_id' => '13', 'cat_name' => '家装家纺'],
				['cat_id' => '14', 'cat_name' => '文娱车品'],
				['cat_id' => '15', 'cat_name' => '箱包'],
				['cat_id' => '16', 'cat_name' => '户外运动'],
			];

			// 成功
			$res 		= [
				'code' 	=> $this->ERROR_CODE_COMMON['SUCCESS'],
				'msg' 	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
				'data' 	=> ['list' => $list, 'cat_list' => $cat_list]
			];
		} else {
			// 数据库错误
			$res = [
				'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
				'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
			];
		}

		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * 获取子级京东商品分类列表
	 * @param int $pid:父级京东商品分类ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:子级京东商品分类列表
	 */
	public function getSubListByParent()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$JingdongCat=new \Common\Model\JingdongCatModel();
			$list=$JingdongCat->getSubListByParent($pid,'asc','Y');
			if($list!==false)
			{
				//成功
				$data=array(
						'list'=>$list
				);
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
						'data'=>$data
				);
			}else {
				//数据库错误
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
						'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
				);
			}
		}else {
			//参数不正确，参数缺失
			$res=array(
					'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
					'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
			);
		}
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}
}
?>