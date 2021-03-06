<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * NewsSliderData class,  Gets the news images to displayed inside slider area.
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class NewsSliderData extends ArticlesListData
{

    /**
     * Constructor, default content type is image
     * If the constructor is overridden, make sure the parent implementation is invoked.
     * @access public
     */
    public function __construct($tables = array(), $period = 0, $limit = 10, $sectionId = null) {
        parent::__construct($tables = array(), $period = 0, $limit = 10, $sectionId = null);
        $this->type = SiteData::IAMGE_TYPE;
        $this->route = '/articles/default/view';
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['slider']['path'] . "/";
    }

    /**
     * Generate the news images list array, each item is associated  array
     * @access public
     * @return array,  dataset of articles.
     */
    public function generate() {
        $this->addOrder("t.create_date desc");
        $this->addWhere("(t.in_slider is not null or t.in_slider <> 0)");
        $this->addColumn("in_slider", "thumb");
        parent::generate();
    }

}
