<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link href="/resource/jsoneditor/dist/jsoneditor.css" rel="stylesheet" type="text/css">
    <link href="/resource/css/response.css" rel="stylesheet" type="text/css">
    <script src="/resource/jsoneditor/dist/jsoneditor.js"></script>
    <style type="text/css">
        body {
            font: 10.5pt arial;
            color: #4d4d4d;
            line-height: 150%;
            width: 100%;
            padding-left: 40px;
        }

        code {
            background-color: #f5f5f5;
        }

        #tab_client {
            width: 500px;
            height: 500px;
        }
    </style>
</head>
<body>
<div class="notice">
    <div class="intr-wrap">
        <ul id="option_spe" class="con-left-nav">
            <li><a id="magazine" class="current" rel="0" onclick="tabSet('magazine')">微信订阅号自定回复</a></li>
            <li><a id="client" class="" rel="1" onclick="tabSet('client')">微信服务号自动回复</a></li>
        </ul>
    </div>
</div>

<div id="tab_client_wrap" style="display: none;">
    <form action="/response/edit" method="post">
        <input type="hidden" name="type" value="client"/>

        <div id="tab_client"></div>
        <input id="client_response" type="hidden" name="response" value='{%$client_response%}'/>
        <input type="submit" onclick="setResponse()" value="保存"/>
    </form>
</div>

<div id="tab_magazine_wrap" style="display: block;">
    <form action="/response/edit" method="post">
        <input type="hidden" name="type" value="magazine"/>

        <div id="tab_magazine"></div>
        <input id="magazine_response" type="hidden" name="response" value='{%$magazine_response%}'/>
        <input type="submit" onclick="setResponse()" value="保存"/>
    </form>
</div>

<script>
    var clientContainer,magazineContainer, options, json, editor;
    clientContainer = document.getElementById('tab_client');
    magazineContainer = document.getElementById('tab_magazine');
    options = {
        mode: 'tree',
        modes: ['code', 'form', 'text', 'tree', 'view'], // allowed modes
        indentation: 4,
    };

    var jsonClientText = document.getElementById('client_response').value;
    var jsonClient = JSON.parse(jsonClientText);
    editorClient = new JSONEditor(clientContainer, options, jsonClient);

    var jsonMagazineText = document.getElementById('magazine_response').value;
    var jsonMagazine = JSON.parse(jsonMagazineText);
    editorMagazine = new JSONEditor(magazineContainer, options, jsonMagazine);

    function setResponse(obj) {
        if (obj == 'client') {
            var jsonStr = JSON.stringify(editorClient.get());
            document.getElementById('client_response').value = jsonStr;
        } else {
            var jsonStr = JSON.stringify(editorMagazine.get());
            document.getElementById('magazine_response').value = jsonStr;
        }
    }

    function tabSet(id) {
        var tabOppoId, currentTab, oppoId;
        if (id == 'client') {
            tabOppoId = 'tab_magazine_wrap'
            oppoId = 'magazine';
        } else {
            tabOppoId = 'tab_client_wrap';
            oppoId = 'client';
        }
        document.getElementById(id).className = 'current';
        document.getElementById(oppoId).className = '';

        currentTab = 'tab_' + id + '_wrap';
        document.getElementById(tabOppoId).style.display = "none";
        document.getElementById(currentTab).style.display = "block";
    }

</script>
</body>