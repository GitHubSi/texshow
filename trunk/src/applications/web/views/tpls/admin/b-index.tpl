<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>管理后台</title>

    <link rel="stylesheet" href="/resource/css/admin/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="/resource/css/admin/font-awesome.css" type="text/css">
    <link rel="stylesheet" href="/resource/jsoneditor/dist/jsoneditor.css" type="text/css">

    <script type="text/javascript" src="http://code.jquery.com/jquery.js"></script>
    <script type="text/javascript" src="/resource/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/resource/jsoneditor/dist/jsoneditor.js"></script>
</head>

<body>
<div class="panel panel-default">
    <div class="panel-body">管理后台</div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="alert alert-info">
                    <strong>功能列表</strong>
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#base-info" class="list-group-item" data-toggle="collapse">基本功能</a>
                        <div id="base-info" class="collapse">
                                <a href="/response/detail?type=magazine" class="list-group-item active">订阅号回复</a>
                                <a href="/response/detail?type=client" class="list-group-item">服务号回复</a>
                                <a href="/redPacketSetting/index" class="list-group-item">微信红包设置</a>
                        </div>

                        <a href="#nav-mall" class="list-group-item" data-toggle="collapse">商品管理</a>
                        <div id="nav-mall" class="collapse">
                            <a href="/headImg/index" class="list-group-item">轮播图添加</a>
                            <a href="/headImg/detail" class="list-group-item">轮播图详情</a>
                            <a href="/goodsManage/info?type=add" class="list-group-item">商品添加</a>
                            <a href="/goodsManage/index" class="list-group-item">商品详情</a>
                        </div>

                        <a href="#nav-vote" class="list-group-item" data-toggle="collapse">投票活动</a>
                        <div id="nav-vote" class="collapse">
                            <a href="/voteManage/index" class="list-group-item">添加用户</a>
                            <a href="/ballotManage/index" class="list-group-item">投票二期</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            {%include file=$tpl%}
        </div>
    </div>
</div>

<script>
    (function () {
        var msg = decodeURIComponent(document.location.hash);
        $("#msg-panel").append(msg);
    })();
</script>

</body>
</html>
