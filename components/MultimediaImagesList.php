<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * MultimediaImagesList class,  Gets the images list
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MultimediaImagesList extends SiteData {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Counstructor, default content type is video
     * If the constructor is overridden, make sure the parent implementation is invoked.     
     * @access public
     */
    public function __construct() {
        $this->moduleName = "images";
        $this->route = '/multimedia/images/index';
        $this->mediaPath = Yii::app()->baseUrl . "/" . self::getSettings()->mediaPaths['images']['path'];
        $this->type = self::IAMGE_TYPE;
    }

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("multimedia", false);
        }
        return self::$_settings;
    }

    /**
     * Generate the videos list array, each item is associated  array
     * @access public
     * @return void
     */
    public function generate() {
        $sorting = self::getSettings()->getTablesSoringOrders();

        if (!count($this->orders)) {
            if (isset($sorting['images'])) {
                $this->addOrder("{$sorting['images']['sortField']} {$sorting['images']['order']}");
            } else {
                $this->addOrder("t.publish_date desc");
            }
        }

        $this->setItems();
    }

    /**
     * Set the videos array list    
     * @todo explain the query
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $currentDate = date("Y-m-d H:i:s");

        $orders = $this->generateOrders();
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("SELECT sql_calc_found_rows 
            tt.image_header 
            ,t.image_id
            ,t.gallery_id            
            ,t.ext
            {$cols}
            from images t 
            inner join images_translation tt on tt.image_id = t.image_id
            inner join galleries g on g.gallery_id = t.gallery_id             
            {$this->joins}
            where tt.content_lang = %s
            and t.is_background = 0 
            and t.published = %d
            and t.publish_date <= '{$currentDate}'            
            and g.published = %d
            and (t.expire_date  >= '{$currentDate}' or t.expire_date is null)  
            $wheres
            $orders
            LIMIT {$this->fromRecord} , {$this->limit}
            ", Yii::app()->db->quoteValue($siteLanguage), 
                    ActiveRecord::PUBLISHED, 
                    ActiveRecord::PUBLISHED);
        $images = Yii::app()->db->createCommand($this->query)->queryAll();
        $index = -1;
        foreach ($images As $image) {
            if ($this->recordIdAsKey) {
                $index = $image['image_id'];
            } else {
                $index++;
            }

            if ($this->titleLength) {
                $this->items[$index]['title'] = Html::utfSubstring($image["image_header"], 0, $this->titleLength);
            } else {
                $this->items[$index]['title'] = $image["image_header"];
            }

            $this->items[$index]['id'] = $image["image_id"];
            $this->items[$index]['link'] = Html::createUrl($this->getRoute(), array('id' => $image['image_id'], 'title' => $image["image_header"]));
            if (isset($image['ext'])) {
                $imageImage = $this->mediaPath . "/{$image['image_id']}.{$image['ext']}";
                $imageImage = str_replace("{gallery_id}", $image['gallery_id'], $imageImage);
            } else {
                $imageImage = null;
            }
            $this->items[$index]['image'] = $imageImage;
            $this->items[$index]['type'] = $this->type;
            foreach ($this->cols as $colIndex => $col) {
                $this->items[$index][$colIndex] = $image[$colIndex];
            }
        }
        $this->count = Yii::app()->db->createCommand('select found_rows()')->queryScalar();
    }

}