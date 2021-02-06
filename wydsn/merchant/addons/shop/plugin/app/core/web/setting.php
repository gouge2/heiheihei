<?php

//20200612
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Setting_EweiShopV2Page extends PluginWebPage {
    public function main() {
        global $_W, $_GPC;

        if(empty($_W['shopversion'])){
            $this->message('请使用新版本访问');
        }

        // 系统设置
        $sets = m('common')->getSysset(array('app', 'pay'));

        // 如果有权限设置支付
        if(cv('app.setting.pay')){
            $sec = m('common')->getSec();
            $sec = iunserializer($sec['sec']);
            if(!is_array($sec['wxapp']) || !isset($sec['wxapp'])){
                $sec['wxapp'] = array();
            }
        }
        
        
        if($_W['ispost']){
            //开启电话客服时，判断电话号码是否存在
            if($_GPC['set']['phone'] == 1){
                if($_GPC['set']['phonenumber'] == '' || empty($_GPC['set']['phonenumber'])){
                    show_json(0,'请输入客服电话号码');
                }

                // 港澳台大陆通用手机正则验证
                //$commonPattern = '/^[1][3-8]\d{9}$|^([6|9])\d{7}$|^[0][9]\d{8}$|^[6]([8|6])\d{5}$/';
                //$telPhone   = '/^\d{7,8}$|^\d{10,12}$/';
                //if(
                //    !preg_match($commonPattern, $_GPC['set']['phonenumber']) ||
                //    !preg_match($telPhone, $_GPC['set']['phonenumber'])
                //)
                //{
                //    show_json(0,'请输入正确的手机号码或座机号码');
                //}
            }
        


            // 保存基本设置
            if(cv('app.setting.edit')){

                $arr_set = $sets['app'];
                $arr_set['appid'] = trim($_GPC['set']['appid']);
                $arr_set['secret'] = trim($_GPC['set']['secret']);
                $arr_set['isclose'] = intval($_GPC['set']['isclose']);
                $arr_set['closetext'] = trim($_GPC['set']['closetext']);
                $arr_set['openbind'] = intval($_GPC['set']['openbind']);
                $arr_set['sms_bind'] = intval($_GPC['set']['sms_bind']);
                $arr_set['bindtext'] = trim($_GPC['set']['bindtext']);
                $arr_set['hidecom'] = intval($_GPC['set']['hidecom']);
                $arr_set['navbar'] = intval($_GPC['set']['navbar']);
                $arr_set['sendappurl'] = trim($_GPC['set']['sendappurl']);
                $arr_set['customer'] = intval($_GPC['set']['customer']);
                $arr_set['customercolor'] = trim($_GPC['set']['customercolor']);
                $arr_set['tmessage_pay'] = intval($_GPC['set']['tmessage_pay']);
                $arr_set['tmessage_send'] = intval($_GPC['set']['tmessage_send']);
                $arr_set['tmessage_virtualsend'] = intval($_GPC['set']['tmessage_virtualsend']);
                $arr_set['tmessage_finish'] = intval($_GPC['set']['tmessage_finish']);
                $arr_set['phone'] = intval($_GPC['set']['phone']);
                $arr_set['phonenumber'] = trim($_GPC['set']['phonenumber']);
                $arr_set['phonecolor'] = trim($_GPC['set']['phonecolor']);
                // 1.7优化增加授权方式&优化小程序核销码域名设置
                $arr_set['force_auth'] = $_GPC['set']['force_auth'] ?  $_GPC['set']['force_auth']: null;
                $arr_set['verifyurl'] = trim($_GPC['set']['verifyurl']);
                if(!strexists($arr_set['verifyurl'],'http') && !empty($arr_set['verifyurl'])){
                     show_json(0,'请输入正确的核销二维码生成链接');
                }
                if(!empty($arr_set['verifyurl']) && substr($arr_set['verifyurl'], -1) =='/'){
                    $arr_set['verifyurl'] = rtrim($arr_set['verifyurl'],'/');
                }
                // 订阅消息设置
                $arr_set['subscribepay'] = trim($_GPC['set']['subscribepay']);
                $arr_set['subscribesend'] = trim($_GPC['set']['subscribesend']);
                $arr_set['subscribeautosend'] = trim($_GPC['set']['subscribeautosend']);
                $arr_set['subscribereceive'] = trim($_GPC['set']['subscribereceive']);
                
                m('common')->updateSysset(array('app' => $arr_set));
                plog('app.setting.edit', '保存基本设置');
            }

            // 保存支付设置
            if(cv('app.setting.pay')){

                if ($_FILES['wxapp_cert_file']['name']) {
                    $sec['wxapp_cert'] = $this->upload_cert('wxapp_cert_file');
                }
                if ($_FILES['wxapp_key_file']['name']) {
                    $sec['wxapp_key'] = $this->upload_cert('wxapp_key_file');
                }
                if ($_FILES['wxapp_root_file']['name']) {
                    $sec['wxapp_root'] = $this->upload_cert('wxapp_root_file');
                }

                $sec['wxapp']['mchid'] = trim($_GPC['pay']['wxapp_mchid']);
                $sec['wxapp']['apikey'] = trim($_GPC['pay']['wxapp_apikey']);
                pdo_update('ewei_shop_sysset', array('sec' => iserializer($sec)), array('uniacid' => $_W['uniacid']));

                $arr_pay = $sets['pay'];
                $arr_pay['wxapp'] = intval($_GPC['pay']['wxapp']);
                m('common')->updateSysset(array('pay' => $arr_pay));

                plog('app.setting.pay', '保存支付设置');
            }

            show_json(1);
        }

        // 短信模板列表
        if(com('sms')){
            $sms_list = com('sms')->sms_temp();
        }

        // 模板消息列表
        $tmsg_list = pdo_fetchall("SELECT id, `name` FROM " . tablename('ewei_shop_wxapp_tmessage'). 'WHERE uniacid=:uniacid AND status=1', array(':uniacid'=>$_W['uniacid']));

        // 是否有订阅消息id，有的话再点开启不请求接口 TODO
        $subscribe_list = pdo_fetchall("select * from ".tablename('ewei_shop_wxapp_subscribe').' where uniacid = :uniacid ', array(':uniacid' => $_W['uniacid']));
        // 判断分销是否有插件，是否开启
        $commission = false;
        if(p('commission') ){
            if(intval($_W['shopset']['commission']['level']>0)){
                $commission = true;
            }
        }

        $customercolors = array('#ff5555', '#000000', '#2ad329', '#46aaff', '#ffb137');
        $customercolor = empty($sets['app']['customercolor'])? '#ff5555': $sets['app']['customercolor'];

        $phonecolors = array('#ff5555', '#000000', '#2ad329', '#46aaff', '#ffb137');
        $phonecolor = empty($sets['app']['phonecolor'])? '#ff5555': $sets['app']['phonecolor'];

        include $this->template();
    }

    // 上传证书
    protected function upload_cert($fileinput) {
        global $_W;
        $path = IA_ROOT . "/addons/shop/cert";
        load()->func('file');
        mkdirs($path);
        $f = $fileinput . '_' . $_W['uniacid'] . '.pem';
        $outfilename = $path . "/" . $f;
        $filename = $_FILES[$fileinput]['name'];
        $tmp_name = $_FILES[$fileinput]['tmp_name'];
        if (!empty($filename) && !empty($tmp_name)) {
            $ext = strtolower(substr($filename, strrpos($filename, '.')));
            if ($ext != '.pem') {
                $errinput = "";
                if ($fileinput == 'weixin_cert_file') {
                    $errinput = "CERT文件格式错误";
                } else if ($fileinput == 'weixin_key_file') {
                    $errinput = 'KEY文件格式错误';
                } else if ($fileinput == 'weixin_root_file') {
                    $errinput = 'ROOT文件格式错误';
                }
                show_json(0, $errinput . ',请重新上传!');
            }
            return file_get_contents($tmp_name);
        }
        return "";
    }
    
    /**
     * 设置订阅消息模板
     * @author zhurunfeng
     */
    public function setTemplate()
    {
        global $_W, $_GPC;
        // 通知类型
        $type = $_GPC['type'];
        
        $subscribe = pdo_fetchall("select * from ".tablename('ewei_shop_wxapp_subscribe').' where uniacid = :uniacid and type=:type ', array(':uniacid' => $_W['uniacid'], ':type' => $type));
        if (!empty($subscribe)) {
            return show_json(1);
        }
        
        $accessToken = $this->model->getAccessToken();
        $data = array();
        switch ($type) {
            case 'pay':
                // 支付通知
                $data['tid'] = '1253';
                $data['kidList'] = array(1,3,4,2);
                $data['sceneDesc'] = '买家支付通知';
                break;
            case 'send':
                // 发货通知
                $data['tid'] = '855';
                $data['kidList'] = array(1,2,3,4,5);
                $data['sceneDesc'] = '卖家发货通知';
                break;
            case 'autosend':
                // 自动发货通知
                $data['tid'] = '855';
                $data['kidList'] = array(1,2,5,8);
                $data['sceneDesc'] = '自动发货通知';
                break;
            case 'receive':
                // 收货通知
                $data['tid'] = '1918';
                $data['kidList'] = array(1,2,3);
                $data['sceneDesc'] = '收货通知';
                break;
        }
        
        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/addtemplate?access_token=".$accessToken;


        //$result = $this->model->execCurl($url, $data);
        //$result = $this->model->execCurl($url, $data);
        $result = ihttp_request($url,$data);;
        $result = json_decode($result['content'], true);

        // 判断是否有误，有错误返回错误信息
        if($result['errcode'] != 0) {
            return show_json(-1, array('errmsg' => $result['errmsg']));
        }
        $ins = array(
            'uniacid' => $_W['uniacid'],
            'type' => $type,
            'templateid' => $result['priTmplId'],
            'createtime' => time()
        );
        // 没错误插入数据库
        pdo_insert('ewei_shop_wxapp_subscribe', $ins);
        
        return show_json(1);
    }


    /**
     * 删除小程序模板消息
     * @author 伟霆
     */
    public function deleteTemplate()
    {
        global $_W, $_GPC;
        // 通知类型
        $type = $_GPC['type'];

        $subscribe = pdo_fetch("select * from ".tablename('ewei_shop_wxapp_subscribe').' where uniacid = :uniacid and type=:type ', array(':uniacid' => $_W['uniacid'], ':type' => $type));
        if (empty($subscribe)) {
            return show_json(0,'模板不存在');
        }
        $data['priTmplId'] = $subscribe['templateid'];
        $accessToken = $this->model->getAccessToken();

        $url = "https://api.weixin.qq.com/wxaapi/newtmpl/deltemplate?access_token=".$accessToken;


        //$result = $this->model->execCurl($url, $data);
        //$result = $this->model->execCurl($url, $data);
        $result = ihttp_request($url,$data);;
        $result = json_decode($result['content'], true);
        // 判断是否有误，有错误返回错误信息
        if($result['errcode'] != 0) {
            return show_json(0,  $result['errmsg']);
        }
        // 没错误插入数据库
        pdo_delete('ewei_shop_wxapp_subscribe', array('id'=>$subscribe['id']));

        return show_json(1);
    }
    
}