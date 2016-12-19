<doctype html>
<html lang="en" style="font-size: 100px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <link href="/resource/css/vote/database.css" rel="stylesheet">
    <link href="/resource/css/vote/detail.css?_t=5" rel="stylesheet">
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
</head>
<body>
<div class="main">
    <div class="banner" id="poster">
        <img src="{%$userInfo.msg.poster%}" alt="">
        <a id="poster-back" href="/vote/list">
            <div class="back">返回</div>
        </a>
        <div class="i-play" id="pptit03" data-vid="{%$userInfo.msg.video%}"></div>
    </div>
    <div class="banner" id="video-container" style="display: none"></div>
    <h3>{%$userInfo.liked%} 票 {%if isset($userInfo["top1"])%}当前排名第一哦 {%else%}距第一名还差 {%$userInfo.fail%} 票{%/if%}</h3>
    <div style="clear: both"></div>
    <div class="info">
        <div class="num">{%$userInfo.number%} 号：{%$userInfo.msg.name%}
            {%if isset($userInfo["top1"])%}
            <div class="huangguan" style="background: url(/resource/img/vote/i-huangguan.png) no-repeat;background-size: contain;"></div>
            {%elseif isset($userInfo["top2"])%}
            <div class="huangguan" style="background: url(/resource/img/vote/i-huangguan-2.png) no-repeat;background-size: contain;"></div>
            {%elseif isset($userInfo["top3"])%}
            <div class="huangguan" style="background: url(/resource/img/vote/i-huangguan-3.png) no-repeat;background-size: contain;"></div>
            {%else%}
            <div class="huangguan"></div>
            {%/if%}
        </div>
        <div class="title">心愿单：{%$userInfo.msg.wish%}</div>
        <div class="desc">{%$userInfo.msg.desc%}</div>
    </div>
    <div class="btns clearfix">
        <div class="btn">给TA投票</div>
        <div class="btn">我要分享</div>
    </div>
</div>

<div class="footer clearfix">
    <div class="img">
        <a href=""><img src="/resource/img/vote/log3.png" alt=""/></a>
        <a href=""><img src="/resource/img/vote/log4.png" alt=""></a>
    </div>
</div>

<div class="popup popup-vote">
    <div class="con">
        <img src="/resource/img/vote/qrcode.jpg" alt="" />
        <p>长按选择识别图中二维码
            <br/>
            并关注公众号
            <br/>
            输入“TP{%$userInfo.number%}”为他投票
            <br/>
            投票后赢取现金红包
        </p>
        <div class="close">X</div>
    </div>
</div>
<div class="popup popup-share">
    <img src="/resource/img/vote/share.png" alt=""/>
</div>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<textarea id="wechat_share" style="display: none">{%$jsapi%}</textarea>
<script src="/resource/scripts/wechat.js?timestamp=1"></script>

</body>
<!--video-->
<script src="http://y2.ifengimg.com/314bd925cdd17196/2014/0814/video.js"></script>
<script src="http://y0.ifengimg.com/components/video/20140813/v2/videoMobile.js"></script>
<script>
    (function () {
        var sW = $("#video-container").width();
        var sH = $("#video-container").height();

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
            $('#video-container').show();
            video1.play();
            $("#video-container").find(".JzClose").show();
        });

        var $btn = $('.btns').children();
        $btn.eq(0).click(function () {
            $('#video-container').hide();
            $('#poster').show();
            video1.pause();
            $('.popup-vote').show();

        });
        $('.popup .close').click(function () {
            $('.popup-vote').hide();
        });
        $btn.eq(1).click(function () {
            $('#video-container').hide();
            $('#poster').show();
            video1.pause();
            $('.popup-share').show();
        });
        $('.popup').click(function () {
            $(this).hide();
        });
    })();
</script>
</html>