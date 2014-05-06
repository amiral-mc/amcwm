<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MenuSistersRelatedData, get sisters data related to the Menu
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MenuSistersRelatedData extends SistersRelatedData {

    /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1) {
        $items = Menus::getMenuItemSisters($this->id, true);
        if ($items['items']) {
            $this->items['records'] = array_slice($items['items'], ($page - 1 ) * $this->pageSize, $this->pageSize);
            $this->items['pager']['count'] = count($items['items']);
            $this->parentTitle = $items['label'];
            $this->success = true;
        }
    }

}

