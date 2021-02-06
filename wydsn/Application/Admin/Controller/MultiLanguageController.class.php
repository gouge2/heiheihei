<?php

/**
 * 多语言
*/

namespace Admin\Controller;

use Admin\Common\Controller\AuthController;
 
 
 

 
class MultiLanguageController extends AuthController{
       
      
    public function countryList(){
          
        $countryList=M('country')->select();
    
          
        $this->assign('countryList',$countryList);
        $this->display();
    }
    public function addCountryAct(){
        $countryArr=explode(',', $_POST['country']);
        $isExist=M('country')->where(['name'=>$countryArr[1]])->find();
        if($isExist){
            $this->error('添加失败！'.$countryArr[1].'语言已存在');
            exit();
        }
        $data=[
               'name'=>$countryArr[1],
            'language_sign'=>$countryArr[0],
        ];
        M('country')->add($data);
        $this->success('添加多语言成功！',U('countryList'));
     
    }
    function langList(){
       
 
     
     $langList=M('multi_language')->where(['country_id'=>(int)$_GET['id']])->select();
    
        
       $this->assign('langList',$langList);
       $this->display();
    }
    
    function  addLang(){
 
         $lang=M('multi_language')->where(['country_id'=>(int)$_GET['cid'],'client_type'=>$_GET['client']])->find();
         
         if($_GET['client']=='android'){
             $lang_text=json_decode($lang['lang_text'],true);
             
             $this->assign('data',$lang);
             $this->assign('lang_text',$lang_text);
         }elseif ($_GET['client']=='小程序'){
             $lang_text=json_decode($lang['lang_text'],true);
//              foreach ($lang_text['']);
         //  p($lang_text);
           
         }
         
       
         

        $this->display();
         
    }
    
    function addLangAction(){
         $isExist=M('multi_language')->where(['client_type'=>$_POST['client'],'country_id'=>$_POST['country_id']])->find();
         if($isExist){
             $this->error('添加失败！'.$_POST['country'].'的语言'.'在'.$_POST['client'].'平台已存在');
             exit();
         }
   
     $lang_text=json_encode($_POST['lang'],JSON_UNESCAPED_UNICODE);
     
  
        $data=[
             'client_type'=>$_POST['client'],
            'lang_text'=>$lang_text,
            'admin_id'=>$_SESSION['admin_id'],
             'country_id'=>$_POST['country_id'],
            'lang_sign'=>$_POST['lang_sign'],
         ];
        M('multi_language')->add($data);
        $this->success('添加成功！',U('langList',array('id'=>$_POST['country_id'])));
    }
    
    function editLang(){
        $lang=M('multi_language')->where(['l_id'=>(int)$_GET['id']])->find();
         
        if($lang['client_type']=='android'){
         
            $lang_text=json_decode($lang['lang_text'],true);
              
            $this->assign('data',$lang);
            $this->assign('lang_text',$lang_text);
        }elseif($lang['client_type']=='小程序'){
            $lang_text=json_decode($lang['lang_text'],true);
            //              foreach ($lang_text['']);
            //  p($lang_text);
             
        }
          
        $this->display();
         
    }
    
    function editLangAction(){
        
    
        error_reporting(E_ALL);
        $lang_text=json_encode($_POST['lang'],JSON_UNESCAPED_UNICODE);
         
        $data=[
             'lang_text'=>$lang_text,
           ];
        M('multi_language')->where(['l_id'=>$_POST['id']])->save($data);
        $this->success('更新成功');
        
    }
   
   
}