<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>科技频道H5</title>
    <meta name="keywords" content=""/>
    <meta name="description" content=""/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no, email=no"/>
    <script>
        (function (doc, win) {
            var _root = doc.documentElement,
                    resizeEvent = 'orientationchange' in window ? 'orientationchange' : 'resize',
                    resizeCallback = function () {
                        var clientWidth = _root.clientWidth,
                                fontSize = 10 * 10;
                        if (!clientWidth) return;
                        if (clientWidth < 1080) {
                            fontSize = 100 * (clientWidth / 750);
                        } else {
                            fontSize = 100 * (1080 / 750);
                        }
                        _root.style.fontSize = fontSize + 'px';
                    };
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvent, resizeCallback, false);
            doc.addEventListener('DOMContentLoaded', resizeCallback, false);
        })(document, window);
    </script>
</head>
<body>
<!-- loading begin -->

<style>
    /*reset*/
    @charset "UTF-8";
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    html{background-color: #fff;} html, body, div, section, article, aside, header, hgroup, footer, nav, h1, h2, h3, h4, h5, h6, p, blockquote, address, time, span, em, strong, img, ol, ul, li, figure, canvas, video, th, td, tr, input, textarea {
        margin: 0;
        padding: 0;
        border: 0;
        list-style: none;
        outline: 0;
    }

    address, article, aside, canvas, details, figcaption, figure, footer, header, hgroup, nav, menu, nav, section, summary {
        display: block;
    }

    body{background-repeat: no-repeat; background-position: center top;background-image: none;Font-family: Helvetica, Tahoma, Arial, "Microsoft YaHei", "微软雅黑", SimSun, "宋体", Heiti, "黑体", sans-serif;
        margin: 0 auto; text-align:left;
        line-height: 1.6; color: #555555; } img {
        max-width: 100%;
        border: none;
        height: auto;
        width: auto
    }

    a:hover{text-decoration: none;}
        /*load*/
    .load{background:#35757b;height:100%;width:100%;position:absolute;z-index:30000;top:0;left:0} .load .icon

    {position:absolute;top:40%;left:50%;margin-top:-50px;margin-left:-50px;width:100px;height:100px;-webkit-animation:shun360 2s linear infinite;-moz-animation:shun360 2s linear infinite;-o-animation:shun360 2s linear infinite;animation:shun360 2s linear infinite
        }
    @-webkit-keyframes shun360 {
    100%{-webkit-transform:rotate(360deg)}
    }

    @-moz-keyframes shun360 {
    100%{-moz-transform:rotate(360deg)}
    }

    @-o-keyframes shun360 {
    100%{-o-transform:rotate(360deg)}
    }

    @keyframes shun360 {
    100%{transform:rotate(360deg)}
    }

    .load_num{position:absolute;width:100%;text-align:center;font-size:15px;top:50%;left:0;color:#f54444} .load_num{position:absolute;width:100%;text-align:center;font-size:15px;top:60%;left:0;color:#fff} .load_txt{position:absolute;width:100%;text-align:center;font-size:15px;top:50%;left:0;color:#fff}

        /*首屏背景图*/
    #layout_1.anim, #layout_2.anim{transition:top 0.5s linear; -webkit-transition:top 0.5s linear; -moz-transition:top 0.5s linear; -ms-transition:top 0.5s linear; -o-transition:top 0.5s linear;} #layout_1{top:0; width: 100%; height: 100%; position:absolute;} #layout_1.fadeOut{top:-100%;z-index:999} #layout_2.fadeIn{top:0;}

        /*箭头*/
    .swipeTip {
        position: absolute;
        left: 50%;
        bottom: 3.05rem;
        width: .91rem;
        height: .73rem;
        margin-left: -.455rem;
        background: url(/resource/img/ballot/up.png) no-repeat;
        -webkit-background-size: 100% 100%;
        background-size: 100% 100%;
        z-index: 999
    }

    .swipeTip

    {-webkit-animation:1s fadeOutUp ease-in-out infinite;-moz-animation:1s fadeOutUp ease-in-out infinite}
    @-webkit-keyframes fadeOutUp {
    0%{opacity:1;-webkit-transform:translateY(0px);transform:translateY(0px)} 50 %{opacity:0.3;-webkit-transform:translateY(-10px);transform:translateY(-10px)}
    }

    @keyframes fadeOutUp {
    0%{opacity:1;-webkit-transform:translateY(0);-ms-transform:translateY(0);transform:translateY(0)} 50 %{opacity:0.3;-webkit-transform:translateY(-10px);-ms-transform:translateY(-10px);transform:translateY(-10px)}
    }

    /*第一屏*/
    #layout_1 {background:url(/resource/img/ballot/bg1.jpg) no-repeat; background-size:7.5rem 13.34rem;background-position:0 -1.15rem;-webkit-overflow-scrolling:touch;} #layout_1 .logo, #layout_2 .logo {position: absolute; bottom: .75rem; width: 4.51rem; height: .47rem; left: 50%; margin-left: -2.255rem; } #layout_1 .logo img, #layout_2 .logo img{display:block;} #layout_1 img {width: 100%; height: 100%;} #layout_1 .title-container {position: absolute; top: 2.45rem; left: 50%; width: 5.15rem; height:3.17rem; margin-left: -2.57rem; } #layout_1 .button {position: absolute; bottom: 1.95rem; width: 2.62rem; height: 1.04rem; left: 50%; margin-left: -1.31rem;}
        /*第二屏*/
    #layout_2{top: 100%; width: 100%; height: 100%; position: absolute;background:url(/resource/img/ballot/bg2.jpg) no-repeat 0 0/100% 100%;overflow:hidden;} #layout_2 .content {position: absolute;top:3.47rem;height: 5.64rem; left: 50%; margin-left: -3.02rem;} #layout_2 .title-container {position: absolute; top: .60rem; left: 50%; width: 4.31rem; height: 2.45rem; margin-left: -2.155rem;} #layout_2 .container {width: 3.02rem; height: 2.82rem; float:left; background: rgba(35,50,37,0.4); padding: .45rem 0  0 .60rem; font-size: .24rem; color: #fff; position: relative;margin:.02rem} #layout_2 .top span {display:inline-block;padding-left:.24rem;background:url(/resource/img/ballot/icon.png) no-repeat;background-size:.16rem .21rem;background-position:0 .07rem} #layout_2 .center {
        font-size: .37rem;
        margin-top: .17rem;
        font-weight: bold;
    }

    #layout_2 .bottom {position: absolute; bottom: .50rem; font-size: .347rem;} #layout_2 .bottom img {width:.27rem; height: .32rem; display: inline-block; vertical-align: top;}
        /*主内容区域*/

        /*第三屏*/
    #layout_3 {width: 100%;height: 100%; position: absolute; top: 0; overflow: auto;display:none;-webkit-overflow-scrolling:touch; } #layout_3 img {width: 100%; height: 100%;display:block;} #layout_3 {background:url(/resource/img/ballot/bg2.jpg) no-repeat; background-size:100% 100%; } #layout_3 .title-container {width: 4.31rem; height: 3.05rem; margin: 0 auto; padding-top: .6rem;} #layout_3 .text {width: 5.26rem; height: .23rem; margin: .32rem auto 0;} #layout_3 .content {width: 5.28rem;margin: .24rem auto 0;} #layout_3 .content:after{content:".";display:block;height:0;clear:both;visibility:hidden;} #layout_3 .container {width: 2.52rem; height: 3.21rem; float: left; margin-bottom: .26rem; font-size: .24rem; color: #fff;} #layout_3 .container:nth-child(odd) {margin-right: .12rem;} #layout_3 .container:nth-child(even) {margin-left: .12rem;} #layout_3 .container .top {width: 100%; height: 2.15rem; position: relative;text-align:center;z-index:9} #layout_3 .container .bottom {width: 2.52; height: 1.06rem; position: relative;line-height:.34rem;overflow:hidden;font-size:.24rem;background-color: #4edcd7;} #layout_3 .container .bottom p{width: 1.6rem;margin: .23rem auto 0;height:.6rem;overflow: hidden;} #layout_3 .container .tiket {position: absolute;top: .55rem; display: inline-block;left: 0; right: 0; text-align: center; } #layout_3 .container .star {margin-top: -.12rem;display: inline-block; height:.29rem; text-align: center;padding-left:.35rem;background:url(/resource/img/ballot/star.png) no-repeat;background-size:.26rem .24rem;margin-top:1.1rem;background-position:0 .05rem;position:relative;} #layout_3 .container .top img{width:2.52rem;height:2.14rem;overflow:hidden;position:absolute;top:0;left:0;z-index:0} #layout_3 .logo {width: 4.51rem; height: .47rem; margin: .30rem auto 0.37rem;} #layout_3 .button {width: 1.98rem; height: .79rem; margin: .30rem auto 0;}

        /*第四屏*/
    #layout_4{width:100%;height:100%;position:absolute;top:0;left:0;text-align:center;} #layout_4 .layout_mask{width:100%;height:100%;position:fixed;top:0;left:0;bottom:0;right:0;background:#000;opacity:0.7} #layout_4 .brief-error, #layout_4 .brief-success, #layout_4 .brief-erweima{position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);display:none} #layout_4 .brief-error{width:4.14rem} #layout_4 .brief-error .brief{width:4.14rem;height:1.88rem;overflow:hidden;} #layout_4 .brief-error .btn{width:2.62rem;height:1.04rem;overflow:hidden;} #layout_4 .brief-success{width:1.87rem;height:1.15rem;overflow:hidden;} #layout_4 .brief-success .brief{width:1.87rem;height:1.15rem;overflow:hidden;} #layout_4 .brief-erweima{width:4.91rem;height:4.72rem;overflow:hidden;} #layout_4 .brief-erweima .erweima{width:4.91rem;height:4.72rem;overflow:hidden;}
        /*横屏提示*/
    .mask{width:100%;height:100%;background:#32373b;position:absolute;left:0;top:0;z-index:99999;display:none} .mask-box{width:100%;height:252px;position:absolute;top:50%;left:0;margin-top:-119px} .mask-pic{width:194px;height:194px;margin:0 auto;text-align:center} .mask span{font-size:22px;display:block;color:#ffd40a;text-align:center;width:100%;height:25px;padding-top:10px} .mask i

    {width:119px;height:199px;background:url(http://y2.ifengimg.com/7573d3fe499db027/2015/0120/bg6.png) 0 0 no-repeat;background-size:119px 199px;display:block;margin:0 auto;-webkit-animation:maskAni 1.5s ease infinite alternate;animation:maskAni 1.5s ease infinite alternate}
    @-webkit-keyframes maskAni {
    0% {-webkit-transform:rotate(-90deg)} 30 % {-webkit-transform:rotate(-90deg)} 70 %{-webkit-transform:rotate(0deg)} 100 % {-webkit-transform:rotate(0deg)}
    }

    @media all and (orientation: landscape) {
        .mask

    {display:block}
    }


</style>


<section class="load">
    <div class="icon">
        <img src="http://p1.ifengimg.com/ae2b95e1d35710ab/2016/31/olympic160803_logo300.png" width="100%">
    </div>
    <div class="load_txt">
        凤凰视频
    </div>
    <div class="load_num"></div>
</section>
<!-- loading end -->


<!-- page 1 S -->
<section class="layout anim" id="layout_1">
    <div class="title-container">
        <img src="/resource/img/ballot/title.png"/>
    </div>
    <div class="button">
        <img src="/resource/img/ballot/button.png"/>
    </div>
    <div class="logo">
        <img src="/resource/img/ballot/logo.png"/>
    </div>
    <!-- 箭头 S -->
    <div class="swipeTip fadeOutUp"></div>
    <!-- 箭头 E -->
</section>
<!-- page 1 E -->

<!-- page 2 S -->
<section class="layout anim" id="layout_2">
    <div class="title-container">
        <img src="/resource/img/ballot/title2.png"/>
    </div>
    <div class="content">
        <div class="container production">
            <div class="top">
                <span>年度</span>
            </div>
            <h1 class="center">十佳产品</h1>
            <div class="bottom">
                <span>投票</span>
                <img src="/resource/img/ballot/arrow.png"/>
            </div>
        </div>
        <div class="container strong_company">
            <div class="top">
                <span>年度</span>
            </div>
            <h1 class="center">实力公司</h1>
            <div class="bottom">
                <span>投票</span>
                <img src="/resource/img/ballot/arrow.png"/>
            </div>
        </div>
        <div class="container technology">
            <div class="top">
                <span>年度</span>
            </div>
            <h1 class="center">技术创新</h1>
            <div class="bottom">
                <span>投票</span>
                <img src="/resource/img/ballot/arrow.png"/>
            </div>
        </div>
        <div class="container new_company">
            <div class="top">
                <span>年度</span>
            </div>
            <h1 class="center">潜力公司</h1>
            <div class="bottom">
                <span>投票</span>
                <img src="/resource/img/ballot/arrow.png"/>
            </div>
        </div>
    </div>
    <div class="logo">
        <img src="/resource/img/ballot/logo.png"/>
    </div>
</section>

<!-- page 2 E -->

<!-- page 3 S -->
<section id="layout_3">
    <div class="title-container">
        <img src="/resource/img/ballot/title2.png"/>
    </div>
    <div class="text">
        <img src="/resource/img/ballot/text.png"/>
    </div>

    <ul class="content production">
        {%foreach $result[1] as $item%}
        <li class="container">
            <div class="top">
                <img src="{%$item.msg.img_url%}" alt="">
                <span class="tiket">{%$item.liked%}票</span>
                <p class="star">投票</p>
            </div>
            <div class="bottom">
                <p>{%$item.msg.name%}</p>
            </div>
        </li>
        {%/foreach%}
    </ul>

    <ul class="content strong_company">
        {%foreach $result[4] as $item%}
        <li class="container">
            <div class="top">
                <img src="{%$item.msg.img_url%}" alt="">
                <span class="tiket">{%$item.liked%}票</span>
                <p class="star">投票</p>
            </div>
            <div class="bottom">
                <p>{%$item.msg.name%}</p>
            </div>
        </li>
        {%/foreach%}
    </ul>

    <ul class="content technology">
        {%foreach $result[2] as $item%}
        <li class="container">
            <div class="top">
                <img src="{%$item.msg.img_url%}" alt="">
                <span class="tiket">{%$item.liked%}票</span>
                <p class="star">投票</p>
            </div>
            <div class="bottom">
                <p>{%$item.msg.name%}</p>
            </div>
        </li>
        {%/foreach%}
    </ul>

    <ul class="content new_company">
        {%foreach $result[3] as $item%}
        <li class="container">
            <div class="top">
                <img src="{%$item.msg.img_url%}" alt="">
                <span class="tiket">{%$item.liked%}票</span>
                <p class="star">投票</p>
            </div>
            <div class="bottom">
                <p>{%$item.msg.name%}</p>
            </div>
        </li>
        {%/foreach%}
    </ul>
    <div class="button">
        <img src="/resource/img/ballot/return.png"/>
    </div>
    <div class="logo">
        <img src="/resource/img/ballot/logo.png"/>
    </div>
</section>
<!-- page 3 E -->

<!-- page4 S-->
<section id="layout_4" style="display:none;z-index:999">
    <div class="layout_mask"></div>
    <div class="brief-error">
        <img src="/resource/img/ballot/error-text.png" alt="" class="brief">
        <a href="#">
            <img src="/resource/img/ballot/btn2.png" alt="" class="btn">
        </a>
    </div>
    <div class="brief-success">
        <img src="/resource/img/ballot/success-text.png" alt="" class="brief">
    </div>
    <div class="brief-erweima">
        <img src="/resource/img/ballot/erweima.png" alt="" class="erweima">
    </div>
</section>
<!-- page4 E--

<!-- 横屏提示 S -->
<div class="mask flexcontainer" id="mask">
    <div class="mask-box">
        <div class="mask-pic">
            <i></i>
        </div>
        <span>为了更好的体验，请将手机/平板竖过来</span>
    </div>
</div>
<!-- 横屏提示 E -->
<script src="http://y0.ifengimg.com/base/jQuery/jquery-1.9.1.min.js"></script>
<script src="http://p1.ifengimg.com/29b92e35b2b20708/2016/41/jquery.touch.js"></script>
<script src="/resource/js/ballot/20161231kj-main.js"></script>

<script>
    /* loadImg  图片预加载 */
    var loadImg = function (pics, callback) {
        var index = 0;
        var len = pics.length;
        var img = new Image();
        var flag = false;
        var progress = function (w) {
            $('.loadNum').html(w);
            if (w == '100%') {
                callback()
            }
        }
        var load = function () {
            img.src = pics[index];
            img.onload = function () {
                progress(Math.floor(((index + 1) / len) * 100) + "%");
                index++;
                if (index < len) {
                    load();
                } else {
                    callback()
                }
            }
            return img;
        }
        if (len > 0) {
            load();
        } else {
            progress("100%");
        }
        return {
            pics: pics,
            load: load,
            progress: progress
        };
    }
    // 页面要加载的图片
    var pics = [
        '/resource/img/ballot/bg1.jpg',
        '/resource/img/ballot/bg2.jpg'
    ];
    // 调用
    loadImg(pics, function () {
        setTimeout(function () {
            $(".load").hide();
            $('#layout_1').addClass('animate');
        }, 500);
    });

    //================make redirect to different map:start=============
    $('#layout_2 .content .production').click(function () {
        $('#layout_1,#layout_2').hide();
        $('#layout_3').show();

        $('#layout_3').find("ul").each(function (index, elem) {
            $(this).hide();
        });
        $('#layout_3 .production').show();
    })

    $('#layout_2 .content .strong_company').click(function () {
        $('#layout_1,#layout_2').hide();
        $('#layout_3').show();

        $('#layout_3').find("ul").each(function (index, elem) {
            $(this).hide();
        });
        $('#layout_3 .strong_company').show();
    })

    $('#layout_2 .content .technology').click(function () {
        $('#layout_1,#layout_2').hide();
        $('#layout_3').show();

        $('#layout_3').find("ul").each(function (index, elem) {
            $(this).hide();
        });
        $('#layout_3 .technology').show();
    })

    $('#layout_2 .content .new_company').click(function () {
        $('#layout_1,#layout_2').hide();
        $('#layout_3').show();

        $('#layout_3').find("ul").each(function (index, elem) {
            $(this).hide();
        });
        $('#layout_3 .new_company').show();
    })
    //============================== end =====

    $('#layout_3 .button').click(function () {
        $('#layout_3').hide();
        $('#layout_2').show();
    })

    $('#layout_3 .container').click(function () {
        $('#layout_4').show()
        $('#layout_4 .brief-error').show()
    })

    $('#layout_4 .layout_mask').click(function () {
        $('#layout_4').hide()
    })
</script>

<!-- 页尾通栏  -->
</body>
</html>
