<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 会员管理
 * 会员组管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class UserGroupController extends AuthController
{
    public function index()
    {
    	//获取用户组列表
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$glist=$UserGroup->getGroupList();
    	$this->assign('glist',$glist);
        $this->display();
    }
    
    //新增会员组
    public function add()
    {
    	if(I('post.'))
    	{
    		layout(false);
    		//判断用户组是否重复
    		$UserGroup=new \Common\Model\UserGroupModel();
    		$title=trim(I('post.title'));
    		$res_exist=$UserGroup->where("title='$title'")->find();
    		if($res_exist)
    		{
    			$this->error('该会员组名已存在，不准重复！');
    		}else {
    			$data=array(
    					'title'=>trim(I('post.title')),
    					'exp'=>trim(I('post.exp')),
    					'commission'=>trim(I('post.commission')),
    					'is_gift'=>trim(I('post.is_gift')),
    					'gift_referrer_tate'=>trim(I('post.gift_referrer_tate')),
    					'gift_referrer_tate2'=>trim(I('post.gift_referrer_tate2')),
    					'time_limit'=>trim(I('post.time_limit')),
    					'discount'=>trim(I('post.discount')),
    					'introduce'=>trim(I('post.introduce')),
    					'is_freeze'=>I('post.is_freeze'),
    					'createtime'=>date('Y-m-d H:i:s'),
    					'fee_user'=>trim(I('post.fee_user')),
    					'fee_user_virtual'=>trim(I('post.fee_user_virtual')),
    					'fee_service'=>trim(I('post.fee_service')),
    					'fee_plantform'=>trim(I('post.fee_plantform')),
    					'self_rate'=>trim(I('post.self_rate')),
    					'referrer_rate'=>trim(I('post.referrer_rate')),
    					'referrer_rate_virtual'=>trim(I('post.referrer_rate_virtual')),
    					'referrer_rate2'=>trim(I('post.referrer_rate2')),
    					'referrer_rate2_virtual'=>trim(I('post.referrer_rate2_virtual')),
    			);

                //上传等级图标icon
                if(!empty($_FILES['level_icon']['name'])) {
                    $config = array(
                        'mimes'         =>  array(), //允许上传的文件MiMe类型
                        'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                        'exts'          =>  array( 'png' ), //允许上传的文件后缀
                        'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                        'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                        'savePath'      =>  '', //保存路径
                        'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                        'replace'       =>  true, //存在同名是否覆盖
                        'saveName'      =>  'level_icon'.time(), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                    );
                    $upload = new \Think\Upload($config);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES['level_icon'],1);
                    if(!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{
                        // 上传成功
                        // 文件完成路径
                        $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                        $data['level_icon']=$filepath;
                    }
                }

    			if(!$UserGroup->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				$this->error($UserGroup->getError());
    			}else {
    				// 验证成功
    				$res=$UserGroup->add($data);
    				if ($res!==false)
    				{
    					$this->success('新增用户组成功！',U('index'));
    				}else {
    					$this->error('操作失败！');
    				}
    			}
    		}
    	}else {
    		$this->display();
    	}
    }

    //编辑会员组
    public function edit($group_id)
    {
    	//获取用户组信息
    	$UserGroup=new \Common\Model\UserGroupModel();
    	$gMsg=$UserGroup->getGroupMsg($group_id);
    	$this->assign('msg',$gMsg);
    	if(I('post.'))
    	{
    		layout(false);
    		//判断用户组是否重复
    		$title=trim(I('post.title'));
    		$res_exist=$UserGroup->where("title='$title' and id!='$group_id'")->find();
    		if($res_exist)
    		{
    			$this->error('该会员组名已存在，不准重复！');
    		}else {
    			$data=array(
    					'title'=>trim(I('post.title')),
    					'exp'=>trim(I('post.exp')),
                        'commission'=>trim(I('post.commission')),
                        'is_gift'=>trim(I('post.is_gift')),
                        'gift_referrer_tate'=>trim(I('post.gift_referrer_tate')),
                        'gift_referrer_tate2'=>trim(I('post.gift_referrer_tate2')),
                        'time_limit'=>trim(I('post.time_limit')),
    					'discount'=>trim(I('post.discount')),
    					'introduce'=>trim(I('post.introduce')),
    					'is_freeze'=>I('post.is_freeze'),
    					'fee_user'=>trim(I('post.fee_user')),
    					'fee_user_virtual'=>trim(I('post.fee_user_virtual')),
    					'fee_service'=>trim(I('post.fee_service')),
    					'fee_plantform'=>trim(I('post.fee_plantform')),
                        'self_rate'=>trim(I('post.self_rate')),
                        'referrer_rate'=>trim(I('post.referrer_rate')),
                        'referrer_rate_virtual'=>trim(I('post.referrer_rate_virtual')),
                        'referrer_rate2'=>trim(I('post.referrer_rate2')),
                        'referrer_rate2_virtual'=>trim(I('post.referrer_rate2_virtual')),
    			);

                //上传等级图标icon
                if(!empty($_FILES['level_icon']['name'])) {
                    $config = array(
                        'mimes'         =>  array(), //允许上传的文件MiMe类型
                        'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                        'exts'          =>  array( 'png' ), //允许上传的文件后缀
                        'subName'       =>  '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
                        'rootPath'      =>  './Public/static/admin/img/', //保存根路径
                        'savePath'      =>  '', //保存路径
                        'saveExt'       =>  'png', //文件保存后缀，空则使用原后缀
                        'replace'       =>  true, //存在同名是否覆盖
                        'saveName'      =>  'level_icon'.time(), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                    );
                    $upload = new \Think\Upload($config);
                    // 上传单个文件
                    $info = $upload->uploadOne($_FILES['level_icon'],1);
                    if(!$info) {
                        // 上传错误提示错误信息
                        $this->error($upload->getError());
                    }else{
                        // 上传成功
                        // 文件完成路径
                        $filepath=substr($config['rootPath'],1).$info['savepath'].$info['savename'];
                        $data['level_icon']=$filepath;
                    }
                }

    			if(!$UserGroup->create($data))
    			{
    				// 如果创建失败 表示验证没有通过 输出错误提示信息
    				$this->error($UserGroup->getError());
    			}else {
    				// 验证成功
    				$res=$UserGroup->where("id='$group_id'")->save($data);
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
    
    //修改分组状态
    public function changestatus($id,$status)
    {
    	$data=array(
    			'is_freeze'=>$status
    	);
    	$group=new \Common\Model\UserGroupModel();
    	if(!$group->create($data))
    	{
    		// 如果创建失败 表示验证没有通过 输出错误提示信息
    		// $this->error($group->getError());
    		echo '0';
    	}else {
    		// 验证成功
    		$res=$group->where("id=$id")->save($data);
    		if($res===false)
    		{
    			echo '0';
    		}else {
    			echo '1';
    		}
    	}
    }
    
    //删除会员组
    public function del($id)
    {
    	//先判断会员组下是否存在会员，存在不允许删除
    	$User=new \Common\Model\UserModel();
    	$user_num=$User->where("group_id='$id'")->count();
    	if($user_num>0)
    	{
    		echo '2';
    	}else {
    		//进行删除操作
    		$UserGroup=new \Common\Model\UserGroupModel();
    		$res=$UserGroup->where("id='$id'")->delete();
    		if($res!==false)
    		{
    			echo '1';
    		}else {
    			echo '0';
    		}
    	}
    }
}