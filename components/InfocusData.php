<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * InFocusData class, gets Infocus data based on the given id
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class InfocusData extends Dataset {

    /**
     * Infocus id, to get record based on it
     * @var integer
     */
    protected $id;

    /**
     * Counstructor, the content type
     * @param integer $id 
     * @param boolean $autoGenerate if true then call the generate method from counstructor
     * @access public
     * 
     */
    public function __construct($id, $autoGenerate = true) {
        $this->id = (int) $id;
        if ($autoGenerate) {
            $this->generate();
        }
    }

    /**
     * Get infocus id
     * @access public
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Generate infocus data row associated  array that contain's following items:
     * <ul>
     * <li>id: integer, infocus id</li>
     * <li>header: string, infocus title</li>
     * <li>brief: string, infocus brief</li>
     * <li>imageExt: string, infocus image extension</li>
     * <li>banner: string, infocus banner image extension</li>
     * <li>background: string, infocus background image extension</li>
     * <li>bgcolor: string, infocus background color</li>
     * </ul>
     * @access public
     * @return void
     */
    public function generate() {
        $this->setItems();
    }

    /**
     * Set the infocus data row associated 
     * @access private
     * @return void
     */
    protected function setItems() {
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $currentDate = date("Y-m-d H:i:s");
        $cols = $this->generateColumns();
        $wheres = $this->generateWheres();
        $this->query = sprintf("select 
            t.thumb imageExt, 
            t.banner, 
            t.background, 
            t.bgcolor, 
            t.infocus_id,
            tt.header,
            tt.brief 
            $cols    
            from infocus t
            inner join infocus_translation tt on t.infocus_id = tt.infocus_id    
            {$this->joins}
            where t.published = %d
            and t.infocus_id = %d
            and t.publish_date <= '$currentDate'             
            and (t.expire_date >= '$currentDate' or t.expire_date is null)
            and tt.content_lang = %s
            $wheres    
            ",  ActiveRecord::PUBLISHED, 
                $this->id, 
                Yii::app()->db->quoteValue($siteLanguage));
        $this->items = Yii::app()->db->createCommand($this->query)->queryRow();
    }
}

