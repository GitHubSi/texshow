<div class="content-box">
    <div class="content-box-header">
        <ul>
            <li><a href="/voteManage/index">用户列表</a></li>
            <li><a href="/voteManage/detail?type=add">添加新用户</a></li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_list">
            <div class="tab-wrap">
                <h4 class="h40">所有用户列表<i class="fa fa-user-o" aria-hidden="true"></i></h4>
            </div>

            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>用户名称</th>
                    <th>点赞数</th>
                    <th class="w140">操作</th>
                </tr>
                </thead>
                {%if !empty($userList)%}
                <tbody>
                {%foreach $userList as $user%}
                <tr>
                    <td>{%$user.id%}</td>
                    <td>{%$user.nick_name%}</td>
                    <td>{%$user.liked%}</td>
                    <td>
                        <a href="/voteManage/detail?type=edit&id={%$user.id%}">编辑</a>|
                        <a href="#" onclick="show_confirm('/voteManage/del?id={%$user.id%}')">删除</a>|
                        <a href="/voteManage/addLike?id={%$user.id%}">点赞</a>
                    </td>
                </tr>
                {%/foreach%}
                </tbody>
                {%/if%}
            </table>

            {%if $nextPage%}
            <div>
                <a href="/voteManage/index?last_id={%$user.id%}" target="_blank">下一页</a>
            </div>
            {%/if%}
        </div>
    </div>
</div>

<script>
    function show_confirm(url) {
        if (confirm("确认删除吗？删除后不可恢复")) {
            document.location.href = url;
        }
    }
</script>