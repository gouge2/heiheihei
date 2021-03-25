<?php
/**
 * by 翠花 http://http://livedd.com
 * 商品管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class GoodsController extends AuthController
{
    /**
     * 获取热门搜索
     * @param number $num:条数，默认10条
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:热门搜索列表
     */
    public function getHotSearch()
    {
        if(trim(I('post.num')))
        {
            $num=trim(I('post.num'));
        }else {
            $num=10;
        }
        $HotSearch=new \Common\Model\HotSearchModel();
        $list=$HotSearch->limit(0,$num)->order('num desc,id asc')->select();
        if($list!==false)
        {
            $data=array(
                'list'=>$list
            );
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                'msg'=>'成功',
                'data'=>$data
            );
        }else {
            //数据库错误
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取商品列表
     * @param int $cat_id:商品分类ID
     * @param string $goods_name:商品名称
     * @param int $p:页码，默认第1页
     * @param int $per:每页条数，默认10条
     * @param float $price1:起始价格
     * @param float $price2:截止价格
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:商品列表
     */
    public function getGoodsList()
    {
        $token 	= trim(I('post.token'));
        $where 	= "is_show='Y'";

        //商品分类
        if (trim(I('post.cat_id'))) {
            $cat_id 		= trim(I('post.cat_id'));
            //获取所有子分类
            $GoodsCat 		= new \Common\Model\GoodsCatModel();
            $subCatList		= $GoodsCat->getSubCatList($cat_id);
            $all_catid 		= $cat_id.',';
            foreach ($subCatList as $l){
                $all_catid .= $l['cat_id'] .',';
            }
            $all_catid 		= substr($all_catid, 0,-1);
            $where 		   .= " and cat_id in ($all_catid)";
        }
        //商品名称
        if (trim(I('post.keyword'))) {
            $goods_name 	= trim(I('post.keyword'));
            $where		   .= " and goods_name like '%$goods_name%'";
            //保存热门搜索
            $HotSearch 		= new \Common\Model\HotSearchModel();
            $HotSearch->statistics($goods_name);
        }

        //商品名称
        if (trim(I('post.goods_name'))) {
            $goods_name 	= trim(I('post.goods_name'));
            $where		   .= " and goods_name like '%$goods_name%'";
            //保存热门搜索
            $HotSearch 		= new \Common\Model\HotSearchModel();
            $HotSearch->statistics($goods_name);
        }
        //商品名称
        if (trim(I('post.keyword'))) {
            $goods_name 	= trim(I('post.keyword'));
            $where		   .= " and goods_name like '%$goods_name%'";
            //保存热门搜索
            $HotSearch 		= new \Common\Model\HotSearchModel();
            $HotSearch->statistics($goods_name);
        }

        //价格区间搜索
        if (trim(I('post.price1'))) {
            $price1 = trim(I('post.price1'))*100;
            $where .= " and price>=$price1";
        }
        if (trim(I('post.price2'))) {
            $price2 = trim(I('post.price2'))*100;
            $where .= " and price<=$price2";
        }

        //多商户
        if (trim(I('post.uid'))) {
            $uids 	= trim(I('post.uid'));
            $MerchUser              = new \Common\Model\ShopMerchUserModel();
            $shop_id = $MerchUser->where(['openid'=>'lailu_'.$uids,'status'=>1])->getField('id');
            if ($shop_id) {
                $where .= " and shop_id =$shop_id";
            }
        }

        if (trim(I('post.shop_id'))) {
            $shop_id = trim(I('post.shop_id'));
            $where .= " and shop_id =$shop_id";
            $MerchUser              = new \Common\Model\ShopMerchUserModel();
            $shop_openid = $MerchUser->where(['id'=>$shop_id,'status'=>1])->getField('openid');
            if($shop_openid)
            {
                $uids = substr($shop_openid,6);
            }
        }

        //分页
        $p 				= trim(I('post.p')) ? trim(I('post.p')) : 1;
        $per 			= trim(I('post.per')) ? trim(I('post.per')) : 10;


        $Goods 			= new \Common\Model\GoodsModel();
        $UserGroup 		= new \Common\Model\UserGroupModel();
        $User 			= new \Common\Model\UserModel();
        $Shopcart 		= new \Common\Model\ShopcartModel();
        $ShortLiveGoodsModel = new \Common\Model\ShortLiveGoodsModel();
        $hostTreatModel = new \Common\Model\HostTreatModel();
        $UserDetail     = new \Common\Model\UserDetailModel();
        $Merch          = new \Common\Model\MultiMerchantModel();
        $MerchUser      = new \Common\Model\ShopMerchUserModel();
        $ShortLiveGoods = new \Common\Model\ShortLiveGoodsModel();
        $uid        	= $token ? $User->where(['token' => $token])->getField('uid') : 0;

        $list 			= $Goods->where($where)->field('goods_id,is_fx_goods,fx_profit_money,cat_id,goods_name,goods_code,img,description,brand_id,clicknum,old_price,price,inventory,give_point,sales_volume,virtual_volume,createtime,is_gift_goods,group_id,is_custom_time,custom_time')->order("is_top desc,sort desc,goods_id asc")->page($p,$per)->select();
        $ud = '';
        if ($list !== false) {
            if ($shop_id) {
                // 个人信息  判断头像是否为第三方应用头像
                $ud  = $UserDetail->where(['user_id'=>$uids])->field('nickname,avatar')->find();
                if ($ud['avatar'] && !is_url($ud['avatar'])) {
                    $ud['avatar']   = (is_url($ud['avatar']) ? $ud['avatar'] : WEB_URL . $ud['avatar']);
                }
                $whe['user_id'] = $uids;
                $whe['is_status'] = 1;
                $whe['is_lose'] = 1;
                $field = 'DISTINCT goods_id,from';
                $all_list = $ShortLiveGoods->where($whe)->field($field)->select();
                $ud['user_id'] = empty($uids)?0:$uids;
                $ud['total_num']  = $all_list ? (int)count($all_list) : 0;
                $list_num 			= $Goods->where(['shop_id'=> $shop_id,'is_show'=>'Y'])->select();
                $ud['shop_num']  = $list_num ? (int)count($list_num) : 0;


                // 多商户标识
                $ud['merchant'] = 0;
                // 是否开启多商户
                $mectype = $Merch->where(['type'=>1,'settle_in'=>2])->find();
                if ($mectype) {
                    $ud['merchant'] = 1;
                }

                $shopuser = $MerchUser->where(['openid'=>'lailu_'.$uids, 'status'=>1])->field('accounttime,desc')->find();

                // 店铺标识
                $ud['shop'] = 0;
                if (date('Y-m-d',$shopuser['accounttime']) > date('Y-m-d')) {
                    $ud['shop'] = 1;
                    // 公告
                    $ud['desc1'] = $shopuser['desc'];
                }
            }
            $num = count($list);

            for ($i = 0; $i < $num; $i++) {
                //价格
                $list[$i]['price'] 	= $list[$i]['price']/100;
                //销量--取消，前端自己计算
//				$list[$i]['sales_volume']=$list[$i]['sales_volume']+
                #确认开启分销的情况下
                $list[$i]['fx_goods_type']=0;
                $list[$i]['from']='self';
                //判断下是否礼包商品，升到那个等级，多久时间



                if ($list[$i]['is_gift_goods'] == 'Y') {
                    $groupMsg 		= $UserGroup->getGroupMsg($list[$i]['group_id']);

                    //判断下该会员组是否有关闭升级通道
                    if ($groupMsg['is_gift'] == 'Y') {
                        $list[$i]['group_name'] = $groupMsg['title'];
                        if ($list[$i]['is_custom_time'] !== 'Y') {
                            $list[$i]['custom_time'] = $groupMsg['time_limit'];
                        }
                        $list[$i]['fx_goods_type']=3;
                    } else {
                        //会员组关闭则取消该商品礼包升级功能
                        $data = [
                            'is_gift_goods' => 'N',
                            'group_id' 		=> 'null',
                            'custom_time' 	=> 0
                        ];
                        $goods_id 					= $list[$i]['goods_id'];
                        $Goods->where("goods_id={$goods_id}")->save($data);
                        $list[$i]['is_gift_goods'] 	= 'N';
                        $list[$i]['group_id']	 	= 'null';
                    }
                }
                #获取商品佣金
                $is_has = 0;
                $list[$i]['commission'] 	= 0;
                $list[$i]['commission_host']	 	= 0;
                if(IS_DISTRIBUTION=='Y' && $list[$i]['is_fx_goods']=='Y')
                {
                    $list[$i]['fx_goods_type']=1;
                    #判定是否存在入库商品列表
                    $item = $ShortLiveGoodsModel->getOne(['from'=>'self','goods_id'=>$list[$i]['goods_id']]);
                    if($item)
                    {
                        $is_has = 1;
                        $list[$i]['fx_goods_type']=2;
                    }
                    $userCommission = $hostTreatModel->getCommissionByUser($uid,$is_has,$list[$i]['fx_profit_money']/100);
                    $list[$i]['commission'] 	= $userCommission['userHasCommission'];
                    $list[$i]['commission_host']	 	= $userCommission['hostUserCommission'];
                }
                // 购物车数量
                $cart_num  = $uid ? $Shopcart->shopcartNum($uid) : 0;
                // 判断是否收藏
                $GoodsCollect = new \Common\Model\GoodsCollectModel();
                $res_c        = $GoodsCollect->where("goods_id='{$list[$i][goods_id]}' and user_id='$uid'")->getField('id');
                $is_collect   = $res_c ? 'Y' : 'N';
                $list[$i]['is_collect'] = $is_collect;
                $list[$i]['img'] = (is_url($list[$i]['img']) ? $list[$i]['img'] : WEB_URL . $list[$i]['img']);
                $list[$i]['sales_volume'] = $list[$i]['virtual_volume']+$list[$i]['sales_volume'];
            }
            $res = [
                'code' 	=> $this->ERROR_CODE_COMMON['SUCCESS'],
                'msg'	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
                'data' 	=> ['list' => $list, 'cart_num' => $cart_num,'user' => $ud]
            ];
        }else {
            //数据库错误
            $res = [
                'code' 	=> $this->ERROR_CODE_COMMON['DB_ERROR'],
                'msg' 	=> $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
            ];
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
	
	/**
	 * 获取商品详情
	 * @param int $goods_id:商品ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->goodsMsg:商品详情
	 * @return @param data->imglist:商品相册
	 * @return @param data->skulist:商品sku配置
	 * */
	 public function getGoodsMsg()
	 {
		$token 	= trim(I('post.token'));

	 	if(trim(I('post.goods_id')))
		{
			$goods_id=trim(I('post.goods_id'));
			$Goods=new \Common\Model\GoodsModel();
            $ShortLiveGoodsModel = new \Common\Model\ShortLiveGoodsModel();
            $hostTreatModel = new \Common\Model\HostTreatModel();
            $userGroup = new \Common\Model\UserGroupModel();
            $groupList = $userGroup->getGroupList();
            $groupVipMsg 	= end($groupList);
            $goodsMsg=$Goods->getGoodsDetail($goods_id);
            $ShortLiveGoodsModel = new \Common\Model\ShortLiveGoodsModel();
            $ShopUserModel = new \Common\Model\ShopMerchUserModel();
            if($goodsMsg!==false)
            {
                //将内容中的图片替换为绝对路径，不用了，后台处理了
                $Ueditor=new \Admin\Common\Controller\UeditorController();

                $goodsMsg['content']=$Ueditor->changeImagePath($goodsMsg['content']);

                //获取商品相册
                $GoodsImg=new \Common\Model\GoodsImgModel();
                $imglist=$GoodsImg->getImgList($goods_id);
                //获取商品sku配置
//				$skulist=array();
                $GoodsSku=new \Common\Model\GoodsSkuModel();
                $shopModel = new \Common\Model\ShopMerchUserModel();
                $skulist=$GoodsSku->getSkuList($goods_id);

                $User 			= new \Common\Model\UserModel();
                $Shopcart 		= new \Common\Model\ShopcartModel();
                $uid        	= $token ? $User->where(['token' => $token])->getField('uid') : 0;
                if(IS_DISTRIBUTION=='Y' && $goodsMsg['is_fx_goods']=='Y')
                {
                    $goodsMsg['fx_goods_type']=1;
                    #判定是否存在入库商品列表
                    $item = $ShortLiveGoodsModel->getOne(['from'=>'self','goods_id'=>$goodsMsg['goods_id']]);
                    if($item)
                    {
                        $goodsMsg['fx_goods_type']=2;
                    }
                }
                #返回商户信息
                $goodsMsg['from'] ='self';
                $shop = $ShopUserModel->getOne(['id'=>$goodsMsg['shop_id']],'merchname,logo,id');
                $logo = $shopModel->getLogo();
                $is_gift_goods = $goodsMsg['is_gift_goods']=='Y'?'Y':'N';
                $goodsMsg['shopInfo']=empty($shop) && $is_gift_goods=='N' ?['merchname'=>'自营商品','logo'=>$logo[1]['logo'],'id'=>0]:(empty($shop) && $is_gift_goods=='Y' ?['merchname'=>'会员商品','logo'=>$logo[2]['logo'],'id'=>0]:['merchname'=>$shop['merchname'],'logo'=>$logo[3]['logo'],'id'=>$shop['id']]);
                $goodsMsg['shopInfo'] = array_merge($goodsMsg['shopInfo'],['is_gift_goods'=>$is_gift_goods]);
                // 购物车数量
                $cart_num  = $Shopcart->shopcartNum($uid);
                #商品会员组信息
                $group = null;
                if($goodsMsg['is_gift_goods'] == 'Y' && $goodsMsg['group_id']>0)
                {
                    $groupInfo = $userGroup->getGroupMsg($goodsMsg['group_id']);
                    $group['title'] = '购买该会员商品可升级为'.$groupInfo['title'];
                    $group['time'] = ($groupInfo['time_limit']==0)?'永久':$groupInfo['time_limit']."天";
                }
                $goodsMsg['group'] = $group;
                #获取商品佣金
                $is_has = 0;
                $goodsMsg['commission'] 	= 0;
                $goodsMsg['commission_host']	 	= 0;
                $goodsMsg['commission_vip']	=0;
                if(IS_DISTRIBUTION=='Y' && $goodsMsg['is_fx_goods']=='Y')
                {
                    $goodsMsg['fx_goods_type']=1;
                    #判定是否存在入库商品列表
                    $item = $ShortLiveGoodsModel->getOne(['from'=>'self','goods_id'=>$goodsMsg['goods_id']]);
                    if($item)
                    {
                        $is_has = 1;
                        $goodsMsg['fx_goods_type']=2;
                    }
                    $userCommission = $hostTreatModel->getCommissionByUser($uid,$is_has,$goodsMsg['fx_profit_money']/100,$groupVipMsg['id']);
                    $goodsMsg['commission'] 	= $userCommission['userHasCommission'];
                    $goodsMsg['commission_host']	 	= $userCommission['hostUserCommission'];
                    $goodsMsg['commission_vip']	 	= $userCommission['vipHasCommission'];
                }
                $goodsMsg['spec'] = null;
                if($goodsMsg['ren_good_id']>0 && $goodsMsg['shop_id']>0)
                {
                    #获取规格属性
                    $spec = $Goods->getGoodsSpec($goodsMsg['goods_id']);
                    $goodsMsg['spec'] = $spec;
                }
                $goodsMsg['sales_volume'] = $goodsMsg['sales_volume'] + $goodsMsg['virtual_volume'];
                $data=array(
                    'goodsMsg' => $goodsMsg,
                    'imglist'  => $imglist,
                    'skulist'  => $skulist,
                    'cart_num' => $cart_num,
                );
                $res=array(
                    'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'  => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['SUCCESS']],
                    'data' => $data
                );
            }else {
                //数据库错误
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 添加商品浏览记录
     * @param string $token:身份认证
     * @param int $goods_id:商品ID
     * @param int $host_id:主播ID
     * @param string $from:商品来源
     * @param int $live_id:主播房间ID
     * @param int $site_id:直播场次
     * @return @param code:返回码
     * @return @param msg:返回码说明
     */
    public function addUserBrowse() {
        if(trim(I('post.token')) and trim(I('post.goods_id')) and trim(I('post.host_id')) and trim(I('post.from')))
        {
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0) {
                //用户身份不合法
                $res=$res_token;
            }else {
                $data = array(
                    'user_id' => $res_token['uid'],
                    'goods_id' => trim(I('post.goods_id')),
                    'host_id' => trim(I('post.host_id')),
                    'from' => trim(I('post.from')),
                    'live_id' => trim(I('post.live_id')),
                    'site_id' => trim(I('post.site_id')),
                    'create_time' => date('Y-m-d H:i:s', time()),
                );

                $HostUserBrowseModel = new \Common\Model\HostUserBrowseModel();

                if(!$HostUserBrowseModel->create($data)) {
                    //验证不通过
                    $res=array(
                        'code'=>$this->ERROR_CODE_COMMON['PARAMETER_FORMAT_ERROR'],
                        'msg'=>$HostUserBrowseModel->getError()
                    );
                }else {
                    $res_add=$HostUserBrowseModel->add($data, array(), true);

                    if ($res_add==false) {
                        //数据库错误
                        $res=array(
                            'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                            'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                        );
                    }

                    $res=array(
                        'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg'=>'成功'
                    );
                }

            }
        } else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    public function test()
    {
        $url = "https://oauth.taobao.com/authorize?response_type=code&view=web&redirect_uri=https%3A%2F%2Fwww.dataoke.com%2Fpmc%2Foauth.html&state=345605&client_id=23116944";
        $redirect_uri = urlencode("http://www.xxx.com/member/aaa/");
        header("Location: https://oauth.taobao.com/authorize?response_type=code&view=web&redirect_uri=https%3A%2F%2Fwww.dataoke.com%2Fpmc%2Foauth.html&state=345605&client_id=23116944");


        $redirect_uri = urlencode('http://www.xxx.com/member/aaa/');
        $code = $_GET['code'];
        $secret = "48f1ee79faaaaaaaaaaaaaa851f";
        $get_token_url = 'https://oauth.taobao.com/token?grant_type=authorization_code&client_id=12345678&client_secret='.$secret.'&code='.$code.'&redirect_uri='.$redirect_uri;
        $data = $this->mypost($get_token_url);

//        $res = file_get_contents($url);
//
//        $doc = new \DOMDocument();
//        $doc->loadHtml($res);
//
//        $head = $doc->getElementsByTagName("head")->item(0);
//
//        $js = $doc->createDocumentFragment();
//        $js->appendXml("<script>
//            $('input[name=fm-login-id]').attr('value','18682239897');
//            $('input[name=fm-login-password]').attr('value','wuxibin123A');
//            $('.fm-submit').trigger('click');
//        </script>");
//        $head->appendChild($js);
//
//        echo $doc->saveHtml();
//        die;
    }
}
?>