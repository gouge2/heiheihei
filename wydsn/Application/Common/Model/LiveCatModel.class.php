<?php
/**
 * 直播分类管理
 */
namespace Common\Model;
use Think\Model;

class LiveCatModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('cat_name','require','分类名称不能为空！',self::EXISTS_VALIDATE),             //存在验证，必填
    );

    
}
?>