<?php
Yii::import('bootstrap.form.*');

/**
 * Bootstrap form builder.
 */
class MyTbForm extends TbForm {

    /**
     * Initializes this form.
     * This method is invoked at the end of the constructor.
     * You may override this method to provide customized initialization (such as
     * configuring the form object).
     */
    protected function init() {
        $this->activeForm = array('class' => 'bootstrap.widgets.MyTbActiveForm');
    }
}