<?php
/**
 * 举报管理类
 */
namespace App\Controller;

use App\Common\Controller\AuthController;

class ReportController extends AuthController
{
    /**
     * 获取举报分类
     */
    public function getCatlist()
    {
        $platform           = trim(I('post.platform'));        // 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

        if (IS_POST) {
            $ReportCat      = new \Common\Model\ReportCatModel();

            $list           = $ReportCat->getAllList();

            $this->ajaxSuccess(['list' => $list]);
        }

        $this->ajaxError();
    }

    /**
	 * 举报图片上传
	 */
	public function imagesUpload()
	{
		if (!empty($_FILES['file']['name'])) {
			$config = [
				'mimes'         =>  array(), //允许上传的文件MiMe类型
				'maxSize'       =>  1024*1024*20, //上传的文件大小限制 (0-不做限制)
				'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
				'rootPath'      =>  './Public/Upload/Report/', //保存根路径
				'savePath'      =>  '', //保存路径
				'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
			];

			$upload = new \Think\Upload($config);
			$info 	= $upload->upload(array($_FILES['file']));  // 上传单个文件

			if (!$info) {
				// 上传错误提示错误信息
				$this->ajaxError($this->ERROR_CODE_COMMON['FILE_UPLOAD_ERROR'], $upload->getError());
				exit();
			} else { 
				// 上传成功  文件完成路径
                foreach ($info as $val) {
                    $fp_mob     = $config['rootPath'] . $val['savepath'] . $val['savename'];
                    $img[]      = substr($fp_mob, 1);
                }

				// 缓存图片路径
                $name           = 'r'. $_SERVER['REQUEST_TIME'] . mt_rand(100, 9999999);
                S($name, json_encode($img));

                // 保存缓存以便删除多余的图片
                $img_arr 	= S('report_img_arr');
                $img_arr[] 	= json_encode($img);
                S('report_img_arr', $img_arr);
				
				$this->ajaxSuccess(['name' => $name]);
			}
		}

		$this->ajaxError();
	}

	/**
	 * 举报提交
	 */
	public function submit()
	{
		$cat_id           	= I('post.cat_id/d');
		$short_id           = I('post.short_id/d');
		$room_id           	= I('post.room_id/d');
		$cause              = trim(I('post.cause'));
		$photo           	= I('post.photo');
		$type               = trim(I('post.type'));				// 举报类型 short：视频  room:房间
		$platform           = trim(I('post.platform'));        	// 平台类型 ios：苹果端  android：安卓端  applet：微信小程序端

		$img_arr 		 	= S('report_img_arr');
		$img_arr 		 	= $img_arr ? $img_arr : [];

		if ($cat_id && (($type == 'short' && $short_id) || ($type == 'room' && $room_id))) {
			// 验证登录的token
			$this->verifyUserToken($token, $User, $res_token);

			$Report      	= new \Common\Model\ReportModel();
			$Short      	= new \Common\Model\ShortModel();
			$LiveRoom      	= new \Common\Model\LiveRoomModel();

			//// 短视频或房间检查是否存在
			if ($type == 'short') {
				$short 		= $Short->field('id,user_id')->where(['id' => $short_id])->find();

				if (!$short) {
					$this->ajaxError(['ERROR_CODE_SHORT' => 'NOT_EXIST']);
				}

			} elseif ($type == 'room') {
				$room 		= $LiveRoom->field('room_id,user_id')->where(['room_id' => $short_id])->find();

				if (!$room) {
					$this->ajaxError(['ERROR_CODE_LIVE' => 'NOT_EXIST']);
				}
			}

			// 获取相册图片
			$img_str 		= [];
			$pho 			= [];
			if ($photo) {
				if (is_array($photo)) {
					foreach ($photo as $val) {
						$temp 			= S($val);

						if ($temp) {
							$img_str[]  = $temp;
							$t_arr      = json_decode($temp, true);

							if ($t_arr) {
								$pho[]  = $t_arr[0];
							}
						}

						S($val, null);
					}
				} else {
					$tem 				= S($photo);
					if ($tem) {
						$img_str[] 		= $tem;
						$pho 			= $tem;
					}

					S($photo, null);
				}
			}

			// 记录数组
			$ins		= [
				'cat_id'	=> $cat_id,
				'user_id'	=> $res_token['uid'],
				'is_type'	=> $type == 'room' ? 2 : 1,
				'add_time'	=> date('Y-m-d H:i:s'),
			];

			if ($cause) {
				$ins['cause']		= $cause;
			}

			if ($short_id) {
				$ins['short_id']	= $short_id;
				$ins['by_id']		= $short['user_id'];
			}

			if ($room_id) {
				$ins['room_id']		= $room_id;
				$ins['by_id']		= $room['user_id'];
			}

			if ($pho) {
				$ins['photo']		= is_array($pho) ? json_encode($pho) : $pho;
			}

			//验证通过
			$res_ins 				= $Report->add($ins);

			if ($res_ins !== false) {
				// 删除多传的图片
				if ($img_arr) {
					foreach ($img_arr as $val) {
						if (!in_array($val, $img_str)) {
							$u_arr = json_decode($val, true);

							if ($u_arr) {
								foreach ($u_arr as $v) {
									@unlink('.'. $v);
								}
							}
						}
					}

					S('report_img_arr', null);
				}

				//成功
				$this->ajaxSuccess();
			} else {
				//数据库错误
				$this->ajaxError(['ERROR_CODE_GOODS' => 'DB_ERROR']);
			}
		}

		$this->ajaxError();
	}

}
?>    