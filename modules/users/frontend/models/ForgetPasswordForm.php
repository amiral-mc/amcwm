<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ForgetPasswordForm class.
 * ForgetPasswordForm is the reset password form to recover user's password.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ForgetPasswordForm extends CFormModel {

    public $email;
    public $verifyCode;
    public $person;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('email', 'email'),
            array('email, verifyCode', 'required'),
            array('email', 'isEmailInList', 'msg' => AmcWm::t("msgsbase.core", 'The email does not exist')),
            array('email', 'length', 'max' => 65),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'submit'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email' => AmcWm::t("msgsbase.core", 'Email'),
            'verifyCode' => AmcWm::t("msgsbase.core", 'Captcha'),
        );
    }

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name=>value) to be set.
     * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
     * A safe attribute is one that is associated with a validation rule in the current {@link scenario}.
     * @see getSafeAttributeNames
     * @see attributeNames
     */
    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
        $this->person = Persons::model()->findByAttributes(array('email' => $this->email));
    }

    public function isEmailInList($attribute, $params) {
        $ok = isset($this->person->users);
        if (!$ok) {
            $this->addError($attribute, $params['msg']);
        }
    }

}
