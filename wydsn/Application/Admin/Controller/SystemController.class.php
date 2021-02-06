<?php
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
use Cassandra\Set;
use Common\Model\SettingModel;
use Common\Model\TaobaoCatModel;
use Common\Model\AdvertHeadModel;
use Common\Model\UserDrawApplyModel;

class SystemController extends AuthController
{
    //获取版本信息，底部展示
    public function versionInfo()
    {
        $version = file_get_contents('./version.txt');
//        $version='';
//        if(VERSION_WAY == 1){
//            if(!$version){
//                if(file_exists('./version.txt')){
//                    $version = file_get_contents('./version.txt');
//                }else{
//                    exit('version.txt is not exists.');
//                }
//            }
//        }else{
//            if(!$version){
//                if(file_exists(DATA_ROOT.'version.php')){
//                    $version = include DATA_ROOT.'version.php';
//                }else{
//                    exit('/data/version.php is not exists.');
//                }
//            }
//        }

//        $apiurl = 'http://safe.taokeyun.cn/check.php?xz=1&v='.$version;
//        $host = 'tao.lailu.live';
//        $hosts = $host . '|' . 'tao.lailu.live';
//        $time = time();
//        $host_url = 'http://' .$host. '/';
//        $site_url_md5 = substr(md5($host_url),-16,-6);
//        $appid = 25;
//        $sqkey = '';
//        $token = md5($time . '|' . $hosts . '|xzphp|'.$site_url_md5);
//        $apiurl.= '&appid='.$appid.'&h=' . $hosts . '&t=' . $time . '&token=' . $token . '&v=' . $version.'&sqkey='.$sqkey;
//        $url = $apiurl . '&a=upgrade';
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/upgrade.php?act=check';
        $html = file_get_contents($url);

        if (!$html) {
            return '请求失败，请刷新试试!';
        }

        return $html;
    }

    public function index_show()
    {
        if($_SESSION['a_group_id']==4)
        {
            $this->display('index_agent');
        }else {
            $User=new \Common\Model\UserModel();
            $TbOrder=new \Common\Model\TbOrderModel();
            $PddOrder=new \Common\Model\PddOrderModel();
            $JingdongOrderDetail=new \Common\Model\JingdongOrderDetailModel();
            $VipOrder=new \Common\Model\VipOrderModel();
            $UserDrawApply=new \Common\Model\UserDrawApplyModel();
            $SelfOrder=new \Common\Model\OrderDetailModel();
            $selfOrdetail=new\Common\Model\OrderModel();
            //统计会员数量
            //总会员数
            $user_allnum=$User->count();
            $this->assign('user_allnum',$user_allnum);
            //普通会员数
            $user_num1=$User->where("group_id=1")->count();
            $this->assign('user_num1',$user_num1);
            //VIP会员
            $this->assign('user_vipnum',$user_allnum-$user_num1);
            //今日新增会员
            $user_today_num=$User->where("date_format(register_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
            $this->assign('user_today_num',$user_today_num);
            //本月新增会员
            $user_month_num=$User->where("date_format(register_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
            $this->assign('user_month_num',$user_month_num);

            //统计财务数据
            //统计所有订单金额
            $tb_order_money=$TbOrder->where("tk_status!='13'")->sum('alipay_total_price');
            $pdd_order_money=$PddOrder->where("order_status in (-1,0,1,2,3,5,8)")->sum('order_amount');
            $jd_order_money=$JingdongOrderDetail->where("validCode in (15,16,17,18)")->sum('estimateCosPrice');
            $vip_order_money=$VipOrder->where("orderSubStatusName!='已失效'")->sum('commissionTotalCost');
            $self_order_money=$selfOrdetail->where("status in(4,5)")->sum('allprice');
            $all_order_money=$tb_order_money+$pdd_order_money/100+$jd_order_money+$vip_order_money+$self_order_money/100;
            $this->assign('all_order_money',$all_order_money);

            //统计所有提现金额
            $all_draw=$UserDrawApply->where("is_check='Y' and check_result='Y'")->sum('money');
            $this->assign('all_draw',$all_draw?$all_draw:0);
            $all_draw_today=$UserDrawApply->where("is_check='Y' and check_result='Y' and date_format(check_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('money');
            $this->assign('all_draw_today',$all_draw_today?$all_draw_today:0);

            //统计淘宝订单数
            //已结算订单
            $tb_order_finished_num=$TbOrder->where("tk_status='3'")->count();
            $this->assign('tb_order_finished_num',$tb_order_finished_num);
            //已付款订单
            $tb_order_pay_num=$TbOrder->where("tk_status='12'")->count();
            $this->assign('tb_order_pay_num',$tb_order_pay_num);
            //今日订单
            $tb_order_today_num=$TbOrder->where("date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
            $this->assign('tb_order_today_num',$tb_order_today_num);
            //本月订单
            $tb_order_month_num=$TbOrder->where("date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
            $this->assign('tb_order_month_num',$tb_order_month_num);

            //统计拼多多订单数
            //已结算订单
            $pdd_order_finished_num=$PddOrder->where("order_status='5'")->count();
            $this->assign('pdd_order_finished_num',$pdd_order_finished_num);
            //已付款订单
            $pdd_order_pay_num=$PddOrder->where("order_status in (0,1,2,3)")->count();
            $this->assign('pdd_order_pay_num',$pdd_order_pay_num);
            //今日订单
            $pdd_order_today_num=$PddOrder->where("date_format(order_create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
            $this->assign('pdd_order_today_num',$pdd_order_today_num);
            //本月订单
            $pdd_order_month_num=$PddOrder->where("date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
            $this->assign('pdd_order_month_num',$pdd_order_month_num);

            //统计京东订单数
            //已结算订单
            $jd_order_finished_num=$JingdongOrderDetail->where("validCode=18")->count();
            $this->assign('jd_order_finished_num',$jd_order_finished_num);
            //已付款订单
            $jd_order_pay_num=$JingdongOrderDetail->where("validCode in (16,17)")->count();
            $this->assign('jd_order_pay_num',$jd_order_pay_num);
            //今日订单
            $jd_order_today_num=$JingdongOrderDetail->where("date_format(orderTime,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
            $this->assign('jd_order_today_num',$jd_order_today_num);
            //本月订单
            $jd_order_month_num=$JingdongOrderDetail->where("date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
            $this->assign('jd_order_month_num',$jd_order_month_num);

            //统计自营订单数
            //已结算订单
            $self_order_finished_num = $SelfOrder->table("lailu_order_detail r")
                ->join("__ORDER__ u on r.order_num = u.order_num")
                ->where("u.status=10 and r.fx_profit_money>0")->count();

            $this->assign('self_order_finished_num',$self_order_finished_num);
            //已付款订单
            $self_order_pay_num = $SelfOrder->table("lailu_order_detail r")
                ->join("__ORDER__ u on r.order_num = u.order_num")
                ->where("u.status in (4,5) and r.fx_profit_money>0")->count();
            $this->assign('self_order_pay_num',$self_order_pay_num);
            //今日订单
            $self_order_today_num = $SelfOrder->table("lailu_order_detail r")
                ->join("__ORDER__ u on r.order_num = u.order_num")
                ->where("r.fx_profit_money>0 and date_format(UNIX_TIMESTAMP(u.create_time),'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
            $this->assign('self_order_today_num',$self_order_today_num);
            //本月订单
            $self_order_month_num = $SelfOrder->table("lailu_order_detail r")
                ->join("__ORDER__ u on r.order_num = u.order_num")
                ->where("r.fx_profit_money>0 and date_format(UNIX_TIMESTAMP(u.create_time),'%Y-%m') = date_format(now(),'%Y-%m')")->count();
            $this->assign('self_order_month_num',$self_order_month_num);
            //统计唯品会订单数
            //已结算订单
//            $vip_order_finished_num=$VipOrder->where("settled=2")->count();
//            $this->assign('vip_order_finished_num',$vip_order_finished_num);
//            //已付款订单
//            $vip_order_pay_num=$VipOrder->where("orderSubStatusName in ('已下单','已付款','已签收','待结算')")->count();
//            $this->assign('vip_order_pay_num',$vip_order_pay_num);
//            //今日订单
//            $vip_order_today_num=$VipOrder->where("date_format(orderTime/1000,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->count();
//            $this->assign('vip_order_today_num',$vip_order_today_num);
//            //本月订单
//            $vip_order_month_num=$VipOrder->where("date_format(orderTime/1000,'%Y-%m')=date_format(now(),'%Y-%m')")->count();
//            $this->assign('vip_order_month_num',$vip_order_month_num);

            //收益统计
            //淘宝总结算收益-未扣除分配给用户的
            $amount_tb=$TbOrder->where("tk_status='3'")->sum('tb_commission');
            //淘宝今日结算收益-未扣除分配给用户的
            $amount_tb_today=$TbOrder->where("tk_status='3' and date_format(create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('tb_commission');
            //淘宝本月结算收益-未扣除分配给用户的
            $amount_tb_month=$TbOrder->where("tk_status='3' and date_format(create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('tb_commission');

            //拼多多总结算收益-未扣除分配给用户的
            $amount_pdd=$PddOrder->where("order_status='5'")->sum('pdd_commission');
            $amount_pdd/=100;
            //拼多多今日结算收益-未扣除分配给用户的
            $amount_pdd_today=$PddOrder->where("order_status='5' and date_format(order_create_time,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('pdd_commission');
            $amount_pdd_today/=100;
            //拼多多本月结算收益-未扣除分配给用户的
            $amount_pdd_month=$PddOrder->where("order_status='5' and date_format(order_create_time,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('pdd_commission');
            $amount_pdd_month/=100;

            //京东总结算收益-未扣除分配给用户的
            $amount_jd=$JingdongOrderDetail->where("validCode=18")->sum('actualFee');
            //京东今日结算收益-未扣除分配给用户的
            $amount_jd_today=$JingdongOrderDetail->where("validCode=18 and date_format(orderTime,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('actualFee');
            //京东本月结算收益-未扣除分配给用户的
            $amount_jd_month=$JingdongOrderDetail->where("validCode=18 and date_format(orderTime,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('actualFee');

            //唯品会总结算收益-未扣除分配给用户的
            $amount_vip=$VipOrder->where("settled=2")->sum('vipCommission');
            //唯品会今日结算收益-未扣除分配给用户的
            $amount_vip_today=$VipOrder->where("settled=2 and date_format(orderTime/1000,'%Y-%m-%d')=date_format(now(),'%Y-%m-%d')")->sum('vipCommission');
            //唯品会本月结算收益-未扣除分配给用户的
            $amount_vip_month=$VipOrder->where("settled=2 and date_format(orderTime/1000,'%Y-%m')=date_format(now(),'%Y-%m')")->sum('vipCommission');

            //总收益
            $amount=$amount_tb+$amount_pdd+$amount_jd+$amount_vip;
            $this->assign('amount_tb',$amount_tb??0);
            $this->assign('amount_pdd',$amount_pdd??0);
            $this->assign('amount_jd',$amount_jd??0);
            $this->assign('amount_vip',$amount_vip??0);
            $this->assign('amount',$amount??0);
            //今日收益
            $amount_today=$amount_tb_today+$amount_pdd_today+$amount_jd_today+$amount_vip_today;
            $this->assign('amount_tb_today',$amount_tb_today??0);
            $this->assign('amount_pdd_today',$amount_pdd_today??0);
            $this->assign('amount_jd_today',$amount_jd_today??0);
            $this->assign('amount_vip_today',$amount_vip_today??0);
            $this->assign('amount_today',$amount_today??0);
            //本月收益
//    	    $amount_month=$amount_tb_month+$amount_pdd_month+$amount_jd_month+$amount_vip_month;
            $this->assign('amount_tb_month',$amount_tb_month??0);
            $this->assign('amount_pdd_month',$amount_pdd_month??0);
            $this->assign('amount_jd_month',$amount_jd_month??0);
            $this->assign('amount_vip_month',$amount_vip_month??0);

            //获取最近30天淘宝订单
            $tb_sql="SELECT count(id) as num,date(create_time) as date FROM __PREFIX__tb_order WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(create_time) GROUP BY date(create_time)";
            $tb_list=M()->query($tb_sql);
            $this->assign('tb_list',$tb_list);

            //获取最近30天京东订单
            $jd_sql="SELECT count(id) as num,date(FROM_UNIXTIME(finishTime, '%Y-%m-%d')) as date FROM __PREFIX__jingdong_order WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(FROM_UNIXTIME(finishTime, '%Y-%m-%d')) GROUP BY date(FROM_UNIXTIME(finishTime, '%Y-%m-%d'))";
            $jd_list=M()->query($jd_sql);
            $this->assign('jd_list',$jd_list);

            //获取最近30天拼多多订单
            $pdd_sql="SELECT count(id) as num,date(FROM_UNIXTIME(order_create_time, '%Y-%m-%d')) as date FROM __PREFIX__pdd_order WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(FROM_UNIXTIME(order_create_time, '%Y-%m-%d')) GROUP BY date(FROM_UNIXTIME(order_create_time, '%Y-%m-%d'))";
            $pdd_list=M()->query($pdd_sql);
            $this->assign('pdd_list',$pdd_list);

            //获取最近30天自营订单
            $self_sql = "SELECT count(id) as num, date(FROM_UNIXTIME(UNIX_TIMESTAMP(u.create_time), '%Y-%m-%d')) as date FROM __PREFIX__order_detail r INNER JOIN __PREFIX__order u on r.order_num = u.order_num WHERE ( r.fx_profit_money>0 and DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(FROM_UNIXTIME(UNIX_TIMESTAMP(create_time), '%Y-%m-%d')) )";
            $self_list=M()->query($self_sql);
            $this->assign('self_list',$self_list);
            $this->display();

            //获取最近30天唯品会订单
//            $vip_sql="SELECT count(id) as num,date(FROM_UNIXTIME(orderTime/1000, '%Y-%m-%d')) as date FROM __PREFIX__vip_order WHERE DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(FROM_UNIXTIME(orderTime/1000, '%Y-%m-%d')) GROUP BY date(FROM_UNIXTIME(orderTime/1000, '%Y-%m-%d'))";
//            $vip_list=M()->query($vip_sql);
//            $this->assign('vip_list',$vip_list);
//            $this->display();
        }
    }

    //站点设置
    public function webset()
    {
        if($_POST) {
            //App名称
            $app_name=I('post.app_name');
            $old_app_name=I('post.old_app_name');
            //苹果版本号
            $version_ios=I('post.version_ios');
            $old_version_ios=I('post.old_version_ios');
            //安卓版本号
            $version_android=I('post.version_android');
            $old_version_android=I('post.old_version_android');
            //苹果下载地址
            $down_ios=I('post.down_ios');
            $old_down_ios=I('post.old_down_ios');
            //安卓下载地址
            $down_android=I('post.down_android');
            $old_down_android=I('post.old_down_android');
            //苹果新版本更新内容
            $update_content_ios=I('post.update_content_ios');
            $old_update_content_ios=I('post.old_update_content_ios');
            //安卓新版本更新内容
            $update_content_android=I('post.update_content_android');
            $old_update_content_android=I('post.old_update_content_android');

            //平台微信号
            $platform_wx=I('post.platform_wx');
            $old_platform_wx=I('post.old_platform_wx');
            //分享淘宝商品网址
            $share_url=I('post.share_url');
            $old_share_url=I('post.old_share_url');
            //分享注册下载网址
            $share_url_register=I('post.share_url_register');
            $old_share_url_register=I('post.old_share_url_register');
            //VIP专用分享网址
            $share_url_vip=I('post.share_url_vip');
            $old_share_url_vip=I('post.old_share_url_vip');

            //网址
            $web_url=I('post.web_url');
            $old_web_url=I('post.old_web_url');
            //网址
            $web_record_number=I('post.web_record_number');
            $old_web_record_number=I('post.old_web_record_number');
            //web_title
            $web_title=I('post.web_title');
            $old_web_title=I('post.old_web_title');
            //keywords
            $keywords=I('post.keywords');
            $old_keywords=I('post.old_keywords');
            //description
            $description=I('post.description');
            $old_description=I('post.old_description');
            //copyright
            $copyright=I('post.copyright');
            $old_copyright=I('post.old_copyright');
            //web_title_en
            $web_title_en=I('post.web_title_en');
            $old_web_title_en=I('post.old_web_title_en');
            //keywords_en
            $keywords_en=I('post.keywords_en');
            $old_keywords_en=I('post.old_keywords_en');
            //description_en
            $description_en=I('post.description_en');
            $old_description_en=I('post.old_description_en');
            //copyright_en
            $copyright_en=I('post.copyright_en');
            $old_copyright_en=I('post.old_copyright_en');

            //copyright_en
            $to_update=I('post.to_update');
            $old_to_update=I('post.old_to_update');

            $to_update_ios=I('post.to_update_ios');
            $old_to_update_ios=I('post.old_to_update_ios');

            $pay_method = '';
            if(I('post.pay_method')){
                $pay_data = array_keys(I('post.pay_method'));
                $pay_method = implode(",",$pay_data);
            }
            $old_pay_method = I('post.old_pay_method');

            //载入系统配置文件
            $str=file_get_contents('./Public/inc/config.php');
            //替换WEB_TITLE
            $find_str_web_title="define('WEB_TITLE','$old_web_title');";
            $replace_str_web_title="define('WEB_TITLE','$web_title');";
//            $str=str_replace($find_str_web_title,$replace_str_web_title,$str);
            if (strpos($str,"define('WEB_TITLE',")!==false){
                $str=str_replace($find_str_web_title,$replace_str_web_title,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//WEB_TITLE'."\r\n".$replace_str_web_title."\r\n".'?>';
            }
            //替换App名称
            $fs_app_name="define('APP_NAME','$old_app_name');";
            $rs_app_name="define('APP_NAME','$app_name');";
//            $str=str_replace($fs_app_name,$rs_app_name,$str);
            if (strpos($str,"define('APP_NAME',")!==false){
                $str=str_replace($fs_app_name,$rs_app_name,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//App名称'."\r\n".$rs_app_name."\r\n".'?>';
            }
            //替换苹果版本号
            $fs_version_ios="define('VERSION_IOS','$old_version_ios');";
            $rs_version_ios="define('VERSION_IOS','$version_ios');";
//            $str=str_replace($fs_version_ios,$rs_version_ios,$str);
            if (strpos($str,"define('VERSION_IOS',")!==false){
                $str=str_replace($fs_version_ios,$rs_version_ios,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//苹果版本号'."\r\n".$rs_version_ios."\r\n".'?>';
            }
            //替换安卓版本号
            $fs_version_android="define('VERSION_ANDROID','$old_version_android');";
            $rs_version_android="define('VERSION_ANDROID','$version_android');";
//            $str=str_replace($fs_version_android,$rs_version_android,$str);
            if (strpos($str,"define('VERSION_ANDROID',")!==false){
                $str=str_replace($fs_version_android,$rs_version_android,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//安卓版本号'."\r\n".$rs_version_android."\r\n".'?>';
            }
            //替换苹果下载地址
            $fs_down_ios="define('DOWN_IOS','$old_down_ios');";
            $rs_down_ios="define('DOWN_IOS','$down_ios');";
            $str=str_replace($fs_down_ios,$rs_down_ios,$str);
            if (strpos($str,"define('DOWN_IOS',")!==false){
                $str=str_replace($fs_down_ios,$rs_down_ios,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//苹果下载地址'."\r\n".$rs_down_ios."\r\n".'?>';
            }
            //替换安卓下载地址
            $fs_down_android="define('DOWN_ANDROID','$old_down_android');";
            $rs_down_android="define('DOWN_ANDROID','$down_android');";
            $str=str_replace($fs_down_android,$rs_down_android,$str);
            if (strpos($str,"define('DOWN_ANDROID',")!==false){
                $str=str_replace($fs_down_android,$rs_down_android,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//安卓下载地址'."\r\n".$rs_down_android."\r\n".'?>';
            }
            //替换苹果新版本更新内容
            $fs_update_content_ios="define('UPDATE_CONTENT_IOS','$old_update_content_ios');";
            $rs_update_content_ios="define('UPDATE_CONTENT_IOS','$update_content_ios');";
//            $str=str_replace($fs_update_content_ios,$rs_update_content_ios,$str);
            if (strpos($str,"define('UPDATE_CONTENT_IOS',")!==false){
                $str=str_replace($fs_update_content_ios,$rs_update_content_ios,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//苹果新版本更新内容'."\r\n".$rs_update_content_ios."\r\n".'?>';
            }
            //替换安卓新版本更新内容
            $fs_update_content_android="define('UPDATE_CONTENT_ANDROID','$old_update_content_android');";
            $rs_update_content_android="define('UPDATE_CONTENT_ANDROID','$update_content_android');";
//            $str=str_replace($fs_update_content_android,$rs_update_content_android,$str);
            if (strpos($str,"define('UPDATE_CONTENT_ANDROID',")!==false){
                $str=str_replace($fs_update_content_android,$rs_update_content_android,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//安卓新版本更新内容'."\r\n".$rs_update_content_android."\r\n".'?>';
            }

            //平台微信号
            $fs_platform_wx="define('PLATFORM_WX','$old_platform_wx');";
            $rs_platform_wx="define('PLATFORM_WX','$platform_wx');";
//    		$str=str_replace($fs_platform_wx,$rs_platform_wx,$str);
            if (strpos($str,"define('PLATFORM_WX',")!==false){
                $str=str_replace($fs_platform_wx,$rs_platform_wx,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//平台微信号'."\r\n".$rs_platform_wx."\r\n".'?>';
            }
            //分享淘宝商品网址
            $fs_share_url="define('SHARE_URL','$old_share_url');";
            $rs_share_url="define('SHARE_URL','$share_url');";
//    		$str=str_replace($fs_share_url,$rs_share_url,$str);
            if (strpos($str,"define('SHARE_URL',")!==false){
                $str=str_replace($fs_share_url,$rs_share_url,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//分享淘宝商品网址'."\r\n".$rs_share_url."\r\n".'?>';
            }
            //分享注册下载网址
            $fs_share_url_register="define('SHARE_URL_REGISTER','$old_share_url_register');";
            $rs_share_url_register="define('SHARE_URL_REGISTER','$share_url_register');";
//    		$str=str_replace($fs_share_url_register,$rs_share_url_register,$str);
            if (strpos($str,"define('SHARE_URL_REGISTER',")!==false){
                $str=str_replace($fs_share_url_register,$rs_share_url_register,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//分享注册下载网址'."\r\n".$rs_share_url_register."\r\n".'?>';
            }
            //VIP专用分享网址
            $fs_share_url_vip="define('SHARE_URL_VIP','$old_share_url_vip');";
            $rs_share_url_vip="define('SHARE_URL_VIP','$share_url_vip');";
//    		$str=str_replace($fs_share_url_vip,$rs_share_url_vip,$str);
            if (strpos($str,"define('SHARE_URL_VIP',")!==false){
                $str=str_replace($fs_share_url_vip,$rs_share_url_vip,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//VIP专用分享网址'."\r\n".$rs_share_url_vip."\r\n".'?>';
            }

            //替换网址
            $fs_web_url="define('WEB_URL','$old_web_url');";
            $rs_web_url="define('WEB_URL','$web_url');";
//            $str=str_replace($fs_web_url,$rs_web_url,$str);
            if (strpos($str,"define('WEB_URL',")!==false){
                $str=str_replace($fs_web_url,$rs_web_url,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//网址'."\r\n".$rs_web_url."\r\n".'?>';
            }
            //替换网站备案号
            $fs_web_record_number="define('WEB_RECORD_NUMBER','$old_web_record_number');";
            $rs_web_record_number="define('WEB_RECORD_NUMBER','$web_record_number');";
//            $str=str_replace($fs_web_url,$rs_web_url,$str);
            if (strpos($str,"define('WEB_RECORD_NUMBER',")!==false){
                $str=str_replace($fs_web_record_number,$rs_web_record_number,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//网站备案号'."\r\n".$rs_web_record_number."\r\n".'?>';
            }
            //替换keywords
            $find_str_keywords="define('seo_keywords','$old_keywords');";
            $replace_str_keywords="define('seo_keywords','$keywords');";
//            $str=str_replace($find_str_keywords,$replace_str_keywords,$str);
            if (strpos($str,"define('seo_keywords',")!==false){
                $str=str_replace($find_str_keywords,$replace_str_keywords,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//keywords'."\r\n".$replace_str_keywords."\r\n".'?>';
            }
            //替换description
            $find_str_description="define('seo_description','$old_description');";
            $replace_str_description="define('seo_description','$description');";
//            $str=str_replace($find_str_description,$replace_str_description,$str);
            if (strpos($str,"define('seo_description',")!==false){
                $str=str_replace($find_str_description,$replace_str_description,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//description'."\r\n".$replace_str_description."\r\n".'?>';
            }
            //替换copyright
            $find_str_copyright="define('seo_copyright','$old_copyright');";
            $replace_str_copyright="define('seo_copyright','$copyright');";
//            $str=str_replace($find_str_copyright,$replace_str_copyright,$str);
            if (strpos($str,"define('seo_copyright',")!==false){
                $str=str_replace($find_str_copyright,$replace_str_copyright,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//copyright'."\r\n".$replace_str_copyright."\r\n".'?>';
            }
            //替换web_title_en
            $find_str_web_title_en="define('WEB_TITLE_EN','$old_web_title_en');";
            $replace_str_web_title_en="define('WEB_TITLE_EN','$web_title_en');";
//            $str=str_replace($find_str_web_title_en,$replace_str_web_title_en,$str);
            if (strpos($str,"define('WEB_TITLE_EN',")!==false){
                $str=str_replace($find_str_web_title_en,$replace_str_web_title_en,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//web_title_en'."\r\n".$replace_str_web_title_en."\r\n".'?>';
            }
            //替换keywords_en
            $find_str_keywords_en="define('seo_keywords_en','$old_keywords_en');";
            $replace_str_keywords_en="define('seo_keywords_en','$keywords_en');";
//            $str=str_replace($find_str_keywords_en,$replace_str_keywords_en,$str);
            if (strpos($str,"define('seo_keywords_en',")!==false){
                $str=str_replace($find_str_keywords_en,$replace_str_keywords_en,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//keywords_en'."\r\n".$replace_str_keywords_en."\r\n".'?>';
            }
            //替换description_en
            $find_str_description_en="define('seo_description_en','$old_description_en');";
            $replace_str_description_en="define('seo_description_en','$description_en');";
//            $str=str_replace($find_str_description_en,$replace_str_description_en,$str);
            if (strpos($str,"define('seo_description_en',")!==false){
                $str=str_replace($find_str_description_en,$replace_str_description_en,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//description_en'."\r\n".$replace_str_description_en."\r\n".'?>';
            }

            //替换copyright_en
            $find_str_copyright_en="define('seo_copyright_en','$old_copyright_en');";
            $replace_str_copyright_en="define('seo_copyright_en','$copyright_en');";
//            $str=str_replace($find_str_copyright_en,$replace_str_copyright_en,$str);
            if (strpos($str,"define('seo_copyright_en',")!==false){
                $str=str_replace($find_str_copyright_en,$replace_str_copyright_en,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//seo_copyright_en'."\r\n".$replace_str_copyright_en."\r\n".'?>';
            }


            $find_str_to_update="define('to_update','$old_to_update');";
            $replace_str_to_update="define('to_update','$to_update');";
//            $str=str_replace($find_str_copyright_en,$replace_str_copyright_en,$str);
            if (strpos($str,"define('to_update',")!==false){
                $str=str_replace($find_str_to_update,$replace_str_to_update,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//seo_copyright_en'."\r\n".$replace_str_to_update."\r\n".'?>';
            }


            $find_str_to_update_ios="define('to_update_ios','$old_to_update_ios');";
            $replace_str_to_update_ios="define('to_update_ios','$to_update_ios');";
//            $str=str_replace($find_str_copyright_en,$replace_str_copyright_en,$str);
            if (strpos($str,"define('to_update_ios',")!==false){
                $str=str_replace($find_str_to_update_ios,$replace_str_to_update_ios,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//to_update_ios'."\r\n".$replace_str_to_update_ios."\r\n".'?>';
            }

            $find_str_pay_method="define('PAY_METHODS','$old_pay_method');";
            $replace_str_pay_method="define('PAY_METHODS','$pay_method');";
//            $str=str_replace($find_str_copyright_en,$replace_str_copyright_en,$str);
            if (strpos($str,"define('PAY_METHODS',")!==false){
                $str=str_replace($find_str_pay_method,$replace_str_pay_method,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//pay_methods'."\r\n".$replace_str_pay_method."\r\n".'?>';
            }

            //上传logo
            if(!empty($_FILES['logo']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'logo', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['logo'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }

            //上传apk包
            if(!empty($_FILES['apk']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array('apk'), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/apk/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  '', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['apk'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                    $filepath=ltrim($filepath,'.');
                    //替换掉安卓下载地址
                    $filepath = 'http://'.$_SERVER['HTTP_HOST'].$filepath;
                    $filepath_down_android="define('DOWN_ANDROID','$filepath');";
//                    $str=str_replace($rs_down_android,$filepath_down_android,$str);
                    if (strpos($str,"define('DOWN_ANDROID',")!==false){
                        $str=str_replace($rs_down_android,$filepath_down_android,$str);
                    }else{
                        $str=str_replace('?>','',$str)."\r\n".'//安卓下载地址'."\r\n".$filepath_down_android."\r\n".'?>';
                    }
                }
            }

            //写入系统配置文件
            file_put_contents('./Public/inc/config.php',$str);
            layout(false);
            $this->success('更新成功！');
        }else {
            //获取网站设置信息
            $msg['app_name']=defined('APP_NAME')?APP_NAME:'';//App名称
            $msg['version_ios']=defined('VERSION_IOS')?VERSION_IOS:'';//苹果版本号
            $msg['version_android']=defined('VERSION_ANDROID')?VERSION_ANDROID:'';//安卓版本号
            $msg['down_ios']=defined('DOWN_IOS')?DOWN_IOS:'';//苹果下载地址
            $msg['down_android']=defined('DOWN_ANDROID')?DOWN_ANDROID:'';//安卓下载地址
            $msg['update_content_ios']=defined('UPDATE_CONTENT_IOS')?UPDATE_CONTENT_IOS:'';//苹果新版本更新内容
            $msg['update_content_android']=defined('UPDATE_CONTENT_ANDROID')?UPDATE_CONTENT_ANDROID:'';//安卓新版本更新内容

            $msg['platform_wx']=defined('PLATFORM_WX')?PLATFORM_WX:'';
            $msg['share_url']=defined('SHARE_URL')?SHARE_URL:'';
            $msg['share_url_register']=defined('SHARE_URL_REGISTER')?SHARE_URL_REGISTER:'';
            $msg['share_url_vip']=defined('SHARE_URL_VIP')?SHARE_URL_VIP:'';//VIP专用分享网址

            $msg['web_url']=defined('WEB_URL')?WEB_URL:'';//网址
            $msg['web_record_number']=defined('WEB_RECORD_NUMBER')?WEB_RECORD_NUMBER:'';//网站备案号
            $msg['web_title']=defined('WEB_TITLE')?WEB_TITLE:'';
            $msg['keywords']=defined('seo_keywords')?seo_keywords:'';
            $msg['description']=defined('seo_description')?seo_description:'';
            $msg['copyright']=defined('seo_copyright')?seo_copyright:'';
            $msg['web_title_en']=defined('WEB_TITLE_EN')?WEB_TITLE_EN:'';
            $msg['keywords_en']=defined('seo_keywords_en')?seo_keywords_en:'';
            $msg['description_en']=defined('seo_description_en')?seo_description_en:'';
            $msg['copyright_en']=defined('seo_copyright_en')?seo_copyright_en:'';
            $msg['web_url']=defined('WEB_URL')?WEB_URL:'';//网址
            $msg['to_update']=defined('to_update')?to_update:'N';//更新android
            $msg['to_update_ios']=defined('to_update_ios')?to_update_ios:'N';//更新ios

            $msg['pay_methods']=defined('PAY_METHODS')?PAY_METHODS:'';//更新ios

            //var_dump($msg['pay_methods']);exit;

            $this->assign('msg',$msg);

            $this->display();
        }
    }


    //站点设置
    public function websetV2()
    {
        if($_POST) {
            $cache_file = "./Public/inc/config.php";
            $config_keys = [
                'app_name', 'version_ios', 'version_android', 'down_ios', 'down_android', 'update_content_ios', 'update_content_android',
                'platform_wx', 'share_url', 'share_url_register', 'share_url_vip', 'web_url', 'web_record_number',
                'web_title', 'keywords', 'description', 'copyright', 'is_distribution','web_title_en', 'keywords_en', 'description_en', 'copyright_en',
                'to_update', 'to_update_ios','commission_broadcast',
            ];
            $model_setting = new SettingModel();
            foreach ($config_keys as $key){
                $value = I('post.'.$key);
                $model_setting->set($key, $value, $cache_file);
            }

            if (I('post.pay_method')) {
                $pay_data = array_keys(I('post.pay_method'));
                $pay_method = implode(",",$pay_data);
                $model_setting->set('PAY_METHODS', $pay_method, $cache_file);
            }

            if (I('post.platform_invite_cn')) {
                $model_setting->set('PLATFORM_INVITR_CN', I('post.platform_invite_cn'), $cache_file);
            }

            //上传logo
            if(!empty($_FILES['logo']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'logo', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['logo'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }

            //上传apk包
            if(!empty($_FILES['apk']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array('apk'), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/apk/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  '', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['apk'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                    $filepath=ltrim($filepath,'.');
                    //替换掉安卓下载地址
                    $filepath = 'http://'.$_SERVER['HTTP_HOST'].$filepath;
                    $model_setting->set('down_android', $filepath, $cache_file);
                }
            }

            //// 其他配置 图片上传
            $else_con   = [
                'mimes'         =>  [],        //允许上传的文件MiMe类型
                'maxSize'       =>  1024*1024*4,    //上传的文件大小限制 (0-不做限制)
                'exts'          =>  ['png'],        //允许上传的文件后缀
                'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                'rootPath'      =>  './Public//Upload/Diy/', //保存根路径
                'savePath'      =>  '', //保存路径
                'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                'replace'       =>  true, //存在同名是否覆盖
            ];

            // 图片组列表
            $else_arr   = ['live_romm_logo', 'share_applet_bg', 'tips_bg', 'login_bg', 'user_tip_bg', 'anchor_tip_bg', 'white_null_bg', 'black_null_bg', 'goods_vip_bg', 'sevrice_logo'];

            foreach ($else_arr as $val) {
                if ($_FILES[$val]['name']) {
                    $else_con['saveName']   = $val;

                    $upload = new \Think\Upload($else_con);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES[$val], 1);

                    if (!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                        break;
                    }
                }
            }

            $this->cacheSetting($cache_file);
            layout(false);
            $this->success('更新成功！');
        }else {
            //获取网站设置信息
            $msg['app_name']=defined('APP_NAME')?APP_NAME:'';//App名称
            $msg['version_ios']=defined('VERSION_IOS')?VERSION_IOS:'';//苹果版本号
            $msg['version_android']=defined('VERSION_ANDROID')?VERSION_ANDROID:'';//安卓版本号
            $msg['down_ios']=defined('DOWN_IOS')?DOWN_IOS:'';//苹果下载地址
            $msg['down_android']=defined('DOWN_ANDROID')?DOWN_ANDROID:'';//安卓下载地址
            $msg['update_content_ios']=defined('UPDATE_CONTENT_IOS')?UPDATE_CONTENT_IOS:'';//苹果新版本更新内容
            $msg['update_content_android']=defined('UPDATE_CONTENT_ANDROID')?UPDATE_CONTENT_ANDROID:'';//安卓新版本更新内容

            $msg['platform_wx']=defined('PLATFORM_WX')?PLATFORM_WX:'';
            $msg['share_url']=defined('SHARE_URL')?SHARE_URL:'';
            $msg['share_url_register']=defined('SHARE_URL_REGISTER')?SHARE_URL_REGISTER:'';
            $msg['share_url_vip']=defined('SHARE_URL_VIP')?SHARE_URL_VIP:'';//VIP专用分享网址

            $msg['web_url']=defined('WEB_URL')?WEB_URL:'';//网址
            $msg['web_record_number']=defined('WEB_RECORD_NUMBER')?WEB_RECORD_NUMBER:'';//网站备案号
            $msg['web_title']=defined('WEB_TITLE')?WEB_TITLE:'';
            $msg['keywords']=defined('seo_keywords')?seo_keywords:'';
            $msg['description']=defined('seo_description')?seo_description:'';
            $msg['copyright']=defined('seo_copyright')?seo_copyright:'';
            $msg['web_title_en']=defined('WEB_TITLE_EN')?WEB_TITLE_EN:'';
            $msg['keywords_en']=defined('seo_keywords_en')?seo_keywords_en:'';
            $msg['description_en']=defined('seo_description_en')?seo_description_en:'';
            $msg['copyright_en']=defined('seo_copyright_en')?seo_copyright_en:'';
            $msg['web_url']=defined('WEB_URL')?WEB_URL:'';//网址
            $msg['to_update']=defined('to_update')?to_update:'N';//更新android
            $msg['to_update_ios']=defined('to_update_ios')?to_update_ios:'N';//更新ios
            $msg['is_distribution']=defined('IS_DISTRIBUTION')?IS_DISTRIBUTION:'N';//更新ios
            $msg['pay_methods']           = defined('PAY_METHODS') ? PAY_METHODS : '';//更新ios
            $msg['platform_invite_cn']    = defined('PLATFORM_INVITR_CN') ? PLATFORM_INVITR_CN : '';  //平台邀请码名称
            $msg['commission_broadcast']    = defined('COMMISSION_BROADCAST') ? COMMISSION_BROADCAST : 'N';  //佣金播报
            // 图片配置
            $msg['live_romm_logo']  = file_exists('./Public/Upload/Diy/live_romm_logo.png') ? '/Public/Upload/Diy/live_romm_logo.png' : false;
            $msg['share_applet_bg'] = file_exists('./Public/Upload/Diy/share_applet_bg.png') ? '/Public/Upload/Diy/share_applet_bg.png' : false;
            $msg['tips_bg']         = file_exists('./Public/Upload/Diy/tips_bg.png') ? '/Public/Upload/Diy/tips_bg.png' : false;
            $msg['login_bg']        = file_exists('./Public/Upload/Diy/login_bg.png') ? '/Public/Upload/Diy/login_bg.png' : false;
            $msg['user_tip_bg']     = file_exists('./Public/Upload/Diy/user_tip_bg.png') ? '/Public/Upload/Diy/user_tip_bg.png' : false;
            $msg['anchor_tip_bg']   = file_exists('./Public/Upload/Diy/anchor_tip_bg.png') ? '/Public/Upload/Diy/anchor_tip_bg.png' : false;
            $msg['white_null_bg']   = file_exists('./Public/Upload/Diy/white_null_bg.png') ? '/Public/Upload/Diy/white_null_bg.png' : false;
            $msg['black_null_bg']   = file_exists('./Public/Upload/Diy/black_null_bg.png') ? '/Public/Upload/Diy/black_null_bg.png' : false;
            $msg['goods_vip_bg']    = file_exists('./Public/Upload/Diy/goods_vip_bg.png') ? '/Public/Upload/Diy/goods_vip_bg.png' : false;
            $msg['sevrice_logo']    = file_exists('./Public/Upload/Diy/sevrice_logo.png') ? '/Public/Upload/Diy/sevrice_logo.png' : false;

            $this->assign('msg',$msg);
            $this->display('webset');
        }
    }

    //敏感词过滤
    public function sensitive()
    {
        $model_setting = new SettingModel();
        $cache_file = './Public/inc/sensitive_word.txt';
        $str = $model_setting->get('sensitive_word');
        $msg=trim($str);
        $this->assign('msg',$msg);
        if(I('post.'))
        {
            $sensitive_word=I('post.sensitive_word');
            //写入配置文件
            file_put_contents($cache_file, $sensitive_word);
            $model_setting->set('sensitive_word', $sensitive_word);
            layout(false);
            $this->success('更新成功！');
        }else {
            $this->display();
        }
    }

    //费用规则设置
    public function feeset()
    {
        if($_POST) {
            layout(false);
            //直接推荐返利比例-百分比
            $referrer_rate=I('post.referrer_rate');
            $old_referrer_rate=I('post.old_referrer_rate');
            //间接推荐返利比例-百分比
            $referrer_rate2=I('post.referrer_rate2');
            $old_referrer_rate2=I('post.old_referrer_rate2');

            //会员升级费用-1个月
            $upgrade_fee_month=I('post.upgrade_fee_month');
            //必须为整数
            if(is_natural_num($upgrade_fee_month)===false) {
                $this->error('会员升级费用-1个月必须为不小于零的整数！');
            }
            $old_upgrade_fee_month=I('post.old_upgrade_fee_month');

            //会员升级费用-1年
            $upgrade_fee_year=I('post.upgrade_fee_year');
            //必须为整数
            if(is_natural_num($upgrade_fee_year)===false) {
                $this->error('会员升级费用-1年必须为不小于零的整数！');
            }
            $old_upgrade_fee_year=I('post.old_upgrade_fee_year');

            //会员升级费用-终生
            $upgrade_fee_forever=I('post.upgrade_fee_forever');
            //必须为整数
            if(is_natural_num($upgrade_fee_forever)===false) {
                $this->error('会员升级费用-终生必须为不小于零的整数！');
            }
            $old_upgrade_fee_forever=I('post.old_upgrade_fee_forever');

            //平台微信号
            $platform_wx=I('post.platform_wx');
            $old_platform_wx=I('post.old_platform_wx');

            //分享淘宝商品网址
            $share_url=I('post.share_url');
            $old_share_url=I('post.old_share_url');
            //分享注册下载网址
            $share_url_register=I('post.share_url_register');
            $old_share_url_register=I('post.old_share_url_register');
            //VIP专用分享网址
            $share_url_vip=I('post.share_url_vip');
            $old_share_url_vip=I('post.old_share_url_vip');

            //载入系统配置文件
            $str=file_get_contents('./Public/inc/fee.config.php');

            //替换直接推荐返利比例-百分比
            $find_str_referrer_rate="define('REFERRER_RATE','$old_referrer_rate');";
            $replace_str_referrer_rate="define('REFERRER_RATE','$referrer_rate');";
//    		$str=str_replace($find_str_referrer_rate,$replace_str_referrer_rate,$str);
            if (strpos($str,"define('REFERRER_RATE',")!==false){
                $str=str_replace($find_str_referrer_rate,$replace_str_referrer_rate,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//直接推荐返利比例-百分比'."\r\n".$replace_str_referrer_rate."\r\n".'?>';
            }
            //替换间接推荐返利比例-百分比
            $find_str_referrer_rate2="define('REFERRER_RATE2','$old_referrer_rate2');";
            $replace_str_referrer_rate2="define('REFERRER_RATE2','$referrer_rate2');";
//    		$str=str_replace($find_str_referrer_rate2,$replace_str_referrer_rate2,$str);
            if (strpos($str,"define('REFERRER_RATE2',")!==false){
                $str=str_replace($find_str_referrer_rate2,$replace_str_referrer_rate2,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//间接推荐返利比例-百分比'."\r\n".$replace_str_referrer_rate2."\r\n".'?>';
            }
            //会员升级费用-1个月
            $fs_upgrade_fee_month="define('UPGRADE_FEE_MONTH','$old_upgrade_fee_month');";
            $rs_upgrade_fee_month="define('UPGRADE_FEE_MONTH','$upgrade_fee_month');";
//    		$str=str_replace($fs_upgrade_fee_month,$rs_upgrade_fee_month,$str);
            if (strpos($str,"define('UPGRADE_FEE_MONTH',")!==false){
                $str=str_replace($fs_upgrade_fee_month,$rs_upgrade_fee_month,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//会员升级费用-1个月'."\r\n".$rs_upgrade_fee_month."\r\n".'?>';
            }
            //会员升级费用-1年
            $fs_upgrade_fee_year="define('UPGRADE_FEE_YEAR','$old_upgrade_fee_year');";
            $rs_upgrade_fee_year="define('UPGRADE_FEE_YEAR','$upgrade_fee_year');";
//    		$str=str_replace($fs_upgrade_fee_year,$rs_upgrade_fee_year,$str);
            if (strpos($str,"define('UPGRADE_FEE_YEAR',")!==false){
                $str=str_replace($fs_upgrade_fee_year,$rs_upgrade_fee_year,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//会员升级费用-1年'."\r\n".$rs_upgrade_fee_year."\r\n".'?>';
            }
            //会员升级费用-终生
            $fs_upgrade_fee_forever="define('UPGRADE_FEE_FOREVER','$old_upgrade_fee_forever');";
            $rs_upgrade_fee_forever="define('UPGRADE_FEE_FOREVER','$upgrade_fee_forever');";
//    		$str=str_replace($fs_upgrade_fee_forever,$rs_upgrade_fee_forever,$str);
            if (strpos($str,"define('UPGRADE_FEE_FOREVER',")!==false){
                $str=str_replace($fs_upgrade_fee_forever,$rs_upgrade_fee_forever,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//会员升级费用-终生'."\r\n".$rs_upgrade_fee_forever."\r\n".'?>';
            }
            //平台微信号
            $fs_platform_wx="define('PLATFORM_WX','$old_platform_wx');";
            $rs_platform_wx="define('PLATFORM_WX','$platform_wx');";
//    		$str=str_replace($fs_platform_wx,$rs_platform_wx,$str);
            if (strpos($str,"define('PLATFORM_WX',")!==false){
                $str=str_replace($fs_platform_wx,$rs_platform_wx,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//平台微信号'."\r\n".$rs_platform_wx."\r\n".'?>';
            }
            //分享淘宝商品网址
            $fs_share_url="define('SHARE_URL','$old_share_url');";
            $rs_share_url="define('SHARE_URL','$share_url');";
//    		$str=str_replace($fs_share_url,$rs_share_url,$str);
            if (strpos($str,"define('SHARE_URL',")!==false){
                $str=str_replace($fs_share_url,$rs_share_url,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//分享淘宝商品网址'."\r\n".$rs_share_url."\r\n".'?>';
            }
            //分享注册下载网址
            $fs_share_url_register="define('SHARE_URL_REGISTER','$old_share_url_register');";
            $rs_share_url_register="define('SHARE_URL_REGISTER','$share_url_register');";
//    		$str=str_replace($fs_share_url_register,$rs_share_url_register,$str);
            if (strpos($str,"define('SHARE_URL_REGISTER',")!==false){
                $str=str_replace($fs_share_url_register,$rs_share_url_register,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//分享注册下载网址'."\r\n".$rs_share_url_register."\r\n".'?>';
            }
            //VIP专用分享网址
            $fs_share_url_vip="define('SHARE_URL_VIP','$old_share_url_vip');";
            $rs_share_url_vip="define('SHARE_URL_VIP','$share_url_vip');";
//    		$str=str_replace($fs_share_url_vip,$rs_share_url_vip,$str);
            if (strpos($str,"define('SHARE_URL_VIP',")!==false){
                $str=str_replace($fs_share_url_vip,$rs_share_url_vip,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//VIP专用分享网址'."\r\n".$rs_share_url_vip."\r\n".'?>';
            }
            //写入配置文件
            file_put_contents('./Public/inc/fee.config.php',$str);
            $this->success('更新成功！');
        }else {
            //获取配置文件
            require_once './Public/inc/fee.config.php';
            $msg=array(
                'point_register'=>defined('POINT_REGISTER')?POINT_REGISTER:'',
                'point_recommend_register'=>defined('POINT_RECOMMEND_REGISTER')?POINT_RECOMMEND_REGISTER:'',
                'referrer_rate'=>defined('REFERRER_RATE')?REFERRER_RATE:'',
                'referrer_rate2'=>defined('REFERRER_RATE2')?REFERRER_RATE2:'',
                'upgrade_fee_month'=>defined('UPGRADE_FEE_MONTH')?UPGRADE_FEE_MONTH:'',
                'upgrade_fee_year'=>defined('UPGRADE_FEE_YEAR')?UPGRADE_FEE_YEAR:'',
                'upgrade_fee_forever'=>defined('UPGRADE_FEE_FOREVER')?UPGRADE_FEE_FOREVER:'',
                'platform_wx'=>defined('PLATFORM_WX')?PLATFORM_WX:'',
                'share_url'=>defined('SHARE_URL')?SHARE_URL:'',
                'share_url_register'=>defined('SHARE_URL_REGISTER')?SHARE_URL_REGISTER:'',
                'share_url_vip'=>defined('SHARE_URL_VIP')?SHARE_URL_VIP:'',//VIP专用分享网址
            );
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //应用账号配置
    public function accountSet()
    {
        //获取配置文件
        require_once './Public/inc/account.config.php';

        if(I('post.')) {
            layout(false);
            $cache_file = './Public/inc/account.config.php';
            $config_keys = [
                'dtk_appkey', 'dtk_appsecret', 'tbk_appid', 'tbk_appkey', 'tbk_appsecret', 'tbk_pid', 'tbk_adzone_id',
                'wy_appkey', 'tbk_relation_pid', 'adzone_id', 'auth_code','bc_app_key','pdd_client_id', 'pdd_client_secret', 'pdd_pid',
                'jpush_key', 'jpush_secret', 'alipay_appid', 'alipay_private_key', 'alipay_public_key', 'ty_channel_name',
                'ty_channel_coding', 'ty_key', 'ty_secret', 'ty_type', 'ty_link', 'kd_key', 'kdn_id', 'kdn_apikey',
                'task_name', 'task_pwd', 'mob_appkey', 'mob_appsecret', 'mob_template', 'wxpay_appid', 'wxpay_appsecret', 'wxpay_merchid', 'wxpay_apikey', 'wxpay_cert', 'wxpay_key', 'tencent_secretid', 'tencent_secretkey',
                'tencent_im_sdkappid', 'tencent_im_key', 'tencent_im_admin', 'tencent_live_key', 'dtk_search_order',
                'tencent_live_push_domain','tencent_live_pull_domain','tencent_live_call_key','tencent_licence_url','tencent_licence_key','tencent_licence_url_ugc','tbxt_switch','pddxt_switch','jdxt_switch','int_mob_template'
            ];

            $model_setting = new SettingModel();
            foreach ($config_keys as $key){
                $value = I('post.'.$key);
                $model_setting->set($key, $value, $cache_file);
            }

            $wxpay_cert = I('post.wxpay_cert');
            $wxpay_key = I('post.wxpay_key');
            if($wxpay_cert){
                $path = VENDOR_PATH.'/pay/wxpay/cert/apiclient_cert.pem';
                file_put_contents($path, $wxpay_cert);
            }
            if($wxpay_key){
                $path = VENDOR_PATH.'/pay/wxpay/cert/apiclient_key.pem';
                file_put_contents($path, $wxpay_key);
            }

            $this->cacheSetting($cache_file);

            $cache_file = './Public/inc/extra.config.php';
            $config_keys = [
                'jd_unionid', 'jd_auth_key', 'android_appkey', 'android_appsecret', 'ios_appkey', 'ios_appsecret',
                'sms_apikey', 'sms_tpl', 'sms_sid', 'jingtuitui_appid', 'jingtuitui_appkey'
            ];

            foreach ($config_keys as $key){
                $value = I('post.'.$key);
                $model_setting->set($key, $value, $cache_file);
            }

            $tk_pid = I('post.tk_pid');
            $tk_pid = json_encode($tk_pid);
            $model_setting->set('tb_pid', $tk_pid, $cache_file);
            $this->cacheSetting($cache_file);

            $this->success('更新成功！');
        }elseif(I('get.access_token')){
            $access_token=I('get.access_token');
            $expires_in=I('get.expires_in');
            $refresh_token=I('get.refresh_token');
            $vip_open_id=I('get.open_id');
            layout(false);
            //载入系统配置文件
            $str=file_get_contents('./Public/inc/account.config.php');
            //替换唯品会access_token
            $fs_vip_access_token="define('VIP_ACCESS_TOKEN','".VIP_ACCESS_TOKEN."');";
            $rs_vip_access_token="define('VIP_ACCESS_TOKEN','$access_token');";
//            $str=str_replace($fs_vip_access_token,$rs_vip_access_token,$str);
            if (strpos($str,"define('VIP_ACCESS_TOKEN',")!==false){
                $str=str_replace($fs_vip_access_token,$rs_vip_access_token,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//唯品会access_token'."\r\n".$rs_vip_access_token."\r\n".'?>';
            }
            //替换唯品会expires_in过期时间
            $date=date('Y-m-d H:i:s',time()+intval($expires_in));
            $fs_vip_expires_date="define('VIP_EXPIRES_DATE','".VIP_EXPIRES_DATE."');";
            $rs_vip_expires_date="define('VIP_EXPIRES_DATE','$date');";
//            $str=str_replace($fs_vip_expires_date,$rs_vip_expires_date,$str);
            if (strpos($str,"define('VIP_EXPIRES_DATE',")!==false){
                $str=str_replace($fs_vip_expires_date,$rs_vip_expires_date,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//唯品会expires_date'."\r\n".$rs_vip_expires_date."\r\n".'?>';
            }
            //替换唯品会refresh_token
            $fs_vip_refresh_token="define('VIP_REFRESH_TOKEN','".VIP_REFRESH_TOKEN."');";
            $rs_vip_refresh_token="define('VIP_REFRESH_TOKEN','$refresh_token');";
//            $str=str_replace($fs_vip_refresh_token,$rs_vip_refresh_token,$str);
            if (strpos($str,"define('VIP_REFRESH_TOKEN',")!==false){
                $str=str_replace($fs_vip_refresh_token,$rs_vip_refresh_token,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//唯品会refresh_token'."\r\n".$rs_vip_refresh_token."\r\n".'?>';
            }
            //替换唯品会open_id
            $fs_vip_open_id="define('VIP_OPEN_ID','".VIP_OPEN_ID."');";
            $rs_vip_open_id="define('VIP_OPEN_ID','$vip_open_id');";
//            $str=str_replace($fs_vip_open_id,$rs_vip_open_id,$str);
            if (strpos($str,"define('VIP_OPEN_ID',")!==false){
                $str=str_replace($fs_vip_open_id,$rs_vip_open_id,$str);
            }else{
                $str=str_replace('?>','',$str)."\r\n".'//唯品会open_id'."\r\n".$rs_vip_open_id."\r\n".'?>';
            }
            //写入配置文件
            file_put_contents('./Public/inc/account.config.php',$str);
            $this->success('唯品会授权成功，跳转中！','http://'.$_SERVER['HTTP_HOST'].'/taokeyun.php?c=System&a=accountSet');
        }else {
            $msg=array(
                'dtk_appkey'=>defined('DTK_APP_KEY')?DTK_APP_KEY:'',//大淘客App key
                'dtk_appsecret'=>defined('DTK_APP_SECRET')?DTK_APP_SECRET:'',//大淘客App secret
                'tbk_appid'=>defined('TBK_APPID')?TBK_APPID:'',//淘宝客AppID
                'tbk_appkey'=>defined('TBK_APPKEY')?TBK_APPKEY:'',//淘宝客App key
                'tbk_appsecret'=>defined('TBK_APPSECRET')?TBK_APPSECRET:'',//淘宝客App secret
                'tbk_pid'=>defined('TBK_PID')?TBK_PID:'',//淘宝客PID
                'tbk_adzone_id'=>defined('TBK_ADZONE_ID')?TBK_ADZONE_ID:'',//淘宝客广告位ID
                'tbk_relation_pid'=>defined('TBK_RELATION_PID')?TBK_RELATION_PID:'',//淘宝客渠道专用PID
                'dtk_search_order'=>defined('DTK_SEARCH_ORDER')?DTK_SEARCH_ORDER:0,//淘宝客渠道专用PID
                'tbxt_switch'=>defined('TBXT_SWITCH')?TBXT_SWITCH:1,//淘宝系统开关
                'pddxt_switch'=>defined('PDDXT_SWITCH')?PDDXT_SWITCH:1,//平多多系统开关
                'jdxt_switch'=>defined('JDXT_SWITCH')?JDXT_SWITCH:1,//京东系统开关
                'wy_appkey'=>defined('WY_APPKEY')?WY_APPKEY:'',//维易淘宝客key
                'adzone_id'=>defined('ADZONE_ID')?ADZONE_ID:'',//广告位ID
                'auth_code'=>defined('AUTH_CODE')?AUTH_CODE:'',//联盟授权码
                'bc_app_key'=>defined('BC_APP_KEY')?BC_APP_KEY:'', // 阿里百川
                'pdd_client_id'=>defined('PDD_CLIENT_ID')?PDD_CLIENT_ID:'',//拼多多client_id
                'pdd_client_secret'=>defined('PDD_CLIENT_SECRET')?PDD_CLIENT_SECRET:'',//拼多多client_secret
                'pdd_pid'=>defined('PDD_PID')?PDD_PID:'',//拼多多推广位pid
                'jpush_key'=>defined('JPUSH_KEY')?JPUSH_KEY:'',//极光推送key
                'jpush_secret'=>defined('JPUSH_SECRET')?JPUSH_SECRET:'',//极光推送secret
                'alipay_appid'=>defined('ALIPAY_APPID')?ALIPAY_APPID:'',//支付宝appid
                'alipay_private_key'=>defined('ALIPAY_PRIVATE_KEY')?ALIPAY_PRIVATE_KEY:'',//支付宝私钥
                'alipay_public_key'=>defined('ALIPAY_PUBLIC_KEY')?ALIPAY_PUBLIC_KEY:'',//支付宝公钥
                'jd_unionid' =>defined('JD_UNIONID')?JD_UNIONID:'',
                'jd_auth_key' =>defined('JD_AUTH_KEY')?JD_AUTH_KEY:'',
                'android_appkey' =>defined('ANDROID_APPKEY')?ANDROID_APPKEY:'',
                'android_appsecret' =>defined('ANDROID_APPSECRET')?ANDROID_APPSECRET:'',
                'ios_appkey' =>defined('IOS_APPKEY')?IOS_APPKEY:'',
                'ios_appsecret' =>defined('IOS_APPSECRET')?IOS_APPSECRET:'',
                'jingtuitui_appid'  => defined('JINGTUITUI_APPID') ? JINGTUITUI_APPID : '',
                'jingtuitui_appkey' => defined('JINGTUITUI_APPKEY') ? JINGTUITUI_APPKEY : '',
                'tk_pid' =>json_decode(tb_pid,true),
                'sms_apikey' =>defined('SMS_APIKEY')?SMS_APIKEY:'',
                'sms_tpl' =>defined('SMS_TPL')?SMS_TPL:'',
                'sms_sid' =>defined('SMS_SID')?SMS_SID:'',
//                'vip_appkey' =>defined('VIP_APPKEY')?VIP_APPKEY:'',//唯品会App key
//                'vip_appsecret' =>defined('VIP_APPSECRET')?VIP_APPSECRET:'',//唯品会App secret
                'vip_accesstoken' =>defined('VIP_ACCESS_TOKEN')?VIP_ACCESS_TOKEN:'',//唯品会App accesstoken
                'vip_expiresdate' =>defined('VIP_EXPIRES_DATE')?VIP_EXPIRES_DATE:'暂未授权',//唯品会expires_date
                'mob_appkey' =>defined('MOB_APPKEY')?MOB_APPKEY:'',//mob appkey
                'mob_appsecret' =>defined('MOB_APPSECRET')?MOB_APPSECRET:'',//mob appSecret
                'ty_channel_name' =>defined('TY_CHANNEL_NAME')?TY_CHANNEL_NAME:'',//团油渠道名称
                'ty_channel_coding' =>defined('TY_CHANNEL_CODING')?TY_CHANNEL_CODING:'',//团油渠道编码
                'ty_key' =>defined('TY_KEY')?TY_KEY:'',//团油key
                'ty_secret' =>defined('TY_SECRET')?TY_SECRET:'',//团油secret
                'ty_type' =>defined('TY_TYPE')?TY_TYPE:'',//团油环境类型
                'ty_link' =>defined('TY_LINK')?TY_LINK:'',//团油链接
//                'kd_type' =>defined('KD_TYPE')?KD_TYPE:'',//快电环境类型
                'kd_key' =>defined('KD_KEY')?KD_KEY:'',//快电key
                'kdn_id' =>defined('KDN_ID')?KDN_ID:'',//快递鸟用户ID
                'kdn_apikey' =>defined('KDN_APIKEY')?KDN_APIKEY:'',//快递鸟apikey
                'task_name' =>defined('TASK_NAME')?TASK_NAME:'',// 任务渠道别名
                'task_pwd' =>defined('TASK_PWD')?TASK_PWD:'',// 任务渠道秘钥
                'mob_template' =>defined('MOB_APPTEMPLATE')?MOB_APPTEMPLATE:'',//wxpay
                'int_mob_template' =>defined('INT_MOB_TEMPLATE')?INT_MOB_TEMPLATE:'',//mob国际模板
                'wxpay_appid' =>defined('WXPAY_APPID')?WXPAY_APPID:'',//wxpay
                'wxpay_appsecret' =>defined('WXPAY_APPSECRET')?WXPAY_APPSECRET:'',//wxpay
                'wxpay_merchid' =>defined('WXPAY_MERCHID')?WXPAY_MERCHID:'',//wxpay
                'wxpay_apikey' =>defined('WXPAY_APIKEY')?WXPAY_APIKEY:'',//wxpay
                'wxpay_cert' =>defined('WXPAY_CERT')?WXPAY_CERT:'',//wxpay
                'wxpay_key' =>defined('WXPAY_KEY')?WXPAY_KEY:'',
                'tencent_secretid'         => TENCENT_SECRETID ? TENCENT_SECRETID : '',
                'tencent_secretkey'        => TENCENT_SECRETKEY ? TENCENT_SECRETKEY : '',
                'tencent_im_sdkappid'      => TENCENT_IM_SDKAPPID ? TENCENT_IM_SDKAPPID : '',
                'tencent_im_key'           => TENCENT_IM_KEY ? TENCENT_IM_KEY : '',
                'tencent_im_admin'         => TENCENT_IM_ADMIN ? TENCENT_IM_ADMIN : '',
                'tencent_live_key'         => TENCENT_LIVE_KEY ? TENCENT_LIVE_KEY : '',
                'tencent_live_call_key'    => TENCENT_LIVE_CALL_KEY ? TENCENT_LIVE_CALL_KEY : '',
                'tencent_live_push_domain' => TENCENT_LIVE_PUSH_DOMAIN ? TENCENT_LIVE_PUSH_DOMAIN : '',
                'tencent_live_pull_domain' => TENCENT_LIVE_PULL_DOMAIN ? TENCENT_LIVE_PULL_DOMAIN : '',
                'tencent_licence_url'      => defined('TENCENT_LICENCE_URL') ? TENCENT_LICENCE_URL : '',    // 腾讯云直播licence
                'tencent_licence_key'      => defined('TENCENT_LICENCE_KEY') ? TENCENT_LICENCE_KEY : '',    // 腾讯云直播licence_key
                'tencent_licence_url_ugc'  => defined('TENCENT_LICENCE_URL_UGC') ? TENCENT_LICENCE_URL_UGC : '',  // 腾讯云点播licence
            );
            // var_dump($msg);exit;
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //唯品会授权
    public function vipEmpower()
    {
        // 唯品会授权API
        $VIP_EMPOWER_API = "https://auth.vip.com/oauth2/authorize?response_type=code&redirect_uri=";
        // 唯品会回调地址
        $VIP_CALLBACK_URL = "http://bmlg.taokeyun.cn/app.php?c=VipCallback%26";
//        $VIP_CALLBACK_URL = "http://www.bmlg.com/app.php?c=VipCallback%26";
        $url = $VIP_EMPOWER_API.$VIP_CALLBACK_URL.'&client_id=' . VIP_APPKEY . '&state=' . $_SERVER['HTTP_HOST']  . '&display=web';
        echo $url;
    }

    //会员升级规则配置
    public function userSet()
    {
        //获取配置文件
        require_once './Public/inc/user.config.php';

        if(I('post.')) {
            $cache_file = './Public/inc/user.config.php';
            $config_keys = [
                'user_upgrade_register', 'user_upgrade_buy', ''
            ];
            layout(false);
            $model_setting = new SettingModel();
            foreach ($config_keys as $key){
                $value = I('post.'.$key);
                $model_setting->set($key, $value, $cache_file);
            }
            $this->cacheSetting($cache_file);
            $this->success('更新成功！');
        }else {
            $msg=array(
                'user_upgrade_register'=>defined('USER_UPGRADE_REGISTER')?USER_UPGRADE_REGISTER:'',//推荐注册增加经验值
                'user_upgrade_buy'=>defined('USER_UPGRADE_BUY')?USER_UPGRADE_BUY:'',//推荐用户购物增加经验值
            );
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //提现设置
    public function drawSet()
    {
        if($_POST) {
            layout(false);
            $cache_file = './Public/inc/draw.config.php';
            $config_keys = [
                'draw_method', 'draw_auto_money', 'draw_auto_type', 'draw_start_date', 'draw_end_date',
                'draw_limit_money', 'draw_fee'
            ];
            $model_setting = new SettingModel();
            foreach ($config_keys as $key){
                $value = I('post.'.$key);
                $model_setting->set($key, $value, $cache_file);
            }

            $this->cacheSetting($cache_file);

            //提现方式 1人工审核  2后台审核 3自动转账
            $draw_method=I('post.draw_method');
            //自动转账金额
            $draw_auto_money=I('post.draw_auto_money');
            //选择自动提现必须填写审核金额
            if($draw_method=='2') {
                if(empty($draw_auto_money)) {
                    $this->error('请输入自动提现金额!');
                }
            }

            $this->success('保存成功！');
        }else {
            //获取配置文件
            require_once './Public/inc/draw.config.php';

            $msg=array(
                'draw_method'=>defined('DRAW_METHOD')?DRAW_METHOD:'',//提现方式
                'draw_auto_money'=>defined('DRAW_AUTO_MONEY')?DRAW_AUTO_MONEY:'',//自动转账金额
                'draw_auto_type'=>defined('DRAW_AUTO_TYPE')?DRAW_AUTO_TYPE:'',//自动转账-大额提现后台审核是否自动转账
                'draw_start_date'=>defined('DRAW_START_DATE')?DRAW_START_DATE:'',//可提现起始日期
                'draw_end_date'=>defined('DRAW_END_DATE')?DRAW_END_DATE:'',//可提现截止日期
                'draw_limit_money'=>defined('DRAW_LIMIT_MONEY')?DRAW_LIMIT_MONEY:'',//最低提现金额
                'draw_fee'=>defined('DRAW_FEE')?DRAW_FEE:'0',//提现手续费
            );
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //返利设置
    public function rebateSet()
    {
        if($_POST) {
            layout(false);
            $cache_file = './Public/inc/draw.config.php';
            $model_setting = new SettingModel();
            //返利方式
            $rebate_method=I('post.rebate_method');
            $model_setting->set('rebate_method', $rebate_method, $cache_file);
            //返利时间
            $rebate_time=I('post.rebate_time');
            $model_setting->set('rebate_time', $rebate_time, $cache_file);

            $rebate_times=I('post.rebate_times');
            $model_setting->set('rebate_times', $rebate_times, $cache_file);
            $this->cacheSetting($cache_file);
            $this->success('保存成功！');
        }else {
            //获取配置文件
            require_once './Public/inc/draw.config.php';
            $msg=array(
                'rebate_method'=>defined('REBATE_METHOD')?REBATE_METHOD:'',//返利方式
                'rebate_time'=>defined('REBATE_TIME')?REBATE_TIME:'',//返利时间
                'rebate_times'=>defined('REBATE_TIMES')?REBATE_TIMES:'',//返利时间
            );
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //系统文章设置
    public function articleSet()
    {
        if($_POST) {
            layout(false);
            $cache_file = './Public/inc/extra.config.php';
            $config_keys = [
                'system_article', 'common_problem', 'novice_tutorial', 'official_announcement', 'college', 'agreement_privacy',
                'agreement', 'privacy', 'pull_new_activities', 'about_us', 'withdrawal_rules', 'zero_buy'
            ];
            $model_setting = new SettingModel();
            foreach ($config_keys as $key){
                $value = I('post.'.$key);
                $model_setting->set($key, $value, $cache_file);
            }
            $this->cacheSetting($cache_file);
            $this->success('保存成功！');
        }else {
            //获取配置文件
            require_once './Public/inc/extra.config.php';
            $msg=array(
                'system_article'=>defined('SYSTEM_ARTICLE')?SYSTEM_ARTICLE:'',//系统文章
                'common_problem'=>defined('COMMON_PROBLEM')?COMMON_PROBLEM:'',//常见问题
                'novice_tutorial'=>defined('NOVICE_TUTORIAL')?NOVICE_TUTORIAL:'',//新手教程
                'official_announcement'=>defined('OFFICIAL_ANNOUNCEMENT')?OFFICIAL_ANNOUNCEMENT:'',//官方公告
                'college'=>defined('COLLEGE')?COLLEGE:'',//商学院
                'agreement_privacy'=>defined('AGREEMENT_PRIVACY')?AGREEMENT_PRIVACY:'',//用户协议和隐私条款
                'agreement'=>defined('AGREEMENT')?AGREEMENT:'',//用户协议
                'privacy'=>defined('PRIVACY')?PRIVACY:'',//隐私条款
                'pull_new_activities'=>defined('PULL_NEW_ACTIVITIES')?PULL_NEW_ACTIVITIES:'',//拉新活动规则
                'about_us'=>defined('ABOUT_US')?ABOUT_US:'',//关于我们
                'withdrawal_rules'=>defined('WITHDRAWAL_RULES')?WITHDRAWAL_RULES:'',//提现规则
                'zero_buy'=>defined('ZERO_BUY')?ZERO_BUY:'',//0元购
            );
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //中文分词
    public function scws($title)
    {
        Vendor('scws.pscws4');
        $pscws = new \PSCWS4();
        $pscws->set_dict(VENDOR_PATH.'scws/lib/dict.utf8.xdb');
        $pscws->set_rule(VENDOR_PATH.'scws/lib/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text(trim($title));
        $words = $pscws->get_tops(5);
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        $pscws->close();
        $keywords=implode(',',$tags);
        echo $keywords;
    }

    //生成sitemap
    public function sitemap()
    {

    }

    /**
	 *  APP导航栏自定义
	 */
    public function customAppNav()
    {
        $list = array_combine(['index','live','shoot','shop','my'],['首页','直播','拍摄','商城','我的']);
        $applet_list = array_combine(['index','live','shop','my'],['首页','直播','商城','我的']);

        if ($_POST) {
            layout(false);
            $nav = I('post.nav/a');
            $nav_ios = I('post.nav_ios/a');
            $nav_applet = I('post.nav_applet/a');
            $nav_str = $ios_str = $applet_str = '';

            $key_arr = array_keys($nav);
            $nav_str = implode(',',$key_arr);

            $key_arr_ios = array_keys($nav_ios);
            $ios_str = implode(',',$key_arr_ios);

            $key_arr_app = array_keys($nav_applet);
            $applet_str = implode(',',$key_arr_app);

            $model_setting = new SettingModel();
            $file = "./Public/inc/config.php";
            // 保存nav
            $model_setting->set('APP_NAV', $nav_str, $file);
            $model_setting->set('APP_NAV_IOS', $ios_str, $file);
            $model_setting->set('APP_NAV_APPLET', $applet_str, $file);

            $this->cacheSetting($file);
            $this->success('更新成功！');
        } else {
            $ret = [];
            $key_vals = explode(',',APP_NAV);
            $key_vals_ios = explode(',',APP_NAV_IOS);
            $key_vals_app = explode(',',APP_NAV_APPLET);
            foreach ($key_vals as $val) {
                if (array_key_exists($val,$list)) {
                    $k = 'nav['.$val.']';
                    $ret[$k] = true;
                }
            }
            foreach ($key_vals_ios as $val) {
                if (array_key_exists($val,$list)) {
                    $k = 'nav_ios['.$val.']';
                    $ret[$k] = true;
                }
            }
            foreach ($key_vals_app as $val) {
                if (array_key_exists($val,$applet_list)) {
                    $k = 'nav_applet['.$val.']';
                    $ret[$k] = true;
                }
            }
            $this->assign('list', $list);
            $this->assign('applet_list', $applet_list);
            $this->assign('resource', json_encode($ret));
            $this->display();
        }
    }

    /**
	 *  小程序配置设置
	 */
    public function appletSet()
    {
        if ($_POST) {
            layout(false);
            $putaway = I('post.putaway_switch/d', 0);

            $model_setting = new SettingModel();
            $file = "./Public/inc/config.php";
            // 保存
            $model_setting->set('APPLET_PUTAWAY_SWITCH', $putaway, $file);
            $model_setting->set('APPLET_APPID', I('post.appid'), $file);
            $model_setting->set('APPLET_APPSECRET', I('post.appsecret'), $file);
            $model_setting->set('RED_PACKER_SWITCH', I('post.red_packet_switch'), $file);
            $model_setting->set('ANCHOR_PK_SWITCH', I('post.anchor_pk_switch'), $file);

            if (I('post.mall_method')) {
                $mall_data = array_keys(I('post.mall_method'));
                $mall_method = implode(",",$mall_data);
                $model_setting->set('PLATFORM_SYSTEM', $mall_method, $file);
            } else $model_setting->set('PLATFORM_SYSTEM', '', $file);
            $this->cacheSetting($file);
            $this->success('更新成功！');

        } else {
            $msg['putaway_switch'] = defined('APPLET_PUTAWAY_SWITCH') ? APPLET_PUTAWAY_SWITCH : 0;
            $msg['appid'] = defined('APPLET_APPID') ? APPLET_APPID : '';
            $msg['appsecret'] = defined('APPLET_APPSECRET') ? APPLET_APPSECRET : '';
            $msg['red_packet_switch'] = defined('RED_PACKER_SWITCH') ? RED_PACKER_SWITCH : '';
            $msg['anchor_pk_switch'] = defined('ANCHOR_PK_SWITCH') ? ANCHOR_PK_SWITCH : '';
            $list['platform_system'] = defined('PLATFORM_SYSTEM') ? PLATFORM_SYSTEM : 'jd,pdd';
            $this->assign('msg', json_encode($msg));
            $this->assign('msgs', $list);
            $this->display();
        }
    }

    /**
     * APP配置设置
     */
    public function appSet()
    {
        $rootPath = './Public/static/admin/img/';
        $sourceList = array(
            ['id' => 'tb','name'=> '淘宝','selected' => false],
            ['id' => 'jd','name'=> '京东','selected' => false],
            ['id' => 'pdd','name'=> '拼多多','selected' => false],
            ['id' => 'self','name'=> '自营商城','selected' => false],
        );
        $headSource  = $goodsSource = $sourceList;
        foreach ($headSource as $key => $value){
            if (defined('SOURCE_HEAD')) {
                if ($value['id'] == SOURCE_HEAD) {
                    $headSource[$key]['selected'] = true;
                }
            }
        }
        foreach ($goodsSource as $key => $value){
            if (defined('GOODS_SOURCE')) {
                if ($value['id'] == GOODS_SOURCE) {
                    $goodsSource[$key]['selected'] = true;
                }
            }
        }
        if (IS_POST) {
            // 保存数据
            $model_setting  = new SettingModel();
            $file  = "./Public/inc/config.php";

            $goods_cat_list = array();
            if (I('post.goods_cat')) {
                foreach (I('post.goods_cat') as $key => $value) {
                    $temp['id'] = $key;
                    $temp['checked'] = $value;
                    $goods_cat_list[] = $temp;
                }
            }
            // 保存
            $model_setting->set('TAB_BG_COL', I('post.tab_bg_col'), $file);
            $model_setting->set('TAB_WORD_COL', I('post.tab_word_col'), $file);
            $model_setting->set('SOURCE_HEAD', I('post.source_head'), $file);

            if ($_FILES['tab_img']['error'] == 0 && !is_null($_FILES['tab_img'])) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 1024 * 1024 * 4;// 设置附件上传大小
                $upload->exts = array('png');// 设置附件上传类型
                $upload->rootPath = $rootPath; // 设置附件上传根目录
                $upload->savePath = ''; // 设置附件上传（子）目录
                $upload->replace = true;
                $upload->autoSub = false;
                $upload->saveName = 'tab_img';
                // 上传文件
                $info = $upload->uploadOne($_FILES['tab_img']);

                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }
                $tab_img = '/Public/static/admin/img/'.$info['savename'];

                // 删除旧图
                @unlink('./'.TAB_IMG);
                $model_setting->set('TAB_IMG', $tab_img, $file);
            }

            $this->cacheSetting($file);
            $this->success('设置成功！');
        } else {
            $msg['tab_img'] = defined('TAB_IMG') ? TAB_IMG : "";
            $msg['tab_bg_col'] = defined('TAB_BG_COL') ? TAB_BG_COL : "#cf2d2d";
            $msg['tab_word_col'] = defined('TAB_WORD_COL') ? TAB_WORD_COL : "#18189c";

            $this->assign('headSource', $headSource);
            $this->assign('msg', $msg);

            $this->display();
        }
    }

    /**
     * APP 首页活动来源
     */
    public function headAdvertSet()
    {
        //获取商品分类列表
        $this->display();
    }

    /**
     * 获取活动数据
     */
    public function getHeadAdvertSet()
    {
        $AdvertHead = new AdvertHeadModel();
        $list = $AdvertHead->getAdvertList();
        foreach ($list as $key => $value) {
            $source = $this->getSourceList($value['advert_source']);
            foreach ($source[$value['advert_source']] as $k => $v) {
                if ($v['cat_id'] == $value['advert_catgray']) {
                    $list[$key]['advert_catgray_name'] = $v['name'];
                }
            }
        }
        $this->ajaxReturn([
            'code' => 0,
            'data' => $list,
            'msg'  => '成功'
        ]);
    }

    /**
     * 删除活动
     */
    public function headAdvertDel()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数错误或者缺失');
        }

        $AdvertHead = new AdvertHeadModel();
        $res = $AdvertHead->delete($id);
        if ($res) {
            $this->ajaxReturn([
                'code' => 0,
                'data' => $res,
                'msg'  => '删除成功'
            ]);
        } else {
            $this->ajaxReturn([
                'code' => 1,
                'data' => $res,
                'msg'  => '删除失败'
            ]);
        }
    }

    /**
     * APP 添加修改活动来源
     */
    public function headAdvertAdd()
    {
        if (IS_POST) {
            layout(false);
            $postArr = I('post.');
            $id = I('get.id');
            if (empty($postArr['advert_title']) && empty($postArr['advert_client']) && empty($postArr['advert_source']) && empty($postArr['diy_id']) ) {
                $this->error('参数错误或者缺失');
            }
            $data = array(
                'advert_title' => $postArr['advert_title'],
                'advert_source' => $postArr['advert_source'],
                'diy_id' => $postArr['diy_id'],
                'advert_catgray' => $postArr['advert_catgray'],
                'advert_cat' => $postArr['advert_cat'],
                'advert_cat_id' => $postArr['advert_cat_id'],
                'advert_word' => $postArr['advert_word'],
                'advert_amount_min' => $postArr['advert_amount_min'],
                'advert_amount_max' => $postArr['advert_amount_max'],
                'advert_price_min' => $postArr['advert_price_min'],
                'advert_price_max' => $postArr['advert_price_max'],
            );

            $model_setting  = new SettingModel();
            $file  = "./Public/inc/config.php";
            if (!empty($_FILES['head_imgs']['name'])) {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 1024 * 1024 * 4;// 设置附件上传大小
                $upload->exts = array('png');// 设置附件上传类型
                $upload->rootPath = './Public/static/admin/img/'; // 设置附件上传根目录
                $upload->savePath = ''; // 设置附件上传（子）目录
                $upload->replace = true;
                $upload->autoSub = false;
                $upload->saveName = 'head_imgs';
                // 上传文件
                $info = $upload->uploadOne($_FILES['head_imgs']);

                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }
                $head_imgs = '/Public/static/admin/img/'.$info['savename'];
                if (!empty($postArr['id'])) {
                    // 删除旧图
//                    @unlink('./'.HEAD_IMGS);
                }
                $model_setting->set('HEAD_IMGS', $head_imgs, $file);
                $data['advert_img'] = $head_imgs;
            }

            if ($postArr['advert_switch_open'] == 'on') {
                $advert_switch = 1;
            } else {
                $advert_switch = 2;
            }
            $data['advert_coupon'] = $postArr['advert_coupon'];
            $data['advert_switch'] = $advert_switch;

            $advertHead = new AdvertHeadModel();

            if (empty($postArr['id'])) {
                $checkID = $advertHead->where([
                    'advert_modular' => $postArr['advert_modular'],
                    'advert_client' => $postArr['advert_client'],
                    'advert_switch' => 1
                ])->find();
                if ($checkID) {
                    $this->error('活动已经存在，请重新选择');
                }
                $data['advert_modular'] = $postArr['advert_modular'];
                $data['advert_client'] = $postArr['advert_client'];
                $data['add_time'] = date('Y-m-d H:i:s');
                $rets = $advertHead->add($data);
                if ($rets) {
                    $this->success('新增活动成功！',U('System/headAdvertSet'));
                } else {
                    $this->error('操作失败！');
                }

            } else {
                $data['update_time'] = date('Y-m-d H:i:s');
                $rets = $advertHead->where(['id' => $postArr['id']])->save($data);
                if ($rets) {
                    $this->success('更新活动成功！',U('System/headAdvertSet'));
                } else {
                    $this->error('操作失败！');
                }
            }
            $this->cacheSetting($file);
        } else {
            $id = I('get.id');
            $advertSource = array(
                ['id' => 'tb','name'=> '淘宝','selected' => false],
                ['id' => 'jd','name'=> '京东','selected' => false],
                ['id' => 'pdd','name'=> '拼多多','selected' => false],
                ['id' => 'self','name'=> '自营商城','selected' => false],
            );
            if (!empty($id)) {
                $AdvertHead = new AdvertHeadModel();
                $resout = $AdvertHead->where(['id' => $id])->find();
                if (!empty($resout)) {
                    foreach ($advertSource as $key => $value){
                        if ($value['id'] == $resout['advert_source']) {
                            $advertSource[$key]['selected'] = true;
                        }
                    }
                }
                $this->assign('resout', $resout);
            }
            $this->assign('advertSource', $advertSource);
            $this->assign('id', $id);
            $this->display();
        }
    }

    /**
     * 删除图片
     * @return bool
     */
    public function deletsImg()
    {
        $id = I('post.id');
        $head_img_name = I('post.head_img_name');
        if ($id || $head_img_name) {
            $AdvertHead = new AdvertHeadModel();
            $data['advert_img'] = "";
            $res = $AdvertHead->where("id=$id")->save($data);
            if ($res) {
                // 删除旧图
                @unlink('./'.$head_img_name);
                $model_setting  = new SettingModel();
                $model_setting->set('HEAD_IMGS', "", "./Public/inc/config.php");
                $this->ajaxSuccess([$res]);
            }
        } else {
            return false;
        }
    }

    /**
     * 修改活动开关
     * @return bool
     */
    public function setAdvertChecked()
    {
        $ids = I('post.id');
        $advert_switch = I('post.advert_switch');
        $ids = explode('_', $ids);
        if ($ids[0]) {
            $advertHead = new AdvertHeadModel();
            if ($advert_switch == 'true') {
                $advert_switch = 1;
            } else {
                $advert_switch = 2;
            }
            if ($advert_switch == 1) {
                // 检测是否有获取已经开启
                $che1 = $advertHead->where(["advert_modular" => $ids[1],'advert_client'=> 'app','advert_switch'=>1])->find();
                $che2 = $advertHead->where(["advert_modular" => $ids[1],'advert_client'=> 'applets','advert_switch'=>1])->find();
                if ($che1) {
                    $this->ajaxError($che1);
                }
                if ($che2) {
                    $this->ajaxError($che2);
                }
            }

            $data['advert_switch'] = $advert_switch;
            $res = $advertHead->where("id=$ids[0]")->save($data);
            if ($res) {
                $this->ajaxSuccess([$res]);
            }
        } else {
            return false;
        }
    }

    public function getSourceList($types = '')
    {
        $arr_cat = [];
        if (empty($types)) {
            $type = I('get.type');
        } else {
            $type = $types;
        }
        $cat_list = I('get.cat_list');
        if (!empty($cat_list)) {
            $arr_cat = explode(',',$cat_list);
        }

        // 淘宝分类
        $TaobaoCatList = new TaobaoCatModel();
        $taobaolist = $TaobaoCatList->getGoodsCatList();
        $tbList = array();
        foreach ($taobaolist as $key => $value) {
            if ($value['pid'] == 0) {
                $k = $value['taobao_cat_id'];
                $tbList[$k]['cat_id'] = $value['taobao_cat_id'];
                $tbList[$k]['name'] = $value['name'];
                $tbList[$k]['checked'] = false;
            }
        }

        // 拼多多分类
        $PddCat = new \Common\Model\PddCatModel();
        $pddList = $PddCat->getGoodsCatList();
        $pdList = array();
        foreach ($pddList as $key => $value) {
            $k = $value['pdd_id'];
            $pdList[$k]['cat_id'] = $value['pdd_id'];
            $pdList[$k]['name'] = $value['name'];
            $pdList[$k]['checked'] = false;
        }

        // 京东分类
        $JingdongCat = new \Common\Model\JingdongCatModel();
        $Jingdonglist = $JingdongCat->getGoodsCatList();
        $JdList = array();
        foreach ($Jingdonglist as $key => $value) {
            $k = $value['jingdong_id'];
            $JdList[$k]['cat_id'] = $value['jingdong_id'];
            $JdList[$k]['name'] = $value['name'];
            $JdList[$k]['checked'] = false;
        }

        // 自营分类
        $GoodsCat = new \Common\Model\GoodsCatModel();
        $goodslist = $GoodsCat->getCatList();
        $selfList = array();
        foreach ($goodslist as $key => $value) {
            if ($value['parent_id'] == 0) {
                $k = $value['cat_id'];
                $selfList[$k]['cat_id'] = $value['cat_id'];
                $selfList[$k]['name'] = $value['cat_name'];
                $selfList[$k]['checked'] = false;
            }
        }
        // 设置选择的分类
        if (!empty($type)) {
            if (!empty($arr_cat)) {
                if ($type == 'tb') {
                    foreach ($tbList as $key => $value) {
                        if (in_array($value['cat_id'],$arr_cat)) {

                            $tbList[$key]['checked'] = true;
                        }
                    }
                } elseif ($type == 'jd') {
                    foreach ($JdList as $key => $value) {
                        if (in_array($value['cat_id'],$arr_cat)) {
                            $JdList[$key]['checked'] = true;
                        }
                    }
                } elseif ($type == 'pdd') {
                    foreach ($pdList as $key => $value) {
                        if (in_array($value['cat_id'],$arr_cat)) {
                            $pdList[$key]['checked'] = true;
                        }
                    }
                } elseif ($type == 'self') {
                    foreach ($selfList as $key => $value) {
                        if (in_array($value['cat_id'],$arr_cat)) {
                            $selfList[$key]['checked'] = true;
                        }
                    }
                }
            }
        }
        sort($selfList);
        sort($JdList);
        sort($pdList);
        sort($tbList);

        $retCatList = array(
            'tb' => $tbList,
            'jd' => $JdList,
            'pdd' => $pdList,
            'self' => $selfList
        );
        if (empty($types)) {
            $this->ajaxReturn($retCatList[$type]);
        } else {
            return $retCatList;
        }
    }

    /**
	 *  礼物相关设置
	 */
    public function LiveGiftCnSet()
    {
        if ($_POST) {
            layout(false);

            $model_setting  = new SettingModel();
            $file           = "./Public/inc/config.php";

            // 提交的key
            $post_key       = ['gift_money_cn', 'gift_deer_cn', 'gift_money_dsc', 'gift_deer_dsc', 'gift_wd_dsc', 'gift_d_ratio', 'gift_r_ratio', 'gift_cost', 'gift_cost', 'gift_convert_min', 'gift_extract_min'];

            // 保存
            foreach ($post_key as $val) {
                $model_setting->set(strtoupper($val), I('post.'. $val), $file);
            }

            $this->cacheSetting($file);

            $this->ajaxSuccess();

        } else {
            $msg['gift_money_cn']   = defined('GIFT_MONEY_CN') ? GIFT_MONEY_CN : '';
            $msg['gift_deer_cn']    = defined('GIFT_DEER_CN') ? GIFT_DEER_CN : '';
            $msg['gift_money_dsc']  = defined('GIFT_MONEY_DSC') ? GIFT_MONEY_DSC : '';
            $msg['gift_deer_dsc']   = defined('GIFT_DEER_DSC') ? GIFT_DEER_DSC : '';
            $msg['gift_wd_dsc']     = defined('GIFT_WD_DSC') ? GIFT_WD_DSC : '';
            $msg['gift_d_ratio']    = defined('GIFT_D_RATIO') ? GIFT_D_RATIO : '';
            $msg['gift_r_ratio']    = defined('GIFT_R_RATIO') ? GIFT_R_RATIO : '';
            $msg['gift_cost']       = defined('GIFT_COST') ? GIFT_COST : '';
            $msg['gift_convert_min']= defined('GIFT_CONVERT_MIN') ? GIFT_CONVERT_MIN : '';
            $msg['gift_extract_min']= defined('GIFT_EXTRACT_MIN') ? GIFT_EXTRACT_MIN : '';

            $this->assign('msg', $msg);

            $this->display();
        }
    }

    /**
	 *  房间相关设置
	 */
    public function LiveRoomSet()
    {
        if ($_POST) {
            layout(false);

            $model_setting  = new SettingModel();
            $file           = "./Public/inc/config.php";

            // 提交的key
            $post_key       = ['live_hint_user', 'live_hint_host'];

            // 保存
            foreach ($post_key as $val) {
                $model_setting->set(strtoupper($val), I('post.'. $val), $file);
            }

            $this->cacheSetting($file);

            $this->ajaxSuccess();

        } else {
            $msg['live_hint_user']  = defined('LIVE_HINT_USER') ? LIVE_HINT_USER : '';
            $msg['live_hint_host']  = defined('LIVE_HINT_HOST') ? LIVE_HINT_HOST : '';


            $this->assign('msg', $msg);

            $this->display();
        }

    }

    /**
     * 分销开关
     */
    public function distribution()
    {
        if($_POST) {
            layout(false);
            $cache_file1 = "./Public/inc/config.php";
            $cache_file2 = './Public/inc/draw.config.php';
            $model_setting = new SettingModel();
            //返利时间
            $rebate_time=I('post.rebate_times');
            $is_distribution=I('post.is_distribution');
            $model_setting->set('rebate_times', $rebate_time, $cache_file2);
            $model_setting->set('is_distribution', $is_distribution, $cache_file1);
            if ($is_distribution == 'N') {
                $Goods = new \Common\Model\GoodsModel();
                $Goods->where(['is_fx_goods'=>'Y'])->save(['is_fx_goods'=>'N','fx_profit_money'=>0]);
            }
            $this->cacheSetting($cache_file1);
            $this->cacheSetting($cache_file2);
            $this->success('保存成功！');
        } else {
            //获取配置文件
            require_once './Public/inc/draw.config.php';
            $msg=array(
                'rebate_times'=>defined('REBATE_TIMES')?REBATE_TIMES:'',//返利时间
            );
            $msg['is_distribution']=defined('IS_DISTRIBUTION')?IS_DISTRIBUTION:'N';//更新ios
            $this->assign('msg',$msg);
            $this->display();
        }
    }

    public function pictureManagement()
    {
        if($_POST || $_FILES['logoImg'] || $_FILES['screenImg'] || $_FILES['loginImg'] || $_FILES['liveImg']) {
            //上传logo
            if(!empty($_FILES['logoImg']['name'])) {

                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png','jpg' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'login_img', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['logoImg'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }

            //上传屏幕
            if(!empty($_FILES['screenImg']['name'])) {

                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png','jpg' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'screen_img', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['screenImg'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }

            //上传登录logo
            if(!empty($_FILES['loginImg']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png','jpg' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'logo_img', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['loginImg'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }

            if(!empty($_FILES['liveImg']['name'])) {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array( 'png','jpg' ), //允许上传的文件后缀
                    'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                    'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'live_img', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['liveImg'],1);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                }
            }
            layout(false);
        }
        $this->display();
    }

    public function countryCode() {
        $country = new \Common\Model\CountryCodeModel();
        if ($_POST) {
            $id = I('post.id');
            $map['country'] = I('post.country');
            $map['code'] = I('post.code');
            $map['type'] = I('post.type');
            if ($id) {
                $country->where(['id'=>$id])->save($map);
            } else {
                $country->add($map);
            }
            $this->ajaxSuccess();
        } else {
            $msg = $country->select();
            $this->assign('msg', $msg);
            $this->display();
        }
    }

    public function countryAdd(){
        $id = I('get.id', 0);
        $country = new \Common\Model\CountryCodeModel();
        if($id > 0){
            $where = ['id' => $id];
            $bk_cat = $country->getOne($where);
            $this->assign('info', $bk_cat);
        }
        $this->assign('id', $id);
        $this->display();
    }

    //清理缓存
    public function cleancache()
    {
        $dirName=APP_PATH.'Runtime';
        delDirAndFile($dirName);
        layout(false);
        $this->cacheSetting('./Public/inc/account.config.php');
        $this->cacheSetting('./Public/inc/config.php');
        $this->cacheSetting('./Public/inc/draw.config.php');
        $this->cacheSetting('./Public/inc/user.config.php');
        $this->cacheSetting('./Public/inc/extra.config.php');
        $this->success('清理缓存成功！');
    }
}
