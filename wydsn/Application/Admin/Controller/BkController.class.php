<?php
/**
 * by 翠花 http://www.lailu.shop
 * 其他管理-宫格板块管理
 */
namespace Admin\Controller;
use Admin\Common\Controller\AuthController;
class BkController extends AuthController
{
    public function index($cat_id)
    {
        $this->assign('cat_id',$cat_id);
        //获取分类信息
        $BkCat=new \Common\Model\BkCatModel();
        $catMsg=$BkCat->getCatMsg($cat_id);
        $this->assign('cat_title',$catMsg['title']);
        //根据分类ID获取链接列表
        $Bk=new \Common\Model\BkModel();
        $hlist=$Bk->getBkList($cat_id);
        $this->assign('hlist',$hlist);
        $this->display();
    }

    //添加宫格板块
    public function add($cat_id)
    {
        $this->assign('cat_id',$cat_id);
        //获取分类信息
        $BkCat=new \Common\Model\BkCatModel();
        $catMsg=$BkCat->getCatMsg($cat_id);

        if($_POST) {
            layout(false);
            //上传文件
            if(!empty($_FILES['img']['name']))
            {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                    'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'bk'.$cat_id.time(), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['img']);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                    $img=substr($filepath,1);
                }
            }
            //保存到数据库
            $data=array(
                'cat_id'=>$cat_id,
                'title'=>trim(I('post.title')),
                'href'=>trim(I('post.href')),
                'sort'=>trim(I('post.sort')),
                'img'=>$img,
                'color'=>trim(I('post.color')),
                'is_show'=>trim(I('post.is_show')),
                'type'=>trim(I('post.type')),
                'type_value'=>trim(I('post.type_value')),
                'createtime'=>date('Y-m-d H:i:s'),
            );
            $Bk=new \Common\Model\BkModel();
            if(!$Bk->create($data)) {
                // 验证不通过
                // 删除图片
                @unlink($filepath);
                $this->error($Bk->getError());
            }else {
                // 验证成功
                $res_add=$Bk->add($data);
                if($res_add!==false) {
                    $this->success('新增成功！',U('index',array('cat_id'=>$cat_id)));
                }else {
                    //删除图片
                    @unlink($filepath);
                    $this->error('操作失败！');
                }
            }
        }else {
            $this->assign('cat_title',$catMsg['title']);

            $this->display();
        }
    }

    //编辑宫格板块
    public function edit($id,$cat_id)
    {
        $this->assign('id',$id);
        $this->assign('cat_id',$cat_id);
        //根据ID获取图片信息
        $Bk=new \Common\Model\BkModel();
        $msg=$Bk->getBkMsg($id);

        if($_POST) {
            layout(false);
            //上传文件
            if(!empty($_FILES['img']['name']))
            {
                $config = array(
                    'mimes'         =>  array(), //允许上传的文件MiMe类型
                    'maxSize'       =>  1024*1024*4, //上传的文件大小限制 (0-不做限制)
                    'exts'          =>  array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
                    'rootPath'      =>  './Public/Upload/Banner/', //保存根路径
                    'savePath'      =>  '', //保存路径
                    'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
                    'replace'       =>  true, //存在同名是否覆盖
                    'saveName'      =>  'bk'.$cat_id.time(), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
                );
                $upload = new \Think\Upload($config);
                // 上传单个文件
                $info = $upload->uploadOne($_FILES['img']);
                if(!$info) {
                    // 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{
                    // 上传成功
                    // 文件完成路径
                    $filepath=$config['rootPath'].$info['savepath'].$info['savename'];
                    $img=substr($filepath,1);
                }
            }else {
                $img=$msg['img'];
            }
            //保存到数据库
            $data=array(
                'title'=>trim(I('post.title')),
                'href'=>trim(I('post.href')),
//                'sort'=>trim(I('post.sort')),
                'img'=>$img,
//                'color'=>trim(I('post.color')),
                'is_show'=>trim(I('post.is_show')),
                'type'=>trim(I('post.type')),
                'type_value'=>trim(I('post.type_value')),
                'createtime'=>date('Y-m-d H:i:s'),
                'agent_id'=>0
            );
            $post = I('post.');
            $model_setting  = new \Common\Model\SettingModel();
            $file           = "./Public/inc/banner.config.php";
            $model_setting->set("BK_{$id}", json_encode($post), $file);
            $this->cacheSetting($file);
            if(!$Bk->create($data)) {
                // 验证不通过
                // 删除图片
                @unlink($filepath);
                $this->error($Bk->getError());
            }else {
                // 验证成功
                $res_edit=$Bk->where("id=$id")->save($data);
                if($res_edit!==false) {
                    // 修改成功
                    // 原图片存在，并且上传了新图片的情况下，删除原标题图片
                    if($msg['img'] and $img!=$msg['img']) {
                        $oldimg='.'.$msg['img'];
                        @unlink($oldimg);
                    }
                    $this->success('修改成功！');
                }else {
                    //删除图片
                    @unlink($filepath);
                    $this->error('操作失败！');
                }
            }
        }else {
            $bannerSource = new \Admin\Controller\BannerController();
            $advertSource = $bannerSource->advertSource;
            // 可变常量提取存入信息
            if (file_exists('./Public/inc/banner.config.php')) require_once './Public/inc/banner.config.php';
            $bannData = defined("BK_{$id}") ? json_decode(get_defined_constants()["BK_{$id}"],true) : '';
            $msg = $bannData ? $bannData : $msg;
            $this->assign('msg',$msg);
            $this->assign('advertSource', $advertSource);

            $this->display();
        }
    }

    //删除宫格板块
    public function del($id)
    {
        $Bk=new \Common\Model\BkModel();
        $msg=$Bk->getBkMsg($id);
        $res_del=$Bk->where("id=$id")->delete();
        if($res_del!==false) {
            //删除图片
            if(!empty($msg['img'])) {
                $img='.'.$msg['img'];
                @unlink($img);
            }
            echo '1';
        }else {
            echo '0';
        }
    }

    //批量删除宫格板块
    public function batchdel($all_id)
    {
        $all_id=substr($all_id,0,-1);
        $id_arr=explode(',',$all_id);
        $num=count($id_arr);
        $Bk=new \Common\Model\BkModel();
        for($i=0;$i<$num;$i++)
        {
            $id=$id_arr[$i];
            $res1=$Bk->getBkMsg($id);
            $img=$res1['img'];
            $res=$Bk->where("id=$id")->delete();
            if($res)
            {
                //删除图片
                if(!empty($img))
                {
                    $img='.'.$img;
                    unlink($img);
                }
                $a.='a';
            }
        }
        $a.='true';
        $str=str_repeat('a',$num).'true';
        if($str==$a)
        {
            echo '1';
        }else {
            echo '0';
        }
    }

    //批量修改排序
    public function changesort()
    {
        $sort_array=I('post.sort');
        $ids = implode(',', array_keys($sort_array));
        $sql = "UPDATE __PREFIX__bk SET sort = CASE id ";
        foreach ($sort_array as $id => $sort) {
            $sql .= sprintf("WHEN %d THEN %d ", $id, $sort);
        }
        $sql.= "END WHERE id IN ($ids)";
        $res = M()->execute($sql);
        layout(false);
        if($res===false)
        {
            $this->error('操作失败!');
        }else {
            $this->success('排序成功!');
        }
    }

    //修改显示状态
    public function changeshow($id,$status)
    {
        $data=array(
            'is_show'=>$status
        );
        $Bk=new \Common\Model\BkModel();
        if(!$Bk->create($data))
        {
            // 验证不通过
            echo '0';
        }else {
            // 验证成功
            $res=$Bk->where("id=$id")->save($data);
            if($res===false)
            {
                echo '0';
            }else {
                echo '1';
            }
        }
    }
}
?>
