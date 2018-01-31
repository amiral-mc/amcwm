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
class VotesWidget extends CWidget
{

    /**
     * @var array list of new sticker items.
     */
    public $items = array();

    /**
     * @var array HTML attributes for the menu's root container tag
     */
    public $htmlOptions = array();

    /**
     * @var boolean whether the news items should be HTML-encoded. Defaults to true.
     */
    public $encodeItem = false;

    /**
     * @var string the base script URL for all tickers resources (e.g. javascript, CSS file, images).
     */
    public $baseScriptUrl;

    /**
     * @var int vote result mode  if ==0 then draw vote form else draw vote results
     */
    public $resultMode = 0;
    public $displayResultBar = true;

    /**
     * Form action url
     * @var string 
     */
    public $formAction = '';
    public $resultAction = '';
    public $class = 'voting_widget';
    public $formClass = 'form';
    public $resultClass = 'pollContainer';
    public $parentId;

    /**
     * VoteForm model instance
     * @var VoteForm 
     */
    public $model = NULL;

    /**
     * Initializes the scroller widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init()
    {
        $this->htmlOptions['id'] = $this->getId();
    }

    /**
     * 
     */
    public function run()
    {
        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        if ($this->resultMode) {
            $this->htmlOptions['class'] = $this->resultClass;
            $this->runResults();
        } else {
            $this->htmlOptions['class'] = $this->formClass;
            $this->runForm();
        }
    }

    protected function runResults()
    {
        echo CHtml::openTag("div", $this->htmlOptions);
        echo '<h1>' . $this->items['ques'] . '</h1>';
        if (count($this->items['results']['votes'])) {
            foreach ($this->items['results']['votes'] as $pollOption) {
                $percent = ($this->items['results']['total']) ? round(($pollOption['votes'] * 100) / $this->items['results']['total']) : 0;
                echo '<div class="option">';
                echo '<div><span class="pollques" >' . $pollOption['option'] . '</span>&nbsp;';
                echo '(<em class="pollques">' . $percent . '% &nbsp;' . $pollOption['votes'] . '&nbsp;' . AmcWm::t("amcFront", "Users Votes") . '</em>)</div>';
                if ($this->displayResultBar) {
                    echo '<div class="fullBar">';
                    echo '<div style="width:' . $percent . '%;display:block" class="bar">&nbsp;</div>';
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        echo CHtml::closeTag("div");
    }

    /**
     * Draw form widget
     * @access private 
     * @return string
     */
    protected function runForm()
    {
        if ($this->items) {
            echo CHtml::openTag("div", $this->htmlOptions);
            $form = $this->beginWidget('CActiveForm', array('action' => $this->formAction, 'id' => $this->getId() . "_form"));
            echo CHtml::hiddenField('lang', Controller::getCurrentLanguage(), array('id' => 'vote_lang'));
            //echo $form->errorSummary($this->model);
            echo '<div class="' . $this->class . '">';
            echo '<div class="voting_error">';
            echo $form->error($this->model, 'question');
            echo '</div>';
            echo '<h1>' . $this->items['ques'] . '</h1>';
            echo $form->hiddenField($this->model, 'question', array('value' => $this->items['id']));
            echo '<div class="voting_options">';
            echo $form->radioButtonList($this->model, 'option', $this->items['optionsList'], array('separator' => '', 'template' => "<p>{input} {label}</p>", 'uncheckValue' => NULL));
            echo '</div>';
            echo '<div class="' . $this->class . '_action">';
            echo CHtml::ajaxSubmitButton(AmcWm::t("amcFront", 'Vote'), $this->formAction, array("update" => "#{$this->parentId}"), array('id' => $this->getId() . "_submit"));
            echo CHtml::ajaxLink(AmcWm::t("amcFront", 'Vote Results'), $this->resultAction, array("update" => "#{$this->parentId}"), array('id' => $this->getId() . "_result", 'onclick' => "$('#" . $this->getId() . "_show_form').show();"));
            echo '</div>';
            echo '</div>';
            $this->endWidget();

            echo CHtml::closeTag("div");
        }
    }

}
