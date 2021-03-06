<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "persons".
 *
 * The followings are the available columns in table 'persons':
 * @property string $person_id
 * @property string $email
 * @property string $inserted_date
 * @property string $country_code
 * @property string $sex
 * @property string $thumb
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property string $date_of_birth
 *
 * The followings are the available model relations:
 * @property Countries $countryCode
 * @property PersonsTranslation[] $personsTranslations
 * @property SectionsTranslation[] $sectionsTranslations
 * @property Users $users
 * @property Writers $writers
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Persons extends ParentTranslatedActiveRecord {

    /**
     * Setting instance generated from settings.php inside an application module folder
     * @var Settings
     * @var array
     */
    private static $_settings = null;
    public $personImage = null;
    public $toMailList = 0;
    public $dobYear = NULL;
    public $dobMonth = NULL;
    public $dobDay = NULL;

    /**
     * Get articles setting used in the system
     * @return Settings
     * @access public 
     */
    static public function getSettings() {
        if (self::$_settings == null) {
            self::$_settings = new Settings("persons", false);
        }
        return self::$_settings;
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Persons the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'persons';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        $mediaSettings = Persons::getSettings()->mediaSettings;
        $date = date("Y-m-d H:i:s");

        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('country_code, sex', 'required'),
            array('email', 'length', 'max' => 65),
            array('country_code', 'length', 'max' => 2),
            array('sex', 'length', 'max' => 1),
            array('thumb', 'length', 'max' => 4),
            array('phone, mobile, fax', 'length', 'max' => 45),
            array('date_of_birth', 'safe'),
            array('email', 'email', 'checkMX' => false),
            array('toMailList', 'numerical', 'integerOnly' => true),
            //array('email', 'UserExist', 'errorMessage' => 'Email already exist, please choose another email'),
            array('email', 'unique'),
            array('inserted_date', 'default',
                'value' => $date,
                'setOnEmpty' => false, 'on' => 'insert'),
            array('personImage', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']['maxImageSize']),
            array('personImage', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['images']['info'],
                'errorMessage' =>
                array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                    'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
                )
            ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('person_id, email, inserted_date, country_code, sex, thumb, phone, mobile, fax, date_of_birth', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'country' => array(self::BELONGS_TO, 'Countries', 'country_code'),
            'translationChilds' => array(self::HAS_MANY, 'PersonsTranslation', 'person_id', 'index' => 'content_lang'),
            'sections' => array(self::HAS_MANY, 'SectionsTranslation', 'supervisor'),
            'users' => array(self::HAS_ONE, 'Users', 'user_id'),
            'writers' => array(self::HAS_ONE, 'Writers', 'writer_id'),
            'maillists' => array(self::HAS_ONE, 'Maillist', 'person_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'person_id' => AmcWm::t("msgsbase.core", 'Person ID'),
            'email' => AmcWm::t("msgsbase.core", 'Email'),
            'inserted_date' => AmcWm::t("msgsbase.core", 'Inserted Date'),
            'country_code' => AmcWm::t("msgsbase.core", 'Country'),
            'sex' => AmcWm::t("msgsbase.core", 'Sex'),
            'thumb' => AmcWm::t("msgsbase.core", 'Person Image'),
            'personImage' => AmcWm::t("msgsbase.core", 'Person Image'),
            'phone' => AmcWm::t("msgsbase.core", 'Phone'),
            'mobile' => AmcWm::t("msgsbase.core", 'Mobile'),
            'fax' => AmcWm::t("msgsbase.core", 'Fax'),
            'date_of_birth' => AmcWm::t("msgsbase.core", 'Date Of Birth'),
            'toMailList' => AmcWm::t("msgsbase.core", 'Subscribe to our mailing list'),
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

        $criteria->compare('person_id', $this->person_id, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('inserted_date', $this->inserted_date, true);
        $criteria->compare('country_code', $this->country_code, true);
        $criteria->compare('sex', $this->sex, true);
        $criteria->compare('thumb', $this->thumb, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('date_of_birth', $this->date_of_birth, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Get person sex label
     * @access public
     * @return string
     */
    public function getSexLabel() {
        $sexLabels = AmcWm::t("amcCore", 'sexLabels');
        return $sexLabels[$this->sex];
    }

    /**
     * Get supervisors needed for systems
     * @param string $emptyLabel if not equal null then add empty item with the given $emptyLabel
     * @param string $language if not equal null then get supervisors according to the given $language, 
     * @access public 
     * @return array     
     */
    static public function getSupervisorsList($emptyLabel = null, $language = null) {
        if (!$language) {
            $language = Controller::getContentLanguage();
        }
        $query = sprintf("
            select t.person_id, tt.name
            from persons t
            inner join persons_translation tt on t.person_id = tt.person_id
            where content_lang = %s", Yii::app()->db->quoteValue($language));
        $supervisors = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'person', 'name');
        if ($emptyLabel) {
            $supervisors[""] = $emptyLabel;
        }
        return $supervisors;
    }
    
    public function deleteImage($thumb = null) {
        $imageSizesInfo = Persons::getSettings()->mediaPaths;
        if (!$thumb) {
            $thumb = $this->thumb;
        }
        if ($thumb) {
            foreach ($imageSizesInfo as $imageInfo) {
                $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $this->person_id . "." . $thumb;
                if (is_file($imageFile)) {
                    unlink($imageFile);
                }
            }
        }
    }

    /**
     * Save person image
     * @param boolean $deleteImageFile
     */
    public function saveImage($deleteImageFile) {
        $oldThumb = (isset($this->oldAttributes['thumb'])) ? $this->oldAttributes['thumb'] : null;
        $imageSizesInfo = Persons::getSettings()->mediaPaths;
        if ($deleteImageFile) {
            $this->deleteImage($oldThumb);
        } else {
            if ($this->personImage instanceof CUploadedFile) {
                $image = new Image($this->personImage->getTempName());
                foreach ($imageSizesInfo as $imageInfo) {
                    if ($imageInfo['autoSave']) {
                        $imageFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $this->person_id . "." . $this->thumb;
                        $oldThumbFile = str_replace("/", DIRECTORY_SEPARATOR, Yii::app()->basePath . "/../" . $imageInfo['path']) . "/" . $this->person_id . "." . $oldThumb;
                        if ($oldThumb != $this->thumb && $oldThumb && is_file($oldThumbFile)) {
                            unlink($oldThumbFile);
                        }
                        if ($imageInfo['info']['crob']) {
                            $image->resizeCrop($imageInfo['info']['width'], $imageInfo['info']['height'], $imageFile);
                        } else {
                            $image->resize($imageInfo['info']['width'], $imageInfo['info']['height'], Image::RESIZE_BASED_ON_WIDTH, $imageFile);
                        }
                    }
                }
            }
        }
    }

    public function beforeValidate() {
        if ($this->dobYear && $this->dobMonth && $this->dobDay) {
            $this->date_of_birth = "{$this->dobYear}-{$this->dobMonth}-{$this->dobDay}";
        } else {
            $this->date_of_birth = null;
        }
        return parent::beforeValidate();
    }

    protected function afterSave() {
        $maillistSettings = new Settings('maillist', 0);
        $maillistOptions = $maillistSettings->getOptions();
        $enableSubscribe = $maillistOptions['default']['check']['enableSubscribe'];
        if ($enableSubscribe) {
            AmcWm::import('amcwm.modules.maillist.models.*');
        }
        if ($enableSubscribe) {
            $maillist = $this->toMailList;
            if ($maillist && $this->maillists === null) {
                $maillistSubscribe = new Maillist();
                $maillistSubscribe->person_id = $this->person_id;
                $maillistSubscribe->status = 1;
                if ($maillistSubscribe->save()) {
                    $maillistSubscribe->saveAllChannels();
                }
            } else if (!$maillist && $this->maillists !== null) {
                $this->maillists->delete();
            }
        }
        parent::afterSave();
    }
    
       public function afterFind() {
        if ($this->date_of_birth) {
            $dobTime = strtotime($this->date_of_birth);
            $this->dobDay = (int) date('d', $dobTime);
            $this->dobMonth = (int) date('m', $dobTime);
            $this->dobYear = date('Y', $dobTime);            
        }
        $maillistSettings = new Settings('maillist', 0);
        $maillistOptions = $maillistSettings->getOptions();
        $enableSubscribe = $maillistOptions['default']['check']['enableSubscribe'];
        if ($enableSubscribe) {
            AmcWm::import('amcwm.modules.maillist.models.*');
            $this->toMailList = $this->maillists !== null;            
        }
        else{
            $this->toMailList = 0;
        }
        
        parent::afterFind();
    }

}
