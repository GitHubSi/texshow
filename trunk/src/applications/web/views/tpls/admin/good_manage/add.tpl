<div class="content-box">
    <div>
        <h4>添加商品</h4>
    </div>

    <form action="/goodsManage/modify?type={%$type%}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="{%$id%}">
        <div class="input-group">
            <span class="input-group-addon">商品名称:</span>
            <input id="name" type="text" name="name" placeholder="请输入商品名称" value="{%$good.name%}" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">商品价格</span>
            <input id="price" type="text" name="price" placeholder="请输入商品价格" value="{%$good.total_score%}"
                   class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">商品图片链接</span>
            <input id="image" type="text" name="image" placeholder="请输入跳转链接" value="{%$good.image%}"
                   class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">商品描述</span>
            <input id="desc" type="text" name="desc" placeholder="请输入商品描述图片" value="{%$good.desc%}"
                   class="form-control">
        </div>

        <div class="input-group">
            <div class="input-group-btn">
                {%if $type eq "add"%}
                <input id="submit" type="submit" class="btn btn-danger pull-right js-form-submit" value='添加'</input>
                {%elseif $type eq "edit"%}
                <input id="submit" type="submit" class="btn btn-danger pull-right js-form-submit" value='编辑'</input>
                {%/if%}
            </div>
        </div>
    </form>
</div>

<script type="application/javascript">
    $("#submit").click(function () {
        if ($("#name").val().length == 0 || $("#price").val().length == 0 || $("#image").val().length == 0) {
            alert("请将信息填写完整");
            return false;
        }
    });
</script>