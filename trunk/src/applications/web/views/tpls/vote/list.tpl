<doctype html>
<html lang="en" style="font-size: 100px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta content="black" name="apple-mobile-web-app-status-bar-style">
    <link href="/resource/css/vote/database.css" rel="stylesheet">
    <link href="/resource/css/vote/detail.css" rel="stylesheet">
    <script src="/resource/js/jquery.min.js"></script>
    <title>入围名单</title>
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
<section class="view2">
    <div class="list-title"></div>
    <div class="search">
        <input type="text" id="number" placeholder="输入选手编号">
        <div class="fangdajing" id="search"><i></i></div>
    </div>
    <ul class="clearfix" id="user-list">
        {%foreach $userList as $user%}
        <li data-pro="{%$user.id%}">
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

    <div class="footer clearfix" id="J_footer">
        <div class="img">
            <a href=""><img src="/resource/img/vote/logo1.png" alt=""/></a>
            <a href=""><img src="/resource/img/vote/logo2.png" alt=""></a>
        </div>
    </div>
</section>
</body>
<script>
    (function () {
        /**
         * 上拉加载数据
         * @return {[type]}     [description]
         */
        var $loading = $('#J_loading'),
                n = 1,
                scrollTimer;

        function hideNoMore() {
            var hideNoMore = setTimeout("$('#J_load_more').hide()", 2000);
            return hideNoMore;
        }
        
        function pullUpLoadData() {
            pullRunning = 1;    //表示数据正在拉取

            var lastNo = $("li").last().attr("data-pro");
            var liked = $("li").last().find(".num").text();
            $.ajax({
                type: "GET",
                url: "/vote/more",//每次加载n+1
                dataType: "json",
                data: {
                    last_liked : liked,
                    last_id : lastNo
                },
                beforeSend: function () {
                    $loading.show();
                },
                success: function (data) {
                    n++;
                    var data_len = data.data.length;
                    if (data_len == 0) {
                        $loading.hide("normal", function () {
                            $('#J_load_more').show("normal", function () {
                                hideNoMore();
                            });
                        });
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
                    pullRunning = 0;
                }
            });
        }

        function generateDom(data) {
            var $userList = $('#user-list');
            var html = '';
            $.each(data, function (i, element) {
                html += '<li data-pro="' + element.id + '">';
                html += '<a href="/vote/detail?no=' + element.id + '">';
                html += '<img src="' + element.msg.poster + '" alt="">';
                html += '<p>' + element.number + '号:' + element.msg.name + '</p>';
                html += '<div class="num">' + element.liked + '</div>';
                html += '<a href="/vote/detail?no=' + element.id + '"><div class="btn-detail">详情</div></a>';
                html += '<div class="i-play"></div>';
                html += '<div class="i-no"></div>';
                html += '</li>';
            });
            $userList.append(html);
        }

        var pullRunning = 0;
        $(window).on('scroll', function () {
            var scrollTop = getScrollTop(),
                    footerOT = $('#J_footer').offset().top,
                    cHeight = getClientHeight();
            if (scrollTop + cHeight >= footerOT) {
                if (pullRunning) {
                    return;
                }
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

    //查询
    (function () {
        $("#search").click(function () {
            var number = $('#number').val();
            if (number.length == 0) {
                return false;
            }
            $.ajax({
                type: "POST",
                url: "/vote/find",//每次加载n+1
                dataType: "json",
                data: {
                    no : number
                },
                success: function (data) {
                    if (data.code == 0) {
                        location.href = "http://act.wetolink.com/vote/detail?no=" + data.data;
                        return;
                    }
                },
                error: function () {
                    console.log('fail');
                },
            });
        });
    })();
    //分享
    (function () {
        var $testList = $('#J_test_list');
        $testList.on('click', '.wxEnjoy', function () {
            var popup = '<div class="popup-mask"><img src="img/enjoywx.png" alt=""/> </div>';
            $('body').append(popup);
        });
        $('body').on('click', '.popup-mask', function () {
            $(this).remove();
        });
    })();
</script>
</html>