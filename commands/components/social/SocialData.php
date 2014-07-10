<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * SocialData class,  gets articles as array list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
abstract class SocialData extends CComponent {

    /**
     * Content language
     * @var integer 
     */
    protected $language = null;

    /**
     * Article language
     * @var integer 
     */
    protected $socialId = null;

    /**
     *
     * @var AmcSocial Social class
     */
    protected $social = true;

    /**
     *
     * @var integer content to post each time
     */
    protected $limit = 1;

    /**
     *
     * @var integer current module id
     */
    protected $moduleId = 0;

    /**
     * 
     * Constructor
     * @param string $module
     * @param integer $socialId
     * @param AmcSocial $social
     * @param string $lang
     * @param integer $limit
     */
    public function __construct($module, $socialId, $social, $lang, $limit) {

        if (!$lang) {
            $lang = Yii::app()->getLanguage();
        }
        $this->language = $lang;
        $this->limit = (int) $limit;
        $this->socialId = $socialId;
        $this->social = $social;
        $query = sprintf('select module_id from modules where module = %s and parent_module=1', AmcWm::app()->db->quoteValue($module));
        $this->moduleId = AmcWm::app()->db->createCommand($query)->queryScalar();
    }

    /**
     * 
     * @param type $route
     * @param type $params
     * @return type
     */
    protected function createUrl($route, $params) {
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            $url = Yii::app()->params['siteUrl'];
        } else {
            $url = Yii::app()->params['siteUrl'] . '/index.php';
        }
        return Html::createLinkRoute($url, $route, $params);
    }

    /**
     *
     * Post content to social network
     * @return boolean
     */
    abstract public function post();
}
