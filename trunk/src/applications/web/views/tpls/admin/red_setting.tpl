<div class="content-box">
    <div class="content-box-header">
        <h3>微信红包设置</h3>
    </div>

    <div class="content-box-content">
        <div>
            <form action="/redPacketSetting/switch" method="post">
                <fieldset tabindex="0">
                    <legend>红包活动是否开启</legend>
                    <input id='red_start' name="red_switch" value="start"
                           type="radio" {%if $switch eq 1%} checked {%/if%}><span class="space-right-10">开启</span>
                    <input id='red_stop' name="red_switch" value="stop"
                           type="radio" {%if $switch eq 0%} checked {%/if%}><span class="space-right-10">关闭</span>
                    <input type="submit" class="button" value="确定">
                </fieldset>
            </form>
        </div>
    </div>

    <div class="content-box-content">
        <div class="wrapper">
            <form action="/redPacketSetting/percent" method="post">
                <fieldset tabindex="0" class='radioGroup'>
                    <legend>设置红包中奖概率</legend>
                    <span>请输入1、2、3、4...N这样的整数，中奖概率的计算规则为：1/N × 100%</span>
                    <input name="percent" type="text" placeholder="当前红包中奖概率为：{%$percentage%}"/>
                    <input type="submit" class="button" value="确定">
                </fieldset>
            </form>
        </div>
    </div>

</div>
