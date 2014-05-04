if(!window.AttachedItemManager){
    window.AttachedItemManager = {
        options : null,
        listCount : function(id){    
            return $("#" + id).length;
        },
        add : function(id, url, dataType){    
            this.options.itemsCount++;
            newId = this.options.itemsCount;
            rowItem = '<tr style="cursor: move;">';
            rowItem += '<td valign="top">';
            rowItem += '<input type="text" value="" id="AttachmentTranslation_title_' + newId + '" name="AttachmentTranslation[' + newId + '][title]" style="width:300px;" maxlength="100">';
            rowItem += '<div dir="ltr" style="overflow: hidden;height:30px;width:300px;">' + url + '</div>';
            rowItem += '</td>';
            rowItem += '<td valign="top">';
            rowItem += '<textarea id="AttachmentTranslation_description' + newId + '" name="AttachmentTranslation[' + newId + '][description]" style="width:200px;height:50px;"></textarea>';            
            rowItem += '</td>';
            rowItem += '<td valign="top">';
            rowItem += '<a href="' + url + '" title="' + url + '" target="_blank"><img border="" src="' + this.options.baseUrl + '/icons/link.png"></a>';
            rowItem += '</td>';
            rowItem += '<td valign="top">';
            rowItem += '<a class="delete-attact-icon" href="javascript:void(0);" class="delete-attact-icon">';
            rowItem += '<img border="0" align="absmiddle" alt="" src="' + this.options.baseUrl + '/icons/remove.png">';
            rowItem += '</a>';
            rowItem += '<input id="AttachmentTranslation_attach_id_' + newId + '" type="hidden" value="" name="AttachmentTranslation[' + newId + '][attach_id]">';
            rowItem += '<input id="AttachmentTranslation_attach_url_' + newId + '" type="hidden" value="' + url + '" name="AttachmentTranslation[' + newId + '][attach_url]">';
            rowItem += '<input id="AttachmentTranslation_content_type_' + newId + '" type="hidden" value="' + dataType + '" name="AttachmentTranslation[' + newId + '][content_type]">';            
            rowItem += '</td>';
            rowItem += '</tr>';
            $("#" + id).append(rowItem);
            $("#" + id).sortable('refresh');
        }
    };    
}

