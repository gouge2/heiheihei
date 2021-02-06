<?php
namespace Common\Model;
use Think\Model;

class CountryCodeModel extends Model
{
    #表名 
    protected $tableName='country_code';
    /**
     * 获取单条数据记录
     */
    public function getOne($whe, $field = '*')
    {
        $res = null;

        if ($whe) {
            $res = $this->field($field)->where($whe)->find();
        }
        return $res;
    }
}
?>