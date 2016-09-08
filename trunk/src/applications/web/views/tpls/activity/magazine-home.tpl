<!DOCTYPE HTML>
<html lang="en" dir="ltr">
<head>
<title>Tex积分夺宝个人中心</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<link rel="stylesheet" type="text/css" href="/resource/css/magazine-home.css" /><!--SET MEDIA HANDELD HERE-->
</head>
<body>

<div id="main-wrapper">
	<section class="listing">
		<article class="clearfix">
			<div class="thumb">
				<figure><img src="{%$userInfo.headimgurl%}" width="101" height="101" alt="Food News" /></figure>
			</div><!--/.thumb-->
			
			<div class="text">
				<h2>{%$userInfo.nickname%}</h2>
				<p>当前{%$userInfo.score%}积分</p>
			</div><!--/.text-->
		</article>
	</section>
	
	<section class="main-content">
		<article class="main-front">
			<h2>邀请码：{%$inviteCode%}</h2>
			<p>邀请好友关注订阅号，只要好友在订阅号中回复邀请码，就可以为自己增加1积分哦！</p>
		</article><!--/.main-front-->

		<section class="listing">
			{%foreach $msgList as $msg%}
			<article class="clearfix" style="padding: 10px">
				<div class="text">
					<h2><a>购买通知</a></h2>
					<p>您于{%$msg.create_time%}参与一元夺宝活动，购买"{%$msg.good_name%}"{%$msg.score%}份。</p>
					<a class="read-more">简讯</a>
				</div><!--/.text-->
			</article>
			{%/foreach%}

			<article class="clearfix" style="padding: 10px">
				<div class="text">
					<h2>欢迎关注订阅号</h2>
					<p>亲爱的小伙伴，欢迎关注我们哦。在这里你能看到全新的科技视频内容，更鲜活更有趣，拒绝枯燥参数，给你最真实的数码体验。在这里，你还能免费参加最新最酷数码产品试玩，每周还有惊喜大奖等你来拿。</p>
					<a class="read-more">简讯</a>
				</div><!--/.text-->
			</article>
		</section>
	</section><!--/.main-content-->
</div>
</body>
</html>
