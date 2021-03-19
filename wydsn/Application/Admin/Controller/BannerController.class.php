<?php
/**
 * by 翠花 http://www.lailu.shop
 * 其他管理-Banner/广告管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BannerController extends AuthController
{
    public $advertSource = array(
        ['id' => "1" ,'name'=> '--网页--','selected' => false],
        ['id' => "2" ,'name'=> '--淘宝--','selected' => false],
        ['id' => "3" ,'name'=> '--京东--','selected' => false],
        ['id' => "4" ,'name'=> '--拼多多--','selected' => false],
        ['id' => "25" ,'name'=> '--自营商城--','selected' => false],
        ['id' => "5" ,'name'=> '--支付宝--','selected' => false],
        ['id' => "6" ,'name'=> '--淘宝年货节--','selected' => false],
        ['id' => "7" ,'name'=> '--春节红包--','selected' => false],
        ['id' => "8" ,'name'=> '--新人红包--','selected' => false],
        ['id' => "9" ,'name'=> '--淘宝商品--','selected' => false],
        ['id' => "10",'name'=> '--拉新活动--','selected' => false],
        ['id' => "11",'name'=> '--新人0元购--','selected' => false],
        ['id' => "12",'name'=> '--新人专区背景图--','selected' => false],
        ['id' => "13",'name'=> '--新手教程--','selected' => false],
        ['id' => "14",'name'=> '--分享淘口令--','selected' => false],
        ['id' => "15",'name'=> '--限时1元秒杀--','selected' => false],
        ['id' => "16",'name'=> '--聚划算榜单--','selected' => false],
        ['id' => "17",'name'=> '--超级券--','selected' => false],
        ['id' => "18",'name'=> '--达人说--','selected' => false],
        ['id' => "19",'name'=> '--必买清单--','selected' => false],
        ['id' => "20",'name'=> '--9.9元购--','selected' => false],
        ['id' => "21",'name'=> '--限时秒杀--','selected' => false],
        ['id' => "22",'name'=> '--拼多多--','selected' => false],
        ['id' => "23",'name'=> '--今日爆款--','selected' => false],
        ['id' => "24",'name'=> '--京东大促--','selected' => false],
    );
    
    public function index($cat_id)
    {
        $this->assign('cat_id', $cat_id);
        //获取分类信息
        $BannerCat = new \Common\Model\BannerCatModel();
        $catMsg = $BannerCat->getCatMsg($cat_id);
        $this->assign('cat_title', $catMsg['title']);
        //根据分类ID获取链接列表
        $hlist = s($cat_id);
        if (empty($hlist)) {
            $Banner = new \Common\Model\BannerModel();
            $res = $Banner->getBannerList($cat_id, '', 0, '*', false);

            $res = json_encode($res,JSON_UNESCAPED_UNICODE);

            S($cat_id,$res,1200);
            $hlist = $res;
        }

        // 小程序修改的页面 标识
        $applet = in_array($cat_id, [32, 33, 34, 35]) ? true : false;

        $this->assign('hlist', json_decode($hlist,true));
        $this->assign('applet', $applet);
        $this->display();
    }
    
    //添加banner/广告图
    public function add($cat_id)
    {
    	$this->assign('cat_id',$cat_id);
    	//获取分类信息
    	$BannerCat=new \Common\Model\BannerCatModel();
    	$catMsg=$BannerCat->getCatMsg($cat_id);
    	
    	if($_POST) {
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['img']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    				$img=substr($filepath,1);
    			}
    		}
    		if (trim(I('post.type'))=='1'){
    		    if (!trim(I('post.href'))){
                    $this->error('类型为网页超链接必填！');
                }
            }
            if (trim(I('post.type'))=='9'){
                if (!trim(I('post.type_value'))){
                    $this->error('类型为淘宝商品类型值商品ID必填！');
                }
            }
    		//保存到数据库
    		$data=array(
    			'cat_id'=>$cat_id,
    			'title'=>trim(I('post.title')),
    			'href'=>trim(I('post.href')),
    			'sort'=>trim(I('post.sort')),
    			'img'=>$img,
    		    'color'=>trim(I('post.color')),
    			'is_show'=>trim(I('post.is_show')),
    			'type'=>trim(I('post.type')),
    			'type_value'=>trim(I('post.type_value')),
    			'createtime'=>date('Y-m-d H:i:s'),
    		);
    		$Banner=new \Common\Model\BannerModel();
    		if(!$Banner->create($data)) {
    			// 验证不通过
    			// 删除图片
    			@unlink($filepath);
    			$this->error($Banner->getError());
    		}else {
    			// 验证成功
    			$res_add=$Banner->add($data);
    			if($res_add!==false) {
    			    // 清除缓存
                    S($cat_id,null);
    				$this->success('新增图片成功！',U('index',array('cat_id'=>$cat_id)));
    			}else {
    				//删除图片
    				@unlink($filepath);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
    	    $this->assign('cat_title',$catMsg['title']);
    	    
    		$this->display();
    	}
    }
    
    //编辑banner/广告图
    public function edit($id,$cat_id)
    {
    	$this->assign('id',$id);
    	$this->assign('cat_id',$cat_id);
    	//根据ID获取图片信息
    	$Banner=new \Common\Model\BannerModel();
    	$msg=$Banner->getBannerMsg($id);
    	
    	if($_POST) {
    		layout(false);
    		//上传文件
    		if(!empty($_FILES['img']['name']))
    		{
    			$config = array(
    					'mimes'         =>  array(), //允许上传的文件MiMe类型
    					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
    					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
    					'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
    					'savePath'      =>  '', //保存路径
    					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
    			);
    			$upload = new \Think\Upload($config);
    			// 上传单个文件
    			$info = $upload->uploadOne($_FILES['img']);
    			if(!$info) {
    				// 上传错误提示错误信息
    				$this->error($upload->getError());
    			}else{
    				// 上传成功
    				// 文件完成路径
    				$filepath=$config['rootPath'].$info['savepath'].$info['savename'];
    				$img=substr($filepath,1);
    			}
    		}else {
    			$img=$msg['img'];
    		}
    		//保存到数据库
    		$data=array(
    			'title'=>trim(I('post.title')),
    			'href'=>trim(I('post.href')),
    			'sort'=>trim(I('post.sort')),
    			'img'=>$img,
    		    'color'=>trim(I('post.color')),
    			'is_show'=>trim(I('post.is_show')),
    			'type'=>trim(I('post.type')),
    			'type_value'=>trim(I('post.type_value')),
    			'createtime'=>date('Y-m-d H:i:s'),
    		    'agent_id'=>0
    		);
            $post = I('post.');
            $model_setting  = new \Common\Model\SettingModel();
            $file           = "./Public/inc/banner.config.php";
            $model_setting->set("BANNER_{$id}", json_encode($post), $file);
            $this->cacheSetting($file);
    		if(!$Banner->create($data)) {
    			// 验证不通过
    			// 删除图片
    			@unlink($filepath);
    			$this->error($Banner->getError());
    		}else {
    			// 验证成功
    			$res_edit=$Banner->where("id=$id")->save($data);
    			if($res_edit!==false) {
    				// 修改成功
    				// 原图片存在，并且上传了新图片的情况下，删除原标题图片
    			    if($msg['img'] and $img!=$msg['img']) {
    				    $oldimg='.'.$msg['img'];
    					@unlink($oldimg);
    				}
    			    S($cat_id,null);
    				$this->success('修改图片成功！');
    			}else {
    				//删除图片
    				@unlink($filepath);
    				$this->error('操作失败！');
    			}
    		}
    	}else {
            $advertSource = $this->advertSource;
            // 可变常量提取存入信息
            if (file_exists('./Public/inc/banner.config.php')) require_once './Public/inc/banner.config.php';
            $bannData = defined("BANNER_{$id}") ? json_decode(get_defined_constants()["BANNER_{$id}"],true) : '';
            $msg = $bannData ? $bannData : $msg;
            $this->assign('msg',$msg);
            $this->assign('advertSource', $advertSource);
    	    
    		$this->display();
    	}
	}
	
	 // 编辑小程序banner/广告图
	 public function appletEdit($id,$cat_id)
	 {
		// 根据ID获取图片信息
		$Banner	= new \Common\Model\BannerModel();
		$msg 	= $Banner->getBannerMsg($id);

		if ($msg && $msg['text']) {
			$temp = json_decode($msg['text'], true);
			unset($msg['text']);
			$msg  = array_merge($msg, $temp);
		}

		if ($_POST) {
			$pattern  	= trim(I('post.pattern'));

			if ($pattern && in_array($pattern, ['goods', 'activity'])) {
				$config = [
					'mimes'         =>  array(), //允许上传的文件MiMe类型
					'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
					'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
					'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
					'savePath'      =>  '', //保存路径
					'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
				];
				$upload 	= new \Think\Upload($config);

				// 主图
				if ($_FILES['img']['name']) {
					// 上传单个文件
					$info = $upload->uploadOne($_FILES['img']);
					if (!$info) {
						// 上传错误提示错误信息
						$this->error($upload->getError());
					} else {
						// 上传成功  文件完成路径
						$img = substr($config['rootPath'] . $info['savepath'] . $info['savename'], 1);
					}
				} else {
					$img     = isset($msg['img']) ? $msg['img'] : '';
				}
				
				// 分享图
				if ($_FILES['share_img']['name']) {
					// 上传单个文件
					$info1 = $upload->uploadOne($_FILES['share_img']);
					if (!$info1) {
						// 上传错误提示错误信息
						$this->error($upload->getError());
					} else {
						// 上传成功  文件完成路径
						$share_img = substr($config['rootPath'] . $info1['savepath'] . $info1['savename'], 1);
					}
				} else {
					$share_img  = isset($msg['a_det']['share_img']) ? $msg['a_det']['share_img'] : '';
				}

				// 模式数据
				$text       					= ['pattern' => $pattern];
				if ($pattern == 'goods') {
					$text['g_det'] 				= I('post.g_det');
				} elseif ($pattern == 'activity') {
					$text['a_det'] 				= I('post.a_det');
					$text['a_det']['share_img'] = $share_img ? $share_img : '';
				}
				
				//保存到数据库
				$data = [
					'title' 	=> trim(I('post.title')),
					'sort'  	=> I('post.sort/d', 0),
					'img'   	=> $img,
					'is_show' 	=> trim(I('post.is_show')),
					'color' 	=> trim(I('post.color')),
					'text' 		=> json_encode($text),
				];

				// 添加或者修改
				if ($id) {
					$res_edit = $Banner->where(['id' => $id])->save($data);
				} else {
					$data['cat_id'] 	= $cat_id;
					$data['createtime'] = date('Y-m-d H:i:s');
					$res_edit = $Banner->add($data);
				}
                $post = I('post.');
                $model_setting  = new \Common\Model\SettingModel();
                $file           = "./Public/inc/banner.config.php";
                $model_setting->set("BANNER_{$id}", json_encode($post), $file);
                $this->cacheSetting($file);
                
				// 删除图片
				if ($res_edit !== false) {
					// 修改成功 原图片存在，并且上传了新图片的情况下，删除原标题图片
					if ($msg['img'] && $img != $msg['img']) {
						@unlink('.'. $msg['img']);
					}

					if ($msg['a_det']['share_img'] && $msg['a_det']['share_img'] != $share_img) {
						@unlink('.'. $msg['a_det']['share_img']);
					}

					$this->success('修改图片成功！');
				} else {
					//删除图片
					@unlink('.'. $img);
					@unlink('.'. $share_img);

					$this->error('操作失败！');
				}
                S($cat_id,null);

			} else {
				$this->error('请选择模式');
			}
			
		} else {
            $advertSource = $this->advertSource;
            unset($advertSource[1],$advertSource[6],$advertSource[9]);
            array_merge($advertSource);
            // 可变常量提取存入信息
            if (file_exists('./Public/inc/banner.config.php')) require_once './Public/inc/banner.config.php';
            $bannData = defined("BANNER_{$id}") ? json_decode(get_defined_constants()["BANNER_{$id}"],true) : '';
            $msg = $bannData ? $bannData : $msg;
            $this->assign('id',$id);
            $this->assign('cat_id',$cat_id);
            $this->assign('msg', $msg);
            $this->assign('from_sel', $advertSource);
			 
			$this->display();
		}
	}

	// 小程序广告位预览
	public function appletPreview()
	{
		$id 	= I('id/d', 0);

		$data   = ['pattern'=> '', 'main_img' => '', 'title' => '', 'g_det' => null, 'a_det' => null];

		if ($id) {
			$ShortLiveGoods 	= new \Common\Model\ShortLiveGoodsModel();
			$Banner 			= new \Common\Model\BannerModel();
			$msg				= $Banner->getBannerMsg($id);

			if ($msg && $msg['text']) {
				$temp 							= json_decode($msg['text'], true);
				unset($msg['text']);
				$msg  							= array_merge($msg, $temp);

				// 处理数据
				$data['pattern']				= $msg['pattern'];
				$data['title']					= $msg['title'];
				$data['main_img']				= $msg['img'] ? WEB_URL . $msg['img'] : '';

				// 模式数据
				if ($msg['pattern'] == 'goods') {
					$data['g_det'] 				= $msg['g_det'];
					unset($data['g_det'] ['goods_arr']);
					unset($data['g_det'] ['from']);
					$goods_arr 					= explode('，', $msg['g_det']['goods_arr']);

					if ($goods_arr) {
						$list   				= [];
						$gls  					= ['id' => 0, 'from' => $msg['g_det'] ['from'], 'short_id' => 0, 'site_id' => 0, 'user_id' => 0, 'is_explain' => 'not'];
						foreach ($goods_arr as $val) {
							$list[]				= array_merge(['goods_id' => $val], $gls);
						}

						// 获取商品数据列表
						$goods_list  			= $ShortLiveGoods->getGoodsData('package', [], 0, 10, 1, '', 'id desc', $list);

					} else {
						$goods_list  			= [];
					}

					$data['g_det']['goods_list']= $goods_list;

				} else {
					$data['a_det']['introduce'] = htmlspecialchars_decode(html_entity_decode($msg['a_det']['introduce']));
					$data['a_det']['copywriter']= htmlspecialchars_decode(html_entity_decode($msg['a_det']['copywriter']));
					$data['a_det']['share_img'] = $msg['a_det']['share_img'] ? WEB_URL . $msg['a_det']['share_img'] : '';
				}
			}
		}

		$data['g_det'] 				= json_encode($data['g_det']);

		$this->assign('data', $data);

		$this->display();
	}
    
    //删除banner/广告图
    public function del($id)
    {
        $cat_id = I('post.cat_id');
    	$Banner=new \Common\Model\BannerModel();
    	$msg=$Banner->getBannerMsg($id);
    	$res_del=$Banner->where("id=$id")->delete();
    	if($res_del!==false) {
    		//删除图片
    	    if(!empty($msg['img'])) {
    	        $img='.'.$msg['img'];
    			@unlink($img);
    		}
            S($cat_id,null);
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量删除banner/广告图
    public function batchdel($all_id)
    {
        $cat_id = I('post.cat_id');
    	$all_id=substr($all_id,0,-1);
    	$id_arr=explode(',',$all_id);
    	$num=count($id_arr);
    	$Banner=new \Common\Model\BannerModel();
    	for($i=0;$i<$num;$i++)
    	{
    		$id=$id_arr[$i];
    		$res1=$Banner->getBannerMsg($id);
    		$img=$res1['img'];
    		$res=$Banner->where("id=$id")->delete();
    		if($res)
    		{
    			//删除图片
    			if(!empty($img))
    			{
    				$img='.'.$img;
    				unlink($img);
    			}
    			$a.='a';
    		}
    	}
    	$a.='true';
    	$str=str_repeat('a',$num).'true';
    	if($str==$a)
    	{
            if ($cat_id) {
                S($cat_id,null);
            }
    		echo '1';
    	}else {
    		echo '0';
    	}
    }
    
    //批量修改排序
    public function changesort()
    {
        $cat_id = I('get.cat_id');
    	$sort_array=I('post.sort');
    	$ids = implode(',', array_keys($sort_array));
    	$sql = "UPDATE __PREFIX__banner SET sort = CASE id ";
    	foreach ($sort_array as $id => $sort) {
    		$sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
    	}
    	$sql.= "END WHERE id IN ($ids)";
    	$res = M()->execute($sql);
    	layout(false);
    	if($res===false)
    	{
    		$this->error('操作失败!');
    	}else {
            if ($cat_id) {
                S($cat_id,null);
            }
    		$this->success('排序成功!');
    	}
    }
    
    //修改显示状态
    public function changeshow($id,$status)
    {
        $cat_id = I('post.cat_id');
        $data = array(
            'is_show' => $status
        );
        $Banner = new \Common\Model\BannerModel();
        if (!$Banner->create($data)) {
            // 验证不通过
            echo '0';
        } else {
            // 验证成功
            $res = $Banner->where("id=$id")->save($data);
            if ($res === false) {
                echo '0';
            } else {
                if ($cat_id) {
                    $list = s($cat_id);
                    $list = json_decode($list,true);
                    foreach ($list as $key => $value) {
                        if ($value['id'] == $id) {
                            $list[$key]['is_show'] = $status;
                        }
                    }
                    $list = json_encode($list,JSON_UNESCAPED_UNICODE);
                    S($cat_id,$list);
                }

                echo '1';
            }
        }
    }
}
?>
