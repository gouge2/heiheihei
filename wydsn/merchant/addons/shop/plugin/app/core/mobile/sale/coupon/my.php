<?php

if (!defined('IN_IA')) {
    exit('Access Denied');
}
require_once EWEI_SHOPV2_PLUGIN . 'app/core/page_mobile.php';

class My_EweiShopV2Page extends AppMobilePage
{

    // 获取 个人优惠券列表
    public function getlist()
    {
        global $_W, $_GPC;

        $openid = $_W['openid'];
        $cate = trim($_GPC['cate']);
        $imgname = 'ling';
        $imgname = 'ling';
        $check = 0;
        if (!empty($cate)) {
            if ($cate == 'used') {
                $used = 1;
                $imgname = 'used';
                $check = 1;
            } else {
                $past = 1;
                $imgname = 'past';
                $check = 2;
            }
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 10;
        $time = time();
        $sql = "select d.id,d.couponid,d.gettime,c.timelimit,c.coupontype,c.timedays,c.timestart,c.timeend,c.thumb,c.couponname,c.enough,c.backtype,c.deduct,c.discount,c.backmoney,c.backcredit,c.backredpack,c.bgcolor,c.thumb,c.merchid,c.tagtitle,c.settitlecolor,c.titlecolor from " . tablename('ewei_shop_coupon_data') . " d";
        $sql .= " left join " . tablename('ewei_shop_coupon') . " c on d.couponid = c.id";
        $sql .= " where d.openid=:openid and d.uniacid=:uniacid ";
        if (!empty($past)) {
            $sql .= " and  ( (c.timelimit =0 and c.timedays<>0 and  c.timedays*86400 + d.gettime <unix_timestamp()) or (c.timelimit=1 and c.timeend<unix_timestamp() ))";
        } else if (!empty($used)) {
            $sql .= " and d.used =1 ";
        } else if (empty($used)) {
            $sql .= " and (   (c.timelimit = 0 and ( c.timedays=0 or c.timedays*86400 + d.gettime >=unix_timestamp() ) )  or  (c.timelimit =1 and c.timeend>={$time})) and  d.used =0 ";
        }
        $total = pdo_fetchcolumn($sql, array(':openid' => $openid, ':uniacid' => $_W['uniacid']));
        $sql .= " order by d.gettime desc  LIMIT " . ($pindex - 1) * $psize . ',' . $psize; //类型+最低消费+示使用
        $coupons = set_medias(pdo_fetchall($sql, array(':openid' => $openid, ':uniacid' => $_W['uniacid'])), 'thumb');
        
        pdo_update('ewei_shop_coupon_data', array('isnew' => 0), array('uniacid' => $_W['uniacid'], 'openid' => $_W['openid']));
        if (empty($coupons)) {
            $coupons = array();
        }
        foreach ($coupons as $i => &$row) {
            $row = com('coupon')->setMyCoupon($row, $time);
            $title2 = '';
            if ($row['coupontype'] == '0') {
                if ($row['enough'] > 0) {
                    $title2 = '消费满' . (float)$row['enough'] . '元';
                } else {
                    $title2 = '消费';
                }
            } elseif ($row['coupontype'] == '1') {
                if ($row['enough'] > 0) {
                    $title2 = '充值满' . (float)$row['enough'] . '元';
                } else {
                    $title2 = '充值';
                }
            } elseif ($row['coupontype'] == '2') {
                if ($row['enough'] > 0) {
                    $title2 = '消费满' . (float)$row['enough'] . '元';
                } else {
                    $title2 = '消费';
                }
            }
            if ($row['backtype'] == 0) {
                $title2 = $title2 . '立减' . ((float)$row['deduct']) . '元';
                if ($row['enough'] == '0') {
                    $row['color'] = 'org ';
                    $tagtitle = '代金券';
                } else {
                    $row['color'] = 'blue';
                    $tagtitle = '满减券';
                }
            }
            if ($row['backtype'] == 1) {
                $row['color'] = 'red ';
                $title2 = $title2 . '打' . ((float)$row['discount']) . '折';
                $tagtitle = '打折券';
            }
            if ($row['backtype'] == 2) {
                if ($row['coupontype'] == '0') {
                    $row['color'] = 'red ';
                    $tagtitle = '购物返现券';
                } elseif ($row['coupontype'] == '1') {
                    $row['color'] = 'pink ';
                    $tagtitle = '充值返现券';
                } elseif ($row['coupontype'] == '2') {
                    $row['color'] = 'red ';
                    $tagtitle = '购物返现券';
                }
                if (!empty($row['backmoney']) && $row['backmoney'] > 0) {
                    $title2 = $title2 . '送' . $row['backmoney'] . '元余额';
                }
                if (!empty($row['backcredit']) && $row['backcredit'] > 0) {
                    $title2 = $title2 . '送' . $row['backcredit'] . '积分';
                }
                if (!empty($row['backredpack']) && $row['backredpack'] > 0) {
                    $title2 = $title2 . '送' . $row['backredpack'] . '元红包';
                }
            }
            if ($row['tagtitle'] == '') {
                $row['tagtitle'] = $tagtitle;
            }
            if ($past == 1) {
                $row['color'] = 'disa';
            }
            $row['imgname'] = $imgname;
            $row['check'] = $check;
            $row['title2'] = $title2;

            $row['iconurl'] = $_W['siteroot']."addons/shop/template/mobile/default/static/images/coupon/".$row['imgname'].".png";

            unset($row['timelimit'], $row['coupontype'], $row['timestart'], $row['timeend'], $row['enough'], $row['backtype'], $row['deduct'], $row['discount'], $row['backmoney'], $row['backcredit'], $row['backredpack'], $row['bgcolor'], $row['merchid'],  $row['past'], $row['merchname'], $row['css'], $row['backstr'], $row['backpre'], $row['_backmoney']);
        }
        unset($row);

        $set = m('common')->getPluginset('coupon');

        return app_json(array('list' => $coupons, 'pagesize' => $psize, 'total' => $total, 'closecenter' => intval($set['closecenter'])));
    }

    // 获取优惠券详情
    public function getdetail()
    {
        global $_W, $_GPC;

        $id = intval($_GPC['id']);
        if (empty($id)) {
            return app_error(AppError::$ParamsError);
        }

        $data = pdo_fetch('select * from ' . tablename('ewei_shop_coupon_data') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
        if (empty($data)) {
            if (empty($coupon)) {
                return app_error(AppError::$CouponRecordNotFound);
            }
        }
        $coupon = pdo_fetch('select * from ' . tablename('ewei_shop_coupon') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $data['couponid'], ':uniacid' => $_W['uniacid']));
        if (empty($coupon)) {
            return app_error(AppError::$CouponNotFound);
        }
        $coupon['gettime'] = $data['gettime'];
        $coupon['back'] = $data['back'];
        $coupon['backtime'] = $data['backtime'];
        $coupon['used'] = $data['used'];
        $coupon['usetime'] = $data['usetime'];
        $time = time();
        $coupon = com('coupon')->setMyCoupon($coupon, $time);
        $commonset = m('common')->getPluginset('coupon');
        if ($coupon['descnoset'] == '0') {
            if ($coupon['coupontype'] == '0') {
                $coupon['desc'] = $commonset['consumedesc'];
            } else if ($coupon['coupontype'] == '1') {
                $coupon['desc'] = $commonset['rechargedesc'];
            } else {
                $coupon['desc'] = $commonset['consumedesc'];
            }
        }
        $title2 = '';
        $title3 = '';
        if ($coupon['coupontype'] == '0') {
            if ($coupon['enough'] > 0) {
                $title2 = '满' . ((float)$coupon['enough']) . '元';
            } else {
                $title2 = '购物任意金额';
            }
        } elseif ($coupon['coupontype'] == '1') {
            if ($coupon['enough'] > 0) {
                $title2 = '充值满' . ((float)$coupon['enough']) . '元';
            } else {
                $title2 = '充值任意金额';
            }
        } elseif ($coupon['coupontype'] == '2') {
            if ($coupon['enough'] > 0) {
                $title2 = '满' . ((float)$coupon['enough']) . '元';
            } else {
                $title2 = '购物任意金额';
            }
        }
        if ($coupon['backtype'] == 0) {
            if ($coupon['enough'] == '0') {
                $coupon['color'] = 'org ';
            } else {
                $coupon['color'] = 'blue';
            }
            $title3 = '减' . ((float)$coupon['deduct']) . '元';
        }
        if ($coupon['backtype'] == 1) {
            $coupon['color'] = 'red ';
            $title3 = '打' . ((float)$coupon['discount']) . '折 ';
        }
        if ($coupon['backtype'] == 2) {
            if ($coupon['coupontype'] == '0' || $coupon['coupontype'] == '2') {
                $coupon['color'] = 'red ';
            } else {
                $coupon['color'] = 'pink ';
            }
            if (!empty($coupon['backmoney']) && $coupon['backmoney'] > 0) {
                $title3 = $title3 . '送' . $coupon['backmoney'] . '元余额 ';
            }
            if (!empty($coupon['backcredit']) && $coupon['backcredit'] > 0) {
                $title3 = $title3 . '送' . $coupon['backcredit'] . '积分 ';
            }
            if (!empty($coupon['backredpack']) && $coupon['backredpack'] > 0) {
                $title3 = $title3 . '送' . $coupon['backredpack'] . '元红包 ';
            }
        }
        if ($coupon['past'] || !empty($data['used'])) {
            $coupon['color'] = 'disa';
        }
        $coupon['title2'] = $title2;
        $coupon['title3'] = $title3;
        $goods = array();
        $category = array();
        if ($coupon['limitgoodtype'] != 0) {
            if (!empty($coupon['limitgoodids'])) {
                $where = 'and id in(' . $coupon['limitgoodids'] . ')';
            }
            $goods = pdo_fetchall('select `title` from ' . tablename('ewei_shop_goods') . ' where uniacid=:uniacid ' . $where, array(':uniacid' => $_W['uniacid']), 'id');
        }
        if ($coupon['limitgoodcatetype'] != 0) {
            if (!empty($coupon['limitgoodcateids'])) {
                $where = 'and id in(' . $coupon['limitgoodcateids'] . ')';
            }
            $category = pdo_fetchall('select `name`  from ' . tablename('ewei_shop_category') . ' where uniacid=:uniacid   ' . $where, array(':uniacid' => $_W['uniacid'],), 'id');
        }
        $num = pdo_fetchcolumn('select ifnull(count(*),0) from ' . tablename('ewei_shop_coupon_data') . ' where couponid=:couponid and openid=:openid and uniacid=:uniacid and used=0 '
            , array(':couponid' => $coupon['id'], ':openid' => $_W['openid'], ':uniacid' => $_W['uniacid']));
        $canuse = !$coupon['past'] && empty($data['used']);
        if ($coupon['coupontype'] == 0) {
            $useurl = mobileUrl('sale/coupon/my/showcoupongoods', array('id' => $id));
        } else if ($coupon['coupontype'] == 1) {
            $useurl = mobileUrl('member/recharge');
        } else if ($coupon['coupontype'] == 2) {
            $useurl = mobileUrl('sale/coupon/my');
        }
        $set = $_W['shopset']['coupon'];

        /*处理数据*/
        $detail = array(
            'name' => $coupon['couponname'],
            'merchstr' => $coupon['merchname'] ? '限购' . $coupon['merchname'] . '店铺商品' : '',
            'title2' => $coupon['title2'],
            'title3' => $coupon['title3'],
            'color' => $coupon['color'],
            //'islimitlevel' => $coupon['islimitlevel'],
            //'gettypestr' => $coupon['gettypestr'],
            'coupontype' => $coupon['coupontype'],
            'limitdiscounttype' => $coupon['limitdiscounttype'],
            'limitgoodtype' => $coupon['limitgoodtype'],
            'limitgoodcatetype' => $coupon['limitgoodcatetype'],
            'limitgoods' => $goods,
            'limitcates' => $category,
            'num' => $num
        );
        // 有效期
        if ($coupon['timestr'] == '0') {
            $detail['usestr'] = '永久有效';
        } else {
            if ($coupon['timestr'] == '1') {
                $detail['usestr'] = '即' . $coupon['gettypestr'] . '日内 ' . $coupon['timedays'] . ' 天有效';
            } else {
                $detail['usestr'] = ' ' . $coupon['timestr'];
            }
        }

        // 领取按钮
        if (empty($coupon['coupontype'])) {
            $detail['getstr'] = "立即去选商品使用";
        } elseif ($coupon['coupontype'] == '1') {
            $detail['getstr'] = "立即去充值";
        } else {
            $detail['getstr'] = "返回我的优惠券";
        }

        // 使用说明
        if ($coupon['descnoset']== '0') {
            if ($coupon['coupontype'] == '0') {
                $detail['desc'] = htmlspecialchars_decode($set['consumedesc']);
            } else if ($coupon['coupontype'] == '1') {
                $detail['desc'] = htmlspecialchars_decode($set['rechargedesc']);
            } else {
                $detail['desc'] = htmlspecialchars_decode($set['consumedesc']);
            }
        } else {
            $detail['desc'] = $coupon['desc'];
        }

        return app_json(array('detail' => $detail));

    }

    /*需要传递的值 key  发送多张优惠券信息对应key
     *
     * 优惠券列表展示页
     *couponnum 优惠券数量
     * 以下是coupon数组的参数
     *coupon-id 优惠券ID
     *coupon-couponname 优惠券名称
     *coupon-title2  优惠券描述文字
     *coupon-tagtitle  标签文字
     *coupon-check 0-正常 1-已使用 2-已过期
     *coupon-color 背景色
     *coupon-thumb logo地址
     *coupon-timestr  有效期文字
     *coupon-past  是否过期
     *coupon-merchname 店铺名称-有的话表示是多商户优惠券
     */

    function showcoupons() {
        global $_W, $_GPC;

        $key = $_GPC['key'];

        $openid = $_W['openid'];


        $time = time();
        $sql = "select d.id,d.couponid,d.gettime,c.timelimit,c.coupontype,c.timedays,c.timestart,c.timeend,c.thumb,c.couponname,c.enough,c.backtype,c.deduct,c.discount,c.backmoney,c.backcredit,c.backredpack,c.bgcolor,c.thumb,c.merchid,c.tagtitle,c.settitlecolor,c.titlecolor from " . tablename('ewei_shop_coupon_sendshow') . " cs";
        $sql.=" inner join " .tablename('ewei_shop_coupon_data').' d  on d.id=cs.coupondataid';
        $sql.=" inner join " . tablename('ewei_shop_coupon') . " c on d.couponid = c.id ";
        $sql.=" where cs.openid=:openid and cs.uniacid=:uniacid and showkey=:key ";


        $sql.=" order by d.gettime desc  "; //类型+最低消费+示使用

        $coupons = set_medias(pdo_fetchall($sql, array(':openid' => $openid, ':uniacid' => $_W['uniacid'],':key' =>$key)), 'thumb');


        if(empty($coupons))
        {
            $coupons=array();
        }


        $new =array();

        foreach ($coupons as $i=>&$row) {

            $imgname = 'ling';
            $row = com('coupon')->setMyCoupon($row, $time);

            $title2 = '';
            if ($row['coupontype'] == '0') {
                if ($row['enough'] > 0) {
                    $title2 = '消费满' . (float)$row['enough'] . '元';
                } else {
                    $title2 = '消费';
                }
            } elseif ($row['coupontype'] == '1') {
                if ($row['enough'] > 0) {
                    $title2 = '充值满' . (float)$row['enough'] . '元';
                } else {
                    $title2 = '充值';
                }
            }
            elseif ($row['coupontype'] == '2') {
                if ($row['enough'] > 0) {
                    $title2 = '消费满' . (float)$row['enough'] . '元';
                } else {
                    $title2 = '消费';
                }
            }

            if ($row['backtype'] == 0) {
                $title2 = $title2 . '立减' . ((float)$row['deduct']) . '元';
                if($row['enough']=='0')
                {
                    $row['color']='orange ';
                    $tagtitle = '代金券';
                }
                else
                {
                    $row['color']='blue';
                    $tagtitle = '满减券';
                }
            }
            if ($row['backtype'] == 1) {
                $row['color'] = 'red ';
                $title2 = $title2 . '打' . ((float)$row['discount']) . '折';
                $tagtitle = '打折券';
            }
            if ($row['backtype'] == 2) {
                if($row['coupontype']=='0')
                {
                    $row['color']='red ';
                    $tagtitle = '购物返现券';
                }
                elseif($row['coupontype']=='1')
                {
                    $row['color']='pink ';
                    $tagtitle = '充值返现券';
                }
                elseif($row['coupontype']=='2')
                {
                    $row['color']='red ';
                    $tagtitle = '购物返现券';
                }

                if (!empty($row['backmoney']) && $row['backmoney'] > 0) {
                    $title2 = $title2 . '送' . $row['discount'] . '元余额';
                }
                if (!empty($row['backcredit']) && $row['backcredit'] > 0) {
                    $title2 = $title2 . '送' . $row['discount'] . '积分';
                }
                if (!empty($row['backredpack']) && $row['backredpack'] > 0) {
                    $title2 = $title2 . '送' . $row['discount'] . '元红包';
                }
            }

            if($row['tagtitle']=='')
            {
                $row['tagtitle'] =  $tagtitle;
            }

            $check =0;
            if($row['used']==1)
            {
                $check=1;
                $imgname = 'used';
            }else if(($row['timelimit']==0&&$row['timedays']!=0&&$row['timedays']*86400+$row['gettime']<time())||($row['timelimit']==1&&$row['timeend']<time()))
            {
                $check=2;
                $row['color']='disa';
                $imgname = 'past';
            }

            $row['imgname']=$imgname;
            $row['check']=$check;
            $row['title2'] = $title2;

            $new[] =array(
                'id' =>$row['id'],
                'couponname' =>$row['couponname'],
                'title2' =>$row['title2'],
                'tagtitle' =>$row['tagtitle'],
                'check' =>$row['check'],
                'color' =>$row['color'],
                'thumb' =>$row['thumb'],
                'timestr' =>$row['timestr'],
                'past' =>$row['past'],
                'merchname' =>$row['merchname']
                );

        }

        unset($row);

        return app_json(array('couponnum'=>count($new), 'coupons'=>$new));
    }






    public function showcoupon2()
    {
        global $_W, $_GPC;

        $id = intval($_GPC['id']);
        if(empty($id)){
            return app_error(AppError::$ParamsError);
        }
        $data = pdo_fetch('select c.*  from ' . tablename('ewei_shop_coupon_data') . '  cd inner join  ' . tablename('ewei_shop_coupon') . ' c on cd.couponid = c.id  where cd.id=:id and cd.uniacid=:uniacid and coupontype =0  limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
        if (empty($data)) {
            if (empty($coupon)) {
                return app_error(AppError::$CouponRecordNotFound);
            }
        }
        if (mb_strlen($data['couponname'], 'utf-8') > 7) {
            $data['couponname'] = mb_substr($data['couponname'], 0, 7, 'utf-8') . '...';
        }

        $data['deduct'] = (float)$data['deduct'];

        if ($data['backtype'] == 0) {
            $data['title1'] = '￥' . (float)$data['deduct'];
        } else if ($data['backtype'] == 1) {
            $data['title1'] = (float)$data['discount'] . '折';
        } else if ($data['backtype'] == 2) {
            if (!empty($data['backmoney']) && $data['backmoney'] > 0) {
                $data['title1'] = '送' . $data['backmoney'] . '元余额';
            }
            if (!empty($data['backcredit']) && $data['backcredit'] > 0) {
                $data['title1'] .= '送' . $data['backcredit'] . '积分';
            }
            if (!empty($data['backredpack']) && $data['backredpack'] > 0) {
                $data['title1'] .= '送' . $data['backredpack'] . '元红包';
            }
        }
        if ($data['enough'] > 0) {
            $data['title2'] = '满' . ((float)$data['enough']) . '元使用';
        } else {
            $data['title2'] = '无金额门槛';
        }
        $goods = array();
        $params = array(':uniacid' => $_W['uniacid']);
        $sql = 'select  distinct  g.*  from ';
        $table = '';
        if ($data['limitgoodcatetype'] == 1 && !empty($data['limitgoodcateids'])) {
            $limitcateids = explode(',', $data['limitgoodcateids']);
            if (count($limitcateids) > 0) {
                $table = '(';
                $i = 0;
                foreach ($limitcateids as $cateid) {
                    $i++;
                    if ($i > 1) {
                        $table .= ' union all ';
                    }
                    $table .= 'select * from ' . tablename('ewei_shop_goods') . ' where FIND_IN_SET(' . $cateid . ',cates)';
                }
                $table .= ') g';
            } else {
                $table = tablename('ewei_shop_goods') . ' g';
            }
        } else {
            $table = tablename('ewei_shop_goods') . ' g';
        }
        $where = ' where  g.uniacid=:uniacid and g.bargain =0 and g.status =1 ';
       
        if ($data['limitgoodtype'] == 1 && !empty($data['limitgoodids'])) {
            $where .= ' and g.id in (' . $data['limitgoodids'] . ') ';
        }
        if (!empty($data['merchid'])) {
            $where .= ' and g.merchid = ' . $data['merchid'] . ' and g.checked=0';
        }
        $where .= ' ORDER BY RAND() LIMIT 5 ';
        $sql = $sql . $table . $where;
        $goods = pdo_fetchall($sql, $params);
        foreach ($goods as $i => &$row) {
            $couponprice = (float)$row['minprice'];
            if ($data['backtype'] == 0) {
                $couponprice = $couponprice - (float)$data['deduct'];
            }
            if ($data['backtype'] == 1) {
                $couponprice = $couponprice * $data['discount'] / 10;
            }
            if ($couponprice < 0) {
                $couponprice = 0;
            }
            $row['couponprice'] = $couponprice;
        }
        unset($row);
        $goods = set_medias($goods, 'thumb');

        return app_json(array('detail'=>$data, 'goods'=>$goods));
    }


    /*需要传递的值 key 发送多张优惠券信息对应key
     *
     * 优惠券列表展示页
     *couponnum 优惠券数量
     * 以下是coupon数组的参数
     *id 优惠券ID
     *couponn-title1  优惠券优惠信息
     *couponn-title2  优惠券满减信息
     *couponn-title3  优惠券类型文字
     *
     * goods 优惠券可用商品列表
     * goods -id 商品ID
     * goods - title    商品名称
     * goods -thumb 商品图片链接
     * goods -minprice 商品价格
     * goods -couponprice 商品用券价格
     */
    function showcoupons3() {
        global $_W, $_GPC;

        $key = $_GPC['key'];

        $openid = $_W['openid'];


        $time = time();
        $sql = "select d.id,d.couponid,d.gettime,c.timelimit,c.coupontype,c.timedays,c.timestart,c.timeend,c.thumb,c.couponname,c.enough,c.backtype,c.deduct,c.discount,c.backmoney,c.backcredit,c.backredpack,c.bgcolor,c.thumb,c.merchid,c.tagtitle,c.settitlecolor,c.titlecolor from " . tablename('ewei_shop_coupon_sendshow') . " cs";
        $sql.=" inner join " .tablename('ewei_shop_coupon_data').' d  on d.id=cs.coupondataid';
        $sql.=" inner join " . tablename('ewei_shop_coupon') . " c on d.couponid = c.id ";
        $sql.=" where cs.openid=:openid and cs.uniacid=:uniacid and showkey=:key ";


        $sql.=" order by d.gettime desc  "; //类型+最低消费+示使用

        $coupons = set_medias(pdo_fetchall($sql, array(':openid' => $openid, ':uniacid' => $_W['uniacid'],':key' =>$key)), 'thumb');


        if(empty($coupons))
        {
            $coupons=array();
        }


        $new = array();

        foreach ($coupons as $i=>&$row) {


            if($row['enough']>0)
            {
                $row['title2'] ='满'.((float)$row['enough']).'元使用';
            }
            else
            {
                $row['title2']  = '无金额门槛';
            }

            $newgoods=array();

            if($row['coupontype'] ==0 ||$row['coupontype'] ==2)
            {

                $row['title3'] ='优惠券';

                if ($row['backtype'] == 0)
                {
                    $row['title1'] ='￥'.(float)$row['deduct'];
                }else if ($row['backtype'] == 1)
                {

                    $row['title1']=(float)$row['discount'].'折';
                }else if($row['backtype'] == 2)
                {
                    if (!empty($row['backmoney']) && $row['backmoney'] > 0) {
                        $row['title1'] =  '送' . $row['backmoney'] . '元余额';
                    }
                    if (!empty($row['backcredit']) && $row['backcredit'] > 0) {
                        $row['title1'] .=  '送' . $row['backcredit'] . '积分';
                    }
                    if (!empty($row['backredpack']) && $row['backredpack'] > 0) {
                        $row['title1'] .=  '送' . $row['backredpack'] . '元红包';
                    }
                }

                $goods = array();
                $params = array(':uniacid'=>$_W['uniacid']);

                $sql='select  distinct  g.*  from ';

                $table ='';
                if($row['limitgoodcatetype']==1&&!empty($row['limitgoodcateids']))
                {
                    $limitcateids=explode(',',$row['limitgoodcateids']);
                    if(count($limitcateids)>0)
                    {
                        $table ='(';
                        $i=0;
                        foreach($limitcateids as $cateid)
                        {
                            $i++;
                            if($i>1)
                            {
                                $table .=' union all ';
                            }
                            $table .='select * from '.tablename('ewei_shop_goods').' where FIND_IN_SET('.$cateid.',cates)';
                        }

                        $table .=') g';

                    }else
                    {
                        $table =tablename('ewei_shop_goods').' g';
                    }

                }else
                {
                    $table =tablename('ewei_shop_goods').' g';
                }

                $where =' where  g.uniacid=:uniacid and g.bargain =0 and g.status =1 ';
                if($row['limitgoodtype']==1&&!empty($row['limitgoodids']))
                {
                    $where .=' and g.id in ('.$row['limitgoodids'].') ';
                }


                if(!empty($row['merchid']))
                {
                    $where .=' and g.merchid = '.$row['merchid'].' and g.checked=0';
                }

                $where .=' ORDER BY RAND() LIMIT 5 ';

                $sql =$sql.$table.$where;

                $goods = pdo_fetchall($sql,$params );

                foreach ($goods as $i=>&$row2) {
                    $couponprice =(float)$row2['minprice'];

                    if ($row['backtype'] == 0) {
                        $couponprice = $couponprice -(float)$row['deduct'];

                    }
                    if ($row['backtype'] == 1) {
                        $couponprice = $couponprice *$row['discount']/10;
                    }
                    if($couponprice<0)
                    {
                        $couponprice=0;
                    }
                    $row2['couponprice']=$couponprice;

                    $newgoods[]=array(
                        'id' =>$row['id'],
                        'title' =>$row['title'],
                        'thumb' =>$row['thumb'],
                        'minprice' =>$row['minprice'],
                        'couponprice' =>$row['couponprice']
                    );
                }

                unset($row2);
                $goods = set_medias($goods, 'thumb');

                $row['goods'] = $goods;
            }else
            {
                $row['title3'] ='充值卷';

                if($row['backtype'] == 2)
                {
                    if (!empty($row['backmoney']) && $row['backmoney'] > 0) {
                        $row['title1'] =  '送' . $row['backmoney'] . '元余额';
                    }
                    if (!empty($row['backcredit']) && $row['backcredit'] > 0) {
                        $row['title1'] .=  '送' . $row['backcredit'] . '积分';
                    }
                    if (!empty($row['backredpack']) && $row['backredpack'] > 0) {
                        $row['title1'] .=  '送' . $row['backredpack'] . '元红包';
                    }
                }
            }

            $new[] =array(
                'id' =>$row['id'],
                'title1' =>$row['title1'],
                'title2' =>$row['title2'],
                'title3' =>$row['title3'],
                'goods' =>$newgoods
            );

        }

        return app_json(array('couponnum'=>count($new), 'coupons'=>$new));
        include $this->template();
    }



    public function showcoupongoods() {
        global $_W, $_GPC;

        $id = intval($_GPC['id']);
        if(empty($id)){
            return app_error(AppError::$ParamsError);
        }
        $data = pdo_fetch('select c.*  from ' . tablename('ewei_shop_coupon_data') . '  cd inner join  ' . tablename('ewei_shop_coupon') . ' c on cd.couponid = c.id  where cd.id=:id and cd.uniacid=:uniacid and coupontype =0  limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
        if (empty($data)) {
            if (empty($coupon)) {
                return app_error(AppError::$CouponRecordNotFound);
            }
        }
        $merchid=0;
        if(!empty($data['merchid'])) {
            $merchid =$data['merchid'];
        }
        if(mb_strlen($data['couponname'],'utf-8')>8) {
            $data['couponname']=mb_substr( $data['couponname'], 0, 8, 'utf-8' ).'..';
        }

        return app_json(array('detail'=>array('couponname'=>$data['couponname'])));
    }


    function get_list() {
        global $_GPC, $_W;

        $args = array(
            'pagesize' => 10,
            'page' => intval($_GPC['page']),
            'isnew' => trim($_GPC['isnew']),
            'ishot' => trim($_GPC['ishot']),
            'isrecommand' => trim($_GPC['isrecommand']),
            'isdiscount' => trim($_GPC['isdiscount']),
            'istime' => trim($_GPC['istime']),
            'issendfree' => trim($_GPC['issendfree']),
            'keywords' => trim($_GPC['keywords']),
            'cate' => trim($_GPC['cate']),
            'order' => trim($_GPC['order']),
            'by' => trim($_GPC['by']),
            'couponid'=>trim($_GPC['couponid']),
            'merchid'=>intval($_GPC['merchid'])
        );

        //判断是否开启自选商品
        $plugin_commission = p('commission');
        if ($plugin_commission && intval($_W['shopset']['commission']['level'])>0 && empty($_W['shopset']['commission']['closemyshop']) && !empty($_W['shopset']['commission']['select_goods'])) {
            $mid = intval($_GPC['mid']);
            if (!empty($mid)) {
                $shop = p('commission')->getShop($mid);
                if (!empty($shop['selectgoods'])) {
                    $args['ids'] = $shop['goodsids'];
                }
            }
        }
        return $this->_condition($args);
    }

    private function _condition($args)
    {
        global $_GPC;
        $merch_plugin = p('merch');
        $merch_data = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $args['merchid'] = intval($_GPC['merchid']);
        }

        if (isset($_GPC['nocommission'])) {
            $args['nocommission'] = intval($_GPC['nocommission']);
        }

        $goods = m('goods')->getListbyCoupon($args);

        return app_json( array('list' => $goods['list'], 'total' => $goods['total'], 'pagesize' => $args['pagesize']));
    }

}

