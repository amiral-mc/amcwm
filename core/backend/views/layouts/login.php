<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
    $currentAppLang = Yii::app()->getLanguage();
    $baseScript = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
?>
<!DOCTYPE html>
<html dir="<?php echo Yii::app()->getLocale()->getOrientation() ?>" lang="<?php echo Yii::app()->getLanguage() ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $baseScript ?>/css/<?php echo $currentAppLang ?>/login.css" />
        <title><?php echo $this->pageTitle ?></title>
    </head>
    <body class="pageError">
        <div id="wrapper">
            <div id="login_wrapper">
               <?php echo $content ?>
            </div>
        </div>        
    </body>
</html>