<?php
//$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>

<div style="margin: 100px auto;width:300px;">
    <div style="margin: 10px auto;width:95px;">
        <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/warning.png" alt="Error <?php echo $code; ?>"/>
    </div>
    <div class="error" style="text-align: center;">
        <?php echo CHtml::encode($message); ?>
    </div>
</div>