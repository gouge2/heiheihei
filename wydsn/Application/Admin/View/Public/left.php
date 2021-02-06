<link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
<style>
	.layui-side::-webkit-scrollbar{
		display: none;
	}
	 .layui-nav-tree .layui-this, .layui-nav-tree .layui-this>a, .layui-nav-tree .layui-this>a:hover {
		background-color: orange !important;
	}
	.layui-nav-tree .layui-nav-bar{
		background-color: orange !important;
	}
</style>
<nav class="layui-side layui-side-menu" id='mydiynav'>
	<!-- <div class="nav-close">
		<i class="fa fa-times-circle"></i>
	</div> -->
	<li class="nav-header layui-side-menu" style="text-align: center">
		<div class="dropdown profile-element">
			<span><img alt="image" class="img-circle" src="__ADMIN_IMG__/logo.png" width="72" /></span>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
				<span class="clear">
					<span class="block m-t-xs"><strong class="font-bold"><?php echo WEB_TITLE;?></strong></span>
				</span>
			</a>
		</div>
		<div class="logo-element">后台</div>
	</li>
	<ul class="layui-nav layui-nav-tree layui-bg-orange" lay-filter="test">

		<div class="layui-side" style="margin-top: 162px;">
			<li class="layui-nav-item ">
				<a href="__MODULE__/System/index">
					<i class="layui-icon layui-icon-home"></i>
					<span class="nav-label">主页</span>
				</a>
			</li>
            <li class="layui-nav-item ">
                <a href="__MODULE__/ActivityBoard/index" target="_blank">
                    <i class="layui-icon layui-icon-release"></i>
                    <span class="nav-label">大数据面板</span>
                </a>
            </li>
			<li class="layui-nav-item ">
				<a href="#">
					<i class="layui-icon layui-icon-component"></i>
					<span class="nav-label">首页DIY设置</span>
				</a>
				<dl class="layui-nav-child">
					<dd><a class="J_menuItem" href="__MODULE__/DiyModule/index">功能模块管理</a></dd>
                    <dd><a class="J_menuItem" href="__MODULE__/BannerCat/index">Banner/广告管理</a></dd>
                    <dd><a class="J_menuItem" href="__MODULE__/BkCat/index">宫格版块管理</a></dd>
				</dl>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-dialogue"></i>
					<span class="nav-label">内容管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/ArticleCat/index">文章管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/MessageCat/index">留言管理</a></li>
					<!-- <li><a class="J_menuItem" href="__MODULE__/HrefCat/index">友情链接</a></li> -->
					<!-- <li><a class="J_menuItem" href="__MODULE__/Qq/index">QQ客服管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Job/index">招聘管理</a></li> -->
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-spread-left"></i>
					<span class="nav-label">淘宝管理系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/TaobaoCat/index">淘宝商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbCat/index">淘宝官方商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/index">淘宝订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/treatOrder">处理遗漏淘宝订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/allotOrder">处理无归属淘宝订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/allotOrderAll">批量处理无归属淘宝订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbOrder/task">淘宝订单卡顿处理（长期订单不同步使用）</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-transfer"></i>
					<span class="nav-label">拼多多管理系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/PddCat/index">拼多多商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/PddOrder/index">拼多多订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/PddOrder/treatOrder">处理拼多多遗漏订单</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-slider"></i>
					<span class="nav-label">京东管理系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/JingdongCat/index">京东商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/JingdongOrder/index">京东订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/JingdongOrder/treatOrder">处理遗漏京东订单</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-shrink-right"></i>
					<span class="nav-label">唯品会管理系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/VipCat/index">唯品会商品分类管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/VipOrder/index">唯品会订单管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/VipOrder/treatOrder">处理遗漏唯品会订单</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-diamond"></i>
					<span class="nav-label">黑卡管理系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/CardCat/index">黑卡分类</a></li>
					<li><a class="J_menuItem" href="__MODULE__/CardPrivilege/index">黑卡列表</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-user"></i>
					<span class="nav-label">社区/论坛系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/BbsBoard/index">版块管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/checkPending">待审核帖子列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/checkPass">已审核帖子列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/checkRefused">审核不通过帖子列表</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-survey"></i>
					<span class="nav-label">营销中心</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/sendGoods">爆款商品推送</a></li>
					<li><a class="J_menuItem" href="__MODULE__/BbsArticle/sendArticle">文章推送</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbGoods/index">淘宝推荐商品管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/HotSearch/index">热门搜索设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Rookie/index">拉新活动</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/UserBalanceRecord/receiveBonus">新人红包设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/TbGoodsFree/index">淘宝0元购商品管理</a></li>
<!--					<li><a class="J_menuItem"-->
<!--							href="https://pub.alimama.com/promo/search/index.htm?spm=a219t.7900221/1.1998910419.de727cf05.2a8f75a54Spltq&toPage=1&queryType=2"-->
<!--							target="_blank">淘宝商品超级搜索</a></li>-->
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-release"></i>
					<span class="nav-label">任务中心</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/PointSet/infoSet">完善资料</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/userSet">分享好友</a></li>
					<!--					<li><a class="J_menuItem" href="javascript:alert('待开发');">实名认证</a></li>-->
					<li><a class="J_menuItem" href="__MODULE__/PointSet/set">积分系统</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-cart-simple"></i>
					<span class="nav-label">商城系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/Brand/index">厂家/品牌管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/GoodsCat/index">商品管理</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/Goods/examineGoods">待审核上架列表</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/Goods/offshelf">已下架列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Goods/recycle">商品回收站</a></li>
					<li><a class="J_menuItem" href="__MODULE__/ConsigneeAddress/index">收货地址管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Invoice/index">发票信息管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Bank/index">银行管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Bood/index">提现管理</a></li>
				</ul>
			</li>
            <li class="layui-nav-item">
                <a href="#">
                    <i class="layui-icon layui-icon-transfer"></i>
                    <span class="nav-label">插件</span>
                </a>
                <ul class="layui-nav-child">
                    <li><a class="J_menuItem" href="__MODULE__/MultiMerchant/index">插件管理</a></li>
                </ul>
            </li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-spread-left"></i>
					<span class="nav-label">订单管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/Order/unpaid">未付款订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/paid">已付款订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/send">已发货订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/finish">已确认收货订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/comment">已评价/已结束订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/refund">申请退款订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/refundSuccess">退款成功订单</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Order/refundFail">拒绝退款订单</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/Order/orderSetting">设置</a></li>
					<!--
						<li><a class="J_menuItem" href="__MODULE__/Order/add">人工录入订单</a></li>
						<li><a class="J_menuItem" href="__MODULE__/Order/StatisticsNum">订单数量统计</a></li>
						<li><a class="J_menuItem" href="__MODULE__/Order/StatisticsMoney">订单金额统计</a></li>
						 -->
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-play"></i>
					<span class="nav-label">视频管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/Short/index">视频列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/ShortComment/index">评论列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Report/short">视频举报</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Advertising/index">短视频广告</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-cellphone-fine"></i>
					<span class="nav-label">直播管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/LiveCat/index">直播分类</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Live/index">房间列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/LiveGift/index">房间礼物</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/HostAuthentication/index">实名认证列表</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-username"></i>
					<span class="nav-label">会员管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/UserGroup/index">会员组管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/index">会员列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/teamReward">会员团队分红设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/liveSetUpThe">主播设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserBalanceRecord/index">会员余额变动记录</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserPointRecord/index">会员积分变动记录</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserAuthCode/index">会员授权码管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/everyday">每日注册会员统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/statistics">会员统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/index2">活跃会员统计</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/export">导出会员列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/User/setup">会员登录设置</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/HostUserGroup/index">直播带货佣金管理</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-dollar"></i>
					<span class="nav-label">代理商系统</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/Agent/index">查看用户列表</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-rmb"></i>
					<span class="nav-label">资金管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/UserDrawApply/checkPending">用户待审核提现申请</a></li>
					<li><a class="J_menuItem" href="__MODULE__/UserDrawApply/checked">用户已审核提现申请</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-set-sm"></i>
					<span class="nav-label">系统设置</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/System/websetV2">站点设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/sensitive">敏感词过滤</a></li>
	<!--					<li><a class="J_menuItem" href="__MODULE__/System/feeset">费用规则设置</a></li> -->
					<li><a class="J_menuItem" href="__MODULE__/System/accountSet">应用账号配置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/drawSet">提现设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/rebateSet">返利设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/articleSet">系统文章设置</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/customAppNav">导航栏自定义</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/IcoSetting/index">ICO设置管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/appletSet">小程序配置设置</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/System/appSet">APP配置设置</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/System/headAdvertSet">APP首页活动设置</a></li>
                    <li><a class="J_menuItem" href="__MODULE__/System/distribution">自营商城分销设置</a></li>
					<li><a class="J_menuItem" href="/upgrade.php">版本更新</a></li>
					<li><a class="J_menuItem" href="__MODULE__/System/pictureManagement">图片管理</a></li>
				</ul>
			</li>
			<li class="layui-nav-item">
				<a href="#">
					<i class="layui-icon layui-icon-user"></i>
					<span class="nav-label">管理员管理</span>
				</a>
				<ul class="layui-nav-child">
					<li><a class="J_menuItem" href="__MODULE__/Admin/index">管理员列表</a></li>
					<li><a class="J_menuItem" href="__MODULE__/AdminGroup/index">组别管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/AuthRule/index">权限管理</a></li>
					<li><a class="J_menuItem" href="__MODULE__/Admin/changepwd">修改密码</a></li>
				</ul>
			</li>

		</div>
	</ul>
</nav>
<script src="__LAYUIADMIN__/layui/layui.all.js"></script>
