<div class="content-box">
    <div>
        <h4>轮播图管理</h4>
    </div>

    <form action="/headImg/add" method="POST"  enctype="multipart/form-data">
        <div class="input-group">
            <span class="input-group-addon">标题:</span>
            <input id="name" type="text" name="name" placeholder="请输入图片链接" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">图片链接:</span>
            <input id="img_url" type="text" name="img_url" placeholder="请输入图片链接" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">请输入跳转链接:</span>
            <input id="redirect_url" type="text" name="redirect_url" placeholder="请输入跳转链接" class="form-control"
                   value="http://act.wetolink.com/mall/detail?item=">
        </div>

        <div class="input-group">
            <div class="input-group-btn">
                <input id="submit" type="submit" class="btn btn-danger pull-right js-form-submit">添加</input>
            </div>
        </div>
    </form>
</div>

<script type="application/javascript" >
    $("#submit").click(function () {
        if ($("#name").val().length == 0  || $("#img_url").val().length == 0 || $("#redirect_url").val().length == 0){
            alert("请将信息填写完整");
            return false;
        }
    });
</script>