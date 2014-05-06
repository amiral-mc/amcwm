<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of MobileController
 * @author Amiral Management Corporation
 * @version 1.0
 */

class MobileController extends FrontendController {

    public $layout = '//layouts/mobile';        // our layout is the mobile.php

    const SECTION_ROUTE = '/mobile/articles';   // router to the articles at the section
    const ARTICLE_ROUTE = '/mobile/details';    // router to the article details

    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        Yii::app()->errorHandler->errorAction = '/mobile/error';
    }

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users useing their mobile fones.
     */
    public function actionIndex() {

        $sections = $this->_getSections();
        $topNews = $this->_getTopLatest(1);
        $this->render('index', array("topNews"=>$topNews, "sections" => $sections));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {        
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /*
     * Action to list all articles at the section
     */

    public function actionArticles($id) {

        if ((int) $id) {
            $allSections = $this->_getSections();
            $section = $this->_getSectionDetails($id);
            $articles = $this->_getSectionArticles($id);
            $this->render('articles', array("allSections" => $allSections,"section" => $section, "articles" => $articles));
        }else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /*
     * action to display the article details
     */

    public function actionDetails($id) {
        if ((int) $id) {
            $allSections = $this->_getSections();
            $articleQuery = sprintf("select a.*, s.section_name, s.section_id
                        from articles a
                        left join sections s on s.section_id=a.section_id
                        where a.article_id = %d", $id);
            $article = Yii::app()->db->createCommand($articleQuery)->queryRow();
            $this->render('details', array("allSections"=>$allSections,  "article" => $article));
        }else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    
    private function _getTopLatest($limit = 5){
        $articlesQuery = sprintf("select a.article_id,article_header,a.thumb, a.article_detail , a.publish_date
            from articles a            
            inner join news n on n.article_id = a.article_id            
            where a.published=1            
            and a.publish_date <=NOW() 
            and (a.content_lang = %s or a.content_lang is null or a.content_lang ='')
            and (a.archive = 0 or a.archive is null)
            and (a.expire_date >=NOW() or a.expire_date is null)           
            order by a.create_date desc limit %d", Yii::app()->db->quoteValue(Controller::getCurrentLanguage()), $limit);
        $items = Yii::app()->db->createCommand($articlesQuery)->queryAll();
        $articles = array();
        if (count($items)) {
            $nextItemKey = 0;
            foreach ($items as $article) {
                $articles[$nextItemKey]['id'] = $article['article_id'];
                $articles[$nextItemKey]['label'] = $article['article_header'];
//                $articles[$nextItemKey]['createDate'] = Yii::app()->dateFormatter("yyyy-MM-dd", $article['create_date']);
                $articles[$nextItemKey]['thumb'] = $article['thumb'];
                $articles[$nextItemKey]['details'] = Html::utfSubstring($article['article_detail'], 0, 255) . "...";
                $articles[$nextItemKey]['url'] = Html::createUrl(self::ARTICLE_ROUTE, array('id' => $article['article_id'], 'lang' => Controller::getCurrentLanguage()));
                $nextItemKey++;
            }
        }
        array_rand($articles);
        return $articles;
    }

    private function _getSections(){
        $query = sprintf("select * from sections s where published = 1 
                        and (s.content_lang = %s or s.content_lang is null or s.content_lang = '')
                        and s.show_in_menu = 1 order by section_sort limit 10", Yii::app()->db->quoteValue(Controller::getCurrentLanguage()));
        $sections = Yii::app()->db->createCommand($query)->queryAll();
        $items = array(
            array('label' => AmcWm::t("amcFront", 'Home'), 'url' => array('/mobile/index', 'lang' => Controller::getCurrentLanguage(),)),
        );
        $nextItemKey = 1;
        foreach ($sections as $itemKey => $section) {
            $items[$nextItemKey]['label'] = $section['section_name'];
            $items[$nextItemKey]['url'] = Html::createUrl(self::SECTION_ROUTE, array('id' => $section['section_id'], 'lang' => Controller::getCurrentLanguage()));
            $items[$nextItemKey]['childs'] = $this->_getSectionArticles($section['section_id']);
            $nextItemKey++;
        }
        
        return $items;
    }

    private function _getSectionArticles($sectionId) {
        $articlesQuery = sprintf("select sql_calc_found_rows a.article_id,article_header,a.thumb, a.article_detail , a.publish_date
            from articles a            
            inner join news n on n.article_id = a.article_id            
            where a.section_id = %d
            and a.published=1            
            and a.publish_date <=NOW() 
            and (a.content_lang = %s or a.content_lang is null or a.content_lang ='')
            and (a.archive = 0 or a.archive is null)
            and (a.expire_date >=NOW() or a.expire_date is null)           
            order by a.create_date desc limit 5", $sectionId, Yii::app()->db->quoteValue(Controller::getCurrentLanguage()));
        $items = Yii::app()->db->createCommand($articlesQuery)->queryAll();
        $articles = array();
        if (count($items)) {
            $nextItemKey = 0;
            foreach ($items as $article) {
                $articles[$nextItemKey]['id'] = $article['article_id'];
//                $articles[$nextItemKey]['createDate'] = Yii::app()->dateFormatter("yyyy-MM-dd", $article['create_date']);
                $articles[$nextItemKey]['label'] = $article['article_header'];
                $articles[$nextItemKey]['thumb'] = $article['thumb'];
                $articles[$nextItemKey]['details'] = Html::utfSubstring($article['article_detail'], 0, 255) . "...";
                $articles[$nextItemKey]['url'] = Html::createUrl(self::ARTICLE_ROUTE, array('id' => $article['article_id'], 'lang' => Controller::getCurrentLanguage()));
                $nextItemKey++;
            }
        }

        return $articles;
    }

    private function _getSectionDetails($sectionId) {
        $sectionQuery = sprintf("select * from sections where section_id = %d", $sectionId);
        $section = Yii::app()->db->createCommand($sectionQuery)->queryRow();
        return $section;
    }

}

?>
