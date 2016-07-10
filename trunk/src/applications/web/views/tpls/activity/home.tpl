<head>
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
            padding:24px 0 0 6%;
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
            margin: 0 -10px -10px -10px;
            clear: both;
            overflow: hidden;
        }

        .invite .tips {
            height: 20px;
            padding: 6px;
            color: white;
            text-align: center;
        }

        .invite .flist {
            background-color: #ffffff;
            padding-top: 2px;
            height: 360px;
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
            float: left;
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

        li .fcenter {
            text-align: center;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
</head>
<body>
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
        <div class="flist"">
            <ul>
                {%if empty($salveList)%}
                    <li>暂时没有邀请</li>
                {%else%}
                {%foreach $salveList as $salve%}
                    <li>
                        <div class="fname">{%$salve.nickname%}</div>
                        <div class="fmagazine"><b>未关注订阅号</b></div>
                        <div class="ftips"></div>
                    </li>
                {%/foreach%}
                {%/if%}
            </ul>
        </div>
    </div>
</div>
</body>

<script type="application/javascript">

</script>