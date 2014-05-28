<?php

return array(
    'backend' => array(
        'messageBase' => "application.modules.backend.modules.sections.messages",
    ),
    'options' => array(
        'default' => array(
            'radio' => array(
                'applyArticlesViewLinks' => false,
                'showSubSections' => false,
                'applySubSectionViewLinks' => false,
                'showMixed' => false,
            ),
            'select' => array(
                'homeStyle' => array(
                    'value' => 1,
                    'list' => array(
                        '1' => 'Style 1',
                        '2' => 'Style 2',
                        '3' => 'Style 3',
                        '4' => 'Style 4',
                    ),
                ),
            ),
        ),
    ),
);
