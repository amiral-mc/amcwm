<?php

Yii::import("zii.widgets.CBreadcrumbs");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Breadcrumbs extends CBreadcrumbs {

    /**
     * Renders the content of the portlet.
     */
    public function run() {
        if (empty($this->links))
            return;
        $moduleParam = Yii::app()->request->getParam('module');
        echo CHtml::openTag($this->tagName, $this->htmlOptions) . "\n";
        $links = array();
        if ($this->homeLink === null)
            $links[] = Html::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl);
        else if ($this->homeLink !== false)
            $links[] = $this->homeLink;
        foreach ($this->links as $label => $url) {
            if (is_string($label) || is_array($url)) {
                if ($moduleParam) {
                    $url['module'] = $moduleParam;
                }
                $links[] = Html::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
            }
            else
                $links[] = '<span>' . ($this->encodeLabel ? CHtml::encode($url) : $url) . '</span>';
        }
        echo implode($this->separator, $links);
        echo CHtml::closeTag($this->tagName);
    }

}

