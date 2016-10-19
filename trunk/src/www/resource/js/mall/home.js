/**
 * Created by neojos on 2016/10/15.
 */

$("document").ready(function () {
    /**
     *  切换导航条选项
     */
    $('#list-name').find('a').each(function (index, elem) {
        $(this).click(function () {
            //将字体修改成之前的黑色
            $('#list-name').find('a').each(function () {
                $(this).css("color", "#1A1A1A");
            });
            $(this).css("color", "red");

            $('#bottom-color').find('li').each(function (bt_index, elem) {
                if (index == bt_index) {
                    $(this).addClass('list-bottom-color')
                } else {
                    $(this).removeClass('list-bottom-color')
                }

            })
        });
    })

    /**
     * 轮播图显示
     */
    var current_index = 0;      //current btn
    var count_index = 0;
    var lastAnimalIsOver = true;
    var adTimer = null;
    var headImgWidth = $('#head-img').innerWidth();

    $('.head-img-ul').find("a").each(function (index, elem) {       //init position
        if (index != 0) {
            $(this).css('left', headImgWidth + 'px')
        }
    });

    $('#head-img').mouseover(function () {
        clearInterval(adTimer);
    });

    $('#head-img').mouseleave(function () {
        adTimer = setInterval(doInterval, 2000)
    });

    $("#head-img-btn").find("li").each(function (index, elem) {
        $(this).mouseover(function () {                                     //单击事件
            showAd(index);
        });

        $(this).mouseleave(function () {
            $(this).find(".circle").css("background-color", "white");
        });
    });

    //定时器执行方法
    function doInterval() {
        var adLen = $('.head-img-ul').find("a").length;
        count_index++;
        if (count_index >= adLen) {
            count_index = 0;
        }
        showAd(count_index);
    }

    adTimer = setInterval(doInterval, 2000);

    //轮播图切换
    function showAd(index) {

        if (!lastAnimalIsOver) {
            return;
        }
        lastAnimalIsOver = false;

        $('.head-img-ul').find("a").each(function (img_index, elem) {
            if (index == img_index) {
                current_index = index;
                count_index = index;

                var lis = $("#head-img-btn").find("li")[index];
                $(lis).find(".circle").css("background-color", "red");

                $(this).animate({
                    'left': '0px',
                    'z-index': 1,
                    'opacity': 1
                }, 1000, function () {
                    $('.head-img-ul').find("a").each(function (img_index) {
                        if (img_index != current_index) {
                            $(this).css({
                                left: headImgWidth + 'px',
                                zIndex: 2
                            })
                        }
                    });
                    lastAnimalIsOver = true;
                    var lis = $("#head-img-btn").find("li")[current_index];
                    $(lis).find(".circle").css("background-color", "white");
                });
            }
        });
    }
});