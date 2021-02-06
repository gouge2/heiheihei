<?php
/**
 * 直播商品讲解时间管理类
 */
namespace Common\Model;
use Think\Model;

class LiveGoodsExplainModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('slg_id','require','直播与商品关联表的标识不能为空！',self::EXISTS_VALIDATE),         //存在验证，必填
    );

}
?>