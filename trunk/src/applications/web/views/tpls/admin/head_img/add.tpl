<div class="content-box">
    <div class="content-box-header">
        <h3>轮播图管理</h3>
    </div>

    <form action="/headImg/add" method="POST"  enctype="multipart/form-data">
        <div class="input-group">
            <span class="input-group-addon">标题</span>
            <input id="image_url" type="text" name="image_url" placeholder="请输入图片链接" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">图片链接</span>
            <input id="redirect_url" type="text" name="redirect_url" placeholder="请输入图片链接" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon">请输入图片链接</span>
            <input id="name" type="text" name="name" placeholder="请输入跳转链接" class="form-control">
        </div>

        <div class="input-group">
            <p class="text-danger" id="error_add"></p>
            <div class="input-group-btn">
                <a class="btn btn-danger pull-right js-form-submit">添加</a>
            </div>
        </div>
    </form>
</div>