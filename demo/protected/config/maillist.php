<?php

return array(
    'tables' => array(
        array(
            'id' => 1,
            'name' => 'maillist',
        ),
        array(
            'id' => 2,
            'name' => 'maillist_users',
        ),
        array(
            'id' => 3,
            'name' => 'maillist_channels',
        ),
        array(
            'id' => 4,
            'name' => 'maillist_channels_templates',
        ),
        array(
            'id' => 5,
            'name' => 'maillist_message',
        ),
        array(
            'id' => 6,
            'name' => 'maillist_messages_setions',
        ),
        array(
            'id' => 7,
            'name' => 'maillist_channels_subscribe',
        ),
        array(
            'id' => 8,
            'name' => 'maillist_log',
        ),
        array(
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
            'widgetImage' => null,
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
