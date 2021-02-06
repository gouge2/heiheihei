<?php


namespace Admin\Controller;


use Admin\Common\Controller\AuthController;
use Common\Model\IcoSettingModel;

class IcoSettingController extends AuthController
{
    /**
     * Ico列表
     */
    public function index()
    {
        $IcoSetting = new IcoSettingModel();
        $list = $IcoSetting->where(['is_delete' => 0])->order('sort desc')->select();

        $this->assign('list', $list);
        $this->display();
    }

    /**
     * Ico设置 添加/编辑
     */
    public function edit()
    {
        $gid = I('gid/d', 0);
        $ico_name = trim(I('ico_name'));
        $ico_url = I('ico_url');
        $ico_images = trim(I('ico_images'));
        $is_show = I('is_show/d');
        $sort = I('sort/d');

        // 上传路径缓存
        $ico_url_arr = S('ico_url_arr');
        $IcoSetting = new IcoSettingModel();
        // 提交
        if (IS_POST) {
            if ($ico_name && $ico_url && $ico_images) {
                $data = [
                    'ico_name' => $ico_name,
                    'ico_url' => $ico_url,
                    'ico_image' => $ico_images,
                ];
                if ($sort || $sort == 0) {
                    $data['sort'] = $sort;
                }
                if ($is_show || $is_show == 0) {
                    $data['is_show'] = $is_show;
                }

                // 新增/编辑
                if ($gid) {
                    $result = $IcoSetting->where(['ico_id' => $gid])->save($data);
                } else {
                    $data['add_time'] = date('Y-m-d H:i:s');
                    $result = $IcoSetting->add($data);
                }
                if ($result !== false) {
                    // 缓存路径文件删除
                    if ($ico_url_arr) {
                        S('ico_url_arr', null);
                    }
                    $this->ajaxSuccess();
                } else {
                    $this->ajaxError('数据库操作错误！');
                }
            }

            $this->ajaxError();

        } else {
            $result = $IcoSetting->where(['ico_id' => $gid])->find();
            if ($result['ico_image']) {
                $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
                $result['ico_image_url'] = $url.$result['ico_image'];
            }

            $this->assign('list', $result);
            $this->display();
        }
    }


    /**
     * 修改ico 显示、隐藏
     */
    public function editshow()
    {
        $code = '0';
        $sw = I('post.sw/d');
        $id = I('post.id/d');
        if ($id) {
            $IcoSetting = new IcoSettingModel();
            $sws = ($sw == 1) ? 1 : 0;
            $res = $IcoSetting->where(['ico_id' => $id])->setField('is_show',$sws);
            $code = ($res !== false) ? '1' : '0';
        }

        echo $code;
    }

    /**
     * 修改ico 排序
     */
    public function editSort()
    {
        $code = '0';
        $sort = I('post.sort/d');
        $id = I('post.id/d');
        if (($sort || $sort == 0) && $id) {
            $IcoSetting = new IcoSettingModel();
            $res = $IcoSetting->where(['ico_id' => $id])->save(['sort' => $sort]);
            $code = ($res !== false) ? '1' : '0';
        }
        echo $code;
    }

    /**
     * 图片上传
     */
    public function upload()
    {
        $type = trim(I('post.type'));
        if ($type && in_array($type, ['img'])) {
            $config = [
                'mimes' => [], //允许上传的文件MiMe类型
                'maxSize' => 0, //上传的文件大小限制 (0-不做限制)
                'exts' => ['jpg', 'gif', 'png', 'jpeg'], //允许上传的文件后缀
                'subName' => '', //子目录创建方式，为空
                'rootPath' => './Public/Upload/ico/', //保存根路径
                'savePath' => '', //保存路径
                'saveExt' => '', //文件保存后缀，空则使用原后缀
            ];

            $dir = iconv("UTF-8", "UTF-8", $config['rootPath']);
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }

            $upload = new \Think\Upload($config);
            $info = $upload->uploadOne($_FILES['file']);  // 上传单个文件

            if (!$info) {
                // 上传错误提示错误信息
                $this->ajaxError($upload->getError());
            } else {
                // 上传成功  文件完成路径
                $filepath = $config['rootPath'] . $info['savepath'] . $info['savename'];
                $img = substr($filepath, 1);

                // 保存路径到缓存
                $ico_url_arr = S('ico_url_arr');
                $ico_url_arr[] = $img;
                S('ico_url_arr', $ico_url_arr);
                $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
                $this->ajaxSuccess(['url' => $img, 'show_url' => $url . $img]);
            }
        }
        $this->ajaxError();
    }
}