<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesSectionsSectionsTask class, run the mixed task
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesSectionsMixedTask extends ArticlesControllerTask {

    /**
     * Run this task
     * @param boolean $displayResult
     * @return boolean
     */
    public function run($displayResult = true) {
        $this->displayResult = $displayResult;
        $limit = Yii::app()->params['pageSize'];
        if (isset($this->extraParams['limit'])) {
            $limit = $this->extraParams['limit'];
        }
        $render = false;
        $this->dataset = new SectionMixedData($this->table, $this->params['id'], $limit);
        $this->dataset->setMediaPath(Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['topContent']['path'] . "/");
        $this->dataset->setSectionMediaPath(Yii::app()->baseUrl . "/" . SectionsData::getSettings()->mediaPaths['blocks']['path'] . "/");
        $this->dataset->setArticleMediaPath(Yii::app()->baseUrl . "/" . $this->settings->mediaPaths['blocks']['path'] . "/");
        $this->dataset->useRecordIdAsKey(false);
        $this->dataset->generate();
        if ($this->displayResult) {
            $section = $this->dataset->getItems();
            if ($this->viewType == "default") {
                $itemsList['records'] = $this->dataset->getContents();
                $itemsList['pager'] = array(
                    'count' => count($itemsList['records']),
                    'pageSize' => 0,
                );
            } else {
                $itemsList = $this->dataset->getContents();
            }
            if (count($section)) {
                $render = true;
                $data['pageSiteTitle'] = $section['sectionTitle'];
                $data['widgetTitle'] = $section['sectionTitle'];
                $data['pageContentTitle'] = $section['sectionTitle'];
                $data['sectionId'] = $section['sectionId'];
                $data['pageContent'] = $section['sectionDescription'];
                $data['widgetImage'] = $section['sectionImage'];
                $data['itemsList'] = $itemsList;
                $data['viewOptions'] = $this->options;
                $data['keywords'] = implode(", ", $this->dataset->getKeywords());
                $data['task'] = $this;
                $data['descriptionKey'] = "description";
                $this->render($this->viewType, array('data' => $data));
            }
        }
        return $render;
    }

    /**
     * Renders a view with a layout.
     * @param string $view name of the view to be rendered
     * @param array $data data to be extracted into PHP variables and made available to the view script
     * @param boolean $return whether the rendering result should be returned instead of being displayed to end users.
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function render($view, $data = array(), $return = false) {
        return Yii::app()->getController()->render($view, $data, $return);
    }

    /**
     * get site mapdata used in this task
     * @access public
     * @return array();
     */
    public function getSiteMapData() {
        $mapData = array();
        if ($this->dataset) {
            $sections = $this->dataset->getContents();
            foreach ($sections as $section) {
                $mapData[] = array(
                    "label" => $section['title'],
                    "url" => $section['link'],
                );
            }
        }
        return $mapData;
    }

}

