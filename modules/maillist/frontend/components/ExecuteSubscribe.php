<?php
AmcWm::import('amcwm.modules.maillist.models.*');
//AmcWm::import("amcwm.modules.maillist.frontend.models.*");

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ExecuteSubscribe, draw sunscribe widget form
 * @package AmcWebManager 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ExecuteSubscribe extends ExecuteWidget {      
    /**
     * 
     * @var Maillist
     */
    private $_model = null;
   
    /**
     * prepare widget properties
     */
    protected function prepareProperties() {
       if($this->_model == null){
           $this->_model = new MaillistUsers;
       } 
       $this->setProperty('model', $this->_model);
    }

}

