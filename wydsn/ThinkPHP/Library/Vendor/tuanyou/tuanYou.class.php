<?php
/**
 * 团油接口
 * 2020-03-09
 */


class tuanYou
{
    //团油渠道编码
    protected $tyChannelCoding=TY_CHANNEL_CODING;
    //团油key
    protected $tyKey=TY_KEY;
    //团油secret
    protected $tySecret=TY_SECRET;
    //团油获取授权码接口测试地址
    protected $test_auth_url='https://test-mcs.czb365.com/services/v3/begin/getSecretCode';
    //团油获取授权码接口正式地址
    protected $auth_url='https://mcs.czb365.com/services/v3/begin/getSecretCode';

    /**
     * 团油静默登录获取授权码接口
     * @param string $phone:手机号
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function getSecretCode($phone)
    {
        //设置post数据
        $post_data=array(
            "app_key" => $this->tyKey,
            'platformId' => $this->tyChannelCoding,
            "phone" => $phone,
            'timestamp'=> get_millistime()
        );
        $post_data['sign'] = $this->getSign($post_data);
        //判断团油环境类型，选择不同地址，1测试 2正式
        if (TY_TYPE==1){
            $url=$this->test_auth_url;
        }else{
            $url=$this->auth_url;
        }
        $res_json=https_request($url,$post_data);
        $res=json_decode($res_json,true);
        return $res;
    }

    /**
     * 团油MD5签名
     * @param array $data
     * @return string
     */
    public function getSign($data) {
        ksort($data);
        $str = '';
        foreach ($data as $k => $v ) {
            $str .= $k.$v;
        }
        $str = $this->tySecret.$str.$this->tySecret;
        return md5($str);
    }
}
?>