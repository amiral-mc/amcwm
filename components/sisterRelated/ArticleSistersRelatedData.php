<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticleSistersRelatedData, get sisters data related to the article
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticleSistersRelatedData extends SistersRelatedData {

    /**
     * Generate the array the contain the sisters data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1) {
        $this->parentTitle = Yii::app()->db->createCommand(sprintf("select section_name from sections_translation where section_id=%d and content_lang=%s", $this->id, Yii::app()->db->quoteValue(Yii::app()->getLanguage()))
                )->queryScalar();
        $dataset = new ArticlesListData(array("articles"), 0, $this->pageSize, $this->id);
        $dataset->checkActive();
        if (ArticlesListData::getSettings()->currentVirtual != "news") {
            $dataset->addColumn("article_pri_header", "pri_title");
            $paging = new PagingDataset($dataset, $this->pageSize, $page);
            $this->items = $paging->getData();
            if ($this->items['pager']['count']) {
                $this->success = true;
            }
        }
    }

}

