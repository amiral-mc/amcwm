<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * SectionSistersRelatedData ,  get sisters data related to the section
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class SectionSistersRelatedData extends SistersRelatedData {

   /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1){
        $parentSection = Yii::app()->db->createCommand(sprintf('select parent_section from sections where section_id=%d', $this->id))->queryScalar();        
        $this->parentTitle = Yii::app()->db->createCommand(sprintf("select section_name from sections_translation where section_id=%d and content_lang=%s", $parentSection, Yii::app()->db->quoteValue(Yii::app()->getLanguage()))
                )->queryScalar();
        
        if($parentSection){
            $dataset = new SectionsData('articles', $parentSection);
            $paging = new PagingDataset($dataset, $this->pageSize, $page);
            $this->items = $paging->getData();
        }
        if ($this->items['pager']['count']) {
            $this->success = true;
        }
        else{
            $sister = new MenuSistersRelatedData(Yii::app()->request->getParam("menu"));
            $this->items = $sister->getItems();
            $this->parentTitle = $sister->getParentTitle();
            $this->success = $sister->hasItems();
        }
    }

}

