<?php
/**
 * by 翠花 http://livedd.com
 * 淘宝商品分类管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Model\GoodsCatModel;
use Common\Model\JingdongCatModel;
use Common\Model\PddCatModel;
use Common\Model\TaobaoCatModel;

class TaobaoCatController extends AuthController 
{
	/**
	 * 获取顶级淘宝商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级淘宝商品分类列表
	 */
	public function getTopCatList()
	{
	    // 默认淘宝分类
        $source_head = (defined('SOURCE_HEAD') && !empty(SOURCE_HEAD)) ?SOURCE_HEAD:'tb';

	    // 读取缓存
        $list = S('CatList');
        $CatType = S('CatType');

        if ($list === false || $CatType !== $source_head) {
            // 清空缓存
            S('CatType',null);
            S('CatList',null);
            if ($source_head == 'tb') {
                $TaobaoCat = new TaobaoCatModel();
                $list = $TaobaoCat->getParentList('Y');

            } elseif ($source_head == 'jd') {
                $JingDongCat = new JingdongCatModel();
                $list = $JingDongCat->getParentList('Y');
                foreach ($list as $key => $value) {
                    $list[$key]['taobao_cat_id'] = $value['jingdong_cat_id'];
                    $list[$key]['tb_cat_id'] = $value['jingdong_id'];
                    unset($list[$key]['jingdong_cat_id']);
                    unset($list[$key]['jingdong_id']);
                }
            } elseif ($source_head == 'pdd') {
                $PddCat = new PddCatModel();
                $list = $PddCat->getParentList('Y');
                foreach ($list as $key => $value) {
                    $list[$key]['taobao_cat_id'] = $value['cat_id'];
                    $list[$key]['tb_cat_id'] = $value['pdd_id'];
                    unset($list[$key]['pdd_cat_id']);
                    unset($list[$key]['pdd_id']);
                }
            } elseif ($source_head == 'self') {
                $SelfCat = new GoodsCatModel();
                $list = $SelfCat->getTopCatList('Y');
                foreach ($list as $key => $value) {
                    $temp['taobao_cat_id'] = $value['cat_id'];
                    $temp['name'] = $value['cat_name'];
                    $temp['icon'] = $value['img'];
                    $temp['sort'] = $value['sort'];
                    $temp['is_show'] = $value['is_show'];
                    $temp['pid'] = $value['parent_id'];
                    $temp['tb_cat_id'] = null;
                    $list[$key] = $temp;
                }
            }
            //未设置缓存，进行设置
            if ($list !== false) {
                //设置缓存
                //不设置过期时间
                S('CatType', $source_head, array('type' => 'file', 'expire' => 0));
                S('CatList', $list, array('type' => 'file', 'expire' => 0));
            } else {
                //数据库错误
                $this->ajaxError($this->ERROR_CODE_COMMON['DB_ERROR'],$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]);
            }
        }

        //成功
        $data = array('source' => $source_head, 'list' => $list);
        $this->ajaxSuccess($data);
	}
	
	/**
	 * 获取子级淘宝商品分类列表
	 * @param int $pid:父级淘宝商品分类ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:子级淘宝商品分类列表
	 */
	public function getSubListByParent()
	{
		if(trim(I('post.pid')))
		{
			$pid=trim(I('post.pid'));
			$TaobaoCat=new \Common\Model\TaobaoCatModel();
			$list=$TaobaoCat->getSubListByParent($pid,'asc','Y');
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