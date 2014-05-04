/**
 * @todo add textArea dropdownlist .. etc to switch case 
 */
(function($){
    $.fn.extendableField = function(settings) {        
        var defaults = {                              
            deleteClassName : "delete-extendable-icon",
            addClassName : "add-extendable-icon",
            sortClassName : "sort-extendable-icon",            
            showSort : true
        };        
        var settings = $.extend(defaults, settings);            
        settings.id = $(this).attr("id");
        settings.attachId = settings.dialog + '_attach_list';
        settings.parent = this;        
        $('body').on('click', '#' + settings.id + ' .' + settings.deleteClassName , function() { 
            $(this).parent().remove();
        });
        $('body').on('click', '#' + settings.id + ' .' + settings.addClassName , function() { 
            settings.parent.addRow();
        });
        this.addRow = function(){
            htmlOptions = "";
            filedId = $("#select_attribute_" + settings.attribute).val();
            fieldName = settings.listNames[filedId];
            for(option in settings.htmlOptions){
                htmlOptions += ' ' + option + '="' + settings.htmlOptions[option]+'"';
            }
            //                  alert(setting)
            if(settings.itemsCount[fieldName] != undefined){
                settings.itemsCount[fieldName]++;                    
            }
            else{
                settings.itemsCount[fieldName] = 1;
            }
            newId = settings.itemsCount[fieldName];    
            inputId = settings.className + '_' +fieldName + '_' + newId;                  
            inputName = 'AttributeModel['+settings.className+']['+fieldName+']['+newId+']';                  
            rowItem = '<div class="extra_row">';
            rowItem += '<select name="' + inputName + '[systemAttrbuiteId]" id="' + inputId + '_systemAttrbuiteId' + '" type="text" value="" class="extra_selector">';
            for(selectValue in settings.listData){
                rowItem +='<option value="'+selectValue +'"';
                if(filedId == selectValue){
                    rowItem +=' selected="selected"';
                }
                rowItem +='>';
                rowItem += settings.listData[selectValue];
                rowItem +='</option>';
            }
            rowItem += '</select>';        
            switch(settings.fieldType){
                case 'textField':
                    rowItem += '<input '+ htmlOptions +' name="' + inputName + '[value]" id="' + inputId + '_value' + '" type="text" value="" />';
                    break;
            }
            if(settings.showSort){
                rowItem += '&nbsp;<input maxlength="10" style="width:30px" name="' + inputName + '[sort]" id="' + inputId + '_sort' + '" type="text" value="" />';
                rowItem += '&nbsp;<a class="'+settings.sortClassName+'" href="javascript:void(0);" data-sort="up">';
                rowItem += '<img border="0" align="absmiddle" alt="" src="' + settings.baseUrl + '/icons/up.gif" />';            
                rowItem += '</a>';
                rowItem += '&nbsp;<a class="'+settings.sortClassName+'" href="javascript:void(0);" data-sort="down">';            
                rowItem += '<img border="0" align="absmiddle" alt="" src="' + settings.baseUrl + '/icons/down.gif" />';               
                rowItem += '</a>';
            }
            
            rowItem += '&nbsp;<a class="'+settings.deleteClassName+'" href="javascript:void(0);">';
            rowItem += '<img border="0" align="absmiddle" alt="" src="' + settings.baseUrl + '/icons/remove.png" />';            
            rowItem += '</a>';
            rowItem += '<input name="' + inputName + '[id]" id="' + inputId + '_id' + '" type="hidden" value="new_'+newId+'" />';
            rowItem += '</div>';            
            $("#" + settings.id + "_" + fieldName).append(rowItem);
        }
        
        
    };
    
})( jQuery );

//