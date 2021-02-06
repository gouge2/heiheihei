<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <style>
        .diys {height: 40px;line-height: 50px;}
        .fa-right {float: right;margin-top: 5px;}
        .form-labal-tilte {width: 115px;}
        .layui-input-block{margin-left: 145px;}
        .layui-input-in {width: 30%;}
        .layui-text {background-color: #FFFFFF;}
        .layui-form {background-color: #FFFFFF;}
        .layui-table-cell {height: auto;line-height: 50px;}

    </style>
</head>
<body class="layui-bg-gray">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <blockquote class="layui-elem-quote layui-text diys">
            <div class="ibox-title">
                <h3 class="layui-inline">当前位置：系统设置 &raquo; APP首页活动设置 </h3>
                <a class="layui-btn fa-right" href="__CONTROLLER__/headAdvertAdd">添加活动 <i class="fa fa-angle-double-right"> >> </i></a></h3>
            </div>
        </blockquote>
        <table class="layui-table" lay-data="{ height: 'full-200', url:'__CONTROLLER__/getHeadAdvertSet', page:true, id:'id'}" id="banner" lay-filter="test">
            <thead>
            <tr>
                <th lay-data="{field:'id', width:50, sort: true,hide: false}">ID</th>
                <th lay-data="{field:'advert_modular_name', width:110,align:'center'}">模块</th>
                <th lay-data="{field:'advert_title', width:110,align:'center'}">活动名称</th>
                <th lay-data="{field:'advert_img', width:200,align:'center', templet: '#imgtmps'}">背景图片</th>
                <th lay-data="{field:'advert_client',width:110,align:'center',templet: '#clientSet'}">所属客户端</th>
                <th lay-data="{field:'advert_source_name',width:100,align:'center'}">活动来源</th>
                <th lay-data="{field:'diy_id',width:100,align:'center'}">活动类型</th>
                <th lay-data="{field:'advert_catgray_name',width:100,align:'center', sort: true}">分类</th>
                <th lay-data="{field:'advert_cat',width:160,align:'center'}">活动</th>
                <th lay-data="{field:'advert_cat_id',width:160,align:'center'}">商品ID</th>
                <th lay-data="{field:'advert_word',width:120,align:'center'}">关键词</th>
                <th lay-data="{field:'advert_amount',width:120,align:'center'}">佣金区间</th>
                <th lay-data="{field:'advert_price',width:120,align:'center'}">价格区间</th>
                <th lay-data="{field:'advert_coupon_name',width:90,align:'center'}">是否有券</th>
                <th lay-data="{field:'advert_switch_name',width:80,align:'center',templet: '#switchTpl'}">状态</th>
                <th lay-data="{fixed: '', width:178,height:300, align:'center', toolbar: '#barDemo'}">操作</th>
            </tr>
            </thead>
        </table>
        <script type="text/html" id="barDemo" >
            <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
            <!--            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>-->
        </script>
        <script type="text/html" id="switchTpl">
            <input type="checkbox" name="advert_switch" value="{{d.id}}_{{d.advert_modular}}" lay-skin="switch" lay-text="开|关" lay-filter="switchTpl" {{ d.advert_switch == 1 ? 'checked' : '' }}>
        </script>
        <script type="text/html" id="clientSet" >
            {{#  if(d.advert_client === 'app'){ }}
            {{ d.advert_client }}
            {{#  } else { }}
            {{ "小程序" }}
            {{#  } }}
        </script>
    </div>
</div>
<script src="__LAYUIADMIN__/layui/layui.all.js"  charset="utf-8"></script>
<script type="text/html" id="imgtmps">
    <img src="{{d.advert_img}}" style="width: 80px;height: 80px;"/>
</script>
<script>
    layui.use('table', function() {
        var table = layui.table
            ,form = layui.form;
        var $ = layui.$;
        table.on('tool(test)', function(obj){
            var data = obj.data;
            // if(obj.event === 'del'){
            //     layer.confirm('确认删除这行么', function(index){
            //         obj.del();
            //         layer.close(index);
            //         let url = '/dmooo.php/System/headAdvertDel?id=' + data.id;
            //         $.post(url,function(res){
            //             if (res.code == 0){
            //                 layer.msg(res.msg);
            //             } else {
            //                 layer.msg(res.msg);
            //             }
            //         });
            //     });
            // } else
            if(obj.event === 'edit'){
                window.location.href="__CONTROLLER__/headAdvertAdd?id="+data.id;
            }
        });
        form.on('switch(switchTpl)', function(obj){
            let url = '/dmooo.php/System/setAdvertChecked';
            $.post(url,{id:this.value,advert_switch:obj.elem.checked},function(res){
                if (res.code !== 'succ') {
                    if (res.code == 'fail'){
                        layer.msg('修改失败，已有相同活动【'+res.msg.id+'】开启,请先关闭此活动');
                    } else {
                        layer.msg('修改失败');
                    }
                }
            },'json');
        });
    })
</script>
</body>
</html>