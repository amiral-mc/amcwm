<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageArticlesCondition class, manage content condtion
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManageUsersArticlesCondition implements IManageContentCondition {

    /**
     * Get list condtions for members
     */
    public function getListCondition() {
        if (!AmcWm::app()->user->isGuest) {
            $userId = (int) AmcWm::app()->user->getId();
            if ($userId) {
                return "users_articles.user_id = {$userId}";
            }
            else{
                $this->controller->redirect(AmcWm::app()->homeUrl);
            }
        } else {
            $this->controller->redirect(AmcWm::app()->homeUrl);
        }
    }    
    
    /**
     * check if member can edit the given $record
     * @param integer $id
     */
    public function canManage(ActiveRecord &$record) {
        $allow = false;        
        if (!AmcWm::app()->user->isGuest) {
            $userId = (int) AmcWm::app()->user->getId();
            $id = (int) $record->article_id;
            if(!$record->isNewRecord){
                if($id){
                    $allow = AmcWm::app()->db->createCommand("select article_id from users_articles where user_id = {$userId} and article_id = {$id}")->queryScalar();
                }
            }
            else{
                $allow = true;
            }
        }
        if (!$allow) {
            AmcWm::app()->getController()->redirect(AmcWm::app()->homeUrl);
        }
        return $allow;
    }

    /**
     * save date to related tables
     * @param ActiveRecord &$record
     */
    public function saveRelated(ActiveRecord &$record) {
        $allow = $this->canManage($record);
        if ($allow) {
            $userId = (int) AmcWm::app()->user->getId();
            $id = (int) $record->article_id;
            if ($record->isNewRecord) {
                AmcWm::app()->db->createCommand("insert into users_articles(user_id, article_id) values ({$userId}, {$id})")->execute();                
            }
        }
    }

}