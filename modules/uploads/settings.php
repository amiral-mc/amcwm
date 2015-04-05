<?php

return array(
    'attributes' => array(
//        'useDopeSheet'=>0
    ),
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'files',
            'sorting' => array('sortField' => "create_date", 'order' => 'asc'),
        ),
    ),
    
    'options' => array(        
        'attachment' => array(
            'integer' => array(
                'files' => 2,
            ),),
    ),
    'media' => array(
        'paths' => array(
            'files' => array(
                'path' => 'multimedia/upload',
                'info' => array('extensions' => 'wmv, flv, jpg, jpeg, png, gif, pdf, doc, docx, xls, xlsx, rtf, swf', 'maxSize' => 50 * 1024 * 1024,),
            ),
        ),
    ),
);
