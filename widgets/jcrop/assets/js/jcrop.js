/**
 * Javascript for Jcrop extension.
 * @author Abdallah El-Gammal
 * 
 */

function jcrop_getCoords(coords, id) {
	$('#'+ id +'_x').val(coords.x);
	$('#'+ id +'_y').val(coords.y);
	$('#'+ id +'_x2').val(coords.x2);
	$('#'+ id +'_y2').val(coords.y2);
	$('#'+ id +'_w').val(coords.w);
	$('#'+ id +'_h').val(coords.h);
}

function jcrop_showThumb(id, content_id) {

    var file_selector = document.getElementById(id);
    
    file_selector.addEventListener("change", function(e) {
        var files_array = id.files;
        if (files_array[0].type.match(/image/)) { // it's an image file
            jcrop_read_image_file(files_array[0], content_id);
            // file_selector.disabled = true; // disable the file selector now 
        }
    }
)}
//	var rx = 100 / coords.w;
//	var ry = 100 / coords.h;
//	
//	var height = $('#'+id).prop('height');
//	var width = $('#'+id).prop('width');
//	
//	$('#thumbEvent_'+id).css({ 
//		width: Math.round(rx * width) + 'px',
//		height: Math.round(ry * height) + 'px',
//		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
//		marginTop: '-' + Math.round(ry * coords.y) + 'px'
//	});
//	if ($('#mirror_'+id).css('display') == 'none') {
//		$('#mirror_'+id).css('display', '');
//		$('#thumb_'+id).css('display', 'none');
//	}



function jcrop_read_image_file(file, content_id) {
    var reader = new FileReader();
    reader.onload = function(e){
        var image_contents = e.target.result;
        var img = document.createElement("img");
        img.id = 'cropZoom2';
        img.src = image_contents;
        content = document.getElementById(content_id);
        //document.body.appendChild(img);
        content.appendChild(img);
        $(img).Jcrop({
            onSelect: showCoords,
            bgColor: 'black',
            bgOpacity: .4,
            setSelect: [ 100, 100, 50, 50 ],
            aspectRatio: 16 / 9
        });

    //var crop = document.getElementById("k").getElementsByTagName('image')[0].href.baseVal;
    //document.getElementById("k").getElementsByTagName('image')[0].href.baseVal = image_contents;
    //document.getElementById("k").getElementsByTagName('image')[0].href.animVal = image_contents;
    //console.log(crop);
    //crop = image_contents;
    };
    reader.readAsDataURL(file);

}









	
function jcrop_reinitThumb(id) {
	$('#mirror_' + id).hide();
	$('#thumb_' + id).show();
}
	
function jcrop_cancelCrop(jcrop) {
	var buttons = jcrop.ui.holder.next(".jcrop-buttons");
	jcrop.disable();
	buttons.find(".jcrop-start").show();
	buttons.find(".jcrop-crop, .jcrop-cancel").hide();
	jcrop_reinitThumb(jcrop.ui.holder.prev("img").attr("id"));
}

function jcrop_initWithButtons(id, options, urlid) {
	var jcrop = {};
	
	function ajaxRequest(id) {
		// ajax request to send
		var ajaxData = {};
		ajaxData[id+'_x'] = $('#'+ urlid +'_x').val();
		ajaxData[id+'_x2'] = $('#'+ urlid +'_x2').val();
		ajaxData[id+'_y'] = $('#'+ urlid +'_y').val();
		ajaxData[id+'_y2'] = $('#'+ urlid +'_y2').val();
		ajaxData[id+'_h'] = $('#'+ urlid +'_h').val();
		ajaxData[id+'_w'] = $('#'+ urlid +'_w').val();
		for (var v in options.ajaxParams) {
			ajaxData[v] = options.ajaxParams[v];
		}
                console.log(ajaxData);
		$.ajax({
			type: "post",
			url: options.ajaxUrl,
			data: ajaxData,
			success: function(msg) {
				if (msg != 'error') {
					// change the image source
					$('#thumb_' + id + '> img').attr('src', msg);
					jcrop_reinitThumb(id);
				}
			}
		});
	}

	$('body').delegate('#start_'+id,'click', function(e){
		$('#crop_'+id+', #cancel_'+id).show();
		$('#start_'+id).hide();
		if (!jcrop.id){
			//jcrop.id = $.Jcrop('#'+id, options);
                        jcrop.id = $.Jcrop('#' + urlid, options)
		}
		jcrop.id.enable();
		var dim = jcrop.id.getBounds();
		jcrop.id.animateTo([dim[0]/4, dim[1]/4,dim[0]/2,dim[1]/2]);
	});
			
	$('body').delegate('#crop_'+id,'click', function(e){
		$('#start_'+id).show();
		$('#crop_'+id+', #cancel_'+id).hide();
		ajaxRequest(urlid);
		jcrop.id.release();
		jcrop.id.disable();
	});
	
	$('body').delegate('#cancel_'+id,'click', function(e){
                $('#crop_'+id+', #cancel_'+id).hide();
		$('#start_'+id).show();
                jcrop.id.release();
	});
}
