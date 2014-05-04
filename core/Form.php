<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Form control form views permissions rules
 * of complex and interactive HTML forms that are associated with data models.
 *
 * @package AmcWm.core
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Form extends CComponent {

    /**
     * The current CActiveForm attached with this form manager
     * @var ActiveForm 
     */
    private $_form;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->_form = new ActiveForm;


//        $this->_form->onCallMethod = new CEvent($this->_form);
    }

    /**
     * Sets value of a component property.
     * Do not call this method. This is a PHP magic method that we override
     * to allow using the following syntax to set a property or attach an event handler
     * <pre>
     * $this->propertyName=$value;
     * $this->eventName=$callback;
     * </pre>
     * @param string $name the property name or the event name
     * @param mixed $value the property value or callback
     * @return mixed
     * @throws CException if the property/event is not defined or the property is read only.
     * @see __get
     */
    public function __set($name, $value) {
        $this->_form->$name = $value;
        if (!isset($this->_form->$name)) {
            parent::__set($name, $value);
        }
    }

    /**
     * Returns a property value, an event handler list or a behavior based on its name.
     * Do not call this method. This is a PHP magic method that we override
     * to allow using the following syntax to read a property or obtain event handlers:
     * <pre>
     * $value=$component->propertyName;
     * $handlers=$component->eventName;
     * </pre>
     * @param string $name the property name or event name
     * @return mixed the property value, event handlers attached to the event, or the named behavior
     * @throws CException if the property or event is not defined
     * @see __set
     */
    public function __get($name) {
        if (isset($this->_form->$name)) {
            return $this->_form->$name;
        } else {
            return parent::__get($name);
        }
    }

    /**
     * Calls the named method which is not a class method.
     * Do not call this method. This is a PHP magic method that we override
     * to implement the behavior feature.
     * @param string $name the method name
     * @param array $parameters method parameters
     * @return mixed the method return value
     */
    public function __call($name, $parameters) {
        if (!method_exists($this, $name)) {
            return $this->_checkMethod($name, $parameters);
        }
    }

    /**
     * Check and calls the named method which is not a class method.
     * Do not call this method. This is a PHP magic method that we override
     * to implement the behavior feature.
     * @param string $name the method name
     * @param array $parameters method parameters
     * @return mixed the method return value
     */
    private function _checkMethod($name, $parameters) {
        return call_user_func_array(array($this->_form, $name), $parameters);
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
    static public function activeAttachmentField($model, $attribute, $htmlOptions = array()) {
        $attachOptions = array();
        if (isset($htmlOptions['attachOptions'])) {
            $attachOptions = $htmlOptions['attachOptions'];
            unset($htmlOptions['attachOptions']);
        }
        $widgetOptions = array(
            'model' => $model,
            'attribute' => $attribute,
            'attachOptions' => $attachOptions,
            'htmlOptions' => $htmlOptions,
        );
        if (isset($htmlOptions['id'])) {
            $widgetOptions["id"] = $htmlOptions['id'];
        }
        AmcWm::app()->getController()->widget('amcwm.core.widgets.attachment.AttachmentWidget', $widgetOptions);
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
    static public function activeRichTextField($model, $attribute, $htmlOptions = array()) {
        if (isset($htmlOptions['editorTemplate'])) {
            $editorTemplate = $htmlOptions['editorTemplate'];
            unset($htmlOptions['editorTemplate']);
        } else {
            $editorTemplate = "full";
        }
        if (!isset($htmlOptions['style'])) {
            $htmlOptions['style'] = "";
        } else {
            $htmlOptions['style'] .= trim($htmlOptions['style'], ";") . ";";
        }
        if (isset($htmlOptions['width'])) {
            $htmlOptions['style'] .= "width:{$htmlOptions['width']};";
            unset($htmlOptions['width']);
        }
        if (isset($htmlOptions['height'])) {
            $htmlOptions['style'] .= "height:{$htmlOptions['height']};";
            unset($htmlOptions['height']);
        }

        $widgetOptions = array(
            'model' => $model,
            'attribute' => $attribute,
            'editorTemplate' => $editorTemplate,
            'htmlOptions' => $htmlOptions,);
        if (isset($htmlOptions['id'])) {
            $widgetOptions["id"] = $htmlOptions['id'];
        }
        if (isset($htmlOptions['rteFileManager'])) {
            $widgetOptions['rteFileManager'] = $htmlOptions['rteFileManager'];
            unset($htmlOptions['rteFileManager']);
        } 
        AmcWm::app()->getController()->widget('amcwm.core.widgets.tinymce.MTinyMce', $widgetOptions);
    }

    /**
     * Generates a CalendarField field input for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see activeInputField
     * @since 1.1.11
     */
    static public function activeCalendarField($model, $attribute, $htmlOptions = array()) {
        $dateOptions = array('showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'timeFormat' => 'hh:mm',
            'dateOnly' => true,
            'changeYear' => false,);
        if (isset($htmlOptions['dateOptions'])) {
            $dateOptions = array_merge($dateOptions, $htmlOptions['dateOptions']);
            unset($htmlOptions['dateOptions']);
        }
        if ($dateOptions['dateOnly']) {
            $dateOptions['showHour'] = false;
            $dateOptions['showMinute'] = false;
            $dateOptions['showTime'] = false;
        }
        unset($dateOptions['dateOnly']);
        if (isset($dateOptions['defaultDate'])) {
            $defaultDate = $dateOptions['defaultDate'];
            unset($dateOptions['defaultDate']);
        } else {
            $defaultDate = date("Y-m-d H:i");
        }

        if (!isset($htmlOptions['readonly'])) {
            $htmlOptions['readonly'] = "readonly";
        }
        if (!isset($htmlOptions['style'])) {
            $htmlOptions['style'] = "direction:ltr";
        } else {
            $htmlOptions['style'] .= "direction:ltr;" . trim($htmlOptions['style'], ";") . ";";
        }
        if (!isset($htmlOptions['value'])) {
            $htmlOptions['value'] = ($model->$attribute) ? date("Y-m-d H:i", strtotime($model->$attribute)) : $defaultDate;
        }
        $widgetOptions = array(
            'model' => $model,
            'attribute' => $attribute,
            'options' => $dateOptions,
            'htmlOptions' => $htmlOptions,
        );
        if (isset($htmlOptions['id'])) {
            $widgetOptions["id"] = $htmlOptions['id'];
        }
        AmcWm::app()->getController()->widget('amcwm.core.widgets.timepicker.EJuiDateTimePicker', $widgetOptions);
    }

    /**
     * 
     * Draw extendable fields
     * @param ActiveRecord $model the data model
     * @param string $attribute the attribute
     * @param string $fieldType
     * @param array $fieldOptions field options like data .. htmlOptions
     */
    static public function activeExtendableField($model, $attribute, $fieldType = 'textField', $fieldOptions = array()) {
//        echo CHtml::activeTextField($model, $attribute, array('size' => 60, 'maxlength' => 100));
        $htmlOptions = array();
        if (isset($fieldOptions['htmlOptions'])) {
            $htmlOptions = $fieldOptions['htmlOptions'];
            unset($fieldOptions['htmlOptions']);
        }
        if ($model->hasAttribute($attribute)) {
            $extendAttribute = $model->getExtendedAttribute($attribute);
            $widgetOptions = array(
                'model' => $model,
                'attribute' => $attribute,
                'extendAttribute' => $extendAttribute,
                'fieldOptions' => $fieldOptions,
                'fieldType' => $fieldType,
                'htmlOptions' => $htmlOptions,
            );
            if (isset($htmlOptions['id'])) {
                $widgetOptions["id"] = $htmlOptions['id'];
                unset($htmlOptions['id']);
            }
            AmcWm::app()->getController()->widget('amcwm.core.widgets.extendableField.ExtendableField', $widgetOptions);
        }
    }

}
