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
    <title>中奖记录</title>
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
<li class="publish-main">
    <ul id="J_test_list">
        {%if !empty($history)%}
        {%foreach $history as $record%}
        <li class="clearfix" data-pro="{%$record.id%}">
            <div class="img"><img src="/resource/img/dbzhijun/fudai.png" alt=""/></div>
            <div class="info">
                <h3>好运气专用夺宝币礼包－速开</h3>
                <p>期 号：{%$record.batch%}</p>
                <p>获得用户：<span class="b">{%$record.winner%}</span></p>
            </div>
        </li>
        {%/foreach%}
        {%else%}
        <li class="clearfix info">您暂时还没有中奖纪录</li>
        {%/if%}
    </ul>
    </div>
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

    <div id="J_footer" class="footer">
        <p>凤凰夺宝</p>
    </div>
</body>
</html>
<script>
    //  动态加载内容模块
    (function () {
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
                url: "/mall/morehistory?last_id=" + dataId,//每次加载n+1
                dataType: "json",
                beforeSend: function () {
                    $loading.show();
                },
                success: function (data) {
                    n++;
                    var recordHistory = data["data"]
                    if (recordHistory.length == 0) {
                        $loading.hide();
                        $('#J_load_more').show();
                        return false;
                    } else {
                        generateDom(data.data);
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
                html += '<li class="clearfix" data-pro="' + n.id + '">';
                html += '<div class="img"><img src="/resource/img/dbzhijun/fudai.png" alt=""/></div>';
                html += '<div class="info">';
                html += '<h3>好运气专用夺宝币礼包－速开</h3>';
                html += '<p>期       号：' + n.batch + '</p>';
                html += '<p>获得用户：<span class="b">' + n.winner + '</span></p>';
                html += '<p>参与人次：' + n.score + '人次</p>';
                html += '</div>';
                html += ' </li>';
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
</script>
