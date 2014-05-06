<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RssSections {

    static $RSS_ROUTE = '/rss/default/index';

    /**
     * @return array()
     */
    public static function DrowSections() {        
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $query = sprintf("select t.section_id, tt.section_name from sections t 
                    inner join sections_translation tt on t.section_id = tt.section_id            
                    where published = %d and parent_section is null
                   and tt.content_lang = %s
                    order by " . SectionsData::getDefaultSortOrder(), 
                ActiveRecord::PUBLISHED, 
                Yii::app()->db->quoteValue($siteLanguage));
        $sections = Yii::app()->db->createCommand($query)->queryAll();
//        $out = '<ul>' . PHP_EOL;
//        foreach ($sections AS $section){
//            $out .= '<li>' . Html::link($section['section_name'], array(self::RSS_ROUTE, 'sectionId' => $section['section_id'])) . '</li>';
//        }
//        $out .= '</ul>' . PHP_EOL;
//        
        return $sections;
    }

}

?>
