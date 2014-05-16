<?php

$videoMaxSize = 20 * 1024 * 1024;
$iniSize = ((int) ini_get('upload_max_filesize') ) * 1024 * 1024;
if ($videoMaxSize > $iniSize) {
    $videoMaxSize = $iniSize;
}
return array(
    'attributes' => array(
        'useDopeSheet'=>0
    ),    
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => null,
                'videos' => null,
                'images' => null,
                'videoComments' => 'AmcVideoCommentsController',
                'imageComments' => 'AmcImageCommentsController',
                'imageReplies' => 'AmcImageRepliesController',
                'videoReplies' => 'AmcVideoRepliesController',
            ),
        ),
    ),
    'options' => array(       
        'default' => array(
            'widgetImage' => '/images/front/media_center.jpg',            
            'integer' => array(
                'presentationId' => 1,
                'presentationViewInSite' => true,
                'presentationMsgInSite'=> false,
                'imageWidth' => "600px",
            ),
        ),
    ),
  
);
