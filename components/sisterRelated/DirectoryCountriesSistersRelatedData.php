<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryCountriesSistersRelatedData, get all countries
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectoryCountriesSistersRelatedData extends SistersRelatedData {

    /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1) {

        $dataset = new DirectoryCountriesData();
        $dataset->setRoute('/directory/default/countryList');
        $dataset->checkActive(true);
        $dataset->addColumn('country', 'title');
        $paging = new PagingDataset($dataset, $this->pageSize, $page);
        $this->items = $paging->getData();

        if ($this->items['pager']['count']) {
            $this->success = true;
        }
    }

}

