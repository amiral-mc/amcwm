<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ArticlesSectionsBreadcrumbs class, generate articles sections breadcrumbs tree 
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ProductsCategoryBreadcrumbs extends BreadcrumbsData {

    /**
     * set Breadcrumbs path for the given $id 
     * @param int $id
     * @access protected
     * @return array     
     */
    protected function setPath($id) {
        $query = sprintf(
                "SELECT s.section_id, s.parent_section, t.section_name
                FROM sections s FORCE INDEX (idx_section_sort)
                JOIN sections_translation t ON s.section_id = t.section_id
                WHERE s.section_id = %d AND t.content_lang = %s limit 0 ,1", $id, Yii::app()->db->quoteValue($this->language));
        $section = Yii::app()->db->createCommand($query)->queryRow();
        if (is_array($section)) {
            $parentRoute = $this->route;
            $parentRoute['id'] = $section['parent_section'];
            $appended = $this->appendToMenuBreadcrumbs($parentRoute);
            if (!$appended) {
                $this->setPath($section['parent_section']);
            }
            $this->path[] = array("label" => $section["section_name"], "url" => array(0 => $this->route['0'], "id" => $section["section_id"], "title" => $section["section_name"]));
        }
    }

}
