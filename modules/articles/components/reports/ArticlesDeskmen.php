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
class ArticlesDeskmen extends Deskmen {

    protected $logTable = 'articles_log';

    public function __construct() {
        parent::__construct();
    }

    protected function setCols() {
        $this->cols = array(
            'create_date' => 'create_date',
            'deskman' => 'name',
            'count' => 'count(*)',
            'published' => 'count(case published when 1 then 1 else null end)',
        );
    }

    protected function setContentTable() {
        $this->contentTable = array('table' => 'articles', 'pk' => 'article_id');
    }

    protected function setJoin() {
        $this->joins = array();
    }

}
