<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * CActiveForm provides a set of methods that can help to simplify the creation
 * of complex and interactive HTML forms that are associated with data models.
 *
 * @package AmcWm.core
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ActiveForm extends CActiveForm {

    /**
     * Renders a calendar field for a model attribute.
     * This method is a wrapper of {@link CHtml::activeDateField}.
     * Please check {@link CHtml::activeDateField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     * @since 1.1.11
     */
    public function calendarField($model, $attribute, $htmlOptions = array()) {
        return Form::activeCalendarField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a attachment field for a model.
     * This method is a wrapper of {@link CHtml::activeDateField}.
     * Please check {@link CHtml::activeDateField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     * @since 1.1.11
     */
    public function attachmentField($model, $attribute, $htmlOptions = array()) {
        return Form::activeAttachmentField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a rich text field for a model attribute.
     * This method is a wrapper of {@link CHtml::activeDateField}.
     * Please check {@link CHtml::activeDateField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     * @since 1.1.11
     */
    public function richTextField($model, $attribute, $htmlOptions = array()) {
        return Form::activeRichTextField($model, $attribute, $htmlOptions);
    }

    /**
     * 
     * Draw extendable fields
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param string $fieldType
     * @param array $fieldOptions field options like data .. htmlOptions
     */
    public function extendableField($model, $attribute, $fieldType = 'textField', $fieldOptions = array()) {
        if ($model->hasAttribute($attribute)) {
            return Form::activeExtendableField($model, $attribute, $fieldType, $fieldOptions);
        } else {
            $htmlOptions = array();
            if (isset($fieldOptions['htmlOptions'])) {
                $htmlOptions = $fieldOptions['htmlOptions'];
                unset($fieldOptions['htmlOptions']);
            }
            $attributeData = $model->getExtendedAttribute($attribute);
            if (!isset($attributeData['data'][$attribute])) {
                $attributeModel = new AttributeModel($attributeData['struct']);
            } else {
                $attributeModel = $attributeData['data'][$attribute];
            }
            $attributeModel->id = $attribute;
            $attributeModel->name = $attribute;
            $attributeModel->label = $attributeData['label'];
            switch ($fieldType) {
                case 'textField':
                    return $this->textField($attributeModel, "[{$model->getClassName()}][{$attributeModel->name}][$attribute]value", $htmlOptions);
                    break;
            }
        }
    }
    
    /**
     * 
     * Draw extendable label
     * @param CModel $model the data model
     * @param string $attribute the attribute
     */
    public function extendableLabel($model, $attribute) {
        if ($model->hasAttribute($attribute)) {
            return $this->labelEx($model, $attribute);
        } else {
            $attributeData = $model->getExtendedAttribute($attribute);
            if (!isset($attributeData['data'][$attribute])) {
                $attributeModel = new AttributeModel($attributeData['struct']);
            } else {
                $attributeModel = $attributeData['data'][$attribute];
            }
            $attributeModel->id = $attribute;
            $attributeModel->name = $attribute;
            $attributeModel->label = $attributeData['label'];
            return $this->labelEx($attributeModel, "[{$model->getClassName()}][{$attributeModel->name}][$attribute]value");           
        }
    }
    
    /**
     * 
     * Draw extendable error
     * @param CModel $model the data model
     * @param string $attribute the attribute
     */
    public function extendableError($model, $attribute) {
        if ($model->hasAttribute($attribute)) {
            return $this->error($model, $attribute);
        } else {
            $attributeData = $model->getExtendedAttribute($attribute);
            if (!isset($attributeData['data'][$attribute])) {
                $attributeModel = new AttributeModel($attributeData['struct']);
            } else {
                $attributeModel = $attributeData['data'][$attribute];
            }
            $attributeModel->id = $attribute;
            $attributeModel->name = $attribute;
            $attributeModel->label = $attributeData['label'];
            return $this->error($attributeModel, "[{$model->getClassName()}][{$attributeModel->name}][$attribute]value");           
        }
    }

}
