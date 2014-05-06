<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dir_companies_branches".
 *
 * The followings are the available columns in table 'dir_companies_branches':
 * @property string $branch_id
 * @property string $company_id
 * @property string $country
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 *
 * The followings are the available model relations:
 * @property DirCompanies $company
 * @property Countries $country0
 * @property DirCompaniesBranchesTranslation[] $dirCompaniesBranchesTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirCompaniesBranches extends ParentTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DirCompaniesBranches the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dir_companies_branches';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('country', 'required'),
            array('company_id', 'length', 'max' => 10),
            array('country', 'length', 'max' => 2),
            array('email', 'length', 'max' => 65),
            array('email', 'email'),
            array('phone, mobile, fax', 'length', 'max' => 20),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('branch_id, company_id, country, email, phone, mobile, fax', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'company' => array(self::BELONGS_TO, 'DirCompanies', 'company_id'),
            'country' => array(self::BELONGS_TO, 'Countries', 'country'),
            'translationChilds' => array(self::HAS_MANY, 'DirCompaniesBranchesTranslation', 'branch_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'branch_id' => AmcWm::t("msgsbase.core", 'Branch'),
            'company_id' => AmcWm::t("msgsbase.core", 'Company'),
            'country' => AmcWm::t("msgsbase.core", 'Country'),
            'email' => AmcWm::t("msgsbase.core", 'Email'),
            'phone' => AmcWm::t("msgsbase.core", 'Phone'),
            'mobile' => AmcWm::t("msgsbase.core", 'Mobile'),
            'fax' => AmcWm::t("msgsbase.core", 'Fax'),
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
        $criteria->compare('company_id', $this->company_id, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('fax', $this->fax, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}