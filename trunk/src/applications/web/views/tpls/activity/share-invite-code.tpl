<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Tex积分夺宝邀请码</title>
    <link rel="stylesheet" href="/resource/css/share-invite-code.css" type="text/css" media="screen"/>
</head>
<body>
<div class="wrap-info">
    <div style="text-align: center; font-size: 2em"><b>邀请码：<span class="wrap-text">{%$inviteCode%}</b></span></div>
    <div><img src="/resource/img/maga.jpg" style="width: 120px; height: 120px; margin-left: 30%; margin-top: 20px">
    </div>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="/resource/scripts/jquery-1.3.2.min.js"></script>
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
</body>
</html>

