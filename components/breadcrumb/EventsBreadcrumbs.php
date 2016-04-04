<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * EventsSectionsBreadcrumbs class,  generate events sections breadcrumbs tree 
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class EventsBreadcrumbs extends BreadcrumbsData {

    /**
     * set Breadcrumbs path for the given $id 
     * @param int $id
     * @access protected
     * @return array     
     */
    protected function setPath($id) {
        if ($id) {
            $this->_getSectionRecursive($id);
        } else {
            $this->path[] = array("label" => AmcWm::t("msgsbase.core", 'Events and Activities'), 'url' => array('/events/default/index'));
        }

        if (isset($this->route['title'])) {
            $this->path[] = array("label" => $this->route['title']);
        }
    }

    private function _getSectionRecursive($sectionId) {
        $query = sprintf(
                "select 
                    s.section_id,
                    s.parent_section,
                    t.section_name
                from sections s force index (idx_section_sort)
                inner join sections_translation t on s.section_id = t.section_id
                where s.section_id = %d and t.content_lang = %s limit 0 ,1", $sectionId, Yii::app()->db->quoteValue($this->language));
        $section = Yii::app()->db->createCommand($query)->queryRow();
        if (is_array($section)) {
            $parentRoute = $this->route;
            $parentRoute['id'] = $section['parent_section'];
            $appended = $this->appendToMenuBreadcrumbs($parentRoute);
            if (!$appended && $section['parent_section']) {
                $this->_getSectionRecursive($section['parent_section']);
            }
            $this->path[] = array("label" => $section["section_name"], "url" => array(0 => $this->route['0'], "id" => $section["section_id"], "title" => $section["section_name"]));
        }
    }

}
