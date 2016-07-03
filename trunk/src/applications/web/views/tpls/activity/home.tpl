<head>
    <style>
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
</head>
<body>
<div style="margin:0;max-width:640px;position:relative">
    <img src="/resource/img/hometitle.jpg" style="width:100%; position: relative">

    <div style="width: 100%;">
        <div style="margin-left:6%; position: relative;margin-top: 4px; width: 20%; float: left">
            <img src="{%$userInfo.headimgurl%}" style="position: relative; width: 100%;">
        </div>
        <div style="float: left; width: 44%; margin-left: 10px;padding-top: 16px;">
            <div><span>{%$userInfo.nickname%}</span></div>
            <div style="margin-top: 5px"><span style="color: red">可兑换积分：</span>{%$score%}</div>
        </div>
        <div style="width: 26%; float: left; padding-top: 30px;">
            <img src="/resource/img/exchange.jpg" style="width: 100%"/>
        </div>
    </div>
    <div style="width: 100%; background-color: #700000; margin-top: 10px; clear: both">
        <div style="width: 100%; height: 30px; line-height: 30px; color: white; text-align: center; padding: 3px"><b>你邀请的好友</b>
        </div>
        <div style="width: 90%;background-color: #ffffff; margin-left: 5%; margin-right: 5%; height: 100%">
            {%if empty($salveList)%}
            <div style="text-align: center; padding: 5px">
                暂时没有邀请
            </div>
            {%else%}
            <ul>
                {%foreach $salveList as $salve%}
                <li>
                    <div style='background-image: url("{%$salve.headimgurl%}"); float: left'></div>
                    <div style='float: left'>{%$salve.nickname%}</div>
                    <div style='float: left'>提醒</div>
                </li>
                {%/foreach%}
            </ul>
            {%/if%}
        </div>
    </div>
</div>
</body>