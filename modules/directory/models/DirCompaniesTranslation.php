<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dir_companies_translation".
 *
 * The followings are the available columns in table 'dir_companies_translation':
 * @property string $company_id
 * @property string $content_lang
 * @property string $company_name
 * @property string $company_address
 * @property string $description
 * @property string $city
 * @property string $activity
 * 
 * The followings are the available model relations:
 * @property DirCompanies $company
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 * 
 */
class DirCompaniesTranslation extends ChildTranslatedActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DirCompaniesTranslation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dir_companies_translation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('content_lang, company_name, company_address', 'required'),
            array('company_id', 'length', 'max' => 10),
            array('content_lang', 'length', 'max' => 2),
            array('description', 'safe'),            
            array('company_name, city', 'length', 'max' => 100),
            array('city', 'required', 'on'=>'subscribe'),            
            array('activity', 'length', 'max' => 250),
            array('company_address', 'length', 'max' => 150),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('company_id, content_lang, company_name, company_address, city', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parentContent' => array(self::BELONGS_TO, 'DirCompanies', 'company_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'company_id' => AmcWm::t("msgsbase.core", 'Company'),
            'content_lang' => AmcWm::t("msgsbase.core", 'Content Lang'),
            'company_name' => AmcWm::t("msgsbase.core", 'Name'),
            'description' => AmcWm::t("msgsbase.core", 'Description'),
            'activity' => AmcWm::t("msgsbase.core", 'Company Activity'),
            'company_address' => AmcWm::t("msgsbase.core", 'Address'),
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
        $sort = new CSort();
        $criteria->compare('company_id', $this->company_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('company_address', $this->company_address, true);
        $criteria->compare('description', $this->description, true);        
        $criteria->compare('activity', $this->activity, true);        
        $criteria->compare('p.accepted', $this->getParentContent()->accepted);
        $criteria->compare('p.published', $this->getParentContent()->published);
        $criteria->compare('p.category_id', $this->getParentContent()->category_id);
        $criteria->compare('p.nationality', $this->getParentContent()->nationality);
        $criteria->compare('city', $this->city, true);
        $criteria->join .=" inner join dir_companies p on t.company_id = p.company_id";
        $criteria->addCondition('accepted = ' . DirCompanies::ACCEPTED);
        $sorting = AmcWm::app()->appModule->getTablesSoringOrders();
        $order = "{$sorting['dir_companies']['sortField']} {$sorting['dir_companies']['order']}";
        $sort->defaultOrder = "p.category_id , {$order}";
        
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }
    
    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function requests() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $sort = new CSort();
        $criteria->compare('company_id', $this->company_id, true);
        $criteria->compare('content_lang', $this->content_lang, true);
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('company_address', $this->company_address, true);
        $criteria->compare('description', $this->description, true);        
        $criteria->compare('activity', $this->activity, true);        
        $criteria->compare('p.accepted', $this->getParentContent()->accepted);
        $criteria->compare('p.published', $this->getParentContent()->published);
        $criteria->compare('p.category_id', $this->getParentContent()->category_id);
        $criteria->compare('p.nationality', $this->getParentContent()->nationality);
        $criteria->compare('city', $this->city, true);
        $criteria->join .=" inner join dir_companies p on t.company_id = p.company_id";
        $criteria->addCondition("registered = 1");
        $sort->defaultOrder = "p.accepted, company_id desc";
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }
    
    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {            
       $this->displayTitle = $this->company_name;
        parent::afterFind();
    }
    
      /**
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * You may override this method to do any preparation work for record saving.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave() {
        $ok = parent::beforeSave();
        $this->company_name = trim($this->company_name);
        $this->company_address = trim($this->company_address);
        $this->activity = trim($this->activity);
        $this->city = trim($this->city);
        return $ok;
    }

}