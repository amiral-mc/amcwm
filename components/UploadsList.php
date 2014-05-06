<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * UploadsList, Generate uploads data 
 * @package AmcWebManager
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UploadsList extends Dataset {

    /**
     * Setting instance
     * @var Settings
     * @var array
     */
    private static $_settings = null;

    /**
     * Current folder id default null
     * @var integer
     */
    private $_folderId = null;

    /**
     * Files parent root directory
     * @var string
     */
    private $_mediaPath = "";

    /**
     * constructor 
     * You should not call the constructor directly, but instead call the static factory method UploadsList.getInstance().<br />
     * @access private
     */
    public function __construct($folderId = null, $limit = 30) {
        $this->_folderId = (int) $folderId;
        $this->limit = $limit;
        $this->_mediaPath = AmcWm::app()->baseUrl . "/" . self::getSettings()->mediaPaths['files']['path'] . "/";
    }

    /**
     * Get module setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("uploads", false);
        }
        return self::$_settings;
    }

    /**
     *
     * Generate lists
     * Make sure you call the parent implementation so that the method is raised properly.
     * @access public
     * @return void
     */
    public function generate() {
        $this->addOrder("create_date desc");
        parent::generate();
    }

    /**
     * Get user informarion
     * @return array
     */
    public function getUserInfo() {
        return AmcWm::app()->user->getInfo();
    }

    /**
     * Set the attachment items
     * @todo change the way of detect admin / registered users
     * @todo explain the query
     * @access private
     * @return void
     */
    protected function setItems() {
        $userInfo = $this->getUserInfo();
        if ($userInfo) {
            if ($userInfo['role_id'] == amcwm::app()->acl->getRoleId(Acl::REGISTERED_ROLE) || $userInfo['role_id'] == amcwm::app()->acl->getRoleId(Acl::CLIENT_ROLE)) {
                $this->addWhere("t.user_id = " . (int) $userInfo['user_id']);
            }
            $mediaPath = self::getSettings()->getMediaPaths();
            $siteLanguage = Yii::app()->user->getCurrentLanguage();
            $orders = $this->generateOrders();
            $cols = $this->generateColumns();
            if ($this->_folderId === null) {
                $wheres = "where t.folder_id is null ";
            } else if ($this->_folderId === 0) {
                $wheres = "where 1 ";
            } else {
                $wheres = "where t.folder_id = {$this->_folderId} ";
            }
            $wheres .= $this->generateWheres();
            $limit = null;
            if ($this->limit) {
                $limit = "LIMIT {$this->fromRecord} , {$this->limit}";
            }
            $countQuery = sprintf("SELECT  count(*)  FROM files t  {$this->joins}  $wheres  $orders");
            $this->query = sprintf("SELECT 
            t.file_id
            ,t.ext
            ,t.file
            ,t.content_type
            ,t.folder_id     {$cols}
            FROM files t
            {$this->joins}
            $wheres
            $orders
            $limit
            ");
            $dataset = Yii::app()->db->createCommand($this->query)->queryAll();
            $index = -1;
            foreach ($dataset As $row) {
                if ($this->recordIdAsKey) {
                    $index = $row['file_id'];
                } else {
                    $index++;
                }
                $this->items[$index]['id'] = $row["file_id"];
                $this->items[$index]['title'] = "{$row["file"]}.{$row["ext"]}";
                $this->items[$index]['type'] = $row["content_type"];
                $this->items[$index]['url'] = $this->_mediaPath . $row["file_id"] . "." . $row["ext"];
                foreach ($this->cols as $colIndex => $col) {
                    $this->items[$index][$colIndex] = $row[$colIndex];
                }
            }
            $this->count = Yii::app()->db->createCommand($countQuery)->queryScalar();
        }
    }

}