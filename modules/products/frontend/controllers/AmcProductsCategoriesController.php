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
class AmcProductsCategoriesController extends FrontendController {

    public function actionIndex() {
        $this->forward('category');
    }

    public function actionCategory() {
        $options = self::getSettings()->options;
        $id = AmcWm::app()->request->getParam('id') ? AmcWm::app()->request->getParam('id') : isset($options['default']['integer']['mainSection']) ? $options['default']['integer']['mainSection'] : null;
        if ($id != null) {
            $categoryId = AmcWm::app()->db->createCommand()
                    ->select('section_id')
                    ->from('sections')
                    ->where("parent_section = " . (int) $id)
                    ->limit(1)
                    ->queryScalar();
            $this->render("category", array('id' => $categoryId));
        } else {
            throw new CHttpException(AmcWm::t('msgsbase.core', 'The category specified does not exist'), 404);
        }
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        
    }

    /**
     * Performs the comments action
     * @param int $aid
     * @access public 
     * @return void
     */
    public function actionComments($id) {
        $this->forward('comments');
    }

}
