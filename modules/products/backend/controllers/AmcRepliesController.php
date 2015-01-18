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
class AmcRepliesController extends RepliesCommentsController {

    /**
     * @create new model
     * @access protected
     * @return ActiveRecord
     */
    public function newModel() {
        $model = new ProductsComments();
        return $model;
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    protected function loadCommentsData($id) {
        parent::loadCommentsData($id);
        $pk = ChildTranslatedActiveRecord::getCompositeValues($id);
        $this->commentsType = ProductsTranslation::model()->findByPk(array("product_id" => $pk['id'], 'content_lang' => $pk['lang']));
        if ($this->commentsType == null) {
            throw new CHttpException(404, 'The requested type does not exist.');
        } else {
            $this->breadcrumbs = array(
                AmcWm::t("msgsbase.core", "Products") => array("/backend/products/default/index"),
                $this->commentsType->displayTitle => array("/backend/products/default/view", 'id' => $this->getItemId()),
            );
            $this->backRoute = array("/backend/products/productComments/index", 'item' => $this->getItemId());
        }
    }

    /**
     * Gets the comments type id
     * @access public
     * @return integer
     */
    public function getItemId() {
        $id = 0;
        if ($this->commentsType != null) {
            $id = $this->commentsType->product_id;
        }
        return $id;
    }

}
