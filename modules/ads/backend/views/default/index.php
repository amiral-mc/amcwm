<?php
$this->breadcrumbs = array(AmcWm::t("msgsbase.core", "Ads"));
$this->sectionName = AmcWm::t("amcTools", "List");

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Preview'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'preview_record', 'image_id' => 'view'),
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/ads/default/create'), 'id' => 'add_record', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'add_record', 'image_id' => 'edit'),
        array('label' => AmcWm::t("msgsbase.servers", 'Ad Servers'), 'url' => array('/backend/ads/servers/index'), 'id' => 'servers', 'image_id' => 'listing'),        
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'records_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_record', 'image_id' => 'delete'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'admin_list', 'image_id' => 'back'),
    ),
));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => Yii::app()->params["adminForm"],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));

    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'selectableRows' => 2,
        'dataProvider' => $model->search(),
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'ad_id',
                'htmlOptions' => array('width' => '40', 'align' => 'center'),
            ),           
            array(                
                'header'=> AmcWm::t("msgsbase.core", "Zone Name"),
                'value'=>'$data["zone_name"]',
                'htmlOptions' => array('width' => '100', 'align' => 'center'),
            ),          
            array(
                'header'=> AmcWm::t("msgsbase.core", "Server Name"),
                'value'=>'$data["server_name"]',
                'htmlOptions' => array('width' => '80', 'align' => 'center'),
            ),
        ),
    ));
    $this->endWidget();
    ?>
</div>