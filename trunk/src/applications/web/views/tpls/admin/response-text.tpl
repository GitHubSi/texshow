<style>
    #tab_client_wrap table {
        width: auto;
    }

    #tab_client_wrap table td {
        padding: 1px;
    }

    #tab_client_wrap table tr {
        padding: 1px;
        margin-top: 2px;
        align: left;
    }
</style>

<div class="content-box">

    <ul class="nav nav-tabs">
        <li class="active"><a href="#maga" data-toggle="tab">订阅号自动回复</a></li>
        <li><a href="#client" data-toggle="tab">服务号自动回复</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="maga">
            <form action="/response/edit?type=magazine" method="post">
                <div id="wx-rep-maga"></div>
                <input id="maga_response" type="hidden" name="response" value='{%$magazine_response%}'/>
                <input type="submit" class="btn btn-primary pull-right" onclick="setMagaResponse()" value="保存"/>
            </form>
        </div>

        <div class="tab-pane" id="client">
            <form action="/response/edit?type=client" method="post">
                <div id="wx-rep-client"></div>
                <input id="client_response" type="hidden" name="response" value='{%$client_response%}'/>
                <input type="submit" class="btn btn-primary pull-right" onclick="setClientResponse()" value="保存"/>
            </form>
        </div>
    </div>

</div>

<script type="application/javascript">
    var magaContainer, clientContainer, options, json, editor;
    clientContainer = document.getElementById('wx-rep-client');
    magaContainer = document.getElementById('wx-rep-maga');
    options = {
        mode: 'tree',
        modes: ['code', 'form', 'text', 'tree', 'view'], // allowed modes
        indentation: 4
    };

    var jsonClientText = document.getElementById('client_response').value;
    var jsonClient = JSON.parse(jsonClientText);
    editorClient = new JSONEditor(clientContainer, options, jsonClient);

    var jsonMagaText = document.getElementById('maga_response').value;
    var jsonMaga = JSON.parse(jsonMagaText);
    editorMaga = new JSONEditor(magaContainer, options, jsonMaga);

    function setClientResponse() {
        var jsonStr = JSON.stringify(editorClient.get());
        document.getElementById("client_response").value = jsonStr;
    }

    function setMagaResponse() {
        var jsonStr = JSON.stringify(editorMaga.get());
        document.getElementById("maga_response").value = jsonStr;
    }

</script>
