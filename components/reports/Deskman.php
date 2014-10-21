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
abstract class Deskman extends ReportsForm {

    CONST REPORTS_PAGE_COUNT = 10;

    protected $logTable = '';
    public $view = '';

    /**
     * @var type string Module Name.
     */
    protected $module;

    /**
     *
     * @var Deskmen
     */
    protected $deskMen = null;
    protected $deskMenClass = 'Deskmen';

    /**
     * @var type string Primary table selected.
     */
    protected $contentTable = array('table' => null, 'pk' => null);
    protected $cols = array('content_title' => null);
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
        $this->view = "amcwm.core.backend.views.default.reports.deskman";
        $this->writer = (int) AmcWm::app()->request->getParam('user_id');
        $deskMenClass = $this->deskMenClass;
        $this->deskMen = new $deskMenClass();
        $this->setWhere('ul.action_id = ' . $this->getActionId());
        if ($this->writer) {
            $this->setWhere("ul.user_id = {$this->writer}");
        }
        $this->setContentTable();
        $this->setCols();
        $this->setJoin();
    }

    abstract protected function setContentTable();

    abstract protected function setCols();

    abstract protected function setJoin();

    protected function renderResult($data, $printMode = false) {
        $data['content'] = $this->getData();
        if ($this->printMode) {
            AmcWm::app()->getController()->render($this->view . "Print", $data);
        } else {
            AmcWm::app()->getController()->render($this->view, $data);
        }
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
        $query .= " INNER JOIN {$this->logTable} l ON {$this->contentTable['table']}.{$this->contentTable['pk']} = l.item_id";
        $query .= ' INNER JOIN users_log ul ON l.log_id = ul.log_id';
        $query .= ' INNER JOIN actions a ON ul.action_id = a.action_id';
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $query .= " $join";
            }
        }
//        $query .= ' WHERE ul.action_id = ' . $this->getActionId();
        $query .= $this->where;
        $query .= " GROUP BY {$this->contentTable['table']}.{$this->contentTable['pk']}";
        $count = "SELECT COUNT(*) " . $query;
        if (!$this->printMode) {
            $query .= " LIMIT " . self::REPORTS_PAGE_COUNT;
        }
        $page = (int) Yii::app()->request->getParam('page');
        if ($page) {
            $query .= " OFFSET " . Deskman::REPORTS_PAGE_COUNT * ($page - 1);
        }
        $select = $select . $query;
        $counts = AmcWm::app()->db->createCommand($count)->queryAll();
        $pagination = new CPagination(count($counts));
        $pagination->setPageSize(self::REPORTS_PAGE_COUNT);
        $data['records'] = AmcWm::app()->db->createCommand($select)->queryAll();
        $data['pagination'] = $pagination;
        $data['count'] = $count;
        return $data;
    }

    public function run() {

        $this->viewResult = AmcWm::app()->request->getParam('result');
        $formOutput = $this->renderSearchForm();
        $data = array();
        if ($this->viewResult) {
            $data = $this->getData();
            if ($this->writer) {
                $this->deskMen->setWhere("user_id = " . (int) AmcWm::app()->request->getParam('user_id'));
            }
            $deskmen = $this->deskMen->getData(true);
            $data['deskman'] = $deskmen['records'];
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
