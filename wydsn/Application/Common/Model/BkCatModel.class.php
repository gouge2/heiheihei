<?php
/**
 * by 翠花 http://www.lailu.shop
 * 宫格板块分类管理
 */
namespace Common\Model;
use Think\Model;

class BkCatModel extends Model
{
    //验证规则
    protected $_validate =array(
        array('title','require','分类名称不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('title','1,50','分类名称不超过50个字符！',self::EXISTS_VALIDATE,'length'),  //存在验证，不超过50个字符
        array('createtime','require','创建时间不能为空！',self::EXISTS_VALIDATE),  //存在验证，必填
        array('createtime','is_datetime','创建时间格式不正确！',self::EXISTS_VALIDATE,'function'),  //存在验证，必须是正确的时间格式
    );

    /**
     * 获取宫格板块分类列表
     * @param int $data:分类ID
     * @return array
     */
    public function getBkCatList($data=0)
    {
        if ($data==1){
            $where="is_delete='N'";
        }else{
            $where="is_delete='Y'";
        }
        $res=$this->where($where)->select();
        if($res!==false)
        {
            return $res;
        }else {
            return false;
        }
    }

    /**
     * 获取分类信息
     * @param int $id:分类ID
     * @return array
     */
    public function getCatMsg($id)
    {
        $res=$this->where("id=$id")->find();
        if($res!==false)
        {
            return $res;
        }else {
            return false;
        }
    }
}