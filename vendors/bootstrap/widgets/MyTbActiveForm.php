<?php

/**
 * TbActiveForm class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package bootstrap.widgets
 */
Yii::import('bootstrap.widgets.TbActiveForm');

/**
 * Bootstrap active form widget.
 */
class MyTbActiveForm extends TbActiveForm {

    /**
     * Creates an input row of a specific widget.
     * @param string $widget the widget
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes
     * @param array $widgetOptions additional widget aoptions like javascript events
     * @return string the generated row
     */
    public function inputWidget($widget, $model, $attribute, $htmlOptions = array(), $widgetOptions = array()) {
        ob_start();
        $widget = "bootstrap.widgets.Tb" . ucfirst($widget);
        $this->getOwner()->widget("bootstrap.widgets.input.MyTbWidgetInput", array(
            'form' => $this,
            'widget'=>$widget,
            'model' => $model,
            'attribute' => $attribute,
            'htmlOptions' => $htmlOptions,
            'widgetOptions'=>$widgetOptions,            
        ));
        return ob_get_clean();
    }

}
