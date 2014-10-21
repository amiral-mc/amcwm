<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Reports
 *
 * @author abdallah
 */
abstract class Reports {

    protected $viewResult = true;
    protected $view = '';
    protected $layout = '';
    protected $printMode = false;
    protected $virtualModule = '';
    

    public function __construct() {
        $this->layout = "amcwm.core.backend.views.default.reports.layout";
    }

    abstract protected function getData();

    abstract protected function renderResult($data, $printMode = false);

    public function run() {
        $data = array();
        if ($this->viewResult) {
            $data['records'] = $this->getData();
        }
        $data['viewResult'] = $this->viewResult;
        $this->renderResult($data, $printMode);
    }

}
