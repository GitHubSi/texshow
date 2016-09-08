<!DOCTYPE HTML>
<html lang="en" dir="ltr">
<head>
<title>Tex积分夺宝</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<link rel="stylesheet" type="text/css" href="/resource/css/share-iphone.css?a=1" /><!--SET MEDIA HANDELD HERE-->
</head>
<body>

<div id="main-wrapper">
	<header>
		<div id="logo" class="clearfix">
			<figure><a><img src="/resource/img/iphone-header.jpg" width="82" height="82" alt="News Mirror Logo" /></a></figure>
			<div class="top-title">
				<h1>全新iPhone 7手机</h1>
				<div class="site-description" style="width:200px; overflow-x: hidden">
					<!--<input type="button" value="分享"/>
					<input type="button" value="夺宝"/> -->
					当前可用&nbsp;{%$userInfo.score%}&nbsp;积分<a style="text-decoration:none" href="http://act.wetolink.com/shareInviteCode/">(邀请好友关注公众号即可获取积分)</asty>。
				</div>
			</div><!--/.top-title-->
		</div><!--/#logo-->
	</header>
	
	<section class="main-content">
		<section class="listing">
			<article class="clearfix">
				<div class="text">
					<h3><a>当前总投注数量：{%$good.current_score%}/{%$good.total_score%}</a></h3>
				</div><!--/.text-->
			</article>
			<article class="clearfix">
				<div class="text">
					<h3><a>时间：{%$startTime%} - {%$endTime%}</a></h3>
				</div><!--/.text-->
			</article>
			<article class="clearfix">
				<div class="text">
					<form action="/shareItem/buy" method="post" enctype="application/x-www-form-urlencoded">
						<input type="text" name="rob_num" placeholder="输入夺宝次数"/>
						<input type="submit" value="立即夺宝"/>
					</form>
				</div><!--/.text-->
			</article>
		</section>
		<section class="listing">
			<div class="detail" style='background-image: url("/resource/img/detail-1.jpg");'></div>
			<div class="detail-title">iPhone7拥有全新的外观设计</div>
		</section>
		<section class="listing">
			<div class="detail" style='background-image: url("/resource/img/detail-2.jpg");'></div>
			<div class="detail-title">iPhone7运行全新的iOS10操作系统</div>
		</section>
		<section class="listing">
			<div class="detail" style='background-image: url("/resource/img/detail-3.jpg");'></div>
			<div class="detail-title">iPhone7采用双摄像头配置</div>
		</section>
		<section class="listing">
			<div class="detail" style='background-image: url("/resource/img/detail-4.jpg");'></div>
			<div class="detail-title">增添蓝色iPhone7 黑白灰金不再单调</div>
		</section>
	</section><!--/.main-content-->
	
</div>
</body>
</html>
