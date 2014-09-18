<?php
$baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish.css"));
?>
<html dir="<?php echo Yii::app()->getLocale()->getOrientation() ?>" lang="<?php echo Yii::app()->getLanguage() ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo AmcWm::t('amcBack', 'Reports')?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $baseScript . "/" . Yii::app()->getLanguage() . "/" ?>reports.css" media="all" />
    </head>
    <body>
        <?php echo $content ?>
    </body>
</html>