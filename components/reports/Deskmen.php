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
abstract class Deskmen extends ReportsForm {

    public $view = '';

    /**
     * @var type string Primary table selected.
     */
    protected $contentTable = array('table' => null, 'pk' => null);
    protected $logTable = '';
    protected $cols = array('content_title' => null, 'create_date' => null);
    protected $where = '';

    /**
     * @var type string Joined Tables.
     */
    protected $joins;

    public function __construct() {
        $this->view = "amcwm.core.backend.views.default.reports.deskmen";
        parent::__construct();
        AmcWm::app()->getController()->layout = $this->layout;
        $this->setContentTable();
        $this->setCols();
        $this->setJoin();
    }

    public function setWhere($where, $operator = "and") {
        $this->where .= " {$operator} $where";
    }

    abstract protected function setContentTable();

    abstract protected function setCols();

    abstract protected function setJoin();

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
        $query .= " INNER JOIN {$this->logTable} l ON {$this->contentTable['table']}.{$this->contentTable['pk']} = l.item_id";
        $query .= ' INNER JOIN users_log ul ON l.log_id = ul.log_id';
        $query .= ' INNER JOIN actions a ON ul.action_id = a.action_id';
        $query .= ' INNER JOIN persons_translation pt ON ul.user_id = pt.person_id';
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $query .= " $join";
            }
        }
        $query .= ' WHERE ul.action_id = ' . $this->getActionId();
        $query .= ' AND pt.content_lang = ' . AmcWm::app()->db->quoteValue(Controller::getContentLanguage());
        $query .= $this->where;
        $count = " SELECT COUNT(*) " . $query;
        $query .= " GROUP BY pt.person_id";
        $select = $select . $query;
//        die($select);
        if ($singleRow) {
            $data['records'] = AmcWm::app()->db->createCommand($select)->queryRow();
        } else {
            $data['records'] = AmcWm::app()->db->createCommand($select)->queryAll();
        }
        $pagination = new CPagination($count);
        $pagination->setPageSize(Deskman::REPORTS_PAGE_COUNT);
        $data['pagination'] = $pagination;
        $data['count'] = $count;
        return $data;
    }

    protected function renderResult($data) {
        AmcWm::app()->getController()->render($this->view, $data);
    }

    protected function renderSearchForm() {
        return AmcWm::app()->getController()->renderPartial($this->view . "Form", array(), true);
    }

}
