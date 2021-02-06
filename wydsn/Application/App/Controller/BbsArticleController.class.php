<?php
/**
 * by 来鹿 www.lailu.shop
 * 论坛帖子管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Controller\JingtuituiController;
use Common\Model\GoodsModel;
use Common\Model\HaodankuModel;

class BbsArticleController extends AuthController
{
	/**
	 * 获取每日爆款商品列表
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:商品列表
	 */
	public function getGoodsList()
	{
		$where="is_show='Y' and is_check='Y' and check_result='Y' and board_id=1";
		if(trim(I('post.p')))
		{
			$p=trim(I('post.p'));
		}else {
			$p=1;
		}
		if(trim(I('post.per')))
		{
			$per=trim(I('post.per'));
		}else {
			$per=6;
		}
		$BbsArticle=new \Common\Model\BbsArticleModel();
		$list=$BbsArticle->where($where)->field('id,uid,title,description,pubtime,share_num,tb_gid')->page($p,$per)->order("is_top desc,id desc")->select();
		if($list!==false)
		{
			$num=count($list);
			$User=new \Common\Model\UserModel();
			//淘宝客类
			Vendor('tbk.tbk','','.class.php');
			$tbk=new \tbk();
			for($i=0;$i<$num;$i++)
			{
				//获取用户信息
				$uid=$list[$i]['uid'];
				$userMsg=$User->getUserDetail($uid);
				//用户昵称
				if($userMsg['detail']['nickname'])
				{
					$nickname=$userMsg['detail']['nickname'];
				}else {
					$nickname=$userMsg['account'];
				}
				$list[$i]['nickname']=$nickname;
				//用户头像
				//判断头像是否为第三方应用头像
				if($userMsg['detail']['avatar'])
				{
					//判断头像是否为第三方应用头像
					if(is_url($userMsg['detail']['avatar']))
					{
						$list[$i]['avatar']=$userMsg['detail']['avatar'];
					}else {
						$list[$i]['avatar']=WEB_URL.$userMsg['detail']['avatar'];
					}
				}else {
					$list[$i]['avatar']='';
				}

				//获取商品详情
				$num_iid=$list[$i]['tb_gid'];
				//$ip=getIP();
				$ip='';
				$res_tbk=$tbk->getItemDetail($num_iid,$platform='2',$ip,$pid='');
				//商品名称
				$list[$i]['goods_name']=$res_tbk['data']['title'];
				//商品相册
				$list[$i]['small_images']=$res_tbk['data']['small_images'];
				//商品折扣价格
				$list[$i]['zk_final_price']=$res_tbk['data']['zk_final_price'];
				//优惠券面额
				$list[$i]['coupon_amount']=$res_tbk['data']['coupon_amount'];
				//佣金
				$commission=$res_tbk['data']['commission']*0.9;
				//保留2位小数，四舍五不入
				$commission=substr(sprintf("%.3f",$commission),0,-1);
				$list[$i]['commission']=$commission;
			}
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
	 * 更新分享次数
	 * @param int $id:帖子ID
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 */
	public function updateShareNum()
	{
		if(trim(I('post.id')))
		{
			$id=trim(I('post.id'));
			$BbsArticle=new \Common\Model\BbsArticleModel();
			//浏览量加1
			$res_share=$BbsArticle->where("id=$id")->setInc('share_num');
			if($res_share!==false)
			{
				$res=array(
						'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
						'msg'=>'成功',
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
	 * 获取帖子文章列表
	 * @param int $board_id:论坛版块ID
	 * @param int $search:搜索内容
	 * @param int $p:页码，默认第1页
	 * @param int $per:每页条数，默认6条
	 * @return array
	 * @return @param code:返回码
	 * @return @param msg:返回码说明
	 * @return @param data:返回数据
	 * @return @param data->list:帖子文章列表
	 */
	public function getArticleList()
	{
        $where = "is_show='Y' and is_check='Y' and check_result='Y'";
        if (trim(I('post.board_id'))) {
            $board_id = trim(I('post.board_id'));
            $where .= " and board_id='$board_id'";
        }
        if (trim(I('post.search'))) {
            $search = trim(I('post.search'));
            $where .= " and (title like '%$search%' or keyword like '%$search%' or description like '%$search%' or content like '%$search%' or mob_text like '%$search%')";
        }
        if (trim(I('post.p'))) {
            $p = trim(I('post.p'));
        } else {
            $p = 1;
        }
        if (trim(I('post.per'))) {
            $per = trim(I('post.per'));
        } else {
            $per = 6;
        }

        if ($board_id == 1) {
            $checkSource = defined('CHECKSOURCE') ? CHECKSOURCE : '2';
            if ($checkSource == 1) {
                $haodanku = new HaodankuModel();
                $res = $haodanku->getPopularDaily($p);
                $list = [];
                if ($res['code'] == 1 ) {
                    $arrList = $res['data'];
                    foreach ($arrList as $key => $value) {
                        $temp['id'] = 0;
                        $temp['uid'] = 0;
                        $temp['board_id'] = $board_id;
                        $temp['title'] = $value['show_content'];
                        $temp['img'] = WEB_URL .'/Public/static/admin/img/logo.png';
                        $temp['mob_text'] = '';
                        $temp['clicknum'] = 0;
                        $temp['pubtime'] = date('Y-m-d H:i:s',$value['show_time']);
                        $temp['share_num'] = $value['dummy_click_statistics'];
                        $temp['nickname'] = '';
                        $temp['source'] = "self";
                        $temp['avatar'] = WEB_URL .'/Public/static/admin/img/logo.png';
                        $temp['source_list']['goods_id'] = $value['itemid'];
                        $temp['source_list']['goods_img'] = $value['sola_image'];
                        $temp['source_list']['goods_price'] = $value['itemprice'];
                        $temp['source_list']['goods_rebate_price'] = $value['itemendprice'];
                        $temp['source_list']['goods_coupon_amount'] = $value['couponmoney'];
                        $temp['source_list']['goods_name'] = $value['itemtitle'];
                        $temp['source_list']['goods_commision'] = 0;
                        $temp['source_list']['goods_small_images'] = $value['itempic'];
                        $temp['source_list']['goods_url'] = $value['itemid'];
                        $temp['source_list']['goods_comment'] = $value['comment'];
                        $list[] = $temp;
                    }
                }
                $data = array('list' => $list);
                $this->ajaxSuccess($data);
            }
        }

        $BbsArticle = new \Common\Model\BbsArticleModel();
        $list = $BbsArticle->where($where)->field('id,uid,board_id,title,keyword,description,img,mob_text,mob_img,clicknum,pubtime,share_num,source,tb_gid')->page($p, $per)->order("is_top desc,id desc")->select();
        if ($list !== false) {
            $num = count($list);
            $User = new \Common\Model\UserModel();
            for ($i = 0; $i < $num; $i++) {
                //获取用户信息
                $uid = $list[$i]['uid'];
                $userMsg = $User->getUserDetail($uid);
                //用户昵称
                if ($userMsg['detail']['nickname']) {
                    $nickname = $userMsg['detail']['nickname'];
                } else {
                    $nickname = $userMsg['account'];
                }
                $list[$i]['nickname'] = $nickname;
                //用户头像
                //判断头像是否为第三方应用头像
                if ($userMsg['detail']['avatar']) {
                    //判断头像是否为第三方应用头像
                    if (is_url($userMsg['detail']['avatar'])) {
                        $list[$i]['avatar'] = $userMsg['detail']['avatar'];
                    } else {
                        $list[$i]['avatar'] = WEB_URL . $userMsg['detail']['avatar'];
                    }
                } else {
                    $list[$i]['avatar'] = '';
                }

                if ($list[$i]['img'] !== null) {
                    $imageUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$list[$i]['img'];
                    $list[$i]['img'] = $imageUrl;
                }

                if ($list[$i]['mob_img'] !== null) {
                    $mob_imgs = json_decode($list[$i]['mob_img']);
                    foreach ($mob_imgs as $key => $value) {
                        $imageUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$value;
                        $mob_imgs[$key] = $imageUrl;
                    }
                    $list[$i]['mob_img'] = $mob_imgs;
                }

                $list[$i]['source_list'] = new \stdClass();
                switch ($list[$i]['source']) {
                    case "tb":
                        $list[$i]['source_list'] = $this->getTaobaokInfo($list[$i]['tb_gid']);
                        break;
                    case "jd":
                        $list[$i]['source_list'] = $this->getJingdongInfo($list[$i]['tb_gid'], $list[$i]['jd_pid']);
                        break;
                    case "pdd":
                        $list[$i]['source_list'] = $this->getPddInfo($list[$i]['tb_gid']);
                        break;
                    case "self":
                        $list[$i]['source_list'] = $this->getSelfInfo($list[$i]['tb_gid']);
                        break;
                }

                $BusinessSchool[$i] = [
                    'id' => $list[$i]['id'],
                    'uid' => $list[$i]['uid'],
                    'board_id' => $list[$i]['board_id'],
                    'title' => $list[$i]['title'],
                    'img' => $list[$i]['img'],
                    'mob_text' => $list[$i]['mob_text'],
                    'clicknum' => $list[$i]['clicknum'],
                    'pubtime' => $list[$i]['pubtime'],
                    'share_num' => $list[$i]['share_num'],
                    'nickname' => $list[$i]['nickname'],
                ];
            }
            // 商学院
            if ($board_id == 3) {
                $list = $BusinessSchool;
            }


            $data = array(
                'list' => $list
            );
            $res = array(
                'code' => $this->ERROR_CODE_COMMON['SUCCESS'],
                'msg' => '成功',
                'data' => $data
            );

        } else {
            //数据库错误
            $res = array(
                'code' => $this->ERROR_CODE_COMMON['DB_ERROR'],
                'msg' => $this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
            );
        }
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
	}

    /**
     * 淘宝
     * @param $num_ids
     * @return array
     */
    public function getTaobaokInfo($num_ids)
    {
        $retData = new \stdClass();
        Vendor('tbk.tbk', '', '.class.php');
        $tbk = new \tbk();
        $resources=$tbk->getItemDetail($num_ids,$platform='2','',$pid='');

        if ($resources['code'] !== 0) {
            return $retData;
        }
        if ($resources['data'] == '') {
            return $retData;
        }
        $ret = $resources['data'];
        $retData = array(
            'goods_id' => $ret['num_iid'],
            'goods_img' => $ret['pict_url'],
            'goods_price' => $ret['reserve_price'] ?? '',    // 原价
            'goods_rebate_price' => $ret['zk_final_price'] ?? '',  // 折扣价
            'goods_coupon_amount' => $ret['coupon_amount'] ?? '',  // 优惠券金额
            'goods_url' => $ret['content_url'],
            'goods_name' => $ret['title'],
            'goods_commision' => $ret['commission'] ?? '',
        );
        $retData['goods_small_images'] = [];
        $retData['goods_small_images'] = $ret['small_images']['string'];
        return $retData;
	}

    /**
     * 京东
     * @param $num_ids
     * @return array
     */
    public function getJingdongInfo($num_ids,$pid)
    {
        $retData = new \stdClass();
        $Jingtuitui = new JingtuituiController();
        $resources = $Jingtuitui::getGoodsDet($num_ids);

        if (!$resources) {
            return $retData;
        }
        $retData = array(
            'goods_id' => $resources['skuId'],
            'goods_img' => $resources['imgUrl'],
            'goods_price' => substr(sprintf("%.3f", $resources['priceInfo']['lowestPrice']), 0, -1) ?? '',
            'goods_rebate_price' => substr(sprintf("%.3f", $resources['priceInfo']['lowestCouponPrice']), 0, -1) ?? '',
            'goods_coupon_amount' => $resources['couponInfo']['couponList'][0]['discount'],
            'goods_name' => $resources['goodsName'],
            'goods_commision' => substr(sprintf("%.3f", ($resources['commissionInfo']['commission'] / 100)), 0, -1) ?? '',
        );
        $retData['goods_small_images'] = []; // 商品轮播
        if (isset($resources['imageInfo']['imageList'])) {
            foreach ($resources['imageInfo']['imageList'] as $key => $val) {
                if (isset($val['url'])) {
                    $retData['goods_small_images'][] = $val['url'];
                }
            }
        }
        $retData['goods_url'] = $Jingtuitui::getGoodsCouponLink($num_ids, $resources['coupon_link'], $pid);

        return $retData;
	}

    /**
     * 拼多多
     * @param $num_ids
     * @return array
     */
    public function getPddInfo($num_ids)
    {
        $goods_id_list 	= "[$num_ids]";
        $retData = new \stdClass();
        Vendor('pdd.pdd', '', '.class.php');
        $pdd = new \pdd();
        $resources = $pdd->getGoodsDetail($goods_id_list);
        if ($resources['code'] !== 0) {
            return $retData;
        }
        $ret = $resources['data']['goods_details'];

        $retData = array(
            'goods_id' => $ret['goods_id'],
            'goods_img' => $ret['goods_image_url'],
            'goods_price' => substr(sprintf("%.3f", ($ret['min_normal_price'] / 100)), 0, -1) ?? '',
            'goods_rebate_price' => substr(sprintf("%.3f", ($ret['min_group_price'] / 100)), 0, -1) ?? '',
            'goods_coupon_amount' => sprintf("%.3f", $ret['coupon_min_order_amount']/100),
            'goods_url' => $ret['url_list'],
            'goods_name' => $ret['goods_name'],
            'goods_commision' => $ret['promotion_rate'] ?? '',
        );
        $retData['goods_small_images'] = []; // 商品轮播
        $retData['goods_small_images'] = $ret['goods_gallery_urls'];

        return $retData;
	}

    public function getSelfInfo($num_ids)
    {
        $retData = new \stdClass();
        $goods = new GoodsModel();
        $resources = $goods->getGoodsDetail($num_ids);
        if (!$resources) {
            return $retData;
        }

        $retData = array(
            'goods_id' => $resources['goods_id'],
            'goods_price' => $resources['price'] ?? '',
            'goods_rebate_price' => $resources['price'] ?? '',
            'goods_coupon_amount' => '',
            'goods_url' => '',
            'goods_name' => $resources['goods_name'],
            'goods_commision' => $resources['promotion_rate'] ?? '',
        );
        $retData['goods_img'] = '';
        $imageUrl = (is_url($resources['img']) ? $resources['img'] : WEB_URL . $resources['img']);
        $retData['goods_img'] = $imageUrl;
        $retData['goods_small_images'] = []; // 商品轮播

        return $retData;
	}
}
?>
