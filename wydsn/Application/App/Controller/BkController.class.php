<?php
/**
 * by 翠花 http://www.lailu.shop
 *  宫格版块管理接口
 */
namespace App\Controller;
use App\Common\Controller\AuthController;

class BkController extends AuthController
{
    /**
     * 获取宫格版块图列表
     * @param int $cat_id:宫格分类ID
     * @param int $agent_id:代理商ID
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->list:宫格图列表
     */
    public function getBkList()
    {
        if(trim(I('post.cat_id')))
        {
            $cat_id=trim(I('post.cat_id'));
            $agent_id=0;
            if(trim(I('post.agent_id'))){
                $agent_id=trim(I('post.agent_id'));
            }
            $Bk=new \Common\Model\BkModel();
            $list=$Bk->getBkList($cat_id,'Y',$agent_id);
            if($list!==false)
            {
                $data=array(
                    'list'=>$list
                );
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data
                );
            }else {
                //数据库错误
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }

    public function getBkListV2(){
        $agent_id=0;
        if(trim(I('post.agent_id'))){
            $agent_id=trim(I('post.agent_id'));
        }
        $cate_list = M('bk_cat')
            ->field('id, title, layout, line_height')
            ->where(['is_delete'=>'N', 'state' => 1])
            ->order('weight asc')
            ->select();
        $list = [
            'new_user' => new \stdClass(),
            'bk'       => []
        ];
        foreach ($cate_list as $cate){
            $layout = $cate['layout'] ? $cate['layout'] : 2;
            $where = [
                'cat_id' => $cate['id'],
                'is_show' => 'Y',
                'agent_id' => $agent_id
            ];
            if($cate['id'] == 1){
                $bk_list = M("bk")->where($where)->order('sort desc')->select();
                if(empty($bk_list)){
                    continue;
                }
                foreach ($bk_list as $key => $bk){
                    list($width, $height) = getimagesize(WEB_URL.$bk['img']);
                    $bk_list[$key]['width'] = $width;
                    $bk_list[$key]['height'] = $height;
                }
                $cate['bk_list'] = $bk_list;
                $list['new_user'] = $cate;
            }else{
                $bk_list = M("bk")->where($where)->order('sort desc')->limit($layout)->select();
                if(empty($bk_list)){
                    continue;
                }
                if(count($bk_list) < $layout){
                    continue;
                }
                foreach ($bk_list as $key => $bk){
                    list($width, $height) = getimagesize(WEB_URL.$bk['img']);
                    $bk_list[$key]['width'] = $width;
                    $bk_list[$key]['height'] = $height;
                }
                $cate['bk_list'] = $bk_list;
                $list['bk'][] = $cate;
            }


        }
        $this->ajaxSuccess($list);
    }

    /**
     * 获取宫格版块图信息
     * @param int $id:宫格版块图ID
     * @return array
     * @return @param code:返回码
     * @return @param msg:返回码说明
     * @return @param data:返回数据
     * @return @param data->bkMsg:宫格版块图信息
     */
    public function getBkMsg()
    {
        if(trim(I('post.id')))
        {
            $id=trim(I('post.id'));
            $Bk=new \Common\Model\BkModel();
            $bkMsg=$Bk->getBkMsg($id);
            if($bkMsg!==false)
            {
                $data=array(
                    'bkMsg'=>$bkMsg
                );
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['SUCCESS'],
                    'msg'=>'成功',
                    'data'=>$data
                );
            }else {
                //数据库错误
                $res=array(
                    'code'=>$this->ERROR_CODE_COMMON['DB_ERROR'],
                    'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['DB_ERROR']]
                );
            }
        }else {
            //参数不正确，参数缺失
            $res=array(
                'code'=>$this->ERROR_CODE_COMMON['PARAMETER_ERROR'],
                'msg'=>$this->ERROR_CODE_COMMON_ZH[$this->ERROR_CODE_COMMON['PARAMETER_ERROR']]
            );
        }
        echo json_encode ($res,JSON_UNESCAPED_UNICODE);
    }
}
?>