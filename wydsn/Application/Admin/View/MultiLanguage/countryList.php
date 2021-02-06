<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
    <link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">

    <link href="__ADMIN_CSS__/img.css" rel="stylesheet">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <!-- Sweet Alert -->
    <link href="__ADMIN_CSS__/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="__ADMIN_JS__/plugins/sweetalert/sweetalert.min.js"></script>
    <!-- Sweet Alert -->

    <!-- ueditor -->
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
    <style>
        body #preview {
            overflow: hidden !important;
        }

        #st a {
            width: 100px;
            margin-right: 61px;
        }
    </style>
</head>
<div class="cover" hidden>
     <div class="layui-card-body">
                        <form action="__MODULE__/MultiLanguage/addCountryAct" class="country_form form-horizontal layui-form  layui-form-pane" lay-filter="mod_form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="0">
                            
                     <div class="layui-form-item">
          <label class="layui-form-label">请选择语言</label>
          <div class="layui-input-block">
   
<select name="country" class="country"  required  lay-verify="required">
  <option value="">请选择一个语言</option>
  <option value="en,英文">英文</option>
  <option value="zh,简体中文">简体中文</option>
  <option value="ms,马来西亚">马来西亚</option>
  <option value="ar,阿拉伯">阿拉伯</option>
</select>    
          
<!--             <input type="text" name="title" required="" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input"> -->
          </div>
        </div>

                    
                            <div class="layui-form-item layui-hide">
                                <input type="button" lay-submit="" lay-filter="LAY-user-front-submit" id="LAY-user-back-submit" value="提交" name="">
                            </div>

                        </form>
                    </div>
</div>

<body class="gray-bg">


<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：插件 &raquo; 多商户</h3>
                </div>
                <div class="ibox-content">
                                                            <a class="layui-btn pull-right" href="javascript:;" onclick="layer_form()">添加语言</a>
                
                    <div class="layui-row layui-col-space17">
                        <form class="layui-form">
                        
                            <table class="layui-table">
                                <thead>
                                <tr>
                                    <th style="width: 25%">id</th>
                                    <th style="width: 25%">语言名称</th>
                                    <th style="width: 25%">语言标识</th>
                                    <th style="width: 25%">操作</th>
                                </tr>
                                </thead>
                                <tbody>

                               <?php foreach ($countryList as $v):?>
                                  <tr>
                                        <td><?= $v['cid']?></td>
                                        <td><?= $v['name']?></td>
                                        <td><?= $v['language_sign']?></td>
                                      
                                        <td>
                                            
                                            <div id='st' style="display: <?php echo $none; ?>">
                                                 <a style="width: 142px;" class="layui-btn pull-right"
                                                   href="__MODULE__/MultiLanguage/langList?id=<?= $v['cid']?>&name=<?= $v['name']?>&lang_sign=<?= $v['language_sign']?>">语言列表</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
 <script type="text/javascript">
   
//添加/编辑
function layer_form() {
 layer.open({
     type: 1,
     content:$('.cover'), 
      title: '添加语言',
     area: ['70%', '500px'],
     btn: ['立即提交', '取消'],
     yes: function(index, layero) {
      $('.country_form').submit();
     }
 }); 
}

layui.use('form', function(){

	  
	  $('.country_form').on('submit',function(data){
		 if(!$('.country').val()){
	    layer.msg('请选择国家');
	    return false;
		 }
	  });  
	});
</script>
<style>
    .sweet-alert button.cancel {
        background-color: rgb(221, 107, 85);
    }
</style>
</body>
</html>