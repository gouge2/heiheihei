<?php
/**
 * by 翠花 www.lailu.shop
 * 快递查询类 v1.0
 * @copyright        福星高照
 * @lastmodify       2014-08-22
 */
namespace Common\Model;

class ExpressModel
{
    /*
     * 网页内容获取方法
    */
    private function getcontent($url)
    {
        if (function_exists("file_get_contents")) {
            $file_contents = file_get_contents($url);
        } else {
            $ch      = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }

    /*
     * 获取对应名称和对应传值的方法
    */
    private function expressname($order)
    {
        $name   = json_decode($this->getcontent("http://www.kuaidi100.com/autonumber/auto?num={$order}"), true);
        $result = $name[0]['comCode'];
        if (empty($result)) {
            return false;
        } else {
            return $result;
        }
    }

    /*
     * 返回$data array      快递数组查询失败返回false
     * @param $order        快递的单号
     * $data['ischeck'] ==1 已经签收
     * $data['data']        快递实时查询的状态 array
    */
    public function getorder($order)
    {
        $keywords = $this->expressname($order);
        if (!$keywords) {
            return false;
        } else {
            $result = $this->getcontent("http://www.kuaidi100.com/query?type={$keywords}&postid={$order}");
            $data   = json_decode($result, true);
            return $data;
        }
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

}
?>