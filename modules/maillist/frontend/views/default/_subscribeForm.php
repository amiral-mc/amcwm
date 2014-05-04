<div>
    <div class="form">
        <?php
        $allOptions = $this->module->appModule->options;
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'maillist-form',
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
        if (AmcWm::app()->request->requestType == "GET") {
            $emailOptions['value'] = AmcWm::t("amcFront", "Email");
            $emailOptions['placeholder'] = AmcWm::t("amcFront", "Email");

            $emailOptions['onfocus'] = 'if(this.value=="' . $emailOptions['value'] . '"){this.value="";}';

            $nameOptions['value'] = AmcWm::t("msgsbase.core", "Name");
            $nameOptions['placeholder'] = AmcWm::t("msgsbase.core", "Name");

            $nameOptions['onfocus'] = 'if(this.value=="' . $nameOptions['value'] . '"){this.value="";}';
        }
        ?>
        <div class="wdl_content newsletter_bg">		   	
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

                    <div style="margin:0px auto;float: <?php echo $align ?>;">
                        <?php //echo $form->labelEx($model->maillistUsers, 'name'); ?>
                        <?php echo $form->textField($model->maillistUsers, 'name', $nameOptions); ?>
                        <?php echo $form->error($model->maillistUsers, 'name'); ?>
                    </div>

                    <div style="clear: both; padding: 1px;"></div>

                    <div style="margin:0px auto;float: <?php echo $align ?>;">
                        <?php echo $form->textField($model->maillistUsers, 'email', $emailOptions); ?>
                    </div>

                    <div style="margin-<?php echo $align; ?>: 5px;float: <?php echo $align ?>;">
                        <?php echo CHtml::submitButton(AmcWm::t("amcFront", 'Subscribe')); ?>
                    </div>

                    <div style="clear: both; text-align: <?php echo $align ?>">
                        <?php echo $form->error($model->maillistUsers, 'email'); ?>
                    </div>

                    <div class="newsletter_note" style="text-align: <?php echo $align ?>;"> 
                        <?php echo AmcWm::t("amcFront", "Newsletter Junk Mail") ?> 
                    </div>
                </div>
            </div>							
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>

