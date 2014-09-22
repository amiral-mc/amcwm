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
    protected $contentTable = array('table' => 'writers', 'pk' => 'writer_id');
    protected $logTable = 'articles_log';
    protected $cols = array(
        'create_date' => 'create_date',
        'reporter' => 'name',
        'count' => 'count(*)',
        'published' => 'count(case published when 1 then 1 else null end)',
    );
    protected $where;

    /**
     * @var type string Joined Tables.
     */
    protected $joins = '';

    public function __construct() {
        if (Yii::app()->request->getParam('print')) {
            $this->printMode = true;
        }
        $this->view = "amcwm.modules.articles.backend.views.reports.reporters";
        $this->virtualModule = AmcWm::app()->controller->getModule()->appModule->currentVirtual;
//        $userId = Yii::app()->request->getParam('user_id');
        if ($this->virtualModule == 'news') {
            $writerIds = "(" . Writers::BOTH_TYPE . ", " . Writers::EDITOR_TYPE . ")";
            $this->join .=" INNER JOIN persons_translation pt ON {$this->contentTable['table']}.{$this->contentTable['pk']} = pt.person_id";
            $this->join .=" LEFT JOIN news_editors ne on pt.person_id = ne.editor_id ";
            $this->join .=" LEFT JOIN articles a on ne.article_id = a.article_id ";
            $this->setWhere("{$this->contentTable['table']}.writer_type IN {$writerIds}");
            $this->setWhere(' pt.content_lang = ' . AmcWm::app()->db->quoteValue(Controller::getContentLanguage()));
//            $this->setWhere(" person_id = {$userId}");
        }
        if ($this->virtualModule == 'essays') {
            $writerIds = "(" . Writers::BOTH_TYPE . ", " . Writers::WRITER_TYPE . ")";
            $this->join .=" INNER JOIN persons_translation pt ON {$this->contentTable['table']}.{$this->contentTable['pk']} = pt.person_id";
            $this->join .=" LEFT JOIN articles a on pt.person_id = a.writer_id ";
            $this->join .=" LEFT JOIN essays e on a.article_id = e.article_id ";
            $this->setWhere("{$this->contentTable['table']}.writer_type IN {$writerIds}");
            $this->setWhere(' pt.content_lang = ' . AmcWm::app()->db->quoteValue(Controller::getContentLanguage()));
//            $this->setWhere(" person_id = {$userId}");
        }
        if ($this->virtualModule == 'articles') {
            $this->contentTable['table'] = 'articles';
            $this->contentTable['pk'] = 'article_id';
            $articlesTables = AmcWm::app()->appModule->getExtendsTables();
            unset($this->cols['reporter']);
            foreach ($articlesTables as $articleTable) {
                $this->join .=" LEFT JOIN {$articleTable} on articles.article_id = {$articleTable}.article_id ";
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
        if (!strlen($this->where)) {
            $this->where .= " WHERE {$where}";
        } else {
            $this->where .= " {$operator} $where";
        }
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
            $this->setWhere("{$this->cols['create_date']} >= '{$fromDate}'", "AND");
        }
        if ($toDate) {
            $this->setWhere("{$this->cols['create_date']} <= '{$toDate} 23:59:59'", "AND");
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
        $query .= $this->join;
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $query .= " $join";
            }
        }
        $query .= $this->where;
        if ($this->virtualModule != 'articles') {
            $query .= " GROUP BY pt.person_id";
        }
        $count = "SELECT COUNT(*) " . $query;
        if (!$this->printMode) {
            $query .= " LIMIT " . Deskman::REPORTS_PAGE_COUNT;
        }
        $page = (int) Yii::app()->request->getParam('page');
        if ($page) {
            $query .= " OFFSET " . Deskman::REPORTS_PAGE_COUNT * ($page - 1);
        }
        $select = $select . $query;
        $counts = AmcWm::app()->db->createCommand($count)->queryAll();
        $pagination = new CPagination(count($counts));
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

    protected function renderResult($data, $printMode = false) {
        $data['module'] = $this->virtualModule;
        if ($this->printMode) {
            AmcWm::app()->getController()->render($this->view . "Print", $data);
        } else {
            AmcWm::app()->getController()->render($this->view, $data);
        }
    }

    protected function renderSearchForm() {
        return AmcWm::app()->getController()->renderPartial($this->view . "Form", array(), true);
    }

}
