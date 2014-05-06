<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * BoardMembersData, gets the board members data
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectorySlider extends ExecuteWidget {
  
    /*
     * Link to company view page
     */
    public $route = "/directory/default/view";
    /**
     * prepare widget properties
     */
    protected function prepareProperties() {
        $list = new DirectoryListData(100);
        $list->addWhere("t.in_ticker = 1");
        $list->setRoute($this->route);
        $list->addColumn('company_name', 'title');
        $list->generate();
        $this->setProperty('items', $list->getItems());
    }

}