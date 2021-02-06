<?php
/**
 * 短视频广告
 */

namespace Admin\Controller;

use Admin\Common\Controller\AuthController;

class AdvertisingController extends AuthController
{

    // 短视频一些状态信息
    public static $short_base = [
        'status' => [
            '1' => ['name' => '上架中', 'sel' => 0],
            '0' => ['name' => '已下架', 'sel' => 0],
        ],
    ];

    /**
     * 广告列表
     */
    public function index()
    {
        // 搜索的值
        $page = I('get.p', self::$page);
        $status 		= trim(I('get.status'));
        $search     	= self::$short_base;

        $whe['is_status'] = $status;

        if ($status || $status === '0') {
            $whe['is_status'] = $status;
            foreach ($search['status'] as $key => $val) {
                if ($status == $key) {
                    $search['status'][$key]['sel'] = 1;
                }
            }
        } else $whe = '';

        // 模型
        $Advertising = new \Common\Model\AdvertisingModel();
        $Page = new \Common\Model\PageModel();
        // 数据
        $count = $Advertising->where($whe)->count();
        $show = $Page->show($count, self::$limit);    // 分页显示输出
        $list = $Advertising->where($whe)->page($page, self::$limit)->order('id desc')->select();
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->assign('search', $search);

        $this->display();
    }

    /**
     * 新增/编辑 广告
     */
    public function mod()
    {
        $id = I('id/d', 0);
        $data['avatar'] = trim(I('post.avatar'));
        $interface = I('post.interface');
        $data['channel_link'] = trim(I('post.channel'));
        $data['media_url'] = trim(I('post.media_url'));
        $data['cover_url'] = trim(I('post.cover_url'));
        $data['title'] = trim(I('post.title'));
        $data['start_time'] = trim(I('post.start_time'));
        $data['end_time'] = trim(I('post.end_time'));
        $data['advertiser_name'] = trim(I('post.advertiser_name'));
        $is_status = I('post.is_status/d');

        // 上传路径缓存
        $short_url_arr = S('short_url_arr');

        $Advertising = new \Common\Model\AdvertisingModel();
        $bannerSource = new \Admin\Controller\BannerController();
        if (IS_POST) {
            //所有参数都毕传
            if (in_array('', $data)) {
                $this->ajaxError();
            }
            $time = date('Y-m-d H:i:s');
            if ($is_status && $time >= $data['end_time']) {
                $this->ajaxError('广告已到期，请重新设置结束日期再上架');
            }
            if ($interface) {
                $data['preparation_interface'] = $interface;
            }
            $data['is_status'] = $is_status;

            // 添加与编辑
            if ($id) {
                $data['update_time'] = date('Y-m-d H:i:s');
                $result = $Advertising->where(['id' => $id])->save($data);
            } else {
                $result = $Advertising->add($data);
            }

            if ($result === false) {
                $this->ajaxError('数据库操作错误');
            }

            // 缓存路径文件删除
            if ($short_url_arr) {
                foreach ($short_url_arr as $v) {
                    if ($v != $data['cover_url'] && $v != $data['media_url'] && $v <> $data['avatar']) {
                        @unlink('.' . $v);
                    }
                }
                S('short_url_arr', null);
            }
            $post = I('post.');
            $model_setting  = new \Common\Model\SettingModel();
            $file           = "./Public/inc/banner.config.php";
            $id = $id ? $id : $result;
            $model_setting->set("ADVER_{$id}", json_encode($post), $file);
            $this->cacheSetting($file);
            $this->ajaxSuccess();

        } else {
            $whe = ['id' => $id];
            $s_one = $Advertising->where($whe)->find();
            $s_one = $s_one ? $s_one : ['id' => 0, 'avatar' => '', 'title' => '', 'start_time' => '', 'end_time' => '', 'channel_link' => '', 'play_num' => 0, 'comment_num' => 0, 'praise_num' => 0, 'forward_num' => 0, 'click_num' => 0, 'is_status' => 1];
            // 地址处理
            $s_one['cover_avatar'] = is_url($s_one['avatar']) ? $s_one['avatar'] : WEB_URL . $s_one['avatar'];
            $s_one['cover_show'] = is_url($s_one['cover_url']) ? $s_one['cover_url'] : WEB_URL . $s_one['cover_url'];
            $s_one['media_show'] = $s_one['media_url'] ? (is_url($s_one['media_url']) ? $s_one['media_url'] : WEB_URL . $s_one['media_url']) : '';
            $advertSource = $bannerSource->advertSource;
            // 可变常量提取存入信息
            if (file_exists('./Public/inc/banner.config.php')) require_once './Public/inc/banner.config.php';
            $bannData = defined("ADVER_{$id}") ? json_decode(get_defined_constants()["ADVER_{$id}"],true) : '';
            $msg = $bannData ? array_merge($s_one, self::$short_base,$bannData) : array_merge($s_one, self::$short_base);
            $this->assign('advertSource',$advertSource);
            $this->assign('short', $msg);
            $this->display();
        }
    }

    /**
     * 视频封面与视频上传
     */
    public function upload()
    {
        $type = trim(I('post.type'));
        $from = trim(I('post.from'));

        if ($type && in_array($type, ['img', 'mp4'])) {
            $root_str = $from ? ($from == 'live' ? 'Room' : ($from == 'gift' ? 'Room/Live' : 'Short')) : 'Short';

            $config = [
                'mimes' => [], //允许上传的文件MiMe类型
                'maxSize' => 0, //上传的文件大小限制 (0-不做限制)
                'exts' => ['jpg', 'gif', 'png', 'jpeg', 'mp4', 'svga'], //允许上传的文件后缀
                'subName' => '', //子目录创建方式，为空
                'rootPath' => './Public/Upload/' . $root_str . '/', //保存根路径
                'savePath' => '', //保存路径
                'saveExt' => '', //文件保存后缀，空则使用原后缀
            ];

            // 上传封面图与视频
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
                $short_url_arr = S('short_url_arr');
                $short_url_arr[] = $img;
                S('short_url_arr', $short_url_arr);

                $this->ajaxSuccess(['url' => $img, 'show_url' => WEB_URL . $img]);
            }
        }

        $this->ajaxError();
    }

    /**
     * 广告删除
     * @param $id
     */
    public function del($id)
    {
        $code = '0';
        if ($id) {
            $Advertising = new \Common\Model\AdvertisingModel();
            $res = $Advertising->where(['id' => $id])->delete();
            $code = ($res !== false) ? '1' : '0';
        }
        echo $code;
    }

    /**
     * 批量删除
     * @param $all_id
     */
    public function batchdel($all_id)
    {
        $all_id = substr($all_id, 0, -1);
        $Advertising = new \Common\Model\AdvertisingModel();
        $res = $Advertising->where("id in ($all_id)")->delete();
        $code = ($res !== false) ? '1' : '0';
        echo $code;
    }

}