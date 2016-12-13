<div class="content-box">
    <div class="content-box-header">
        <ul>
            <li><a href="/voteManage/index">用户列表</a></li>
            <li><a href="/voteManage/detail?type=add">添加新用户</a></li>
        </ul>
    </div>

    <div class="others">
        <div id="wrap_tabs" class="tab-content">
            <div class="tab-pane active" id="tab_list">
                <div class="tab-wrap">
                    {%if $type eq 'add'%}
                    <h4 class="h40">添加新的用户<i class="fa fa-user-plus" aria-hidden="true"></i></h4>
                    {%elseif $type eq 'edit'%}
                    <h4 class="h40">编辑用户信息<i class="fa fa-pencil-square-o" aria-hidden="true"></i></h4>
                    {%/if%}
                </div>

                <form action="/voteManage/modify?type={%$type%}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{%$userInfo.id%}">
                    <div class="input-group">
                        <span class="input-group-addon">用户名</span>
                        <input id="name" type="text" name="name" placeholder="请输入用户名称" value="{%$userInfo.msg.name%}" required
                               class="form-control">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">想要实现的梦想</span>
                        <input id="wish" type="text" name="wish" placeholder="想要实现的梦想" value="{%$userInfo.msg.wish%}" required
                               class="form-control">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">一句话描述</span>
                        <input id="desc" type="text" name="desc" placeholder="请输入一句话描述" value="{%$userInfo.msg.desc%}" required
                               class="form-control">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">海报</span>
                        <input id="poster" type="text" name="poster" placeholder="请输入海报的图片链接" required
                               value="{%$userInfo.msg.poster%}"
                               class="form-control">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">视频Key</span>
                        <input id="video" type="text" name="video" placeholder="请输入视频相关的Key" required
                               value="{%$userInfo.msg.video%}"
                               class="form-control">
                    </div>

                    <div class="input-group">
                        <div class="input-group-btn">
                            {%if $type eq "add"%}
                            <input id="submit" type="submit" class="btn btn-danger pull-right" value='添加'</input>
                            {%elseif $type eq "edit"%}
                            <input id="submit" type="submit" class="btn btn-danger pull-right" value='编辑'</input>
                            {%/if%}
                        </div>
                    </div>
                </form>
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