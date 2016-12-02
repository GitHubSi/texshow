<html lang="en" style="font-size: 100px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <link href="/resource/css/vote/database.css" rel="stylesheet">
    <link href="/resource/css/vote/detail.css" rel="stylesheet">
    <script src="/resource/js/jquery.min.js"></script>
    <title>投票详情页</title>
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
    <style>
        .main {
            width: 7.5rem;
            margin: 0 auto;
            height: 10.21rem;
        }

        .main .banner {
            width: 100%;
            height: 4.51rem;
            position: relative;
            overflow: hidden;
        }

        .main .banner img {
            width: 100%;
            height: 100%;
        }

        .main .banner .back {
            width: 1.43rem;
            height: 0.54rem;
            font-size: 0.34rem;
            line-height: 0.54rem;
            position: absolute;
            top: 0.36rem;
            left: 0.47rem;
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            border-radius: 0.1rem;
        }

        .main .banner .i-play {
            width: 1.47rem;
            height: 1.09rem;
            background: url(/resource/img/vote/i-play.png) no-repeat;
            position: absolute;
            margin: auto auto;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
        }

        .main h3 {
            color: #333;
            font-size: 0.28rem;
            margin-top: 0.15rem;
            text-indent: 3.6rem;
        }

        .main .info {
            width: 100%;
            padding: 0 0.45rem;
        }

        .main .info .num .huangguan {
            width: 0.69rem;
            height: 0.48rem;
            background: url(/resource/img/vote/i-huangguan.png) no-repeat;
            background-size: cover;
            position: absolute;
            top: -0.35rem;
            left: 2.45rem;
        }

        .main .info .num {
            margin-top: 0.25rem;
            position: relative;
            display: inline-block;
        }

        .main .info .num, .main .info .title {
            font-size: 0.4rem;
            color: #1b1b1b;
        }

        .main .info .desc {
            width: 6.59rem;
            height: 2.75rem;
            background: url(/resource/img/vote/line-desc.png) no-repeat;
            padding: 0.8rem 0.15rem 0 0.2rem;
            background-size: cover;
            font-size: 0.3rem;
            color: #333;
        }

        .main .btns {
            width: 6.5rem;
            margin: 0.3rem auto 0;
        }

        .main .btns .btn {
            width: 3.18rem;
            height: 0.83rem;
            background: #ff762c;
            color: #fff;
            text-align: center;
            font-size: 0.36rem;
            line-height: 0.83rem;
            border-radius: 0.1rem;
            float: left;
        }

        .main .btns .btn:nth-child(2) {
            margin-left: 0.14rem;
        }

        .footer {
            width: 3.64rem;
            padding: 0.2rem 0 0.1rem 0;
            margin: 0 auto;
        }

        .footer img {
            width: 3.64rem;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="banner" id="video-container">
        <img id="poster" src="/resource/img/vote/girl.png" alt="">
        <div class="back">返回</div>
        <div class="i-play" id="pptit03" data-vid="01689db3-2b01-4353-a656-19ee60289358"></div>
    </div>
    <h3>612 票 距第一名还差 12 票</h3>
    <div class="info">
        <div class="num">619 号：余文文
            <div class="huangguan"></div>
        </div>
        <div class="title">心愿单：普吉岛机票1张</div>
        <div class="desc">快节奏的生活中向往诗和远方，想去一个阳光耀眼的地方，感受生命。给我一张机票，随便什么地方。</div>
    </div>
    <div class="btns clearfix">
        <div class="btn">给TA投票</div>
        <div class="btn">我要分享</div>
    </div>
</div>
<div class="footer">
    <img src="/resource/img/vote/logo.png" alt=""/>
</div>
</body>
<!--video-->
<script src="http://y2.ifengimg.com/314bd925cdd17196/2014/0814/video.js"></script>
<script src="http://y0.ifengimg.com/components/video/20140813/v2/videoMobile.js"></script>
<script>
    (function () {
        var sW = $(".banner").width();
        var sH = $(".banner").height();

        var player1 = $('#pptit03');
        var vid1 = player1.attr('data-vid');
        var video1;

        video1 = new SpecailVideo();
        video1.create({
            guid: vid1,
            containerId: 'video-container', // 这个地方必须是id
            AutoPlay: false, // 是否自动播放   height: sW*7/5改的是页面的宽度
            width: sW,
            height: sH,
            subjectid: 'mirror-lff-0813', // 组id，尽量以专题名加业内实例化序号来生成，保证不重复
            // 播放列表，如果没有，可以传回当前的guid或者传回''；
            guidList: vid1,
            // 播放下一条的回调，主要用于操作播放列表的样式。
            getNextGuid: function (guid) {
            }
        });

        player1.click(function () {
            $('#poster').hide();
            video1.play();
            $("#video-container").find(".JzClose").show();
        });
    })();
</script>
</html>