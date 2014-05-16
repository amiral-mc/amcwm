<?php

class NewsletterWidget extends Widget {

    
    /**
     * Maillist model
     * @var MaillistUsers 
     */
    public $model;    
    
    public $title;
    
    /**
     * Render the widget and display the result
     * @access public
     * @return void
     */
    public function run() {
        $htmlOptions['id'] = $this->id;
        $htmlOptions['class'] = 'wdg_box';        
        echo CHtml::openTag('div', $htmlOptions);
        echo '<div class="wdg_box_head"><h2><strong>' . $this->title. '</strong></h2></div>';
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => $this->id . "-form",
            'type' => 'inline',
            'htmlOptions' => array('class' => 'well'),
            
            'action' => array('/maillist/default/subscribe'),
//            'enableClientValidation' => true,
//            'clientOptions' => array(
//                'validateOnSubmit' => true,
//            ),
        ));
        $this->model->setAttributeLabel('email', AmcWm::t("app", 'Enter your E-mail Address'));
        $append = '<button type="submit" class="append-button"><i class="icon-play" ></i></button>';        
        echo $form->textFieldRow($this->model, 'email', array('append'=>$append,'class' => 'input-medium',));
        $this->endWidget();
        echo CHtml::closeTag("div");
    }

}
