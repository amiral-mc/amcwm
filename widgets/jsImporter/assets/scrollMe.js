jQuery(function() {
    jQuery('#toTop').bind('click',function(event){
        var $anchor = jQuery(this);
        jQuery('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1000);
        event.preventDefault();
    });

    jQuery('#topMenu ul a').bind('click',function(event){
        var $anchor = jQuery(this);
        jQuery('html, body').stop().animate({
            scrollTop: jQuery($anchor.attr('href')).offset().top
        }, 1000);
        event.preventDefault();
    });
});
