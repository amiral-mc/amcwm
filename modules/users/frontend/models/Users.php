<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property string $user_id
 * @property string $username
 * @property integer $role_id
 * @property string $passwd
 * @property integer $published
 * @property integer $is_system
 *
 * The followings are the available model relations:
 * @property Comments[] $comments
 * @property Images[] $images
 * @property Persons $person
 * @property Roles $role
 * @property UsersArticles[] $usersArticles
 * @property ResetPasswods[] $resetPasswods
 * 
 * @property UsersAccessRights[] $usersAccessRights
 * @property UsersLog[] $usersLogs
 * @property Videos[] $videoses
 * @property Voters[] $voters
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Users extends ActiveRecord {

    public $passwdRepeat = NULL;
    public $verifyCode;

    /**
     * Returns the static model of the specified AR class.
     * @return Users the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, username, passwd', 'required'),
            array('passwdRepeat', 'required', 'on' => 'register'),
            array('passwdRepeat', 'compare', 'compareAttribute' => 'passwd', 'operator' => '=', 'on' => 'register'),
            array('role_id, published, is_system', 'numerical', 'integerOnly' => true),
            array('user_id', 'length', 'max' => 10),
            array('username', 'length', 'max' => 65, 'min' => 4),
            array('username', 'UserCharacters'),
            array('username', 'UserExist', 'errorMessage' => 'Username already exist, please choose another username'),
            array('passwd', 'length', 'max' => 32, 'min' => 8),
            //array('role_id', 'VaildateSystemUser', 'on' => 'update' ,'errorMessage'=>AmcWm::t("msgsbase.core",'Can not change system user')),
            array('role_id', 'VaildateSystemUser', 'on' => 'update', 'errorMessage' => AmcWm::t("msgsbase.core", 'Can not change system user')),
            array('is_system', 'default',
                'value' => new CDbExpression('0'),
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, username, role_id, passwd, published, is_system', 'safe', 'on' => 'search'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'register'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'comments' => array(self::HAS_MANY, 'Comments', 'user_id'),
            'images' => array(self::HAS_MANY, 'Images', 'user_id'),
            'person' => array(self::BELONGS_TO, 'Persons', 'user_id'),
            'role' => array(self::BELONGS_TO, 'Roles', 'role_id'),
            'resetPasswods' => array(self::HAS_MANY, 'ResetPasswods', 'user_id'),
            'usersArticles' => array(self::HAS_MANY, 'UsersArticles', 'user_id'),
            'usersAccessRights' => array(self::HAS_MANY, 'UsersAccessRights', 'user_id'),
            'usersLogs' => array(self::HAS_MANY, 'UsersLog', 'user_id'),
            'videoses' => array(self::HAS_MANY, 'Videos', 'user_id'),
            'voters' => array(self::HAS_MANY, 'Voters', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => 'User',
            'username' => AmcWm::t("msgsbase.core", 'Username'),
            'role_id' => AmcWm::t("msgsbase.core", 'Role'),
            'passwd' => AmcWm::t("msgsbase.core", 'Password'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'passwdRepeat' => AmcWm::t("msgsbase.core", 'Password Repeat'),
            'is_system' => 'Is System User',
            'verifyCode' => AmcWm::t("amcFront", 'Verification Code'),
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

        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('role_id', $this->role_id);
        $criteria->compare('passwd', $this->passwd, true);
        $criteria->compare('published', $this->published);
        $criteria->compare('is_system', $this->is_system);
        $criteria->compare('name', $this->person->name);
        $criteria->compare('email', $this->person->email);
        $criteria->compare('content_lang', $this->person->content_lang);
        $criteria->with = array(
            'person' => array(
                'together' => true,
                'on' => 'person.person_id = t.user_id',
                'joinType' => 'INNER JOIN',
            ),
        );
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    /**
     * has reset key
     * @return boolean
     */
    public function hasResetKey($key) {
        $resetAttributes = array(
            'user_id' => $this->user_id,
            'reset_date' => date("Y-m-d"),
            'reset_key' => $key
        );
        $reset = ResetPasswods::model()->findByAttributes($resetAttributes);
        return $reset !== null;
    }

    /**
     * 
     */
    public function resetEmailConfig(){
        
    }
    /**
     * Generate reset key
     * @return null|array
     */
    public function generateResetKey() {
        $reset = new ResetPasswods();
        $resetAttributes = array(
            'user_id' => $this->user_id,
            'reset_date' => date("Y-m-d"),
            'reset_key' => sprintf('%x', crc32(uniqid(md5("{$this->person->email}{$this->user_id}{$reset->reset_date}") . ".", true)))
        );
        $reset->attributes = $resetAttributes;        
        if ($reset->validate()) {
            $reset->save();            
        }
        else{
            $resetAttributes = null;
        }
        return $resetAttributes;
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            $userParams = Yii::app()->request->getParam('Users');
            if (isset($userParams['passwd']) && trim($userParams['passwd']) != '') {
                $this->setAttribute('passwd', md5($this->passwd));
            }
        }
        return true;
    }
}
