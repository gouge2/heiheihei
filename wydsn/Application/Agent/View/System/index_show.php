<link rel="stylesheet" type="text/css" href="__ADMIN_CSS__/index.css">
<link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
<link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
<link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="__ADMIN_JS__/html5shiv.min.js"></script>
<script src="__ADMIN_JS__/respond.min.js"></script>
<![endif]-->

<section class="rt_wrap content">
    <div class="container-fluid">
        <!--主体内容开始-->
        <div class="c_totalmember">
            <div class="c_lan1">
                <h3>会员数据统计</h3>
            </div>
            <div class="c_totalmemberbox">
                <a class="c_totalmember_item c_xianright">
                    <dl>
                        <dt>
                            <!--                            <img src="__ADMIN_IMG__/c_icon1.png">-->
                            <i class="layui-icon layui-icon-user" style="font-size: 30px;"></i>
                        </dt>
                        <dd>
                            <p>会员数量</p>
                            <span>{$user_allnum}<small>人</small></span>
                        </dd>
                    </dl>
                </a> <a class="c_totalmember_item c_xianright">
                    <dl>
                        <dt>
                            <!--                            <img src="__ADMIN_IMG__/c_icon2.png">-->
                            <i class="layui-icon layui-icon-username" style="font-size: 30px;"></i>
                        </dt>
                        <dd>
                            <p>普通会员</p>
                            <span>{$user_num1}<small>人</small></span>
                        </dd>
                    </dl>
                </a> <a class="c_totalmember_item c_xianright">
                    <dl>
                        <dt>
                            <!--                            <img src="__ADMIN_IMG__/c_icon3.png">-->
                            <i class="layui-icon layui-icon-senior" style="font-size: 30px;"></i>
                        </dt>
                        <dd>
                            <p>VIP会员</p>
                            <span>{$user_vipnum}<small>人</small></span>
                        </dd>
                    </dl>
                </a> <a class="c_totalmember_item c_xianright">
                    <dl>
                        <dt>
                            <!--                            <img src="__ADMIN_IMG__/c_icon4.png">-->
                            <i class="layui-icon layui-icon-layer" style="font-size: 30px;"></i>
                        </dt>
                        <dd>
                            <p>今日新增</p>
                            <span>{$user_today_num}<small>人</small></span>
                        </dd>
                    </dl>
                </a> <a class="c_totalmember_item c_xianright">
                    <dl>
                        <dt>
                            <!--                            <img src="__ADMIN_IMG__/c_icon5.png">-->
                            <i class="layui-icon layui-icon-date" style="font-size: 30px;"></i>
                        </dt>
                        <dd>
                            <p>本月新增</p>
                            <span>{$user_month_num}<small>人</small></span>
                        </dd>
                    </dl>
                </a>
                <div class="clear"></div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="c_databox">
            <div class="row">
                <!--S -->
                <div class="col-xs-4" style="width: 25%">
                    <div class="c_databox_item1">
                        <div class="c_lan1">
                            <h3>淘宝订单</h3>
                        </div>
                        <div class="c_databox_item1con">
                            <ul>
                                <li class="c_databox_item1_color1">
                                    <a href="__MODULE__/TbOrder/index">
                                        <p>{$tb_order_finished_num}</p>
                                        <span>已结算订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color2">
                                    <a href="__MODULE__/TbOrder/index">
                                        <p>{$tb_order_pay_num}</p>
                                        <span>已付款订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color3">
                                    <a href="__MODULE__/TbOrder/index">
                                        <p>{$tb_order_today_num}</p>
                                        <span>今日订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color4">
                                    <a href="__MODULE__/TbOrder/index">
                                        <p>{$tb_order_month_num}</p>
                                        <span>本月订单</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <!--E -->
                <!--S -->
                <div class="col-xs-4" style="width: 25%">
                    <div class="c_databox_item1">
                        <div class="c_lan1">
                            <h3>拼多多订单</h3>
                        </div>
                        <div class="c_databox_item1con">
                            <ul>
                                <li class="c_databox_item1_color1">
                                    <a href="__MODULE__/PddOrder/index">
                                        <p>{$pdd_order_finished_num}</p>
                                        <span>已结算订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color2">
                                    <a href="__MODULE__/PddOrder/index">
                                        <p>{$pdd_order_pay_num}</p>
                                        <span>已付款订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color3">
                                    <a href="__MODULE__/PddOrder/index">
                                        <p>{$pdd_order_today_num}</p>
                                        <span>今日订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color4">
                                    <a href="__MODULE__/PddOrder/index">
                                        <p>{$pdd_order_month_num}</p>
                                        <span>本月订单</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <!--E -->
                <!--S -->
                <div class="col-xs-4" style="width: 25%">
                    <div class="c_databox_item1">
                        <div class="c_lan1">
                            <h3>京东订单</h3>
                        </div>
                        <div class="c_databox_item1con">
                            <ul>
                                <li class="c_databox_item1_color1">
                                    <a href="__MODULE__/JingdongOrder/index">
                                        <p>{$jd_order_finished_num}</p>
                                        <span>已结算订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color2">
                                    <a href="__MODULE__/JingdongOrder/index">
                                        <p>{$jd_order_pay_num}</p>
                                        <span>已付款订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color3">
                                    <a href="__MODULE__/JingdongOrder/index">
                                        <p>{$jd_order_today_num}</p>
                                        <span>今日订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color4">
                                    <a href="__MODULE__/JingdongOrder/index">
                                        <p>{$jd_order_month_num}</p>
                                        <span>本月订单</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <!--E -->
                <!--S -->
                <div class="col-xs-4" style="width: 25%">
                    <div class="c_databox_item1">
                        <div class="c_lan1">
                            <h3>唯品会订单</h3>
                        </div>
                        <div class="c_databox_item1con">
                            <ul>
                                <li class="c_databox_item1_color1">
                                    <a href="__MODULE__/VipOrder/index">
                                        <p>{$vip_order_finished_num}</p>
                                        <span>已结算订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color2">
                                    <a href="__MODULE__/VipOrder/index">
                                        <p>{$vip_order_pay_num}</p>
                                        <span>已付款订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color3">
                                    <a href="__MODULE__/VipOrder/index">
                                        <p>{$vip_order_today_num}</p>
                                        <span>今日订单</span>
                                    </a>
                                </li>
                                <li class="c_databox_item1_color4">
                                    <a href="__MODULE__/VipOrder/index">
                                        <p>{$vip_order_month_num}</p>
                                        <span>本月订单</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <!--E -->
            </div>
        </div>
        <div class="clear"></div>
        <div class="c_databoxdetail" style="margin-bottom:50px">
            <div class="row">
                <!--S -->
                <div class="col-xs-8">
                    <!--
                    <div class="c_lan2">
                        <h3>
                            淘宝订单数据 <a class="cat_color1">本月</a> <a class="cat_color2">上月</a>
                        </h3>
                    </div>
                     -->
                    <div class="linechart">
                        <!-- 引入 echarts.js -->
                        <script src="__JS__/echarts.min.js"></script>
                        <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                        <div id="ECharts" style="width: 100%; height: 255px;margin-top:70px"></div>
                        <script type="text/javascript">
                            // 基于准备好的dom，初始化echarts实例
                            var myChart = echarts.init(document.getElementById('ECharts'));

                            // 指定图表的配置项和数据
                            var option = {
                                color: ['#009688'],
                                title: {
                                    text: '淘宝近一月订单数据'
                                },
                                tooltip: {},
                                legend: {
                                    data: ['订单数']
                                },
                                xAxis: {
                                    data: [
                                        <?php
                                        foreach ($list as $l) {
                                            //日期
                                            $day = substr($l['date'], 5);
                                            echo "'" . $day . "',";
                                        }
                                        ?>
                                    ]
                                },
                                yAxis: {},
                                series: [{
                                    name: '订单数',
                                    type: 'bar',
                                    data: [
                                        <?php
                                        foreach ($list as $l) {
                                            echo $l['num'] . ',';
                                        }
                                        ?>
                                    ]
                                }]
                            };

                            // 使用刚指定的配置项和数据显示图表。
                            myChart.setOption(option);
                        </script>
                        <!-- <img src="__ADMIN_IMG__/c_img.jpg" style="width: 100%; height: 252px;" /> -->
                    </div>
                </div>
                <br><br><br><br><br>
                <!--E -->
                <!--S -->
                <!--                <div class="col-xs-4">-->
                <!--                    <div class="cdetailbox">-->
                <!--                        <div class="cdetail_title">累计收入</div>-->
                <!--                        <div class="cdetail_price">￥{$amount}</div>-->
                <!--                        <div class="cdetail_list">-->
                <!--                            <ul>-->
                <!--                                <li>-->
                <!--                                    <p>今日收入</p> <span>${$amount_today}</span>-->
                <!--                                </li>-->
                <!--                                <li>-->
                <!--                                    <p>本月收入</p> <span>${$amount_month}</span>-->
                <!--                                </li>-->
                <!--                            </ul>-->
                <!--                        </div>-->
                <!--                        <div class="clear"></div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="layui-col-md4">
                    <div class="layui-card">
                        <div class="layui-card-header">收入统计</div>
                        <div class="layui-card-body">
                            <div carousel-item>
                                <ul class="layui-row layui-col-space10">
                                    <li class="layui-col-xs6">
                                        <a lay-href="app/content/comment.html" class="layadmin-backlog-body">
                                            <h3>累计收入</h3>
                                            <p><cite>￥{$amount}</cite></p>
                                        </a>
                                    </li>
                                    <br><br><br><br>
                                    <li class="layui-col-xs6">
                                        <a lay-href="app/content/comment.html" class="layadmin-backlog-body">
                                            <h3>今日收入</h3>
                                            <p><cite>￥{$amount_today}</cite></p>
                                        </a>
                                    </li>
                                    <li class="layui-col-xs6">
                                        <a lay-href="app/forum/list.html" class="layadmin-backlog-body">
                                            <h3>本月收入</h3>
                                            <p><cite>￥{$amount_month}</cite></p>
                                        </a>
                                    </li>
                                    <br><br><br><br>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!--E -->
            </div>
        </div>
        <!--主体内容结束-->
    </div>

</section>
