<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * FileManagerWidget extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class FileManagerWidget extends Widget {

    /**
     * @var string the opener component "attach" or "rte"
     */
    public $openerType = "attach";
    /**
     * @var string default widget parent dialog id
     */
    public $dialog = null; 
    /**
     *
     * @var string default file component name 
     */
    public $default = "uploadsFiles";

    /**
     * @var array attachment container html attributes
     */
    public $htmlOptions = array();

    /**
     * @var array attachment informations array
     */
    public $attachmentInfo;

    /**
     *
     * @var base url for the widget
     */
    protected $baseScriptUrl = null;

    /**
     * Initializes the menu widget.
     * This method mainly normalizes the {@link items} property.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        if (!isset($this->htmlOptions['class'])) {
            $this->htmlOptions['class'] = "filemanager";
        }
        $this->getController()->pageTitle = AmcWm::t("{$this->basePath}.core", "_file_manager_") ;
        parent::init();
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $this->baseScriptUrl = Yii::app()->getAssetManager()->publish($this->basePath . DIRECTORY_SEPARATOR . 'assets', true, -1, $this->forcePublish);
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery.ui');
        $cs->registerCssFile($this->baseScriptUrl . '/fileManager.css');
        $cs->registerScriptFile($this->baseScriptUrl . '/jquery.fileManager.js');
        $output = CHtml::openTag('div', $this->htmlOptions);
        $output .= '<div class="panel">';        
        $output .= '<div class="form">';        
        $output .= '<div class="row">';
        $output .= CHtml::dropDownList("component", $this->default, $this->attachmentInfo['list'], array("empty" => AmcWm::t("{$this->basePath}.core", "_select_component_"), 'id' => "componentChanger",));
        $output .= '</div>';
        $output .= '<div class="row file-preview">';
        $output .= '<div>';        
        $output .= '</div>';        
        $output .= '</div>';        
        $output .= '<div class="row">';
        $output .= CHtml::button(AmcWm::t("{$this->basePath}.core", "_insert_file_"), array("name"=>"{$this->htmlOptions['id']}_insert",  "id"=>"{$this->htmlOptions['id']}_insert"));        
        $output .= "&nbsp;";
        $output .= CHtml::button(AmcWm::t("{$this->basePath}.core", "_close_file_manager_"), array("name"=>"{$this->htmlOptions['id']}_close",  "id"=>"{$this->htmlOptions['id']}_close"));        
        $output .= '</div>';                                
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="manage-area" id="' . $this->htmlOptions['id'] . '_area">';
        $output .= '</div>';
        $output .= '<div style="clear:both;"></div>';
        $output .=CHtml::closeTag("div");
        //print_r($this->attachmentInfo['data']['actions']);
//        echo CJSON::encode($this->attachmentInfo['data']['actions']);
//        die();
        $options['mediaTypes']["image"] = AttachmentList::IMAGE;
        $options['mediaTypes']["externalVideo"] = AttachmentList::EXTERNAL_VIDEO;
        $options['mediaTypes']["internaVideo"] = AttachmentList::INTERNAL_VIDEO;
        $options['mediaTypes']["link"] = AttachmentList::LINK;
        $options['openerType'] = $this->openerType;
        $options['dialog'] = $this->dialog;
        $options['componentChanger'] = 'componentChanger';
        $options['attachmentActions'] = $this->attachmentInfo['actions'];
        $options['page'] = AmcWm::app()->request->getParam("page");        
        $options['messages']['deleteMsg'] = AmcWm::t($this->messageFile, "_delete_message_");
//        $jsCode = "
//            $('#componentChanger').change(function(){
//            var options = " . CJavaScript::encode($options) .";
//            options.defaultComponent =  $(this).attr('value');
//            $('#{$this->htmlOptions['id']}_area').fileManager(options);     
//        });";
        echo $output;
        if ($this->default) {
            $options['defaultComponent'] = $this->default;            
        }
        
        
        $options['baseUrl'] = $this->baseScriptUrl;
        $jsCode = "$('#{$this->htmlOptions['id']}').fileManager(" . CJavaScript::encode($options) . ");";
        $cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
    }

}