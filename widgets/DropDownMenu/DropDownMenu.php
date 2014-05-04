<?php

/**
 * CDropDownMenu class file.
 *
 * @author Herbert Maschke <thyseus@gmail.com>
 * @link http://www.yiiframework.com/
 */
/**
 * CDropDownMenu is an extension to CMenu that supports Drop-Down Menus using the
 * superfish jquery-plugin.
 *
 * Please be sure to also read the CMenu API Documentation to understand how this 
 * menu works.
 *
 */
Yii::import('zii.widgets.CMenu');

class DropDownMenu extends CMenu {

    public $style = 'default'; // can be changed to vertical or navbar
    public $cssFile = 'superfish.css';
    public $position = CClientScript::POS_HEAD;

    public function init() {
        parent::init();
    }

    /**
     * Calls {@link renderMenu} to render the menu.
     */
    public function run() {
        $this->renderDropDownMenu($this->items);
    }

    protected function cssClass() {
        $class = 'sf-menu';
        if ($this->style == 'vertical')
            $class .= ' sf-vertical';
        if ($this->style == 'navbar')
            $class .= ' sf-navbar';
        return $class;
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please see {@link items} on what data might be in the item.
     * @since 1.1.6
     */
    protected function renderMenuItem($item) {
        if (isset($item['url'])) {
            $label = $this->linkLabelWrapper === null ? $item['label'] : '<' . $this->linkLabelWrapper . '>' . $item['label'] . '</' . $this->linkLabelWrapper . '>';
            return Html::link($label, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
        }
        else
            return CHtml::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
    }

    protected function renderDropDownMenu($items) {
        if (isset($this->htmlOptions['class']))
            $this->htmlOptions['class'] .= ' ' . $this->cssClass();
        else
            $this->htmlOptions['class'] = $this->cssClass();

        $this->renderMenu($items);

        $this->registerClientScript();
        echo '<div style="clear:both;"></div>';
    }

    protected function registerClientScript() {
        $baseUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.widgets.DropDownMenu.assets'));
        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerCssFile($baseUrl . '/css/' . $this->cssFile);
        if ($this->style == 'navbar')
            $cs->registerCssFile($baseUrl . '/css/' . 'superfish-navbar.css');
        if ($this->style == 'vertical')
            $cs->registerCssFile($baseUrl . '/css/' . 'superfish-vertical.css');
        $cs->registerScriptFile($baseUrl . '/js/' . 'superfish.js', $this->position);
        $cs->registerScriptFile($baseUrl . '/js/' . 'hoverIntent.js', $this->position);
        $cs->registerScriptFile($baseUrl . '/js/' . 'CDropDownMenu.js', $this->position);
    }

}
