<style>
    #tab_magazine_wrap table {
        width: auto;
    }

    #tab_magazine_wrap table td {
        padding: 1px;
    }

    #tab_magazine_wrap table tr {
        padding: 1px;
        margin-top: 2px;
        align: left;
    }

</style>
<div class="content-box">
    <div class="content-box-header">
        <h3>微信订阅号自动回复</h3>
    </div>

    <div class="content-box-content">
        <div id="tab_magazine_wrap" style="display: block;">
            <form action="/response/edit" method="post">
                <input type="hidden" class="button" name="type" value="magazine"/>

                <div id="tab_magazine"></div>
                <input id="magazine_response" type="hidden" name="response" value='{%$magazine_response%}'/>
                <input type="submit" class="button" onclick="setResponse()" value="保存"/>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript">
    var magazineContainer, options, json, editor;
    magazineContainer = document.getElementById('tab_magazine');
    options = {
        mode: 'tree',
        modes: ['code', 'form', 'text', 'tree', 'view'], // allowed modes
        indentation: 4
    };

    var jsonMagazineText = document.getElementById('magazine_response').value;
    var jsonMagazine = JSON.parse(jsonMagazineText);
    editorMagazine = new JSONEditor(magazineContainer, options, jsonMagazine);

    function setResponse(obj) {
        var jsonStr = JSON.stringify(editorMagazine.get());
        document.getElementById('magazine_response').value = jsonStr;
    }
</script>
