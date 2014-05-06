<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesViewTask class, run the section task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesSectionsParamTask extends ParamsTask {

    /**
     * Generate the array the contain the data 
     * @param integer $page, current page
     * @access public
     * @return void     
     */
    public function generate($page = 1) {
        $dataset = new SectionsData("articles");
        $dataset->setLanguage(Controller::getContentLanguage());
        $dataset->getTopParentOnly(false);
        $dataset->useRecordIdAsKey(false);
        $dataset->addWhere("section_name like " . Yii::app()->db->quoteValue('%%' . $this->searchFor . '%%'));
        if ($this->selectedRow)
            $dataset->addOrder('FIELD(t.section_id, ' . $this->selectedRow . ') DESC, t.section_id');
        $dataset->addColumn('(select count(*) from sections s1 where s1.parent_section = t.section_id)', "subsCount");
        $dataset->addColumn('(select count(*) from articles a where a.section_id = t.section_id)', "articesCount");
        $paging = new PagingDataset($dataset, $this->pageSize, $page);
        $this->setDataProvider($paging);
        $this->success = true;
    }

    public function setGridColumns($cols) {
        parent::setGridColumns($cols);
        $this->gridCols[] = array(
            'value' => '$data["subsCount"]',
            'header' => AmcWm::t("amcBack", 'Sub sections'),
            'htmlOptions' => array('width' => '80', 'align' => 'center')
        );

        $this->gridCols[] = array(
            'value' => '$data["articesCount"]',
            'header' => AmcWm::t("amcBack", 'Sub Articles'),
            'htmlOptions' => array('width' => '80', 'align' => 'center')
        );
    }

    /**
     * Get the item title
     * @access public
     * @return string
     */
    public function getTitle() {
        $itemId = $this->searchFor;
        $query = sprintf("select section_name from sections_translation 
                 where section_id = %d and content_lang = %s
                ", $itemId, Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return Yii::app()->db->createCommand($query)->queryScalar();
    }

    public function validate($paramData) {
        $valid = false;
        foreach ($this->params as $param) {
            if (isset($paramData[$param['param_id']]) && $paramData[$param['param_id']] != null) {
//                $paramType = MenuItems::getParamType($paramData[$param['param_id']]);
                $valid = true;
            }
        }
//        die(print_r($paramData));
        return $valid;
    }

}

