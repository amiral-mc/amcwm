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
        $contentType = Yii::app()->request->getParam('ct', 'text');
        $page = Yii::app()->request->getParam('page', 1);        
        $infocus = new InFocusItemsData($id, $contentType, 15, 1, 3);
        $infocus->genereateTopResults(true);        
        $infocus->generate();
        $this->render('view', array(
            'id' => $id,
            'page' => $page,
            'contentType'=>$contentType,
            'infocusData' => $infocus->getInfocusData(),
            'infocusItems' => $infocus->getResults(),
            'infocusLatestData'=>$infocus->getLatestResults(),
        ));
    }
}