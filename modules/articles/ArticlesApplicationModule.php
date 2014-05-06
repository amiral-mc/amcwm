<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesApplicationModule, Articles application
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ArticlesApplicationModule extends ApplicationModule {

    /**
     * 
     * @param integer $id
     * @return boolean
     */
    public function checkArticleInTable($id) {
        $id = (int) $id;
        $cuurentTable = $this->getTable();
        $tables = $this->getExtendsTables();
        $table = $cuurentTable;
        foreach ($tables as $articleTable) {
            $found = Yii::app()->db->createCommand("select article_id from {$articleTable} where article_id = {$id}")->queryScalar();
            if ($found) {
                $table = $articleTable;
                break;
            }
        }        
        return ($cuurentTable == $table);
    }

}
