<div class="content-box">
    <div>
        <h2>当前购买该商品的排名前40的用户</h2>
        <h4>只显示用户的openid，选定中奖用户之后才会去获取该用户的昵称（PS：之所有不都显示昵称，是因为请求微信接口次数每天有上限）</h4>
    </div>

    <div class="others">
        <div id="wrap_tabs" class="tab-content">
            <div class="tab-pane active" id="tab_list">
                <div class="tab-wrap">
                    <h4 class="h40">{%$good.name%}</h4>
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>用户openid</th>
                        <th>该商品总购买数</th>
                        <th class="w140">操作</th>
                    </tr>
                    </thead>
                    {%if !empty($rankList)%}
                    <tbody>
                    {%foreach $rankList as $openid => $score%}
                    <tr>
                        <td>{%$openid%}</td>
                        <td>{%$score%}</td>
                        <td>
                            <a href="/goodsManage/userdetail?openid={%$openid%}">用户详细信息</a> |
                            <a href="/goodsManage/prize?openid={%$openid%}&id={%$good.id%}">设置为中奖用户</a>
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