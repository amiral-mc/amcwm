<div class="form">

    <?php
    $allOptions = $this->module->appModule->options;
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'id' => 'maillist-form',
        'type' => 'horizontal',
        'inlineErrors' => true,
        'action' => array("/maillist/default/subscribe"),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    $align = (Yii::app()->getLocale()->getOrientation() == "rtl") ? "right" : "left";
    ?>
    <?php echo CHtml::hiddenField('lang', Controller::getCurrentLanguage(), array('id' => 'maillist_lang')) ?>
    <?php
    $emailOptions['size'] = 20;
    $nameOptions['size'] = 20;
    ?>
    <fieldset>

        <div class="newsletter_title" style="text-align: <?php echo $align ?>"> 
            <?php echo AmcWm::t("amcFront", "Write down your Email") ?>
        </div>
        <div class="newsletter_body">
            <div class="newsletter_header"></div>
            <div class="newsletter_icon">
                <div style="padding-bottom: 5px;">
                    <?php
                    $selectChannels = Yii::app()->request->getParam('channels');
                    if ($channels !== NULL && !$allOptions['default']['check']['saveAllChannels']) {
                        if (count($channels)) {
                            echo '<h3 style="margin:3px">';
                            echo AmcWm::t('msgsbase.core', 'Join in this Channels');
                            echo '</h3>';


                            foreach ($channels as $channel) {
                                echo '<div>';
                                echo CHtml::checkBox("channels[{$channel['id']}]", isset($selectChannels[$channel['id']]), array('value' => $channel['id'], 'id' => 'ch' . $channel['id'], 'class' => 'channelsCks', 'onclick' => '$(\'#chAll\').attr(\'checked\', false);'));
                                echo CHtml::label($channel['channel'], 'ch' . $channel['id'], array('style' => 'display:inline;'));
                                echo '</div>';
                            }
                            echo '<div style="border-top:1px dotted #ccc; width:50%"></div>';
                            echo CHtml::checkBox('chAll', count($channels) == count($selectChannels), array('onclick' => 'if(this.checked){$(\'.channelsCks\').attr(\'checked\', true);}else{$(\'.channelsCks\').attr(\'checked\', false);}'));
                            echo CHtml::label(AmcWm::t('msgsbase.core', 'All Channels'), 'chAll', array('style' => 'display:inline;'));
                        }
                    }
                    ?>
                </div>
                <div style="clear: both; padding: 1px;"></div>
                <?php echo $form->textFieldRow($model->maillistUsers, 'name', $nameOptions); ?>                   
                <?php echo $form->textFieldRow($model->maillistUsers, 'email', $emailOptions); ?>
                <div class="form-actions">
                    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'type' => 'primary', 'label' => AmcWm::t("amcFront", 'Subscribe'))); ?>                        
                </div>
                <div style="clear: both; padding: 1px;"></div>
                <div class="newsletter_note" style="text-align: <?php echo $align ?>;"> 
                    <?php echo AmcWm::t("amcFront", "Newsletter Junk Mail") ?> 
                </div>
            </div>
        </div>							
    </fieldset>
    <?php $this->endWidget(); ?>
</div>

