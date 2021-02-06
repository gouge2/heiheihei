<?php
if (!(defined('IN_IA'))) {
	exit('Access Denied');
}


require_once IA_ROOT . '/addons/shop/version.php';
require_once IA_ROOT . '/addons/shop/defines.php';
require_once EWEI_SHOPV2_INC . 'functions.php';
require_once EWEI_SHOPV2_INC . 'receiver.php';
class Ewei_shopv2ModuleReceiver extends Receiver
{
	public function receive()
	{
		parent::receive();
	}
}


?>