<?php
/**
 * 短视频/直播与商品关联管理类
 */
namespace Common\Model;
use Think\Model;

class ShortLiveGoodsModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('goods_id','require','商品标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
        array('user_id','require','用户标识不能为空！',self::EXISTS_VALIDATE),          //存在验证，必填
    );

    /**
     * 获取某记录单个值
     */
    public function getVal($whe, $field = 'id', $list = false, $sort = 'sort desc, id asc')
    {
        $res = null;

        if ($whe) {
            $whe['is_lose'] = 1;

            if (!isset($whe['is_status'])) {
                $whe['is_status'] = 1;
            }

            if ($list) {
                $res = $this->where($whe)->order($sort)->getField($field, true);
            } else {
                $res = $this->where($whe)->getField($field);
            }
        }

        return $res;
    }

    /**
     * 获取单条数据记录
     */
    public function getOne($whe, $field = '*')
    {
        $res = null;

        if ($whe) {
            $whe['is_lose'] = 1;

            if (!isset($whe['is_status'])) {
                $whe['is_status'] = 1;
            }

            $res = $this->field($field)->where($whe)->find();
        }

        return $res;
    }

    /**
     * 获取列表数据记录
     */
    public function getList($whe, $field = '*', $sort = 'sort desc,id desc')
    {
        $res = null;

        if ($whe) {
            $whe['is_lose'] = 1;

            if (!isset($whe['is_status'])) {
                $whe['is_status'] = 1;
            }

            $res = $this->field($field)->where($whe)->order($sort)->select();
        }

        return $res;
    }

     /**
     * 获取列表记录数
     */
    public function getCount($whe, $field = 'id')
    {
        $res = 0;

        if ($whe) {
            $whe['is_lose'] = 1;

            if (!isset($whe['is_status'])) {
                $whe['is_status'] = 1;
            }

            $res = $this->where($whe)->count($field);
        }

        return $res;
    }

    /**
     * 获取推荐商品列表
     */
    public function getGoodsData($type, $whe, $at_id, $limit, $page, $platform = '', $sort = 'id desc', $goods_list = [])
    {
        $data                   = [];
        $field                  = 'goods_id,user_id,from,is_explain,id';
        $whe['is_status']       = isset($whe['is_status']) ? $whe['is_status'] : 1;
        $whe['is_lose']         = isset($whe['is_lose']) ? $whe['is_lose'] : 1;

        // 微信小程序
        if ($platform == 'applet' && $type != 'the') {
            $whe['from']= ['neq', 'tb'];
        }

        // 我的橱窗
        if ($type == 'thing') {
            unset($whe['is_status']);
            $field              = 'DISTINCT goods_id,from';   // 不可在添加其他字段查询 因为有去重筛选
            $sg_arr             = $this->field($field)->where($whe)->page($page, $limit)->order($sort)->select();

        // 短视频
        } elseif ($type == 'short') {
            $field              = 'short_id,'. $field;
            $sg_arr             = $this->where($whe)->getField($field);

        // 本场直播的商品列表  或者 后台假直播商品
        } elseif ($type == 'the' || $type == 'ad_fake') {
            $sort               = 'sort desc,id asc';
            $sg_arr             = $this->field($field)->where($whe)->order($sort)->select();

        // 直播的列表展示的一个商品
        } elseif ($type == 'live' || $type == 'fake') {
            $field              = $type == 'fake' ? 'user_id,'. $field : 'site_id,'. $field;
            $whe['is_explain']  = 'load';
            $sg_arr_one         = $this->where($whe)->getField($field);

            $whe['is_explain']  = 'not';
            $sg_arr_two         = $this->where($whe)->order($sort)->getField($field);

            $sg_arr_one         = $sg_arr_one ? $sg_arr_one : [];
            $sg_arr_two         = $sg_arr_two ? $sg_arr_two : [];
            $sg_arr             = $sg_arr_one + $sg_arr_two;

        // 直播回放列表展示的一个商品
        } elseif ($type == 'record') {
            $field              = 'site_id,'. $field;
            $sg_arr_one         = $this->where($whe)->select();
            $sg_arr             = $sg_arr_one ? $sg_arr_one : [];
        }

        // 我的橱窗 总数量
        if ($type == 'thing') {
            $all_list           = $this->field($field)->where($whe)->select();
            $data['total_num']  = $all_list ? (int)count($all_list) : 0;
        }

        // 自己传输的假列表
        if ($type == 'package' && $goods_list) {
            $sg_arr             = $goods_list;
        }

        if ($sg_arr) {
            $User       = new \Common\Model\UserModel();
            $Goods      = new \Common\Model\GoodsModel();
            $UserGroup  = new \Common\Model\UserGroupModel();

            // 查询用户会员组
            $user_msg   = $User->getUserMsg($at_id);
            $group_msg  = $user_msg ? $UserGroup->getGroupMsg($user_msg['group_id']) : [];
            $fee_user   = $group_msg ? $group_msg['fee_user'] : 0;

            // 淘宝类库
            Vendor('tbk.tbk','','.class.php');
            $Tbk        = new \tbk();
            $ip         = getIP();
            $tb_gid     = '';
            $tb_data    = S('tb_data');
            $tb_data    = $tb_data ? $tb_data : [];

            // 京东类库
           /*  Vendor('JingDong.JingDong','','.class.php');
            $JindDong   = new \JindDong(); */
            $Jingtuitui = new \Common\Controller\JingtuituiController();
            $jd_gid     = [];
            $jd_data    = S('jd_data');
            $jd_data    = $jd_data ? $jd_data : [];

            // 拼多多类库
            Vendor('pdd.pdd','','.class.php');
            $Pdd        = new \pdd();
            $pdd_gid    = [];
            $pdd_data    = S('pdd_data');
            $pdd_data    = $pdd_data ? $pdd_data : [];

            // 唯品会类库
            Vendor('vip.vip','','.class.php');
            $Vip=new \vip();
            $vip_gid    = [];
            $vip_data   = S('vip_data');
            $vip_data   = $vip_data ? $vip_data : [];

            // 自营
            $self_gid   = $self_data  = [];


            // 商品消息
            $goods_demo = [
                'goods_id'      => '0000',
                'goods_url'     => '',
                'goods_name'    => '商品名称',
                'img'           => WEB_URL . '/Public/Upload/GoodsCat/5d09a2bcea2bc964.jpg',
                'price'         => '0',
                'old_price'     => '0',
                'sales_volume'  => '0',
                'from'          => 'tb',
                'coupon_amount' => '0',
                'commission'    => '0',
            ];

            // 短视频列表显示的商品
            if ($type == 'short') {
                $sm_list                    = json_decode(file_get_contents('http://'. $_SERVER['HTTP_HOST'] .'/app.php?c=Tbk&a=getGoodsSmoke'), true);
                $smokes                     = (isset($sm_list['data']['list']) && $sm_list['data']['list']) ? $sm_list['data']['list'] : [];
                $goods_demo['smokes']       = $smokes;             // 商品烟雾
                $goods_demo['critic_list']  = [];                  // 商品评论
            }

            // 获取商品ID组
            foreach ($sg_arr as $k => $v) {
                if ($v['from'] == 'tb' && !isset($tb_data[$v['goods_id']])) {
                    $tb_gid    .= $v['goods_id'] . ',';
                } elseif ($v['from'] == 'jd' && !in_array($v['goods_id'], $jd_gid)) {
                    $jd_gid[]   = $v['goods_id'];
                } elseif ($v['from'] == 'pdd' && !in_array($v['goods_id'], $pdd_gid)) {
                    $pdd_gid[]  = $v['goods_id'];
                } elseif ($v['from'] == 'vip' && !in_array($v['goods_id'], $vip_gid)) {
                    $vip_gid[] = $v['goods_id'];
                } elseif ($v['from'] == 'self' && !in_array($v['goods_id'], $self_gid)) {
                    $self_gid[] = $v['goods_id'];
                }
            }

            // 淘宝商品列表
            $tb_gid     = $tb_gid ? substr($tb_gid, 0,-1) : 0;
            if ($tb_gid) {
                $tb_res     = $Tbk->getItemList($tb_gid, '2', $ip);
                if ($tb_res && isset($tb_res['data']['list']) && $tb_res['data']['list']) {
                    foreach ($tb_res['data']['list'] as $v) {
                        $tb_data[$v['num_iid']] = $v;
                    }

                    S('tb_data', $tb_data, 86400);     // 保存三天缓存
                }
            }

            // 京东商品列表 （暂时用京推推的接口）
            if ($jd_gid) {
                $jd_data    = $Jingtuitui::jdConciseList($jd_gid, $fee_user, $user_msg['group_id']);

                S('jd_data', $jd_data, 86400);     // 保存三天缓存
            }

            // 拼多多商品列表
            if ($pdd_gid) {
                $pdd_data   = $Pdd->pddConciseList($pdd_gid, $fee_user, $user_msg['group_id']);

                S('pdd_data', $pdd_data, 86400);     // 保存三天缓存
            }

            // 唯品会商品列表
            if ($vip_gid) {
                $vip_goods = $Vip->getGoodsDetail($vip_gid);

                if ((isset($vip_goods['data']['list']) || isset($vip_goods['data']['goods_details'])) && $vip_goods) {
                    if (isset($vip_goods['data']['list'])) {
                        foreach ($vip_goods['data']['list'] as $val) {
                            $vip_data[$val['goodsId']] = $val;
                        }
                    } else {
                        $vip_data[$vip_goods['data']['goods_details']['goodsId']] = $vip_goods['data']['goods_details'];
                    }

                    S('vip_data', $vip_data, 86400);     // 保存1天缓存
                }
            }

            // 自营商品列表
            if ($self_gid) {
                $self_data  = $Goods->where(['goods_id' => ['in', $self_gid]])
                              ->getField('goods_id,goods_name,img,old_price,price,sales_volume,virtual_volume,fx_profit_money');
            }

            // 组装商品信息
            foreach ($sg_arr as $val) {
                $goods_one              = $goods_demo;
                $goods_one['goods_id']  = $val['goods_id'];
                $goods_one['from']      = $val['from'];

                if ($type == 'short') {
                    shuffle($smokes);                       // 商品烟雾 随机排序
                    $goods_one['smokes']= $smokes;          // 商品烟雾
                }

                // 淘宝商品信息
                if ($val['from'] == 'tb') {
                    if (isset($tb_data[$val['goods_id']])) {
                        $goods_tem                      = $tb_data[$val['goods_id']];

                        $goods_one['goods_url']         = $goods_tem['item_url'];// 链接
                        $goods_one['goods_name']        = $goods_tem['title']; // 名称
                        $goods_one['img']               = $goods_tem['pict_url'];  // 主图
                        $goods_one['price']             = substr(sprintf("%.3f", ($goods_tem['zk_final_price']*1 - $goods_tem['coupon_amount']*1)), 0, -1);        // 价格
                        $goods_one['old_price']         = substr(sprintf("%.3f", $goods_tem['zk_final_price']), 0, -1);    // 原价
                        $goods_one['sales_volume']      = $goods_tem['volume'];// 销量
                        $goods_one['coupon_amount']     = (string)$goods_tem['coupon_amount'];  // 优惠券金额

                        // 根据会员组计算相应佣金   保留2位小数，四舍五不入
                        $goods_one['commission']        = $goods_tem['commission'] ? $goods_tem['commission']*$fee_user/100 : $fee_user/100;
                        $goods_one['commission']        = substr(sprintf("%.3f", $goods_one['commission']), 0, -1);
                    }

                // 京东商品信息
                } elseif ($val['from'] == 'jd') {
                    if (isset($jd_data[$val['goods_id']])) {
                        $goods_one                      = $jd_data[$val['goods_id']];

                        if ($type == 'short') {
                            $goods_one['critic_list']   = $goods_demo['critic_list'];
                            $goods_one['smokes']        = $smokes;          // 商品烟雾
                        }
                    }

                // 拼多多商品信息
                } elseif ($val['from'] == 'pdd') {
                    if (isset($pdd_data[$val['goods_id']])) {
                        $goods_one                  = $pdd_data[$val['goods_id']];

                        if ($type == 'short') {
                            $goods_one['critic_list']   = $goods_demo['critic_list'];
                            $goods_one['smokes']        = $smokes;          // 商品烟雾
                        }
                    }

                // 唯品会商品信息
                } elseif ($val['from'] == 'vip') {
                    if (isset($vip_data[$val['goods_id']])) {
                        $goods_tem                      = $vip_data[$val['goods_id']];

                        $goods_one['goods_url']         = $goods_tem['destUrl'];// 链接
                        $goods_one['goods_name']        = $goods_tem['goodsName']; // 名称
                        $goods_one['img']               = $goods_tem['goodsMainPicture'];  // 主图
                        $goods_one['price']             = substr(sprintf("%.3f", $goods_tem['vipPrice']), 0, -1);        // 价格
                        $goods_one['old_price']         = substr(sprintf("%.3f", $goods_tem['marketPrice']), 0, -1);    // 原价
                        $goods_one['sales_volume']      = '100';// 销量
                        $goods_one['coupon_amount']     = (string)$goods_tem['commissionRate'];  // 优惠券金额

                        // 根据会员组计算相应佣金   保留2位小数，四舍五不入
                        $goods_one['commission']        = $goods_tem['commission'] ? $goods_tem['commission']*$fee_user/100 : $fee_user/100;
                        $goods_one['commission']        = substr(sprintf("%.3f", $goods_one['commission']), 0, -1);
                    }

                // 自营商品信息
                } elseif ($val['from'] == 'self') {
                    if (isset($self_data[$val['goods_id']])) {
                        $temp                       = $goods_one;
                        $goods_one                  = $self_data[$val['goods_id']];

                        // 获取之前的数据
                        $goods_one['from']          = $temp['from'];
                        $goods_one['goods_url']     = $temp['goods_url'];

                        $goods_one['old_price']     = substr(sprintf("%.3f", $goods_one['old_price']), 0, -1);
                        $goods_one['price']         = $goods_one['price']/100;        // 价格
                        $goods_one['price']         = substr(sprintf("%.3f", $goods_one['price']), 0, -1);
                        $goods_one['sales_volume']  = (string)($goods_one['sales_volume']+$goods_one['virtual_volume']);  // 销量
                        unset($goods_one['virtual_volume']);
                        $goods_one['img']           = (is_url($goods_one['img']) ? $goods_one['img'] : WEB_URL . $goods_one['img']);    // 主图

                        // 佣金  保留2位小数，四舍五不入
                        $goods_one['commission']    = 0;
                        $goods_one['commission']    = substr(sprintf("%.3f", $goods_one['commission']), 0, -1);
                        $hostTreatModel = new \Common\Model\HostTreatModel();
                        $ShortLiveGoodsModel = new \Common\Model\ShortLiveGoodsModel();
                        #判定是否存在入库商品列表
                        $item = $ShortLiveGoodsModel->getOne(['from'=>'self','goods_id'=>$self_data[$val['goods_id']]]);
                        if($item)
                        {
                            $is_has = 1;
                        }
                        $userGroup = new \Common\Model\UserGroupModel();
                        $groupList = $userGroup->getGroupList();
                        $groupVipMsg 	= end($groupList);
                        $userCommission = $hostTreatModel->getCommissionByUser($at_id, $is_has,$self_data[$val]['fx_profit_money']/100, $groupVipMsg['id']);
                        $goods_one['commission'] 	= $userCommission['userHasCommission'];

                        // 优惠券金额
                        $goods_one['coupon_amount'] = $temp['coupon_amount'];

                        if ($type == 'short') {
                            // 获取之前的数据
                            $goods_one['smokes']       = $temp['smokes'];
                            $goods_one['critic_list']  = $temp['critic_list'];
                        }
                    }
                }

                // 添加商品讲解状态
                $goods_one['is_explain']            = isset($val['is_explain']) ? $val['is_explain'] : 'not';


                // 微信小程序 淘宝返回null (不改变数组的列)
                if ($platform == 'applet' && $type == 'the' && $val['from'] == 'tb') {
                    $goods_one                      = null;
                }

                // 未拿到数据商品删除
                if (is_array($goods_one) && $goods_one['price'] == '0' && $goods_one['old_price'] == '0') {
                    unset($goods_one);

                    // 清除缓存
                    if ($val['from'] == 'tb') {
                        S('tb_data', null);
                    } elseif ($val['from'] == 'jd') {
                        S('jd_data', null);
                    } elseif ($val['from'] == 'pdd') {
                        S('pdd_data', null);
                    } elseif ($val['from'] == 'vip') {
                        S('vip_data', null);
                    }

                    // 该为失效记录
                    if (isset($val['id'])) {
                        $this->where(['id' => $val['id']])->save(['is_lose' => 0]);
                    }

                } else {
                    if ($type == 'short') {
                        $data[$val['short_id']] = $goods_one;
                    } elseif ($type == 'live') {
                        $data[$val['site_id']]  = $goods_one;
                    } elseif ($type == 'fake') {
                        $data[$val['user_id']]  = $goods_one;
                    } elseif ($type == 'record') {
                        $data[$val['site_id']][]= $goods_one;
                    } elseif ($type == 'ad_fake') {
                        $data[$val['id']]       = $goods_one;
                    } else {
                        $data[]                 = $goods_one;
                    }
                }
            }
        }

        return $data;
    }


    /**
     * 商品讲解回调记录数据 / 直播过程中选品添加数据
     */
    public function callback($room_id, $uid, $data, $type)
    {
        if ($room_id && $uid && $data && $type) {
            $date               = date('Y-m-d H:i:s');
            $date_null          = '0000-00-00 00:00:00';

            // 查询房间号是否存在
            $r_whe              = ['room_id' => $room_id];
            $u_whe              = ['user_id' => $uid];
            $LiveRoom           = new \Common\Model\LiveRoomModel();
            $lr_one             = $LiveRoom->where($r_whe)->where($u_whe)->getField('room_id');

            if ($lr_one) {
                // 本直播场次
                $LiveSite       = new \Common\Model\LiveSiteModel();
                $ls_one         = $LiveSite->where(['start_time' => ['elt', $date], 'end_time' => $date_null])->where($r_whe)->order('site_id desc')->getField('site_id');

                if ($ls_one && isset($data['goods'])) {
                    $site_live_whe          = ['site_id' => $ls_one, 'type' => 'live'];
                    $LiveGoodsExplain       = new \Common\Model\LiveGoodsExplainModel();

                    $this->startTrans();   // 启用事务
                    try {

                        //// 商品讲解回调记录数据
                        if (isset($data['type']) && $type == 'live_goods') {
                            $gf_whe             = ['goods_id' => $data['goods']['goods_id'], 'from' => $data['goods']['from']];
                            $s_whe              = ['site_id' => $ls_one];

                            // 查询记录是否存在
                            $g_one              = $this->getVal(array_merge($gf_whe, $site_live_whe));

                            if ($g_one) {
                                    //// 开始讲解处理
                                    if ($data['type'] == 'start') {
                                        $load   = $this->getVal(array_merge($u_whe, $s_whe, ['is_explain' => 'load', 'type' => 'live']));

                                        // 正在讲解更新为已讲解
                                        if ($load != $g_one) {
                                            $this->where(['id' => $load])->setField('is_explain', 'yet');
                                            $LiveGoodsExplain->where(['slg_id' => $load, 'end_explain' => $date_null])->setField('end_explain', $date);
                                        }

                                        // 未讲解更新为正在讲解
                                        $lge_one = $LiveGoodsExplain->where(['slg_id' => $g_one, 'start_explain' => ['elt', $date], 'end_explain' => $date_null])->getField('id');
                                        if (!$lge_one) {
                                            $this->where(['id' => $g_one])->setField('is_explain', 'load');
                                            $LiveGoodsExplain->add(['start_explain' => $date, 'slg_id' => $g_one]);
                                        }

                                    //// 结束讲解处理
                                    } elseif ($data['type'] == 'end') {
                                        // 更新为已讲解
                                        $this->where(['id' => $g_one])->setField('is_explain', 'yet');
                                        $LiveGoodsExplain->where(['slg_id' => $g_one, 'end_explain' => $date_null])->setField('end_explain', $date);
                                    }
                            }


                        //// 直播过程中选品添加数据
                        } elseif ($type == 'add_goods') {
                            $ins_goods  = [];

                            // 已添加的商品
                            $yet_goods  = $this->getList($site_live_whe, 'site_id,goods_id,from');

                            foreach ($data['goods'] as $key => $val) {
                                $temp = [
                                    'site_id'   => $ls_one,
                                    'goods_id'  => $val['goods_id'],
                                    'from'      => $val['from'],
                                ];

                                if (!in_array($temp, $yet_goods)) {
                                    $ins_goods[] = $temp;
                                }
                            }

                            // 组装在插入数据
                            if ($ins_goods) {
                                foreach ($ins_goods as $key => $val) {
                                    $ins_goods[$key]['user_id']     = $uid;
                                    $ins_goods[$key]['type']        = 'live';
                                    $ins_goods[$key]['add_time']    = $date;
                                }

                                // 插入数据
                                $this->addAll($ins_goods);
                            }
                        }

                        // 事务提交
                        $this->commit();

                    } catch(\Exception $e) {
                        // 事务回滚
                        $this->rollback();
                    }
                }
            }
        }
    }

    /**
     *  假直播商品添加
     */
    public function fakeGoodsAdd($data, $uid, $del = true)
    {
        $new_id                     = [];

        if ($data && $uid) {
            // 组装数组
            $date                   = date('Y-m-d H:i:s');

            // 删除已有的  在添加
            if ($del) {
                $this->where(['user_id' => $uid, 'type' => 'fake', 'is_status' => 1, 'is_lose' => 1])->save(['is_status' => 0]);
            }

            // 添加数据  返回id组
            foreach ($data as $val) {
                $ins                = array_merge($val, ['user_id' => $uid, 'type' => 'fake']);
                $t_id               = $this->getVal(array_merge($ins, ['is_status' => ['in', [0,1]]]));

                if ($t_id) {
                    $this->where(['id' => $t_id])->save(['add_time' => $date, 'is_status' => 1]);
                } else {
                    $ins['add_time']= $date;
                    $new_id[]       = $this->add($ins);
                }
            }
        }

        return $new_id;
    }

    /**
     *  短视频商品添加
     */
    public function shortGoodsAdd($data, $uid, $sid)
    {
        if ($uid && $sid) {
            $id = $this->getVal(['user_id' => $uid, 'short_id' => $sid, 'type' => 'short']);

            if ($data) {
                $data['short_id']   = $sid;
                $data['user_id']    = $uid;
                $data['type']       = 'short';
                $data['add_time']   = date('Y-m-d H:i:s');

                if ($id) {
                    $this->where(['id' => $id])->save($data);
                } else {
                    $this->add($data);
                }
            } else {
                // 无商品  删除原商品记录
                if ($id) {
                    $this->where(['id' => $id])->save(['is_status' => 0]);
                }
            }
        }
    }

}
?>
