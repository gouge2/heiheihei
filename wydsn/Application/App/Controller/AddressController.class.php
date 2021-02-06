<?php
/**
 * by 来鹿 www.lailu.shop
 *  AccountConfig/获取配置账号接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class AddressController extends AuthController
{

    public function getSub(){
        $fid = I('post.fid');
        $list = M('global_address')
            ->field(['id', 'area_name'])
            ->where(['pid'=>$fid])
            ->select();
        return $this->ajaxSuccess($list);
    }

}
