<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>管理后台</title>

    <link rel="stylesheet" href="/resource/css/admin/style.css" type="text/css">
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
                        {%foreach $menu as $group=>$item%}
                        <a href="#{%$group%}" class="s-list-bk list-group-item" data-toggle="collapse">{%$item["title"]%}</a>
                        <div id="{%$group%}" class="collapse {%if $item.unfold%}in{%/if%}">
                            {%foreach $item["sub_menu"] as $value%}
                            <a href="{%$value.href%}" class="list-group-item {%if $value.active%}active{%/if%}">{%$value.title%}</a>
                            {%/foreach%}
                        </div>
                        {%/foreach%}
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
