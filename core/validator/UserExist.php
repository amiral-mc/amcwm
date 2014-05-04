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
class UserExist extends CValidator {

    /**
     * Error message to be displayed
     * @var string
     */
    public $errorMessage = null;

    /**
     * Validate latin characters
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @return void
     * @access public
     */
    public function validateAttribute($object, $attribute) {
        $currentRow = null;
        switch ($object->tableName()) {
            case 'users':
                $query = sprintf("SELECT user_id id, $attribute FROM users u              
                WHERE {$attribute} = '%s' limit 0, 1", $object->$attribute);
                $currentRow = 'user_id';
                break;
            case 'persons':
                $query = sprintf("SELECT person_id id, $attribute FROM persons p                 
                WHERE {$attribute} = '%s' limit 0, 1", $object->$attribute);
                $currentRow = 'person_id';
                break;
        }

        $currentAttribute = Yii::app()->db->createCommand($query)->queryRow();
        $attributeExist = $currentAttribute['id'];
        if (!$object->isNewRecord && $currentAttribute['id'] == $object->$currentRow) {
            $attributeExist = 0;
        }
        if ($attributeExist) {
            $this->addError($object, $attribute, AmcWm::t("amcFront", $this->errorMessage));
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