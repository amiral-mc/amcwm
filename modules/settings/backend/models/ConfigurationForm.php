<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ConfigurationForm controller.
 * ConfigurationForm is the data structure for keeping
 * Configuration form data. It is used by the 'Configuration' controller.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ConfigurationForm extends CFormModel {

    public $configProperties;
    public $content_lang;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('configProperties', 'required'),
            array('configProperties', 'validateItems'),
        );
    }

    public function validateItems($attribute, $params) {
        $languages = Yii::app()->params['languages'];
        $formAttribute = $this->$attribute;
        foreach ($languages as $lang => $name) {
            $langFromAttrib = $formAttribute[$lang];
            foreach ($langFromAttrib as $attributeName => $attributeValue) {
                if ($attributeValue == "") {
                    $this->addError($attribute, Yii::t("yii", "{attribute} cannot be blank.", array("{attribute}" => $attributeName . " " . $name)));
                }
            }
        }
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
//    public function attributeLabels() {
//        return array(
//            'configProperties' => 'Verification Code',
//        );
//    }
}