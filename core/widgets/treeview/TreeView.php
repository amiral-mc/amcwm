<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TreeView extends CTreeView {

    public $dir = "ltr";

    /**
     * Initializes the widget.
     * This method registers all needed client scripts and renders
     * the tree view content.
     */
    public function init() {
        if($this->dir == 'rtl'){
            $assetsFolder = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('amcwm.core.widgets.treeview.assets'));            
            $this->cssFile = $assetsFolder . "/treeview/css/jquery.treeview.rtl.css";
        }
        parent::init();
    }

}

?>
