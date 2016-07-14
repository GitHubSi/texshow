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
    <div>
        <input class="input-1" type="text" placeholder="收货人姓名" name="name"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="手机号" name="phone"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="省份" name="provice"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="城市" name="city"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="区县" name="region"/>
    </div>
    <div>
        <input class="input-1" type="text" placeholder="具体地址" name="detail"/>
    </div>
    <div class="but">确定</div>
</div>
</div>
</body>
