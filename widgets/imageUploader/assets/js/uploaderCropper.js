(function(factory) {
    if (typeof define === "function" && define.amd) {
        define(['jquery'], factory);
    }
    else {
        factory(jQuery);
    }
})(function($) {
    var defaults = {'sizes': {}};
    $.fn.uploaderCropper = function(options) {
        options = $.extend(true, {}, defaults, options);
        var $this = this;
        var id = $this.attr('id');
        var imgSrc = '';
        var imageCropper = {
            data: {
                currentCoords: {},
                ratio: 1,
            },
            sizes: options.sizes
        };
        options.cropOptions.onSelect = function(c) {
            imageCropper.data.currentCoords.x = c.x
            imageCropper.data.currentCoords.x2 = c.x2
            imageCropper.data.currentCoords.y = c.y
            imageCropper.data.currentCoords.y2 = c.y2
            imageCropper.data.currentCoords.x *= imageCropper.data.ratio;
            imageCropper.data.currentCoords.x2 *= imageCropper.data.ratio;
            imageCropper.data.currentCoords.y *= imageCropper.data.ratio;
            imageCropper.data.currentCoords.y2 *= imageCropper.data.ratio;
            var cropWidth = imageCropper.data.currentCoords.x2 - imageCropper.data.currentCoords.x;
            var checkSize = function(key, value) {
                if (value.width < cropWidth) {
                    $('#icon_size_' + key).attr('src', options.yesIcon);
                }
                else {
                    $('#icon_size_' + key).attr('src', options.noIcon);
                }
                if (value !== null && typeof value === 'object') {
                    $.each(value, checkSize);
                }
            }            
            $.each(imageCropper.sizes, checkSize);
        }
        $($this).change(function(e) {
            var files = this.files;
            if (this.files[0].type.match(/image/)) {
                (function(file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#container_' + id).html('');
                        var myImg = new Image();
                        var maxImageSize = 800 - 25;
                        myImg.src = imgSrc = e.target.result;
                        imageCropper.data.ratio = 1;
                        if (myImg.width > maxImageSize) {
                            imageCropper.data.ratio = myImg.width / maxImageSize;
                            myImg.width = maxImageSize;
                            myImg.height = myImg.height / imageCropper.data.ratio;
                        }
                        imgWidth = myImg.width;
                        imgHeight = myImg.height;
                        $('#dialog_' + id).dialog('open');
                        $('#container_' + id).append(myImg);
                        $(myImg).Jcrop(options.cropOptions);
                    };
                    reader.readAsDataURL(file);
                })(this.files[0])                
            }
            return false;

        });
        $this.crop = function() {
            
            $("#" + id + "_coords").val(JSON.stringify(imageCropper.data.currentCoords));
            var cropWidth = parseInt(imageCropper.data.currentCoords.x2 - imageCropper.data.currentCoords.x);
            var cropHeight = parseInt(imageCropper.data.currentCoords.y2 - imageCropper.data.currentCoords.y);
            var thumbWidth = imgWidth * imageCropper.data.ratio; // Get original image width
            var thumbHeight = imgHeight * imageCropper.data.ratio; // Get original image height
            var ratio = 1;
            if (cropWidth > options.thumbnailInfo.width) {
                ratio = parseInt(cropWidth / options.thumbnailInfo.width);
                cropWidth = options.thumbnailInfo.width;
                cropHeight = parseInt(cropHeight / ratio);
                thumbWidth = parseInt(thumbWidth / ratio); // Resize original image width
                thumbHeight = parseInt(thumbHeight / ratio); // Resize original image height
            }
            $("#dialog_" + id).dialog("close");
            $("#thumb_prev_" + id).html("");
            $("#thumb_prev_" + id).width(cropWidth);
            $("#thumb_prev_" + id).height(cropHeight);
            $("#thumb_prev_" + id).css({
                "background": "url(" + imgSrc + ") no-repeat",
                "background-size": thumbWidth + "px " + thumbHeight + "px",
                "background-position": "-" + parseInt(imageCropper.data.currentCoords.x / ratio) + "px -" + parseInt(imageCropper.data.currentCoords.y / ratio) + "px"  // Get cropped area coords from original image size and divide by ratio.
            });
        }
        return $this;
    }
});