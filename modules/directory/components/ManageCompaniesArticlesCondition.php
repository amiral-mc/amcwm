<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * ManageCompaniesArticlesCondition class, manage companies condtion
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ManageCompaniesArticlesCondition implements IManageContentCondition {

    /**
     * get company id for the active user
     * @return integer
     */
    protected function getCompanyId() {
        $userId = (int) Yii::app()->user->getId();
        return AmcWm::app()->db->createCommand("select company_id from dir_companies where user_id = {$userId}")->queryScalar();
    }

    /**
     * Get list condtions for members
     */
    public function getListCondition() {
        if (!AmcWm::app()->user->isGuest) {
            $companyId = $this->getCompanyId();
            if ($companyId) {
                return "dir_companies_articles.company_id = {$companyId}";
            } else {
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
            $companyId = $this->getCompanyId();
            $id = (int) $record->article_id;
            if (!$record->isNewRecord) {
                if ($id) {
                    $allow = AmcWm::app()->db->createCommand("select article_id from dir_companies_articles where company_id = {$companyId} and article_id = {$id}")->queryScalar();
                }
            } else {
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
            $companyId = $this->getCompanyId();
            $id = (int) $record->article_id;
            if ($record->isNewRecord) {
                AmcWm::app()->db->createCommand("insert into dir_companies_articles(company_id, article_id) values ({$companyId}, {$id})")->execute();
            }
        }
    }

}