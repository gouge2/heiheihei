<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .activity_content::-webkit-scrollbar{ 
            display: none;
        }
        .goods_content::-webkit-scrollbar{ 
            display: none;
        }
        .box {
            width: 375px;
            height: 667px;
            background-color: rgba(135, 192, 254, 1);
            border: 1px solid black;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%,-50%);
            
        }
        .head {
            width: 100%;
            color: rgba(51, 51, 51, 1);
            text-align: center;
            font-size: 20px;
            height: 55px;
            line-height: 55px;
            background-color: #fff;
        }

        .img1 {
            width: calc(100% - 20px);
            border-radius: 8px;
            transform: translateX(10px);
            margin-top: 10px;
        }
        .text1_title,.text2_title {
            font-size: 14px;
            color: #333333;
            line-height: 40px;
            font-weight: 600;
            text-align: center;
            height: 40px;
        }
        .text1,.text2 {
            width: calc(100% - 20px);
            margin: 10px auto;
            background-color: #FFFFFF;
            border-radius: 8px;
            padding: 0px 16px 20px 10px;
            box-sizing: border-box;
        }
        .activity_content,.goods_content {
            overflow: auto;
            height: calc(100% - 55px);
            display: none;
        }
        .btn_group {
            width: 100%;
            height: 44px;
            box-sizing: border-box;
            position: fixed;
            bottom: 15px;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            color: #FFFFFF;
            font-size: 14px;
            line-height: 44px;
            text-align: center;
        }
        .coyp_btn {
            background: #FAA442;
            width: 140px;
            height: 44px;
            border-radius: 4px;
            box-shadow: 0px 8px 10px 0px rgba(76, 46, 11, 0.32);
        }
        .save_btn {
            background:#EE1B4A;
            width: 140px;
            height: 44px;
            border-radius: 4px;
            box-shadow: 0px 8px 10px 0px rgba(65, 8, 20, .32);
        }
        .text1_content,.text2_content {
            text-align: left;
        }
        .show {
            display: block;
        }
        .list_item {
            width: calc(100% - 20px);
            height: 140px;
            background: #FFFFFF;
            border-radius: 8px;
            margin: 10px auto;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            box-sizing: border-box;
        }
        .goods_detail {
            width: 203px;
        }
        .goods_title {
            height: 40px;
            line-height: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 600;
            color: #333333;
            font-size: 16px;
            
        }

        .goods_price {
            display: flex;
            width: 190px;
            height: 40px;
        }
        .left {
            width: 73px;
        }
        .noraml_price {
            font-size: 12px;
            color: #999999;
            text-decoration: line-through;
        }
        .prcie_text {
            color: #EE1B4A;
            font-size: 12px;
        }
        .right {
            margin-left: 20px;
            font-size: 27px;
            font-weight: bold;
            color: #EE1B4A;

        }
        .goods_btn {
            width: 90px;
            height: 30px;
            background: #EE1B4A;
            border-radius: 15px;
            text-align: center;
            line-height: 30px;
            font-size: 12px;
            color: #FFFFFF;
            margin-top: 10px;
        }
        .item_img {
        width: 120px;
        height: 120px;
        border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="head">标题</div>
        <div class="activity_content" >
            <img src="https://tao.lailu.live/Public/Upload/Banner/2020-08-28/5f486c04631d0771.jpg" class="img1"  />
            <div class="text1">
                <div class="text1_title">
                    活动介绍
                </div>
                
                <div class="text1_content">
                    <p>
                        <span>【超级品牌日】包个白处今即自达持目教治省水克意县阶再称上花增的严基文七华清型者民系体活都水口&nbsp;</span>
                    </p>
                    <p>
                        <span>1.求段米行线南置可亲什场各那图争叫商何级把更表好提与。一例分下员低以。&nbsp;</span>
                    </p>
                    <p>
                        <span>2.求段米行线南置可亲什场各那图争叫商何级把更表好提与。一例分下员低以。</span>
                    </p>
                    <p>
                        <br/>
                    </p>
                </div>
            </div>
            <div class="text2">
                <div class="text2_title">
                    活动文案
                </div>
                <div class="text2_content">
                    <p>
                        【都市丽人集团闪购大牌日】<br/>部分低至4.4折！<br/>每满299减50！<br/>入口https://u.jd.com/b74YZ3<br/><br/>【买2免1】2套女士保暖内衣套装到手价仅79元&nbsp;，最后一天哦~<br/>促销优惠勾选&nbsp;满2件，价格最低1件免费<br/>&nbsp;&nbsp;https://u.jd.com/avkjFi<br/>【3件6折叠券】<br/>到手价约53.4元得情侣加绒加厚保暖套装<br/>https://u.jd.com/bsSTJi<br/>【立减30】到手价89元一套女士家居服睡衣套装柔软亲肤，限时抢购<br/>https://u.jd.com/CtkuLe<img src="http://img.baidu.com/hi/jx2/j_0019.gif"/>
                    </p>
                </div>
            </div>
            <image src="https://tao.lailu.live/Public/Upload/Banner/2020-10-20/5f8e827f22e6d593.jpg" class="img1" mode='widthFix'></image>
            <div class="btn_group">
                <div class="coyp_btn" >复制文案</div>
                <div class="save_btn" >保存图片</div>
            </div>
        </div>
        <div class="goods_content">
        </div>
    </div>
</body>
<script>
    let main_img   = '{$data.main_img}',
        pattern    = '{$data.pattern}',
        title      = '{$data.title}',
        g_det      = '{$data.g_det}',
        a_det      = '{$data.a_det}',
        share_img  = '{$data.a_det.share_img}'
        introduce  = '{$data.a_det.introduce}'
        copywriter = '{$data.a_det.copywriter}'
        g_det      = g_det && JSON.parse(g_det)

    function showGoods ({ main_img, g_det }) {
        document.querySelector('.goods_content').classList.add('show')
        let imgList = document.querySelectorAll('.img1')
        // imgList[2].setAttribute('src', main_img)
        let html = `<img  class="img1" src="${main_img}" />`
        g_det.goods_list.map(e => {
            html += `
            <div class="list_item">
                <img src="${e.img}" class="item_img" />
                <div class="goods_detail">
                    <div class="goods_title" style="color:${g_det.name_color}">${e.goods_name}</div>
                    <div class="goods_price">
                        <div class="left">
                            <div class="noraml_price">原价:${e.old_price}</div>
                            <div class="prcie_text" style="color:${g_det.price_color}">内购价: ￥</div>
                        </div>
                        <div class="right" style="color:${g_det.price_color}">
                        ${e.price}
                        </div>
                    </div>
                    <div class="goods_btn" style="background:${g_det.button_color};color:${g_det.button_font_color}">
                    立即购买
                    </div>
                </div>
            </div> ` 
        })
        document.querySelector('.goods_content').innerHTML = html
        document.querySelector('.box').style.background = g_det.bg_color

    }

    function showActivity ({ main_img, a_det }) {
        document.querySelector('.activity_content').classList.add('show')
        let imgList = document.querySelectorAll('.img1')
        imgList[0].setAttribute('src', main_img)
        imgList[1].setAttribute('src', share_img)
        document.querySelector('.text1_content').innerHTML = introduce
        document.querySelector('.text2_content').innerHTML = copywriter
    }
    

    // let http = new Promise((resolve) => {
    //     let xhr = new XMLHttpRequest()
    //     xhr.open('POST',"https://tao.lailu.live/app.php?c=Banner&a=getAppletDet")
    //     xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded')
    //     xhr.onreadystatechange = function () {
    //         xhr.readyState==4 && xhr.status==200 &&resolve(xhr.responseText)
    //     }
        
    //     xhr.send(`banner_id=${id}`)  
    // })

    // http.then(function(res){
        // })
    // let {main_img, pattern, title, g_det, a_det} = JSON.parse(res).data.detail
    document.querySelector('.head').innerHTML = title
    pattern == "goods" ? showGoods({ g_det, main_img,  }) : showActivity({ a_det, main_img })

</script>
</html>