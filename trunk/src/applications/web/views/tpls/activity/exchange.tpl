<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script type="text/javascript" src="/resource/js/jquery.min.js"></script>
    <title>收货地址</title>
    <style>
        body {
            overflow-x: hidden;
            background-color: #efefef;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .content {
            position: relative;
            margin: 50px 0px 0px 0px;
            width: 100%;
            max-width: 640px;
        }

        .input-1 {
            border-radius: 10px 10px;
            border-color: #e3e2e2;
            border-style: solid;
            margin: 20px 6.3% 0px;
            padding: 2px 5px 2px 5px;
            width: 85%;
            height: 45px;
        }

        .but {
            border-radius: 10px 10px;
            background-color: #f72a0b;
            line-height: 45px;
            text-align: center;
            width: 85%;
            height: 45px;
            margin: 20px 6.3% 0px;
        }

    </style>
</head>

<body>
<input type="hidden" id="ajax_req" value="1"/>

<div class="content">
    <input type="hidden" name="prize_id" id="prize_id" value="{%$prizeId%}"/>

    <div>
        <input class="input-1" type="text" placeholder="收货人姓名" name="name" id="name"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="手机号" name="phone" id="phone"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="省份" name="province" id="province"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="城市" name="city" id="city"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="区县" name="region" id="region"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="具体地址" name="detail" id="detail"/>
    </div>
    <div class="but" data="1">确定</div>
</div>

<script type="application/javascript">
    $(".but").click(function () {
        var whetherSub = $(".but").data;
        if (whetherSub == 0) {
            return;
        }
        $(".but").data = 0;

        var prizeId = $("#prize_id").val();
        var name = $("#name").val();
        var phone = $("#phone").val();
        var province = $("#province").val();
        var city = $("#city").val();
        var region = $("#region").val();
        var detail = $("#detail").val();

        if (!prizeId || !name || !phone || !province || !city || !region || !detail) {
            alert("请将信息输入完整之后提交");
            return;
        }

        $.ajax({
            url: "/home/exchangesubmit",
            type: "POST",
            dataType: "json",
            data: {
                "prize_id": prizeId,
                "name": name,
                "phone": phone,
                "province": province,
                "city": city,
                "region": region,
                "detail": detail
            },
            success: function (data, status) {
                if (data.req == 0) {
                    alert("兑换成功");

                } else {
                    alert("兑换失败");
                }
                $(".but").data = 1;
                window.location.href = "/home";
            },
            error: function (xhr, status) {
                $(".but").data = 1;
            }
        });
    });
</script>
</body>
