<?php

class DefaultController extends AmcDirectoryController {   
      /**
     * Check related 
     */
    protected function checkRelated($id) {
        $id = (int) $id;
        $count = AmcWm::app()->dbReports->createCommand("select count(*) from vessels_destination where agency = {$id}")->queryScalar();
        return $count;
    }

}
