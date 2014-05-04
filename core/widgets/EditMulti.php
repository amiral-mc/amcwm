<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * EditMulti extension class,
 * @package AmcWebManager
 * @subpackage Extensions
 * @copyright 2012, Amiral Management Corporation. All Rights Reserved..
 * @author Amiral Management Corporation
 * @version 1.0
 */
class EditMulti extends CWidget {

    public $htmlOptions;
    public $className;
    public $model;
    public $form;
    public $elements;
    public $data;
    public $modelName;
    public $title = "Add";
    public function init() {
        $modelName = $this->modelName;
        $this->model = new $modelName;
        $this->htmlOptions['id'] = $this->getId();
        if ($this->className) {
            $this->htmlOptions['class'] = $this->className;
        }
        parent::init();
    }

    /**
     * Renders the header part.
     */
    public function run() {
        $output = CHtml::openTag("div", $this->htmlOptions);
        $output.='<div style="text-align: right;padding5px;">';
        $output.= Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/add.png", "", array("border" => 0, "align" => 'absmiddle')) . "&nbsp;" . $this->title, "javascript:void(0);", array("id" => "{$this->htmlOptions['id']}_addNew", "class" => "btn_label"));
        $output.='</div>';
        $output .= CHtml::closeTag("div");
        $output.=' <table border="0" cellpadding="2" cellspasing ="0" id="' . $this->getId() . '_table">';
        $table = $this->model->getMetaData()->tableSchema;
        if (count($this->data)) {
            $i = 0;
//            $output.='<tr>';
//            foreach ($this->elements as $elementName => $elementConfig) {
//                $output.='<th>';
//                $output .= $this->form->labelEx($this->model, "$elementName");
//                $output.='</th>';
//            }
//            $output.='</tr>';          
            if(isset($_POST["{$this->htmlOptions['id']}Removed"])){
                foreach ($_POST["{$this->htmlOptions['id']}Removed"] as $removedId) {
                   $output.=CHtml::hiddenField("{$this->htmlOptions['id']}Removed[]", $removedId);
                }
            }
            foreach ($this->data as $dataModel) {
                $trKey = "{$this->htmlOptions['id']}_row_{$i}";
                $key = "{$this->htmlOptions['id']}_{$i}";
                $output.='<tr id="' . $trKey . '">';
                foreach ($this->elements as $elementName => $elementConfig) {
                    switch ($elementConfig['type']) {
                        case 'text':
                            $output.='<td>';
                            //$output .= $this->form->labelEx($dataModel, "[$i]$elementName");
                            $htmlOptions = array('size' => $elementConfig['size'], 'maxlength' => $elementConfig['maxlength']);
                            $output .= $this->form->textField($dataModel, "[$i]$elementName", $htmlOptions);
                            $this->form->error($dataModel, "[$i]$elementName");
                            //$dataModel->$elementName;    
                            $output.='</td>';
                            break;
                    }
                    $output .= $this->form->hiddenField($dataModel, "[$i]{$table->primaryKey}");
                }
                $output.='<td>';
                $output.= Chtml::link(CHtml::image(Yii::app()->baseUrl . "/images/remove.png", "", array("border" => 0, "align" => 'absmiddle')), "javascript:void(0);", array("id" => $key, "onclick" => "EditMulti.removeRow(this.id);", "class" => "btn_label"));
                $output.='</td>';
                $output.='</tr>';
                $i++;
                //switch($row)
            }
        }
        $output.='</table>';
        echo $output;
        Yii::app()->clientScript->registerScript(__CLASS__ . $this->getId(), "    
            EditMulti = {};
            EditMulti.elements = " . CJSON::encode($this->elements) . ";
            EditMulti.elementsLength = " . count($this->elements) . ";        
            $('#{$this->htmlOptions['id']}_addNew').click(function(){    
                EditMulti.addItem();
                return false;
            });            
            EditMulti.addItem = function(){    
                lastRow = ($('#{$this->htmlOptions['id']}_table tr').length -1);
                var row = '<tr id=\"{$this->htmlOptions['id']}_row_'+lastRow+'\">';                    
                
                for(var element in EditMulti.elements ){
                    switch (EditMulti.elements[element].type) {
                        case 'text':
                            row += '<td>';
                            row += '<input name=\"{$this->modelName}['+lastRow+']['+element+']\" id=\"{$this->modelName}_'+lastRow+'_'+element+'\" type=\"text\" value=\"\" size=\"'+EditMulti.elements[element].size+'\" maxlength=\"'+EditMulti.elements[element].maxsize+'\" />';
                            row += '<input name=\"{$this->modelName}['+lastRow+'][{$table->primaryKey}]\" id=\"{$this->modelName}_'+lastRow+'_{$table->primaryKey}\" type=\"hidden\" value=\"\" />';
                            row += '</td>';
                        break;
                    }
                }
                row += '<td>';
                row +='<a id=\"{$this->htmlOptions['id']}_'+lastRow+'\" onclick=\"EditMulti.removeRow(this.id)\" class=\"btn_label\" href=\"javascript:void(0);\"><img border=\"0\" align=\"absmiddle\" src=\"" . Yii::app()->baseUrl . "/images/remove.png\" alt=\"\" />';
                row += '</td>';
                row += '</tr>';
                $('#{$this->htmlOptions['id']}_table').append(row);
            }
            EditMulti.removeRow = function(rowId){                                                
                removeNumber = rowId.substring(" . strlen("{$this->htmlOptions['id']}_") . ");                
                id = parseInt($('#{$this->modelName}_'+removeNumber+'_{$table->primaryKey}').val());                
                if(!isNaN(id) && id){
                   $('#" . $this->form->getId() . "').append('<input type=\"hidden\" name=\"{$this->modelName}Removed[]\" value=\"'+id+'\" />');
                }        
                $('#{$this->htmlOptions['id']}_row_'+removeNumber).html('<td colspan=\"'+ (EditMulti.elementsLength + 1) +'\">&nbsp;</td>');
                $('#{$this->htmlOptions['id']}_row_'+removeNumber).hide();
            }            
");
    }

}
?>
