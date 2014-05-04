<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ForgetPasswordForm class.
 * ForgetPasswordForm is the reset password form to recover user's password.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ResetPasswordForm extends CFormModel {

    public $email;
    public $passwd;
    public $passwdRepeat;
    public $verifyCode;
    public $person;
    public $key;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('email', 'email'),
            array('email, passwd, passwdRepeat, verifyCode', 'required'),
            array('key', 'isValidKey', 'msg' => AmcWm::t("msgsbase.core", 'The key does not exist')),
            array('email', 'isEmailInList', 'msg' => AmcWm::t("msgsbase.core", 'The email does not exist')),
            array('email', 'length', 'max' => 65),
            array('passwd', 'length', 'max' => 32, 'min' => 8),
            array('key', 'length', 'max' => 8, 'min' => 8),
            array('passwdRepeat', 'compare', 'compareAttribute' => 'passwd', 'operator' => '='),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email' => AmcWm::t("msgsbase.core", 'Email'),
            'passwd' => AmcWm::t("msgsbase.core", 'Password'),
            'passwdRepeat' => AmcWm::t("msgsbase.core", 'Confirm Password'),
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

     /**
     * has reset key
     * @return boolean
     */
    public function hasResetKey() {
        $resetAttributes = array(
            'reset_date' => date("Y-m-d"),
            'reset_key' => $this->key,
        );
        $reset = ResetPasswods::model()->findByAttributes($resetAttributes);
        return $reset !== null;
    }
    
    public function isValidKey($attribute, $params) {
        $ok = $this->hasResetKey();
        if (!$ok) {
            $this->addError($attribute, $params['msg']);
        }
    }

    public function isEmailInList($attribute, $params) {
        $ok = isset($this->person->users) && $this->person->users->hasResetKey($this->key);
        if (!$ok) {
            $this->addError($attribute, $params['msg']);
        }
    }

    public function savePasswd() {
        $this->person->users->setAttribute('passwd', md5($this->passwd));
        $this->person->users->save();
        ResetPasswods::model()->deleteAllByAttributes(array('user_id' => $this->person->users->user_id));
    }

}
