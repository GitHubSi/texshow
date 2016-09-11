<!DOCTYPE HTML>
<html lang="en" dir="ltr">
<head>
    <title>Tex积分夺宝</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="/resource/css/share-iphone.css?a=129"/>
    <!--SET MEDIA HANDELD HERE-->
    <!--<link rel="stylesheet" type="text/css" href="/resource/css/jquery-ui.css"/>-->
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css"/>
    <!--SET MEDIA HANDELD HERE-->
    <script src="/resource/scripts/jquery-1.12.4.js"></script>
    <script src="/resource/scripts/jquery-ui.js"></script>
</head>

<body>
<div id="main-wrapper">
    <header>
        <div id="logo" class="clearfix">
            <figure><a><img src="/resource/img/iphone-header.jpg" width="82" height="82" alt="News Mirror Logo"/></a>
            </figure>
            <div class="top-title">
                <h1>全新iPhone 7手机</h1>

                <div class="site-description" style="width:200px; overflow-x: hidden">
                    <!--<input type="button" value="分享"/>
                    <input type="button" value="夺宝"/> -->
                    {%if $noRegister eq 0%}
                    当前可用&nbsp;{%$userInfo.score%}&nbsp;积分<a style="text-decoration:none"
                                                            href="http://act.wetolink.com/shareInviteCode/">(邀请好友关注公众号即可获取积分)。
                        {%elseif $noRegister eq 1%}
                        尚未关注订阅号，关注订阅号即可参与活动哦！还可以邀请好友获得更多机会。
                        {%/if%}
                </div>
                <input type="hidden" id="cur-score" value="{%$userInfo.score%}"/>
                <input type="hidden" id="no-register" value="{%$noRegister%}"/>
            </div>
            <!--/.top-title-->
        </div>
        <!--/#logo-->
    </header>

    <section class="main-content">
        <section class="listing">
            <article class="clearfix">
                <div class="text">
                    <h3><a>当前总投注数量：{%$good.current_score%}/{%$good.total_score%}</a></h3>
                </div>
                <!--/.text-->
            </article>
            <article class="clearfix">
                <div class="text">
                    <h3><a>时间：{%$startTime%} - {%$endTime%}</a></h3>
                </div>
                <!--/.text-->
            </article>
            <article class="clearfix">
                <div class="text">
                    <form action="/shareItem/buy" method="post" enctype="application/x-www-form-urlencoded">
                        <input type="text" name="rob_num" placeholder="输入夺宝次数"/>
                        <input type="submit" id="submit-iphone" value="立即夺宝"/>
                    </form>
                </div>
                <!--/.text-->
            </article>
        </section>
        <section class="listing">
            <div class="detail" style='background-image: url("/resource/img/back.jpg");'></div>
        </section>
    </section>
    <!--/.main-content-->
</div>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<textarea id="wechat_share" style="display: none">{%$jsapi%}</textarea>
<script type="application/javascript">
    var shareData = $("#wechat_share").val();
    var wechatInfo = JSON.parse(shareData);
    var obj = {
        // debug: true,
        appId: wechatInfo.appId,
        timestamp: wechatInfo.timestamp,
        nonceStr: wechatInfo.noncestr,
        signature: wechatInfo.signature,
        jsApiList: ['hideMenuItems', 'onMenuShareTimeline', 'onMenuShareAppMessage']
    };

    wx.config(obj);
    wx.ready(function () {
        if (wechatInfo.sharetext) {
            var shareObj = {
                title: wechatInfo.sharetext,
                link: wechatInfo.shareurl,
                imgUrl: wechatInfo.shareimg,
                desc: wechatInfo.sharedesc
            };
            wx.onMenuShareAppMessage(shareObj);
            wx.onMenuShareTimeline(shareObj);
        }
        wx.hideMenuItems({
            menuList: [
                "menuItem:readMode",
            ]
        });
    });
</script>

<div id="dialog" title="提醒">
    <p id="dialog-content">您当前积分为0，邀请好友关注订阅号，即可获得更多积分哦！</p>
    <div><img src="/resource/img/maga.jpg" style="width: 100px; height: 100px; margin-left: 30%;margin-top: 15px;"/>
    </div>
</div>

<script>
    $("#dialog").dialog({autoOpen: false});
    $("#submit-iphone").click(function () {
        var noRegister = $("#no-register").val();
        var score = $("#cur-score").val();
        if (noRegister == 1) {
            $("#dialog-content").html("您当前尚未关注Tex订阅号，关注订阅号（ifengdigi）即可获得积分，参与积分夺iPhone 7活动!");
            $("#dialog").dialog("open");
            return false;
        }
    });
</script>
</body>
</html>
