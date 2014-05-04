<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Issue {

    private $_issueParamValue = null;
    private $_issue = array('current' => array('issue_id' => null, 'issue_date' => null, 'published' => null), 'lastActive' => array('issue_id' => null, 'issue_date' => null, 'published' => null), 'lastNotActive' => array('issue_id' => null, 'issue_date' => null, 'published' => null));
    /**
     * The Singleton Data instance.
     * @var Issue
     * @static
     * @access private
     */
    private static $_instance = null;

    /**
     * Constructor, this Config implementation is a Singleton.
     * You should not call the constructor directly, but instead call the static Singleton factory method Data.getInstance().<br />

     * @access private
     * @throws Error Error if you call the constructor directly
     */
    private function __construct() {
        $this->_setIssue();
    }

    public function changeIssue() {
        $issue = Yii::app()->request->getParam('issue');
        if ($issue) {
            $cookie = new CHttpCookie('issue', $issue);
            Yii::app()->request->cookies['issue'] = $cookie;
        }
    }

    /**
     * Factory Singleton Data method.
     * @static
     * @var Issue
     * @access public
     * @return Config the Singleton instance of the Config
     */
    public static function &getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getIssues($published = -1) {
        $wherePub = null;
        switch ($published) {
            case -0:
                $wherePub = ' where published = 0 ';
                break;
            case 1:
                $wherePub = ' where published = 1 ';
                break;
        }
        $query = "select * from issues $wherePub order by issue_date desc";
        $issues = Yii::app()->db->createCommand($query)->queryAll();
        return $issues;
    }

    public function getIssuesList($published = -1) {
        $issues = $this->getIssues($published);
        $issuesList = array();
        foreach ($issues as $issue) {
            $issuesList[$issue['issue_id']] = AmctWm::t('amcFront', 'Issue number ({no}) {date}', array('{no}' => $issue['issue_id'], '{date}' => date("m-d-Y", strtotime($issue['issue_date']))));
        }
        return $issuesList;
    }

    public function getIssue() {
        return $this->_issue;
    }

    public function getCurrentIssue($published = false) {
        $issueData = array('issue_id' => null, 'issue_date' => null, 'published' => null);
        if ($this->_issue['current']['issue_id']) {
            if ($published && !$this->_issue['current']['published']) {
                $issueData = $this->_issue['lastActive'];
            } else {
                $issueData = $this->_issue['current'];
            }
        } else {
            if ($published) {
                $issueData = $this->_issue['lastActive'];
            } else {
                $issueData = $this->_issue['lastNotActive'];
            }
        }
        return $issueData;
    }

    public function getCurrentIssueId($published = false) {
        $issueData = $this->getCurrentIssue($published);
        return $issueData['issue_id'];
    }

    public function getIssueValue() {
        return $this->_issueParamValue;
    }

    public function allowManage(){
        $issueData = $this->getCurrentIssue(false);
        $toTime = strtotime(date("Y-m-d 23:59:59")) + 60*60*24;
        $fromTime =  strtotime(date("Y-m-d 00:00:00"));
        $issueTime = strtotime(date($issueData['issue_date']));
        $allow = $issueTime >=$fromTime && $issueTime <=$toTime ;
        return $allow;        
    }
    private function _setLastIssue($published) {
        $wherePub = ($published) ? ' where published = 1 ' : ' where published = 0 ';
        $query = "select * from issues $wherePub order by issue_date desc limit 1";
        $issueData = Yii::app()->db->createCommand($query)->queryRow();
        if (!is_array($issueData)) {
            $issueData = array('issue_id' => null, 'issue_date' => null, 'published' => null);
        }
        return $issueData;
    }

    private function _setIssue() {
        $issue = Yii::app()->request->getParam('issue');
        if (!$issue) {
            if (isset(Yii::app()->request->cookies['issue'])) {
                $issue = Yii::app()->request->cookies['issue']->value;
            }
        }
        $this->_issueParamValue = $issue;
        $wherePub = null;
        $whereNotPub = null;
        if ($issue) {
            $query = 'select * from issues where issue_id = ' . (int) $issue;
            $issueData = Yii::app()->db->createCommand($query)->queryRow();
            if (is_array($issueData)) {
                $this->_issue['current'] = $issueData;
            }
        } else {
            $this->_issue['current'] = array('issue_id' => null, 'issue_date' => null, 'published' => null);
        }
        $this->_issue['lastNotActive'] = $this->_setLastIssue(false);
        $this->_issue['lastActive'] = $this->_setLastIssue(true);
    }

}

?>