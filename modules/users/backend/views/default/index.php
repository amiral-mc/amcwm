<?php
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.core", "Users"),
);
$this->sectionName = AmcWm::t("msgsbase.core", "Manage");
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/users/default/create'), 'id' => 'add_user', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'edit_user', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Delete'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'delete_users', 'image_id' => 'delete'),
        array('label' => AmcWm::t("msgsbase.core", 'Activate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'publish_users', 'image_id' => 'publish'),
        array('label' => AmcWm::t("msgsbase.core", 'Deactivate'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'unpublish_users', 'image_id' => 'unpublish'),
        array('label' => AmcWm::t("amcTools", 'Translate'), 'js' => array('formId' => Yii::app()->params["adminForm"],), 'id' => 'translate_event', 'image_id' => 'translate'),
        array('label' => AmcWm::t("msgsbase.core", 'Permissions'), 'js' => array('formId' => Yii::app()->params["adminForm"], 'action' => 'permissions'), 'id' => 'user_permissions', 'image_id' => 'permissions'),
        array('label' => AmcWm::t("amcTools", 'Search'), 'js' => array('formId' => Yii::app()->params["adminForm"]), 'id' => 'users_search', 'image_id' => 'search'),
        array('label' => AmcWm::t("msgsbase.core", 'Groups'), 'url' => array('/backend/users/groups/index') , 'id' => 'user_permissions', 'image_id' => 'groups'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/default/index'), 'id' => 'backend_list', 'image_id' => 'back'),
    ),
));
?>
<div class="form">
    <div class="search-form" style="display:none">
        <?php
        $this->renderPartial('_search', array(
            'model' => $model,
        ));
        ?>
    </div><!-- search-form -->
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => Yii::app()->params["adminForm"],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
            ));

    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = Yii::app()->params["pageSize"];
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'data-grid',
        'dataProvider' => $dataProvider,
        'selectableRows' => Yii::app()->params["pageSize"],
        'columns' => array(
            array(
                'class' => 'CheckBoxColumn',
                'checked' => '0',
                'checkBoxHtmlOptions' => array("name" => "ids"),
                'htmlOptions' => array('width' => '16', 'align' => 'center'),
            ),
            array(
                'name' => 'user_id',
                'htmlOptions' => array('width' => '16'),
            ),
            array(
                'name' => 'username',
                'htmlOptions' => array('width' => '110'),
            ),
            array(
                'name' => 'email',
                'htmlOptions' => array('width' => '120'),
            ),
            array(
                'name' => 'name',
                'htmlOptions' => array('width' => '120'),
            ),
            array(
                'name' => 'published',
                'value' => '($data->published) ? CHtml::image(Yii::app()->baseUrl . "/images/yes.png", "", array("border" => 0)) : CHtml::image(Yii::app()->baseUrl . "/images/no.png", "", array("border" => 0))',
                'type' => 'html',
                'htmlOptions' => array('width' => '20'),
            ),
        )
    ));
    $this->endWidget();
    ?>
</div>