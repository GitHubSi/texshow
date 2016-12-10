<doctype html>
<html lang="en" style="font-size: 100px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <link href="/resource/css/vote/database.css" rel="stylesheet">
    <link href="/resource/css/vote/detail.css?_t=17" rel="stylesheet">
    <script src="/resource/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/resource/css/vote/swiper.min.css">
    <script src="/resource/js/vote/swiper.min.js"></script>
    <title>你任性-我买单</title>
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
                            docEl.style.fontSize = 100 * (1000 / 750) + 'px';
                        } else {
                            docEl.style.fontSize = 100 * (clientWidth / 750) + 'px';
                        }
                    };
            // Abort if browser does not support addEventListener
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            recalc();
        })(document, window);
    </script>
</head>
<body>
<div class="swiper-container">
    <div class="swiper-wrapper">
        <section class="swiper-slide">
            <div class="footer">
                <div class="img">
                    <a href=""><img src="/resource/img/vote/logo1.png" alt=""/></a>
                    <a href=""><img src="/resource/img/vote/logo2.png" alt=""></a>
                </div>
            </div>
            <div class="btn-rule">
                <a href="{%$ruleLink%}"></a>
            </div>
            <div class="btn-next"></div>
        </section>
        <section class="swiper-slide view2">
            <div class="title"></div>
            <ul class="clearfix">
                {%foreach $userList as $user%}
                <li>
                    <a href="/vote/detail?no={%$user.id%}">
                        <img src="{%$user.msg.poster%}" alt="">
                        <p>{%$user.number%}号:{%$user.msg.name%}</p>
                        <div class="num">{%$user.liked%}</div>
                        <a href="/vote/detail?no={%$user.id%}">
                            <div class="btn-detail">详情</div>
                        </a>
                        <div class="i-play"></div>
                        <div class="i-no"></div>
                    </a>
                </li>
                {%/foreach%}
            </ul>
            <a href="/vote/list">
                <div class="btn-more">查看更多</div>
            </a>
        </section>
    </div>
</div>
</body>
<script>
    /*var clientHeight = document.documentElement.clientHeight;
    $(".swiper-container").css("height", clientHeight + "px");*/

    var mySwiper = new Swiper('.swiper-container', {
        direction: 'vertical',
        calculateHeight : true,
        updateOnImagesReady : true,
        resistance: false
    });
</script>
</html>