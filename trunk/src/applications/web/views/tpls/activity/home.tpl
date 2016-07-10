<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script type="text/javascript" src="/resource/js/iscroll.js"></script>
    <script type="text/javascript" src="/resource/js/jquery.min.js"></script>
    <script type="application/javascript">
        var myScroll,
                pullDownEl, pullDownOffset,
                pullUpEl, pullUpOffset,
                generatedCount = 0;

        function pullUpAction() {
            setTimeout(function () {	// <-- Simulate network congestion, remove setTimeout from production!
                var lastId = $("ul li:last-child").val()  //最后一个li标签

                var completeBeforeRequest = $("#ajax_req").val();
                if (completeBeforeRequest == 0) {
                   return false;
                }
                $("ajax_req").val(0);

                $.ajax({
                    url: "/home/listuser",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "last_id": lastId,
                    },
                    success: function (data, status) {
                        if (!data) {
                            return false;
                        }
                        $.each(data, function (index, value) {
                            var line = '<li class="row" value="' + value.id + '">'
                                    + '<div class="fname">' + value.nickname + '</div>'
                                    + '<div class="fmagazine"><b>未关注订阅号</b></div>'
                                    + '<div class="ftips"></div></li>';
                            $("#userlist").append(line);
                        });
                        $("#ajax_req").val(1);
                    }
                });

                myScroll.refresh();		// Remember to refresh when contents are loaded (ie: on ajax completion)
            }, 1000);	// <-- Simulate network congestion, remove setTimeout from production!
        }

        function pullDownAction() {
            return false;
        }

        function loaded() {
            pullDownEl = document.getElementById('pullDown');
            pullDownOffset = pullDownEl.offsetHeight;
            pullUpEl = document.getElementById('pullUp');
            pullUpOffset = pullUpEl.offsetHeight;

            myScroll = new iScroll('wrapper', {
                useTransition: true,
                topOffset: pullDownOffset,
                onRefresh: function () {
                    if (pullDownEl.className.match('loading')) {
                        pullDownEl.className = '';
                        pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Pull down to refresh...';
                    } else if (pullUpEl.className.match('loading')) {
                        pullUpEl.className = '';
                        pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Load...';
                    }
                },
                onScrollMove: function () {
                    if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip')) {
                        pullUpEl.className = 'flip';
                        pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Loading...';
                        this.maxScrollY = this.maxScrollY;
                    } else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip')) {
                        pullUpEl.className = '';
                        pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Load...';
                        this.maxScrollY = pullUpOffset;
                    }
                },
                onScrollEnd: function () {
                    if (pullUpEl.className.match('flip')) {
                        pullUpEl.className = 'loading';
                        pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Loading...';
                        pullUpAction();	// Execute custom function (ajax call?)
                    }
                }
            });

            setTimeout(function () {
                document.getElementById('wrapper').style.left = '0';
            }, 800);
        }

        document.addEventListener('touchmove', function (e) {
            e.preventDefault();
        }, false);

        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(loaded, 200);
        }, false);

    </script>
    <style>
        body {
            overflow-x: hidden;
        }

        .content {
            position: relative;
            margin: 0 auto;
            width: 100%;
            max-width: 640px;
            background-color: #ffffff;
        }

        .header-1 {
            background-image: url('/resource/img/hometitle.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 50px;
            margin: -10px -10px 0 -10px;
        }

        .wrap {
            margin: 10px 0 10px 0;
            clear: both;
            overflow: hidden;
        }

        .header-2 {
            width: 20%;
            margin: 0 0 0 5%;
            height: 80px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            z-index: 2;
            float: left;
        }

        .header-3 {
            width: 40%;
            padding: 24px 0 0 6%;
            float: left;
            font-style: normal;
        }

        .header-3 .nickname {
            font-size: 16px;
        }

        .header-3 .score {
            margin-top: 6px;
            font-size: 12px;
            color: #990000;
        }

        .header-4 {
            width: 25%;
            height: 24px;
            margin: 30px 0 0 0;
            float: left;
            background-image: url('/resource/img/exchange.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .invite {
            background-color: #990000;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            clear: both;
            position: absolute;
            width: 100%;
        }

        .invite .tips {
            background-color: #990000;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            margin: 0 -10px 0;
            height: 20px;
            padding: 6px;
            color: white;
            text-align: center;
        }

        #wrapper {
            position: absolute;
            z-index: 3;
            background-color: #ffffff;
            padding-top: 2px;
            height: 500px;
            width: 100%;
        }

        #scroller {
            width: 100%;
        }

        ul {
            list-style: none;
            margin: 0px;
        }

        li {
            list-style-type: none;
            width: 100%;
            height: 30px;
            line-height: 30px;
            margin: 3px;
            display: block;
        }

        li .fname {
            width: 25%;
            height: 30px;
            float: left;
            overflow: hidden;
        }

        li .fmagazine {
            width: 40%;
            margin-left: 12px;
            float: left;
            color: #990000;
        }

        li .ftips {
            width: 20%;
            height: 25px;
            text-align: right;
            margin-left: 6px;
            float: left;
            background-image: url('/resource/img/invite.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        #pullDown, #pullUp {
            background: #fff;
            height: 40px;
            line-height: 40px;
            padding: 5px 10px;
            border-bottom: 1px solid #ccc;
            font-weight: bold;
            font-size: 14px;
            color: #888;
        }

        #pullDown .pullDownIcon, #pullUp .pullUpIcon {
            display: block;
            float: left;
            width: 40px;
            height: 40px;
            background: url('/resource/img/pull-icon@2x.png') 0 0 no-repeat;
            -webkit-background-size: 40px 80px;
            background-size: 40px 80px;
            -webkit-transition-property: -webkit-transform;
            -webkit-transition-duration: 250ms;
        }

        #pullDown .pullDownIcon {
            -webkit-transform: rotate(0deg) translateZ(0);
        }

        #pullUp .pullUpIcon {
            -webkit-transform: rotate(-180deg) translateZ(0);
        }

        #pullDown.flip .pullDownIcon {
            -webkit-transform: rotate(-180deg) translateZ(0);
        }

        #pullUp.flip .pullUpIcon {
            -webkit-transform: rotate(0deg) translateZ(0);
        }

        #pullDown.loading .pullDownIcon, #pullUp.loading .pullUpIcon {
            background-position: 0 100%;
            -webkit-transform: rotate(0deg) translateZ(0);
            -webkit-transition-duration: 0ms;

            -webkit-animation-name: loading;
            -webkit-animation-duration: 2s;
            -webkit-animation-iteration-count: infinite;
            -webkit-animation-timing-function: linear;
        }

        @-webkit-keyframes loading {
            from {
                -webkit-transform: rotate(0deg) translateZ(0);
            }
            to {
                -webkit-transform: rotate(360deg) translateZ(0);
            }
        }
    </style>
</head>

<body>
<input type="hidden" id="ajax_req" value="1"/>

<div class="content">
    <div class="header-1"></div>

    <div class="wrap">
        <div class="header-2" name="headimg" style="background-image: url({%$userInfo.headimgurl%});"></div>
        <div class="header-3" name="userinfo">
            <div class="nickname"><span>{%$userInfo.nickname%}</span></div>
            <div class="score"><span>可兑换积分：</span>{%$score%}</div>
        </div>
        <div class="header-4" name="exchange" id="exchange"></div>
    </div>

    <div class="invite">
        <div class="tips"><b>你邀请的好友</b></div>
        <div id="wrapper">
            <div id="scroller">
                <div id="pullDown"></div>
                <ul id="userlist">
                    {%if empty($salveList)%}
                    <li>暂时没有邀请</li>
                    {%else%}
                    {%foreach $salveList as $salve%}
                    <li class="row" value="{%$salve.id%}">
                        <div class="fname">{%$salve.nickname%}</div>
                        <div class="fmagazine"><b>未关注订阅号</b></div>
                        <div class="ftips"></div>
                    </li>
                    {%/foreach%}
                    {%/if%}
                </ul>
                <div id="pullUp">
                    <span class="pullUpIcon"></span><span class="pullUpLabel">查看更多.</span>
                </div>
            </div>
        </div>
        <div id="footer"></div>
    </div>
</div>
</div>
</body>
