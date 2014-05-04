<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * DbCache class file
 * @author Amiral Management Corporation
 * @version 1.0
 */

class DbCache extends CDbCache {

    /**
     * The Sqllite db file
     * @var string 
     */
    public $cacheDbFile = "";

    /**
     * @return CDbConnection the DB connection instance
     * @throws CException if {@link connectionID} does not point to a valid application component.
     */
    public function init() {
        if (!$this->cacheDbFile) {
            $this->cacheDbFile = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR . 'cache-' . Yii::getVersion() . '.db';
        }
        $this->setDbConnection(new CDbConnection('sqlite:' . $this->cacheDbFile));
        parent::init();
    }

}
