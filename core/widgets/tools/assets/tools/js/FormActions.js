FormActions = {};
FormActions.messages = new Array();
FormActions.manageMany = function(itemId){
    var chkLength = 0;        
    $("input[name='ids[]']:checked").each(function(){
        chkLength ++;
    });
    if(chkLength == 0){
        $("#dialogSelectMany").dialog("open");
    }
    else{
        $("#dialogAreYouSure"+itemId).dialog("open");
    }    
}
FormActions.manageOne = function(route, refId, params){
    var chkLength = 0;        
    var currentId = null;
    $("input[name='ids[]']:checked").each(function(){
        currentId = $(this).val();
        chkLength ++;
    });
    if(chkLength != 1){
        $("#dialogSelectOne").dialog("open");
    }
    else{        
        params[refId] = currentId;
        document.location.href = FormActions.createUrl(route, params);
    }    
}
FormActions.createUrl=function(route, params){    
    url = route;    
    if(route.lastIndexOf('?') == -1){
        url += '?'  ;    
        
    }    
    for(paramKey in params){
        url += "&" +paramKey +"=" + params[paramKey];
    }        
    return url;
}
FormActions.search = function(formId, itemId){
    $('.search-form').toggle();
    $('.search-form form').submit(function(){
        $.fn.yiiGridView.update('data-grid', {
            data: $(this).serialize()
        });
        return false;
    });       
}
FormActions.submitAction = function(formId, route, params){
    for(var paramKey in params){
        $("#"+formId).append('<input type="hidden" name="' + paramKey + '" value="' + params[paramKey] + '" />');    
    }
    
    $("#"+formId).attr('action', route);
    $('#'+formId).submit();
}
FormActions.submit = function(formId, params){    
    if(params != undefined){
        for(var paramKey in params){	
            $("#"+formId).append('<input type="hidden" name="' + paramKey + '" value="' + params[paramKey] + '" />');    
        }
    }
    $('#'+formId).submit();
}
FormActions.translationChange = function(route, parmas){
    parmas.tlang =  $('#tlang').val();
    document.location.href =  FormActions.createUrl(route, parmas);
//alert($route + $('#tlang').val());
}
