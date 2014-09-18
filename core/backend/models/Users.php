<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
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
 * @property UsersAccessRights[] $usersAccessRights
 * @property UsersLog[] $usersLogs
 * @property Videos[] $videoses
 * @property Voters[] $voters
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Users extends ActiveRecord {

    const REF_PAGE_SIZE = 30;

    /**
     *
     * @var current person name 
     */
    public $name;

    /**
     *
     * @var current person email 
     */
    public $email;

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
            array('role_id, username, passwd', 'required'),
            array('role_id, published, is_system', 'numerical', 'integerOnly' => true),
            array('user_id', 'length', 'max' => 10),
            array('username', 'length', 'max' => 65, 'min' => 5),
            array('username', 'UserCharacters'),
            array('username', 'UserExist', 'errorMessage' => 'Username already exist, please choose another username'),
            array('passwd', 'length', 'max' => 32, 'min' => 8),
            array('username', 'VaildateUser', 'on' => 'update', 'errorMessage' => AmcWm::t("msgsbase.core", 'Can not change your Username')),
            array('role_id', 'VaildateSystemUser', 'on' => 'update', 'errorMessage' => AmcWm::t("msgsbase.core", 'Can not change system user')),
            array('is_system', 'default',
                'value' => 0,
                'setOnEmpty' => false, 'on' => 'insert'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, username, role_id, passwd, published, is_system', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Validate user name, user cannot chage his/her username
     * @param string $attribute
     * @param array $params
     */
    public function VaildateUser($attribute, $params) {
        $userInfo = AmcWm::app()->user->getInfo();
        $currentUser = $this->findByPk($this->user_id);
        if ($userInfo['username'] == $currentUser->$attribute && $currentUser->$attribute != $this->$attribute) {
            $this->addError($attribute, AmcWm::t("msgsbase.core", 'Can not change your Username'));
        }
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
            'user_id' => AmcWm::t("msgsbase.core", 'ID'),
            'username' => AmcWm::t("msgsbase.core", 'Username'),
            'name' => AmcWm::t("msgsbase.core", 'Name'),
            'email' => AmcWm::t("msgsbase.core", 'Email'),
            'role_id' => AmcWm::t("msgsbase.core", 'Role'),
            'passwd' => AmcWm::t("msgsbase.core", 'Password'),
            'published' => AmcWm::t("msgsbase.core", 'Published'),
            'is_system' => 'Is System User',
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
        $criteria->compare('published', $this->published);
        $criteria->compare('is_system', $this->is_system);
        $criteria->compare('pt.name', $this->person->getCurrent()->name);
        $criteria->compare('p.email', $this->person->email);
        $criteria->select = "t.user_id, t.username , t.role_id, t.published, t.is_system, pt.name , p.email";
        $criteria->join .=" inner join persons p on t.user_id = p.person_id";
        $criteria->join .= sprintf(" left join persons_translation pt on p.person_id = pt.person_id and pt.content_lang = %s", Yii::app()->db->quoteValue(Controller::getContentLanguage()));
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Get users roles
     * @access public 
     * @param int $roleId
     * @return array
     */
    public static function getUsersRoles($roleId = null) {
        $rolesNotEncluded = array();
        $rolesNotEncluded[] = (int) AmcWm::app()->acl->getRoleId();
        if ($roleId)
            $rolesNotEncluded[] = $roleId;
        $query = sprintf('select role_id, role from roles where role_id NOT IN (%s) ', implode(',', $rolesNotEncluded));
        $roles = CHtml::listData(Yii::app()->db->createCommand($query)->queryAll(), 'role_id', 'role');
        return $roles;
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * @access public
     * @return void
     */
    protected function afterFind() {
        $this->displayTitle = $this->username;
        parent::afterFind();
    }

    /**
     * This method is invoked after saving a record successfully.
     * The default implementation raises the {@link onAfterSave} event.
     * You may override this method to do postprocessing after record saving.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    public function afterSave() {
        if (!$this->isNewRecord) {
            if ($this->oldAttributes['role_id'] != $this->role_id && !$this->is_system) {
                $query = sprintf('delete from users_access_rights where user_id = %d', $this->user_id);
                Yii::app()->db->createCommand($query)->execute();
            }
        }
        parent::afterSave();
    }

    /**
     * This method is invoked after each record has been saved
     * @access protected
     * @return boolean
     */
    public function beforeSave() {
        if (parent::beforeSave()) {
            $userParams = Yii::app()->request->getParam('Users');
            if (isset($userParams['passwd']) && trim($userParams['passwd']) != '') {
                $this->setAttribute('passwd', md5($this->passwd));
            }
        }
        return true;
    }

    /**
     * Set permissions for the current user
     * @access public
     * @param array $permissions 
     * @return boolean return true if success;
     */
    public function setPermissions($permissions = array()) {
        $query = sprintf('delete from users_access_rights where user_id = %d', $this->user_id);
        Yii::app()->db->createCommand($query)->execute();
        $success = true;
        if (is_array($permissions)) {
            $queries = array();
            $accessControllers = amcwm::app()->acl->getAccessControllers($this->role_id);
            $accessQuery = 'insert into users_access_rights(user_id, role_id, controller_id, access) values ';
            foreach ($accessControllers as $controllerId => $controller) {
                if ($controller['visible']) {
                    if (array_key_exists($controllerId, $permissions)) {
                        $access = array_sum(array_unique($permissions[$controllerId]));
                        if ($access != $controller['access']) {
                            $queries[] = sprintf('(%d, %d, %d, %d)', $this->user_id, $controller['role_id'], $controllerId, $access);
                        }
                    } else {
                        if ($controller['access']) {
                            $queries[] = sprintf('(%d, %d, %d, %d)', $this->user_id, $controller['role_id'], $controllerId, 0);
                        }
                    }
                    unset($permissions[$controllerId]);
                }
            }
        }
        if (count($queries)) {
            $success = Yii::app()->db->createCommand($accessQuery . "\n" . implode(",\n", $queries) . ";")->execute();
        }
        return $success;
    }

    /**
     * Get users list
     * @return array
     * @access public
     */
    static public function getUsersList($keywords = null, $pageNumber = 1, $prompt = null) {
        if (!$pageNumber) {
            $pageNumber = 1;
        }
        $queryWhere = null;
        $pageNumber = (int) $pageNumber;
        $keywords = trim($keywords);
        $queryCount = "SELECT count(*) FROM users u
        inner join persons p on u.user_id = p.person_id
        inner join persons_translation pt on p.person_id = pt.person_id
        ";
        $command = AmcWm::app()->db->createCommand();
        $command->select("u.user_id, p.email, pt.name");
        $command->from = "users u";
        $command->join("persons p", 'u.user_id = p.person_id');
        $command->join("persons_translation pt", 'p.person_id = pt.person_id');
        $where = sprintf("pt.content_lang = %s", AmcWm::app()->db->quoteValue(Controller::getContentLanguage()));
        if ($keywords) {
            $keywords = "%{$keywords}%";
            $where .= sprintf("
                    and (name like %s 
                    or email like %s) 
                    "
                    , AmcWm::app()->db->quoteValue($keywords)
                    , AmcWm::app()->db->quoteValue($keywords)
            );
        }
        $command->where($where);
        $queryCount.=" where {$where}";
        $command->limit(self::REF_PAGE_SIZE, self::REF_PAGE_SIZE * ($pageNumber - 1));
        $data = $command->queryAll();
        $list = array('records' => array(), 'total' => 0);
        if ($prompt) {
            $list['records'][] = array("id" => null, "text" => $prompt);
        }
        foreach ($data as $row) {
            $label = "[{$row['name']}]";
            if ($row['email']) {
                $label .= " [{$row['email']}]";
            }
            $list['records'][] = array("id" => $row['user_id'], "text" => $label);
        }
        $list['total'] = AmcWm::app()->db->createCommand($queryCount)->queryScalar();
        return $list;
    }

}
