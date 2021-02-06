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
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
    <script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
    <script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
    <style>
    .disable{
	    color: #333;
    word-break: break-all;
    word-wrap: break-word;
    background-color: #f5f5f5;
    border: 1px solid #ccc;
    }
    </style>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-content">
                    <h3>当前位置：多商户&raquo; 多语言&raquo;编辑语言<a class="layui-btn pull-right" href="javascript:;" onClick="javascript :history.back(-1);" style="margin-top: -10px">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__MODULE__/MultiLanguage/editLangAction" onSubmit="return check(this);" class="form-horizontal layui-form " class="layui-form" method="post" enctype="multipart/form-data">
                             <input type="hidden" value="<?= $_GET['id']?>" name="id" />
                    
                            <hr />
                             <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;"><b>源语言  —></b></label>
                                <div class="layui-input-block">
                                     <p style=" padding-top: 8px;"><b>   <?= $_GET['name'] ?>语言</b></p>
                                    <div style="width: 94%;color: red">{$error1}</div>
                                </div>
                            </div>
                             <?php 
                            
                             foreach ($lang_text as $k=>$v):?>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;"><pre><?= $v?></pre></label>
                                <div class="layui-input-block">
                                    <input class="layui-input" data-text='<?= $v?>'  value="<?= $v?>" name="lang[<?= $k ?>]" placeholder="请输入目标国家语言" style="width: 30%;"   />
                                    <div style="width: 94%;color: red">{$error1}</div>
                                </div>
                            </div>
                            <?php endforeach;?>
                          
                  <!--     
                       
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">组别描述</label>
                                <div class="layui-input-block">
                                    <textarea name="introduce" placeholder="" class="layui-input" style="height:150px;width: 94%;"></textarea>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 150px;">是否冻结</label>
                                <div class="layui-input-block">
                                    <input type="radio" name="is_freeze" value="N" checked title='正常使用'>
                                    <input type="radio" name="is_freeze" value="Y" title='冻结'>
                                </div>
                            </div>
                             -->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block" style="width: 94%">
                                    <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;提交</button>
                                    <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function check(v){
	 for(i=2;i<(v.length-2);i++){

		 k=v[i].value?v[i].value:v[i].value=v[i].getAttribute('data-text');
		 
		 console.log(k);
	}
	console.log(i);
		return true;

}                        

 </script>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
</body>
</html>