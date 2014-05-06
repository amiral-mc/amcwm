<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * The source code copied from:
 * http://yiihaa.com/validate-unique-constraints-with-more-then-one-attribute
 * Add expression checked by ashrafakl@yahoo.com
 * Add uniqueParams by ashrafakl@yahoo.com
 * Change with from string to array by ashrafakl@yahoo.com
 * Example:
 * array('legal_id', 'UniqueAttributesValidator', 'skipOnError' => true,
 *   'with' => array('key1', 'key2'),
 *       'expressions'=>array('key3'=>array(
 *         'expression'=>'SQL_FUNC(KEY3)',
 *         'value'=>"VALUE",
 *         'useEval'=>true,
 *    )),
 *    'uniqueParams' => array('caseSensitive' => false))
 * 
 * @version 1.0
 */
class UniqueAttributesValidator extends CValidator {

    /**
     * The attributes boud in the unique contstraint with attribute
     * @var array
     */
    public $with = array();

    /**
     * experssions to bound in the unique constraint criteria
     * @var array
     */
    public $expressions = array();

    /**
     * Unique params sent to CUniqueValidator class
     * @var array
     */
    public $uniqueParams;

    /**
     * Validates the attribute of the object.
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     */
    protected function validateAttribute($object, $attribute) {
        if (count($this->with) < 1 && count($this->expressions < 1))
            throw new Exception("Attribute 'with' or 'expressions' not set");
        $uniqueValidator = new CUniqueValidator();
        $uniqueValidator->attributes = array($attribute);
        $uniqueValidator->message = $this->message;
        $uniqueValidator->on = $this->on;
        $conditionParams = array();
        $params = array();
        foreach ($this->with as $attribute) {
            $attribute = trim($attribute);
            $conditionParams[] = "`{$attribute}`=:{$attribute}";
            $params[":{$attribute}"] = $object->$attribute;
        }
        foreach ($this->expressions as $attribute => $expression) {
            $conditionParams[] = "{$expression['expression']}=:{$attribute}";
            $value = str_replace($attribute, $object->$attribute, $expression['value']);
            if ($expression['useEval']) {
                eval('$params[":' . $attribute . '"]=' . $value . ";");
            } else {
                $params[":{$attribute}"] = $value;
            }
        }
        $condition = implode(" AND ", $conditionParams);

        foreach ($this->uniqueParams as $paramKey => $paramValue) {
            $uniqueValidator->$paramKey = $paramValue;
        }

        $uniqueValidator->criteria = array(
            'condition' => $condition,
            'params' => $params
        );
        $uniqueValidator->validate($object);
    }

}
