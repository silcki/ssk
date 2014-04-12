function ajaxLoader (el, options) {
    // Becomes this.options
    var defaults = {
        bgColor 		: '#fff',
        duration		: 800,
        opacity			: 0.7,
        classOveride 	: false
    }
    this.options 	= jQuery.extend(defaults, options);
    this.container 	= $(el);
    this.init = function() {
        var container = this.container;
        // Delete any other loaders
        this.remove();
        // Create the overlay
        var overlay = $('<div></div>').css({
            'background-color': this.options.bgColor,
            'opacity':this.options.opacity,
            'width':container.width(),
            'height':container.height(),
            'position':'absolute',
            'top':'0px',
            'left':'0px',
            'z-index':99999
        }).addClass('ajax_overlay');
        // add an overiding class name to set new loader style
        if (this.options.classOveride) {
            overlay.addClass(this.options.classOveride);
        }
        // insert overlay and loader into DOM
        container.append(
            overlay.append(
                $('<div></div>').addClass('ajax_loader')
                ).fadeIn(this.options.duration)
            );
    };
    this.remove = function(){
        var overlay = this.container.children(".ajax_overlay");
        if (overlay.length) {
            overlay.fadeOut(this.options.classOveride, function() {
                overlay.remove();
            });
        }
    }
    this.init();
}


$(document).ready(function(){

    $("#zakaz").click(function(event){
        event.preventDefault();
    });
    $("#zakaz").click(function(){
        if($('div.okhold').length > 0){
            $('div.okhold').remove();
        }
        $(this).parents('form').submit();
    });

    $("#zakaz-item").click(function(event){
        event.preventDefault();
    });
    $("#zakaz-item").click(function(){
        if($('div.okhold').length > 0){
            $('div.okhold').remove();
        }
        $(this).parents('form').submit();
    });

    $("#callback").fancybox({
        padding: "4",
        //	margin: Array(20,56,3,100),
        fixed : false,
        'wrapCSS' : 'fancybox-custom',
        //    'titlePosition' : 'inside',
        //    'transitionIn' : 'none',
        //    'transitionOut' : 'none',

        ajax            : {
            type    : "GET",
            cache   : false,
            data    : $(this).serializeArray()
        }
    //    'ajax':{'a':1}
    //    helpers:  {
    //    overlay : null
    //    },

    });


    //$(".gallery2").fancybox({
    //	fixed : false,
    //	'wrapCSS' : 'fancybox-custom',
    //	//    'titlePosition' : 'inside',
    //	//    'transitionIn' : 'none',
    //	//    'transitionOut' : 'none',
    //
    //	ajax            : {
    //	    type    : "GET",
    //	    cache   : false,
    //	    data    : $(this).serializeArray()
    //	},
    //	//    'ajax':{'a':1}
    //	//    helpers:  {
    //	//    overlay : null
    //	//    },
    //
    //    });

    $('.fotofancybox').fancybox({
        padding: "9",
        //	margin: 0,
        wrapCSS : 'fancybox-gallery',

        openEffect  : 'none',
        closeEffect : 'none',

        prevEffect : 'fade',
        nextEffect : 'fade',

        //	closeBtn  : false,

        helpers : {
            title : {
                type : 'inside'
            }
        //	    , buttons	: {}
        }

    //	afterLoad : function() {
    //	    this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
    //	}
    }
    );

    $('.maps').fancybox({
        padding: "9",
        wrapCSS : 'fancybox-gallery',
        openEffect  : 'none',
        closeEffect : 'none',
        //	helpers : {
        //	    media : {},
        //	    title: 'inside'
        //	}
        helpers : {
            title : {
                type : 'inside'
            }
        //	    , buttons	: {}
        }
    });

    $('a.complain').fancybox();


//    $(".videogallary").fancybox({
//	'transitionIn'  : 'none',
//	'transitionOut'  : 'none',
//	'titlePosition' : 'inside',
//	'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {
//	    return '<div id="fancybox-title-inside">' + title + '</div>';
//	}
//    });
});