<?php
/**
 * by 翠花 http://http://livedd.com
 * 多商户
 */

namespace Common\Model;

use Think\Model;

class MultiMerchantModel extends Model
{

    /**
     * 获取多商户开启状态
     * @return array|false|mixed|string|null
     */
    public function getBannerCatList()
    {
      
        $res = $this->select();
    
        if ($res !== false) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * 获取分类信息
     * @param int $id :分类ID
     * @return array
     */
    public function getCatMsg($id, $show = true)
    {
        $whe = ['id' => $id];
        if ($show) {
            $whe['is_show'] = 1;
        }

        $res = $this->where($whe)->find();

        return $res !== false ? $res : false;
    }

    /**
     * 更新多商户配置
     * @param $data
     */
    public function mod($data)
    {
        $res = $this->where(['id'=>1])->save($data);
        return $res !== false ? $res : false;
    }
}