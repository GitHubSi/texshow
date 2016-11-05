<div class="content-box">
    <div>
        <h4>商品列表</h4>
    </div>

    <div class="others">
        <div id="wrap_tabs" class="tab-content">
            <div class="tab-pane active" id="tab_list">
                <div class="tab-wrap">
                    <h4 class="h40">线上商品列表</h4>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>当前购买数</th>
                        <th>需要购买数</th>
                        <th class="w140">操作</th>
                    </tr>
                    </thead>
                    {%if !empty($goods["online"])%}
                    <tbody>
                    {%foreach $goods["online"] as $good%}
                    <tr>
                        <td>{%$good.name%}</td>
                        <td>{%$good.current_score%}</td>
                        <td>{%$good.total_score%}</td>
                        <td>
                            <a href="#"
                               onclick="show_confirm('/goodsManage/offline?id={%$good.id%}');return false;">下线</a> |
                            <a href="/goodsManage/info?type=detail&id={%$good.id%}">详情</a>|
                            <a href="/goodsManage/info?type=edit&id={%$good.id%}">编辑</a>|
                            <a href="/goodsManage/recordHistory?id={%$good.id%}">查看购买情况</a>
                        </td>
                    </tr>
                    {%/foreach%}
                    </tbody>
                    {%/if%}
                </table>
            </div>
        </div>

        <div id="wrap_tabs" class="tab-content">
            <div class="tab-pane active" id="tab_add">
                <div class="tab-wrap">
                    <h4 class="h40">线下商品列表</h4>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>名称</th>
                        <th>当前购买数</th>
                        <th>需要购买数</th>
                        <th class="w140">操作</th>
                    </tr>
                    </thead>
                    {%if !empty($goods["offline"])%}
                    <tbody>
                    {%foreach $goods["offline"] as $good%}
                    <tr>
                        <td>{%$good.name%}</td>
                        <td>{%$good.current_score%}</td>
                        <td>{%$good.total_score%}</td>
                    </tr>
                    {%/foreach%}
                    </tbody>
                    {%/if%}
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function show_confirm(url) {
        if (confirm("确认下线吗？下线之后商品即会下线，并且展示在开奖列表中")) {
            document.location.href = url;
        }
    }
</script>