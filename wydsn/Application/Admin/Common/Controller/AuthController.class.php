<?php

/*
 * 
 * @thinkphp3.2.2  auth认证   php5.3以上
 * @如果需要公共控制器，就不要继承AuthController，直接继承Controller
 */
namespace Admin\Common\Controller;
use Think\Controller;
use Think\Auth;

//权限认证
class AuthController extends Controller 
{
	public static $limit   	     		= 30;      		// 每页显示条数
	public static $page    		 		= 1;       		// 当前页

	protected function _initialize()
	{
	   
		//session不存在时，不允许直接访问
		if (!$_SESSION['admin_id']) {
			layout(false);
			$this->error('还没有登录，正在跳转到登录页',U('Index/index'));
		}
		
		//session存在时，不需要验证的权限
		$not_check = array(
		    'Admin/changepwd','System/index','System/index_show','System/cleancache','System/clearrubbish',//修改密码、系统首页、清理缓存、清理垃圾文件
			'ArticleCat/deloldimg','Article/deloldimg','Article/deloldbigimg','Article/deloldfile',//删除文章分类原图片、删除文章原图片、删除文章原大图片、删除文章原文件
			'GoodsCat/deloldimg',//删除商品分类原图片
		);
		
		//当前操作的请求                 模块名/方法名
		if (in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $not_check)) {
			return true;
		}

		//授权检测
		if (!isset($_COOKIE['authcode']) || $_COOKIE['authcode'] != '4c723eb9fc') {
            $hosts = $_SERVER['HTTP_HOST'].'|'.$_SERVER['SERVER_NAME'];
//            $ckret = file_get_contents('http://safe.taokeyun.cn/check.php?a=index&appsign=25_200324163817929_bda981b8_66935536ea9f988d72f235a132ad1d26&h='.urlencode($hosts).'&t='.$_SERVER['REQUEST_TIME'].'&token='.md5($_SERVER['REQUEST_TIME'].'|'.$hosts.'|xzphp|4c723eb9fc'));
//            if ($ckret) {
//                $ckret = json_decode($ckret, true);
//                if ($ckret['status'] != 1) {
//                    exit($ckret['msg']);
//                } else {
                    setcookie('authcode', '4c723eb9fc', time()+86400,'/'); //
//                    unset($hosts,$ckret);
//                }
//            } else {
//                exit('您还未授权，无法使用。');
//            }
        }
		
		$auth = new Auth();

		if (!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME,$_SESSION['admin_id']) and $_SESSION['a_group_id']!='1') {
			layout(false);
			echo '没有权限!';die();
		}
	}

    /**
     * 返回成功信息
     * @param array $data
     */
	protected function ajaxSuccess($data = [])
	{
        $res = [
            'code'	=> 'succ',
            'msg'	=> '操作成功',
            'data'	=> $data,
		];

        echo json_encode ($res,JSON_UNESCAPED_UNICODE); exit();
	}
	
	/**
     * 返回成功信息
     * @param array $data
     */
	protected function ajaxError($msg = '')
	{
        $res = [
            'code'	=> 'fail',
            'msg'	=>  $msg ? $msg : '缺少参数',
		];

        echo json_encode ($res,JSON_UNESCAPED_UNICODE); exit();
	}
	
	/**
     * 缓存配置
     * @param string $file
     */
    public function cacheSetting($file = ''){
        $where = ['cache' => $file];
        $model = new \Common\Model\SettingModel();
        $list = $model->where($where)->select();
        $special_key = [
            'dtk_appkey' => 'DTK_APP_KEY',
            'dtk_appsecret' => 'DTK_APP_SECRET',
            'tb_pid' => 'tb_pid',
            'to_update' => 'to_update',
            'to_update_ios' => 'to_update_ios'
        ];
        $str = '<?php'.PHP_EOL;
        foreach ($list as $item){
            $value = $item['value'];
            $key = $item['key'];
            if(isset($special_key[$key])){
                $key_up = $special_key[$key];
            }else{
                $key_up = strtoupper($key);
            }

            $str .= "define('$key_up','$value');".PHP_EOL;
        }
        file_put_contents($file, $str);
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

    //清理垃圾文件
    public function clearrubbish()
    {
        $dirName='./Public/Upload/ueditor_temp';
        delDirAndFile($dirName);
        layout(false);
        $this->success('清理垃圾文件成功！');
    }
}