<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class AmcInfocusController extends FrontendController {

    /**
     * Controller constrctors
     * @param string $id id of this controller
     * @param CWebModule $module the module that this controller belongs to. This parameter
     * @access public
     */
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
    }

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        $inFocusItems = new InFocusListData();
        $inFocusItems->addColumn("publish_date");
        $inFocusItems->addColumn("brief");
        $infocusData = new PagingDataset($inFocusItems,  Yii::app()->params['pageSize'], Yii::app()->request->getParam("page"));        
        $this->render('index', array('infocusData'=>$infocusData->getData()));
    }

    /**
     * Display sections Articles
     */
    public function actionView($id) {
        $contentType = Yii::app()->request->getParam('ct', 'news');
        $keywords = Yii::app()->request->getParam('q');
        $infocus = new InFocusItemsData($id, $keywords, $contentType, 10);
        $options = InFocusListData::getSettings()->options['default']['integer'];
        if($options['topImages'] || $options['topVideos'] || $options['topText']){
            $infocus->genereateTopResults(true, $options['topText'], $options['topVideos'], $options['topImages']);             
        }        
        $infocus->generate();        
        $this->render('view', array(
            'id' => $id,
            'page' => Yii::app()->request->getParam('page', 1),
            'contentType' => $infocus->getContentType(),
            'infocusData' => $infocus->getInfocusData(),
            'infocusResults' => $infocus->getResults(),
            'advancedParams' => $infocus->getAdvancedParam(),
            'keywords' => $keywords,
            'infocusLatest'=>$infocus->getLatestResults(),
            'routers' => Yii::app()->params['routers'],
        ));
    }
}