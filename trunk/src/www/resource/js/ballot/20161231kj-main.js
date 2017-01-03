var main = {
    init : function(){
        tool.init();
        welcome.init();
    }
};

var welcome = {
    $hasSetNav : false,
    $pad : $('#layout_1'),
    $main : $('#layout_2'),

    init : function(){
        if(welcome.$pad.length>0){
            if(!tool.isPC){

                setTimeout(function(){
                    if($('body').scrollTop()>0){
                        welcome.$pad.addClass('fadeOut');
                        welcome.$main.addClass('fadeIn');
                        return;
                    }
                    welcome.enableSwipe();
                },500);
            }
        }else{
            welcome.$main.removeClass('anim');
        }
    },

    enableSwipe : function(){
        welcome.$pad.enableTouch({
                preventDefault: {
                    drag: true,
                    swipe: true
                }
        });
        welcome.$pad.on('swipeUp', function(e, o) {
            welcome.$pad.addClass('fadeOut');
            welcome.$main.addClass('fadeIn');
        });
    }
};

var tool ={
    btn_onTop : $('#ifeng_backToTop'),
    pageWidth : $(window).width(),
    pageHeight : $(window).height(),

    init : function(){
        tool.isPC = tool.isPCScreenWidth();
    },


    viewport : function() {
        var e = window, a = 'inner';
        if (!('innerWidth' in window )) {
          a = 'client';
          e = document.documentElement || document.body;
        }
        return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
    },


    isPCScreenWidth : function(){
        var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth,
        y = w.innerHeight|| e.clientHeight|| g.clientHeight;
        if(x>=768){
            return true;
        }else{
            return false;
        }
    },


    setTouchScroll : function($isEnable){
        if(!$isEnable){
            $(document).bind("touchmove",tool.preventDefault);
        }else{
            $(document).unbind("touchmove",tool.preventDefault);
        }
    },


    preventDefault : function(e){
        e.preventDefault();
    }

};

main.init();
