<?php
$currentAppLang = Yii::app()->getLanguage();
$days = $this->getDaysList();
$locale = Yii::app()->getLocale();
?>
<!DOCTYPE html>
<html dir="<?php echo Yii::app()->getLocale()->getOrientation() ?>" lang="<?php echo $currentAppLang ?>">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />                
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" />
        <meta name="generator" content="<?php echo AmcWm::t("amcFront", "Amiral Management Corporation  http://amc.amiral.com") ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/<?php echo $currentAppLang ?>/front.css?v=" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/<?php echo $currentAppLang ?>/print.css?v=" media="print" />
        <!--[if IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/<?php echo $currentAppLang ?>/frontIE8.css" media="screen, projection" />
        <![endif]-->
        <!--[if lt IE 8]>
        <script>document.location.href = '<?php echo Yii::app()->request->baseUrl; ?>/ie6.html'</script>
        <![endif]-->
    </head>
    <body>
        <div id="container">                                   
            <div id="header">
                <div class="wrapper">
                    <div id="logo">
                    </div>
                    <div id="right_clm">
                        <div class="row1">
                            <div id="top_menu">
                                <?php
                                $url[0] = trim($this->getRoute(), "/");
                                $errorRoute = trim(AmcWm::app()->errorHandler->errorAction, "/") == $url[0];
                                $menuLanguages = array();
                                if ($errorRoute) {
                                    $url[0] = "/" . AmcWm::app()->defaultController . "/index";
                                } else {
                                    $url[0] = "/" . $url[0];
                                    $url = array_merge($url, $this->getActionParams());
                                }
                                $topMenuItems = Menus::getMenu('TopMenu')->getMenuItems();
                                foreach (Yii::app()->params['languages'] as $language => $languageName) {
                                    if ($language != $currentAppLang) {
                                        $url['lang'] = $language;
                                        $topMenuItems[] = array('label' => $languageName, 'url' => $url);
                                    }
                                }
                                $this->widget('Menu', array(
                                    'items' => $topMenuItems,
                                ));
                                ?>
                            </div>
                            <?php if (AmcWm::app()->user->isGuest): ?>                            
                                <div id="login_label"><?php echo AmcWm::t("amcFront", 'Access your Account') ?></div>
                            <?php else: ?>
                                <div id="login_label">
                                    <?php
                                    $user = Yii::app()->user->getInfo();
                                    echo AmcWm::t("app", 'You logged as {user}', array('{user}' => "<span>{$user['username']}</span>"));
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="row2">
                            <div id="search_area"> 
                                <?php
                                $searchForm = $this->beginWidget('CActiveForm', array(
                                    'id' => 'search_form',
                                    'action' => array('/site/search'),
                                    'enableClientValidation' => true,
                                    'clientOptions' => array(
                                        'validateOnSubmit' => true,
                                    ),
                                    'htmlOptions' => array(
                                        'class' => 'well form-search',
                                    ),
                                    'method' => 'get',
                                ));
                                ?>
                                <div class="input-append">
                                    <?php echo CHtml::textField('q', ((Yii::app()->request->getParam('q')) ? Yii::app()->request->getParam('q') : ""), array('class' => 'span2', 'style' => 'width: 150px;', 'placeholder' => AmcWm::t("amcFront", 'Enter Search Keywords'))) ?>        
                                    <span class="add-on">             
                                        <button type="submit" class="append-button">        
                                            <i class="icon-search"></i>
                                        </button>
                                    </span>

                                </div>
                                <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage(), array('id' => 'search_lang_t')); ?>
                                <?php $this->endWidget(); ?>
                            </div>
                            <div id="online_access_area">
                                <?php if (AmcWm::app()->user->isGuest): ?>
                                    <?php
                                    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                                        'id' => 'inlineLoginForm',
                                        'type' => 'inline',
                                        'htmlOptions' => array('class' => 'well'),
                                        'action' => array('/site/login'),
                                    ));
                                    ?>                                

                                    <?php echo $form->textFieldRow($this->loginModel(), 'username', array('class' => 'input-small')); ?>
                                    <?php echo $form->passwordFieldRow($this->loginModel(), 'password', array('class' => 'input-small')); ?>
                                    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'label' => AmcWm::t("app", 'Log in'))); ?>
                                    <div class="access_info">
                                        <?php echo AmcWm::t("app", 'Register') ?> <?php echo html::link('<strong>' . AmcWm::t("app", 'Now') . '</strong>', array('/users/default/register')) ?> ,
                                        <?php echo AmcWm::t("app", 'Forgot Your') ?> <?php echo html::link('<strong>' . AmcWm::t("app", 'Password') . '</strong>', array('/users/default/forgot')) ?>
                                    </div>
                                    <?php $this->endWidget(); ?>                                
                                <?php else : ?>
                                    <div class="access_info">
                                        <?php echo html::link('<span class="icon-th-large" style="margin-top:-1px;"></span> <strong>' . AmcWm::t("app", 'My Account') . '</strong>', array('/users/default/index')) ?>,
                                        <?php echo html::link('<span class="icon-eject" style="margin-top:-1px;"></span> <strong>' . AmcWm::t("app", 'Logout') . '</strong>', array('/site/logout')) ?>
                                    </div>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="main_menu">
                <div class="main_menu_wrapper">
                    <div id="l_menu">
                        <?php
                        $this->widget('Menu', array(
                            'items' => Menus::getMenu("MainMenu")->getMenuItems(),
                            'encodeLabel' => false,
                        ));

                        $this->widget('amcwm.widgets.stockData.StockDataTicker', array(
                            'id' => 'stockDataTicker',
                        ));
                        ?>
                    </div>
                    <?php if ($this->isHomePage): ?>

                    <?php endif; ?>
                </div>
            </div>            
            <?php echo $content; ?>
            <div id="footer">
                <div class="footer_wrapper">                    
                    <?php if ($this->memberships['section'] && $this->memberships['articles']): ?>
                        <div id="membership_list">
                            <div class="title"><?php echo $this->memberships['section']['sectionTitle'] ?></div>                           
                            <div class="list">
                                <?php
                                $this->widget('amcwm.widgets.imageCarousel.ImageCarousel', array(
                                    'items' => $this->memberships['articles'],
                                    'title' => $this->memberships['section']['sectionTitle'],
                                    'contentOnly' => true,
                                    'prevClass' => "membership-carousel-prev",
                                    'nextClass' => "membership-carousel-next",
                                    'options' => array(
                                        'ContainerWidth' => 950,
                                        'ContainerHeight' => 100,
                                        'VeiwElements' => 5),
                                    'arrows' => array(
                                        'next' => Yii::app()->request->baseUrl . '/images/front/member_slider_arrow_right.png',
                                        'prev' => Yii::app()->request->baseUrl . '/images/front/member_slider_arrow_left.png',
                                    )
                                ));
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>                   
                    <div id="footer_line">
                        <div class="credit">                            
                            <?php echo AmcWm::t("app", "Designed and Developed By"); ?>                            
                            <a href="http://amc.amiral.com"><?php echo AmcWm::t("app", "AMIRAL Management Corporation"); ?></a></div>
                        <div class="copyright">
                            <?php echo AmcWm::t("app", 'Website {year} Copyright © All rights reserved', array('{year}' => date('Y'))) ?>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </body>
</html>