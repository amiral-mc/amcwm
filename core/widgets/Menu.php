<?php

Yii::import("zii.widgets.CMenu");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Menu extends CMenu {

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @todo convert $url = "#" to js:void
     * @param array $item the menu item to be rendered. Please see {@link items} on what data might be in the item.    
     * @return string
     * @since 1.1.6
     */
    protected function renderMenuItem($item) {
        if (isset($item['url'])) {
            $label = $this->linkLabelWrapper === null ? $item['label'] : '<' . $this->linkLabelWrapper . '>' . $item['label'] . '</' . $this->linkLabelWrapper . '>';
            $url = "#";
            if (is_array($item['url']) && count($item['url'])) {
                $url = $item['url'];
            } else if ($item['url']) {
                $url = $item['url'];
                $linkData = parse_url($url);
                if (isset($linkData['scheme'])) {
                    $item['linkOptions']['target'] = "_blank";
                }
            }
            return Html::link($label, $url, isset($item['linkOptions']) ? $item['linkOptions'] : array());
        } else
            return CHtml::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if the currently requested URL is generated by the 'url' option
     * of the menu item. Note that the GET parameters not specified in the 'url' option will be ignored.
     * @param array $item the menu item to be checked
     * @param string $route the route of the current request
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item, $route) {
        $isActive = false;
        if (isset($item['url'][0])) {
//            $useLang = count(Yii::app()->params['languages']) || (isset(Yii::app()->params['langAsFoldder']) && Yii::app()->params['langAsFoldder']);
//            if (!isset($item['url']['lang']) && $useLang) {
//                $item['url']['lang'] = Controller::getCurrentLanguage();
//            }
            $isActive = parent::isItemActive($item, $route);
        }
        return $isActive;
    }

}

?>
