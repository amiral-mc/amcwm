<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectorySistersRelatedData, get sisters data related to the directory
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectorySistersRelatedData extends SistersRelatedData {

   /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1){
//        $this->id
        $countryCode = Yii::app()->db->createCommand(sprintf('select nationality from dir_companies where company_id=%d', $this->id))->queryScalar();        
//        $this->parentTitle = Yii::app()->db->createCommand(sprintf("select section_name from sections_translation where section_id=%d and content_lang=%s", $parentSection, Yii::app()->db->quoteValue(Yii::app()->getLanguage()))
//                )->queryScalar();
        
        if($countryCode){
            $dataset = new DirectoryListData();
            $dataset->setRoute('/directory/default/view');
            $dataset->checkActive(true);
            $dataset->addColumn('company_name', 'title');
            $dataset->addWhere(sprintf('nationality = %s', AmcWm::app()->db->quoteValue($countryCode)));
            $paging = new PagingDataset($dataset, $this->pageSize, $page);
            $this->items = $paging->getData();
        }
        
        if ($this->items['pager']['count']) {
            $this->success = true;
        }
    }

}

