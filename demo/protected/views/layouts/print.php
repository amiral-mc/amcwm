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
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/<?php echo $currentAppLang ?>/service_print.css?v=" media='screen, projection, print' />
    </head>
    <body>
        <div id="print_bar">            
            <a href="javascript:window.print()" class="doc-print"><?php echo AmcWm::t("amcTools", 'Print'); ?></a>
        </div>
        <div id="container">       
            <?php echo $content; ?>            
        </div>        
    </body>
    <?php
    if (Yii::app()->request->getParam('p')) {
        Yii::app()->getClientScript()->registerScript("printWindow", "window.print();");
    }
    ?>
</html>
