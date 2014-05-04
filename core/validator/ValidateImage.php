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
class ValidateImage extends CValidator {

    /**
     * Error message to be displayed
     * @var array
     */
    public $errorMessage = array();
    /**
     * image values to be checked
     * @var array 
     */
    public $checkValues = array('width' => 8, 'height' => 8, 'exact' => false, 'allowedUploadRatio' => 10);

    /**
     * Validate latin characters
     * If there is any error, the error message is added to the object.
     * @param CModel $object the object being validated
     * @param string $attribute the attribute being validated
     * @return void
     * @access public
     */
    public function validateAttribute($object, $attribute) {
        $object->$attribute = CUploadedFile::getInstance($object, $attribute);
        $error = false;
        $messages = array();
        if ($object->$attribute instanceof CUploadedFile) {
            $error = true;
            $image = new Image($object->$attribute->getTempName());
            if (isset($this->checkValues['dimensions'])) {
                $dimensions = $this->checkValues['dimensions'];
            } else {
                $dimensions[0]['width'] = $this->checkValues['width'];
                $dimensions[0]['height'] = $this->checkValues['height'];
            }
            if ($this->checkValues['exact']) {
                foreach ($dimensions as $dimension) {
                    $messages[] = AmcWm::t("amcFront", $this->errorMessage['exact'], array("{width}" => $dimension['width'], '{height}' => $dimension['height'], '{maxwidth}' => $dimension['width'] * $this->checkValues['allowedUploadRatio'], '{maxheight}' => $dimension['height'] * $this->checkValues['allowedUploadRatio']));
                    $error = ($error && !$image->checkExactImageSize($dimension['width'], $dimension['height'], $this->checkValues['allowedUploadRatio']));    
                }
            } else {
                foreach ($dimensions as $dimension) {
                    $messages[] = AmcWm::t("amcFront", $this->errorMessage['notexact'], array("{width}" => $dimension['width'] * $this->checkValues['allowedUploadRatio'], '{height}' => $dimension['height'] * $this->checkValues['allowedUploadRatio']));                    
                    $error = ($error && !$image->checkImageSizeInArea($dimension['width'] * $this->checkValues['allowedUploadRatio'], $dimension['height'] * $this->checkValues['allowedUploadRatio']));                    
                }
            }
        }        
        if ($error) {
            $message = implode("<br />", $messages);
            $this->addError($object, $attribute, $message);
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
            messages.push(' . CJSON::encode(AmcWm::t("amcFront", $this->errorMessage['exact'])) . ');
            }
        ';
        return $scriptPart;
    }

}