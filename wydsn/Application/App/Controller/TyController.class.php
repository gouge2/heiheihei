<?php
/**
 * by 来鹿 http://www.lailu.shop
 * app团油接口管理
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class TyController extends AuthController
{
    /**
     * 用户团油接口获取链接
     * @param string $token:用户身份令牌
     * @param string $phone:用户手机号
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     */
    public function getTyLink(){
        if (trim(I('post.token')) and trim(I('post.phone'))){
            //判断用户身份
            $token=trim(I('post.token'));
            $User=new \Common\Model\UserModel();
            $res_token=$User->checkToken($token);
            if($res_token['code']!=0) {
                //用户身份不合法
                $res=$res_token;
            }else {
                $phone=trim(I('post.phone'));
                //判断团油环境类型，选择不同地址，1测试 2正式
                if (TY_TYPE==1){
                    $tk_link='https://test-open.czb365.com/redirection/todo/?platformType='.TY_CHANNEL_CODING.'&authCode=';
                }else{
                    $tk_link='https://open.czb365.com/redirection/todo?platformType='.TY_CHANNEL_CODING.'&authCode=';
                }
                Vendor('tuanyou.tuanYou','','.class.php');
                $TuanYou=new \tuanYou();
                $result=$TuanYou->getSecretCode($phone);
                $res=array(
                    'code'=>$result['code'],
                    'msg'=>$result['message'],
                );
                if ($result['code']==200){
                    $data=array(
                        'ty_link'=>$tk_link.$result['result'],
                    );
                    $res=array(
                        'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                        'msg'=>'成功',
                        'data'=>$data
                    );
                }

            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}
?>