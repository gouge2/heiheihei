<?php
/**
 * by 翠花 www.lailu.shop
 *  AccountConfig/获取配置账号接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;
use Common\Model\UserModel;

class AccountConfigController extends AuthController
{
    /**
     * 获取Config/淘宝客账号
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data:tbk_/配置账号数据
     */
    public function getAccountConfig()
    {

        $data=array(
            'dtk_appkey' => DTK_APP_KEY,
            'dtk_appsecret' => DTK_APP_SECRET,
            'tbk_appkey' => TBK_APPKEY,
            'tbk_appsecret' => TBK_APPSECRET,
            'tbk_adzone_id' => TBK_ADZONE_ID,
            'tbk_auth_code' => AUTH_CODE,
            've_key' => WY_APPKEY,
            'pdd_client_id' => PDD_CLIENT_ID,
            'pdd_client_secret' => PDD_CLIENT_SECRET,
            'mob_template' => MOB_APPTEMPLATE,
            'int_mob_template' => INT_MOB_TEMPLATE,
        );

        $plat=I('post.platform');

        $langList=M('multi_language')->where(['client_type'=>$plat])->select();


        $data['language'] = [];
        foreach ($langList as $v){
            $country=M('country')->where(['cid'=>$v['country_id']])->select();
           $data['language'][]=[
            'lang' =>$v['lang_sign'],
            'name' =>$country[0]['name'],
             ];

       }


        $res=array(
            'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
            'msg'=>'成功',
            'data'=>$data
        );
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取语言包
     */
    public function getLang(){
        $lang=I('post.lang');
        $plat=I('post.platform');
        $data=M('multi_language')->where(['client_type'=>$plat,'lang_sign'=>$lang])->select();

        $this->ajaxSuccess(json_decode($data[0]['lang_text']));
    }

    /**
     * APP打包配置
     */
    public function getAppConfig()
    {
        $resData = array(
            "qd_app_key" => TBK_APPKEY,         //淘宝客App key
            "qd_app_secret" => TBK_APPSECRET,   // 淘宝客App secret
            "qd_app_code" => AUTH_CODE,         //联盟授权码
            "bc_app_key" => BC_APP_KEY,         //阿里百川
            "wx_app_id" => WXPAY_APPID,         //开放平台APPID
            "wx_app_secret" => WXPAY_APPSECRET,  //开放平台APPSECRET
            "jpush_key" => JPUSH_KEY,            // 极光推送key
            "jd_app_id" => ANDROID_APPKEY,       // 安卓appkey
            "jd_app_secret" => ANDROID_APPSECRET,   //安卓appsecret
            "jd_unionid" => JD_UNIONID,          //京东用户id
            "pdd_client_id" => PDD_CLIENT_ID,   //拼多多client_id
            "pdd_app_secret" => PDD_CLIENT_SECRET,  //拼多多client_secret
            "mob_app_key" => MOB_APPKEY,         // mob账号appkey
            "mob_app_secret" => MOB_APPSECRET,   // mob账号appsecret
            "auth" => "76D3b5240a99b2131be",
            "v_key" => WY_APPKEY,           // 维易淘宝客key
            "platform_invitr" => PLATFORM_INVITR_CN,   // 翠花号
            "tx_sdk_app_id" => TENCENT_IM_SDKAPPID,   //腾讯IM sdkappid
            "licence_url" => defined('TENCENT_LICENCE_URL') ? TENCENT_LICENCE_URL : '',  // 腾讯云直播licence
            "license_key" => defined('TENCENT_LICENCE_KEY') ? TENCENT_LICENCE_KEY : '',   //腾讯云直播licence_key
            "licence_url_ugc" => defined('TENCENT_LICENCE_URL_UGC' ) ? TENCENT_LICENCE_URL_UGC : '',// 腾讯云点播licence
            "twitter_appkey" => defined('TWITTER_APPKEY') ? TWITTER_APPKEY : '', // 推特apikey
            "twitter_secretkey" => defined('TWITTER_SECRETKEY') ? TWITTER_SECRETKEY : '', //推特secretkey
        );
        if (TBXT_SWITCH && JDXT_SWITCH && PDDXT_SWITCH) {
            $resData['platform_system'] = 'self,tb,jd,pdd';
        } else {
            if (TBXT_SWITCH && !JDXT_SWITCH && !PDDXT_SWITCH){
                $resData['platform_system'] = 'self,tb';
            } elseif (TBXT_SWITCH && JDXT_SWITCH && !PDDXT_SWITCH){
                $resData['platform_system'] = 'self,tb,jd';
            } elseif (TBXT_SWITCH && !JDXT_SWITCH && PDDXT_SWITCH) {
                $resData['platform_system'] = 'self,tb,pdd';
            } elseif (!TBXT_SWITCH && JDXT_SWITCH && PDDXT_SWITCH) {
                $resData['platform_system'] = 'self,jd,pdd';
            } elseif (!TBXT_SWITCH && !JDXT_SWITCH && PDDXT_SWITCH) {
                $resData['platform_system'] = 'self,pdd';
            } elseif (!TBXT_SWITCH && JDXT_SWITCH && !PDDXT_SWITCH) {
                $resData['platform_system'] = 'self,jd';
            } else {
                $resData['platform_system'] = 'self';
            }
        }


        $this->ajaxSuccess($resData);
    }

    /**
     * App tab分类页面顶部UI
     */
    public function getAppTab()
    {
        $resData = array(
            'tab_img' => (defined('TAB_IMG') && TAB_IMG !=="") ? TAB_IMG : "",
            'tab_bg_col' => (defined('TAB_BG_COL') && TAB_BG_COL !== "") ? TAB_BG_COL : "#cf2d2d",
            'tab_word_col' => (defined('TAB_WORD_COL') && TAB_WORD_COL !=="") ? TAB_WORD_COL : "#18189c",
        );

        $this->ajaxSuccess($resData);
    }

    /**
     * 红包开关
     * 直播PK开关
     * @return array
     */
    public function getRedPacketSwitch()
    {
        $resData = array(
            'red_packet_switch' => (defined('RED_PACKER_SWITCH') && RED_PACKER_SWITCH !== "") ? RED_PACKER_SWITCH : 'off',
            'anchor_pk_switch' => (defined('ANCHOR_PK_SWITCH') && ANCHOR_PK_SWITCH !== "") ? ANCHOR_PK_SWITCH : 'off',
            'platform_system' => defined('PLATFORM_SYSTEM') ? 'self,'.PLATFORM_SYSTEM : 'self,jd,pdd',
        );
        $resData['platform_system'] = rtrim($resData['platform_system'],',');
        $this->ajaxSuccess($resData);
    }

    /**
     * 是否开启了更新
     */
    public function isUpdate(){
        $platform = I("post.platform");
        if($platform == 'android'){
            return $this->ajaxSuccess(defined("to_update") ? to_update == 'Y' : false);
        }
        return $this->ajaxError('参数错误');
    }

}
?>
