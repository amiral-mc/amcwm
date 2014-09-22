<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * This is the model class for table "writers".
 *
 * The followings are the available columns in table 'writers':
 * @property string $writer_id
 * @property integer $writer_type
 *
 * The followings are the available model relations:
 * @property Articles[] $articles
 * @property NewsEditors[] $news
 * @property Persons $person
 * 
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Writers extends ActiveRecord {

    const REF_PAGE_SIZE = 30;
    const BOTH_TYPE = 1;
    const WRITER_TYPE = 2;
    const EDITOR_TYPE = 3;
    /**
     * Returns the static model of the specified AR class.
     * @return Writers the static model class
     */
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'writers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('writer_type', 'required'),
            array('writer_type', 'numerical', 'integerOnly' => true),
            array('writer_id', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('writer_id, writer_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'articles' => array(self::HAS_MANY, 'Articles', 'writer_id'),
            'news' => array(self::HAS_MANY, 'NewsEditors', 'editor_id'),
            'person' => array(self::BELONGS_TO, 'Persons', 'writer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'writer_id' => 'Writer',
            'writer_type' => AmcWm::t("msgsbase.core", 'Writer Type'),
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

        $criteria->compare('writer_id', $this->writer_id, true);
        $criteria->compare('writer_type', $this->writer_type);

        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
        ));
    }   
    
    /**
     * Get writer type label
     * @access public
     * @return string
     */
    public function getWriterTypeLabel() {
        $types = AmcWm::t("msgsbase.core", 'writersLabels');
        return $types[$this->writer_type];
    }
     
    /**
     * Get editors list 
     * @return array
     * @access public 
     */
    static public function getEditorsList($keywords = null, $pageNumber = 1, $prompt = null) {
        return self::getWritersEditorsList(self::EDITOR_TYPE, $keywords, $pageNumber, $prompt);
    }
    
    /**
     * Get writers list 
     * @return array
     * @access public 
     */
    static public function getWritersList($keywords = null, $pageNumber = 1, $prompt = null) {
        return self::getWritersEditorsList(self::WRITER_TYPE, $keywords, $pageNumber, $prompt);
    }
    
    /**
     * Get writers/editors list
     * @return array
     * @access public
     */
    static public function getEditorsWritersList($keywords = null, $pageNumber = 1, $prompt = null) {
        return self::getWritersEditorsList(self::BOTH_TYPE, $keywords, $pageNumber, $prompt);
    }
    
    /**
     * Fetch writers/editors data
     * @return array
     * @access public
     */
    static protected function getWritersEditorsList($type = Writers::BOTH_TYPE, $keywords = null, $pageNumber = 1, $prompt = null, $includeEmails = false) {
        if (!$pageNumber) {
            $pageNumber = 1;
        }
        $queryWhere = null;
        $pageNumber = (int) $pageNumber;
        $keywords = trim($keywords);
        $queryCount = "SELECT count(*) FROM writers t
        inner join persons p on t.writer_id = p.person_id
        inner join persons_translation pt on p.person_id = pt.person_id
        ";
        $command = AmcWm::app()->db->createCommand();
        $command->select("t.writer_id, p.email, pt.name");
        $command->from = "writers t";
        $command->join("persons p", 't.writer_id = p.person_id');
        $command->join("persons_translation pt", 'p.person_id = pt.person_id');
        $where ="writer_type in (". $type . ", ". self::BOTH_TYPE .")"; 
        $where .= sprintf(" and pt.content_lang = %s", AmcWm::app()->db->quoteValue(Controller::getContentLanguage()));        
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
            if ($row['email'] && $includeEmails) {
                $label .= " [{$row['email']}]";
            }
            $list['records'][] = array("id" => $row['writer_id'], "text" => $label);
        }
        $list['total'] = AmcWm::app()->db->createCommand($queryCount)->queryScalar();
        return $list;
    }

}