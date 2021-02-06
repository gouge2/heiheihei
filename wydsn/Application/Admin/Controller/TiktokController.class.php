<?php
namespace Admin\Controller;

use Admin\Common\Controller\AuthController;
use Think\Controller;

class TiktokController extends Controller {

    public function getVideo(){
        $number = intval(I('post.number'));
        $uid = intval(I('post.uid'));
        S('get_tiktok_video_number', $number, 3600);
        S('get_tiktok_video_uid', $uid, 3600);
        echo 1;
    }
}