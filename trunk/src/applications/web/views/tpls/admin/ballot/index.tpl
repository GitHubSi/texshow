<div class="content-box">
    <div class="content-box-header">
        <ul>
            <li><a href="/ballotManage/index">用户列表</a></li>
            <li><a href="/ballotManage/detail?type=add">添加新用户</a></li>
        </ul>
    </div>

    {%function name=ballot%}
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
            <td>{%$user.name%}</td>
            <td>{%$user.liked%}</td>
            <td>
                <a href="/ballotManage/detail?type=edit&id={%$user.id%}">编辑</a>|
                <a href="#" onclick="show_confirm('/ballotManage/del?id={%$user.id%}')">删除</a>|
                <a href="/ballotManage/addLike?id={%$user.id%}">点赞</a>
            </td>
        </tr>
        {%/foreach%}
        </tbody>
        {%/if%}
    </table>
    {%/function%}

    <div class="tab-content">
        <div class="tab-pane active" id="tab_list">
            <div class="tab-wrap">
                <h4 class="h40">所有用户列表<i class="fa fa-user-o" aria-hidden="true"></i></h4>
            </div>

            <ul class="nav nav-tabs" role="tablist">
                <li data-toggle="production" class="active">
                    <a href="javascript:;">产品</a>
                </li>
                <li data-toggle="technology">
                    <a href="javascript:;">创新技术</a>
                </li>
                <li data-toggle="new_company">
                    <a href="javascript:;">创业公司</a>
                </li>
                <li data-toggle="strong_company">
                    <a href="javascript:;">实力公司</a>
                </li>
            </ul>

            <div id="production">
                {%ballot userList=$userList[1]%}
            </div>
            <div id="technology" style="display: none">

                {%ballot userList=$userList[2]%}
            </div>
            <div id="new_company" style="display: none">
                {%ballot userList=$userList[3]%}
            </div>
            <div id="strong_company" style="display: none">
                {%ballot userList=$userList[4]%}
            </div>
        </div>
    </div>
</div>

<script>
    function show_confirm(url) {
        if (confirm("确认删除吗？删除后不可恢复")) {
            document.location.href = url;
        }
    }

    $(".nav-tabs").find("li").each(function (index, elem) {
        $(this).click(function () {

            $(".nav-tabs").find("li").each(function (index, elem) {
                $(this).removeClass("active");
                var tab = $(this).attr("data-toggle");
                $("#" + tab).hide();
            });

            $(this).addClass("active");
            var tab = $(this).attr("data-toggle");
            $("#" + tab).show();
        });
    });
</script>