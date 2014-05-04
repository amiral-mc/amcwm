<?php
$channelId = ($this->channel) ? $this->channel->id : null;
$this->breadcrumbs = array(
    AmcWm::t("msgsbase.mailing", "Maillist Message") => array('/backend/maillist/messages/index', 'cid' => $channelId),
    AmcWm::t("amcTools", "View"),
);

$this->sectionName = $model->subject;

$this->widget('amcwm.core.widgets.tools.Tools', array(
    'id' => 'tools-grid',
    'items' => array(
        array('label' => AmcWm::t("amcTools", 'Create'), 'url' => array('/backend/maillist/messages/create', 'cid' => $channelId), 'id' => 'add_maillist', 'image_id' => 'add'),
        array('label' => AmcWm::t("amcTools", 'Edit'), 'url' => array('/backend/maillist/messages/update', 'id' => $model->id, 'cid' => $channelId), 'id' => 'edit_maillist', 'image_id' => 'edit'),
        array('label' => AmcWm::t("amcTools", 'Back'), 'url' => array('/backend/maillist/messages/index', 'cid' => $channelId), 'id' => 'maillist_list', 'image_id' => 'back'),
    ),
));
$cronCondition = MaillistMessage::cronConditionsList($model->cron_condition);
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        array(
            'name' => 'template_id',
            'value' => (isset($model->template->subject)) ? $model->template->subject : null,
        ),
          array(
            'name' => 'published',
            'value' => ($model->published) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        array(
            'name' => 'cron_condition',
            'value' => $cronCondition,
        ),
        array(
            'name' => 'cron_start',
            'value' => ($model->cron_start) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->cron_start) : NULL,
        ),        
       
        array(
            'name' => 'cron_end',
            'value' => ($model->cron_end) ? Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $model->cron_end) : NULL,
        ),
        array(
            'name' => 'withoutCronEnd',
            'value' => ($model->withoutCronEnd) ? AmcWm::t("amcBack", "Yes") : AmcWm::t("amcBack", "No"),
        ),
        'subject',        
        array(
            'name' => 'body',
            'type' => 'raw',
        ),
    ),
));
