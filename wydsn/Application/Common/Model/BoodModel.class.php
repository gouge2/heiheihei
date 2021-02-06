<?php
namespace Common\Model;
use Think\Model;
class BoodModel extends Model {


    public function getOne($whe, $field = '*')
    {
        $res = null;

        if ($whe) {

            $res = $this->field($field)->where($whe)->find();
        }
        return $res;
    }

    /**
     * 处理已经付款的申请
     * @param $sn
     * @param $pay_method
     */
    public function treatOrder($sn,$pay_method)
    {
        $boodLogModel = new \Common\Model\BoodLogModel();
        $msg = $boodLogModel->where("log_sn ='{$sn}'")->find();

        if($msg)
        {
            if($msg['pay_status']=='1')
            {
                return false;
            }

            #如果存在的情况下,更新支付信息以及处理
            $bood = $this->getOne(['user_id'=>$msg['user_id']]);
            $money = empty($bood)?0:$bood['bood'];
            $this->startTrans();
            if($bood)
            {
                $res = $this->where('id='.$bood['id'])->save(['bood'=>$money+$msg['bood_money'],'update_time'=>date('Y-m-d H:i:s',time())]);
            }else{
                $res = $this->add(['user_id'=>$msg['user_id'],'bood'=>$msg['bood_money'],'create_time'=>date('Y-m-d H:i:s',time()),'update_time'=>date('Y-m-d H:i:s',time()),'bood_change'=>0]);
            }
            writeLog(json_encode(['sql'=>$this->getLastSql()]),'boodLog');
            if($res !== false)
            {
                $res_add = $boodLogModel->where('id='.$msg['id'])->save(['payment'=>$pay_method,'pay_time'=>date('Y-m-d H:i:s',time()),'pay_status'=>1]);
                writeLog(json_encode(['sql'=>$this->getLastSql()]),'boodLog1');
                if($res_add !== false)
                {
                    $this->commit();
                    return true;
                }else{
                    //验证不通过
                    //回滚
                    $this->rollback();
                    return false;
                }
            }else{
                //验证不通过
                //回滚
                $this->rollback();
                return false;
            }
        }
    }

    /**
     * 获取提现列表
     * @param string $is_show:是否显示 Y显示 N不显示
     * @return array|boolean
     */
    public function getBoodList($whe,$page, $limit)
    {
        $boodLogModel = new \Common\Model\BoodLogModel();

        $list = $boodLogModel->where($whe)->field('id,user_id,bood_money,payment,pay_time,pay_status,account_mobile,account_name')->limit($page,$limit)->order('id desc')->select();
        if($list !== false)
        {
            return $list;
        }else {
            return false;
        }
    }
}