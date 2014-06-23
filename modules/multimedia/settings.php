<?php

$videoMaxSize = 20 * 1024 * 1024;
$iniSize = ((int) ini_get('upload_max_filesize') ) * 1024 * 1024;
if ($videoMaxSize > $iniSize) {
    $videoMaxSize = $iniSize;
}
return array(
    'attributes' => array(
//        'useDopeSheet'=>0
    ),
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'galleries',
            'sorting' => array('sortField' => "gallery_id", 'order' => 'asc'),
        ),
        array(
            'id' => 2,
            'name' => 'images',
            'sorting' => array('sortField' => "image_sort", 'order' => 'desc'),
        ),
        array(
            'id' => 3,
            'name' => 'videos',
            'sorting' => array('sortField' => "video_sort", 'order' => 'desc'),
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcGalleriesController',
                'images' => 'AmcImagesController',
                'backgrounds' => 'AmcBackgroundsController',
                'videos' => 'AmcVideosController',
                'videosComments' => 'AmcVideosCommentsController',
                'repliesVideos' => 'AmcRepliesVideosController',
                'imagesComments' => 'AmcImagesCommentsController',
                'backgroundsComments' => 'AmcBackgroundsCommentsController',
                'repliesImages' => 'AmcRepliesImagesController',
                'repliesBackgrounds' => 'AmcRepliesBackgroundsController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcGalleriesController',
                'videos' => 'AmcVideosController',
                'images' => 'AmcImagesController',
                'videoComments' => 'AmcVideoCommentsController',
                'imageComments' => 'AmcImageCommentsController',
                'imageReplies' => 'AmcImageRepliesController',
                'videoReplies' => 'AmcVideoRepliesController',
            ),
        ),
    ),
    'options' => array(
        'attachment' => array(
            'integer' => array(
                'videos' => 1,
                'images' => 1,
                'backgrounds' => 1,
            ),),
        "youtubeApi" => array(
//            'text' => array(
//                'defaultCategory'=>'',
//                'clientId' => '',
//                'developerKey' => '',
//                'sessionID' => '',
//            ),
        ),
        'default' => array(
            'widgetImage' => '/images/front/media_center.jpg',
            'blockImages' => array(
                'videos' => '/images/front/videos.jpg',
                'images' => '/images/front/images.jpg',
                'presentations' => '/images/front/presentations.jpg'
            ),
            'check'=>array(
                'videosGalleryFilterOnStart'=> true,
                'imagesGalleryFilterOnStart'=>true,
                'useGalleriesList'=>true,
                'seoImages' => false,
            ),
            'integer' => array(
                'presentationId' => 0,
                'presentationViewInSite' => false,
                'presentationMsgInSite'=> false,
                'imageWidth' => "600px",
            ),
        ),
    ),
    'media' => array(
        'info' => array(
            'maxImageSize' => 70 * 1024 * 1024,
            'extensions' => 'jpg, gif, png',
            'url' => null,
        ),
        'paths' => array(
            'galleries' => array(
                'path' => 'multimedia/galleries',
            ),
            'videos' => array(
                'path' => 'multimedia/galleries/{gallery_id}/videos',
                'info' => array('size' => $videoMaxSize, 'extensions' => 'flv, wmv',),
                'thumb' => array(
                    'path' => 'multimedia/galleries/{gallery_id}/videos/thumbs',
                    'info' => array('width' => 375, 'height' => 300, 'exact' => false, 'allowedUploadRatio' => 4, 'size' => 1 * 1024 * 1024, 'extensions' => 'jpg, gif, png',),
                )
            ),
            'images' => array(
                'path' => 'multimedia/galleries/{gallery_id}/img',
                'info' => array('width' => 800, 'height' => 600, 'exact' => false, 'allowedUploadRatio' => 8, 'size' => 1 * 1024 * 1024, 'extensions' => 'jpg, gif, png', 'thumbSize' => array('width' => 120, 'height' => 120),),
            ),
            'backgrounds' => array(
                'path' => 'multimedia/galleries/{gallery_id}/bg',
                'info' => array('dimensions' => array(
                        array('width' => 800, 'height' => 600),
                        array('width' => 1024, 'height' => 600),
                        array('width' => 1024, 'height' => 768),
                        array('width' => 1280, 'height' => 720),
                        array('width' => 1280, 'height' => 768),
                        array('width' => 1366, 'height' => 768),
                        array('width' => 1920, 'height' => 1200),
                    ),
                    'exact' => true,
                    'size' => 1 * 1024 * 1024,
                    'allowedUploadRatio' => 1,
                    'extensions' => 'jpg, gif, png',
                    'thumbSize' => array('width' => 120, 'height' => 120),
                ),
            ),
        ),
    ),
);
