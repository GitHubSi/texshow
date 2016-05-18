<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link href="/resource/jsoneditor/dist/jsoneditor.css" rel="stylesheet" type="text/css">
    <script src="/resource/jsoneditor/dist/jsoneditor.js"></script>
    <style type="text/css">
        body {
            font: 10.5pt arial;
            color: #4d4d4d;
            line-height: 150%;
            width: 500px;
            padding-left: 40px;
        }
        code {
            background-color: #f5f5f5;
        }
        #jsoneditor {
            width: 500px;
            height: 500px;
        }
    </style>
</head>
<body>
<p>微信自动回复</p>
<form action="/response/edit" method="post">
    <div id="jsoneditor"></div>
    <input id="auto_response" type="hidden" name="auto_response" value='{%$cur_response%}'/>
    <input type="submit" onclick="setResponse()"  value="保存" />
</form>
<script>
    var container, options, json, editor;
    container = document.getElementById('jsoneditor');
    options = {
        mode: 'tree',
        modes: ['code', 'form', 'text', 'tree', 'view'], // allowed modes
        indentation: 4,
    };
    var jsonText = document.getElementById('auto_response').value;
    var json = JSON.parse(jsonText);
    editor = new JSONEditor(container, options, json);
    function setResponse(){
        var jsonStr = JSON.stringify(editor.get());
        document.getElementById('auto_response').value = jsonStr;
    }
</script>
</body>