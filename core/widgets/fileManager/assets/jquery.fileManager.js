(function($){
    $.fn.fileManager = function(settings) {        
        var defaults = {                    
            'openerType': null,
            'messages': {
                'deleteMsg':'Are you sure?'
            },
            'baseUrl': "",
            'fileClassName': "file-box",
            'resultClassName': "file-result",
            'selectCheckBox': "file_select",
            'deleteClassName': "file-delete",
            'backClassName': "file-back",
            'uploadClassName': "file-upload",
            'previewClassName': "file-preview",
            'attachmentActions':null,
            'defaultComponent':null,
            'componentChanger':'componentChanger',
            'dialog' : null,
            'page':''
        };        
        var settings = $.extend(defaults, settings);            
        settings.id = $(this).attr("id");
        settings.attachId = settings.dialog + '_attach_list';
        this.component = settings.defaultComponent;
        settings.parent = this; 
        
        /**
         * @todo send the delete values to attachment action
         */
        $('body').on("click", '.' + settings.deleteClassName, function(){
            var deletedValues = "";
            conf = confirm(settings.messages.deleteMsg);
            if(conf){
                deletedValues = settings.selectCheckBox+ "[]=" + $(this).attr("data-id") +"&";
                doAjax('component='+settings.parent.component+"&"+deletedValues+"action=delete");
            }
        });
        $('body').on("click", '.' + settings.uploadClassName, function(){
            doAjax('component='+settings.parent.component+"&"+"action=upload");           
        });
        $('body').on("click", '.' + settings.backClassName, function(){
            doAjax('component='+settings.parent.component+"&"+"action=list");           
        });
        $('body').on("click", '.' + settings.fileClassName, function(){
            var url = $(this).attr("data-url");
            $('.' + settings.resultClassName).html(url);
            settings.parent.preview($(this).attr("data-url"), $(this).attr("data-type"));
        });
        this.preview = function(url, mediaType){
            html = '<a href="' + url + '" target="_blank"><img src="'+ settings.baseUrl +'/images/link.png" border="0" /></a>';            
            switch (mediaType){
                case settings.mediaTypes.image:
                    html = '<img src="' + url + '" />';                    
                    break;                
            }
            $("." + settings.previewClassName + " div").attr("data-url", url);                        
            $("." + settings.previewClassName + " div").attr("data-type", mediaType);                        
            $("." + settings.previewClassName + " div").html(html);            
        }
        $('#' + settings.id + "_insert").click(function(){
            url = $("." + settings.previewClassName + " div").attr("data-url");
            dataType = $("." + settings.previewClassName + " div").attr("data-type");
            if(typeof url != "undefined"){
                switch(settings.openerType){
                    case "attach":
                        window.parent.AttachmentManager.add(settings.attachId, url, dataType);
                        
                        $('.' + settings.resultClassName).hide("drop", {
                            direction: "down"
                        }, 500, 
                        function(){
                            $(this).show();
                            var chtml = $(this).html();
                            $(this).html('<img src="'+ settings.baseUrl +'/images/yes.png" border="0" />' + chtml);
                        }
                        );
                        //window.parent.$('#' + settings.dialog).dialog('close');   
                        break;
                    case "rte":
                        var win = tinyMCEPopup.getWindowArg("window");
                        win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = url;
                        if (win.ImageDialog != undefined){
                            if (win.ImageDialog.getImageData)
                                win.ImageDialog.getImageData();
                            if (win.ImageDialog.showPreviewImage)
                                win.ImageDialog.showPreviewImage(url);
                        }
                        tinyMCEPopup.close();       
                        break;
                }
            }
        });
        $('#' + settings.id + "_close").click(function(){
            switch(settings.openerType){
                case "attach":
                    window.parent.$('#' + settings.dialog).dialog('close');   
                    break;
                case "rte":
                    tinyMCEPopup.close();
                    break;
            }
        });
        $('#' + settings.componentChanger).change(function(){
            setManageArea($(this).attr('value'));
        });
        doAjax = function(data){
            if(typeof settings.attachmentActions[settings.parent.component] != "undefined"){
                data += "&dialog=" + settings.dialog + "&page=" + settings.page +"&op="+ settings.openerType;
                jQuery.ajax({
                    'url':settings.attachmentActions[settings.parent.component],
                    'type':'get',
                    'data':data,
                    'error':function(jqXHR, textStatus, errorThrown){
                        alert(errorThrown)
                    },
                    'success':function(data){
                        // data will contain the xml data passed by the controller
                        if (data){
                            $("#"+settings.id + "_area").html(data);                    
                        } 
                    } ,
                    'cache':false
                });                       
            }
        },
        setManageArea = function(component){
            $("." + settings.previewClassName + " div").html(''); 
            settings.parent.component = component;                                    
            doAjax('component='+component);
        }
        
        setManageArea(settings.defaultComponent);
    };
    
})( jQuery );

//