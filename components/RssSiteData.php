<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * RssSiteData class, Gets the contents "articles / videos / images" to displayed in rss feeds
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class RssSiteData extends ArticlesListData {
    /**
     * Full story type, display the header and the content, 
     */
    const FULL_STORY = 3;
    /**
     * Short story type, display the header and brief generated from content, 
     */
    const SHORT_STORY = 2;
    /**
     * Heading story type, display the content header only, 
     */
    const HEADING_STORY = 1;
    /**
     * Current story type, the could be one of the following "Full Story , Short Story or Heading Srory"
     * @var integer 
     */
    protected $storyType = 2;
    
      /**
     * Router for viewing content details
     * @var string 
     */
    protected $route = '/articles/default/view';

    /**
     * Set storytype 
     * @param integer $storyType
     * @access public
     */
    public function setStoryType($storyType) {
        $this->storyType = $storyType;
    }
}