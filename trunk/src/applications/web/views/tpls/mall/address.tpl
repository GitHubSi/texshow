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
    <title>收货地址</title>
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
                        } else {
                            docEl.style.fontSize = 100 * (clientWidth / 720) + 'px';
                        }
                    };
            // Abort if browser does not support addEventListener
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            recalc();
        })(document, window);
    </script>
    <style>
        .group-address {
            font-size: 16px;
            color: #999999;
        }

        .group-address .item-input {
            background-color: #ffffff;
            border-bottom: 1px solid #d5d5d5;
            font-size: 16px;
            line-height: 43px;
            margin: 0 5px;
        }

        .group-address label {
            float: left;
            margin-left: 20px;
            width: 100px;
            font-family: "Microsoft YaHei" ! important;
            color: #1A1A1A;
        }

        .group-address .wrap-input {
            overflow: hidden;
            margin-right: 20px;
        }

        .group-address input {
            width: 100%;
            border: none;
            height: 43px;
            color: #333333;
            font-size: 16px;
        }

        .group-address .expandingArea {
            position: relative;
        }

        .group-address pre {
            display: block;
            visibility: hidden;
        }

        .group-address textarea {
            width: 100%;
            border: none;
            margin-top: 10px;
            line-height: 20px;
            color: #333333;
            font-size: 16px;
            resize: none;
        }

        .group-address .bottom-button {
            max-width: 640px;
            position: fixed;
            width: 90%;
            line-height: 50px;
            bottom: 0;
            z-index: 1;
            border-top: 1px solid #d8d9d9;
            background-color: #ef0f17;
            color: #ffffff;
            text-align: center;
            border-radius: 5px;
            margin: 0 5%;
            bottom: 1px;
        }

        .group-address .bottom-button:link,
        .group-address .bottom-button:visited {
            color: #FFFFFF;
        }
    </style>
</head>
<body>
<div class="group-address">
    <div class="item-input clearfix">
        <label for="user">收货人：</label>

        <div class="wrap-input">
            <input id="user" name="name" type="text" value="{%if !empty($address.name)%}{%$address.name%}{%/if%}"
                   maxlength="10" placeholder="请输入收货人姓名">
        </div>
    </div>
    <div class="item-input clearfix">
        <label for="phone">联系方式：</label>

        <div class="wrap-input">
            <input id="mobile" name="phone" length="11" value="{%if !empty($address.phone)%}{%$address.phone%}{%/if%}"
                   maxlength="11"
                   placeholder="请输入手机号码">
        </div>
    </div>
    <div class="item-input clearfix expandingArea">
        <label for="address">详细地址：</label>

        <div class="wrap-input">
            <textarea id="address" name="addr" rows="7" maxlength="100"
                      placeholder="街道、楼牌号等">{%if !empty($address.address)%}{%$address.address%}{%/if%}
            </textarea>
        </div>
    </div>
    <a id="js-address-commit" href="javascript:;" class="bottom-button">保存收货地址</a>
</div>

<script>
    $("#js-address-commit").click(function () {
        var name = $("#user").val();
        var phone = $("#mobile").val();
        var addr = $("#address").val();

        if (name.length <= 0 && phone.length <= 0 && addr.length <= 0) {
            return false;
        }

        $.ajax({
            type: "GET",
            url: "/mall/modifyAddress?",
            dataType: "json",
            data: {
                "name": name,
                "phone": phone,
                "addr": addr
            },
            success: function (data) {
                var code = data["code"]
                if (code == 0) {
                    document.location.href = "/mall/address";
                }
            },
            error: function () {
                console.log('fail');
            },
            complete: function () {
            }
        });
    });
</script>
</body>
</html>