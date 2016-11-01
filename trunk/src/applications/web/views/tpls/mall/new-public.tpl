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
    <title>最新揭晓</title>
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
        <li><a class="active" href="/latestPublic">最新揭晓</a></li>
        <li><a href="/home">个人中心</a></li>
    </ul>
</header>
<div class="publish-main">
    <ul>
        {%if !empty($latestPublic)%}
        {%foreach $latestPublic as $public%}
        <li class="clearfix">
            <div class="img"><img src="/resource/img/dbzhijun/fudai.png" alt=""/></div>
            <div class="info">
                <h3>{%$public.name%}－速开</h3>
                <p>期 号：{%$public.batch%}</p>
                <p>获得用户：<span class="b">{%$public.user_name%}</span></p>
                <p>参与人次：{%$public.current_score%}人次</p>
                <p>幸运好嘛：<span class="r">{%$public.invite_code%}</span></p>
                <p>揭晓时间：{%$public.open_time%}</p>
            </div>
        </li>
        {%/foreach%}
        {%/if%}
    </ul>
</div>
</body>
</html>