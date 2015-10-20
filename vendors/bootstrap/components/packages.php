<?php

/**
 * Built-in client script packages.
 *
 * Please see {@link CClientScript::packages} for explanation of the structure
 * of the returned array.
 *
 * @author Ruslan Fadeev <fadeevr@gmail.com>
 * copid from http://yiibooster.clevertech.biz/
 * @var MyBootstrap $this
 */
$cssAppendName = ($this->getOrientation() == "rtl") ? ".rtl" : "";
return array(
    //widgets start
    'datepicker' => array(
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap-datepicker',
        'depends' => array('jquery'),
        'css' => array(YII_DEBUG ? 'css/bootstrap-datepicker.css' : 'css/bootstrap-datepicker.min.css'),
        'js' => array(YII_DEBUG ? 'js/bootstrap-datepicker.js' : 'js/bootstrap-datepicker.min.js')
    ),
    'datetimepicker' => array(
        'depends' => array('jquery'),
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap-datetimepicker', // Not in CDN yet
        'css' => array(YII_DEBUG ? 'css/bootstrap-datetimepicker.css' : 'css/bootstrap-datetimepicker.min.css'),
        'js' => array(YII_DEBUG ? 'js/bootstrap-datetimepicker.js' : 'js/bootstrap-datetimepicker.min.js')
    ),
    'select2' => array(
        'depends' => array('jquery'),
        'baseUrl' => $this->getAssetsUrl() . '/select2', // Not in CDN yet
        'css' => array(
            YII_DEBUG ? 'css/select2.css' : 'css/select2.css',
            YII_DEBUG ? 'css/select2-bootstrap.css' : 'css/select2-bootstrap.css'
        ),
        'js' => array(YII_DEBUG ? 'js/select2.js' : 'js/select2.js')
    ),
    'tinymce4' => array(
        'depends' => array('jquery.js'),
        'baseUrl' => $this->getAssetsUrl() . '/tinymce4',
        'js' => array(
            'tinymce.min.js',
            'jquery.tinymce.min.js'
            ),
        
    ),
    'bootstrap' => array(
        'baseUrl' => $this->getAssetsUrl(),
        'js' => array(YII_DEBUG ? 'js/bootstrap.js' : 'js/bootstrap.min.js'),
        'css' => array(YII_DEBUG ? "css/bootstrap{$cssAppendName}.css" : "css/bootstrap{$cssAppendName}.min.css"),
        'depends' => array('jquery')
    ),
                
    'bootstrap.responsive' => array(
        'baseUrl' => $this->getAssetsUrl(),
        'css' => array(YII_DEBUG ? "css/bootstrap-responsive{$cssAppendName}.css" : "css/bootstrap-responsive{$cssAppendName}.min.css"),
        'depends' => array('boootstrap')
    ),
    'timepicker' => array(
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap-timepicker',
        'js' => array(YII_DEBUG ? 'js/bootstrap-timepicker.js' : 'js/bootstrap-timepicker.min.js'),
        'css' => array(YII_DEBUG ? 'css/bootstrap-timepicker.css' : 'css/bootstrap-timepicker.min.css'),
        'depends' => array('bootstrap')
    ),
    
    'lightbox' => array(
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap-lightbox',
        'js' => array(YII_DEBUG ? 'js/bootstrap-lightbox.js' : 'js/bootstrap-lightbox.min.js'),
        'css' => array(YII_DEBUG ? 'css/bootstrap-lightbox.css' : 'css/bootstrap-lightbox.min.css'),
        'depends' => array('bootstrap.js')
    ),
);
