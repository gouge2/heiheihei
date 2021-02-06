<?php
/**
 * 主播实名认证管理
 */

namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class HostAuthenticationController extends AuthController
{
    public function index() {
        $where="1";

        //真实姓名
        if(I('get.real_name'))
        {
            $real_name=trim(I('get.real_name'));
            $where.=" and real_name='$real_name'";
        }
        //真实姓名
        if(I('get.real_status'))
        {
            $real_status=trim(I('get.real_status'));
            $where.=" and real_status='$real_status'";
        }

        $RealName = new \Common\Model\RealNameModel();
        $count = $RealName->where($where)->count();
        $per = 15;
        if($_GET['p'])
        {
            $p=$_GET['p'];
        }else {
            $p=1;
        }
        // 分页显示输出
        $Page=new \Common\Model\PageModel();
        $show= $Page->show($count,$per);
        $this->assign('page',$show);

        $realNameList = $RealName->where($where)->page($p.','.$per)->order('add_time desc')->select();

        $this->assign('list', $realNameList);

        $this->display();
    }

    // 编辑
    public function update($id) {
        $RealName = new \Common\Model\RealNameModel();
        $realNameMsg = $RealName->where("real_id=$id")->find();

        $this->assign('msg',$realNameMsg);

        if(I('post.')) {
            layout(false);
            $data=array(
                'real_status'=>trim(I('post.real_status')),
                'fail_explain'=>trim(I('post.fail_explain')),
            );

            $is_succ = false;
            if ($data['real_status'] != 'fail') {
                $data['fail_explain'] = "";
                $is_succ = true;
            }

            $RealName->where("real_id=$id")->save($data);

            // 认证通过 成员改成主播身份
            if ($data['real_status'] == 'pass') {
                $User       = new \Common\Model\UserModel();
                $LiveRoom   = new \Common\Model\LiveRoomModel();

                $uid        = $realNameMsg['user_id'];
                
                // 改成主播身份
                $User->where(['uid' => $uid])->save(['is_host' => 'Y']);

                // 插入房间记录
                $l_one      = $LiveRoom->where(['user_id' => $uid])->getField('room_id');
                if (!$l_one) {
                    $LiveRoom->add(['user_id' => $uid]);
                }
            }

            if (in_array($data['real_status'], array('fail', 'pass'))) {
                $User = new \Common\Model\UserModel();
                $UserMsg = $User->where("uid=" . $realNameMsg['user_id'])->find();
                if ($UserMsg['openid']) {
                    $WxServicePushModel = new \Common\Model\WxServicePushModel();
                    $WxServicePushModel->push("authentication",
                        array("实名认证审核", $is_succ ? '通过' : '不通过', date('Y-m-d H:i:s'), $data['fail_explain']),
                        $UserMsg['openid']);
                }
            }
            $this->success('修改状态成功！',U('index'));
        } else {
            $this->display();
        }
    }

    // 删除
    public function del($id) {
        $RealName = new \Common\Model\RealNameModel();
        //$realNameMsg = $RealName->where("real_id=$id")->find();
        $res=$RealName->where("real_id=$id")->delete();
        if($res)
        {
            //记录日志
            //日志内容
            /*$log_content='删除实名认证信息，编号：'.$id;
            $OrderLog=new \Common\Model\OrderLogModel();
            $res_log=$OrderLog->addLog($id, $log_content, 'del');*/
            echo '1';
        }else {
            echo '0';
        }
    }

    public function LiveRoomSet()
    {
        if ($_POST) {
            layout(false);

            $model_setting  = new \Common\Model\SettingModel();
            $file           = "./Public/inc/config.php";

            // 提交的key
            $post_key       = ['live_hint_user_auth','live_user_number_auth'];

            // 保存
            foreach ($post_key as $val) {
                $model_setting->set(strtoupper($val), I('post.'. $val), $file);
            }

            $this->cacheSetting($file);

            $this->ajaxSuccess();

        } else {
            $msg['live_hint_user_auth']  = defined('LIVE_HINT_USER_AUTH') ? LIVE_HINT_USER_AUTH : 1;
            $msg['live_user_number_auth']  = defined('LIVE_USER_NUMBER_AUTH') ? LIVE_USER_NUMBER_AUTH : 1;

            $this->assign('msg', $msg);

            $this->display();
        }
    }
}