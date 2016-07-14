<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <script type="text/javascript" src="/resource/js/jquery.min.js"></script>
    <title>积分兑换</title>
    <style>
        body {
            overflow-x: hidden;
            background-image: url('/resource/img/prize.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .content {
            position: relative;
            margin: 100px 0px 0px 0px;
            width: 100%;
            max-width: 640px;
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
            height: 30px;
            float: left;
            overflow: hidden;
        }

        li .fmagazine {
            width: 20%;
            margin-left: 10px;
            float: left;
        }

        li .ftips {
            width: 20%;
            height: 25px;
            text-align: right;
            margin-left: 6px;
            float: left;
            background-image: url('/resource/img/exchange.jpg');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        .ftips-2 {
            width: 20%;
            height: 25px;
            text-align: center;
            margin-left: 6px;
            float: left;
            background-color: #888888;
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

    </style>
</head>

<body>
<input type="hidden" id="ajax_req" value="1"/>

<div class="content">
    <ul>
        <li>
            <div class="fname"><b>奖品名称</b></div>
            <div class="fmagazine" style="margin-left: 5px"><b>数量</b></div>
            <div style="width: 20%; height: 25px; text-align: right; float: left;"><b>积分</b></div>
        </li>
        {%foreach $prizeList as $prize%}
        <li>
            <div class="fname">{%$prize.name%}</div>
            <div class="fmagazine"><b>{%$prize.num%}</b></div>
            <div class="fmagazine"><b>{%$prize.score%}</b></div>
            {%if $score > $prize.score%}
            <div class="ftips-2" style="background-color: #990000" data="1" prize="{%$prize.id%}"><b
                        style="color: white">兑换</b></div>
            {%else%}
            <div class="ftips-2"><b style="color: white">兑换</b></div>
            {%/if%}
        </li>
        {%/foreach%}
    </ul>
</div>
</div>
</body>
<script type="application/javascript">
    $(".ftips-2").click(function () {
        var prizeValid = this.data;
        if (prizeValid) {
            return false;
        } else {
            window.location.href = "/home/exchange";
        }
    });
</script>
