<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "dir_companies".
 *
 * The followings are the available columns in table 'dir_companies':
 * @property string $company_id
 * @property integer $category_id
 * @property string $nationality
 * @property integer $published
 * @property string $hits
 * @property integer $votes
 * @property integer $in_ticker
 * @property integer $accepted
 * @property integer $registered
 * @property integer $user_id
 * @property double $votes_rate
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string $fax
 * @property string $image_ext
 * @property string $file_ext
 * @property string $create_date
 * @property string $maps
 * @property string $url
 *
 * The followings are the available model relations:
 * @property Countries $nationality0
 * @property DirCategories $category
 * @property Users $user
 * @property DirCompaniesBranches[] $dirCompaniesBranches
 * @property DirCompaniesTranslation[] $dirCompaniesTranslations
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class DirCompanies extends ParentTranslatedActiveRecord {

    public $attachFile;
    public $imageFile;
    public $mapFile;
    public $verifyCode;

    const SUSPENDED = 0;
    const ACCEPTED = 1;
    const DENIED = 2;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DirCompanies the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'dir_companies';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $date = date("Y-m-d H:i:s");
        $settings = AmcWm::app()->appModule->settings;
        $allOptions = $settings['options'];
        $options = null;
        $mediaSettings = $settings['media'];
        $required = "nationality";
        if ($allOptions['system']['check']['requiredCategory']) {
            $required .= ", category_id";
        }
        if ($this->category) {
            $options = CJSON::decode($this->category->settings);
        }
        if (!$options) {
            $options = $allOptions['default'];
        }
        $rules[] = array($required, 'required');
        $rules[] = array('email, category_id', 'required', 'on' => 'subscribe');
        $rules[] = array('user_id, category_id, in_ticker, registered, accepted,  published, votes', 'numerical', 'integerOnly' => true);
        $rules[] = array('votes_rate', 'numerical');
        $rules[] = array('nationality', 'length', 'max' => 2);
        $rules[] = array('hits', 'length', 'max' => 10);
        $rules[] = array('email', 'length', 'max' => 65);
        $rules[] = array('email', 'unique', 'caseSensitive' => false, "allowEmpty" => true, 'skipOnError' => true, 'message' => AmcWm::t('msgsbase.core', 'Email already exist, please choose another email'));
        $rules[] = array('email', 'unique', 'criteria' => array('condition' => "email<>" . $this->dbConnection->quoteValue($this->email)), 'className' => 'amcwm.core.backend.models.Persons', 'caseSensitive' => false, "allowEmpty" => true, 'skipOnError' => true, 'message' => AmcWm::t('msgsbase.core', 'Email already exist, please choose another email'));
        $rules[] = array('url', 'length', 'max' => 100);
        $rules[] = array('url', 'url', 'allowEmpty' => true);
        $rules[] = array('email', 'email');
        $rules[] = array('phone, mobile, fax', 'length', 'max' => 20);
        $rules[] = array('file_ext', 'length', 'max' => 4);
        $rules[] = array('image_ext', 'length', 'max' => 3);
        $rules[] = array('imageFile', 'file', 'types' => $mediaSettings['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']["maxImageSize"]);
        $rules[] = array('imageFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['images']['info'],
            'errorMessage' =>
            array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
            )
        );

        $rules[] = array('mapFile', 'file', 'types' => $mediaSettings['paths']['maps']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['info']["maxImageSize"]);
        $rules[] = array('mapFile', 'ValidateImage', 'checkValues' => $mediaSettings['paths']['maps']['info'],
            'errorMessage' =>
            array('exact' => 'Supported image dimensions between  "{width} x {height}" and "{maxwidth} x {maxheight}"',
                'notexact' => 'Image width must be less than {width}, Image height must be less than {height}',
            )
        );

        $rules[] = array('create_date', 'default',
            'value' => $date,
            'setOnEmpty' => false, 'on' => 'insert');
        $rules[] = array('attachFile', 'file', 'types' => $mediaSettings['paths']['attach']['info']['extensions'], 'allowEmpty' => true, 'maxSize' => $mediaSettings['paths']['attach']['info']['maxSize']);
        $rules[] = array('company_id, category_id, nationality, published, hits, votes, votes_rate, email, phone, mobile, fax, image_ext', 'safe', 'on' => 'search');
        if (!$options['check']['useTicker']) {
            $rules[] = array('in_ticker', 'default',
                'value' => 0,
                'setOnEmpty' => false,);
        }
        $rules[] = array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'subscribe');
        return $rules;
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'countryCode' => array(self::BELONGS_TO, 'Countries', 'nationality'),
            'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
            'category' => array(self::BELONGS_TO, 'DirCategories', 'category_id'),
            'dirCompaniesBranches' => array(self::HAS_MANY, 'DirCompaniesBranches', 'company_id'),
            'translationChilds' => array(self::HAS_MANY, 'DirCompaniesTranslation', 'company_id', "index" => "content_lang"),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'company_id' => AmcWm::t("msgsbase.core", 'ID'),
            'category_id' => AmcWm::t("msgsbase.core", 'Category'),
            'nationality' => AmcWm::t("msgsbase.core", 'Nationality'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'hits' => AmcWm::t("msgsbase.core", 'Hits'),
            'votes' => AmcWm::t("msgsbase.core", 'Votes'),
            'votes_rate' => AmcWm::t("msgsbase.core", 'Votes Rate'),
            'email' => AmcWm::t("msgsbase.core", 'Email'),
            'url' => AmcWm::t("msgsbase.core", 'Website'),
            'phone' => AmcWm::t("msgsbase.core", 'Phone'),
            'mobile' => AmcWm::t("msgsbase.core", 'Mobile'),
            'fax' => AmcWm::t("msgsbase.core", 'Fax'),
            'image_ext' => AmcWm::t("msgsbase.core", 'Image'),
            'imageFile' => AmcWm::t("msgsbase.core", 'Image'),
            'file_ext' => AmcWm::t("msgsbase.core", 'File Extension'),
            'attachFile' => AmcWm::t("msgsbase.core", 'Attach File'),
            'accepted' => AmcWm::t("msgsbase.core", 'Status'),
            'user_id' => AmcWm::t("msgsbase.core", 'User'),
            'in_ticker' => AmcWm::t("msgsbase.core", 'In Ticker'),
            'verifyCode' => AmcWm::t("amcFront", 'Verification Code'),
            'maps' => AmcWm::t("msgsbase.core", 'Directory Maps'),
            'mapFile' => AmcWm::t("msgsbase.core", 'Map File'),
        );
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
        if ($this->registered && !$this->isNewRecord) {
            $accepted = $this->oldAttributes['accepted'];
            if ($accepted != self::ACCEPTED) {
                $this->accepted == (int) ($this->accepted);
                if ($this->accepted == self::ACCEPTED) {
                    $this->published = 1;
                } else {
                    $this->published = 0;
                }
            }
        }
        return $ok;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('company_id', $this->company_id, true);
        $criteria->compare('category_id', $this->category_id, true);
        $criteria->compare('nationality', $this->nationality, true);
        $criteria->compare('published', $this->published, true);
        $criteria->compare('accepted', $this->accepted);
        $criteria->compare('hits', $this->hits, true);
        $criteria->compare('votes', $this->votes);
        $criteria->compare('votes_rate', $this->votes_rate);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('image_ext', $this->image_ext, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Get categories list
     * @return array
     */
    public static function getCategories() {
        $categories = CHtml::listData(Yii::app()->db->createCommand(sprintf("select category_id, category_name from dir_categories_translation where content_lang=%s order by category_name ", Yii::app()->db->quoteValue(Controller::getContentLanguage())))->queryAll(), 'category_id', "category_name");
        return $categories;
    }

    /**
     * Get company status list
     * @return array
     */
    public static function getStatus() {
        $status = array();
        $status[self::SUSPENDED] = AmcWm::t("msgsbase.core", 'Suspended');
        $status[self::ACCEPTED] = AmcWm::t("msgsbase.core", 'Accepted');
        $status[self::DENIED] = AmcWm::t("msgsbase.core", 'Denied');
        return $status;
    }

    public static function getArticles($title = null, $companyId = null) {
        $wheres = array();
        if ($title) {
            $wheres[] = sprintf('tt.article_header like %s', Yii::app()->db->quoteValue("%{$title}%"));
        } else {
            $wheres[] = ' da.company_id = ' . (int) $companyId;
        }

        $wheres[] = 't.published = 1';
        $wheres[] = 'n.article_id is null';
        $wheres[] = sprintf('tt.content_lang = %s', Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        $wheres[] = ' (da.company_id = ' . (int) $companyId . ' OR da.company_id is null)';

        $where = " WHERE " . implode(" AND ", $wheres);

        $query = "SELECT distinct t.article_id id, tt.article_header text
            FROM articles t
            INNER JOIN articles_translation tt ON tt.article_id=t.article_id
            LEFT JOIN news n ON n.article_id=t.article_id
            LEFT JOIN dir_companies_articles da ON da.article_id=t.article_id
            {$where} 
            LIMIT 10";
        $data = Yii::app()->db->createCommand($query)->queryAll();
        return $data;
    }

    public static function setArticles($companyId, $articles = array()) {
        $success = true;
        if (is_array($articles) && isset($articles[0]) && $articles[0] != '') {
            $query = sprintf('DELETE FROM dir_companies_articles WHERE company_id = %d', $companyId);
            Yii::app()->db->createCommand($query)->execute();
            $queries = array();
            $addQuery = 'INSERT INTO dir_companies_articles (article_id, company_id) values ';
            foreach ($articles as $articleId) {
                if ($articleId)
                    $queries[] = sprintf('(%d, %d)', trim($articleId), $companyId);
            }
            if (count($queries)) {
                $q = $addQuery . "\n" . implode(",\n", $queries) . ";";
                $success = Yii::app()->db->createCommand($q)->execute();
            } else {
                $success = false;
            }
        } else {
            $success = false;
        }
        return $success;
    }

    /**
     * Get user informarion
     * @todo change the way of detect admin / registered users
     * @return array
     */
    public function getUserInfo() {
        $info = array();
        if ($this->moduleSettings->options['default']['check']['allowUsersApply'] && !AmcWm::app()->user->isGuest) {
            $userInfo = AmcWm::app()->user->getInfo();
            if ($userInfo['role_id'] == amcwm::app()->acl->getRoleId(Acl::REGISTERED_ROLE) || $userInfo['role_id'] == amcwm::app()->acl->getRoleId(Acl::CLIENT_ROLE)) {
                $info = $userInfo;
            }
        }
        return $info;
    }

}
