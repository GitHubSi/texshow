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
    <title>凤凰夺宝</title>
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
<header>
    <ul class="clearfix">
        <li><a class="active" href="/mall">首页</a></li>
        <li><a href="/latestPublic">最新揭晓</a></li>
        <li><a href="/home">个人中心</a></li>
    </ul>
</header>
<div class="swiper-container swiper-container-horizontal">
    <div class="swiper-wrapper"
         style="transform: translate3d(-2250px, 0px, 0px); -webkit-transform: translate3d(-2250px, 0px, 0px); transition-duration: 0ms; -webkit-transition-duration: 0ms;">
        {%foreach $goodList["headImg"] as $headImg%}
        <div class="swiper-slide">
            <a href="{%$headImg.redirect_url%}"><img src="{%$headImg.img_url%}"></a>
        </div>
        {%/foreach%}
    </div>
    <div class="swiper-pagination"></div>
</div>
<div class="system">
    <div class="title">热门商品</div>
    <ul class="clearfix" id="J_test_list">
        {%foreach $goodList["goodList"] as $good%}
        <li data-pro="{%$good.id%}">
            <a href="/mall/detail?item={%$good.id%}">
                <img src="{%$good.image%}" alt=""/>
            </a>
            <p class="shop-des">{%$good.name%}</p>
            <div class="info clearfix">
                <div class="progress">
                    <p>进度 <span>{%$good.rank%}</span></p>
                    <div class="progress-bar">
                        <div class="bar" style="width: {%$good.rank%}"></div>
                    </div>
                </div>
                <div class="enjoy wxEnjoy" data-pro="{%$good.id%}">分享</div>
                <div class="duobao" data-pro="{%$good.id%}">夺宝</div>
            </div>
        </li>
        {%/foreach%}
    </ul>
</div>

<input type="hidden" id="user_score" value="{%$userInfo.score%}"/>

<!--loading框开始-->
<div id="J_loading" class="loading" style="display: none;">
    <div class="spinner">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
    <p class="txt">数据加载中</p>
</div>
<div id="J_load_more" class="loading">
    <p class="txt">没有更多数据了~</p>
</div>
<!--loading框结束-->
<div id="J_footer" class="footer">
    <p>凤凰夺宝</p>
</div>
<!--分享弹窗-->

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<textarea id="wechat_share" style="display: none">{%$jsapi%}</textarea>
<script src="/resource/scripts/wechat.js?timestamp=1"></script>

<script>
    //轮播  动态加载内容模块
    (function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            loop: true,
            spaceBetween: 30,
            autoplay: 5000,
            autoplayDisableOnInteraction: false
        });

        //判断设备为手机端还是PC
        /*function browserRedirect() {
         var sUserAgent = navigator.userAgent.toLowerCase();
         var bIsIpad = sUserAgent.match(/ipad/i) == "ipad";
         var bIsIphoneOs = sUserAgent.match(/iphone os/i) == "iphone os";
         var bIsMidp = sUserAgent.match(/midp/i) == "midp";
         var bIsUc7 = sUserAgent.match(/rv:1.2.3.4/i) == "rv:1.2.3.4";
         var bIsUc = sUserAgent.match(/ucweb/i) == "ucweb";
         var bIsAndroid = sUserAgent.match(/android/i) == "android";
         var bIsCE = sUserAgent.match(/windows ce/i) == "windows ce";
         var bIsWM = sUserAgent.match(/windows mobile/i) == "windows mobile";
         //手机端
         if (bIsIpad || bIsIphoneOs || bIsMidp || bIsUc7 || bIsUc || bIsAndroid || bIsCE || bIsWM) {

         } else {
         $('.system_fots ').addClass('mobile-system-fots');
         $('.erweima').show();
         }
         //PC端
         }
         browserRedirect();*/
        /**
         * 上拉加载数据
         * @return {[type]}     [description]
         */
        var $testList = $('#J_test_list');
        var $loading = $('#J_loading'),
                n = 1,
                scrollTimer;

        function pullUpLoadData() {
            var dataId = $testList.find('li').last().attr('data-pro');
            $.ajax({
                type: "GET",
                url: "/mall/more?last_id=" + dataId,//每次加载n+1
                dataType: "json",
                beforeSend: function () {
                    $loading.show();
                },
                success: function (data) {
                    n++;
                    var goodsList = data["data"]
                    if (goodsList.length == 0) {
                        $loading.hide();
                        $('#J_load_more').show();
                        return false;
                    } else {
                        generateDom(goodsList);
                    }

                },
                error: function () {
                    console.log('fail');
                },
                complete: function () {
                    $loading.hide();
                }
            });
        }

        function generateDom(data) {
            var $testList = $('#J_test_list');
            var html = '';
            $.each(data, function (i, n) {
                html += '<li data-pro="' + n.id + '">';
                html += '<img src=' + n.image + ' alt=""/>';
                html += '<p class="shop-des">' + n.name + '</p>';
                html += '<div class="info clearfix">';
                html += '<div class="progress">';
                html += '<p>进度 <span>' + n.rank + '</span></p>';
                html += '<div class="progress-bar">';
                html += '<div class="bar" style="width:' + n.rank + '"></div>';
                html += '</div>';
                html += '</div>';
                html += '<div class="enjoy wxEnjoy" data-pro="' + n.id + '">分享</div>';
                html += '<div class="duobao" data-pro="' + n.id + '">夺宝</div>';
                html += '</div>';
                html += '</li>';
            });
            $testList.append(html);
        }

        $(window).on('scroll', function () {
            var scrollTop = getScrollTop(),
                    footerOT = $('#J_footer').offset().top,
                    cHeight = getClientHeight();
            if (scrollTop + cHeight >= footerOT) {
                // 上拉加载数据(延迟执行，防止操作过快多次加载)
                clearTimeout(scrollTimer);
                scrollTimer = setTimeout(function () {
                    pullUpLoadData();
                }, 200);
            }
        });
        /**
         * 获取滚动高度
         * @return {[type]} [description]
         */
        function getScrollTop() {
            if (document.documentElement && document.documentElement.scrollTop) {
                return document.documentElement.scrollTop;
            } else if (document.body) {
                return document.body.scrollTop;
            }
        }

        /**
         * 获取文档高度
         * @return {[type]} [description]
         */
        function getClientHeight() {
            if (document.body.clientHeight && document.documentElement.clientHeight) {
                return (document.body.clientHeight < document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
            } else {
                return (document.body.clientHeight > document.documentElement.clientHeight) ? document.body.clientHeight : document.documentElement.clientHeight;
            }
        }
    })();
    //分享
    (function () {
        var $testList = $('#J_test_list');
        $testList.on('click', '.wxEnjoy', function () {
            var popup = '<div class="popup-mask"><img src="/resource/img/dbzhijun/enjoywx.png" alt=""/> </div>';
            $('body').append(popup);

            //set wechat share
            var itemId = ($(this).attr("data-pro"));
            var shareurl = "http://act.wetolink.com/mall/detail?item=" + itemId;
            var shareimg = $(this).closest('li').find('img').attr('src');
            var sharetitle = $(this).closest('li').find('.shop-des').text();

            var jsapi = JSON.parse($("#wechat_share").text());
            jsapi.sharetext = (sharetitle);
            jsapi.shareurl = (shareurl);
            jsapi.shareimg = (shareimg);

            $("#wechat_share").text(JSON.stringify(jsapi));
            share_wexin();
        });
        $('body').on('click', '.popup-mask', function () {
            $(this).remove();
        });
    })();
    //夺宝
    (function () {
        //投注
        var max = $("#user_score").val();
        if ($.trim(max) == "") {
            max = 0;
        }

        var html = '<div class="popup-mask1">';
        html += '<div class="message">';
        html += ' <h3>人次期数选择</h3>';
        html += ' <div class="num-box clearfix">';
        html += ' <div class="des">参与次数</div>';
        html += ' <div class="btns">';
        html += ' <div class="sub">-</div>';
        html += ' <div class="text">' + max + '</div>';
        html += ' <div class="add">+</div>';
        html += ' </div>';
        html += ' <div class="des" id="pay-error-tips"><!--(你拥有xxxx次投注机会)--></div>';
        html += ' </div>';
        html += ' <div class="btn-snatch">';
        html += ' 立即夺宝';
        html += ' </div>';
        html += ' <div class="close"></div>';
        html += ' </div>';
        html += ' </div>';
        var dataPro, dataId;
        $('body').on('click', '.duobao', function () {
            dataId = $(this).attr('data-pro');
            $('body').append(html);
            dataPro = $(this).attr('data-pro');
        });
        $('body').on('click', '.close', function () {
            $('.popup-mask1').remove();
        });

        var $testList = $('body');
        var num;
        $testList.on('click', '.sub', function () {
            var $text = $('.message').find('.text');
            if (!num) {
                num = $text.text();
            }
            num--;
            if (num <= 0) {
                num = 0;
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
            /*if ($.trim(max) == "" || max <= 0) {
                return false;
             }*/

            var $text = $('.message').find('.text');
            var num = $text.text();
            $.get('/mall/buy',{num:num, item:dataId}, function (data) {
                if (data.code == 0) {
                    //success
                    $("#error-tip").remove();
                    var payErrorTip = '<span id="error-tip" style="font-size: smaller; color: red">支付成功<span>';
                    $("#pay-error-tips").append(payErrorTip);
                    $("#error-tip").fadeOut(3000, function () {
                        $("#error-tip").remove();
                        $(".popup-mask, .popup-mask1").remove();
                        document.location.href = "/mall";
                    });
                } else {
                    //fail
                    $("#error-tip").remove();
                    var payErrorTip = '<span id="error-tip" style="font-size: smaller; color: red">支付失败<span>';
                    $("#pay-error-tips").append(payErrorTip);
                    $("#error-tip").fadeOut(3000, function () {
                        $("#error-tip").remove();
                    });
                }
            });
        });
    })();

</script>

</body>
</html>