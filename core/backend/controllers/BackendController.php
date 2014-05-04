<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 * @author Amiral Management Corporation
 * @version 1.0
 */

class BackendController extends Controller {

    /**
     * Admin Section name for example "News, Gallery"
     * @var string
     */
    public $sectionName = "";

    /**
     * Contain html for site statistics to display inside statistics widget
     * @var string
     */
    public $statistics = null;

    /**
     *
     * @var ManageContent
     */
    protected $manager = null;

    /**
     *
     * @var string 
     */
    public $backendBaseUrl = null;

    /**
     * Initializes the controller.
     * This method is called by the application before the controller starts to execute.
     * You may override this method to perform the needed initialization for the controller.
     */
    public function init() {
        parent::init();
        if (AmcWm::app()->backend['bootstrap']['use']) {
            if (isset(AmcWm::app()->backend['bootstrap']['useResponsive']) && AmcWm::app()->backend['bootstrap']['useResponsive']) {
                Yii::app()->bootstrap->useResponsive = true;
            }
            else{
                Yii::app()->bootstrap->useResponsive = false;
            }
            Yii::app()->bootstrap->register();
        }        
        $this->manager = new ManageContent(true);
        $this->backendBaseUrl = Yii::app()->getAssetManager()->getPublishedUrl(Yii::getPathOfAlias(AmcWm::app()->getModule(AmcWm::app()->backendName)->viewsBaseAlias . ".layouts.publish"));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $this->layout = $this->getModule()->viewsBaseAlias . '.layouts.error';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Performs the publish action
     * @see ActiveRecord::publish($published)
     * @param int $published
     * @access public 
     * @return void
     */
    public function actionPublish($published) {
        $this->publish($published, "index", array());
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
    protected function publish($published, $action = "index", $params = array(), $loadMethod = "loadChildModel") {
        $this->manager->publish($published, $action, $params, $loadMethod);
    }

    /**
     * Get Socials networks list
     * @access public 
     * @return array     
     */
    public function getSocials() {
        return $this->manager->getSocials();
    }

    /**
     * Get information list as json
     * @access public
     */
    public function actionChangeContentLang($clang) {
        if (!array_key_exists($clang, Yii::app()->params['languages'])) {
            $clang = Html::escapeString(self::$currentLang);
        }
        Yii::app()->user->setState('contentLang', $clang);
        $this->redirect(array("/backend/default/index"));
    }

    /**
     * 
     * @return ManagwContent
     */
    public function getManager() {
        return $this->manager;
    }
    
    public function getCountries($addEmpty = false, $code = NULL, $contentLang = null) {
        return parent::getCountries($addEmpty, $code, AmcWm::app()->getLanguage());
    }

}
