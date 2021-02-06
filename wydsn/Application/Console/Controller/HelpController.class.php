<?php
namespace Console\Controller;

use Common\Model\SettingModel;
use Think\Controller;

class HelpController extends Controller {

	function index()
	{
		echo "this console.".PHP_EOL;
	}

	public function test(){
        Vendor('pay.wxpay','','.class.php');
        $wxpay=new \wxpay();
        $wxpay->SpBill();
    }
}