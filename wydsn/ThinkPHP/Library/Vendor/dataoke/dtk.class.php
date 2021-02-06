<?php
include "ApiSdk.php";
class dtk{
    protected $dtkappkey=DTK_APP_KEY;
    protected $dtksecret=DTK_APP_SECRET;

    /**
     * 大淘客訂單列表
     * @param number $query_type:查询时间类型，1：按照订单淘客创建时间查询，2:按照订单淘客付款时间查询，3:按照订单淘客结算时间查询
     * @param number $tk_status:淘客订单状态，12-付款，13-关闭，14-确认收货，3-结算成功;不传，表示所有状态
     * @param number $order_scene:场景订单场景类型，1:常规订单，2:渠道订单，3:会员运营订单，默认为1
     * @param number $member_type:推广者角色类型,2:二方，3:三方，不传，表示所有角色
     * @param number $page_no:第几页，默认1，1~100
     * @param number $page_size:页大小，默认20，1~100
     * @param string $position_index:位点，除第一页之外，都需要传递；前端原样返回。
     * @param number $jump_type:跳转类型，当向前或者向后翻页必须提供,-1: 向前翻页,1：向后翻页
     */
    public function dtkGetOrderList($query_type,$tk_status='',$order_scene=1,$start_time,$end_time,$member_type='',$page_no=1,$page_size=20,$position_index='',$jump_type=1)
    {
        $c = new \CheckSign();
        $c->host = 'https://openapi.dataoke.com/api/tb-service/get-order-details';
        //appKey  必填
        $c->appKey = $this->dtkappkey;#'5f9b7fb26494d';#$this->dtkappkey;
        //appSecret  必填
        $c->appSecret = $this->dtksecret;#"64c136ca1ab63f4f85cd9824cee88318";#$this->dtksecret;
        //版本号  必填
        $c->version = 'v1.0.2';
        $params = array();
        $params['queryType'] = $query_type;
        $params['tkStatus'] = $tk_status;
        $params['orderScene'] = $order_scene;
        $params['endTime'] = $end_time;
        $params['startTime'] = $start_time;
        $params['memberType'] = $member_type;
        $params['pageNo'] = $page_no;
        $params['pageSize'] = $page_size;
        $params['positionIndex'] = $position_index;
        $params['jumpType'] = $jump_type;
        $request = $c->request($params);
        return json_decode($request,true);
    }
}