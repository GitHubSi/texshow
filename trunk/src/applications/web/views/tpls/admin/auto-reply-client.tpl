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

    <div class="content-box-header">
        <h3>服务号自动回复</h3>
    </div>

    <div class="content-box-content">
        <div id="tab_client_wrap">
            <form action="/response/edit" method="post">
                <input type="hidden" name="type" value="client"/>

                <div id="tab_client"></div>
                <input id="client_response" type="hidden" name="response" value='{%$client_response%}'/>
                <input type="submit" class="button" onclick="setResponse('client')" value="保存"/>
            </form>
        </div>
    </div>

</div>

<script type="application/javascript">
    var magazineContainer, options, json, editor;
    clientContainer = document.getElementById('tab_client');
    options = {
        mode: 'tree',
        modes: ['code', 'form', 'text', 'tree', 'view'], // allowed modes
        indentation: 4
    };

    var jsonClientText = document.getElementById('client_response').value;
    var jsonClient = JSON.parse(jsonClientText);
    editorClient = new JSONEditor(clientContainer, options, jsonClient);

    function setResponse(obj) {
        var jsonStr = JSON.stringify(editorClient.get());
        document.getElementById('client_response').value = jsonStr;
    }
</script>
