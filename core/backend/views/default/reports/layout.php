<?php
$currentAppLang = Yii::app()->getLanguage();
$baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
?>
<html dir="<?php echo Yii::app()->getLocale()->getOrientation() ?>" lang="<?php echo Yii::app()->getLanguage() ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo AmcWm::t('amcBack', 'Reports')?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $baseScript ?>/css/<?php echo $currentAppLang ?>/style.css" />        
        <link rel="stylesheet" type="text/css" href="<?php echo $baseScript ?>/css/<?php echo $currentAppLang ?>/reports.css" />        
    </head>
    <body>
        <?php echo $content ?>
    </body>
</html>