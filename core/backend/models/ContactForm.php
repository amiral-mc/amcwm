<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ContactForm extends CFormModel {

    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // name, email, subject and body are required
            array('name, email, subject, body', 'required'),
            // email has to be a valid email address
            array('email', 'email'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'verifyCode' => 'Verification Code',
        );
    }

}