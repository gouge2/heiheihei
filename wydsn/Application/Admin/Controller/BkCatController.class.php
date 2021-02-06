<?php
/**
 * by 来鹿 http://www.lailu.shop
 * 其他管理-宫格板块分类管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BkCatController extends AuthController
{
    public function index()
    {
        //获取分类列表
        $BkCat=new \Common\Model\BkCatModel();
        $list=$BkCat->getBkCatList(1);
        $this->assign('list',$list);
        $this->display();
    }

    public function info(){
        $id = I('get.id', 0);
        if($id > 0){
            $where = ['id' => $id];
            $bk_cat = M('bk_cat')->where($where)->find();
            $this->assign('info', $bk_cat);
        }

        $this->assign('id', $id);
        $this->display();
    }

    public function save(){
        layout(false);
        if(!$_POST){
            return;
        }
        $id = I('post.id');
        $data = [
            'title' => I('post.title'),
            'layout' => I('post.layout'),
            'line_height' => I('post.line_height'),
            'weight' => I('post.weight'),
            'state' => I('post.state')
        ];

        if($id > 0){
            $where = ['id' => $id];
            $bk_cat = M('bk_cat')->where($where)->find();
            if(empty($bk_cat)){
                $data['id'] = $id;
                M('bk_cat')->add($data);
            }else{
                M('bk_cat')->where($where)->save($data);
            }
        }else{
            $last = M('bk_cat')->field("max(`id`) as `last_id`")->find();
            $data['is_delete'] = 'N';
            $data['id'] = $last['last_id']+1;
            $data['createtime'] = date('Y-m-d H:i:s', time());
            M('bk_cat')->add($data);

            for($i=0; $i< $data['layout']; $i++){
                $bk_info = [
                    'cat_id' => $data['id'],
                    'title'  => '示例',
                    'img' => '',
                    'is_show' => 'N',
                    'createtime' => date('Y-m-d H:i:s', time())
                ];
                M('bk')->add($bk_info);
            }
        }

        $this->ajaxSuccess();
    }

    //添加分类
    public function add()
    {
        if($_POST) {
            layout(false);
            $data=array(
                'title'=>trim(I('post.title')),
                'is_delete'=>'N',
                'createtime'=>date('Y-m-d H:i:s')
            );
            $BkCat=new \Common\Model\BkCatModel();
            if(!$BkCat->create($data)) {
                // 验证不通过
                $this->error($BkCat->getError());
            }else {
                // 验证成功
                $res_add=$BkCat->add($data);
                if($res_add!==false) {
                    $this->success('添加成功！');
                }else {
                    $this->error('操作失败！');
                }
            }
        }
    }

    //编辑分类
    public function edit($id)
    {
        //根据ID获取分类信息
        $BkCat=new \Common\Model\BkCatModel();
        $msg=$BkCat->getCatMsg($id);

        if($_POST) {
            layout(false);
            $data=array(
                'title'=>trim(I('post.title')),
                'createtime'=>date('Y-m-d H:i:s')
            );
            if(!$BkCat->create($data)) {
                // 验证不通过
                $this->error($BkCat->getError());
            }else {
                // 验证成功
                $res_edit=$BkCat->where("id=$id")->save($data);
                if($res_edit!==false) {
                    $this->success('编辑成功！',U('index'));
                }else {
                    $this->error('操作失败！');
                }
            }
        }else {
            $this->assign('msg',$msg);

            $this->display();
        }
    }

    //删除分类
    public function del($id)
    {
        $BkCat=new \Common\Model\BkCatModel();
        $res=$BkCat->where("id=$id")->delete();
        if($res!==false) {
            //删除分类下的所有广告图
            $Bk=new \Common\Model\BkModel();
            $res2=$Bk->where("cat_id=$id")->select();
            if(!empty($res2)) {
                $num=count($res2);
                for($i=0;$i<$num;$i++) {
                    $img=$res2[$i]['img'];
                    if(!empty($img))
                    {
                        $img='.'.$img;
                        unlink($img);
                    }
                    $a.='a';
                }
                $a=$a.'true';
                $str=str_repeat('a',$num).'true';
                if($str==$a)
                {
                    $res3=$Bk->where("cat_id=$id")->delete();
                    if($res3!=false)
                    {
                        echo '1';
                    }else {
                        echo '0';
                    }
                }
            }else {
                echo '1';
            }
        }else {
            echo '0';
        }
    }
}