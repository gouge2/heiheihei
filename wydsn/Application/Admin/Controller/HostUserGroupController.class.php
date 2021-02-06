<?php
/**
 * 主播带货分佣管理
 */

namespace Admin\Controller;
use Admin\Common\Controller\AuthController;

class HostUserGroupController extends AuthController
{
    public function index() {
        // 获取基础设置内容
        $HostCommissionModel = new \Common\Model\HostCommissionModel();
        $hostCommission = $HostCommissionModel->getGroupMsg();
        $HostUserGroupModel = new \Common\Model\HostUserGroupModel();
        $hostUserGroup = $HostUserGroupModel->getGroupList();

        $this->assign('hostCommission', $hostCommission);
        $this->assign('glist', $hostUserGroup);

        $this->display();
    }

    // 更新主播分佣基本配置
    public function update() {
        if (I('post.')) {

            $id = 1;
            $data = array();
            if (trim(I('post.fee_service'))) {
                $data['fee_service'] = trim(I('post.fee_service'));
            }
            if (trim(I('post.fee_plantform'))) {
                $data['fee_plantform'] = trim(I('post.fee_plantform'));
            }
            if (trim(I('post.fee_sell'))) {
                $data['fee_sell'] = trim(I('post.fee_sell'));
            }

            if (trim(I('post.broker_rate'))) {
                $data['broker_rate'] = trim(I('post.broker_rate'));
            }
            if (trim(I('post.broker_rate2'))) {
                $data['broker_rate2'] = trim(I('post.broker_rate2'));
            }
            if (trim(I('post.fee_host'))) {
                $data['fee_host'] = trim(I('post.fee_host'));
            }
            if (trim(I('post.fee_user'))) {
                $data['fee_user'] = trim(I('post.fee_user'));
            }

            if (!empty($data)) {
                $HostCommissionModel = new \Common\Model\HostCommissionModel();
                $res = $HostCommissionModel->where("id=$id")->save($data);

                if ($res!==false)
                {
                    $this->success('修改成功！',U('index'));
                }else {
                    $this->error('操作失败！',U('index'));
                }
            }
        }
    }

    // 删除会员组
    public function del($id) {
        //先判断会员组下是否存在会员，存在不允许删除
        $User=new \Common\Model\UserModel();
        $user_num=$User->where("group_id='$id'")->count();
        if($user_num>0)
        {
            echo '2';
        }else {
            //进行删除操作
            $HostUserGroupModel=new \Common\Model\HostUserGroupModel();
            $res=$HostUserGroupModel->where("id='$id'")->delete();
            if($res!==false)
            {
                echo '1';
            }else {
                echo '0';
            }
        }
    }

    //编辑会员组
    public function edit($group_id)
    {
        //获取用户组信息
        $HostUserGroupModel = new \Common\Model\HostUserGroupModel();
        $gMsg=$HostUserGroupModel->getGroupMsg($group_id);
        $this->assign('msg',$gMsg);
        if(I('post.'))
        {
            layout(false);
            //判断用户组是否重复
            $title=trim(I('post.title'));
            $res_exist=$HostUserGroupModel->where("title='$title' and id!='$group_id'")->find();
            if($res_exist)
            {
                $this->error('该会员组名已存在，不准重复！');
            }else {
                $data=array(
                    'title'=>trim(I('post.title')),
                    'exp'=>trim(I('post.exp')),
                    'discount'=>trim(I('post.discount')),
                    'introduce'=>trim(I('post.introduce')),
                    'is_freeze'=>I('post.is_freeze'),
                    'fee_user'=>trim(I('post.fee_user')),
                    /*'fee_service'=>trim(I('post.fee_service')),
                    'fee_plantform'=>trim(I('post.fee_plantform')),*/
                    'self_rate'=>trim(I('post.self_rate')),
                    'referrer_rate'=>trim(I('post.referrer_rate')),
                    'referrer_rate2'=>trim(I('post.referrer_rate2')),
                    'team_rate'=>trim(I('post.team_rate')),
                    'team_rate2'=>trim(I('post.team_rate2'))
                );
                if(!$HostUserGroupModel->create($data))
                {
                    // 如果创建失败 表示验证没有通过 输出错误提示信息
                    $this->error($HostUserGroupModel->getError());
                }else {
                    // 验证成功
                    $res=$HostUserGroupModel->where("id='$group_id'")->save($data);
                    if($res===false)
                    {
                        $this->error('操作失败!');
                    }else {
                        $this->success('编辑成功!',U('index'));
                    }
                }
            }
        }else {
            $this->display();
        }
    }
}