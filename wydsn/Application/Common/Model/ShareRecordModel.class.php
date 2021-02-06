<?php
/**
 * 分享图片记录管理类
 */
namespace Common\Model;
use Think\Model;

class ShareRecordModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('url','require','图片路径不能为空！',self::EXISTS_VALIDATE),      // 存在验证，必填
        array('type','require','类型不能为空！',self::EXISTS_VALIDATE),         // 存在验证，必填
    );
    

    /**
     * 获取记录图片路径  
     */
    public function getOneUrl($whe)
    {
        $str = ['id' => 0, 'url' => '', 'code_img' => ''];

        $one = $this->field('id,url,code_img')->where($whe)->find();

        if ($one) {
            $str['url']         = $one['url'] ? WEB_URL . $one['url'] : '';
            $str['code_img']    = $one['code_img'] ? WEB_URL . $one['code_img'] : '';
            $str['id']          = $one['id'];
        }

        return $str;
    }
}
?>