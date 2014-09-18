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
class ArticlesReporters extends ReportsForm {

    public $view = '';

    /**
     * @var type string Primary table selected.
     */
    protected $contentTable = array('table' => 'articles', 'pk' => 'article_id');
    protected $logTable = 'articles_log';
    protected $cols = array(
        'create_date' => 'create_date',
        'reporter' => 'name',
        'count' => 'count(*)',
        'published' => 'count(case published when 1 then 1 else null end)',
    );
    protected $where = '';

    /**
     * @var type string Joined Tables.
     */
    protected $joins = '';

    public function __construct() {
        $this->view = "amcwm.modules.articles.backend.views.reports.reporters";
        $virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
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
                $this->setWhere("{$articleTable}.article_id IS NULL");
            }
        }
        parent::__construct();
        AmcWm::app()->getController()->layout = $this->layout;
        $this->setContentTable();
        $this->setCols();
        $this->setJoin();
    }

    public function setWhere($where, $operator = "and") {
        $this->where .= " {$operator} $where";
    }

    protected function setContentTable() {
        
    }

    protected function setCols() {
        
    }

    protected function setJoin() {
        
    }

    public function getData($singleRow = false) {
        $fromDate = AmcWm::app()->request->getParam('datepicker-from');
        $toDate = AmcWm::app()->request->getParam('datepicker-to');
        if ($fromDate) {
            $this->setWhere("{$this->contentTable['table']}.{$this->cols['create_date']} >= '{$fromDate}'", "AND");
        }
        if ($toDate) {
            $this->setWhere("{$this->contentTable['table']}.{$this->cols['create_date']} <= '{$toDate} 23:59:59'", "AND");
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
        $query .= " INNER JOIN persons_translation pt ON {$this->contentTable['table']}.writer_id = pt.person_id";
        $query .= $this->join;
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $query .= " $join";
            }
        }
        $query .= ' WHERE pt.content_lang = ' . AmcWm::app()->db->quoteValue(Controller::getContentLanguage());
        $query .= $this->where;
        $count = "SELECT COUNT(*) " . $query;
        $query .= " GROUP BY pt.person_id";
        $select = $select . $query;
        $pagination = new CPagination($count);
        $pagination->setPageSize(Deskman::REPORTS_PAGE_COUNT);
        $data['pagination'] = $pagination;
        $data['count'] = $count;
        if ($singleRow) {
            $data['records'] = AmcWm::app()->db->createCommand($select)->queryRow();
        } else {
            $data['records'] = AmcWm::app()->db->createCommand($select)->queryAll();
        }
        return $data;
    }

    protected function renderResult($data) {
        AmcWm::app()->getController()->render($this->view, $data);
    }

    protected function renderSearchForm() {
        return AmcWm::app()->getController()->renderPartial($this->view . "Form", array(), true);
    }

}
