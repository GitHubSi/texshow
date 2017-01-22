<div class="content-box">
    <div class="content-box-header">
        <ul>
            <li><a href="/ballotManage/index">用户列表</a></li>
            <li><a href="/ballotManage/detail?type=add">添加新用户</a></li>
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

                <form action="/ballotManage/modify?type={%$type%}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{%$userInfo.id%}">

                    <div class="input-group">
                        <span class="input-group-addon">项目类型</span>
                        <select class="input-group-addon" style="width: 150px" name="ballot_type">
                            <option value ="1" {%if $userInfo.type eq 1%}selected{%/if%}>产品</option>
                            <option value ="2" {%if $userInfo.type eq 2%}selected{%/if%}>创新技术</option>
                            <option value="3" {%if $userInfo.type eq 3%}selected{%/if%}>创业公司</option>
                            <option value="4" {%if $userInfo.type eq 4%}selected{%/if%}>实力公司</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">名称</span>
                        <input id="name" type="text" name="name" placeholder="请输入名称" value="{%$userInfo.msg.name%}" required
                               class="form-control">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">图片</span>
                        <input id="poster" type="text" name="img_url" placeholder="请输入图片链接" required
                               value="{%$userInfo.msg.img_url%}" class="form-control">
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon">获奖理由</span>
                        <input id="poster" type="text" name="prize_reason" placeholder="请输入获奖理由" required
                               value="{%$userInfo.msg.prize_reason|default:''%}" class="form-control">
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