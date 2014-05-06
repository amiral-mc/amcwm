<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * WebSiteData class, merge data from SiteData instances and return the final merge data list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class WebSiteData {

    /**
     * Array of SiteData instances
     * @var array 
     */
    protected $dataObjects = array();

    /**
     * Counstructor
     * @access public
     */
    public function __construct() {
    }

    /**
     * Adding Dataset instance to WebSiteData.dataObjects array
     * @param Dataset $object
     * @access public 
     */
    public function setObject(Dataset $object) {
        $this->dataObjects[] = $object;
    }

    /**
     * Merge data from SiteData instances and generate the final data list
     * @param int $limit, the numbers of items to fetch from table 
     * @param int $start, the number of record to start fetching data from each SiteData instance
     * @param int $sectionId  the section id to get contents from, if equal null then we gets contents from all sections     *
     * @access public
     * @return array,  dataset of items list.
     */
    public function generate($limit = 10 , $start = 0, $sectionId = NULL){
        $items = array();
        foreach ($this->dataObjects as $object) {
            $object->setLimit($limit);
            $object->setSectionId($sectionId);
            $object->setFromRecord($start);
            $object->generate();
            $items = array_merge($items, $object->getItems());
        }        
        return $items;
    }

}