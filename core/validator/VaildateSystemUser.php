<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class VaildateSystemUser extends CValidator {

    /**
     * Error message to be displayed
     * @var string
     */
    public $errorMessage = NULL;

    /**
     * Validate latin characters
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @return void
     * @access public
     */
    public function validateAttribute($object, $attribute) {
        switch ($attribute) {
            case 'role_id':
                $currentUser = $object->findByPk($object->user_id);
                $error = (($currentUser['username'] != $object->username || !$object->published || $currentUser['role_id']!= $object->role_id) && $object->is_system);
                break;
        }
        if ($error) {
            $this->addError($object, $attribute, $this->errorMessage);
        }
    }

    /**
     * Returns the JavaScript needed for performing client-side validation.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @return string the client-side validation script.
     * @todo add ajax validation check
     * @see CActiveForm::enableClientValidation
     */
    public function clientValidateAttribute($object, $attribute) {
        $condition = 0;
        $scriptPart = 'if(' . $condition . ') {
            messages.push(' . CJSON::encode(AmcWm::t("amcFront", $this->errorMessage)) . ');
            }
        ';
        return $scriptPart;
    }

}