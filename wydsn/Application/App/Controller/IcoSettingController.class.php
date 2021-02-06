<?php


namespace App\Controller;

use App\Common\Controller\AuthController;
use Common\Model\IcoSettingModel;

class IcoSettingController extends AuthController
{
    /**
     * ico 接口
     */
    public function getIcoList()
    {
        $IcoSetting = new IcoSettingModel();
        $res = $IcoSetting->getList();
        $this->ajaxSuccess($res);
    }

}