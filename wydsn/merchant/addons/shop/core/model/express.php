<?php
class Express_EweiShopV2Model
{
	/**
     * 获取快递列表
     */
	public function getExpressList1()
	{
		global $_W;
		$sql = 'select * from ' . tablename('ewei_shop_express') . ' where status=1 order by displayorder desc,id asc';
		$data = pdo_fetchall($sql);
		return $data;
	}
	public function getExpressList()
	{
		return [
//			['id'=>1,'name' => '自己物流','express'=>'self'],
			['id'=>2,'name' => '顺丰速运','express'=>'shunfeng'],
			['id'=>3,'name' => '圆通速递','express'=>'yuantong'],
			['id'=>4,'name' => '中通快递','express'=>'zhongtong'],
			['id'=>5,'name' => '申通快递','express'=>'shentong'],
			['id'=>6,'name' => '韵达速递','express'=>'yunda'],
			['id'=>7,'name' => 'EMS','express'=>'ems'],
			['id'=>8,'name' => '天天快递','express'=>'tiantian'],
//			['id'=>9,'name' => '优速快递','express'=>'self'],
			['id'=>10,'name' => '百世快递','express'=>'huitongkuaidi'],
			['id'=>11,'name' => '安能物流','express'=>'annengwuliu'],
			['id'=>12,'name' => '宅急送','express'=>'zhaijisong'],
//			['id'=>13,'name' => '快捷快递','express'=>'self'],
			['id'=>14,'name' => '邮政包裹','express'=>'youzhengguonei'],
			['id'=>15,'name' => '德邦','express'=>'debangwuliu'],
//			['id'=>16,'name' => '汇通快递','express'=>'self'],
			['id'=>17,'name' => '速尔快递','express'=>'suer'],
			['id'=>18,'name' => '国通快递','express'=>'guotongkuaidi'],
			['id'=>19,'name' => '邮政快递包裹','express'=>'youzhengguonei'],
			['id'=>20,'name' => '京东快递','express'=>'jd'],
			['id'=>21,'name' => '德邦快递','express'=>'debangwuliu'],
			['id'=>22 ,'name'=> '安捷快递','express'=>'anjie'],
			['id'=>23,'name' => 'EMS国内','express'=>'ems'],
//			['id'=>24,'name' => '丰巢','express'=>'self'],
			['id'=>25,'name' => '京东快运','express'=>'jd'],
			['id'=>26,'name' => '韵达快运','express'=>'yunda'],
			['id'=>27,'name' => '中邮快递','express'=>'zhongyouwuliu'],
			['id'=>28,'name' => '芝麻开门','express'=>'zhimakaimen'],
		];
	}

	public function checkExpress($name)
	{
		$express = $this->getExpressList();

		foreach ($express as $item)
		{
			if($name == $item['name'])
			{
				return $item['id'];
			}
		}
	}
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

?>
