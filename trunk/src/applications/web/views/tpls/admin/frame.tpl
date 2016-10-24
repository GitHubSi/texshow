<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link rel="stylesheet" href="/resource/css/admin/frame.css"/>
    <link rel="stylesheet" href="/resource/css/admin/admin.css"/>
    <link rel="stylesheet" href="/resource/css/resp_index.css"/>
    <link href="/resource/css/response.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'/>
    <link href="/resource/jsoneditor/dist/jsoneditor.css" rel="stylesheet" type="text/css">

    <script src="/resource/jsoneditor/dist/jsoneditor.js"></script>
    <script src="http://libs.baidu.com/jquery/1.7.0/jquery.min.js"></script>
</head>
<body>
<div class="tips">
    <strong>Tex微信管理后台</strong>
    <span style="margin-left: 30px; font-size: small">今日关注人数: {%$subscribe%}</span>
    <span style="margin-left: 30px; font-size: small">今日取消关注人数: {%$unsubscribe%}</span>
</div>
<div class="sidebar">
    <div class="parent">
        <ul>
            <li><a href="/response/detail">微信自动回复</a></li>
            <li><a href="/redPacketSetting/index">红包配置</a></li>
        </ul>
    </div>
</div>

<div class="content">
    {%include file=$tpl%}
</div>

</body>