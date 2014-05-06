<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ExecuteWidget, get data needed for a widget then render it
 * @package AmcWebManagment
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class ExecuteWidget extends CComponent {

    /**
     * Check if the widget is prepared or not
     * @var boolean 
     */
    private $_prepared;

    /**
     * @var array widget class information
     * Possible list names include the following:
     * <ul>
     * <li>name: widget name
     * <li>propertie: array list of initial property values for the widget (Property Name => Property Value)
     * <li>capture: boolean whether to capture the output of the widget. If true, the method will capture
     * </ul>
     */
    private $_widgetInfo = array('name' => null, 'properties' => array(), 'capture' => false);

    /**
     * Counstructor
     * @param $string $widgetName widget class name
     * @param array $properties list of initial property values for the widget (Property Name => Property Value)
     * @param boolean $captureOutput whether to capture the output of the widget. If true, the method will capture
     * @access private
     */
    public function __construct($widgetName, $properties = array(), $captureOutput = false) {
        $this->_widgetInfo['name'] = $widgetName;
        $this->_widgetInfo['properties'] = $properties;
        $this->_widgetInfo['capture'] = $captureOutput;        
    }

    /**
     * Factory method method used ceate the instance and render the result in one step
     * @static
     * @param $string $widgetName widget class name
     * @param array $managerProperties list of initial property values for the widget manager (Property Name => Property Value)
     * @param array $properties list of initial property values for the widget (Property Name => Property Value)
     * @param boolean $captureOutput whether to capture the output of the widget. If true, the method will capture
     * @access public
     * @return string the rendering result. Null if the rendering result is not required.
     */
    static public function execute($widgetName, $managerProperties = array(), $properties = array(), $captureOutput = false) {
        $className = get_called_class();
        if ($widgetName == null && isset($managerProperties['widget'])) {
            $widgetName = $managerProperties['widget'];
            unset($managerProperties['widget']);
        }
        $widget = new $className($widgetName, $properties, $captureOutput);
        foreach ($managerProperties as $name => $value) {
            $widget->$name = $value;
        }
        $widget->prepareProperties();
        return $widget->_render();
    }

    /**
     * Renders the widget. must to preoare widget properties befrore render it     
     * @return string the rendering result. Null if the rendering result is not required.
     */
    private function _render() {
        $output = null;
        if ($this->_widgetInfo['name'] && $this->_prepared) {            
            $output = AmcWm::app()->getController()->widget($this->_widgetInfo['name'], $this->_widgetInfo['properties'], $this->_widgetInfo['capture']);
        }
        return $output;
    }

    /**
     * prepare widget properties
     */
    abstract protected function prepareProperties();
        
    /**
     * Set widget name
     * @param string $widgetName widget class name
     * @access public
     */
    public function setWidgetName($widgetName) {
        $this->_widgetInfo['name'] = $widgetName;
    }
    
    /**
     * get widget name
     * @return string  widget class name
     */
    public function getWidgetName() {
        return $this->_widgetInfo['name'];
    }

    /**
     * Set widget property
     * @param string $property property mame
     * @param mixed $value property value
     * @access public
     */
    public function setProperty($property, $value) {
        $this->_prepared = true;
        $this->_widgetInfo['properties'][$property] = $value;
    }

    /**
     * get widget property
     * @param string $property property mame
     * @access public
     * @return mixed
     */
    public function getProperty($property) {
        $value = null;
        if (array_key_exists($property, $this->_widgetInfo['properties'])) {
            $value = $this->_widgetInfo['properties'][$property];
        }
        return $value;
    }

    /**
     * Run the widget
     * @access public
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function executeWidget() {
        $this->prepareProperties();
        return $this->_render();
    }

}