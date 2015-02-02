<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * AttachmentBehaviors class, adding attachment methods to any model
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class AttachmentBehaviors extends CBehavior {

    /**
     * old attachment list
     * @var array
     */
    protected $oldAttachment = array();

    /**
     *
     * @var array of attachment 
     */
    protected $attachment = array();

    /**
     *
     * @var integer module id  
     */
    private $_moduleId = 0;

    /**
     *
     * @var integer table id  
     */
    private $_tableId = 0;

    /**
     *
     * @var integer referer id  
     */
    private $_refId = 0;

    /**
     *
     * @var ActiveRecord 
     */
    private $_model = null;

    /**
     * Constructor.
     * @param string $module
     * @param ActiveRecord $model
     * @param integer $tableId
     * @param integer $refId
     * @access public
     */
    public function __construct($module, &$model, $tableId, $refId) {
        $moduleData = amcwm::app()->acl->getModule($module);
        if ($moduleData) {
            $this->_moduleId = (int) $moduleData['id'];
            $this->_model = $model;
            $this->_tableId = (int) $tableId;
            $this->_refId = (int) $refId;
            //$this->_model->attachEventHandler("onBeforeValidate", array($this, 'validateAttachment'));
            $this->_model->onBeforeValidate = array($this, 'validateAttachment');
            $this->_model->onAfterSave = array($this, 'saveAttachment');
            $countQuery = "select 
            content_lang 
            , count(t.attach_id) max_attachs 
            from attachment t
            inner join attachment_translation  tt on t.attach_id = tt.attach_id
            where module_id = {$this->_moduleId} and ref_id = {$this->_refId} and table_id = {$this->_tableId}
                group by content_lang order by max_attachs desc limit 1";
            $maxLang = AmcWm::app()->db->createCommand($countQuery)->queryRow();
            $query = "select 
            t.attach_id
            ,content_type
            ,attach_url
            ,title
            ,description
            from attachment t
            inner join attachment_translation  tt on t.attach_id = tt.attach_id
            where module_id = {$this->_moduleId} and ref_id = {$this->_refId} and table_id = {$this->_tableId}
            and content_lang='{$maxLang['content_lang']}'  order by attach_sort asc";
            $rows = AmcWm::app()->db->createCommand($query)->queryAll();
            foreach ($rows as &$row) {
                if ($maxLang['content_lang'] != $model->content_lang) {
                    $row['title'] = "";
                    $row['description'] = "";
                    $query = "select 
                attach_id
                ,title
                ,description
                from attachment_translation
                where attach_id = {$row['attach_id']}
                and   content_lang='{$model->content_lang}'  ";
                    $translated = AmcWm::app()->db->createCommand($query)->queryRow();
                    if ($translated) {
                        $row['title'] = $translated['title'];
                        $row['description'] = $translated['description'];
                    }
                }
                $record = AttachmentTranslation::model()->populateRecord($row);
                if ($record !== null) {
                    $this->attachment[$record->attach_id] = $record;
                }
            }
            $this->oldAttachment = $this->attachment;
        }
    }

    /**
     * Set attachment list
     * @param array $attachment
     */
    public function setAttachmentAttribute() {
        $attachment = AmcWm::app()->request->getParam('AttachmentTranslation');
        $currentAttachment = $this->attachment;
        $this->attachment = array();
        if ($attachment) {
            $sort = 1;
            $new = array();
            foreach ($attachment as $index => $attachmentRow) {
                if ($attachmentRow['attach_id'] && isset($currentAttachment[$attachmentRow['attach_id']])) {
                    $attachmentModel = Attachment::model()->findByPk($attachmentRow['attach_id']);
                    if ($attachmentModel !== null) {
                        $attachmentTranslationModel = AttachmentTranslation::model()->findByPk(array("attach_id" => $attachmentRow['attach_id'], "content_lang" => $this->_model->content_lang));
                        if ($attachmentTranslationModel === null) {
                            $attachmentTranslationModel = new AttachmentTranslation();
                            $attachmentModel->addTranslationChild($attachmentTranslationModel, $this->_model->content_lang);
                        }
                        $keyIndex = $attachmentRow['attach_id'];
                    }
                } else {
                    $attachmentModel = new Attachment;
                    $attachmentTranslationModel = new AttachmentTranslation();
                    $attachmentModel->addTranslationChild($attachmentTranslationModel, $this->_model->content_lang);
                    $keyIndex = "new_{$sort}";
                }
                $attachmentModel->ref_id = $this->_refId;
                $attachmentModel->table_id = $this->_tableId;
                $attachmentModel->module_id = $this->_moduleId;
                $attachmentTranslationModel->title = $attachmentRow['title'];
                $attachmentTranslationModel->description = $attachmentRow['description'];
                $attachmentTranslationModel->content_type = $attachmentRow['content_type'];
                $attachmentTranslationModel->attach_url = $attachmentRow['attach_url'];
                $attachmentTranslationModel->content_lang = $this->_model->content_lang;
                $attachmentTranslationModel->attach_sort = $sort;
                $this->attachment[$keyIndex] = $attachmentTranslationModel;
                $new[$keyIndex] = $attachmentRow;
                $sort++;
            }
        }
    }

    /**
     * Get attachment list
     * return array
     */
    public function getAttachment() {
        return $this->attachment;
    }

    /**
     * save attachment list
     */
    public function deleteAttachment() {
        $primaryKey = $this->_model->primaryKey;
        if (is_array($primaryKey)) {
            $primaryKeys = array_keys($primaryKey);
            $id = $primaryKey[$primaryKeys[0]];
        } else {
            $id = $this->_model->primaryKey;
        }
        $query = "delete attachment, attachment_translation from attachment, attachment_translation  where attachment.attach_id =attachment_translation.attach_id and module_id = {$this->_moduleId} and ref_id = {$id} and table_id = {$this->_tableId}";
        AmcWm::app()->db->createCommand($query)->execute();        
    }
    /**
     * save attachment list
     */
    public function saveAttachment() {
        $saved = true;
        $primaryKey = $this->_model->primaryKey;
        if (is_array($primaryKey)) {
            $primaryKeys = array_keys($primaryKey);
            $id = $primaryKey[$primaryKeys[0]];
        } else {
            $id = $this->_model->primaryKey;
        }
        if ($id) {
            foreach ($this->attachment as $attachmentTranslation) {
                $attachment = $attachmentTranslation->getParentContent();
                if (!$attachment->ref_id) {
                    $attachment->ref_id = $id;
                }
                $attachmentTranslation->content_lang = $this->_model->content_lang;
                $attachment->attach_url = $attachmentTranslation->attach_url;
                $attachment->attach_sort = $attachmentTranslation->attach_sort;
                $attachment->content_type = $attachmentTranslation->content_type;
                $saved &= $attachment->save();
                $attachmentTranslation->attach_id = $attachment->attach_id;
                $saved &= $attachmentTranslation->save();
            }
            foreach ($this->oldAttachment as $oldAttachment) {
                if (!isset($this->attachment[$oldAttachment->attach_id])) {
                    $oldAttachment->getParentContent()->delete();
                }
            }
//        foreach ($this->attachment as $attachmentTranslation) {
//            $attachment = $attachment->getParentContent();
//            echo "{$attachmentTranslation->attach_id}|{$attachmentTranslation->attach_sort}|{$attachmentm->attach_sort}<br />";
//        }
        }
        return $saved;
    }

    /**
     * validate attachment list
     * @param CModelEvent $event
     * @param array $params
     */
    public function validateAttachment($event) {
        $this->setAttachmentAttribute();
        $validate = true;
        if (count($this->attachment)) {
            foreach ($this->attachment as $attachmentTranslation) {
                $validate &= $attachmentTranslation->validate();
            }
        }
        $event->isValid = $validate;
        return $validate;
    }

}
