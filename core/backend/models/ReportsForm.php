<?php

/**
 * TollVessels class.
 * TollVessels performs calculations for vessels navigating through Suez Canal.
 * It is used by the 'admin' action of 'DefaultController'.
 */
class ReportsForm extends CFormModel {

    public $datepicker_from;
    public $datepicker_to;
    public $user_id;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, datepicker_from', 'required'),
            array('scid', 'numerical', 'integerOnly' => true),
            array('staying_days, grt, scnt, draft, beam', 'numerical', 'integerOnly' => false),
            array('datepicker_from, datepicker_to, user_id', 'safe'),
            array('datepicker_from, datepicker_to, user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array();
    }

}
