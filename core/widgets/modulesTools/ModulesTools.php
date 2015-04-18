<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */
/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

/**
 * ModulesTools extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ModulesTools extends CWidget {

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
        foreach ($this->items as $itemKey => $item) {
            if ($this->encodeLabel) {
                $this->items[$itemKey]['label'] = $item['label'];
            }
        }

        //$route = $this->getController()->getRoute();
    }

    /**
     * Calls {@link renderItem} to render the menu.
     */
    public function run() {
        $assetsFolder = "";
        if ($this->baseScriptUrl === null) {
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.core.widgets.modulesTools.assets'));
            $this->baseScriptUrl = $assetsFolder . "/modulestools";
        }
        $cs = Yii::app()->getClientScript();
        $this->getController();
        $output = '';
        foreach ($this->items as $item) {
            if ($item['visible']) {
                $output.=$this->renderItem($item);
            }
        }
        echo $output;
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the tool item to be rendered	 
     */
    protected function renderItem($item) {
        $customImage = AmcWm::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR . "{$item['image_id']}.png";
        if (file_exists($customImage)) {
            $image = CHtml::image(AmcWm::app()->baseUrl . "/images/modules/{$item['image_id']}.png", "", array('border' => 0));
        } else {
            $image = CHtml::image($this->baseScriptUrl . "/images/{$item['image_id']}.png", '', array('border' => 0));
        }
        $itemHtml = '<div class="module_item" id="' . $item['id'] . '">';
        $itemHtml.='<div class="module_icon">' . Html::link($image, $item['url']) . '</div>';
        $itemHtml.='<div class="module_text">' . Html::link($item['label'], $item['url']) . '</div>';
        $itemHtml.="</div>";
        return $itemHtml;
    }

}
