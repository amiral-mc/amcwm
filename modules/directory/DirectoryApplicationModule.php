<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * DirectoryApplicationModule, directory application
 * 
 * @package AmcWm.modules
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirectoryApplicationModule extends ApplicationModule {

    /**
     * Get Countries list
     * @access public
     * @return array      
     */
    public function getNationality() {
        $allOptions = $this->getSettings("options");        
        $join = null;
        $where = null;
        if($allOptions['system']['integer']['regionFilter']){
            $join = 'inner join regions_has_countries r on r.country_code = c.code';
            $where = " and region_id = " . (int) $allOptions['system']['integer']['regionFilter'];
        }
        
        $query = sprintf("
            select distinct code, country from 
            countries_translation c 
            {$join}
            where content_lang=%s {$where} order by country            
        ", Yii::app()->db->quoteValue(Controller::getContentLanguage()));

        static $countries = NULL;
        if ($countries == null) {
            $countries = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'code', "country");
        }
        return $countries;
    }

}
