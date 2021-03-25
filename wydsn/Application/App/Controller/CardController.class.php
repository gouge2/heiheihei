<?php
/**
 * by 翠花 http://http://livedd.com
 * 商品分类管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class CardController extends AuthController
{
	/**
	 * 获取顶级商品分类列表
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:顶级商品分类列表
	 */
	public function index()
	{
        $Banner=new \Common\Model\BannerModel();
        $banner=$Banner->getBannerList(8,'Y',0);
        $CardGoods = new \Common\Model\CardPrivilegeModel();
        $CardCat = new \Common\Model\CardCatModel();
        $catlist = $CardCat->getCardCatList('Y');
        foreach ($catlist as $k=>$cat){
            $list= $CardGoods->getGoodsList($cat['id'],'desc','Y',true);
            $catlist[$k]['goods'] =$list;
        }
        //test
        $tequanlist = $CardGoods->getGoodsTequan('Y');
        //成功
        $data=array(
            'banner'=>$banner,
            'catlist'=>$catlist,
            'tequan' =>$tequanlist
        );
        $res=array(
            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'=>'成功',
            'data'=>$data
        );
		echo json_encode ($res,JSON_UNESCAPED_UNICODE);
	}

}
?>