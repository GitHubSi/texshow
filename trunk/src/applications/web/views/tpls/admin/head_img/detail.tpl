<div class="content-box">
    <div>
        <h4>轮播图查看管理</h4>
    </div>

    <div id="wrap_tabs" class="tab-content">
        <div class="tab-pane active" id="tab_list">
            <div class="tab-wrap">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>标题</th>
                        <th>图片链接</th>
                        <th>跳转链接</th>
                        <th class="w140">操作</th>
                    </tr>
                    </thead>
                    {%if !empty($headImgs)%}
                    <tbody>
                    {%foreach $headImgs as $image%}
                    <tr>
                        <td>{%$image.name%}</td>
                        <td><a href="{%$image.img_url%}">图片链接</a></td>
                        <td><a href="{%$image.redirect_url%}">图片跳转链接</a></td>
                        <td>
                            <a href="#" onclick="show_confirm('/headImg/del?id={%$image.id%}');return false;">删除</a>
                        </td>
                    </tr>
                    {%/foreach%}
                    </tbody>
                    {%/if%}
                </table>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">
    function show_confirm(url) {
        var sure = confirm("确认删除！");
        if (sure) {
            window.location.href = url;
        }
    }
</script>