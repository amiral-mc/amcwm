<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */
class Keywards extends CWidget {

    public $htmlOptions;
    public $attribute;
    public $model;
    public $name;
    public $values;
    public $formId;
    public $container = "keywordTags";
    public $delimiter = "\n\r";
    public $elements = array("min" => 3, "max" => 6);
    public $wordsCount = array("min"=>3, "max"=>4);
    
    private $data = array();
    private $tmpAttr = array();

    public function init() {
        $this->tmpAttr["min"] = $this->elements["min"]; 
        $this->tmpAttr["max"] = $this->elements["max"];         
        if ($this->name !== null)
            $this->name = $this->name;
        else if (isset($this->htmlOptions['name']))
            $this->name = $this->htmlOptions['name'];
        else if ($this->hasModel())
            $this->name = CHtml::activeName($this->model, $this->attribute);
        else
            throw new CException(Yii::t('zii', '{class} must specify "model" and "attribute" or "name" property values.', array('{class}' => get_class($this))));

        $this->htmlOptions["style"] = "margin:2px;";

        if ($this->values) {
            $this->data = explode($this->delimiter, $this->values);
            
            $this->elements["min"] = (count($this->data) > $this->elements["min"] && count($this->data) < $this->elements["max"])?count($this->data):$this->elements["min"];
        }

        parent::init();
    }

    /**
     * Renders the header part.
     */
    public function run() {
        $kewordItem = CHtml::openTag("div", array("id"=>$this->container));
//        $kewordItem .= CHtml::tag("br");
        $kewordItem .= CHtml::openTag("div", array('style'=>'padding-bottom:8px;'));
        $kewordItem .= AmcWm::t("amcBack", "Please type at least {min}, with max {max} words on each of the following 2 boxs."
                        , array("{min}"=>$this->wordsCount["min"], "{max}"=>$this->wordsCount["max"])
                        );
        $kewordItem .= CHtml::closeTag("div");
        
        if (is_array($this->elements)) {
            $elements = $this->elements;
            if (isset($elements["min"])) {
                $count = (count($this->data)>$elements["min"])?count($this->data):$elements["min"];
                for ($i = 0; $i < $count; $i++) {

                    if(!isset ($this->data[$i])){
                        $value = "";
                    }else{
                        $value = $this->data[$i];
                    }
                    $this->htmlOptions['id'] = $this->getId() . $i;
                    $this->htmlOptions['maxlength'] = "100";
                    $this->htmlOptions['onKeyup'] = "keywordAction.isCharAllowed(this);";
                    $this->htmlOptions['class'] = "keywordsTxtItems";
                    
                    $kewordItem .= CHtml::openTag("div", array("id"=>"keyword_row_{$i}"));
                    //$kewordItem .= CHtml::tag("br");
                    $kewordItem .= ($i<$this->tmpAttr["min"])?"<span class='required'>*</span>":"";
                    $kewordItem .= CHtml::textField($this->name, $value, $this->htmlOptions);
                    $kewordItem .= ($i>=$this->tmpAttr["min"])?'&nbsp;'. Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/remove.png", "", array("border" => 0, "align" => 'absmiddle')),"javascript:void(0);" ,array("id"=>"row_{$this->htmlOptions['id']}","onclick" => "keywordAction.removeKeyword({$i})", "class" => "btn_label")):"";
                    $kewordItem .= CHtml::closeTag("div");
                    
                }
            }
            
            $kewordItem .= CHtml::closeTag("div");
//            $kewordItem .= CHtml::tag("br");
            
            $addBtnStyle = (count($this->data) >= $elements["max"])?"display:none":"";
            $kewordItem .= Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add.png", "", array("border" => 0, "align" => 'absmiddle')) . "&nbsp;" . AmcWm::t("amcBack", "Add more keywords"), "javascript:void(0);", array("id" => "addNewKeyword", "class" => "btn_label", "style"=>"clear:both; $addBtnStyle"));
            
//            $kewordItem .= CHtml::tag("br");
            $kewordItem .= "<div style='padding-top:4px;'>". AmcWm::t("amcBack", "The maximum available number of sentences {max} fields", array("{max}"=>$elements["max"])) ."</div>";
            echo $kewordItem;
            //echo "<input onkeyup='alert((window.event)?window.event.keyCode:event.which);' />";
        }


        Yii::app()->clientScript->registerScript('initKeywords', "
             var keywordAction = {
                isCharAllowed : function(element){
                    //[0-9a-zA-Z !@#$%&*()\-_+=|:;?.,] 
                    var notAllowed = /[@#$%&*()\-_+=|:;?.,،,\"\'\؟/]/g;
                    if (notAllowed.test(element.value)) {
                        oldvalue = element.value.replace(notAllowed, '');
                        element.value = oldvalue;
                    }
                    
                    elementContent = element.value.split(' ');
                    if(elementContent.length > {$this->wordsCount["max"]}){
                        alert('".AmcWm::t("amcBack", "maximum {max} words allowed", array("{max}"=>$this->wordsCount["max"]))."');
                        element.value = '';
                    }
                }
            }
        ", CClientScript::POS_HEAD);

        Yii::app()->clientScript->registerScript('addNewKeyword', "
                
            $('#addNewKeyword').click(function(){  
                ".( ($this->elements["min"])?"
                keywordItems = $('input:text[name^=\"{$this->name}\"]');    
                keywordNumbers = keywordItems.length;
                if((keywordNumbers+1) == {$this->tmpAttr["max"]})
                    $('#addNewKeyword').hide();
                keywordAction.addKeyword(keywordNumbers);
                ":"keywordAction.addKeyword(1);")."
                return false;
            });
                
            keywordAction.addKeyword = function(keywordNumber){
                var keywordRow = '<div class=\"row\" id=\"keyword_row_'+keywordNumber+'\" style=\"{$this->htmlOptions["style"]}\">';
                keywordRow += '<input class=\"extraKeywords\" maxlength=\"100\" name=\"{$this->name}\" id=\"{$this->getId()}'+keywordNumber+'\" type=\"text\" onkeyup=\"keywordAction.isCharAllowed(this);\" value=\"\" />';
                keywordRow += '&nbsp;<a id=\"row'+keywordNumber+'\" onclick=\"keywordAction.removeKeyword('+keywordNumber+')\" class=\"btn_label\" href=\"javascript:void(0);\"><img border=\"0\" align=\"absmiddle\" src=\"" . Yii::app()->baseUrl . "/images/remove.png\" alt=\"\" /></a>';
                keywordRow += '</div>';
                $('#{$this->container}').append(keywordRow);
            }
            
            keywordAction.removeKeyword = function(keywordNumber){
                ".( ($this->elements["min"])?"
                keywordItems = $('input:text[name^=\"{$this->name}\"]');
                keywordNumbers = keywordItems.length;
                if((keywordNumbers-1) < {$this->tmpAttr["max"]}){
                    $('#addNewKeyword').show('fast');
                }
                $('#keyword_row_'+keywordNumber).remove();
                ":"$('#keyword_row_'+keywordNumber).remove();")."
            }

            
            $('#{$this->formId}').bind('submit', function(e){
                var formElementTitle = $('input:text[name*=\"header\"]').val();
                var gotErrors = false;
                $('.keywordsTxtItems').each(function(i, attrib){
                    if($('#' + attrib.id).val() == ''){
                        e.preventDefault();
                        $('#' + attrib.id).addClass('error');
                        $('#' + attrib.id).focus();
                        gotErrors = true;
                        alert('".AmcWm::t("amcBack", "Please type at least {min} words in each box", array("{min}"=>$this->wordsCount["min"]))."');
                        return false;
                    }else{
                        var elementContent = document.getElementById(attrib.id).value;
                        var elementContentArray = elementContent.split(' ');
                        if(elementContentArray.length < {$this->wordsCount["min"]}){
                            e.preventDefault();
                            alert('".AmcWm::t("amcBack", "minimum {min} words allowed", array("{min}"=>$this->wordsCount["min"]))."');
                            $('#' + attrib.id).addClass('error');
                            $('#' + attrib.id).focus();
                            gotErrors = true;
                            return false;
                        }else{
                            $('#' + attrib.id).removeClass('error');
                        }
                        
                        if(elementContent == formElementTitle){
                            e.preventDefault();
                            alert('".AmcWm::t("amcBack", "Keyword cant contain the same title")."');
                            $('#' + attrib.id).addClass('error');
                            $('#' + attrib.id).focus();
                            gotErrors = true;
                            return false;
                        }
                    }
                });
                if(!gotErrors){
                    $('.extraKeywords').each(function(i, attrib){
                        var elementContent = document.getElementById(attrib.id).value;
                        var elementContentArr = elementContent.split(' ');
                        if(elementContentArr.length < {$this->wordsCount["min"]}){
                            e.preventDefault();
                            alert('".AmcWm::t("amcBack", "minimum {min} words allowed", array("{min}"=>$this->wordsCount["min"]))."');
                            $('#' + attrib.id).addClass('error');
                            $('#' + attrib.id).focus();
                            return false;
                        }else{
                            $('#' + attrib.id).removeClass('error');
                        }

                        if(elementContent == formElementTitle){
                            e.preventDefault();
                            alert('".AmcWm::t("amcBack", "Keyword cant contain the same title")."');
                            $('#' + attrib.id).addClass('error');
                            $('#' + attrib.id).focus();
                            return false;
                        }

                    });
                }
                return true;
            });

           
            
            "
            // , CClientScript::POS_HEAD
            );
//        parent::run();
    }

    /**
     * @return boolean whether this widget is associated with a data model.
     */
    protected function hasModel() {
        return $this->model instanceof CModel && $this->attribute !== null;
    }

}

?>
