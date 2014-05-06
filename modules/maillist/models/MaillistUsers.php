<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "maillist_users".
 *
 * The followings are the available columns in table 'maillist_users':
 * @property string $user_id
 * @property string $email
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Maillist $user
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class MaillistUsers extends ActiveRecord {

    public $emailRepeat = null;
    public $verifyCode = null;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MaillistUsers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'maillist_users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email', 'required'),
            array('user_id', 'length', 'max' => 10),
            array('email, emailRepeat, name', 'length', 'max' => 100),            
            array('email', 'unique', 'caseSensitive' => false, 'skipOnError' => true, 'message' => AmcWm::t("amcFront", 'This email already exist'), 'on' => 'insert, update'),
            array('emailRepeat', 'required', 'on' => 'unsubscribe'),
            array('emailRepeat', 'compare', 'compareAttribute' => 'email', 'operator' => '=', 'on' => 'unsubscribe'),
            array('email', 'email', 'checkMX' => false),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, email, name', 'safe', 'on' => 'search'),            
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'unsubscribe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::BELONGS_TO, 'Maillist', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => AmcWm::t('msgsbase.core', 'User'),
            'email' => AmcWm::t('msgsbase.core', 'E-mail'),            
            'name' => AmcWm::t('msgsbase.core', 'Name'),            
            'verifyCode' => AmcWm::t("amcFront",'Verification Code'),
            'emailRepeat' => AmcWm::t('msgsbase.core', 'E-mail Repeat'),
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
        $criteria->compare('email', $this->email, true);        
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }


    public function generateKey() {
        return md5($this->email . "|" . $this->user_id . "|" . $this->user->ip);
    }

}