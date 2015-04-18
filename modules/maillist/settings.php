<?php

return array(
    'tables' => array(
        't1' => array(
            'id' => 1,
            'name' => 'maillist',
        ),
        't2' => array(
            'id' => 2,
            'name' => 'maillist_users',
        ),
        't3' => array(
            'id' => 3,
            'name' => 'maillist_channels',
        ),
        't4' => array(
            'id' => 4,
            'name' => 'maillist_channels_templates',
        ),
        't5' => array(
            'id' => 5,
            'name' => 'maillist_message',
        ),
        't6' => array(
            'id' => 6,
            'name' => 'maillist_messages_setions',
        ),
        't7' => array(
            'id' => 7,
            'name' => 'maillist_channels_subscribe',
        ),
        't8' => array(
            'id' => 8,
            'name' => 'maillist_log',
        ),
        't9' => array(
            'id' => 9,
            'name' => 'maillist_articles_log',
        ),
    ),
    'backend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcMaillistController',
                'channels' => 'AmcMaillistChannelsController',
                'messages' => 'AmcMaillistMessagesController',
            ),
        ),
    ),
    'frontend' => array(
        'structure' => array(
            'controllers' => array(
                'default' => 'AmcMaillistController',
            ),
        ),
    ),
    'options' => array(
        'default' => array(
            'check' => array(
                'enableSubscribe' => true,
                'showChannels' => true,
                'saveAllChannels' => true,
            ),
            'text' => array(
                'subscriptoinRedirectUrl' => '/maillist/default/subscribe',
            ),
            'widgetImage' => '/images/front/maillistImage.png',
        ),
    ),
    'media' => array(
        'paths' => array(
            'html' => array(
                'autoSave' => false,
                'path' => 'multimedia/newsletter/sent',
            ),
            'templates' => array(
                'autoSave' => true,
                'path' => 'multimedia/newsletter/templates',
            ),
        ),
    ),
);
