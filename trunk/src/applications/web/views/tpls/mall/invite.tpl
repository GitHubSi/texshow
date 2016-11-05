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
    <title>邀请码</title>
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
                                };
            // Abort if browser does not support addEventListener
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            recalc();
        })(document, window);
    </script>
    <style>
        .main {
            background: #f2f2f2;
            border-top: 1px solid #fff;
        }

        .code {
            width: 5rem;
            margin: 0.86rem auto 0;
            line-height: 0.58rem;
        }

        .code .caption {
            width: 1.3rem;
            float: left;
            font-size: 0.34rem;
            color: #000;
        }

        .code .num {
            width: 3.5rem;
            float: left;
            background: #fff;
            border: 1px solid #e7e7e7;
            color: #aaaaaa;
            font-size: 0.28rem;
            height: 0.58rem;
            text-indent: 0.2rem;
        }

        .erweima1 {
            width: 2.9rem;
            margin: 0.3rem auto 0;
            display: block;
        }

        .con {
            width: 100%;
            display: block;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="code clearfix">
        <div class="caption">邀请码:</div>
        <div class="num">{%$inviteCode%}</div>
    </div>
    <img src="/resource/img/dbzhijun/qrcode.jpg" alt="" class="erweima1">
    <img src="/resource/img/dbzhijun/code_con.png" alt="" class="con">
</div>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<textarea id="wechat_share" style="display: none">{%$jsapi%}</textarea>
<script src="/resource/scripts/wechat.js?timestamp=1"></script>

</body>
</html>