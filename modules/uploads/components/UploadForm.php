<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class UploadForm extends CFormModel {

    /**
     * upload file
     * @var CUploadedFile 
     */
    public $file;

    /**
     * uploaded file info
     * @var array 
     */
    protected $uploadedFileInfo = array();

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        $mediaSettings = AmcWm::app()->appModule->mediaPaths;
        return array(
            // username and password are required
            array('file', 'required'),
            array('file', 'file', 'types' => $mediaSettings['files']['info']['extensions'], 'allowEmpty' => false, 'maxSize' => $mediaSettings['files']['info']['maxSize']),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'file' => AmcWm::t("msgsbase.core", '_file_'),
        );
    }

    /**
     * save uploaded file
     */
    public function saveFile($folderId = null) {
        $opdata = (int) (Yii::app()->request->getParam('op')=="rte")?true:false;
        $folderId = (int) $folderId;
        $types['jpg'] = AttachmentList::IMAGE;
        $types['jepg'] = AttachmentList::IMAGE;
        $types['gif'] = AttachmentList::IMAGE;
        $types['png'] = AttachmentList::IMAGE;
        $types['flv'] = AttachmentList::INTERNAL_VIDEO;
        $types['wmv'] = AttachmentList::INTERNAL_VIDEO;
        if ($this->validate()) {
            $mediaSettings = AmcWm::app()->appModule->mediaPaths;
            $ext = strtolower($this->file->getExtensionName());
            if (isset($types[$ext])) {
                $type = $types[$ext];
            } else {
                $type = AttachmentList::LINK;
            }
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $fileName = $this->file->getName();
                $userInfo = AmcWm::app()->user->getInfo();
                $sql = sprintf(
                        "insert into files 
                            (user_id, ext, create_date, file, content_type, folder_id, rte) values (%d, %s, %s , %s , %s , %s, %d)", 
                        $userInfo['user_id'] ,
                        AmcWm::app()->db->quoteValue($ext), 
                        AmcWm::app()->db->quoteValue(date("Y-m-d H:i:s")), 
                        AmcWm::app()->db->quoteValue(str_replace(".{$ext}", "", $fileName)), 
                        AmcWm::app()->db->quoteValue($type), 
                        ($folderId) ? $folderId : 'null',
                        $opdata
                );
                $ok = AmcWm::app()->db->createCommand($sql)->execute();
                if ($ok) {
                    $saved = $this->file->saveAs(AmcWm::app()->basePath . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $mediaSettings['files']['path'] . DIRECTORY_SEPARATOR . AmcWm::app()->db->getLastInsertID() . ".{$ext}");
                    if ($saved) {
                        $ok = true;                        
                        $mediaSettings = AmcWm::app()->appModule->mediaPaths;                        
                        $this->uploadedFileInfo['type'] = $type;
                        $this->uploadedFileInfo['title'] = "{$fileName}.{$ext}";
                        $this->uploadedFileInfo['id'] = AmcWm::app()->db->lastInsertID;
                        $this->uploadedFileInfo['url'] = AmcWm::app()->baseUrl . "/{$mediaSettings['files']['path']}/{$this->uploadedFileInfo['id']}.$ext" ;
                        $transaction->commit();
                    } else {
                        $ok = false;
                        $transaction->rollback();
                    }
                }
            } catch (CDbException $e) {
                $ok = false;
                $transaction->rollback();
            }
            if (!$ok) {
                $this->addError("file", AmcWm::t("msgsbase.core", '_cannot_upload_file_'));
            }
            return $ok;
        }
    }
    
    /**
     * Get uploaded file info
     * @return array
     */
    public function getuploadedFileInfo(){
        return $this->uploadedFileInfo;
    }

}
