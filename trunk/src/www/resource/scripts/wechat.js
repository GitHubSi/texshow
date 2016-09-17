/**
 * Created by acer on 2016/9/17.
 */

$(function(){
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
})
