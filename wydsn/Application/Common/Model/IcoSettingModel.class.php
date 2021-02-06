<?php


namespace Common\Model;


use Think\Model;

class IcoSettingModel extends Model
{
    /**
     * 验证规则
     * @var array
     */
    protected $_validate = array(
        array('ico_name', 'require', '名称不能为空！', self::EXISTS_VALIDATE),  //存在验证，必填
        array('ico_image', 'require', '图片不能为空！', self::EXISTS_VALIDATE),  //存在验证，必填
    );

    /**
     * ico列表
     * @return mixed
     */
    public function getList()
    {
        $resouce = $this->field(['ico_id','ico_name','ico_image','ico_url','sort','is_show'])->where(['is_delete'=> 0,'is_show'=>1])->order('sort desc')->select();
        foreach ($resouce as $key => $value ) {
            $url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'];
            if ($value['ico_image']) {
                $resouce[$key]['ico_image'] = $url.$value['ico_image'];
            }
        }
        return $resouce;
    }

}