<html lang="en" style="font-size: 100px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <link href="/resource/css/database.css" rel="stylesheet">
    <link rel="stylesheet" href="/resource/css/swiper.min.css">
    <link href="/resource/css/my.css" rel="stylesheet">
    <script src="/resource/js/jquery.min.js"></script>
    <script src="/resource/js/swiper.min.js"></script>
    <title>个人中心</title>
    <script>
        (function (doc, win) {
            // 分辨率Resolution适配
            var docEl = doc.documentElement,
                    resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                    recalc = function () {
                        var clientWidth = docEl.clientWidth;
                        if (!clientWidth) return;
                        if (clientWidth >= 750 && clientWidth <= 1000) {
                            docEl.style.fontSize = 100 + 'px';
                        } else if (clientWidth >= 1000) {
                            docEl.style.fontSize = 100 * (1000 / 720) + 'px';
                        } else{docEl.style.fontSize = 100 * (clientWidth / 720) + 'px';}
                                }
            // Abort if browser does not support addEventListener
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            recalc();
        })(document, window);
    </script>
</head>
<body>
<header>
    <ul class="clearfix">
        <li><a href="/mall">首页</a></li>
        <li><a href="/latestPublic">最新揭晓</a></li>
        <li><a class="active" href="/home">个人中心</a></li>
    </ul>
</header>
<div class="per-main">
    <div class="per-info clearfix">
        <div class="img">
            <img src={%if !isset($userInfo.headimgurl)%}"/resource/img/dbzhijun/circle.png"{%else%}{%$userInfo.headimgurl%}{%/if%}
                 alt=""/>
        </div>
        <div class="info">
            <h3>{%$userInfo.nickname%}</h3>
            <p>夺宝币：<span>{%$userInfo.score%}个</span></p>
        </div>
    </div>
    <ul>
        <li><a href="/mall/address">
                <p>收货地址</p>
                <i></i></a>
        </li>
        <li><a href="/mall/history">
                <p>夺宝记录</p>
                <i></i></a>
        </li>
        <li><a href="/mall/winRecord">
                <p>中奖记录</p>
                <i></i></a>
        </li>
        <li><a href="">
                <p>常见问题</p>
                <i></i></a>
        </li>
    </ul>
</div>
</body>
</html>