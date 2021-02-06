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
<style>
    .indexShow {
        width: 100%;
        /* height: 100%; */
        background: #F0F3F8;
        padding: 1.81% 3.26%;
    }

    .indexshow_title {
        width: 144px;
        height: 30px;
        font-size: 24px;
        font-weight: 600;
        color: rgba(7, 15, 41, 1);
        line-height: 30px;
    }

    .indexShow_header {
        background: rgba(255, 255, 255, 1);
        border-radius: 8px;
        padding: 1.67% 3.33%;
        margin-top: 1.6%;
        display: flex;
        align-items: center;
        justify-content: center;

    }

    .indexShow_headerItem {
        flex: 1;
        display: flex;
        align-items: center;
    }

    .indexShow_headerItem_icon {
        width: 3vw;
        height: 3vw;
        margin-right: 12px;
    }

    .ish_tip {
        font-size: 16px;
        font-weight: 400;
        color: rgba(110, 125, 138, 1);
        line-height: 22px;
        padding-top: 12px;
    }

    .ish_num {
        font-size: 24px;
        font-weight: bold;
        color: rgba(7, 15, 41, 1);
        line-height: 28px;
    }

    .ish_num_unit {
        font-size: 16px;
        font-weight: 400;
        color: rgba(7, 15, 41, 1);
        line-height: 22px;
    }

    .is_content {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px;
    }

    .isc_item1 {
        width: 24%;
        height: 12.5vw;
        background: linear-gradient(311deg, rgba(83, 77, 243, 1) 0%, rgba(151, 106, 243, 1) 100%);
        border-radius: 8px;
        overflow: hidden;
    }

    .isc_item2 {
        width: 24%;
        height: 12.5vw;
        background: linear-gradient(311deg, rgba(237, 156, 83, 1) 0%, rgba(252, 127, 125, 1) 100%);
        box-shadow: 0px 2px 5px 0px rgba(83, 97, 255, 0.05);
        border-radius: 8px;
    }

    .isc_item3 {
        width: 24%;
        height: 12.5vw;
        background: linear-gradient(135deg, rgba(247, 146, 159, 1) 0%, rgba(133, 87, 226, 1) 100%);
        box-shadow: 0px 2px 5px 0px rgba(83, 97, 255, 0.05);
        border-radius: 8px;
    }

    .isc_item4 {
        width: 24%;
        height: 12.5vw;
        background: linear-gradient(134deg, rgba(45, 202, 177, 1) 0%, rgba(46, 160, 224, 1) 100%);
        box-shadow: 0px 2px 5px 0px rgba(83, 97, 255, 0.05);
        border-radius: 8px;
    }

    .isc_line {
        widows: 100%;
        height: 2px;
        background: #DDE1EE;
    }

    .isc_hesder {
        padding: 10px 20px;
        display: flex;
        align-items: center;
    }

    .isc_hesder_icon {
        width: 3vw;
        height: 3vw;
        margin-right: 15px;
    }

    .isc_hesder_text {
        font-size: 18px;
        font-weight: 600;
        color: rgba(255, 255, 255, 1);
    }

    .isc_content {
        width: 100%;
        height: calc(12.5vw - 30px - 3vw);
        padding: 10px 20px;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .iscc_item {
        width: 50%;
        height: 50%;
    }

    .iscc_num {
        font-size: 24px;
        font-weight: bold;
        color: rgba(255, 255, 255, 1);
        line-height: 24px;
        padding-left: 12px;
    }

    .iscc_text {
        font-size: 14px;
        font-weight: 400;
        color: rgba(255, 255, 255, 1);
    }
    .dot0 {
        width:6px;
        height:6px;
        background:rgba(90,176,32,1);
        margin-right: 5px;
        display: inline-block;
        border-radius: 50%;
    }

    .dot1 {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(90, 176, 32, 1);
        border: 1px solid rgba(255, 255, 255, 1);
        margin-left: 5px;
        display: inline-block;
    }

    .dot2 {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255, 153, 2, 1);
        border: 1px solid rgba(255, 255, 255, 1);
        margin-left: 5px;
        display: inline-block;
    }

    .dot3 {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255, 102, 102, 1);
        border: 1px solid rgba(255, 255, 255, 1);
        margin-left: 5px;
        display: inline-block;
    }

    .dot4 {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(102, 185, 255, 1);
        border: 1px solid rgba(255, 255, 255, 1);
        margin-left: 5px;
        display: inline-block;
    }

    .is_echart {
        width: 100%;
        margin-top: 55px;

    }

    .ise_header {
        width: 100%;
        font-size: 24px;
        font-weight: 600;
        color: rgba(51, 61, 95, 1);
        line-height: 24px;
        margin-bottom: 26px;
    }

    .ise_conetnt {
        width: 100%;
        height: 360px;
        position: relative;
        display: flex;
        align-items: center;

    }

    .ise_left {
        width: calc(100% - 158px);
        height: 100%;
        /* background:rgba(255,255,255,1); */
        margin-right: 17px;
    }

    .ise_right {
        width: 142px;
        height: 100%;
        background: rgba(255, 255, 255, 1);
    }

    .ise_right_header {
        font-size: 18px;
        font-weight: 600;
        color: rgba(7, 15, 41, 1);
        padding: 17px;
        background: rgba(229, 231, 248, 1);
    }

    .ise_right_content{
        width: 100%;
        height: calc(100% - 52px);
        /* background: #000; */
        padding: 19px;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
    .iserc_item{
        margin-bottom: 26px;
    }
    .iserc_item_num{
        padding-left: 8px;
        font-size:22px;
        font-weight:bold;
        color:rgba(7,15,41,1);
        line-height:26px;
        margin-bottom: 5px;
    }
    .iserc_item_txt{
        height:20px;
        font-size:14px;
        font-weight:400;
        color:rgba(110,125,138,1);
        line-height:20px;
    }
</style>

<section class="rt_wrap content">
    <div class="container-fluid indexShow">
        <div class="indexshow_title">
            会员数据统计
        </div>
        <div class="indexShow_header">
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show1.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$user_allnum}</span>
                        <span class="ish_num_unit">人</span>
                    </div>
                    <div class="ish_tip">
                        会员数量
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show2.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$user_num1}</span>
                        <span class="ish_num_unit">人</span>
                    </div>
                    <div class="ish_tip">
                        普通会员
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show3.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$user_vipnum}</span>
                        <span class="ish_num_unit">人</span>
                    </div>
                    <div class="ish_tip">
                        VIP会员
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show4.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$user_today_num}</span>
                        <span class="ish_num_unit">人</span>
                    </div>
                    <div class="ish_tip">
                        今日新增
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show5.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$user_month_num}</span>
                        <span class="ish_num_unit">人</span>
                    </div>
                    <div class="ish_tip">
                        本月新增
                    </div>
                </div>
            </div>
        </div>
        <div class="indexShow_header" style="margin-top: 16px;" >
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show6.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$all_order_money}</span>
                        <span class="ish_num_unit">元</span>
                    </div>
                    <div class="ish_tip">
                        营业额
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show7.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$amount}</span>
                        <span class="ish_num_unit">元</span>
                    </div>
                    <div class="ish_tip">
                        总收入
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show8.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$all_draw}</span>
                        <span class="ish_num_unit">元</span>
                    </div>
                    <div class="ish_tip">
                        总支出
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show9.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$amount_today}</span>
                        <span class="ish_num_unit">元</span>
                    </div>
                    <div class="ish_tip">
                        今日收入
                    </div>
                </div>
            </div>
            <div class="indexShow_headerItem">
                <img src="__ADMIN_IMG__/show10.png" alt="" class="indexShow_headerItem_icon">
                <div>
                    <div>
                        <span class="ish_num">{$all_draw_today}</span>
                        <span class="ish_num_unit">元</span>
                    </div>
                    <div class="ish_tip">
                        今日支出
                    </div>
                </div>
            </div>
        </div>
        <div class="is_content">
            <div class="isc_item4">
                <div class="isc_hesder">
                    <img class="isc_hesder_icon" src="/Public/static/admin/img/logo.png" alt="" style="border-radius: 25px;">
                    <span class="isc_hesder_text">自营订单</span>
                </div>
                <div class="isc_line"></div>
                <div class="isc_content">
                    <div class="iscc_item">
                        <div class="iscc_num">{$self_order_finished_num}</div>
                        <div class="iscc_text"><span class="dot1"></span> 已结算订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$self_order_pay_num}</div>
                        <div class="iscc_text"><span class="dot2"></span> 已付款订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$self_order_today_num}</div>
                        <div class="iscc_text"><span class="dot3"></span> 今日订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$self_order_month_num}</div>
                        <div class="iscc_text"><span class="dot4"></span> 本月订单</div>
                    </div>
                </div>
            </div>
            <div class="isc_item1">
                <div class="isc_hesder">
                    <img class="isc_hesder_icon" src="__ADMIN_IMG__/tb.png" alt="">
                    <span class="isc_hesder_text">淘宝订单</span>
                </div>
                <div class="isc_line"></div>
                <div class="isc_content">
                    <div class="iscc_item">
                        <div class="iscc_num">{$tb_order_finished_num}</div>
                        <div class="iscc_text"><span class="dot1"></span> 已结算订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$tb_order_pay_num}</div>
                        <div class="iscc_text"><span class="dot2"></span> 已付款订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$tb_order_today_num}</div>
                        <div class="iscc_text"><span class="dot3"></span> 今日订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$tb_order_month_num}</div>
                        <div class="iscc_text"><span class="dot4"></span> 本月订单</div>
                    </div>
                </div>
            </div>
            <div class="isc_item2">
                <div class="isc_hesder">
                    <img class="isc_hesder_icon" src="__ADMIN_IMG__/pdd.png" alt="">
                    <span class="isc_hesder_text">拼多多订单</span>
                </div>
                <div class="isc_line"></div>
                <div class="isc_content">
                    <div class="iscc_item">
                        <div class="iscc_num">{$pdd_order_finished_num}</div>
                        <div class="iscc_text"><span class="dot1"></span> 已结算订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$pdd_order_pay_num}</div>
                        <div class="iscc_text"><span class="dot2"></span> 已付款订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$pdd_order_today_num}</div>
                        <div class="iscc_text"><span class="dot3"></span> 今日订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$pdd_order_month_num}</div>
                        <div class="iscc_text"><span class="dot4"></span> 本月订单</div>
                    </div>
                </div>
            </div>
            <div class="isc_item3">
                <div class="isc_hesder">
                    <img class="isc_hesder_icon" src="__ADMIN_IMG__/jd.png" alt="">
                    <span class="isc_hesder_text">京东订单</span>
                </div>
                <div class="isc_line"></div>
                <div class="isc_content">
                    <div class="iscc_item">
                        <div class="iscc_num">{$jd_order_finished_num}</div>
                        <div class="iscc_text"><span class="dot1"></span> 已结算订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$jd_order_pay_num}</div>
                        <div class="iscc_text"><span class="dot2"></span> 已付款订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$jd_order_today_num}</div>
                        <div class="iscc_text"><span class="dot3"></span> 今日订单</div>
                    </div>
                    <div class="iscc_item">
                        <div class="iscc_num">{$jd_order_month_num}</div>
                        <div class="iscc_text"><span class="dot4"></span> 本月订单</div>
                    </div>
                </div>
            </div>
<!--            <div class="isc_item4">-->
<!--                <div class="isc_hesder">-->
<!--                    <img class="isc_hesder_icon" src="__ADMIN_IMG__/vip.png" alt="">-->
<!--                    <span class="isc_hesder_text">唯品会订单</span>-->
<!--                </div>-->
<!--                <div class="isc_line"></div>-->
<!--                <div class="isc_content">-->
<!--                    <div class="iscc_item">-->
<!--                        <div class="iscc_num">{$vip_order_finished_num}</div>-->
<!--                        <div class="iscc_text"><span class="dot1"></span> 已结算订单</div>-->
<!--                    </div>-->
<!--                    <div class="iscc_item">-->
<!--                        <div class="iscc_num">{$vip_order_pay_num}</div>-->
<!--                        <div class="iscc_text"><span class="dot2"></span> 已付款订单</div>-->
<!--                    </div>-->
<!--                    <div class="iscc_item">-->
<!--                        <div class="iscc_num">{$vip_order_today_num}</div>-->
<!--                        <div class="iscc_text"><span class="dot3"></span> 今日订单</div>-->
<!--                    </div>-->
<!--                    <div class="iscc_item">-->
<!--                        <div class="iscc_num">{$vip_order_month_num}</div>-->
<!--                        <div class="iscc_text"><span class="dot4"></span> 本月订单</div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
        <div class="is_echart">
            <div class="ise_header">淘宝近1月订单数据</div>
            <div class="ise_conetnt">
                <div class="ise_left">
                    <script src="__JS__/echarts.min.js"></script>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="ECharts" style="width: 100%; height: 100%"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('ECharts'));

                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                // text: '堆叠区域图'
                            },
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    label: {
                                        backgroundColor: '#6a7985'
                                    }
                                }
                            },
                            legend: {
                                data: ['订单数']
                            },
                            toolbox: {
                                feature: {
                                    // saveAsImage: {}
                                }
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [
                                {
                                    type: 'category',

                                    boundaryGap: false,
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false,

                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },
                                    data: [
                                        <?php
                                        foreach ($tb_list as $l) {
                                            //日期
                                            $day = substr($l['date'], 5);
                                            echo "'" . $day . "',";
                                        }
                                        ?>
                                    ]
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false
                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },

                                }
                            ],
                            series: [
                                {

                                    type: 'line',
                                    stack: '总量',
                                    // smooth: true,
                                    label: {
                                        normal: {
                                            show: true,
                                            position: 'top'
                                        }
                                    },
                                    itemStyle : {
                                        normal : {
                                            color:'#5664FF',
                                            borderWidth:'3',
                                            shadowColor:new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                                { offset: 1, color: "#D4C9FA" },
                                                { offset: 0, color: "#A27DFF" }
                                            ]),
                                            lineStyle:{
                                                color:'#746FFE'
                                            }
                                        }
                                    },
                                    areaStyle: {
                                        color:  new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                            { offset: 1, color: "#D4C9FA" },
                                            { offset: 0, color: "#A27DFF" }
                                        ])
                                    },
                                    data: [<?php
                                        foreach ($tb_list as $l) {
                                            echo $l['num'] . ',';
                                        }
                                        ?>
                                    ]
                                }
                            ]
                        };


                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="ise_right">
                    <div class="ise_right_header">收入统计</div>
                    <div class="ise_right_content">
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_tb}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>累计收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_tb_today}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>今日收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_tb_month}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>本月收入
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="is_echart">
            <div class="ise_header">拼多多近1月订单数据</div>
            <div class="ise_conetnt">
                <div class="ise_left">
                    <script src="__JS__/echarts.min.js"></script>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="ECharts1" style="width: 100%; height: 100%"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('ECharts1'));

                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                // text: '堆叠区域图'
                            },
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    label: {
                                        backgroundColor: '#6a7985'
                                    }
                                }
                            },
                            legend: {
                                data: ['订单数']
                            },
                            toolbox: {
                                feature: {
                                    // saveAsImage: {}
                                }
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [
                                {
                                    type: 'category',

                                    boundaryGap: false,
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false,

                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },
                                    data: [
                                        <?php
                                        foreach ($pdd_list as $l) {
                                            //日期
                                            $day = substr($l['date'], 5);
                                            echo "'" . $day . "',";
                                        }
                                        ?>
                                    ]
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false
                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },

                                }
                            ],
                            series: [
                                {

                                    type: 'line',
                                    stack: '总量',
                                    // smooth: true,
                                    label: {
                                        normal: {
                                            show: true,
                                            position: 'top'
                                        }
                                    },
                                    itemStyle : {
                                        normal : {
                                            color:'#F58C69',
                                            borderWidth:'3',
                                            shadowColor:new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                                { offset: 1, color: "#F68C6A" },
                                                { offset: 0, color: "#A27DFF" }
                                            ]),
                                            lineStyle:{
                                                color:'#F58C69'
                                            }
                                        }
                                    },
                                    areaStyle: {
                                        color:  new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                            { offset: 1, color: "#F0F0F2" },
                                            { offset: 0, color: "#F2CEC5" }
                                        ])
                                    },
                                    data: [
                                        <?php
                                        foreach ($pdd_list as $l) {
                                            echo $l['num'] . ',';
                                        }
                                        ?>
                                    ]
                                }
                            ]
                        };


                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="ise_right">
                    <div class="ise_right_header">收入统计</div>
                    <div class="ise_right_content">
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_pdd}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>累计收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_pdd_today}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>今日收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_pdd_month}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>本月收入
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="is_echart">
            <div class="ise_header">京东近1月订单数据</div>
            <div class="ise_conetnt">
                <div class="ise_left">
                    <script src="__JS__/echarts.min.js"></script>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="ECharts2" style="width: 100%; height: 100%"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('ECharts2'));

                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                // text: '堆叠区域图'
                            },
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    label: {
                                        backgroundColor: '#6a7985'
                                    }
                                }
                            },
                            legend: {
                                data: ['订单数']
                            },
                            toolbox: {
                                feature: {
                                    // saveAsImage: {}
                                }
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [
                                {
                                    type: 'category',

                                    boundaryGap: false,
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false,

                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },
                                    data: [
                                        <?php
                                        foreach ($jd_list as $l) {
                                            //日期
                                            $day = substr($l['date'], 5);
                                            echo "'" . $day . "',";
                                        }
                                        ?>
                                    ]
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false
                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },

                                }
                            ],
                            series: [
                                {

                                    type: 'line',
                                    stack: '总量',
                                    // smooth: true,
                                    label: {
                                        normal: {
                                            show: true,
                                            position: 'top'
                                        }
                                    },
                                    itemStyle : {
                                        normal : {
                                            color:'#C97AB9',
                                            borderWidth:'3',
                                            shadowColor:new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                                { offset: 1, color: "#F68C6A" },
                                                { offset: 0, color: "#A27DFF" }
                                            ]),
                                            lineStyle:{
                                                color:'#C97AB9'
                                            }
                                        }
                                    },
                                    areaStyle: {
                                        color:  new echarts.graphic.LinearGradient(0, 0, 0, 1, [

                                            { offset: 1, color: "#E2C8E2" },
                                            { offset: 0, color: "#B771C4" },
                                        ])
                                    },
                                    data: [
                                        <?php
                                        foreach ($jd_list as $l) {
                                            echo $l['num'] . ',';
                                        }
                                        ?>
                                    ]
                                }
                            ]
                        };


                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="ise_right">
                    <div class="ise_right_header">收入统计</div>
                    <div class="ise_right_content">
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_jd}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>累计收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_jd_today}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>今日收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_jd_month}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>本月收入
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="is_echart">
            <div class="ise_header">自营近1月订单数据</div>
            <div class="ise_conetnt">
                <div class="ise_left">
                    <script src="__JS__/echarts.min.js"></script>
                    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
                    <div id="ECharts3" style="width: 100%; height: 100%"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('ECharts3'));

                        // 指定图表的配置项和数据
                        var option = {
                            title: {
                                // text: '堆叠区域图'
                            },
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    label: {
                                        backgroundColor: '#6a7985'
                                    }
                                }
                            },
                            legend: {
                                data: ['订单数']
                            },
                            toolbox: {
                                feature: {
                                    // saveAsImage: {}
                                }
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: [
                                {
                                    type: 'category',

                                    boundaryGap: false,
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false,

                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },
                                    data: [
                                        <?php
                                        foreach ($self_list as $l) {
                                            //日期
                                            $day = substr($l['date'], 5);
                                            echo "'" . $day . "',";
                                        }
                                        ?>
                                    ]
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisLine:{
                                        show:false
                                    },
                                    axisTick:{
                                        show:false
                                    },
                                    axisLine:{
                                        lineStyle:{
                                            color:'#9EA5BD',
                                            width:0
                                        },
                                    },

                                }
                            ],
                            series: [
                                {

                                    type: 'line',
                                    stack: '总量',
                                    // smooth: true,
                                    label: {
                                        normal: {
                                            show: true,
                                            position: 'top'
                                        }
                                    },
                                    itemStyle : {
                                        normal : {
                                            color:'#2EBCC1',
                                            borderWidth:'3',
                                            shadowColor:new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                                { offset: 1, color: "#F68C6A" },
                                                { offset: 0, color: "#A27DFF" }
                                            ]),
                                            lineStyle:{
                                                color:'#2EBCC1'
                                            }
                                        }
                                    },
                                    areaStyle: {
                                        color:  new echarts.graphic.LinearGradient(0, 0, 0, 1, [

                                            { offset: 1, color: "#ABE0E4" },
                                            { offset: 0, color: "#53FFED" },
                                        ])
                                    },
                                    data: [
                                        <?php
                                        foreach ($self_list as $l) {
                                            echo $l['num'] . ',';
                                        }
                                        ?>
                                    ]
                                }
                            ]
                        };


                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="ise_right">
                    <div class="ise_right_header">收入统计</div>
                    <div class="ise_right_content">
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_vip}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>累计收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_vip_today}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>今日收入
                            </div>
                        </div>
                        <div class="iserc_item">
                            <div class="iserc_item_num">
                                ¥{$amount_vip_month}
                            </div>
                            <div class="iserc_item_txt">
                                <span class='dot0'></span>本月收入
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</section>
