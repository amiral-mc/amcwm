<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ChildTranslatedActiveRecord class,  Used for generate active record for child translation table records liks articles_translation
 * @package AmcWebManager
 * @subpackage Data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ChildTranslatedActiveRecord extends ActiveRecord {

    /**
     * Parent relation name in relation array
     * Default equal to parentContent
     * @var string 
     */
    protected $parentRelationName = "parentContent";

    /**
     * Gets parent relation name in relation array, default equal to parentContent
     * @access  public
     * @return string
     */
    public function getParentRelationName() {
        return $this->parentRelationName;
    }

    /**
     * Gets the parent active record content
     * @access public
     * @return ActiveRecord
     */
    public function getParentContent() {
        $parentRelationName = $this->parentRelationName;
        $parentContent = null;
        if (isset($this->$parentRelationName)) {
            $parentContent = $this->$parentRelationName;
        }
        return $parentContent;
    }

    /**
     * Deletes the row corresponding to this active record
     * @access public
     * @return boolean whether the deletion is successful.
     * @throws CException if the record is new
     * 
     */
    public function delete() {
        return parent::delete();
    }

    /**
     * 
     * Get the compiste primary key value for the given id
     * Id send as $id = pk1, pk2
     * @param string $id
     * @access public
     * @return array
     */
    public static function getCompositeValues($id) {
        $idComposite = explode(",", $id);
        $keyCount = count($idComposite);
        $pkValues = array('id' => 0, "lang" => Controller::getContentLanguage());
        switch ($keyCount) {
            case 1;
                $pkValues['id'] = (int) $idComposite[0];
                break;
            case 2;
                $idComposite[1] = trim($idComposite[1]);
                $pkValues['id'] = (int) $idComposite[0];
                if ($idComposite[1]) {
                    $pkValues['lang'] = $idComposite[1];
                }
                break;
        }
        return $pkValues;
    }

    /**
     * This method is invoked after each record has been saved
     * @access protected
     * @return boolean
     */
    protected function beforeSave() {
        $pk = $this->parentContent->tableSchema->primaryKey;
        if ($this->isNewRecord) {
            $this->setAttribute($pk, $this->parentContent->$pk);
        }
        return parent::beforeSave();
    }       
}
