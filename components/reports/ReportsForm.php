<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReportsForm
 *
 * @author abdallah
 */
abstract class ReportsForm extends Reports {

    abstract protected function renderSearchForm();

    public function run() {

        $this->viewResult = AmcWm::app()->request->getParam('result');
        $formOutput = $this->renderSearchForm();
        $data = array();
        if ($this->viewResult) {
            $data = $this->getData();
//            $data['records'] = $this->getData();
        }
        $data['viewResult'] = $this->viewResult;
        $data['formOutput'] = $formOutput;
        $this->renderResult($data);
    }
    
    protected function getActionId() {
        return Data::getInstance()->getActionId(AmcWm::app()->controller->getModule()->appModule->currentVirtual, 'default');
    }

}
