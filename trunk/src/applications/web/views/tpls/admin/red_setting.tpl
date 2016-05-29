<div class="wrapper">
    <form action="/redPacketSetting/switch" method="post">
        <fieldset tabindex="0" class='radioGroup'>
            <legend>红包活动是否开启</legend>
            <input id='red_start' name="red_switch" value="start" type="radio" {%if $switch eq 1%} checked {%/if%}>
            <label for='red_start'>开启</label>
            <input id='red_stop' name="red_switch" value="stop" type="radio" {%if $switch eq 0%} checked {%/if%}>
            <label for='red_stop'>关闭</label>
            <input type="submit" class="button" value="确定">
        </fieldset>
    </form>
</div>