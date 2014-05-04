<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * MainTopicList, draw articles main topic list
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class TopicList extends ExecuteWidget {

    /**
     * Section id to get contents from it, null to get from all sections
     * @var integer 
     */
    public $sectionId;

    /**
     * articles limit
     * @var integer 
     */
    public $limit;

    /**
     * articles limit
     * @var integer 
     */
    public $orderBy = "create_date desc";

    /**
     * 
     * @var string 
     */
    public $module = "articles";

    /**
     * 
     * @var string 
     */
    public $mediaIndex = null;

    /**
     * first items section if equal null then remove it from the data sent to widget
     * @var string 
     */
    public $firstItemMediaIndex = null;

    /**
     * prepare widget properties
     */
    protected function prepareProperties() {
        $settings = ArticlesListData::getSettings();
        $virtuals = $settings->getVirtuals();
        $table = "articles";
        if (isset($virtuals[$this->module])) {
            $table = $virtuals[$this->module]['table'];
        }
        $list = new ArticlesListData(array($table), 0, $this->limit, $this->sectionId);
        if ($this->orderBy) {
            $list->addOrder($this->orderBy);
        }
        if ($this->mediaIndex) {
            $list->setMediaPath(Yii::app()->baseUrl . "/" . $settings->mediaPaths[$this->mediaIndex]['path'] . "/");
        }
        $list->addColumn("article_detail", "detail");
        $list->useRecordIdAsKey(false);
        $list->generate();
        $items = $list->getItems();
//        die(print_r($items));
        if ($items) {
            if ($this->firstItemMediaIndex !== null) {
                $firstItem = $items[0];
                if ($this->firstItemMediaIndex) {
                    $mediaPath = Yii::app()->baseUrl . "/" . $settings->mediaPaths[$this->firstItemMediaIndex]['path'] . "/";                    
                    if ($firstItem["imageExt"]) {
                        $firstItem['image'] = $mediaPath . $firstItem["id"] . "." . $firstItem["imageExt"];
                    }
                }
                $this->setProperty('firstItem', $firstItem);
                unset($items[0]);
            }
            $this->setProperty('items', $items);
        }
    }

}

