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

        <h5>商品介绍详情（每张图片都需要有文字介绍,暂时支持最多4张图片）</h5>
        <div id="desc-parent-node">
            <div class="input-group">
                <span class="input-group-addon">图片链接</span>
                <input id="desc" type="text" name="desc_img[]" placeholder="请输入商品图片链接" value="{%$good.desc[0].image%}"
                       class="form-control">
                <span class="input-group-addon">文字介绍</span>
                <input id="desc" type="text" name="desc_title[]" placeholder="请输入商品描述图片" value="{%$good.desc[0].title%}"
                       class="form-control">
            </div>
            <div class="input-group">
                <span class="input-group-addon">图片链接</span>
                <input id="desc" type="text" name="desc_img[]" placeholder="请输入商品图片链接" value="{%$good.desc[1].image%}"
                       class="form-control">
                <span class="input-group-addon">文字介绍</span>
                <input id="desc" type="text" name="desc_title[]" placeholder="请输入商品描述图片" value="{%$good.desc[1].title%}"
                       class="form-control">
            </div>
            <div class="input-group">
                <span class="input-group-addon">图片链接</span>
                <input id="desc" type="text" name="desc_img[]" placeholder="请输入商品图片链接" value="{%$good.desc[2].image%}"
                       class="form-control">
                <span class="input-group-addon">文字介绍</span>
                <input id="desc" type="text" name="desc_title[]" placeholder="请输入商品描述图片" value="{%$good.desc[2].title%}"
                       class="form-control">
            </div>
            <div class="input-group">
                <span class="input-group-addon">图片链接</span>
                <input id="desc" type="text" name="desc_img[]" placeholder="请输入商品图片链接" value="{%$good.desc[3].image%}"
                       class="form-control">
                <span class="input-group-addon">文字介绍</span>
                <input id="desc" type="text" name="desc_title[]" placeholder="请输入商品描述图片" value="{%$good.desc[3].title%}"
                       class="form-control">
            </div>
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