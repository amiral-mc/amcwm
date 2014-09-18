<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticlesDiskData
 *
 * @author abdallah
 */
class ArticlesDeskman extends Deskman {

    private $_virtualModule = '';
    protected $deskMenClass = "ArticlesDeskmen";
    protected $logTable = 'articles_log';

    public function __construct() {
        $this->_virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
        //$this->view = "amcwm.modules.articles.backend.views.reports.deskman";
//        $this->writer = AmcWm::app()->request->getParam('user_id');
//        if ($this->writer) {
//            $this->setWhere(" ul.user_id = {$this->writer}", "AND");            
//        }
        parent::__construct();
        if($this->_virtualModule != 'news'){
            $this->view = "amcwm.core.backend.views.default.reports.deskman";
        }
        else{
            $this->view = "amcwm.modules.articles.backend.views.reports.deskman";
        }
    }

    protected function setCols() {
        $this->cols = array(
            'header' => 'article_header',
            'date' => 'create_date',
            'views' => 'hits',
            'comments' => 'comments',
        );
        if ($this->_virtualModule == 'news') {
            $this->cols['reporters'] = "
                (SELECT GROUP_CONCAT(pt.name ORDER BY pt.name SEPARATOR ', ')
                FROM persons_translation pt
                INNER JOIN news_editors ne ON pt.person_id = ne.editor_id
                WHERE pt.content_lang = " . AmcWm::app()->db->quoteValue(Controller::getContentLanguage()) . " and ne.article_id = articles.article_id group by ne.article_id )
                ";
        }
    }

    protected function setContentTable() {
        $this->contentTable = array('table' => 'articles', 'pk' => 'article_id');
    }

    protected function setJoin() {
        if ($this->_virtualModule == 'news') {
            $this->joins[] = " INNER JOIN news n on {$this->contentTable['table']}.{$this->contentTable['pk']} = n.{$this->contentTable['pk']}";
            $this->joins[] = " INNER JOIN news_editors ne on n.{$this->contentTable['pk']} = ne.{$this->contentTable['pk']} ";
//            $this->joins[] = " INNER JOIN persons_translation pt on ne.editor_id = pt.person_id ";
        }
//        
//        elseif($this->_virtualModule == 'essays'){
//            $this->joins[] = "INNER JOIN essays e on {$this->contentTable['table']}.{$this->contentTable['pk']} = e.{$this->contentTable['pk']}";
//        }
//        
//        elseif($this->_virtualModule == 'articles'){
//            
//        }
//        
//        
//        if ($virtualModule == 'news') {
//            $this->join .=" INNER JOIN news n on {$this->contentTable['table']}.{$this->contentTable['pk']} = n.{$this->contentTable['pk']} ";
//            $this->join .=" INNER JOIN news_editors ne on n.{$this->contentTable['pk']} = ne.{$this->contentTable['pk']} ";
//        }
//        if ($virtualModule == 'essays') {
//            $this->join .=" INNER JOIN essays e on {$this->contentTable['table']}.{$this->contentTable['pk']} = e.{$this->contentTable['pk']} ";
//        }
//        if ($virtualModule == 'articles') {
//            $articlesTables = AmcWm::app()->appModule->getExtendsTables();
//            foreach ($articlesTables as $articleTable) {
//                $this->join .=" LEFT JOIN {$articleTable} on p.article_id = {$articleTable}.article_id ";
//                $this->setWhere("{$articleTable}.article_id IS NULL");
//            }
//        }
//        $this->_virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
//        //$articlesTables = AmcWm::app()->appModule->getExtendsTables();
//        //print_r($articlesTables);
//        //die($this->_virtualModule);
////        $this->joins = array(
////        );
//        if ($this->_virtualModule == 'news') {
//            $this->joins[] = "INNER JOIN news n on {$this->contentTable['table']}.{$this->contentTable['pk']} = news.{$this->contentTable['pk']}";
//        }
    }

}
