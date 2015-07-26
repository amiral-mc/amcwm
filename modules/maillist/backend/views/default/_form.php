<div class="form">
    <?php
    $allOptions = $this->module->appModule->options;
    $channels = array();
    if ($allOptions['default']['check']['showChannels'] && !$allOptions['default']['check']['saveAllChannels']) {
        $channels = MaillistChannels::model()->findAllByAttributes(array('content_lang' => Controller::getCurrentLanguage()));
        $selectedChannels = Yii::app()->request->getParam('channels');
        $selectChannels = Yii::app()->request->getParam('channels');
        foreach ($model->maillistChannels as $channel){
            $selectChannels[$channel->channel_id] = $channel->channel_id;
        }
    }
    $form = $this->beginWidget('CActiveForm', array(
        'id' => $formId,
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    ?>
    <p class="note"><?php echo AmcWm::t("amcBack", "Fields with {star} are required", array("{star}" => "<span class='required'>*</span>")); ?>.</p>

    <?php echo $form->errorSummary($model); ?>
    <fieldset>
        <legend><?php echo AmcWm::t("msgsbase.core", "Email data"); ?>:</legend>

  <div class="row">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->checkbox($model, 'status'); ?>
            <?php echo $form->error($model, 'status'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model->maillistUsers, 'name'); ?>
            <?php echo $form->textField($model->maillistUsers, 'name', array('size' => 45, 'maxlength' => 145)); ?>
            <?php echo $form->error($model->maillistUsers, 'name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model->maillistUsers, 'email'); ?>
            <?php echo $form->textField($model->maillistUsers, 'email', array('size' => 45, 'maxlength' => 145, 'style' => 'direction:ltr')); ?>
            <?php echo $form->error($model->maillistUsers, 'email'); ?>
        </div>
        <?php        
        if ($channels && !$allOptions['default']['check']['saveAllChannels']) {
            
            echo '<div style="border-top:1px dotted #ccc; width:50%"></div>';
            echo CHtml::checkBox('chAll', count($channels) == count($selectChannels), array('onclick' => 'if(this.checked){$(\'.channelsCks\').attr(\'checked\', true);}else{$(\'.channelsCks\').attr(\'checked\', false);}'));
            echo CHtml::label(AmcWm::t('msgsbase.core', 'All Channels'), 'chAll', array('style' => 'display:inline;'));
            foreach ($channels as $channel) {
                echo '<div>';
                echo CHtml::checkBox("channels[{$channel['id']}]", isset($selectChannels[$channel['id']]), array('value' => $channel['id'], 'id' => 'ch' . $channel['id'], 'class' => 'channelsCks', 'onclick' => '$(\'#chAll\').attr(\'checked\', false);'));
                echo CHtml::label($channel['channel'], 'ch' . $channel['id'], array('style' => 'display:inline;'));
                echo '</div>';
            }
            
        }
        ?>
     
    </fieldset>

    <?php $this->endWidget(); ?>

</div><!-- form -->