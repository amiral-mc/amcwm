<?php
$user = Yii::app()->user->getInfo();
$currentAppLang = Yii::app()->getLanguage();
$baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
?>
<!DOCTYPE html>
<html dir="<?php echo Yii::app()->getLocale()->getOrientation() ?>" lang="<?php echo Yii::app()->getLanguage() ?>">
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="<?php echo Yii::app()->getLanguage() ?>" />      	
        <link rel="stylesheet" type="text/css" href="<?php echo $baseScript ?>/css/<?php echo $currentAppLang ?>/style.css" />        
        <title><?php echo $this->pageTitle ?></title>
    </head>
    <body>
        <div id="container">
            <div id="head">
                <div id="logo_user_details">
                    <h1 id="logo"><a href="#"></a></h1>
                    <div id="user_details">
                        <ul id="user_details_menu">
                            <li><b><?php echo $user['username'] ?></b></li>
                            <li>
                                <ul id="user_access">
                                    <li class="first"><?php echo CHtml::link(AmcWm::t("amcBack", "profile"), array("/backend/users/default/profile")) ?></li>
                                    <li><?php echo CHtml::link(AmcWm::t("amcBack", "logout"), array("/backend/default/logout")) ?></li>
                                    <li class="last"><?php echo CHtml::link(AmcWm::t("amcBack", "View Website"), array("/site/index"), array('target'=>'_blank')) ?></li>
                                </ul>
                            </li>
                            <li><?php echo CHtml::link(Yii::app()->user->getMessagesCount() . ' ' . AmcWm::t("amcBack", "messages"), array("/user/newMessages"), array("class" => "new_messages")) ?></li>
                        </ul>
                        <div id="server_details">
                            <dl>
                                <dt><?php echo AmcWm::t("amcBack", "time") ?>:</dt>
                                <dd><?php echo Yii::app()->getDateFormatter()->format('h:mm a', time()) ?></dd>
                            </dl>
                            <dl>
                                <dt><?php echo AmcWm::t("amcBack", "last_login_from") ?>:</dt>
                                <dd><?php echo Yii::app()->user->getLastLogIp() ?></dd>
                            </dl>
                        </div>
                    </div>			

                </div>   
            </div>

            <div id="main_menu">
                <div class="float">
                    <?php
//                    $menus = Yii::app()->user->getSubModulesList(Yii::app()->params['backendModuleName']);
                    $menus = Yii::app()->user->getSubModulesList('backend');
                    //$items = 
                    $items['sys_manage'] = array(
                        'label' => AmcWm::t("amcBack", 'Managment'),
                        'url' => array('#'),
                        'items' => $menus,
                    );
                    $items['sys_home'] = array(
                        'label' => AmcWm::t("amcBack", 'Home'),
                        'url' => array('/backend/default/index'),
                    );
                    //$this->widget('application.modules.backend.components.MainMenu', array()) 
                    $this->widget('amcwm.widgets.DropDownMenu.DropDownMenu'
                            , array('style' => 'default', // or default or navbar
                        'items' => array_reverse($items),
                            )
                    );
                    ?>
                </div>
                <div class="float">
                    <?php
                    $formContChange = $this->beginWidget('CActiveForm', array(
                        'id' => "content_lang_change",
                        'method' => 'get',
                        'enableAjaxValidation' => false,
                        'enableClientValidation' => true,
                        'action' => array("/backend/default/changeContentLang"),
                       
                        'clientOptions' => array(
                            'validateOnSubmit' => true,
                        ),
                            ));
                    ?>
                    <div class="row" style="color:#000000;">  
                        <?php echo CHtml::label(AmcWm::t("amcBack", "Current Content Language"), "clang") ?>
                        <?php echo CHtml::dropDownList("clang", Controller::getContentLanguage(), $this->getLanguages(), array("onchange" => "$('#content_lang_change').submit();")); ?>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>    
            </div>
            <div id="content">
                <div id="content_frm">
                    <div class="title_wrapper">
                        <h2><?php
                    if (isset($this->breadcrumbs))
                        $this->widget('Breadcrumbs', array('links' => $this->breadcrumbs));
                    ?>&nbsp;&nbsp;&nbsp;[<?php
                            if (isset($this->sectionName))
                                echo $this->sectionName
                        ?>]</h2>
                        <span class="title_wrapper_left"></span>
                        <span class="title_wrapper_right"></span>
                    </div> 
                    <div class="sct">
                        <?php
                        echo $this->getFlashMsg();
                        echo $content
                        ?></div> 			
                </div>
                <div id="content_uti">
                    <div class="title_wrapper_g">
                        <h2><?php echo AmcWm::t("amcFront", "statistics") ?></h2>
                        <span class="title_wrapper_left_g"></span>
                        <span class="title_wrapper_right_g"></span>
                    </div>
                    <div class="sct">
                        <?php
                        if (isset($this->statistics)) {
                            $vars['statistics_data'] = $this->statistics;
                        }
                        ?>
                    </div> 		

                </div>
            </div>
            <div id="footer">
                <div><b><?php echo AmcWm::t("amcBack", "amc_web_manager") ?></b></div>
                <div style="direction:ltr;"><?php echo AmcWm::t("amcFront", "copyright", array("{year}" => date("Y"))) ?></div>
            </div>
        </div>
    </body>
</html>
