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
abstract class AmcSocialData extends CComponent {

    /**
     *
     * @var string current route 
     */
    protected $route;

    /**
     *
     * @var string current route 
     */
    protected $customUrlRule;

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
     * @var AmcSocial current social
     */
    protected $social;

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
        $this->socialId = (int) $socialId;
        $this->social = $social;
        $query = sprintf('select module_id from modules where module = %s and parent_module=1', AmcWm::app()->db->quoteValue($module));
        $this->moduleId = AmcWm::app()->db->createCommand($query)->queryScalar();
    }

    /**
     * 
     * @param type $route
     * @param type $params
     * @return string
     */
    protected function createUrl($route, $params) {
        
        if (Yii::app()->getUrlManager()->getUrlFormat() == 'path') {
            $url = Yii::app()->params['siteUrl'];
        } else {
            $url = Yii::app()->params['siteUrl'] . '/index.php';
        }        
        return  Html::createConsoleUrl($url, $route, $params);
        
    }

    /**
     * Setting social route used to generate link
     * @param string $route
     */
    public function setRoute($route) {
        if ($route) {
            $this->route = $route;
        }
    }

    /**
     * Update social after post 
     */
    protected function updateSoicalConfig($tableId, $itemId, $createDate, $langItemId) {
        $isConfig = AmcWm::app()->db->createCommand("select config_id from module_social_config_langs where config_id = {$langItemId} and content_lang = " . AmcWm::app()->db->quoteValue($this->language))->queryScalar();
        if (!$isConfig) {
            AmcWm::app()->db->createCommand("insert into module_social_config_langs (config_id ,content_lang) values({$langItemId}, " . AmcWm::app()->db->quoteValue($this->language) . ")")->execute();
        }
        $query = "update module_social_config set post_date = '{$createDate}' where module_id = {$this->moduleId} and table_id = {$tableId} and ref_id = {$itemId} and social_id = {$this->socialId}";
        AmcWm::app()->db->createCommand($query)->execute();
    }

    /**
     *
     * Post content to social network
     * @return boolean
     */
    abstract public function post();
}
