<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class UserCharacters extends CValidator {
    
    /**
     * Pattern used to check against username
     * @var string
     */
    private $pattern = "/^[a-z]([0-9a-z_.@-])+$/i";    
     /**
     * Error message to be displayed
     * @var string
     */
    private $errorMessage = 'Username contains invalid characters, only letters, numbers and _ are allowed.';
    /**
     * Validate latin characters
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @return void
     * @access public
     */
    public function validateAttribute($object, $attribute) {
        
        if (!preg_match($this->pattern, $object->$attribute))
            $this->addError($object, $attribute, AmcWm::t("amcFront",$this->errorMessage));
    }

    /**
     * Returns the JavaScript needed for performing client-side validation.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @return string the client-side validation script.
     * @see CActiveForm::enableClientValidation
     */
    public function clientValidateAttribute($object, $attribute) {
        $condition = "!value.match({$this->pattern})";
        $scriptPart = 'if('.$condition.') {
            messages.push(' . CJSON::encode(AmcWm::t("amcFront",$this->errorMessage)) .');
            }
        ';        
        return $scriptPart;
    }

}