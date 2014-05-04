(function($){
    $.fn.dropDownSwitcher = function(settings) {
        var defaults = {                    
            'imagePath': null,
            'redirectOnClick': true,
            'imageClass': 'flag',
            'switcherAction': null,
            'selectName': null,
            'dropDownClass' : 'dropdown',
            'switcherValue' : '_switcher_value_'
        };
        
        var settings = $.extend(defaults, settings);            
        settings.ownerId = $(this).attr('id');
        settings.targetId = "target"+ settings.ownerId;
        var dropDownForm = $("div#" + settings.ownerId + " form");
        if(!settings.switcherAction){
            settings.switcherAction = dropDownForm.attr("action");
        }
        dropDownForm.hide();
        this.createDropDown = function(){
            var dropDown = $("#"+ settings.ownerId);
            var selected = dropDown.find("option:selected");
            dropDown.removeAttr("autocomplete");
            var options = $("option", dropDown);                
            imageSelectedTag = (settings.imagePath) ? '<img class="' + settings.imageClass + '" src="' + settings.imagePath + '/' +  selected.val() + '.png" />'  : '' ;
            $("#"+settings.ownerId).append('<dl id="' + settings.targetId + '" class="' + settings.dropDownClass + '"></dl>')
            $("#"+settings.targetId).append('<dt><a href="javascript:;">' + imageSelectedTag + '<em>' + selected.text() + '</em></a></dt>')
            $("#"+settings.targetId).append('<dd><ul></ul></dd>')
            options.each(function(){
                imageTag = (settings.imagePath) ? '<img src="' + settings.imagePath + '/' + $(this).val() + '.png" />'  : '' ;
                $("#" + settings.targetId + " dd ul").append('<li><a href="javascript:;">' + imageTag + '<em data-value="' + $(this).val() + '">' + $(this).text() + '</em></a></li>');
            });                                
        };
        // turn select into dl
        this.turnSelectIntoDl = function(){
            var $dropTrigger = $("#"+ settings.ownerId + " ." + settings.dropDownClass + " dt a");
            // --- language dropdown --- //
            var $languageList = $("#"+ settings.ownerId + " ." + settings.dropDownClass + " dd ul");
            // open and close list when button is clicked
            $dropTrigger.toggle(function() {
                $languageList.slideDown(200);
                $dropTrigger.addClass("active");
            }, function() {
                $languageList.slideUp(200);
                $(this).removeAttr("class");
            });
            // close list when anywhere else on the screen is clicked
            $(document).bind('click', function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass(settings.dropDownClass))
                    $languageList.slideUp(200);
                $dropTrigger.removeAttr("class");
            });
            
            //    // when a language is clicked, make the selection and then hide the list
            $("#"+ settings.ownerId + " ." + settings.dropDownClass + " dd ul li a").click(function() {
                if((settings.imagePath)){
                    var imageSrc = $(this).find("img").attr("src");
                    $("#" + settings.targetId + " dt a img").attr("src", imageSrc);
                }
                
                var clickedTitle = $(this).find("em").html();                
                var clickedValue = $(this).find("em").attr('data-value');
                if(settings.selectName){
                    $("#"+ settings.selectName).val(clickedValue).change();
                }
                var link = settings.switcherAction.replace(settings.switcherValue, clickedValue);
                if(settings.redirectOnClick){
                    document.location.href = link;
                }
                $("#" + settings.targetId + " dt em").html(clickedTitle);
                $languageList.hide();
                $dropTrigger.removeAttr("class");
            });
        }
        this.createDropDown();
        this.turnSelectIntoDl();
    };
    
})( jQuery );

//