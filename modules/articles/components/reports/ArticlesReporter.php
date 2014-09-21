<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Diskmen
 *
 * @author abdallah
 */
class ArticlesReporter extends ReportsForm {

    public $view = '';

    /**
     * @var type string Module Name.
     */
    protected $module;

    /**
     *
     * @var Deskmen
     */
    protected $reporters = null;
    protected $reportersClass = 'ArticlesReporters';

    /**
     * @var type string Primary table selected.
     */
    protected $contentTable = array('table' => 'articles', 'pk' => 'article_id');
    protected $cols = array(
        'header' => 'article_header',
        'date' => 'create_date',
        'views' => 'hits',
        'comments' => 'comments',
    );
    protected $where = '';
    protected $writer = null;

    /**
     * @var type string Joined Tables.
     */
    protected $joins;

    public function __construct() {
        $virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
        $this->view = "amcwm.modules.articles.backend.views.reports.reporter";
        $this->writer = (int) AmcWm::app()->request->getParam('user_id');
        $reportersClass = $this->reportersClass;
        $this->reporters = new $reportersClass();
        if ($this->writer) {
            if ($virtualModule == 'news') {
                $this->setWhere("editor_id = {$this->writer}");
            } else {
                $this->setWhere("writer_id = {$this->writer}");
            }
        }
        if ($virtualModule == 'news') {
            $this->join .=" INNER JOIN news n on {$this->contentTable['table']}.{$this->contentTable['pk']} = n.{$this->contentTable['pk']} ";
            $this->join .=" INNER JOIN news_editors ne on n.{$this->contentTable['pk']} = ne.{$this->contentTable['pk']} ";
        }
        if ($virtualModule == 'essays') {
            $this->join .=" INNER JOIN essays e on {$this->contentTable['table']}.{$this->contentTable['pk']} = e.{$this->contentTable['pk']} ";
        }
        if ($virtualModule == 'articles') {
            $articlesTables = AmcWm::app()->appModule->getExtendsTables();
            foreach ($articlesTables as $articleTable) {
                $this->join .=" LEFT JOIN {$articleTable} on {$this->contentTable['table']}.article_id = {$articleTable}.article_id ";
                $this->setWhere(" {$articleTable}.article_id IS NULL");
            }
        }
    }

    protected function renderResult($data) {
        $data['content'] = $this->getData();
        AmcWm::app()->getController()->render($this->view, $data);
    }

    protected function renderSearchForm() {
        return AmcWm::app()->getController()->renderPartial($this->view . "Form", array(), true);
    }

    public function setWhere($where, $operator = "and") {
        if (!strlen($this->where)) {
            $this->where .= " WHERE {$where}";
        } else {
            $this->where .= " {$operator} $where";
        }
    }

    protected function getData() {
        $fromDate = AmcWm::app()->request->getParam('datepicker-from');
        $toDate = AmcWm::app()->request->getParam('datepicker-to');
        if ($fromDate) {
            $this->setWhere("{$this->contentTable['table']}.{$this->cols['date']} >= '{$fromDate}'", "AND");
        }
        if ($toDate) {
            $this->setWhere("{$this->contentTable['table']}.{$this->cols['date']} <= '{$toDate} 23:59:59'", "AND");
        }
        $select = 'SELECT ';
        $index = 0;
        foreach ($this->cols as $alias => $col) {
            if ($index) {
                $select .= ", ";
            }
            $select .= "{$col} {$alias}";
            $index = 1;
        }
        $query = " FROM {$this->contentTable['table']}";
        $query .= " INNER JOIN {$this->contentTable['table']}_translation on {$this->contentTable['table']}.{$this->contentTable['pk']} = {$this->contentTable['table']}_translation.{$this->contentTable['pk']}";
        $query .= " AND {$this->contentTable['table']}_translation.content_lang = " . AmcWm::app()->db->quoteValue(Controller::getContentLanguage());
        $query .= $this->join;
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $query .= " $join";
            }
        }
        if (!$this->writer) {
            $this->setWhere("writer_id IS NOT NULL");
        }
        $query .= $this->where;
        $count = "SELECT COUNT(*) " . $query;
        $select = $select . $query;
        $pagination = new CPagination($count);
        $pagination->setPageSize(Deskman::REPORTS_PAGE_COUNT);
        $data['pagination'] = $pagination;
        $data['count'] = $count;
//        die($select);
        
        $data['records'] = AmcWm::app()->db->createCommand($select)->queryAll();
        return $data;
    }

    public function run() {
        $virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
        $this->viewResult = AmcWm::app()->request->getParam('result');
        $formOutput = $this->renderSearchForm();
        $data = array();
        if ($this->viewResult) {
            $data = $this->getData();
//            print_r($data['records']); exit;
//            $data['records'] = $this->getData();
            
            if ($this->writer) {
                if ($virtualModule == 'news') {
                    $this->reporters->setWhere("editor_id = " . $this->writer);
                } else {
                    $this->reporters->setWhere("writer_id = " . $this->writer);
                }
            }
            $data['reporter'] = $this->reporters->getData(true);
        }
        $data['viewResult'] = $this->viewResult;
        $data['formOutput'] = $formOutput;
        $this->renderResult($data);
    }

}

//SELECT article_header header, create_date date, hits views, comments comments 
//    FROM articles 
//    INNER JOIN articles_translation on articles.article_id = articles_translation.article_id 
//    AND articles_translation.content_lang = 'ar' 
//    INNER JOIN news n on articles.article_id = n.article_id 
//    INNER JOIN news_editors ne on n.article_id = ne.article_id 
//    WHERE editor_id = 11