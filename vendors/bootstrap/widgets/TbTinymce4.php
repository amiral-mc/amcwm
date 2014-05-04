<?php

/**
 * ## TbTinymce class file.
 * 
 * @package booster.widgets.forms.inputs.wysiwyg
 */
class TbTinymce4 extends CInputWidget {

    /**
     * @var TbActiveForm when created via TbActiveForm
     * This attribute is set to the form that renders the widget
     * @see TbActionForm->inputRow
     */
    public $form;

    /**
     * full featured editor or basic
     * @var boolean 
     */
    public $fullfeatured = false;

    /**
     * @var array the Tinymce options
     * @see <http://docs.cksource.com/>
     */
    public $options = array();

    /**
     * ### .init()
     *
     * Initializes the widget.
     */
    public function init() {
        if (!isset($this->options['directionality'])) {
            $this->options['directionality'] = Yii::app()->bootstrap->getOrientation();
        }
        if (!isset($this->options['language'])) {
            $this->options['language'] = Yii::app()->getLanguage();
        }
        if (!isset($this->options['theme'])) {
            $this->options['theme'] = "modern";
        }
        if (!isset($this->options['plugins'])) {
            if ($this->fullfeatured) {
                $this->options['plugins'][] = 'advlist autolink lists link image charmap print preview hr anchor pagebreak';
                $this->options['plugins'][] = 'searchreplace wordcount visualblocks visualchars code fullscreen';
                $this->options['plugins'][] = 'insertdatetime media nonbreaking save table contextmenu directionality';
                if (isset($this->options['templates'])) {
                    $this->options['plugins'][] = 'emoticons template paste textcolor';
                } else {
                    $this->options['plugins'][] = 'emoticons paste textcolor';
                }
            } else {
                $this->options['plugins'][] = 'advlist autolink lists link image charmap print preview anchor';
                $this->options['plugins'][] = 'searchreplace visualblocks code fullscreen';
                $this->options['plugins'][] = 'insertdatetime media table contextmenu paste';
            }
        }
        if (!$this->fullfeatured) {
            if (!isset($this->options['menubar'])) {
                $this->options['menubar'] = "edit insert view format table tools";
            }
            if (!isset($this->options['statusbar'])) {
                $this->options['statusbar'] = false;
            }
        }

        if (!isset($this->options['toolbars'])) {
            if ($this->fullfeatured) {
                $this->options['toolbar1'] = "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";
                $this->options['toolbar2'] = "print preview media | forecolor backcolor emoticons";
            } else {
                $this->options['toolbar'] = "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image";
            }
        } else {
            $toolbars = $this->options['toolbars'];
            unset($this->options['toolbars']);
            if (count($toolbars) == 1) {
                $this->options["toolbar"] = $toolbars[0];
            } else {
                $toolIndex = 1;
                foreach ($toolbars as $toolbar) {
                    $this->options["toolbar{$toolIndex}"] = $toolbar;
                    $toolIndex++;
                }
            }
        }
    }

    /**
     * ### .run()
     *
     * Display editor
     */
    public function run() {
        list($name, $id) = $this->resolveNameID();
        $this->htmlOptions['id'] = $id;
        $this->options['selector'] = "#{$id}";
        $this->registerClientScript($id);
        // Do we have a model?
        if ($this->hasModel()) {
            if ($this->form) {
                $html = $this->form->textArea($this->model, $this->attribute, $this->htmlOptions);
            } else {
                $html = CHtml::activeTextArea($this->model, $this->attribute, $this->htmlOptions);
            }
        } else {
            $html = CHtml::textArea($name, $this->value, $this->htmlOptions);
        }
        echo $html;
    }

    /**
     * ### .registerClientScript()
     *
     * Registers required javascript
     *
     * @param string $id
     */
    public function registerClientScript($id) {
        Yii::app()->bootstrap->assetsRegistry->registerPackage('tinymce4');
        $options = !empty($this->options) ? CJavaScript::encode($this->options) : '{}';
        Yii::app()->clientScript->registerScript(
                __CLASS__ . '#' . $this->getId(), "tinymce.init($options);"
        );
    }

}
?>