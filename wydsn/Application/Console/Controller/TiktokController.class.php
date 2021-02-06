<?php
namespace Console\Controller;

use Console\Common\BaseController;

class TiktokController extends BaseController {

    private $number = 0;
    private $uid = 0;

    public function getVideo(){
        $number = intval(S('get_tiktok_video_number'));
        $uid = intval(S('get_tiktok_video_uid'));
        if($number <= 0 || $uid <= 0){
            return;
        }
        $this->number = $number;
        $this->uid = $uid;
        S('get_tiktok_video_number', null);
        S('get_tiktok_video_uid', null);
        $this->println("启动抓取. 用户：$this->uid , 数量：$this->number");
        while (1){
            $i = rand(1, 999);
            $this->getShortVideo($i);
            sleep(1);
        }
    }

	private function getShortVideo($page, $limit=20)
	{
	    $area = rand(1, 10);
	    $url = "https://api3-normal-c-lq.amemv.com/aweme/v2/shop/discovery/feed/?version_code=11.7.0&js_sdk_version=1.70.0.2&tma_jssdk_version=1.70.0.2&app_name=aweme&app_version=11.7.0&vid=A32D7D7A-E60F-4F31-91A6-D68266D1E4FC&device_id=10832213334&channel=App%20Store&mcc_mnc=46007&aid=1128&screen_width=1242&openudid=51b66ba09869ea73e6bdd77f0689623121913227&cdid=37020555-11CF-44FC-991A-4090708544DA&os_api=18&ac=WIFI&os_version=12.1&build_number=117009&device_platform=iphone&device_type=iPhone8,2&is_vcd=1&iid=2981531912256775&idfa=3AC5C41B-89E4-4A01-94B8-B02DA600672A&size=$limit&area_id=$area&category_id=0&request_tag_from=rn&page=$page";
		$data = $this->getUrl($url);
		$data = json_decode($data, true);
        if(!isset($data['aweme_list'])){
            return;
        }
        $aweme_list = $data['aweme_list'];
        foreach ($aweme_list as $aweme){
            $short = [];
            $short['vid'] = $aweme['aweme_id'] ;

            if(!isset($aweme['video'])){
                continue;
            }

            if(!isset($aweme['video']['download_suffix_logo_addr'])){
                continue;
            }

            if(!isset($aweme['anchor_info']['extra'])){
                continue;
            }

            $video = $aweme['video']['download_suffix_logo_addr']['url_list'][2];
            $video = str_replace("watermark=1", 'watermark=0', $video);

            $goods = json_decode($aweme['anchor_info']['extra'], true);
            $goods = isset($goods[0]) ? $goods[0] : [];

            if(empty($goods)){
                continue;
            }

            if($goods['promotion_source'] != 7){
                continue;
            }

            $where = [
                'vid' => $short['vid']
            ];
            $info = M('short')->where($where)->find();
            if(empty($info)){
                $short['short_name'] = $this->filter_Emoji($aweme['desc']);
                $short['user_id'] = $this->uid;
                $short['description'] = $this->filter_Emoji($aweme['desc']);
                $short['create_time'] = date('Y-m-d H:i:s', time());
                $short['update_time'] = date('Y-m-d H:i:s', time());
                $short['expiret_time'] = "9999-12-31 23:59:59";
                $short['cat_id']      = 0;
                $short['cat_name']    = "其他";
                $short['cover_url']   = $aweme['video']['origin_cover']['url_list'][0];
                $short['short_type']  = 'mp4';
                $short['media_url']   = $video;
//                $short['praise_num']  = $aweme['statistics']['digg_count'];
//                $short['comment_num'] = $aweme['statistics']['comment_count'];
//                $short['forward_num'] = $aweme['statistics']['forward_count'];
                $short['praise_num']  = 0;
                $short['comment_num'] = 0;
                $short['forward_num'] = 0;
                $short['width']  = 720;
                $short['height'] = 1280;

                $short_id = M('short')->add($short);
            }else{
                $this->println("视频已存在.".$short['vid']);
                continue;
            }

            $where = [
                "goods_id" => $goods['product_id']
            ];
            $tb_goods = M('tb_goods')->where($where)->find();
            if(empty($tb_goods)){
                $tb_goods = [];
                $tb_goods['goods_id']   = $goods['product_id'];
                $tb_goods['goods_name'] = $goods['title'];
                $tb_goods['zk_final_price'] = $goods['price']/100;
                $tb_goods['pict_url'] = $goods['elastic_images'][0]['url_list'][0];
                $tb_goods['small_images'] = $goods['elastic_images'][2]['url_list'][0];
                $tb_goods['description'] = $goods['elastic_title'];
                $tb_goods['commission_rate'] = 10;
                $tb_goods['coupon_amount'] = 0;
                $tb_goods['create_time'] = date('Y-m-d H:i:s', time());

                M('tb_goods')->add($tb_goods);
            }

            $where = [
                'short_id' => $short_id,
                'goods_id' => $goods['product_id'],
                'from' => 'tb'
            ];
            $short_goods = M('short_live_goods')
                ->where($where)
                ->find();

            if(empty($short_goods)){
                $short_goods = [];
                $short_goods['short_id'] = $short_id;
                $short_goods['goods_id'] = $goods['product_id'];
                $short_goods['user_id']  = $this->uid;
                $short_goods['from']     = 'tb';
                $short_goods['type']     = 'short';

                M('short_live_goods')->add($short_goods);
            }
            $this->number--;
            $this->println("新增一条，剩余数量：$this->number");
            if($this->number <= 0){
                exit;
            }
        }
	}

    private function getUrl($url){
        $headerArray =array(
            "Content-type:application/json;",
            "Accept:application/json"
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    private function filter_Emoji($str)
    {
        $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }

}