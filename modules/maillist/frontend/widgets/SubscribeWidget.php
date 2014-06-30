<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * EventsList extension draw events list
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SubscribeWidget extends SideWidget {

    /**
     * Newsletter title
     * @var string
     */
    public $newsletterTitle;

    /**
     * Maillist model
     * @var Maillist 
     */
    public $model;

    /**
     * Initializes widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        if (!$this->newsletterTitle) {
            $this->newsletterTitle = AmcWm::t($this->messageFile, "_newsletter_title_");
        }
        parent::init();
    }

    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function setContentData() {
        $emailOptions['value'] = AmcWm::t($this->messageFile, "_newsletter_email_");
        $emailOptions['size'] = 20;
        $emailOptions['onfocus'] = 'if(this.value=="' . $emailOptions['value'] . '"){this.value="";}';
        //$cs = Yii::app()->getClientScript();
        //$cs->registerCoreScript('yiiactiveform');                
        ob_start();
        ob_implicit_flush(false);
        echo '<div class="form newsletter_form">';
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'maillist-form',
            'action' => array("/maillist/default/subscribe", 'lang' => Controller::getCurrentLanguage()),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        echo '<div class="newsletter_title">';
        echo $this->newsletterTitle;
        echo '</div>';
        echo '<div class="newsletter_icon">';
        echo $form->textField($this->model, 'email', $emailOptions);
        echo CHtml::submitButton(AmcWm::t($this->messageFile, '_newsletter_subscribe_button_'));
        echo '<div>';
        echo $form->error($this->model, 'email');
        echo '</div>';
        echo '<div class="newsletter_note">';
        echo AmcWm::t($this->messageFile, "_newsletter_subscribe_notes_");
        echo '</div>';
        echo '</div>';
        $this->endWidget();
        echo '</div>';

        $this->contentData = ob_get_clean();
    }

}

