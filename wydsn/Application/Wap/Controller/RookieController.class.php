<?php
/**
 * by 翠花 http://livedd.com
 * 拉新活动
 */
namespace Wap\Controller;
use Think\Controller;
class RookieController extends Controller
{
    public function index($uid='')
    {
        layout(false);
        $this->assign('uid',$uid);
        
        //获取当前最新的拉新活动
        $Rookie=new \Common\Model\RookieModel();
        $msg=$Rookie->getLastRookie();
        if($msg){
            $msg['end_time']=str_replace('-','/',$msg['end_time']);
            $this->assign('msg',$msg);
            $this->assign('web_title',$msg['name']);
            $this->assign('web_keywords','');
            $this->assign('web_description','');
            
            //计算倒计时总秒数量
            $second=strtotime($msg['end_time'])-time();
            $this->assign('second',$second);

            $people_num=0;
            $reward_money=0;
            $reward_unit='元';
            if($uid){
                //活动开始结束时间
                $start_time=$msg['start_time'];
                $end_time=$msg['end_time'];
                $User=new \Common\Model\UserModel();
                //统计邀请人数
                $people_num=$User->where("register_time between '$start_time' and '$end_time' and referrer_id=$uid")->count();
                
                //统计应该获取的奖励
                $RookieDetails=new \Common\Model\RookieDetailsModel();
                $reward=$RookieDetails->getReward($msg['id'], $people_num);
                $reward_money=$reward['reward_allnum'];
                $reward_unit=$reward['unit'];
            }
            $this->assign('people_num',$people_num);
            $this->assign('reward_money',$reward_money);
            $this->assign('reward_unit',$reward_unit);
            
            //获取拉新活动对应的奖励设置
            $rewardList=$RookieDetails->getRewardSet($msg['id']);
            $this->assign('rewardList',$rewardList);
            
            $this->display();
        }else {
            //暂无拉新活动
            $this->display('Rookie/fail');
        }
    }
    
    //拉新成员列表
    public function teamList($rid,$uid='')
    {
        layout(false);
        if($uid){
            $this->assign('uid',$uid);
            $this->assign('rid',$rid);
            //获取当前最新的拉新活动
            $Rookie=new \Common\Model\RookieModel();
            $msg=$Rookie->getLastRookie();
            if($msg){
                $this->assign('msg',$msg);
                
                $this->assign('web_title',$msg['name'].'-拉新成员列表');
                $this->assign('web_keywords','');
                $this->assign('web_description','');
                
                //获取活动兑现日期
                $start_m=date('m',strtotime($msg['exs_time']));
                $this->assign('start_m',$start_m);
                $start_d=date('d',strtotime($msg['exs_time']));
                $this->assign('start_d',$start_d);
                $end_m=date('m',strtotime($msg['exe_time']));
                $this->assign('end_m',$end_m);
                $end_d=date('d',strtotime($msg['exe_time']));
                $this->assign('end_d',$end_d);
                
                //获取用户获取期间拉取用户列表
                $where="u.referrer_id=$uid";
                $start_time=$msg['start_time'];
                $end_time=$msg['end_time'];
                $where="u.referrer_id=$uid and u.register_time between '$start_time' and '$end_time'";
                $sql="select u.phone,u.register_time,d.avatar from __PREFIX__user u,__PREFIX__user_detail d where $where and u.uid=d.user_id order by u.uid asc";
                $userList=M()->query($sql);
                $this->assign('userList',$userList);
                #计算奖励
                $rookieDetailModel = new \Common\Model\RookieDetailsModel();
                $setting = $rookieDetailModel->where('start_interval <='.count($userList).' and end_interval >='.count($userList))->find();
                $ref = $setting['reward_num']*count($userList);
                $this->assign('ref',$ref);
                $rookieUserModel = new \Common\Model\RookieUserModel();
                #看一下改次活动的奖励是否已经发放了
                $rookieUserInfo =$rookieUserModel->where(['rid'=>$rid,'user_id'=>$uid,'is_ex'=>'Y'])->find();
                $this->assign('is_change',!empty($rookieUserInfo)?1:0);

                $this->display();
            }else {
                $this->error('活动不存在！');
            }
        }else {
            //未登录
            redirect('dmooo://toLogin');
        }
    }

    public function exChangePorift($uid='',$rid='')
    {
        layout(false);
        $this->assign('uid',$uid);

        if($uid)
        {
            //获取当前最新的拉新活动
            $Rookie=new \Common\Model\RookieModel();
            $rookieUserModel = new \Common\Model\RookieUserModel();
            #看一下改次活动的奖励是否已经发放了
            $rookieUserInfo =$rookieUserModel->where(['rid'=>$rid,'user_id'=>$uid,'is_ex'=>'Y'])->find();
            if($rookieUserInfo)
            {
                echo json_encode(['msg'=>'奖励已发放，请注意查看余额','code'=>0]);
                exit;
            }

            $msg=$Rookie->getLastRookie();
            if($msg){
                if(strtotime($msg['exe_time']) <time() || strtotime($msg['exs_time']) >time())
                {
                    echo json_encode(['msg'=>'请在兑换时间内进行兑换','code'=>0]);
                    exit;
                }

                $res = $Rookie->exChangeUserProift($uid,$rid,$msg);
                if($res === false)
                {
                    echo json_encode(['msg'=>'兑换失败','code'=>0]);
                    exit;
                }else{
                    echo json_encode(['msg'=>'兑换成功','code'=>1,'data'=>$res['ref']]);
                    exit;
                }
            }
        }

    }
}