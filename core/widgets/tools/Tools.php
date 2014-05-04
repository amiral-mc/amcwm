<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Tools extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Tools extends CWidget {

    /**
     * @var array list of tools items. Each menu item is specified as an array of name-value pairs.
     * Possible option names include the following:
     * <ul>
     * <li>label: string, specifies the menu item label. When {@link encodeLabel} is true, the label
     * will be HTML-encoded. If the label is not specified, it defaults to an empty string.</li>
     * <li>url: string or array, specifies the URL of the tool item.
     * <li>image_id: string specifies the image id of the tool item.
     * </ul>
     */
    public $items = array();

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * @var boolean whether the labels for menu items should be HTML-encoded. Defaults to true.
     */
    public $encodeLabel = true;

    /**
     * @var string the base script URL for all grid view resources (e.g. javascript, CSS file, images).
     * Defaults to null, meaning using the integrated grid view resources (which are published as assets).
     */
    public $baseScriptUrl;

    /**
     * Initializes the menu widget.
     * This method mainly normalizes the {@link items} property.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $this->htmlOptions['id'] = $this->getId();
        $items = array();
        foreach ($this->items as $itemKey => $item) {
            $visible = array_key_exists("visible", $this->items[$itemKey]) ? $this->items[$itemKey]['visible'] : true;
            if ($visible) {
                $items[$itemKey] = $item;
                if (!isset($items[$itemKey]['label']))
                    $items[$itemKey]['label'] = '';
                if ($this->encodeLabel)
                    $items[$itemKey]['label'] = CHtml::encode($item['label']);
            }
        }
        $this->items = $items;
        //$route = $this->getController()->getRoute();
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.core.widgets.tools.assets'));
            $this->baseScriptUrl = $assetsFolder . "/tools";
        }
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($this->baseScriptUrl . '/js/FormActions.js');
        $jsCode = "FormActions.urlFormat = '" . Yii::app()->getUrlManager()->getUrlFormat() . "'" . PHP_EOL;
        $cs->registerScript("Tools", $jsCode, CClientScript::POS_READY);
        $output = '<div class="action_area">';
        foreach ($this->items as $item) {
            $output.=$this->renderItem($item);
            $item['js']['many'] = true;
            if (isset($item['js']['many']) && isset($item['js']['formId'])) {
                $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
                    'id' => 'dialogAreYouSure' . $item['id'],
                    'options' => array(
                        'title' => '',
                        'autoOpen' => false,
                        'modal' => true,
                        'buttons' => array(
                            AmcWm::t("amcBack", 'Yes') => 'js:function(){FormActions.submitAction("' . $item['js']['formId'] . '", "' . $item['url'] . '", ' . CJSON::encode(array()) . ');}',
                            AmcWm::t("amcBack", 'No') => 'js:function(){ $(this).dialog("close");}',
                        ),
                    ),
                    'htmlOptions' => array('class' => 'dialogBoxs',)
                ));
                echo AmcWm::t("amcBack", 'are_you_sure');
                $this->endWidget('zii.widgets.jui.CJuiDialog');
            }
        }
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => 'dialogSelectOne',
            'options' => array(
                'title' => '',
                'autoOpen' => false,
                'modal' => true,
                'buttons' => array(
                    AmcWm::t("amcBack", 'Close') => 'js:function(){ $(this).dialog("close");}',
                ),
            ),
        ));
        echo AmcWm::t("amcBack", 'select_one_record');
        $this->endWidget('zii.widgets.jui.CJuiDialog');
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => 'dialogSelectMany',
            'options' => array(
                'title' => '',
                'autoOpen' => false,
                'modal' => true,
                'buttons' => array(
                    AmcWm::t("amcBack", 'Close') => 'js:function(){ $(this).dialog("close");}',
                ),
            ),
            'htmlOptions' => array('class' => 'dialogBoxs',)
        ));
        echo AmcWm::t("amcBack", 'select_at_least_one_record');
        $this->endWidget('zii.widgets.jui.CJuiDialog');
        $output.="</div>";
        echo $output;
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the tool item to be rendered	 
     */
    protected function renderItem(&$item) {
        $moduleParam = Yii::app()->request->getParam('module');
        if(isset($item['icon'])){
            $image = CHtml::image("{$item['icon']}", '', array('border' => 0));
        }
        else{
            $image = CHtml::image($this->baseScriptUrl . "/images/{$item['image_id']}.png", '', array('border' => 0));
        }
        $jsAction = null;
        $link = null;
        $options = array();
        $routeRoot = "/";
        $route = null;
        if ($this->getController()->getModule()) {
            $routeRoot .= $this->getController()->getModule()->getId();
        }
        $routeRoot .="/" . $this->getController()->getId();
        $action = $this->getController()->getAction()->getId();
        $customJsAction = (isset($item['js']['action'])) ? $item['js']['action'] : null;
        $jsParam = (isset($item['js']['params']) && is_array($item['js']['params'])) ? $item['js']['params'] : array();
        $jsCalling = array();
        if (isset($item['url'])) {
            $link = $item['url'];
            $link['module'] = $moduleParam;
            $route = $link[0];
            if(isset($item['target'])){
                $options = array("target" => $item['target']);
            }
        } else if (isset($item['js'])) {
            if (!array_key_exists('refId', $item['js'])) {
                $item['js']['refId'] = 'id';
            }
            $actionName = isset($item['action']) ? $item['action'] : $item['image_id'];
            switch ($actionName) {
                case 'save':
                    $jsCalling['method'] = "FormActions.submit";
                    $jsCalling['params'][0] = "'{$item['js']['formId']}'";
                    if (count($jsParam)) {
                        $jsCalling['params'][1] = CJSON::encode($jsParam);
                    }
                    break;
                case 'publish':
                    $action = "publish";
                    $jsParam["published"] = ActiveRecord::PUBLISHED;
                    $item['js']['many'] = true;
                    $jsCalling['method'] = "FormActions.manageMany";
                    $jsCalling['params'][0] = "'{$item['id']}'";
                    break;
                case 'unpublish':
                    $action = "publish";
                    $jsParam["published"] = 0;
                    $item['js']['many'] = true;
                    $jsCalling['method'] = "FormActions.manageMany";
                    $jsCalling['params'][0] = "'{$item['id']}'";
                    break;
                case 'delete':
                    $action = "delete";
                    $item['js']['many'] = true;
                    $jsCalling['method'] = "FormActions.manageMany";
                    $jsCalling['params'][0] = "'{$item['id']}'";
                    break;
                case 'edit':
                    $action = "update";
                    $jsCalling['method'] = "FormActions.manageOne";
                    $jsCalling['params'][0] = "'{url}'";
                    $jsCalling['params'][1] = "'{$item['js']['refId']}'";
                    break;
                case 'translate':
                    $action = "translate";
                    $jsCalling['method'] = "FormActions.manageOne";
                    $jsCalling['params'][0] = "'{url}'";
                    $jsCalling['params'][1] = "'{$item['js']['refId']}'";
                    break;
                case 'search':
                    $jsCalling['method'] = "FormActions.search";
                    $jsCalling['params'][0] = "'{$item['js']['refId']}'";
                    break;
                default:
                    $action = $actionName;

                    if (isset($item['js']['many']) && $item['js']['many']) {
                        $jsCalling['method'] = "FormActions.manageMany";
                        $jsCalling['params'][0] = "'{$item['id']}'";
                    } else if (isset($item['js']['method'])) {
                        $jsCalling['method'] = $item['js']['method'];
                        $jsCalling['params'][0] = "'{$item['js']['formId']}'";
                        if ($item['js']['method'] == "submitAction") {
                            $jsCalling['method'] = "FormActions.submitAction";
                            $jsCalling['params'][1] = "'{url}'";
                        }
                        foreach ($item['js']['params'] as $paramId => $paramValue) {
                            $jsCalling['params'][$paramId] = "'{$paramValue}'";
                        }
                        $jsCalling['params'] = $item['js']['params'];
                    } else {
                        $jsCalling['method'] = "FormActions.manageOne";
                        $jsCalling['params'][0] = "'{url}'";
                        $jsCalling['params'][1] = "'{$item['js']['refId']}'";
                    }
                    break;
            }
            $route = ($customJsAction) ? "{$routeRoot}/{$customJsAction}" : "{$routeRoot}/{$action}";
            $jsParam['module'] = $moduleParam;
            $url = Html::createUrl($route, $jsParam);

            $jsCode = "{$jsCalling['method']}(" . implode(',', $jsCalling['params']) . ", " . CJSON::encode(array()) . ");";
            $jsCode = str_replace("{url}", $url, $jsCode);
//            echo "<hr>$jsCode<hr>";
            $options = array("onclick" => $jsCode . ";");
            $item['js']['params'] = $jsParam;
            $item['url'] = $url;


            $link = "javascript:void(0);//";
        } else if (isset($item['customJs'])) {
            $link = 'javascript:void(0);//';
            $options = array("onclick" => $item['customJs']);
        }
        $forward = $this->getController()->getForwardModule();
        $module = $this->getController()->getModule();
        if (isset($forward[0]) && $module) {
            $route = str_replace($module->getId(), $forward[0], $route);
        }
        $itemHtml = "";
        $langsCount = count(AmcWm::app()->params->languages);
        $tranlate = strrpos($route, "/translate");
        $routeCheck = $route;
        if ($tranlate) {
            $routeCheck = str_replace("translate", "update", $routeCheck);
        }
        if (Yii::app()->user->checkRouteAccess(trim($routeCheck, "/")) || isset($item['customJs'])) {
            if (!$tranlate || ($tranlate && $langsCount > 1)) {
                $itemHtml = '<div class="btn_area" id="' . $item['id'] . '">';
                $itemHtml.='<div>' . Html::link($image, $link, $options) . '</div>';
                $itemHtml.='<div class="btn_label">' . Html::link($item['label'], $link, $options) . '</div>';
                $itemHtml.="</div>";
            }
        }
        return $itemHtml;
    }

}