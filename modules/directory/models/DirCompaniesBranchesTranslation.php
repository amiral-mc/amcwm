<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dir_companies_branches_translation".
 *
 * The followings are the available columns in table 'dir_companies_branches_translation':
 * @property string $branch_id
 * @property string $content_lang
 * @property string $branch_name
 * @property string $branch_address
 * @property string $city
 *
 * The followings are the available model relations:
 * @property DirCompaniesBranches $branch
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirCompaniesBranchesTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DirCompaniesBranchesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dir_companies_branches_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, branch_name, branch_address', 'required'),
            array('branch_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('branch_name, city', 'length', 'max' => 100),
            array('branch_address', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('branch_id, content_lang, branch_name, branch_address, city', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'DirCompaniesBranches', 'branch_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'branch_id' => AmcWm::t("msgsbase.core", 'Branch'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'branch_name' => AmcWm::t("msgsbase.core", 'Branch Name'),
            'branch_address' => AmcWm::t("msgsbase.core", 'Address'),
            'city' => AmcWm::t("msgsbase.core", 'City'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('branch_id', $this->branch_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('branch_name', $this->branch_name, true);
        $criteria->compare('branch_address', $this->branch_address, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('p.company_id', $this->getParentContent()->company_id);
        $criteria->join .=" inner join dir_companies_branches p on t.branch_id = p.branch_id ";
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}