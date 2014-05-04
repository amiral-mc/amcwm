<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @todo link event with multimedia (images and videos)
 */

/**
 * EventsApplicationModule, section application
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class EventsApplicationModule extends ApplicationModule {

    protected $sendEmailAfterInsert = true;

    public function getSendEmailAfterInsert() {
        return $this->sendEmailAfterInsert;
    }

    public function setSendEmailAfterInsert($status) {
        $this->sendEmailAfterInsert = $status;
    }

}
