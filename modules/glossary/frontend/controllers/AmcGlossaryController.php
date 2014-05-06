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

class AmcGlossaryController extends FrontendController {

    public function actionIndex() {        
        $params = array();
        $params['keywords'] = Yii::app()->request->getParam('q');
        $params['isAlpha'] = (int) Yii::app()->request->getParam('a');
        $params['categoryId'] = (int) Yii::app()->request->getParam('catId');
        $params['limit'] = (int) Yii::app()->request->getParam('limit', 10);
        
        $glossary = new GlossaryData($params);
        $glossary->generate();
        $this->render('glossary', array(
            'page' => (int) Yii::app()->request->getParam('page', 1),
            'glossaryData' => $glossary->getResults(),
            'advancedParams' => $glossary->getAdvancedParam(),
            'keywords' => $glossary->getKeywords(),
            'alphabet' => $glossary->getAlphabet(),
            'categoriesList' => $glossary->getAllCategories(),
            'categoryId' => $params['categoryId']
        ));
    }

}
