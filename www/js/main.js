function reset_detect_key(the_key)
{
    return the_key;
}
$(document).ready(function(){
    $("#sendvote").click(function(event){
        event.preventDefault();
    });
    $("#sendvote").click(function(){
        $(this).parents('form').submit();
    });

    $("map area").click(function(event){
        event.preventDefault();
    });
    $("map area").click(function(){
        cl = $(this).attr("class");
        $("body").scrollTo( $('#'+cl), 800);
    });
    $(".back").click(function(event){
        event.preventDefault();
    });
    $(".back").click(function(){
        $("body").scrollTo( $('.img img'), 800);
    });
    //if ($.browser.msie){IEpngFix();};
    $(".zakaz").click(function(){
        $("body").scrollTo( $('.zakaz2'), 800);
    });
    var maxWidth = 1000; // max width
    var minWidth = 1000; // min width
    var selector = $(".holder"); // selector

    var windowWidthTemp = 0;
    var windowWidth = 0;

    if($("a[rel=gallery]").length>0){
        $("a[rel=gallery]").fancybox({
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'titlePosition' : 'inside',
            'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
                return '<div id="fancybox-title-inside">' + title + '</div>';
            }
        });
    }
    if($("a.zoom").length>0){
        $("a.zoom").fancybox({
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'titlePosition' : 'inside',
            'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
                return '<div id="fancybox-title-inside">' + title + '</div>';
            }
        });
    }

    if($("a.thickbox").length>0){
        $("a.thickbox").fancybox({
            'transitionIn' : 'none',
            'transitionOut' : 'none',
            'titlePosition' : 'inside',
            'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
                return '<div id="fancybox-title-inside">' + title + '</div>';
            }
        });
    }//
    if($(".photo li").length>3){
        $(".photo").jcarousel();
    }
    if($.browser.msie){
        mmwidth();
        $(window).resize(function(){
            windowWidth = $(window).width();
            if (windowWidth != windowWidthTemp){
                windowWidthTemp = windowWidth;
                mmwidth();
            }
        });
    };
    function mmwidth(){
        $(selector).css({
            width:100+"%"
        });
        bodyWidth = $(window).width();
        if (bodyWidth < minWidth){
            $(selector).width(minWidth);
        }else{
            if (bodyWidth > maxWidth){
                $(selector).width(maxWidth);
            }else{
                $(selector).width(100+"%");
            }
        }
    };

    if ($(".search .text").length > 0) {
        inputValue(".search .text");
    }
    if($(".question .text").length > 0){
        inputValue(".text");
    }
    if($("fieldset .text").length > 0){
        inputValue("fieldset .text");
    }
    function inputValue(input) {
        var asInitVals = new Array;
        $(input).each(function(i) {
            asInitVals[i] = $(this).val();
        });
        $(input).focus(function() {
            if ($(this).val() == asInitVals[$(input).index(this)]) {
                $(this).val("");
                $(this).toggleClass("norm");
            }

        });
        $(input).blur(function(i) {
            if (!(jQuery.trim($(this).val()))) {
                $(this).val(asInitVals[$(input).index(this)]);
                $(this).toggleClass("norm");
            }

        });
    }
    //png fix for ie
    function IEpngFix(){
        if ($.browser.msie){
            var transparentImage = "/i/transparent.gif";

            oImg = $("img[src$=.png]");
            lImg = $("img[src$=.png]").length;

            for (i=0;i<lImg;i++){
                srcImg = $(oImg[i]).attr("src");
                $(oImg[i]).attr({
                    src:transparentImage
                });
                oImg[i].runtimeStyle.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + srcImg + "',sizingMethod='scale')";
                oImg[i].style.display = "inline-block";
            }
        }
    }
    if ($("#gallary").length){
        galleries("#gallary", 1000, 5000, ".gallary_hold .number", 1);
    }
    $(window).load(function(){
        if ($("#brands").length){
            brWidth = $("#brands").width();
            $("#brands").find("table").width(brWidth);

            var tovar = $("#brands").find("table");
            var tovarLength = tovar.length;
            var maxHeightTov = 0;
            for (i=0;i<tovarLength;i++){
                tovHeight = tovar[i];
                tovHeight = $(tovHeight).height();
                if (maxHeightTov<tovHeight){
                    maxHeightTov=tovHeight;
                }
            }
            $("#brands").height(maxHeightTov + 10);

            galleries("#brands", 500, 5000, ".brands_hold .number", 1);
        }
    })
    function galleries(gSelector, time1, time2, numbSelector, bool){
        var gItem = $(gSelector +" li");
        var gLength = $(gItem).length;
        var numbItem = $(numbSelector +" li");
        var n,m;
        var fIter;

        if(bool){
            $.each(gItem, function(i, gtItem){
                $(gItem[i]).css({
                    "zIndex":gLength-i,
                    opacity:0
                });
            });
            playHead(0);
        }
        function playHead(activePos){
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
            }, time2);
        }
        function gPlay(n){
            if (n < gLength-1){
                m = n+1;
                gNext(n,m);
                n++;
            }else {
                gNext(gLength-1, 0);
                n=0;
            }
            fIter = setTimeout(function(){
                gPlay(n);
            }, time2);
        }
        function gNext(gi,gj){
            $(gItem[gi]).animate({
                opacity:0
            },time1).css({
                "zIndex":0
            });
            $(gItem[gj]).animate({
                opacity:1
            },time1).css({
                "zIndex":40
            });
            $(numbItem[gi]).removeClass("active");
            $(numbItem[gj]).addClass("active");
        }
        $(numbItem).live("click",function(event){
            event.preventDefault();
            changePos(this)
        });
        function changePos(thisItm){
            clearTimeout(fIter);
            var iNew = $(numbItem).index(thisItm);
            thisActive = $(numbItem).parent().find(".active");
            var iOld = $(numbItem).index(thisActive);
            gNext(iOld,iNew);
            fIter = setTimeout(function(){
                gPlay(iNew);
            }, time2);
        }
    }

    $("#sendphone").live("click", function(event){
        event.preventDefault();
    });
    $("#sendphone").live("click", function() {
        ajaxurl = $(this).attr("href");
        var phone = $(this).parents("form").serialize();
        thisBTN = $(this);


        $('.callback.phoneback').append('<div class="phoneback_back"><div class="phoneback_back_loader">&nbsp;</div></div>');

        $.post(ajaxurl, phone, function(data) {
            if (data==1) {
                $('.callback.phoneback').html(callback_mess);
                setTimeout('$("#fancybox-close").trigger("click")', 7000);
            }
            else {
                $(thisBTN).parents("form").find(".errhold").html(data);
            }
            $('.phoneback_back').remove();
            $('.phoneback_back_loader').remove();
        })
    });


    $("#sendcomplain").live("click", function(event){
        event.preventDefault();
    });
    $("#sendcomplain").live("click", function() {
        ajaxurl = $(this).attr("href");
        var phone = $(this).parents("form").serialize();
        thisBTN = $(this);


        $('.callback.phoneback.complain').append('<div class="phoneback_back"><div class="phoneback_back_loader">&nbsp;</div></div>');

        $.post(ajaxurl, phone, function(data) {
            if (data==1) {
                $('.callback.phoneback.complain').html(complain_mess);
                setTimeout('$("#fancybox-close").trigger("click")', 7000);
            }
            else {
                $(thisBTN).parents("form").find(".errhold").html(data);
            }
            $('.phoneback_back').remove();
            $('.phoneback_back_loader').remove();
        })
    });

    function popupchickClose() {
        $(".popupchik").remove();
    }

    function popup(open_btn) {
        var winWidth = $("body").width();

        $(".close, .close-popup").live("click", function(event) {
            event.preventDefault();
        });
        $(".close, .close-popup").live("click", function() {
            $(".popupchik").remove();
        });
        $(open_btn).click(function(event) {
            event.preventDefault();
        });
        $(open_btn).click(function(e) {
            $(".popupchik").remove();
            $("*:not(.popupchik)").click(function(e) {
                kids = e.target;
                var _a = kids;
                while (true) {
                    _atag = _a.tagName;
                    if (_atag && ($(_a).hasClass('popupchik'))) {
                        break;
                    }
                    else {
                        if (!_atag) {
                            $(".popupchik").remove();
                            break;
                        }
                        _a = _a.parentNode;
                    }
                }
            });
            //selector = $(this).parent();

            ajaxlink = $(this).attr("href");
            $.get(ajaxlink, function(data) {
                $("body").append('<div class="popupchik"></div>');
                $(".popupchik").css({
                    right: winWidth - e.pageX,
                    top: e.pageY
                });
                selector = $(".popupchik");
                $(selector).prepend(data);
                inputValue("input.text");
            });

        });
    }
    $(window).load(function(){
        if($(".rubr").length>0){
            tovarHeightFix(".rubr");
        }
        if($(".catalog li").length>0){
            tovarHeightFix(".catalog li");
        }
        if($(".gallery li a").length>0){
            tovarHeightFix(".gallery li a");
        }
        if($(".gallery2 li").length>0){
            tovarHeightFix(".gallery2 li a");
        }
        if($(".clients li").length>0){
            tovarHeightFix(".clients li");
        }
        if($(".stelazhmore li").length>0){
            tovarHeightFix(".stelazhmore li");
        }
    });
    function tovarHeightFix(selector){
        var tovar = $(selector);
        var tovarLength = tovar.length;
        var maxHeightTov = 0;
        for (i=0;i<tovarLength;i++){
            tovHeight = tovar[i];
            tovHeight = $(tovHeight).height();
            if (maxHeightTov<tovHeight){
                maxHeightTov=tovHeight;
            }
        }
        $(selector).height(maxHeightTov);
    };

    //  menu_slider();
    function menu_slider() {
        /*    $(".menucat ul").hide();
    $(".menucat #current").addClass("active").parents("ul").show();
    $(".menucat #current").parents("li").addClass("active");
    */

        $(".menucat li a").click(function(event) {
            activ = $(this).parent().hasClass("active")
            nextTag = $(this).next().attr("tagName");
            if ((!activ) && (nextTag == "UL")) {
                event.preventDefault();
                $(this).next().slideDown(500);
                $(this).parent().addClass("active")
            }
            else {
                if (nextTag == "UL") {
                    event.preventDefault()
                    thisParent = $(this).parent();
                    thisNext = $(this).next();
                    $(thisNext).slideUp(500);
                    $(thisNext).queue(function() {
                        $(thisParent).removeClass("active");
                        $(this).dequeue();
                    });
                }
                else {
                    return true
                };
            }
        });
    }
    $(".faqs .ask a").click(function(event){
        event.preventDefault();
    });
    $(".faqs .text").hide();
    faq = $(".faq");
    if (faq.length){
        $(".faqs .ask").toggle(
            function(){
                $(this).parents(".faq").find(".text").slideDown();
                $(this).toggleClass("open");
            },
            function(){
                $(this).parents(".faq").find(".text").slideUp(function(){
                    $(this).parents(".faq").find(".ask").toggleClass("open")
                });
            });
    };
    if(($(".callback").length>0)&&($(".fdbck").length>0)){
        $(".callback .fdbck").hide();
        $(".callback .heading a").click(function(event){
            event.preventDefault();
        })
        $(".callback .heading").toggle(
            function(){
                $(this).parents(".callback").find(".fdbck").slideDown();
                $(this).toggleClass("open");
            },
            function(){
                $(this).parents(".callback").find(".fdbck").slideUp(function(){
                    $(this).parents(".callback").find(".heading").toggleClass("open");
                });
            });
    }
    if($(".more_parent .more_link").length>0){
        $(".more_parent .more_link").toggle(
            function(){
                var parent = $(this).parents(".more_parent");
                $(parent).toggleClass("open");
                $(parent).find(".more_block").slideDown(500);
            },
            function(){
                var parent = $(this).parents(".more_parent");
                $(parent).find(".more_block").slideUp(500, function(){
                    $(parent).toggleClass("open");
                })
            }
            )
    }
    $("a[vlink]").click(function(event){event.preventDefault();});
    $("a[vlink]").click(function(){
        var link = $(this).attr('vlink');
        window.location.href = link;
    });

    $('form input, form select, form textarea').live('focus', function(){
        var fnsting = document.onkeydown.toString();
        var name = fnsting.match( /function\s*(.*?)\s*\(/ )[1];
        if (name == 'detect_key') {
            document.onkeydown = reset_detect_key;
        }
    });

    $('form input, form select, form textarea').live('blur', function(){
        document.onkeydown = detect_key;
    });
});
