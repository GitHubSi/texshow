<div class="content-box">
    <div>
        <h4>用户微信相关信息</h4>
    </div>

    <div class="others">
        <div id="wrap_tabs" class="tab-content">
            <div class="tab-pane active" id="tab_list">
                <div class="tab-wrap">
                    <h4 class="h40">用户微信相关信息</h4>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>昵称</th>
                        <th>头像</th>
                    </tr>
                    </thead>
                    {%if !empty($userInfo)%}
                    <tbody>
                    <tr>
                        <td>{%$userInfo.nickname%}</td>
                        <td><a href="{%$userInfo.headimgurl%}" title="头像">头像</a></td>
                    </tr>
                    </tbody>
                    {%/if%}
                </table>
            </div>
        </div>
    </div>
</div>