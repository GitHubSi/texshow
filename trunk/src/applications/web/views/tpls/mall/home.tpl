<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>凤凰夺宝</title>
    <link rel="stylesheet" href="/resource/css/mall/style.css" type="text/css"/>
    <script type="text/javascript" src="/resource/js/jquery.min.js"></script>
    <script type="text/javascript" src="/resource/js/mall/home.js"></script>
</head>
<body>
<div id="main" class="main">
    <!--导航条-->
    <div id="navigation">
        <ul id="list-name">
            <li><a href="#">首页</a></li>
            <li><a href="#">最新揭晓</a></li>
            <li><a href="#">个人中心</a></li>
        </ul>
        <div style="clear: both"></div>
        <ul id="bottom-color">
            <li class="list-bottom-color"></li>
            <li></li>
            <li></li>
        </ul>
    </div>
    <div style="clear: both"></div>

    <!--轮播图-->
    <div id="head-img">
        <ul class="head-img-ul">
            <li><a href="#"
                   style='background-image: url("/resource/img/home/head-img-1.jpg")'></a>
            </li>
            <li><a href="#"
                   style='background-image: url("/resource/img/home/head-img-2.jpg")'></a>
            </li>
            <li><a href="#"
                   style='background-image: url("/resource/img/home/head-img-3.jpg")'></a>
            </li>
        </ul>
        <div id="head-img-btn">
            <ol>
                <li><div class="circle">&nbsp;</div></li>
                <li><div class="circle">&nbsp;</div></li>
                <li><div class="circle">&nbsp;</div></li>
            </ol>
        </div>
    </div>

    <!--商品列表-->
    <div id="shop-list">
        <div id="shop-row" class="shop-row-item">
            <div name="shop-left" style='background-image: url("")'>
                <div name="shop-name"></div>
                <div name="shop-oper">
                    <div name="item-process" class="item item-process">进度</div>
                    <div name="item-share" class="item item-share">分享</div>
                    <div name="item-buy" class="item item-buy">夺宝</div>
                </div>
            </div>
            <div name="shop-right">
                <div name="shop-name"></div>
                <div name="shop-oper">
                    <div name="item-process" class="item item-process">进度</div>
                    <div name="item-share" class="item item-share">分享</div>
                    <div name="item-buy" class="item item-buy">夺宝</div>
                </div>
            </div>
            <div style="clear: both"></div>
        </div>
    </div>

    <!--foot-->
    <div id="buy-box">
        <div>人次期数选择</div>
        <div>
            <div class="btn">参与人次+</div>
            <div class="btn"><input type="text" name="number"/></div>
            <div class="btn">-</div>
        </div>
    </div>
</div>
</body>
</html>