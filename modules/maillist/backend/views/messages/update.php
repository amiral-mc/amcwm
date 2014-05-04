<?php
$channelId = ($this->channel) ? $this->channel->id : null;
$formId = Yii::app()->params["adminForm"];
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.mailing", "Maillist Message") => array('/backend/maillist/messages/index', 'cid' => $channelId),
    AmcWm::t("amcTools", "Edit"),
);

$this->sectionName = $model->subject;
$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Save'), 'js' => array('formId' => $formId), 'id' => 'edit_maillist', 'image_id' => 'save'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/messages/index', 'cid' => $channelId), 'id' => 'maillist_list', 'image_id' => 'back'),
    ),
));
?>
<?php echo $this->renderPartial('_form', array('model' => $model, 'formId' => $formId)); ?>