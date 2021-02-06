<?php
/**
 * 大淘客淘宝商品管理接口
 */

namespace App\Controller;
use App\Common\Controller\AuthController;


class ThirdGoodsController extends AuthController
{
    /**
     * 获取商品列表
     * @param $page int 页码，默认1
     * @param $length int 页大小，默认10
     */
    public function getGoodsList()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);

        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getGoodsList($page, $limit, []);

            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 获取商品详情
     * @param $id int  来源商品id，如dtk id
     * @param $goodsId string 平台商品id,如淘宝 id
     */
    public function getGoodsDetail()
    {
        $id = trim(I('post.id'));
        $goodsId = trim(I('post.goodsId'));

        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getGoodsDetail($id, $goodsId);

            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 生成口令
     */
    public function createKL()
    {
        $text = trim(I('post.text'));
        $url = trim(I('post.url'));
        $logo = trim(I('post.logo'));
        $userId = trim(I('post.userId'));

        if ($text && $url) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->createKL($text, $url, $logo, $userId);

            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }

        $this->ajaxError();
    }

    /**
     * 超级分类
     */
    public function superClass()
    {
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getSuperCategory();
            if ($res['code'] == 0) {
                $num = count($res['data']);
                for ($i = 0; $i < $num; $i++) {
                    unset($res['data'][$i]['cpic']);
                    $num1 = count($res['data'][$i]['subcategories']);
                    for ($i1 = 0; $i1 < $num1; $i1++) {
                        $res['data'][$i]['commodity'][$i1]['product_id'] = $res['data'][$i]['subcategories'][$i1]['subcid'];
                        $res['data'][$i]['commodity'][$i1]['product_name'] = $res['data'][$i]['subcategories'][$i1]['subcname'];
                    }
                    unset($res['data'][$i]['subcategories']);
                }
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 联盟搜索
     */
    public function allianceSearch()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $data['keyWords'] = trim(I('post.keywords'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getTbService($page, $limit, $data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 高效转链
     */
    public function efficientChainTransfer()
    {
        $goodsId = trim(I('post.goodsId'));
        $relationId = trim(I('post.channelId'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getPrivilegeLink($goodsId, $relationId);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 商品收藏
     */
    public function commodityCollection()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $data['cid'] = trim(I('post.cid'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getCollectList($page, $limit, $data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 发布的商品
     */
    public function productsReleased()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getOwnerGoods($page, $limit, '');
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 商品更新
     */
    public function productUpdate()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getNewestGoods($page, $limit, '');
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 热搜记录
     */
    public function heatSearchRecord()
    {
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getTop100();
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 各大榜单
     */
    public function majorLists()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $data['rankType'] = trim(I('post.rankType'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getRankingList($page, $limit, $data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 大淘客搜索
     */
    public function daTaoKeSearch()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $token = trim(I('post.token'));
        $keyWords = trim(I('post.keyWords'));
        $sort_type = trim(I('post.sort_type'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getDtkSearchGoods($page, $limit, $keyWords, ($sort_type==7) ? 0: $sort_type);
            if ($res['code'] == 0) {
                    $User = new \Common\Model\UserModel();
                    $shortModel = new \Common\Model\ShortLiveGoodsModel();
                    $hostCommissionModel = new \Common\Model\HostTreatModel();
                    $uid            = $User->getUserId($token);
                    $UserGroup = new \Common\Model\UserGroupModel();
                    $vipGroup=$UserGroup->getGroupList();
                    $vipGroupEnd = end($vipGroup);
                    for ($i=0;$i<count($res['data']);$i++)
                    {
                        // 商品是否收藏
                        $TbGoodsCollect=new \Common\Model\TbGoodsCollectModel();
                        $res_exist = $TbGoodsCollect->where(["goods_id"=>$res[$i]['goodsId'], "user_id"=>$uid])->find();
                        if($res_exist)
                        {
                            $is_collect = 'Y';
                        } else {
                            $is_collect = 'N';
                        }
                        $res['data'][$i]['is_collect'] = $is_collect;
                        $res['data'][$i]['mainPic'] 	= is_url($res['data'][$i]['mainPic']) ? $res['data'][$i]['mainPic'] : 'http:' . $res['data'][$i]['mainPic'];
                        #佣金价
                        $commission = $res['data'][$i]['actualPrice']*($res['data'][$i]['commissionRate']/100);
                        $is_has =0;
                        $short_goods= $shortModel->getOne(['from'=>'tb','goods_id'=>$res[$i]['goodsId']]);
                        if($short_goods)
                        {
                            $is_has =1;
                        }
                        $userCommission = $hostCommissionModel->getCommissionByUser($uid,$is_has,$commission,$vipGroupEnd['id']);
                        $res['data'][$i]['commission'] = $userCommission['userHasCommission'];
                        $res['data'][$i]['commission_host'] = $userCommission['hostUserCommission'];
                        $res['data'][$i]['commission_vip'] = $res['data'][$i]['commission_high']  = $userCommission['vipHasCommission'];
                    }
                    if ($sort_type == 7) $res['data'] = array_merge(array_sort($res['data'],'monthSales','asc'));
                $this->ajaxSuccess(['list' => $res['data']]);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 失败商品
     */
    public function failedGoods()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $data['startTime'] = trim(I('post.startTime'));
        $data['endTime'] = trim(I('post.endTime'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getStaleGoodsByTime($page, $limit, $data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 定时拉取
     */
    public function timedPull()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->pullGoodsByTime($page, $limit, '');
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 超级搜索
     */
    public function superSearch()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $data['type'] = trim(I('post.type'));
        $data['keyWords'] = trim(I('post.keyWords'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->listSuperGoods($page, $limit, $data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 生成朋友圈文案
     */
    public function generateCopy()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->momentsCopywriting($page, $limit);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 订单查询接口
     */
    public function checkingOrder()
    {
        $data['pageSize'] = I('post.limit', self::$limit);
        $data['limit'] = I('post.limit', self::$limit);
        $data['startTime'] = trim(I('post.startTime'));
        $data['endTime'] = trim(I('post.endTime'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->orderQueryInterface($data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 淘系万能解析接口
     */
    public function universalAnalysis()
    {
        $data['content'] = trim(I('post.content'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->taoSystemUniversalAnalysis($data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 包邮精选
     */
    public function freeShippingSelection()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        $data['nineCid'] = trim(I('post.nineCid'));
        $data['nineCid'] = 1;
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->opGoodsList($page, $limit, $data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 猜你喜欢
     */
    public function youMayAlsoLike()
    {
        $limit = I('post.limit', self::$limit);
        $id = trim(I('post.id'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->listSimilerGoodsByOpen($limit, $id);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 品牌库
     */
    public function brandLibrary()
    {
        $page = I('post.page', self::$page);
        $limit = I('post.limit', self::$limit);
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->getBrandList($page, $limit);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }

    /**
     * 联想词
     */
    public function associatedWords()
    {
        $data['keyWords'] = trim(I('post.keyWords'));
        $data['type'] = trim(I('post.type'));
        if (IS_POST) {
            $DaTaoKe = new \Common\Model\DaTaoKeModel();
            $res = $DaTaoKe->searchSuggestion($data);
            if ($res['code'] == 0) {
                $this->ajaxSuccess($res['data']);
            } else {
                $this->ajaxError($res['code'], $res['msg']);
            }
        }
    }
}
