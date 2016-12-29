<html lang="en" style="font-size: 100px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <link href="/resource/css/person/database.css?t=36" rel="stylesheet">
    <script src="/resource/js/jquery.min.js"></script>
    <script src="/resource/js/jquery.touchSwipe.js"></script>
    <link rel="stylesheet" href="/resource/css/vote/swiper.min.css">
    <script src="/resource/js/vote/swiper.min.js"></script>

    <title>那些熟悉的陌生人</title>
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
                        } else{docEl.style.fontSize = 100 * (clientWidth / 750) + 'px';}
                                };
            // Abort if browser does not support addEventListener
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            recalc();
        })(document, window);
    </script>
</head>
<body>

<div class="page page1">
    <div class="nav news-nav-list clearfix" id="nav-contain">
        <div class="swiper-container swiper-container1">
            <ul class="clearfix swiper-wrapper">
                <li class="active swiper-slide swiper-slide0"><a href="/people?person=0">“90后”女骑士</a></li>
                <li class="swiper-slide swiper-slide0"><a href="/people?person=1">“孤独的”地图采集员</a></li>
                <li class="swiper-slide swiper-slide0"><a href="/people?person=2">“黑白颠倒”的代驾员</a></li>
                <li class="swiper-slide swiper-slide0"><a href="/people?person=3">幕后英雄传站员</a></li>
                <li class="swiper-slide swiper-slide0"><a href="/people?person=4">穿正装的老司机</a></li>
                <li class="swiper-slide swiper-slide1"><a href="#">更多人物，明日更新</a></li>
            </ul>
        </div>
    </div>

    <div class="banner">
        <img src="/resource/img/person/banner.png" alt=""/>
    </div>

    <div class="mv-wrapper">
        <div class="tag">分享专题</div>

        <div class="mv" id="poster">
            <img src="{%$user.poster%}" alt="">
            <div class="i-play" id="pptit03" data-vid="{%$user.video%}"></div>
        </div>
        <div class="mv" id="video-container" style="display: none"></div>

        <div class="desc">
            {%$user.summary%}
        </div>
    </div>
</div>

<div class="page page2">
    {%$user.detail%}
</div>

<div class="f-print">--------- 凤凰科技出品 ---------</div>


<div class="pop-up">
    <a href="javascript:void(0);" class="subject">进入专题</a>
</div>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<textarea id="wechat_share" style="display: none">{%$jsapi%}</textarea>
<script src="/resource/scripts/wechat.js?timestamp=1"></script>

<input type="hidden" id="nav-index" value="{%$navIndex%}">
<script>
    $(document).ready(function () {
        var clientWidth = document.body.clientWidth;
        //初始化,元素的宽度
        $("body").css({
            "width": clientWidth + "px",
            "overflow-x": "hidden"
        });
        $(".nav").css("width", clientWidth + 'px');
    });

    $('.pop-up').click(function () {
        $(this).hide();
    });

    var swiper1 = new Swiper('.swiper-container1', {
        slidesPerView: 3,
        paginationClickable: true,
        spaceBetween: 0,
        freeMode: true
    });

    var navIndex = $("#nav-index").val();
    swiper1.slideTo(navIndex, 1000, false);

    $(".nav").find("li").each(function (index) {
        $(this).removeClass("active");
        if (index == navIndex) {
            $(this).addClass("active")
        }
    })
</script>

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
        });

        $('.tag').click(function () {
            $('#video-container').hide();
            $('#poster').show();
            video1.pause();
            $('.pop-up').show();
        });
    })();
</script>
</body>
</html>