<?php
/**
 * by 翠花 http://http://livedd.com
 * 快递鸟-及时查询接口
 * 2020-03-24
 */


class kdApiSearch
{
//    //电商ID 测试
//    protected $EBusinessID='test1627746';
//    //电商加密私钥，快递鸟提供，注意保管，不要泄漏 测试
//    protected $AppKey='c7d71b10-32e0-42ff-9962-46e447bee411';
//    //请求url 测试地址
//    protected $ReqURL='http://sandboxapi.kdniao.com:8080/kdniaosandbox/gateway/exterfaceInvoke.json';

    //电商ID 正式
    protected $EBusinessID=KDN_ID;
    //电商加密私钥，快递鸟提供，注意保管，不要泄漏 正式
    protected $AppKey=KDN_APIKEY;
    //请求url 正式地址
    protected $ReqURL='http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx';

    //快递公司对应编码
    protected $express_code=array(
        '顺丰速运'=>'SF',
        '百世快递'=>'HTKY',
        '中通快递'=>'ZTO',
        '申通快递'=>'STO',
        '圆通速递'=>'YTO',
        '韵达速递'=>'YD',
        '邮政快递包裹'=>'YZPY',
        'EMS'=>'EMS',
        '天天快递'=>'HHTT',
        '京东快递'=>'JD',
        '优速快递'=>'UC',
        '德邦快递'=>'DBL',
        '宅急送'=>'ZJS',
        '安捷快递'=>'AJ',
        'EMS国内'=>'EMS2',
        '丰巢'=>'FBOX',
        '京东快运'=>'JDKY',
        '韵达快运'=>'YDKY',
        '中邮快递'=>'ZYKD',
        '芝麻开门'=>'ZMKM',
        '安能物流'=>'ANE',
        '速尔快递'=>'SURE',
        '汇通快递'=>'HTO',
    );


    /**
     * Json方式 查询订单物流轨迹
     * @param string $OrderCode:订单编号
     * @param string $ShipperCode:快递公司编码
     * @param String $LogisticCode 物流单号
     * @return array
     */
    public function getOrderTracesByJson($OrderCode,$ShipperCode,$LogisticCode)
    {
        $logistics_arr = json_decode(logistics, true);
        $requestData=array(
            'OrderCode'=>$OrderCode,
            'ShipperCode'=>$this->express_code[$logistics_arr[$ShipperCode]],
            'LogisticCode'=>$LogisticCode
        );
        $requestData=json_encode($requestData);
//        $requestData= "{'OrderCode':'','ShipperCode':'$ShipperCode','LogisticCode':'$LogisticCode'}";

        $data = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );
        $data['DataSign'] = $this->encrypt($requestData);
        $result=$this->sendPost($this->ReqURL, $data);
        $result=json_decode($result,true);
        //根据公司业务处理返回的信息......
        // 加入快递名称
        if (isset($result['ShipperCode']) && $result['ShipperCode']) {
            foreach ($this->express_code as $key => $val) {
                if ($result['ShipperCode'] == $val) {
                    $result['ShipperName'] = $key;
                }
            }
        }

        return $result;
    }

    /**
     * mob秒验服务端验证
     * @param string $url 请求Url
     * @param array $datas 提交的数据
     * @return string
     */
    public function sendPost($url, $data)
    {
        $temps = array();
        foreach ($data as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param string $data 内容
     * @return string DataSign签名
     */
    public function encrypt($data)
    {
        return urlencode(base64_encode(md5($data.$this->AppKey)));
    }
}
?>