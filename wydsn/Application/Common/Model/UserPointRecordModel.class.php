<?php
/**
 * by 翠花 http://livedd.com
 * 用户积分变更记录管理
 * 变更情况：
 * ①用户支付消费时增加，积分增加数量和金额相等
 * ②使用余额支付商品时增加，积分增加数量和金额相等
 * ③购买积分商品时扣除积分
 */
namespace Common\Model;
use Think\Model;

class UserPointRecordModel extends Model
{
	//验证规则
	protected $_validate =array(
			array('user_id','require','会员不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('user_id','is_positive_int','会员不存在',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正整数
			array('point','require','积分数不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			//array('point','is_natural_num','积分数必须为大于零的整数！',self::EXISTS_VALIDATE,'function'),  //存在验证 ，必须是自然数	
			//array('all_point','is_natural_num','积分存量必须为大于零的整数！',self::VALUE_VALIDATE,'function'),  //值不为空的时候验证，必须是自然数
			array('create_time','require','时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
			array('create_time','is_datetime','时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
			//array('action',array('register','recommend_register','signin','buy','buy_balance','del','complete_info'),'操作类型只能为消费增加和使用减少！',self::VALUE_VALIDATE,'in'),  //存在验证，只能是buy线上支付增加 buy_balance余额支付增加  del	使用减少
	);
	
	/**
	 * 添加积分变动记录
	 * @param int $user_id:用户ID
	 * @param int $point:变动积分数
	 * @param int $all_point:积分存量
	 * @param string $action:操作类型
	 * @return boolean
	 */
	public function addLog($user_id,$point,$all_point=0,$action)
	{
		$data=array(
				'user_id'=>$user_id,
				'point'=>$point,
				'all_point'=>$all_point,
				'create_time'=>date('Y-m-d H:i:s'),
				'action'=>$action
		);
		if(!$this->create($data))
		{
			return false;
		}else {
			$res=$this->add($data);
			if($res!==false)
			{
				return true;
			}else {
				return false;
			}
		}
	}
	
	/**
	 * 获取操作类型描述
	 * @param string $action:操作类型
	 * @return array
	 */
	public function getActionZh($action)
	{
	    switch ($action) {
	        case 'register':
	            $action_zh='注册获取';
	            $action_symbol='+';
	            break;
	        case 'recommend_register':
	            $action_zh='推荐注册获取';
	            $action_symbol='+';
	            break;
	        case 'signin':
	            $action_zh='签到获取';
	            $action_symbol='+';
	            break;
	        case 'buy':
	            $action_zh='购买商品获取';
	            $action_symbol='+';
	            break;
	        case 'complete_info':
	            $action_zh='完善资料获取';
	            $action_symbol='+';
	            break;
	        case 'buy_d':
	            $action_zh='购买商品抵扣';
	            $action_symbol='-';
	            break;
	        case 'buy_refund':
	            $action_zh='购买商品退款';
	            $action_symbol='+';
	            break;
	        default:
	            $action_zh='';
	            $action_symbol='';
	            break;
	    }
	    return array(
	        'action_zh'=>$action_zh,
	        'action_symbol'=>$action_symbol
	    );
	}
	
	/**
	 * 获取会员积分变动记录
	 * @param int $user_id:会员ID
	 * @return array|boolean
	 */
	public function getRecordList($user_id)
	{
		$list=$this->where("user_id='$user_id'")->select();
		if($list!==false) {
			$num=count($list);
			for($i=0;$i<$num;$i++) {
			    $list[$i]['point']*=1;
			    $list[$i]['all_point']*=1;
			    //获取操作类型描述
			    $res_action=$this->getActionZh($list[$i]['action']);
			    $list[$i]['action_zh']=$res_action['action_zh'];
			    $list[$i]['action_symbol']=$res_action['action_symbol'];
			}
			return $list;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取分页会员积分变动记录
	 * @param int $user_id:会员ID
	 * @param string $order:排序规则 desc降序 asc升序
	 * @param int $p:当前页码，默认1
	 * @param int $per:每页显示条数，默认15
	 * @return array|boolean
	 * @return array $list:会员积分变动记录
	 * @return array $page:分页条
	 */
	public function getRecordListByPage($user_id,$order='desc',$p=1,$per=15)
	{
		//列表
		$where="user_id='$user_id'";
		$list = $this->where($where)->page($p.','.$per)->order("id $order")->select();
		if($list!==false) {
			$num=count($list);
			for($i=0;$i<$num;$i++) {
			    $list[$i]['point']*=1;
			    $list[$i]['all_point']*=1;
			    //获取操作类型描述
			    $res_action=$this->getActionZh($list[$i]['action']);
			    $list[$i]['action_zh']=$res_action['action_zh'];
			    $list[$i]['action_symbol']=$res_action['action_symbol'];
			}
			//总数
			$count=$this->where($where)->count();
			//分页
			$Page=new \Common\Model\PageModel();
			$show=$Page->show($count, $per);
			$content=array(
					'list'=>$list,
					'page'=>$show
			);
			return $content;
		}else {
			return false;
		}
	}

    /**
     * 购买自营商城用户抵扣积分转换
     * @param string $point:抵扣积分
     * @return string
     */
    public function deductionPointExchange($point)
    {
        //判断抵扣积分和积分金额比是几位小数
        $point_arr=explode('.',$point);
        $point_value_arr=explode('.',POINT_VALUE);
        //积分金额比有几位小数位
        if(isset($point_value_arr[1])){
            //积分金额比有一位小数位，抵扣积分最多只能到一位小数位，否则保留到一位
            if (strlen($point_value_arr[1])===1){
                if (isset($point_arr[1])){
                    if (strlen($point_arr[1])===2){
                        $point=substr($point,0,strlen($point)-1);
                    }
                }
            }
            //积分金额比有两位小数位，抵扣积分只能保留整数
            if (strlen($point_value_arr[1])===2){
                if (isset($point_arr[1])){
                    if (strlen($point_arr[1])===1){
                        $point=substr($point,0,strlen($point)-2);
                    }
                    if (strlen($point_arr[1])===2){
                        $point=substr($point,0,strlen($point)-3);
                    }
                }
            }
        }
        return $point;
    }
}