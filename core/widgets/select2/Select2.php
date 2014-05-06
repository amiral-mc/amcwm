<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Select2 extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */

class Select2 extends CWidget {

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * isMultiple check if its multiple select element or not, default is normal
     * @var boolean
     */
    public $multiple = false;
    /**
     * the default select value
     */
    public $defaultValue = array();

    /**
     * news class name
     * @var string 
     */
    public $className = 'mtsSelect2';

    /**
     * select element name
     * @var string 
     */
    public $elementName = 'mtsSelect2';
    public $ajaxUrl = null;
    public $showRelatedOptions = false;
    public $sectionsListId = 'sections';
    public $sectionsChkRelated = 'relatedToSection';

    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    private $baseScriptUrl;

    /**
     * @var Object the client script object
     */
    private $cs = null;

    /**
     * Initializes the player widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $this->htmlOptions['class'] = $this->className;

        if ($this->baseScriptUrl === null) {
            $this->baseScriptUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.core.widgets.select2.assets'));
        }

        $this->cs = Yii::app()->getClientScript();
        $this->cs->registerCssFile($this->baseScriptUrl . '/select2.css', 'screen');
//        $cs->registerCoreScript('jquery');
        $this->cs->registerScriptFile($this->baseScriptUrl . '/select2.min.js');
    }

    /**
     * Calls {@link renderItem} to render the select html
     */
    public function run() {
//        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $jsCode = '
            $("#' . $this->htmlOptions['id'] . '").select2({
                placeholder: "' . Yii::t('Select2.select2', 'Enter Search Keywords') . '",
                formatInputTooShort: function (input, min) { return "' . Yii::t('Select2.select2', 'Please enter {chars} more characters', array('{chars}' => "3")) . '"},
                formatSearching: function () { return "' . Yii::t('Select2.select2', 'Searching...') . '"},
                formatNoMatches: function () { return "' . Yii::t('Select2.select2', 'No matches found') . '"},
                minimumInputLength: 3,
                initSelection : function (element, callback) {
                    var data = [];
                    $(element.val().split(",")).each(function () {
                        data.push({id: this, text: this});
                    });
                    callback(data);
                },
                '.($this->multiple?'multiple: true,':'').'
                ajax: {
                    url: "' . $this->ajaxUrl . '",
                    dataType: "json",
                    data: function (term, page) {
                        return {
                            q: term
                            ' . (($this->showRelatedOptions) ? '
                            ,sId: $("#' . $this->sectionsListId . ' :selected").val()
                            ,showRelated: $("#' . $this->sectionsChkRelated . '").is(\':checked\')
                            ' : '') . '
                        };
                    },
                    results: function (data, page) { // parse the results into the format expected by Select2.
                        // since we are using custom formatting functions we do not need to alter remote JSON data
                        return {results: data.records};
                    }
                },
                dropdownCssClass: "bigdrop" // apply css that makes the dropdown taller
            });
        ';

//        if (isset($this->defaultValue['id']) && $this->defaultValue['id'] != '') {
        if (is_array($this->defaultValue) && count($this->defaultValue)) {
            $data = json_encode($this->defaultValue);
            $jsCode .= '
               $("#' . $this->htmlOptions['id'] . '").select2(\'data\', ' . $data . '); 
            ';
        }

        $this->cs->registerScript(__CLASS__ . $this->getId(), $jsCode, CClientScript::POS_READY);
        echo '<input value="" name="' . $this->elementName . '" type="hidden" class="bigdrop" id="' . $this->htmlOptions['id'] . '" style="width:500px"/>';
    }

}
