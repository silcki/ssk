(function($) {
    jQuery.fn.galleries = function(options){
        var options = $.extend({
            time1: 1000,
            time2: 5000,
            numbSelector: '',
            bool: 1
        }, options);

        var gItem = $(this).find('li');
        var gLength = $(gItem).length;
        var numbItem = $(this).parent().find(options.numbSelector + " li");
        var n,m;
        var fIter;

        if(options.bool){
            $.each(gItem, function(i, gtItem){
                $(gItem[i]).css({
                    "zIndex":gLength-i,
                    opacity:0
                });
            });
            playHead(0);
        }

        $(numbItem).live("click",function(event){
            event.preventDefault();
            changePos(this)
        });


        function playHead(activePos) {
            $(gItem).css({
                "opacity":"0"
            });
            $(gItem[activePos]).css({
                "opacity":"1"
            });
            $(numbItem).removeClass("active");
            $(numbItem[activePos]).addClass("active");
            fIter = setTimeout(function(){
                gPlay(activePos);
            }, options.time2);
        }

        function gPlay(n){
            if (n < gLength-1){
                m = n+1;
                gNext(n,m);
                n++;
            } else {
                gNext(gLength-1, 0);
                n=0;
            }

            fIter = setTimeout(function(){
                gPlay(n);
            }, options.time2);
        }

        function gNext(gi,gj){
            $(gItem[gi]).animate({
                opacity:0
            },options.time1).css({
                    "zIndex":0
                });
            $(gItem[gj]).animate({
                opacity:1
            },options.time1).css({
                    "zIndex":40
                });
            $(numbItem[gi]).removeClass("active");
            $(numbItem[gj]).addClass("active");
        }

        function changePos(thisItm){
            clearTimeout(fIter);
            var iNew = $(numbItem).index(thisItm);
            thisActive = $(numbItem).parent().find(".active");
            var iOld = $(numbItem).index(thisActive);
            gNext(iOld,iNew);
            fIter = setTimeout(function(){
                gPlay(iNew);
            }, options.time2);
        }
    };


})(jQuery);