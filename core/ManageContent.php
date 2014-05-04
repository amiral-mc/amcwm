<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageContent class, manage content
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManageContent extends CComponent {

    /**
     *
     * @var Controller 
     */
    protected $controller;

    /**
     *
     * @var boolean
     */
    protected $isBackend = true;

    /**
     * Constructor
     * @param boolean $isBackend
     * @access private
     * @throws Error Error if you call the constructor directly
     */
    public function __construct($isBackend = true) {
        $this->isBackend = $isBackend;
        $this->controller = AmcWm::app()->getController();
        $this->init();
    }

    /**
     * Initializes the manage.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     */
    protected function init() {
        
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @param string $action action to redirect to it after publish / unpublish the content
     * @param array $params paramters to append to redirect route
     * @access public 
     * @return void
     */
    public function publish($published, $action = "index", $params = array(), $loadMethod = "loadChildModel") {
        if (Yii::app()->request->isPostRequest && method_exists($this->controller, $loadMethod)) {
            if ($published) {
                $okMessage = 'item "{displayTitle}" has been published';
            } else {
                $okMessage = 'item "{displayTitle}" has been unpublished';
            }
            $ids = Yii::app()->request->getParam('ids');
            $messages = array();
            $messages['error'] = array();
            $messages['success'] = array();

            foreach ($ids as $id) {
                if ($loadMethod == "loadChildModel") {
                    $contentModel = $this->controller->loadChildModel($id);
                    $model = $contentModel->getParentContent();
                    $itemName = $contentModel->displayTitle;
                } else {
                    $model = $this->controller->$loadMethod($id);
                    $itemName = $model->displayTitle;
                }

                if ($model->publish($published)) {
                    $messages['success'][] = AmcWm::t("amcBack", $okMessage, array("{displayTitle}" => $itemName));
                } else {
                    $messages['error'][] = AmcWm::t("amcBack", 'Can not publish item "{displayTitle}"', array("{displayTitle}" => $itemName));
                }
            }
            if (count($messages['error'])) {
                Yii::app()->user->setFlash('error', array('class' => 'flash-error', 'content' => implode("<br />", $messages['error'])));
            }
            if (count($messages['success'])) {
                Yii::app()->user->setFlash('success', array('class' => 'flash-success', 'content' => implode("<br />", $messages['success'])));
            }
        }
        $url = array($action);
        if (count($params)) {
            foreach ($params as $key => $value) {
                $url[$key] = $value;
            }
        }
        $this->controller->redirect($url);
    }

    /**
     * Get Socials networks list
     * @access public 
     * @return array     
     */
    public function getSocials() {
        $social = CHtml::listData(SocialNetworks::model()->findAll(), 'social_id', 'network_name');
        return $social;
    }       
}
