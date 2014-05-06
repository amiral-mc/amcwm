<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * ActiveRecord is the base class for classes representing relational data.
 * @package AmcWm.data
 * @author Amiral Management Corporation
 * @version 1.0
 */
class ActiveRecord extends CActiveRecord {

    /**
     * published flag
     */
    const PUBLISHED = 1;

    /**
     * Unpublished flag
     */
    const UNPUBLISHED = 0;

    /**
     *
     * @var string 
     */
    public $displayTitle = null;

    /**
     * Old attributes values
     * @var array 
     */
    public $oldAttributes = array();

    /**
     * AmcWm module settings array
     * @var Settings  
     */
    protected $moduleSettings = array();

    /**
     * Labels array(
     * @var Settings  
     */
    protected $labels = array();

    /**
     * Constructor.
     * @access public
     * @param string $scenario scenario name. See {@link CModel::scenario} for more details about this parameter.
     */
    public function __construct($scenario = 'insert') {
        $this->labels = $this->attributeLabels();
        parent::__construct($scenario);
        if ($scenario === null) {
            $this->afterConstruct();
        }
    }

    /**
     * This method is invoked before deleting a record.
     * The default implementation raises the {@link onBeforeDelete} event.
     * You may override this method to do any preparation work for record deletion.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the record should be deleted. Defaults to true.
     */
    protected function beforeDelete() {
        $isSystem = isset($this->is_system) && $this->is_system;
        $deleted = false;
        if (!$isSystem) {
            $deleted = parent::beforeDelete();
        }
        return $deleted;
    }

    /**
     * Get online atributes 
     * @param string $attribute
     * @return array
     */
    public function getOnlineAttribute($attribute) {
        $onlineAttributes = $this->getOnlineAttributes();
        if (isset($onlineAttributes[$attribute])) {
            return $onlineAttributes[$attribute];
        }
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
        $this->oldAttributes = $this->getOnlineAttributes();
        return parent::beforeSave();
    }

    /**
     * This method is invoked after saving a record successfully.
     * The default implementation raises the {@link onAfterSave} event.
     * You may override this method to do postprocessing after record saving.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterSave() {
        if ($this->hasEventHandler('onAfterSave')){
            $params['model'] = $this;
            $this->onAfterSave(new CEvent($this, $params));
        }
    }

    /**
     * This method is invoked before publish a record .
     * The default implementation raises the {@link onBeforePublish} event.
     * You may override this method to do any preparation work for record publishing.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforePublish() {
        $event = new CModelEvent($this);
        $this->onBeforePublish($event);
        return $event->isValid;
    }

    /**
     * This method is invoked after publishing a record successfully.
     * The default implementation raises the {@link onAfterPublish} event.
     * You may override this method to do postprocessing after record publishing.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterPublish() {
        $this->onAfterPublish(new CEvent($this));
    }

    /**
     * This event is raised before the record is published.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link save()} process will be stopped.
     * @param CModelEvent $event the event parameter
     */
    public function onBeforePublish($event) {
        $this->raiseEvent("onBeforePublish", $event);
    }

    /**
     * This event is raised after the record is published.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link save()} process will be stopped.
     * @param CModelEvent $event the event parameter
     */
    public function onAfterPublish($event) {
        $this->raiseEvent("onAfterPublish", $event);
    }

    /**
     * publish the current recored
     * @param int $published
     * @access public
     * @return boolean
     */
    public function publish($published = PUBLISHED) {
        $ok = false;
        if ($this->beforePublish() && !$this->isNewRecord && isset($this->published)) {
            $this->attributes = array("published" => $published);
            $ok = $this->save();
            $this->afterPublish();
        }
        return $ok;
    }

    /**
     * 
     * @todo change it to query to get the data attribute from the table
     * Get online attributes from table
     * @return array 
     * @access public
     */
    public function getOnlineAttributes() {
        $attributes = array();
        if (!$this->isNewRecord) {
            $onlineModel = $this->findByPk($this->primaryKey);
            if ($onlineModel !== null) {
                $attributes = $onlineModel->attributes;
            }
        }
        return $attributes;
    }

    /**
     * Sets the database connection used by active record.
     * By default, the "db" application component is used as the database connection.
     * You may override this method if you want to use a different database connection.
     * @return void
     * 
     */
    public static function setDbConnection($db) {
        self::$db = Yii::app()->getComponent($db);
    }

    /**
     * Get active record class name
     * @access public
     * @return string
     */
    public function getClassName() {
        return get_class($this);
    }

    /**
     * 
     * @param string $attribute
     * @param array $params
     * @access public
     */
    public function isArray($attribute, $params) {
        $allowEmpty = false;
        if (array_key_exists("allowEmpty", $params)) {
            $allowEmpty = $params['allowEmpty'];
        }
        if (!is_array($this->$attribute) && !$allowEmpty) {
            $this->addError($attribute, AmcWm::t('amcCore', "{attribute} must be array.", array("{attribute}" => $this->getAttributeLabel($attribute))));
        }
    }

    /**
     * Set setting attached to this model
     * @param array $settings
     */
    public function setModuleSettings($settings) {
        $this->moduleSettings = $settings;
    }

    /**
     * get setting attached to this model
     * @param array $settings
     */
    public function getModuleSettings() {
        if (!$this->moduleSettings) {
            $this->moduleSettings = AmcWm::app()->getController()->module->appModule->settingsObject();
        }
        return $this->moduleSettings;
    }

    /**
     * Performs the validation.
     *
     * This method executes the validation rules as declared in {@link rules}.
     * Only the rules applicable to the current {@link scenario} will be executed.
     * A rule is considered applicable to a scenario if its 'on' option is not set
     * or contains the scenario.
     *
     * Errors found during the validation can be retrieved via {@link getErrors}.
     *
     * @param array $attributes list of attributes that should be validated. Defaults to null,
     * meaning any attribute listed in the applicable validation rules should be
     * validated. If this parameter is given as a list of attributes, only
     * the listed attributes will be validated.
     * @param boolean $clearErrors whether to call {@link clearErrors} before performing validation
     * @return boolean whether the validation is successful without any error.
     * @see beforeValidate
     * @see afterValidate
     */
    public function validate($attributes = null, $clearErrors = true) {
        $validate = parent::validate($attributes, $clearErrors);
        return $this->validateOthers() && $validate;
    }

    /**
     * This method is invoked before validation starts.
     * The default implementation calls {@link onBeforeValidate} to raise an event.
     * You may override this method to do preliminary checks before validation.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return boolean whether validation should be executed. Defaults to true.
     * If false is returned, the validation will stop and the model is considered invalid.
     */
    protected function validateOthers() {
        $params['model'] = $this;
        $event = new CModelEvent($this, $params);
        $this->onValidateOthers($event);
        return $event->isValid;
    }

    /**
     * This event is raised before the validation is performed.
     * @param CModelEvent $event the event parameter
     */
    public function onValidateOthers($event) {
        $this->raiseEvent('onValidateOthers', $event);
    }

    /**
     * Check if the given value is empty or not
     * @param mixed $value
     * @param boolean $trim
     * @return boolean
     */
    protected function isEmpty($value, $trim = true) {
        return $value === null || $value === array() || $value === '' || $trim && is_scalar($value) && trim($value) === '';
    }

    /**
     * Check table integrity in delete process
     * @return boolean
     */
    public function integrityCheck() {
        return false;
    }

    /**
     * set label for the given $attribute
     * @param string $attribute the attribute name
     * @param string $label the attribute label
     */
    public function setAttributeLabel($attribute, $label) {
        $this->labels[$attribute] = $label;
    }

    /**
     * Returns the text label for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute label
     * @see generateAttributeLabel
     * @see attributeLabels
     */
    public function getAttributeLabel($attribute) {
        if (isset($this->labels[$attribute]))
            return $this->labels[$attribute];
        else
            return $this->generateAttributeLabel($attribute);
    }  

}
