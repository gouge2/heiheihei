<?php


namespace Common\Model;


class DaTaoKeModel
{
    protected $appKey='5d81e40b1249d';
    protected $appSecret='5640fd1985083a41d81109b5459baf55';
    protected $gateWayUrl='https://openapi.dataoke.com';
    private $c;

    public function __construct() {
        Vendor('dataoke.ApiSdk','','.php');
        $this->c = new \CheckSign();
        //appKey  必填
        $this->c->appKey = $this->appKey;
        //appSecret  必填
        $this->c->appSecret = $this->appSecret;
    }

    /**
     * 获取商品列表
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getGoodsList($page, $length, $params) {
        $params = str_replace("&quot;", '"', $params); // 转换一个双引号
        $params = json_decode($params, true);

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/get-goods-list';
        //版本号  必填
        $this->c->version = 'v1.2.3';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 获取商品详情
     * @param $id
     * @param $goodsId
     */
    public function getGoodsDetail($id, $goodsId) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/get-goods-details';
        //版本号  必填
        $this->c->version = 'v1.2.3';

        $params = array(
            'id' => $id,
            'goodsId' => $goodsId
        );

        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $data = $result['data'];
            $resData = $this->paramConvert($data);
            /*$resData = array_merge($resData, array(
                'detailPics' => $data['detailPics'],
                'imgs' => $data['imgs'],
                'reimgs' => $data['reimgs'],
            ));*/

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$resData
            );
        }

        return $res;
    }

    /**
     * 生成淘口令
     * @param $text string 口令弹框内容，长度大于5个字符
     * @param $url string 口令跳转目标页
     * @param $logo string 口令弹框logoURL
     */
    public function createKL($text, $url, $logo='',$userId='') {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/creat-taokouling';
        //版本号  必填
        $this->c->version = 'v1.0.0';

        $params = array(
            'text' => $text,
            'url' => $url,
            'logo' => $logo,
            'userId' => $userId,
        );

        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $data = $result['data'];
            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data['model']
            );
        }

        return $res;
    }

    /**
     * 获取商品收藏列表
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getCollectList($page, $length, $params) {

        $url = "/api/goods/get-collection-list";
        return $this->commonResult($page, $length, $params,  $url);
    }

    /**
     * 我发布的商品
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getOwnerGoods($page, $length, $params) {

        $url = "/api/goods/get-owner-goods";
        return $this->commonResult($page, $length, $params,  $url);
    }

    /**
     * 商品更新
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getNewestGoods($page, $length, $params) {

        $url = "/api/goods/get-newest-goods";
        return $this->commonResult($page, $length, $params,  $url,'v1.1.0');
    }

    /**
     * 热搜记录
     */
    public function getTop100() {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/category/get-top100';
        //版本号  必填
        $this->c->version = 'v1.0.1';

        $params = array();

        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $data = $result['data'];
            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data['hotWords']
            );
        }

        return $res;
    }

    /**
     * 各大榜单
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getRankingList($page, $length, $params) {
        $params = str_replace("&quot;", '"', $params); // 转换一个双引号
        $params = json_decode($params, true);

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/get-goods-list';
        //版本号  必填
        $this->c->version = 'v1.3.0';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 高佣转链
     * @param $params
     * @return array
     */
    public function getPrivilegeLink($goodsId, $relationId='') {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/get-privilege-link';
        //版本号  必填
        $this->c->version = 'v1.3.0';
        $params['goodsId'] = $goodsId;
        $params['pid'] = TBK_PID;
        $params['channelId'] = $relationId;
        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data'];

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$list
            );
        }

        return $res;
    }

    /**
     * 大淘客搜索
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getDtkSearchGoods($page, $length, $keyWords, $sort = 0) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/get-dtk-search-goods';
        //版本号  必填
        $this->c->version = 'v2.1.2';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;
        $params['keyWords'] = $keyWords;
        $params['sort'] = $sort;
        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 超级分类
     */
    public function getSuperCategory() {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/category/get-super-category';
        //版本号  必填
        $this->c->version = 'v1.1.0';

        $params = array();

        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $data = $result['data'];
            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 失效商品
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function getStaleGoodsByTime($page, $length, $params) {

        $url = "/api/goods/get-stale-goods-by-time";
        $version = "v1.0.1";
        return $this->commonResult($page, $length, $params, $url, $version);
    }

    /**
     * 定时拉取
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function pullGoodsByTime($page, $length, $params) {
        $params = str_replace("&quot;", '"', $params); // 转换一个双引号
        $params = json_decode($params, true);

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/pull-goods-by-time';
        //版本号  必填
        $this->c->version = 'v1.2.3';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 联盟搜索
     * @param $page
     * @param $length
     * @param $keyWords
     * @return array
     */
    public function getTbService($page, $length, $params) {
//        $params = str_replace("&quot;", '"', $params); // 转换一个双引号
//        $params = json_decode($params, true);

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/get-tb-service';
        //版本号  必填
        $this->c->version = 'v2.1.0';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageNo'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = new \Common\Model\GoodsParamModel();
                $param->title = $val['title'];
                $param->monthSales = $val['volume'];
                $param->nickName = $val['nick'];
                $param->couponStartTime = $val['coupon_start_time'];
                $param->couponEndTime = $val['coupon_end_time'];
                $param->couponId = $val['coupon_id'];
                $param->mainPic = $val['pict_url'];
                $param->imgs = $val['small_images'];
                $param->originalPrice = $val['reserve_price'];
                $param->actualPrice = $val['zk_final_price'];
                $param->shopType = $val['user_type'];
                $param->sellerId = $val['seller_id'];
                $param->couponTotalNum = $val['coupon_total_count'];
                $param->couponReceiveNum = $val['coupon_remain_count'];
                $param->couponConditions = $val['coupon_info'];
                $param->shopName = $val['shop_title'];
                $param->commissionRate = $val['commission_rate'];

                $param->extInfo = array(
                    'dtk' => array(
                        'tkTotalSales' => $val['tk_total_sales'],
                    )
                );
                $param = json_decode(json_encode($param), true);
                $param = array_filter($param);
                $param = array_merge($param, array(
                     'shopDsr' => 'shop_dsr',
                     'levelOneCategoryName' => 'level_one_category_name',
                     'levelOneCategoryId' => 'level_one_category_id',
                     'categoryName' => 'category_name',
                     'categoryId' => 'category_id',
                     'shortTitle' => 'short_title',
                     'whiteImage' => 'white_image',
                     'couponStartFee' => 'coupon_start_fee',
                     'couponAmount' => 'coupon_amount',
                     'itemDescription' => 'item_description',
                     'itemId' => 'item_id',
                     'ysylTljFace' => 'ysyl_tlj_face',
                     'presaleDeposit' => 'presale_deposit',
                     'presaleDiscountFeeText' => 'presale_discount_fee_text',
                ));
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 超级搜索
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function listSuperGoods($page, $length, $params) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/list-super-goods';
        //版本号  必填
        $this->c->version = 'v1.3.0';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 9.9包邮精选
     * @param $page
     * @param $length
     * @param $params
     * @return array
     */
    public function opGoodsList($page, $length, $params) {

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/nine/op-goods-list';
        //版本号  必填
        $this->c->version = 'v2.0.0';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 猜你喜欢
     * @param $length
     * @param $id
     * @return array
     */
    public function listSimilerGoodsByOpen($length, $id) {
        $params = array();

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/list-similer-goods-by-open';
        //版本号  必填
        $this->c->version = 'v1.2.2';

        //其他请求参数 根据接口文档需求选填
        $params['size'] = $length;
        $params['id'] = $id;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data'];
            $data = array();
            foreach ($list as $key => $val) {
                $param = $this->paramConvert($val);
                array_push($data, $param);
            }

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$data
            );
        }

        return $res;
    }

    /**
     * 品牌库
     * @param $page
     * @param $length
     * @return array
     */
    public function getBrandList($page, $length) {
        $params = array();

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/get-brand-list';
        //版本号  必填
        $this->c->version = 'v1.1.1';

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data'];

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$list
            );
        }

        return $res;
    }

    /**
     * 联想词
     * @param $params
     * @return array
     */
    public function searchSuggestion($params) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/search-suggestion';
        //版本号  必填
        $this->c->version = 'v1.0.2';

        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data'];

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$list
            );
        }

        return $res;
    }

    private function commonResult($page, $length, $params, $url, $version = 'v1.0.1') {
        $params = str_replace("&quot;", '"', $params); // 转换一个双引号
        $params = json_decode($params, true);

        //接口地址 必填
        $this->c->host = $this->gateWayUrl . $url;
        //版本号  必填
        $this->c->version = $version;

        //其他请求参数 根据接口文档需求选填
        $params['pageSize'] = $length;
        $params['pageId'] = $page;

        //$result = $c->request($parame,'POST'); //接口特别说明需要POST请求才使用
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if($result['code'] != 0) {
            $res=array(
                'code'=>$result['code'],
                'msg'=>$result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下
            $list = $result['data']['list'];

            $res = array(
                'code'=>0,
                'msg'=>'成功',
                'data'=>$list
            );
        }

        return $res;
    }

    private function paramConvert($val) {
        $goodsParam = new \Common\Model\GoodsParamModel();

        $goodsParam->id = $val['id'];
        $goodsParam->goodsId = $val['goodsId'];
        $goodsParam->itemLink = $val['itemLink'];
        $goodsParam->title = $val['title'];
        $goodsParam->desc = $val['desc'];
        $goodsParam->cid = $val['tbcid'];
        $goodsParam->mainPic = $val['mainPic'];
        $goodsParam->marketingMainPic = $val['marketingMainPic'];
        $goodsParam->video = $val['video'];
        $goodsParam->originalPrice = $val['originalPrice'];
        $goodsParam->actualPrice = $val['actualPrice'];
        $goodsParam->discounts = $val['discounts'];
        $goodsParam->commissionType = $val['commissionType'];
        $goodsParam->commissionRate = $val['commissionRate'];
        $goodsParam->couponLink = $val['couponLink'];
        $goodsParam->couponTotalNum = $val['couponTotalNum'];
        $goodsParam->couponReceiveNum = $val['couponReceiveNum'];
        $goodsParam->couponStartTime = $val['couponStartTime'];
        $goodsParam->couponEndTime = $val['couponEndTime'];
        $goodsParam->couponPrice = $val['couponPrice'];
        $goodsParam->couponConditions = $val['couponConditions'];
        $goodsParam->monthSales = $val['monthSales'];
        $goodsParam->twoHoursSales = $val['twoHoursSales'];
        $goodsParam->dailySales = $val['dailySales'];
        $goodsParam->brand = $val['brand'];
        $goodsParam->brandId = $val['brandId'];
        $goodsParam->brandName = $val['brandName'];
        $goodsParam->createTime = $val['createTime'];
        $goodsParam->activityType = $val['activityType'];
        $goodsParam->activityStartTime = $val['activityStartTime'];
        $goodsParam->activityStartTime = $val['activityStartTime'];
        $goodsParam->shopType = $val['shopType'];
        $goodsParam->haitao = $val['haitao'];
        $goodsParam->sellerId = $val['sellerId'];
        $goodsParam->shopName = $val['shopName'];
        $goodsParam->shopLevel = $val['shopLevel'];
        $goodsParam->descScore = $val['descScore'];
        $goodsParam->dsrScore = $val['dsrScore'];
        $goodsParam->dsrPercent = $val['dsrPercent'];
        $goodsParam->shipScore = $val['shipScore'];
        $goodsParam->shipPercent = $val['shipPercent'];
        $goodsParam->serviceScore = $val['serviceScore'];
        $goodsParam->servicePercent = $val['servicePercent'];
        $goodsParam->hotPush = $val['hotPush'];
        $goodsParam->teamName = $val['teamName'];
        $goodsParam->quanMLink = $val['quanMLink'];
        $goodsParam->hzQuanOver = $val['hzQuanOver'];
        $goodsParam->yunfeixian = $val['yunfeixian'];
        $goodsParam->estimateAmount = $val['estimateAmount'];
        $goodsParam->shopLogo = $val['shopLogo'];
        $goodsParam->specialText = $val['specialText'];
        $goodsParam->freeshipRemoteDistrict = $val['freeshipRemoteDistrict'];
        $goodsParam->goldSellers = $val['goldSellers'];
        $goodsParam->extInfo = array(
            'dtk' => array(
                'dtitle' => $val['dtitle'],
                'cid' => $val['cid'],
                'subcid' => $val['subcid'],
            )
        );

        // 商品详情
        $goodsParam->detailPics = $val['detailPics'];
        $goodsParam->imgs = $val['imgs'];
        $goodsParam->reimgs = $val['reimgs'];

        // 榜单相关数据
        $goodsParam->top = $val['top'];
        $goodsParam->keyWord = $val['keyWord'];
        $goodsParam->upVal = $val['upVal'];
        $goodsParam->hotVal = $val['hotVal'];

        $param = json_decode(json_encode($goodsParam), true);
        $param = array_filter($param);

        return $param;
    }

    /**
     * 淘口令解析
     */
    public function amoyPasswordAnalysis($tkl) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/parse-taokouling';
        //版本号  必填
        $this->c->version = 'v1.0.0';

        $params['content'] = $tkl;
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if ($result['code'] != 0) {
            $res = array(
                'code' => $result['code'],
                'msg' => $result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下

            $res = array(
                'code' => 0,
                'msg' => '成功',
                'data' => $result['data']
            );
        }

        return $res;
    }

    /**
     * 官方活动链接
     */
    public function officialEventLink($params) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/category/get-tb-topic-list';
        //版本号  必填
        $this->c->version = 'v1.2.0';

        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if ($result['code'] != 0) {
            $res = array(
                'code' => $result['code'],
                'msg' => $result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下

            $res = array(
                'code' => 0,
                'msg' => '成功',
                'data' => $result['data']
            );
        }

        return $res;
    }
    /**
     * 生成朋友圈文案
     */
    public function momentsCopywriting($page,$length) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/goods/friends-circle-list';
        //版本号  必填
        $this->c->version = 'v1.3.0';
        $params['pageSize'] = $length;
        $params['pageId'] = $page;
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if ($result['code'] != 0) {
            $res = array(
                'code' => $result['code'],
                'msg' => $result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下

            $res = array(
                'code' => 0,
                'msg' => '成功',
                'data' => $result['data']
            );
        }

        return $res;
    }
    /**
     * 订单查询接口
     */
    public function orderQueryInterface($params) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/get-order-details';
        //版本号  必填
        $this->c->version = 'v1.0.0';
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if ($result['code'] != 0) {
            $res = array(
                'code' => $result['code'],
                'msg' => $result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下

            $res = array(
                'code' => 0,
                'msg' => '成功',
                'data' => $result['data']
            );
        }

        return $res;
    }

    /**
     * 淘系万能解析
     */
    public function taoSystemUniversalAnalysis($params) {
        //接口地址 必填
        $this->c->host = $this->gateWayUrl . '/api/tb-service/parse-content';
        //版本号  必填
        $this->c->version = 'v1.0.0';
        $result = $this->c->request($params);
        $result = json_decode($result, true);

        if ($result['code'] != 0) {
            $res = array(
                'code' => $result['code'],
                'msg' => $result['msg'],
            );
        }else {
            // 获取到列表数据后，按照我们系统的字段名，转换一下

            $res = array(
                'code' => 0,
                'msg' => '成功',
                'data' => $result['data']
            );
        }

        return $res;
    }
}
