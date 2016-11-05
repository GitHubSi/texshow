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
    <title>{%$good.name%}</title>
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
</head>
<body>
<div class="per-main">
    <div class="product clearfix">
        <img src="{%$good.image%}" alt=""/>
        <div class="info" id="good-detail" data-pro="{%$good.id%}">
            <p>{%$good.name%}</p>
            <div class="btns">
                <div class="enjoy wxEnjoy">分享</div>
                <div class="duobao" id="popuptz">夺宝</div>
            </div>
        </div>
    </div>
    <div class="totle-num">当前投注总数量: <span>{%$good.current_score%}/{%$good.total_score%}</span></div>
    <!-- <div class="date">时间：2016年9月5日－9月10日</div> -->
    <div class="product-des">
        {%foreach $good.desc as $introduce%}
        <img src="{%$introduce.image%}" alt=""/>
        <p>{%$introduce.title%}</p>
        {%/foreach%}
    </div>
</div>
<input type="hidden" id="user_score" value="{%$userInfo.score%}"/>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<textarea id="wechat_share" style="display: none">{%$jsapi%}</textarea>
<script src="/resource/scripts/wechat.js?timestamp=1"></script>
</body>
<script>
    (function () {
        //投注
        var max = $("#user_score").val();
        if ($.trim(max) == "") {
            max = 0;
        }

        var html = '<div class="popup-mask1">';
        html += '<div class="message">';
        html += ' <h3>投注数量选择</h3>';
        html += ' <div class="num-box clearfix">';
        html += ' <div class="des">投注次数</div>';
        html += ' <div class="btns">';
        html += ' <div class="sub">-</div>';
        html += ' <div class="text">' + max + '</div>';
        html += ' <div class="add">+</div>';
        html += ' </div>';
        html += ' <div class="des">(你拥有' + max + '次投注机会)</div>';
        html += ' </div>';
        html += ' <div class="btn-snatch">';
        html += ' 立即夺宝';
        html += ' </div>';
        html += ' <div class="close"></div>';
        html += ' </div>';
        html += ' </div>';
        var $popuptz = $('#popuptz');
        $popuptz.click(function () {
            $('body').append(html);
        });
        $('body').on('click', '.close', function () {
            $('.popup-mask1').remove();
        });
    })();

    //分享
    (function () {
        var $testList = $('body');
        $testList.on('click', '.wxEnjoy', function () {
            var popup = '<div class="popup-mask"><img src="/resource/img/dbzhijun/enjoywx.png" alt=""/> </div>';
            $('body').append(popup);

        });
        $testList.on('click', '.popup-mask', function () {
            $(this).remove();
        });
    })();
    //投注
    (function () {
        var max = $("#user_score").val();
        if ($.trim(max) == "") {
            max = 0;
        }

        var $testList = $('body');
        var num;
        $testList.on('click', '.sub', function () {
            var $text = $('.message').find('.text');
            if (!num) {
                num = $text.text();
            }
            num--;
            if (num <= 0) {
                num = 1;
            }
            $text.text(num);
        });
        $testList.on('click', '.add', function () {
            var $text = $('.message').find('.text');
            if (!num) {
                num = $text.text();
            }
            num++;
            if (num >= max) {
                num = max;
            }
            $text.text(num);
        });
        //提交
        $testList.on('click', '.btn-snatch', function () {
            var max = $("#user_score").val();
            if ($.trim(max) == "" || max <= 0) {
                return false;
            }

            var dataId = $("#good-detail").attr('data-pro');
            var $text = $('.message').find('.text');
            var num = $text.text();
            $.get('/mall/buy',{num:num, item:dataId}, function () {

            });
        });
    })()
</script>
</html>