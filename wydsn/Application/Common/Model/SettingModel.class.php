<?php
/**
 * by 翠花 http://http://livedd.com
 * 客服QQ管理
 */
namespace Common\Model;
use Think\Model;

class SettingModel extends Model
{

    /**
     * 获取配置
     * @param string $key
     * @param string $default
     * @return mixed|string
     */
    public function get($key='', $default=''){
        $info = $this->where(['key'=>$key])->find();
        if(empty($info)){
            return $default;
        }
        return $info['value'];
    }
    /**
     * 保存配置
     * @param $key
     * @param string $cache
     * @param string $value
     */
    public function set($key, $value='', $cache = ''){
        $info = $this->where(['key'=>$key])->find();
        if(empty($info)){
            $this->add(['key'=>$key, 'value'=>$value, 'cache'=>$cache]);
        }else{
            $this->where(['key'=>$key])->save(['value'=>$value, 'cache'=>$cache]);
        }
    }

}