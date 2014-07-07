<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Votes {

    /**
     *
     * @var string inner widget used for results and form 
     */
    protected $innerWidget = '';
    /**
     * The dataset of current active vote 
     * @var Array
     * @static
     * @access protected
     */
    protected $data = array();

    /**
     * Controller $controller the controller object that called this class
     * @var Controller 
     * @static
     * @access protected
     */
    protected $controller;

    /**
     * The Votes instance.
     * @var Votes
     * @static
     * @access protected
     */
    protected static $_instance = null;

    /**
     * VoteForm instance
     * @var VoteForm 
     * @access protected
     */
    protected $voteForm;

    /**
     * Votes widget dom id
     * @var string 
     * @access protected
     */
    protected $id;

    /**
     * @todo explain the query
     * Constructor, this Votes implementation is a Singletone.
     * You should not call the constructor directly, but instead call the static factory method Votes.getInstance().<br />
     * @param Controller $controller the controller object that called this class
     * @access protected
     * @throws Error Error if you call the constructor directly or PDO could not connect to MySQL server
     */
    protected function __construct(Controller $controller, $innerWidget = 'amcwm.core.votes.VotesWidget', $id = "votes") {
        $this->innerWidget = $innerWidget;
        $date = date("Y-m-d H:i:s");
        $this->id = $id;
        $this->voteForm = new VoteForm();
        $this->controller = $controller;
        $siteLanguage = Yii::app()->user->getCurrentLanguage();
        $query = sprintf("select tt.ques, t.ques_id id , suspend
         from votes_questions t
         inner join votes_questions_translation tt on t.ques_id = tt.ques_id
         where t.published = 1 
         and t.publish_date <='$date' 
         and tt.content_lang = %s
         and (t.expire_date >=NOW() or t.expire_date is null)                     
         order by creation_date desc limit 0, 1
        ", Yii::app()->db->quoteValue($siteLanguage));
        $question = Yii::app()->db->createCommand($query)->queryRow();
        if (count($question)) {
            $query = sprintf("select o.value, o.option_id
            from votes_options o 
            where o.ques_id =%d and o.content_lang = %s
        ", $question['id'], Yii::app()->db->quoteValue($siteLanguage));
            $question['options'] = Yii::app()->db->createCommand($query)->queryAll();
            if (count($question['options']) > 1) {
                $i = 0;
                foreach ($question['options'] as $option) {
                    $question['optionsList'][$option['option_id']] = $option['value'];
                    $i++;
                }
                $this->data = $question;
                $quesModel = VotesQuestionsTranslation::model()->findByPk(array("ques_id" => $question['id'], 'content_lang' => $siteLanguage));
                $this->data['results'] = $quesModel->getResults();
            }
        }
    }

    /**
     * Votes.data getter methods
     * @access public
     * @return array 
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Save vote
     * @access public
     * @return boolean 
     */
    public function save() {
        if (isset($_POST['VoteForm']) && isset($_POST['VoteForm']['option'])) {
            $this->voteForm->attributes = $_POST['VoteForm'];
            $voter = new Voters();
            $voterOptions['option_id'] = $_POST['VoteForm']['option'];
            $voterOptions['content_lang'] = Yii::app()->user->getCurrentLanguage();
            if (!Yii::app()->user->isGuest) {
                $user = Yii::app()->user->getInfo();
                $voterOptions['user_id'] = $user['user_id'];
            }
            $voter->attributes = $voterOptions;
            $voter->questionId = $this->data['id'];
            if ($voter->validate() && !$this->data['suspend'] && !$this->isVoted()) {
                if ($voter->save()) {
                    if (!isset($voterOptions['user_id'])) {
                        $cookie = new CHttpCookie("votes_{$voter->options->ques_id}", $voter->options->ques_id);
                        $cookie->expire = time() + 60 * 60 * 24 * 360 * 100;
                        Yii::app()->request->cookies["votes_{$voter->options->ques_id}"] = $cookie;
                    }
                    $this->data['results']['total'] = $this->data['results']['total'] + 1;
                    $this->data['results']['votes'][$voterOptions['option_id']]['votes'] = $this->data['results']['votes'][$voterOptions['option_id']]['votes'] + 1;
                    $this->viewResults(1);
                }
            } else {
                $errors = $voter->getErrors();
                foreach ($errors as $errorAttr) {
                    foreach ($errorAttr as $error) {
                        $this->voteForm->addError('question', AmcWm::t("amcFront", $error));
                    }
                }
                $this->viewResults();
            }
        } else {
            $this->voteForm->addError('question', AmcWm::t("amcFront", "Please choose your answer."));
            $this->viewResults();
        }
        Yii::app()->end();
    }

    /**
     * Factory Votes method.
     * @static
     * @param Controller $controller the controller object that called this class
     * @param string $innerWidget inner widget used for results and form
     * @access public
     * @return Votes the Singleton instance of the Votes
     */
    public static function &getInstance(Controller $controller, $innerWidget = 'amcwm.core.votes.VotesWidget') {
        if (self::$_instance == NULL) {
            self::$_instance = new self($controller, $innerWidget);
        }
        return self::$_instance;
    }

    /**
     * View current vote form.
     * If user is already voted then draw vote results
     * @param boolean $resultMode
     * @param boolwan $return return result if equal true otherwise print the result
     * @access public
     * @return string
     */
    public function viewResults($resultMode = false, $return = false, $displayResultBar = true) {
        $output = null;
        if (count($this->data)) {
            if ($this->data['suspend']) {
                $resultMode = true;
            } else if (!$resultMode) {
                $resultMode = $this->isVoted();
            }

            $voteOptions = array(
                'id' => $this->id . "_inside",
                'parentId' => $this->id,
                'model' => $this->voteForm,
                'class' => 'voting_widget',
                'displayResultBar'=>$displayResultBar,
                'resultClass' => 'pollContainer',
                'formClass' => 'form',
                'resultMode' => $resultMode,
                'formAction' => array('/site/vote', 'lang' => Controller::getCurrentLanguage()),
                'resultAction' => array('/site/voteResults', 'lang' => Controller::getCurrentLanguage()),
                'items' => $this->data,
            );
            if($return){
               $output =  $this->controller->widget($this->innerWidget, $voteOptions, true);
            }
            else{
                $this->controller->widget($this->innerWidget, $voteOptions);
            }            
        }
        return $output;
    }

    /**
     * check if user vote on the active poll or not
     * @access public
     * @return boolean
     */
    public function isVoted() {
        if (Yii::app()->user->isGuest) {
            $voted = isset(Yii::app()->request->cookies["votes_{$this->data['id']}"]);
        } else {
            $user = Yii::app()->user->getInfo();
            $votedQuery = sprintf("select user_id from voters v
                inner join votes_options o on o.option_id = v.option_id
                inner join votes_questions q on q.ques_id = o.ques_id
                where v.user_id = %d and q.ques_id = %d limit 0 ,1", $user['user_id'], $this->data['id']);
            $voted = Yii::app()->db->createCommand($votedQuery)->queryScalar();
        }
        return $voted;
    }

    /**
     * View current vote.
     * If user is already voted then draw vote results
     * @param boolwan $return return result if equal true otherwise print the result
     * @access public
     * @return string
     */
    public function view($return = false, $displayResultBar = true) {
        $returnOutput = null;
        $output = CHtml::openTag('div', array('id' => $this->id));
        $output .= $this->viewResults(false, $return , $displayResultBar);
        $output .= CHtml::closeTag('div');
        if (count($this->data)) {
            $output .= '<div class="voting_widget_action" style="display:none;" id="' . $this->id . '_inside_show_form">';
            $output .= CHtml::ajaxLink(AmcWm::t("amcFront", 'Vote'), array('site/vote', 'view' => 1, 'lang' => Controller::getCurrentLanguage()), array("update" => "#{$this->id}"), array('id' => $this->id . "_inside_show_form_link", 'onclick' => "$('#" . $this->id . "_inside_show_form').hide();"));
            $output .= '</div>';
        } else {
            $output .= '<div class="no_poll">';
            $output .= AmcWm::t("amcFront", 'No active poll has been found.');
            $output .= '</div>';
        }
        if ($return) {
            $returnOutput = $output;
        } else {
            echo $output;
        }
        return $returnOutput;
    }

}

?>
