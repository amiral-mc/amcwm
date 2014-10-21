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
        if (Yii::app()->request->getParam('print')) {
            $this->printMode = true;
        }
        $this->virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
        $this->view = "amcwm.modules.articles.backend.views.reports.reporter";
        $this->writer = (int) AmcWm::app()->request->getParam('user_id');
        $reportersClass = $this->reportersClass;
        $this->reporters = new $reportersClass();
        if ($this->writer) {
            if ($this->virtualModule == 'news') {
                $this->setWhere("editor_id = {$this->writer}");
            } else {
                $this->setWhere("articles.writer_id = {$this->writer}");
            }
        }
        if ($this->virtualModule == 'news') {
            $this->join .=" INNER JOIN news n on {$this->contentTable['table']}.{$this->contentTable['pk']} = n.{$this->contentTable['pk']} ";
            $this->join .=" INNER JOIN news_editors ne on n.{$this->contentTable['pk']} = ne.{$this->contentTable['pk']} ";
        }
        if ($this->virtualModule == 'essays') {
            $this->join .=" INNER JOIN essays e on {$this->contentTable['table']}.{$this->contentTable['pk']} = e.{$this->contentTable['pk']} ";
        }
        if ($this->virtualModule == 'articles') {
            $articlesTables = AmcWm::app()->appModule->getExtendsTables();
            foreach ($articlesTables as $articleTable) {
                $this->join .=" LEFT JOIN {$articleTable} on {$this->contentTable['table']}.article_id = {$articleTable}.article_id ";
                $this->setWhere(" {$articleTable}.article_id IS NULL");
            }
        }
    }

    protected function renderResult($data, $printMode = false) {
        $data['module'] = $this->virtualModule;
        $data['content'] = $this->getData();
        if ($this->printMode) {
            AmcWm::app()->getController()->render($this->view . "Print", $data);
        } else {
            AmcWm::app()->getController()->render($this->view, $data);
        }
    }

    protected function renderSearchForm($module = null) {
        return AmcWm::app()->getController()->renderPartial($this->view . "Form", array('module' => $module), true);
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
//        if (!$this->writer) {
//            $this->setWhere("writers.writer_id IS NOT NULL");
//        }
        $query .= $this->where;

        $count = "SELECT COUNT(*) " . $query;
        if (!$this->printMode) {
            $query .= " LIMIT " . Deskman::REPORTS_PAGE_COUNT;
        }
        $page = (int) Yii::app()->request->getParam('page');
        if ($page) {
            $query .= " OFFSET " . Deskman::REPORTS_PAGE_COUNT * ($page - 1);
        }
        $select = $select . $query;
//        echo $count;
//        echo "<br />";
//        echo "<br />";
//        die($select);
        $pagination = new CPagination(AmcWm::app()->db->createCommand($count)->queryScalar());
        $pagination->setPageSize(Deskman::REPORTS_PAGE_COUNT);
        $data['pagination'] = $pagination;
        $data['count'] = $count;
        $data['records'] = AmcWm::app()->db->createCommand($select)->queryAll();
        return $data;
    }

    public function run() {
        $this->viewResult = AmcWm::app()->request->getParam('result');
        $formOutput = $this->renderSearchForm($this->virtualModule);
        $data = array();
        if ($this->viewResult) {
            $data = $this->getData();
            if ($this->writer) {
                if ($this->virtualModule == 'news') {
                    $this->reporters->setWhere("editor_id = " . $this->writer);
                } else {
                    $this->reporters->setWhere("writers.writer_id = " . $this->writer);
                }
            }
            $data['reporter'] = $this->reporters->getData(true);
        }
        $data['viewResult'] = $this->viewResult;
        $data['formOutput'] = $formOutput;
        if ($this->printMode) {
            $this->renderResult($data, true);
        } else {
            $this->renderResult($data);
        }
    }

}
